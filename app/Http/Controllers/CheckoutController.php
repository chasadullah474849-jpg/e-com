<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\StripeClient;
use Stripe\Webhook;

class CheckoutController extends Controller
{
    public function index(): View|RedirectResponse
    {
        $summary = $this->cartSummary();

        if (empty($summary['items'])) {
            session()->forget('cart');

            return redirect('/')
                ->with('error', 'Your cart is empty.');
        }

        return view('checkout', [
            'cartItems' => $summary['items'],
            'subtotal' => $summary['subtotal'],
            'shipping' => $summary['shipping'],
            'total' => $summary['total'],
            'stripeKey' => config('services.stripe.key'),
            'stripeCurrency' => strtolower(
                config('services.stripe.currency', 'usd')
            ),
        ]);
    }

    public function login(): RedirectResponse
    {
        return redirect()->route('checkout');
    }

    public function process(Request $request): RedirectResponse
    {
        $validated = $this->validateCheckout($request, false);

        if (
            $validated['payment_method']
            !== 'cash_on_delivery'
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Please use the secure Stripe card form.'
                );
        }

        try {
            $summary = $this->cartSummary();

            if (empty($summary['items'])) {
                return redirect('/')
                    ->with('error', 'Your cart is empty.');
            }

            $orderId = $this->createOrder(
                $validated,
                $summary,
                'pending',
                null
            );

            session()->forget('cart');
            session()->flash('order_id', $orderId);

            return redirect()
                ->route('order.success')
                ->with(
                    'success',
                    'Order placed successfully. Payment will be collected on delivery.'
                );
        } catch (\Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to place order: '
                    . $exception->getMessage()
                );
        }
    }

    public function createPaymentIntent(
        Request $request
    ): JsonResponse {
        $validated = $this->validateCheckout(
            $request,
            true
        );

        $summary = $this->cartSummary();

        if (empty($summary['items'])) {
            return response()->json([
                'message' => 'Your cart is empty.',
            ], 422);
        }

        $stripeSecret = (string) config(
            'services.stripe.secret'
        );

        if (
            empty($stripeSecret)
            || !str_starts_with($stripeSecret, 'sk_')
        ) {
            return response()->json([
                'message' =>
                    'Stripe secret key is not configured.',
            ], 500);
        }

        try {
            $checkoutToken = (string) Str::uuid();

            $orderId = $this->createOrder(
                $validated,
                $summary,
                'pending',
                $checkoutToken
            );

            $currency = strtolower(
                (string) config(
                    'services.stripe.currency',
                    'usd'
                )
            );

            /*
             * Stripe expects the smallest currency unit.
             * Example:
             * USD 35.00 becomes 3500 cents.
             */
            $amount = (int) round(
                $summary['total'] * 100
            );

            if ($amount < 1) {
                throw new \RuntimeException(
                    'The payment amount is invalid.'
                );
            }

            $stripe = new StripeClient(
                $stripeSecret
            );

            $paymentIntent =
                $stripe->paymentIntents->create(
                    [
                        'amount' => $amount,
                        'currency' => $currency,

                        'payment_method_types' => [
                            'card',
                        ],

                        'receipt_email' =>
                            $validated['email'],

                        'description' =>
                            'Kaira order #' . $orderId,

                        'metadata' => [
                            'order_id' =>
                                (string) $orderId,

                            'checkout_token' =>
                                $checkoutToken,
                        ],
                    ],
                    [
                        'idempotency_key' =>
                            $checkoutToken,
                    ]
                );

            $this->updateExistingColumns(
                'orders',
                $orderId,
                [
                    'stripe_payment_intent_id' =>
                        $paymentIntent->id,

                    'payment_status' =>
                        'pending',
                ]
            );

            session()->put(
                'stripe_checkout',
                [
                    'order_id' => $orderId,

                    'checkout_token' =>
                        $checkoutToken,

                    'payment_intent_id' =>
                        $paymentIntent->id,
                ]
            );

            return response()->json([
                'success' => true,

                'clientSecret' =>
                    $paymentIntent->client_secret,

                'orderId' => $orderId,
            ]);
        } catch (ApiErrorException $exception) {
            report($exception);

            return response()->json([
                'message' =>
                    $exception->getMessage(),
            ], 422);
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'message' =>
                    'Unable to prepare payment: '
                    . $exception->getMessage(),
            ], 500);
        }
    }

    public function stripeSuccess(
        Request $request
    ): RedirectResponse {
        $request->validate([
            'payment_intent' => [
                'required',
                'string',
                'starts_with:pi_',
            ],
        ]);

        $checkout = session(
            'stripe_checkout'
        );

        if (
            empty($checkout)
            || empty($checkout['payment_intent_id'])
            || !hash_equals(
                $checkout['payment_intent_id'],
                $request->payment_intent
            )
        ) {
            return redirect()
                ->route('checkout')
                ->with(
                    'error',
                    'Invalid Stripe payment session.'
                );
        }

        try {
            $stripe = new StripeClient(
                config('services.stripe.secret')
            );

            $paymentIntent =
                $stripe->paymentIntents->retrieve(
                    $request->payment_intent,
                    []
                );

            if (
                $paymentIntent->status
                !== PaymentIntent::STATUS_SUCCEEDED
            ) {
                return redirect()
                    ->route('checkout')
                    ->with(
                        'error',
                        'Payment is not complete. Status: '
                        . $paymentIntent->status
                    );
            }

            $order = DB::table('orders')
                ->where('id', (int) $checkout['order_id'])
                ->first();

            if (!$order) {
                throw new \RuntimeException('The order no longer exists.');
            }

            $orderTotal = (float) (
                $order->total_amount
                ?? $order->total
                ?? 0
            );

            $expectedAmount = (int) round($orderTotal * 100);
            $expectedCurrency = strtolower((string) config(
                'services.stripe.currency',
                'usd'
            ));

            if (
                (int) $paymentIntent->amount_received !== $expectedAmount
                || strtolower((string) $paymentIntent->currency) !== $expectedCurrency
            ) {
                throw new \RuntimeException('Stripe amount verification failed.');
            }

            $stripeOrderId = (int) (
                $paymentIntent
                    ->metadata
                    ->order_id ?? 0
            );

            $stripeCheckoutToken = (string) (
                $paymentIntent
                    ->metadata
                    ->checkout_token ?? ''
            );

            if (
                $stripeOrderId
                !== (int) $checkout['order_id']
            ) {
                return redirect()
                    ->route('checkout')
                    ->with(
                        'error',
                        'Stripe order verification failed.'
                    );
            }

            if (
                !hash_equals(
                    $checkout['checkout_token'],
                    $stripeCheckoutToken
                )
            ) {
                return redirect()
                    ->route('checkout')
                    ->with(
                        'error',
                        'Stripe payment verification failed.'
                    );
            }

            $this->markOrderPaid(
                (int) $checkout['order_id'],
                $paymentIntent->id
            );

            session()->forget([
                'cart',
                'stripe_checkout',
            ]);

            session()->flash(
                'order_id',
                (int) $checkout['order_id']
            );

            return redirect()
                ->route('order.success')
                ->with(
                    'success',
                    'Payment successful. Your order is confirmed.'
                );
        } catch (\Throwable $exception) {
            report($exception);

            return redirect()
                ->route('checkout')
                ->with(
                    'error',
                    'Unable to verify payment. Please contact support.'
                );
        }
    }

    public function webhook(Request $request): JsonResponse
    {
        try {
            $event = Webhook::constructEvent(
                $request->getContent(),

                (string) $request->header(
                    'Stripe-Signature'
                ),

                (string) config(
                    'services.stripe.webhook_secret'
                )
            );

            if (
                $event->type
                === 'payment_intent.succeeded'
            ) {
                $paymentIntent =
                    $event->data->object;

                $orderId = (int) (
                    $paymentIntent
                        ->metadata
                        ->order_id ?? 0
                );

                if ($orderId > 0) {
                    $this->markOrderPaid(
                        $orderId,
                        $paymentIntent->id
                    );
                }
            }

            return response()->json([
                'received' => true,
            ]);
        } catch (
            \UnexpectedValueException $exception
        ) {
            return response()->json([
                'message' => 'Invalid webhook payload.',
            ], 400);
        } catch (
            \Stripe\Exception\SignatureVerificationException
            $exception
        ) {
            return response()->json([
                'message' =>
                    'Invalid Stripe webhook signature.',
            ], 400);
        }
    }

    public function orderSuccess(): View
    {
        $orderId = session('order_id');

        return view(
            'order-success',
            compact('orderId')
        );
    }

    public function placeOrder(
        Request $request
    ): RedirectResponse {
        return $this->process($request);
    }

    private function validateCheckout(
        Request $request,
        bool $stripeCard
    ): array {
        return $request->validate([
            'first_name' => [
                'required',
                'string',
                'max:100',
            ],

            'last_name' => [
                'required',
                'string',
                'max:100',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
            ],

            'address' => [
                'required',
                'string',
                'max:500',
            ],

            'address2' => [
                'nullable',
                'string',
                'max:500',
            ],

            'country' => [
                'required',
                'string',
                'max:100',
            ],

            'city' => [
                'required',
                'string',
                'max:100',
            ],

            'zip' => [
                'required',
                'string',
                'max:20',
            ],

            'payment_method' => [
                'required',

                $stripeCard
                    ? 'in:credit_debit_card'
                    : 'in:cash_on_delivery,credit_debit_card',
            ],
        ]);
    }

    private function cartSummary(): array
    {
        $cart = (array) session(
            'cart',
            []
        );

        $items = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {
            if (!is_array($item)) {
                continue;
            }

            $product = null;

            $uuid = $item['uuid']
                ?? $key
                ?? null;

            if (!empty($uuid)) {
                $product = Product::with('images')
                    ->where('uuid', $uuid)
                    ->first();
            }

            if (
                !$product
                && !empty($item['product_id'])
            ) {
                $product = Product::with('images')
                    ->find($item['product_id']);
            }

            if (!$product) {
                continue;
            }

            $quantity = max(
                1,
                (int) ($item['quantity'] ?? 1)
            );

            /*
             * Never trust the product price stored
             * in the browser or session.
             */
            $price = (float) $product->price;

            $itemTotal =
                $price * $quantity;

            $firstImage =
                $product->images->first();

            $image = $firstImage
                ? $firstImage->image
                : ($item['image'] ?? null);

            $items[] = [
                'product_id' => $product->id,
                'uuid' => $product->uuid,
                'name' => $product->name,
                'price' => $price,
                'quantity' => $quantity,
                'image' => $image,
                'item_total' => $itemTotal,
            ];

            $subtotal += $itemTotal;
        }

        $shipping = 0;
        $total = $subtotal + $shipping;

        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
        ];
    }

    private function createOrder(
        array $validated,
        array $summary,
        string $paymentStatus,
        ?string $checkoutToken
    ): int {
        return DB::transaction(
            function () use (
                $validated,
                $summary,
                $paymentStatus,
                $checkoutToken
            ) {
                $customerName = trim(
                    $validated['first_name']
                    . ' '
                    . $validated['last_name']
                );

                $completeAddress = implode(
                    ', ',
                    array_filter([
                        $validated['address'],
                        $validated['address2'] ?? null,
                        $validated['city'],
                        $validated['country'],
                        $validated['zip'],
                    ])
                );

                $orderData =
                    $this->onlyExistingColumns(
                        'orders',
                        [
                            'order_no' =>
                                'ORD-'
                                . strtoupper(
                                    Str::random(8)
                                ),

                            'order_date' => now(),

                            'name' =>
                                $customerName,

                            'customer_name' =>
                                $customerName,

                            'email' =>
                                $validated['email'],

                            'customer_email' =>
                                $validated['email'],

                            'phone' =>
                                $validated['phone'],

                            'customer_phone' =>
                                $validated['phone'],

                            'address' =>
                                $completeAddress,

                            'shipping_address' =>
                                $completeAddress,

                            'city' =>
                                $validated['city'],

                            'postal_code' =>
                                $validated['zip'],

                            'payment_method' =>
                                $validated['payment_method'],

                            'subtotal' =>
                                $summary['subtotal'],

                            'shipping' =>
                                $summary['shipping'],

                            'total' =>
                                $summary['total'],

                            'total_amount' =>
                                $summary['total'],

                            'status' => 'pending',

                            'payment_status' =>
                                $paymentStatus,

                            'fulfillment_status' =>
                                'unfulfilled',

                            'delivery_status' =>
                                'pending',

                            'delivery_method' =>
                                'standard',

                            'checkout_token' =>
                                $checkoutToken,

                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );

                $orderId = DB::table('orders')
                    ->insertGetId($orderData);

                foreach (
                    $summary['items']
                    as $item
                ) {
                    $orderItemData =
                        $this->onlyExistingColumns(
                            'order_items',
                            [
                                'order_id' =>
                                    $orderId,

                                'product_id' =>
                                    $item['product_id'],

                                'product_name' =>
                                    $item['name'],

                                'price' =>
                                    $item['price'],

                                'unit_price' =>
                                    $item['price'],

                                'quantity' =>
                                    $item['quantity'],

                                'subtotal' =>
                                    $item['item_total'],

                                'total' =>
                                    $item['item_total'],

                                'image' =>
                                    $item['image'],

                                'created_at' =>
                                    now(),

                                'updated_at' =>
                                    now(),
                            ]
                        );

                    DB::table('order_items')
                        ->insert($orderItemData);
                }

                return $orderId;
            }
        );
    }

    private function markOrderPaid(
        int $orderId,
        string $paymentIntentId
    ): void {
        $this->updateExistingColumns(
            'orders',
            $orderId,
            [
                'payment_status' => 'paid',
                'status' => 'processing',

                'stripe_payment_intent_id' =>
                    $paymentIntentId,

                'paid_at' => now(),
                'updated_at' => now(),
            ]
        );
    }

    private function updateExistingColumns(
        string $table,
        int $id,
        array $data
    ): void {
        $data = $this->onlyExistingColumns(
            $table,
            $data
        );

        if (!empty($data)) {
            DB::table($table)
                ->where('id', $id)
                ->update($data);
        }
    }

    private function onlyExistingColumns(
        string $table,
        array $data
    ): array {
        return collect($data)
            ->filter(
                fn ($value, $column) =>
                    Schema::hasColumn(
                        $table,
                        $column
                    )
            )
            ->all();
    }
}
