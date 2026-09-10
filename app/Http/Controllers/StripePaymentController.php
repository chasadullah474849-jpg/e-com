<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StripePayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Stripe\Exception\SignatureVerificationException;
use Stripe\StripeClient;
use Stripe\Webhook;
use Symfony\Component\HttpFoundation\Response;
use UnexpectedValueException;

class StripePaymentController extends Controller
{
    /**
     * Display checkout form.
     */
    public function checkout(Product $product): View
    {
        abort_unless(
            $product->price > 0,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            'This product does not have a valid price.'
        );

        return view('payments.checkout', [
            'product' => $product,
            'stripeKey' => config('services.stripe.key'),
            'currency' => strtolower(
                config('services.stripe.currency', 'usd')
            ),
        ]);
    }

    /**
     * Create the Stripe PaymentIntent on the server.
     */
    public function createPaymentIntent(
        Request $request,
        Product $product
    ): JsonResponse {
        $validated = $request->validate([
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],
            'customer_email' => [
                'required',
                'email',
                'max:255',
            ],
        ]);

        abort_unless(
            $product->price > 0,
            Response::HTTP_UNPROCESSABLE_ENTITY,
            'Invalid product price.'
        );

        /*
         * Stripe requires amounts in the smallest currency unit.
         *
         * For USD:
         * $10.00 becomes 1000 cents.
         *
         * Product price is read from the database.
         * Never accept the payment amount directly from JavaScript.
         */
        $amount = (int) round(
            ((float) $product->price) * 100
        );

        $currency = strtolower(
            config('services.stripe.currency', 'usd')
        );

        if ($amount < 50 && $currency === 'usd') {
            return response()->json([
                'message' => 'The minimum USD payment is $0.50.',
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $stripe = new StripeClient(
                config('services.stripe.secret')
            );

            $intent = $stripe->paymentIntents->create([
                'amount' => $amount,
                'currency' => $currency,

                // Only allow card payments
                'payment_method_types' => ['card'],

                'receipt_email' => $validated['customer_email'],

                'description' => sprintf(
                    'Payment for product: %s',
                    $product->name
                ),

                'metadata' => [
                    'product_id' => (string) $product->id,
                    'product_name' => (string) $product->name,
                    'user_id' => (string) (
                        auth()->id() ?? ''
                    ),
                ],
            ], [
                /*
                 * Prevent accidental duplicate PaymentIntents
                 * if the request is retried.
                 */
                'idempotency_key' => sprintf(
                    'product_%s_user_%s_%s',
                    $product->id,
                    auth()->id() ?? 'guest',
                    $request->session()->getId()
                ),
            ]);

            StripePayment::updateOrCreate(
                [
                    'stripe_payment_intent_id' => $intent->id,
                ],
                [
                    'user_id' => auth()->id(),
                    'product_id' => $product->id,
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'amount' => $amount,
                    'currency' => $currency,
                    'status' => $intent->status,
                    'metadata' => [
                        'product_name' => $product->name,
                    ],
                ]
            );

            return response()->json([
                'clientSecret' => $intent->client_secret,
                'paymentIntentId' => $intent->id,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Stripe PaymentIntent creation failed', [
                'message' => $exception->getMessage(),
                'product_id' => $product->id,
            ]);

            return response()->json([
                'message' => 'Unable to start payment. Please try again.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Return page after Stripe confirmation.
     *
     * The webhook remains the authoritative source for payment status.
     */
    public function success(Request $request): View
    {
        $request->validate([
            'payment_intent' => [
                'required',
                'string',
                'starts_with:pi_',
            ],
        ]);

        $paymentIntentId = $request->string(
            'payment_intent'
        )->toString();

        $payment = StripePayment::where(
            'stripe_payment_intent_id',
            $paymentIntentId
        )->firstOrFail();

        try {
            $stripe = new StripeClient(
                config('services.stripe.secret')
            );

            $intent = $stripe->paymentIntents->retrieve(
                $paymentIntentId,
                []
            );

            $payment->update([
                'status' => $intent->status,
                'stripe_payment_method_id' =>
                    is_string($intent->payment_method)
                        ? $intent->payment_method
                        : null,
                'paid_at' =>
                    $intent->status === 'succeeded'
                        ? now()
                        : $payment->paid_at,
            ]);
        } catch (\Throwable $exception) {
            Log::error('Stripe payment retrieval failed', [
                'message' => $exception->getMessage(),
                'payment_intent' => $paymentIntentId,
            ]);
        }

        $payment->refresh();

        return view('payments.success', [
            'payment' => $payment,
        ]);
    }

    /**
     * Process Stripe webhook events.
     */
    public function webhook(Request $request): Response
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $webhookSecret = config(
            'services.stripe.webhook_secret'
        );

        if (!$signature || !$webhookSecret) {
            return response(
                'Webhook configuration missing.',
                Response::HTTP_BAD_REQUEST
            );
        }

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                $webhookSecret
            );
        } catch (UnexpectedValueException $exception) {
            Log::warning('Invalid Stripe webhook payload', [
                'message' => $exception->getMessage(),
            ]);

            return response(
                'Invalid payload.',
                Response::HTTP_BAD_REQUEST
            );
        } catch (SignatureVerificationException $exception) {
            Log::warning('Invalid Stripe webhook signature', [
                'message' => $exception->getMessage(),
            ]);

            return response(
                'Invalid signature.',
                Response::HTTP_BAD_REQUEST
            );
        }

        $intent = $event->data->object;

        switch ($event->type) {
            case 'payment_intent.succeeded':
                StripePayment::where(
                    'stripe_payment_intent_id',
                    $intent->id
                )->update([
                    'status' => 'succeeded',
                    'stripe_payment_method_id' =>
                        is_string($intent->payment_method)
                            ? $intent->payment_method
                            : null,
                    'failure_message' => null,
                    'paid_at' => now(),
                ]);
                break;

            case 'payment_intent.payment_failed':
                StripePayment::where(
                    'stripe_payment_intent_id',
                    $intent->id
                )->update([
                    'status' => 'payment_failed',
                    'failure_message' =>
                        $intent->last_payment_error?->message
                        ?? 'Payment failed.',
                ]);
                break;

            case 'payment_intent.canceled':
                StripePayment::where(
                    'stripe_payment_intent_id',
                    $intent->id
                )->update([
                    'status' => 'canceled',
                ]);
                break;
        }

        return response(
            'Webhook received.',
            Response::HTTP_OK
        );
    }
}
