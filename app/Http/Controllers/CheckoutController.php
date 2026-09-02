<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
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

            // Make sure cart item is an array
            if (!is_array($item)) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Find Product
            |--------------------------------------------------------------------------
            */

            $productUuid = $item['uuid'] ?? $key ?? null;

            $product = null;

            if ($productUuid) {
                $product = Product::with('images')
                    ->where('uuid', $productUuid)
                    ->first();
            }

            /*
            |--------------------------------------------------------------------------
            | Fallback: Product ID
            |--------------------------------------------------------------------------
            */

            if (!$product && !empty($item['product_id'])) {
                $product = Product::with('images')
                    ->find($item['product_id']);
            }

            /*
            |--------------------------------------------------------------------------
            | Skip Invalid Product
            |--------------------------------------------------------------------------
            */

            if (!$product) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Product Image
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
            | ALWAYS USE CURRENT DATABASE PRICE
            |--------------------------------------------------------------------------
            */

            $price = (float) $product->price;

            $itemTotal = $price * $quantity;

            /*
            |--------------------------------------------------------------------------
            | Checkout Item
            |--------------------------------------------------------------------------
            */

            $cartItems[] = [
                'id'         => $product->id,
                'product_id' => $product->id,
                'uuid'       => $product->uuid,
                'name'       => $product->name,
                'price'      => $price,
                'quantity'   => $quantity,
                'image'      => $image,
                'item_total' => $itemTotal,
            ];

            $subtotal += $itemTotal;
        }

        /*
        |--------------------------------------------------------------------------
        | No Valid Products
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
        | Final Total
        |--------------------------------------------------------------------------
        */

        $total = $subtotal + $shipping;

        return view(
            'checkout',
            compact(
                'cartItems',
                'subtotal',
                'shipping',
                'total'
            )
        );
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
     */
    public function process(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | 1. VALIDATE CHECKOUT FORM
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
        | 2. GET CART
        |--------------------------------------------------------------------------
        */

        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/')
                ->with('error', 'Your cart is empty.');
        }


        /*
        |--------------------------------------------------------------------------
        | 3. PREPARE ORDER ITEMS
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
            | Find Product Using UUID
            |--------------------------------------------------------------------------
            */

            $product = null;

            if ($uuid) {
                $product = Product::with('images')
                    ->where('uuid', $uuid)
                    ->first();
            }


            /*
            |--------------------------------------------------------------------------
            | Fallback Using Product ID
            |--------------------------------------------------------------------------
            */

            if (!$product && !empty($item['product_id'])) {

                $product = Product::with('images')
                    ->find($item['product_id']);
            }


            /*
            |--------------------------------------------------------------------------
            | Product Doesn't Exist
            |--------------------------------------------------------------------------
            */

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
            | STOCK CHECK
            |--------------------------------------------------------------------------
            |
            | Your CartController is already deducting stock when adding
            | the product to cart.
            |
            | Therefore we DO NOT deduct stock again here.
            |
            */

            $price = (float) $product->price;

            $itemTotal = $price * $quantity;

            $subtotal += $itemTotal;


            /*
            |--------------------------------------------------------------------------
            | Product Image
            |--------------------------------------------------------------------------
            */

            $firstImage = $product->images->first();

            $image = $firstImage
                ? $firstImage->image
                : null;


            /*
            |--------------------------------------------------------------------------
            | Prepare Order Item
            |--------------------------------------------------------------------------
            */

            $orderItems[] = [
                'product_id'   => $product->id,
                'product_name' => $product->name,
                'price'        => $price,

                // IMPORTANT:
                // Your order_items table requires unit_price.
                'unit_price'   => $price,

                'quantity'     => $quantity,
                'subtotal'     => $itemTotal,
                'total'        => $itemTotal,
                'image'        => $image,
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Make Sure Cart Has Valid Products
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
        | SHIPPING
        |--------------------------------------------------------------------------
        */

        $shipping = 0;


        /*
        |--------------------------------------------------------------------------
        | FINAL TOTAL
        |--------------------------------------------------------------------------
        */

        $total = $subtotal + $shipping;


        /*
        |--------------------------------------------------------------------------
        | CUSTOMER NAME
        |--------------------------------------------------------------------------
        */

        $customerName = trim(
            $validated['first_name'] .
            ' ' .
            $validated['last_name']
        );


        /*
        |--------------------------------------------------------------------------
        | COMPLETE ADDRESS
        |--------------------------------------------------------------------------
        */

        $fullAddress = $validated['address'];

        if (!empty($validated['address2'])) {

            $fullAddress .= ', ' .
                $validated['address2'];
        }

        if (!empty($validated['country'])) {

            $fullAddress .= ', ' .
                $validated['country'];
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

                $orderData = [];

                /*
                |--------------------------------------------------------------------------
                | Frontend / Standard Order Columns
                |--------------------------------------------------------------------------
                */

                if (Schema::hasColumn('orders', 'name')) {

                    $orderData['name'] = $customerName;
                }

                if (Schema::hasColumn('orders', 'email')) {

                    $orderData['email'] = $validated['email'];
                }

                if (Schema::hasColumn('orders', 'phone')) {

                    $orderData['phone'] = $validated['phone'];
                }

                if (Schema::hasColumn('orders', 'address')) {

                    $orderData['address'] = $fullAddress;
                }

                if (Schema::hasColumn('orders', 'city')) {

                    $orderData['city'] = $validated['city'];
                }

                if (Schema::hasColumn('orders', 'postal_code')) {

                    $orderData['postal_code'] = $validated['zip'];
                }

                if (Schema::hasColumn('orders', 'payment_method')) {

                    $orderData['payment_method'] =
                        $validated['payment_method'];
                }

                if (Schema::hasColumn('orders', 'subtotal')) {

                    $orderData['subtotal'] = $subtotal;
                }

                if (Schema::hasColumn('orders', 'shipping')) {

                    $orderData['shipping'] = $shipping;
                }

                if (Schema::hasColumn('orders', 'total')) {

                    $orderData['total'] = $total;
                }

                if (Schema::hasColumn('orders', 'status')) {

                    $orderData['status'] = 'pending';
                }


                /*
                |--------------------------------------------------------------------------
                | Admin Order Columns
                |--------------------------------------------------------------------------
                */

                if (Schema::hasColumn('orders', 'order_no')) {

                    $orderData['order_no'] =
                        'ORD-' .
                        strtoupper(
                            substr(
                                str_shuffle(
                                    'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'
                                ),
                                0,
                                8
                            )
                        );
                }

                if (Schema::hasColumn('orders', 'order_date')) {

                    $orderData['order_date'] = now();
                }

                if (Schema::hasColumn('orders', 'customer_name')) {

                    $orderData['customer_name'] =
                        $customerName;
                }

                if (Schema::hasColumn('orders', 'customer_email')) {

                    $orderData['customer_email'] =
                        $validated['email'];
                }

                if (Schema::hasColumn('orders', 'customer_phone')) {

                    $orderData['customer_phone'] =
                        $validated['phone'];
                }

                if (Schema::hasColumn('orders', 'shipping_address')) {

                    $orderData['shipping_address'] =
                        $fullAddress;
                }

                if (Schema::hasColumn('orders', 'total_amount')) {

                    $orderData['total_amount'] =
                        $total;
                }

                if (Schema::hasColumn('orders', 'payment_status')) {

                    $orderData['payment_status'] =
                        'pending';
                }

                if (Schema::hasColumn('orders', 'fulfillment_status')) {

                    $orderData['fulfillment_status'] =
                        'unfulfilled';
                }

                if (Schema::hasColumn('orders', 'delivery_status')) {

                    $orderData['delivery_status'] =
                        'pending';
                }

                if (Schema::hasColumn('orders', 'delivery_method')) {

                    $orderData['delivery_method'] =
                        'standard';
                }


                /*
                |--------------------------------------------------------------------------
                | Timestamps
                |--------------------------------------------------------------------------
                */

                if (Schema::hasColumn('orders', 'created_at')) {

                    $orderData['created_at'] = now();
                }

                if (Schema::hasColumn('orders', 'updated_at')) {

                    $orderData['updated_at'] = now();
                }


                /*
                |--------------------------------------------------------------------------
                | INSERT ORDER
                |--------------------------------------------------------------------------
                */

                $orderId = DB::table('orders')
                    ->insertGetId($orderData);


                /*
                |--------------------------------------------------------------------------
                | CREATE ORDER ITEMS
                |--------------------------------------------------------------------------
                */

                foreach ($orderItems as $item) {

                    $itemData = [];


                    /*
                    |--------------------------------------------------------------------------
                    | Order ID
                    |--------------------------------------------------------------------------
                    */

                    if (Schema::hasColumn(
                        'order_items',
                        'order_id'
                    )) {

                        $itemData['order_id'] =
                            $orderId;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Product ID
                    |--------------------------------------------------------------------------
                    */

                    if (Schema::hasColumn(
                        'order_items',
                        'product_id'
                    )) {

                        $itemData['product_id'] =
                            $item['product_id'];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Product Name
                    |--------------------------------------------------------------------------
                    */

                    if (Schema::hasColumn(
                        'order_items',
                        'product_name'
                    )) {

                        $itemData['product_name'] =
                            $item['product_name'];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | PRICE
                    |--------------------------------------------------------------------------
                    */

                    if (Schema::hasColumn(
                        'order_items',
                        'price'
                    )) {

                        $itemData['price'] =
                            $item['price'];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | UNIT PRICE
                    |--------------------------------------------------------------------------
                    |
                    | THIS IS THE IMPORTANT FIX.
                    |
                    | Your database requires unit_price.
                    |
                    */

                    if (Schema::hasColumn(
                        'order_items',
                        'unit_price'
                    )) {

                        $itemData['unit_price'] =
                            $item['unit_price'];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Quantity
                    |--------------------------------------------------------------------------
                    */

                    if (Schema::hasColumn(
                        'order_items',
                        'quantity'
                    )) {

                        $itemData['quantity'] =
                            $item['quantity'];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Subtotal
                    |--------------------------------------------------------------------------
                    */

                    if (Schema::hasColumn(
                        'order_items',
                        'subtotal'
                    )) {

                        $itemData['subtotal'] =
                            $item['subtotal'];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Total
                    |--------------------------------------------------------------------------
                    */

                    if (Schema::hasColumn(
                        'order_items',
                        'total'
                    )) {

                        $itemData['total'] =
                            $item['total'];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Image
                    |--------------------------------------------------------------------------
                    */

                    if (
                        Schema::hasColumn(
                            'order_items',
                            'image'
                        )
                    ) {

                        $itemData['image'] =
                            $item['image'];
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Timestamps
                    |--------------------------------------------------------------------------
                    */

                    if (
                        Schema::hasColumn(
                            'order_items',
                            'created_at'
                        )
                    ) {

                        $itemData['created_at'] =
                            now();
                    }

                    if (
                        Schema::hasColumn(
                            'order_items',
                            'updated_at'
                        )
                    ) {

                        $itemData['updated_at'] =
                            now();
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | INSERT ORDER ITEM
                    |--------------------------------------------------------------------------
                    */

                    DB::table('order_items')
                        ->insert($itemData);
                }


                /*
                |--------------------------------------------------------------------------
                | RETURN ORDER ID
                |--------------------------------------------------------------------------
                */

                return $orderId;
            });


            /*
            |--------------------------------------------------------------------------
            | CLEAR CART ONLY AFTER SUCCESS
            |--------------------------------------------------------------------------
            */

            session()->forget('cart');


            /*
            |--------------------------------------------------------------------------
            | SAVE ORDER ID
            |--------------------------------------------------------------------------
            */

            session()->flash(
                'order_id',
                $orderId
            );


            /*
            |--------------------------------------------------------------------------
            | REDIRECT TO SUCCESS PAGE
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
        | DATABASE ERROR
        |--------------------------------------------------------------------------
        */

        catch (\Throwable $e) {

            report($e);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Unable to place order. ' .
                    $e->getMessage()
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
        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'item_id' => 'required',
            'quantity' => 'required|integer|min:1',
        ]);


        $itemId = $request->input('item_id');

        $newQty = max(
            1,
            (int) $request->input('quantity')
        );


        /*
        |--------------------------------------------------------------------------
        | Get Cart
        |--------------------------------------------------------------------------
        */

        $cart = session()->get('cart', []);


        /*
        |--------------------------------------------------------------------------
        | Check Product
        |--------------------------------------------------------------------------
        */

        if (!isset($cart[$itemId])) {

            return response()->json([
                'success' => false,
                'message' => 'Product not found in cart.',
            ], 404);
        }


        /*
        |--------------------------------------------------------------------------
        | Update Quantity
        |--------------------------------------------------------------------------
        */

        $cart[$itemId]['quantity'] =
            $newQty;


        session()->put(
            'cart',
            $cart
        );


        /*
        |--------------------------------------------------------------------------
        | Calculate Totals
        |--------------------------------------------------------------------------
        */

        $itemTotal = 0;

        $subtotal = 0;


        foreach ($cart as $id => $details) {

            if (!is_array($details)) {
                continue;
            }

            $price = (float) (
                $details['price'] ?? 0
            );

            $quantity = max(
                1,
                (int) (
                    $details['quantity'] ?? 1
                )
            );


            $lineTotal =
                $price * $quantity;


            $subtotal +=
                $lineTotal;


            if (
                (string) $id ===
                (string) $itemId
            ) {

                $itemTotal =
                    $lineTotal;
            }
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

        $total =
            $subtotal +
            $shipping;


        /*
        |--------------------------------------------------------------------------
        | JSON RESPONSE
        |--------------------------------------------------------------------------
        */

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
                    ->sum(function ($item) {

                        return is_array($item)
                            ? (int) (
                                $item['quantity'] ?? 0
                            )
                            : 0;
                    }),
        ]);
    }


    /**
     * =========================================================
     * PLACE ORDER
     * =========================================================
     *
     * Kept as a compatibility method in case your route currently
     * points to placeOrder().
     *
     * It uses the same correct process() method.
     *
     */
    public function placeOrder(Request $request)
    {
        return $this->process($request);
    }
}
