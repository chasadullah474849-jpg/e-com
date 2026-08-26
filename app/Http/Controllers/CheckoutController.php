<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * =========================================================
     * SHOW CHECKOUT PAGE
     * =========================================================
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/')
                ->with('error', 'Your cart is empty.');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $key => $item) {

            /*
            |--------------------------------------------------------------------------
            | Make sure cart item is an array
            |--------------------------------------------------------------------------
            */
            if (!is_array($item)) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Find product
            |--------------------------------------------------------------------------
            | Your cart normally uses product UUID as the array key.
            |--------------------------------------------------------------------------
            */

            $productUuid = $item['uuid']
                ?? $key
                ?? null;

            $product = null;

            if ($productUuid) {
                $product = Product::with('images')
                    ->where('uuid', $productUuid)
                    ->first();
            }

            /*
            |--------------------------------------------------------------------------
            | Fallback: product ID
            |--------------------------------------------------------------------------
            */

            if (!$product && !empty($item['product_id'])) {
                $product = Product::with('images')
                    ->find($item['product_id']);
            }

            /*
            |--------------------------------------------------------------------------
            | Skip invalid/deleted products
            |--------------------------------------------------------------------------
            */

            if (!$product) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Product image
            |--------------------------------------------------------------------------
            */

            $firstImage = $product->images->first();

            $image = $firstImage
                ? $firstImage->image
                : ($item['image'] ?? null);

            /*
            |--------------------------------------------------------------------------
            | Quantity
            |--------------------------------------------------------------------------
            */

            $quantity = max(
                1,
                (int) ($item['quantity'] ?? 1)
            );

            /*
            |--------------------------------------------------------------------------
            | Current database price
            |--------------------------------------------------------------------------
            */

            $price = (float) $product->price;

            $itemTotal = $price * $quantity;

            /*
            |--------------------------------------------------------------------------
            | Checkout item
            |--------------------------------------------------------------------------
            */

            $cartItems[] = [
                'id'          => $product->id,
                'product_id'  => $product->id,
                'uuid'        => $product->uuid,
                'name'        => $product->name,
                'price'       => $price,
                'quantity'    => $quantity,
                'image'       => $image,
                'item_total'  => $itemTotal,
            ];

            $subtotal += $itemTotal;
        }

        /*
        |--------------------------------------------------------------------------
        | If cart contained invalid products
        |--------------------------------------------------------------------------
        */

        if (empty($cartItems)) {
            session()->forget('cart');

            return redirect('/')
                ->with('error', 'Your cart contains unavailable products.');
        }

        /*
        |--------------------------------------------------------------------------
        | Shipping
        |--------------------------------------------------------------------------
        */

        $shipping = 0;

        /*
        |--------------------------------------------------------------------------
        | Total
        |--------------------------------------------------------------------------
        */

        $total = $subtotal + $shipping;

        return view('checkout', compact(
            'cartItems',
            'subtotal',
            'shipping',
            'total'
        ));
    }


    /**
     * =========================================================
     * CHECKOUT LOGIN
     * =========================================================
     */
    public function login()
    {
        return redirect()->route('checkout');
    }


    /**
     * =========================================================
     * PROCESS / PLACE ORDER
     * =========================================================
     *
     * IMPORTANT:
     * This is now the ONLY method responsible for placing
     * an order.
     */
    public function process(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate checkout form
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
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
                'in:cash_on_delivery',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Get cart
        |--------------------------------------------------------------------------
        */

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/')
                ->with('error', 'Your cart is empty.');
        }


        /*
        |--------------------------------------------------------------------------
        | Prepare cart products from DATABASE
        |--------------------------------------------------------------------------
        | Do not trust price/name from session.
        |--------------------------------------------------------------------------
        */

        $orderItems = [];

        $subtotal = 0;


        foreach ($cart as $key => $item) {

            if (!is_array($item)) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Product UUID
            |--------------------------------------------------------------------------
            */

            $uuid = $item['uuid'] ?? $key;

            /*
            |--------------------------------------------------------------------------
            | Find actual product
            |--------------------------------------------------------------------------
            */

            $product = Product::with('images')
                ->where('uuid', $uuid)
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Fallback by ID
            |--------------------------------------------------------------------------
            */

            if (!$product && !empty($item['product_id'])) {
                $product = Product::with('images')
                    ->find($item['product_id']);
            }

            if (!$product) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'One of the products in your cart no longer exists.'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | Quantity
            |--------------------------------------------------------------------------
            */

            $quantity = max(
                1,
                (int) ($item['quantity'] ?? 1)
            );


            /*
            |--------------------------------------------------------------------------
            | IMPORTANT STOCK CHECK
            |--------------------------------------------------------------------------
            |
            | Your current CartController deducts stock when adding to cart.
            | Therefore DO NOT deduct stock again here.
            |
            */

            $price = (float) $product->price;

            $itemTotal = $price * $quantity;

            $subtotal += $itemTotal;


            /*
            |--------------------------------------------------------------------------
            | Product image
            |--------------------------------------------------------------------------
            */

            $firstImage = $product->images->first();

            $image = $firstImage
                ? $firstImage->image
                : null;


            /*
            |--------------------------------------------------------------------------
            | Prepare order item
            |--------------------------------------------------------------------------
            */

            $orderItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'price' => $price,
                'quantity' => $quantity,
                'total' => $itemTotal,
                'image' => $image,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Make sure we actually have products
        |--------------------------------------------------------------------------
        */

        if (empty($orderItems)) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'No valid products were found in your cart.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Shipping
        |--------------------------------------------------------------------------
        */

        $shipping = 0;

        $total = $subtotal + $shipping;


        /*
        |--------------------------------------------------------------------------
        | Customer full name
        |--------------------------------------------------------------------------
        */

        $customerName = trim(
            $validated['first_name'] .
            ' ' .
            $validated['last_name']
        );


        /*
        |--------------------------------------------------------------------------
        | Combine address information
        |--------------------------------------------------------------------------
        |
        | This avoids requiring extra database columns for address2/country
        | if your existing orders table only has address/city/postal_code.
        |--------------------------------------------------------------------------
        */

        $fullAddress = $validated['address'];

        if (!empty($validated['address2'])) {
            $fullAddress .= ', ' . $validated['address2'];
        }

        if (!empty($validated['country'])) {
            $fullAddress .= ', ' . $validated['country'];
        }


        /*
        |--------------------------------------------------------------------------
        | DATABASE TRANSACTION
        |--------------------------------------------------------------------------
        */

        try {

            $orderId = DB::transaction(function () use (
                $validated,
                $customerName,
                $fullAddress,
                $orderItems,
                $subtotal,
                $shipping,
                $total
            ) {

                /*
                |--------------------------------------------------------------------------
                | CREATE ORDER
                |--------------------------------------------------------------------------
                */

                $orderId = DB::table('orders')->insertGetId([

                    'name' => $customerName,

                    'email' => $validated['email'],

                    'phone' => $validated['phone'],

                    'address' => $fullAddress,

                    'city' => $validated['city'],

                    'postal_code' => $validated['zip'],

                    'payment_method' =>
                        $validated['payment_method'],

                    'subtotal' => $subtotal,

                    'shipping' => $shipping,

                    'total' => $total,

                    'status' => 'pending',

                    'created_at' => now(),

                    'updated_at' => now(),
                ]);


                /*
                |--------------------------------------------------------------------------
                | CREATE ORDER ITEMS
                |--------------------------------------------------------------------------
                */

                foreach ($orderItems as $item) {

                    DB::table('order_items')->insert([

                        'order_id' => $orderId,

                        'product_id' => $item['product_id'],

                        'product_name' => $item['product_name'],

                        'price' => $item['price'],

                        'quantity' => $item['quantity'],

                        'total' => $item['total'],

                        'created_at' => now(),

                        'updated_at' => now(),
                    ]);
                }


                return $orderId;
            });


            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            | Only clear cart AFTER database transaction succeeds.
            |--------------------------------------------------------------------------
            */

            session()->forget('cart');


            /*
            |--------------------------------------------------------------------------
            | Save order ID for success page
            |--------------------------------------------------------------------------
            */

            session()->flash(
                'order_id',
                $orderId
            );


            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->route('order.success')
                ->with(
                    'success',
                    'Order placed successfully!'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Database error
        |--------------------------------------------------------------------------
        */

        catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to place order. ' .
                    ($e->getMessage())
                );
        }
    }


    /**
     * =========================================================
     * ORDER SUCCESS
     * =========================================================
     */
    public function orderSuccess()
    {
        $orderId = session('order_id');

        return view(
            'order-success',
            compact('orderId')
        );
    }


    /**
     * =========================================================
     * UPDATE CART QUANTITY
     * =========================================================
     */
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'item_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);

        $itemId = $request->input('item_id');

        $newQty = max(
            1,
            (int) $request->input('quantity')
        );

        $cart = session()->get('cart', []);

        if (!isset($cart[$itemId])) {

            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart.',
            ], 404);
        }

        $cart[$itemId]['quantity'] = $newQty;

        session()->put(
            'cart',
            $cart
        );


        $itemTotal = 0;

        $subtotal = 0;


        foreach ($cart as $id => $details) {

            $price = (float) (
                $details['price'] ?? 0
            );

            $quantity = (int) (
                $details['quantity'] ?? 1
            );

            $lineTotal =
                $price * $quantity;

            $subtotal += $lineTotal;

            if ((string) $id === (string) $itemId) {
                $itemTotal = $lineTotal;
            }
        }


        $shipping = 0;

        $total =
            $subtotal +
            $shipping;


        return response()->json([

            'success' => true,

            'message' =>
                'Cart updated successfully.',

            'item_total' =>
                number_format(
                    $itemTotal,
                    2
                ),

            'subtotal' =>
                number_format(
                    $subtotal,
                    2
                ),

            'total' =>
                number_format(
                    $total,
                    2
                ),

            'cart_count' =>
                collect($cart)
                    ->sum('quantity'),
        ]);
    }
}
