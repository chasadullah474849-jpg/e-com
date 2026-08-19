<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;


class CheckoutController extends Controller
{
    /**
     * Display checkout page
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect('/')->with('error', 'Your cart is empty.');
        }

        $cartItems = [];
        $subtotal = 0;

        foreach ($cart as $item) {
            $price = (float) ($item['price'] ?? 0);
            $quantity = (int) ($item['quantity'] ?? 1);
            $itemTotal = $price * $quantity;
            $item['item_total'] = $itemTotal;
            $cartItems[] = $item;
            $subtotal += $itemTotal;
        }

        $shipping = 0;
        $total = $subtotal + $shipping;

        return view('checkout', [
            'cartItems' => $cartItems,
            'subtotal'  => $subtotal,
            'shipping'  => $shipping,
            'total'     => $total,
        ]);
    }

    public function login()
    {
        return redirect()->route('checkout');
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string|max:500',
            'city'           => 'required|string|max:100',
            'country'        => 'required|string|max:100',
            'zip'            => 'required|string|max:20',
            'payment_method' => 'required|string',
        ]);

        session()->forget('cart');

        return redirect()->route('order.success')->with('success', 'Your order has been placed successfully.');
    }

    // Renders the order success page
    public function orderSuccess()
    {
        return view('order-success');
    }
    /**
     * Process Checkout
     */
    public function process(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Customer Information
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'name' => 'required|string|max:255',

            'email' => 'required|email|max:255',

            'phone' => 'required|string|max:30',

            'address' => 'required|string|max:500',

            'city' => 'required|string|max:100',

            'postal_code' => 'nullable|string|max:20',

            'payment_method' => 'required|in:cash_on_delivery',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Get Cart
        |--------------------------------------------------------------------------
        */

        $cart = session()->get('cart', []);


        if (empty($cart)) {

            return redirect('/')

                ->with(
                    'error',
                    'Your cart is empty.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Subtotal
        |--------------------------------------------------------------------------
        */

        $subtotal = 0;


        foreach ($cart as $item) {

            $price = (float) ($item['price'] ?? 0);

            $quantity = (int) ($item['quantity'] ?? 1);

            $subtotal += $price * $quantity;
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


        /*
        |--------------------------------------------------------------------------
        | Database Transaction
        |--------------------------------------------------------------------------
        */

        DB::beginTransaction();


        try {

            /*
            |--------------------------------------------------------------------------
            | Create Order
            |--------------------------------------------------------------------------
            */

            $orderId = DB::table('orders')->insertGetId([

                'name' => $validated['name'],

                'email' => $validated['email'],

                'phone' => $validated['phone'],

                'address' => $validated['address'],

                'city' => $validated['city'],

                'postal_code' => $validated['postal_code'] ?? null,

                'payment_method' => $validated['payment_method'],

                'subtotal' => $subtotal,

                'shipping' => $shipping,

                'total' => $total,

                'status' => 'pending',

                'created_at' => now(),

                'updated_at' => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Save Order Items
            |--------------------------------------------------------------------------
            */

            foreach ($cart as $item) {

                DB::table('order_items')->insert([

                    'order_id' => $orderId,

                    'product_id' =>
                        $item['product_id']
                        ?? $item['id']
                        ?? null,

                    'product_name' =>
                        $item['name']
                        ?? 'Product',

                    'price' =>
                        (float) ($item['price'] ?? 0),

                    'quantity' =>
                        (int) ($item['quantity'] ?? 1),

                    'total' =>
                        (float) ($item['price'] ?? 0)
                        *
                        (int) ($item['quantity'] ?? 1),

                    'created_at' => now(),

                    'updated_at' => now(),
                ]);
            }


            /*
            |--------------------------------------------------------------------------
            | Clear Cart
            |--------------------------------------------------------------------------
            */

            session()->forget('cart');


            /*
            |--------------------------------------------------------------------------
            | Commit
            |--------------------------------------------------------------------------
            */

            DB::commit();


            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            */

            return redirect('/')

                ->with(
                    'success',
                    'Order placed successfully! Order ID: #' . $orderId
                );
        }


        catch (\Exception $e) {

            /*
            |--------------------------------------------------------------------------
            | Rollback
            |--------------------------------------------------------------------------
            */

            DB::rollBack();


            /*
            |--------------------------------------------------------------------------
            | Return Error
            |--------------------------------------------------------------------------
            */

            return back()

                ->withInput()

                ->with(
                    'error',
                    'Something went wrong while placing your order: '
                    . $e->getMessage()
                );
        }
    }
    public function updateQuantity(Request $request)
    {
        $itemId = $request->input('item_id');
        $newQty = max(1, (int) $request->input('quantity'));

        // Retrieve cart from session (or your custom Cart DB/Session structure)
        $cart = session()->get('cart', []);

        if (isset($cart[$itemId])) {
            $cart[$itemId]['quantity'] = $newQty;
            session()->put('cart', $cart);
        }

        // Calculate item total & grand totals
        $itemTotal = 0;
        $subtotal = 0;

        foreach ($cart as $id => $details) {
            $linePrice = $details['price'] * $details['quantity'];
            $subtotal += $linePrice;
            if ($id == $itemId) {
                $itemTotal = $linePrice;
            }
        }

        $shipping = 0; // Set your shipping logic here
        $total = $subtotal + $shipping;

        return response()->json([
            'success' => true,
            'item_total' => number_format($itemTotal, 2),
            'subtotal' => number_format($subtotal, 2),
            'total' => number_format($total, 2),
            'cart_count' => count($cart),
        ]);
    }
    public function removeFromCart(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        $removed = false;

        // 1. Direct array key lookup
        if (isset($cart[$id])) {
            unset($cart[$id]);
            $removed = true;
        } else {
            // 2. Fallback search inside array items for matching product 'id'
            foreach ($cart as $key => $item) {
                $itemId = is_object($item) ? ($item->id ?? null) : ($item['id'] ?? null);
                if ((string)$key === (string)$id || (string)$itemId === (string)$id) {
                    unset($cart[$key]);
                    $removed = true;
                    break;
                }
            }
        }

        if ($removed) {
            session()->put('cart', $cart);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Product removed successfully.']);
            }

            return redirect()->back()->with('success', 'Product removed successfully.');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Product not found in cart.'], 404);
        }

        return redirect()->back()->with('error', 'Product not found in cart.');
    }
    public function store(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Validate checkout inputs
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
        ]);

        // Process order logic here (e.g., Save to database)

        // Clear cart session after successful order
        session()->forget('cart');

        return redirect()->route('order.success')->with('success', 'Order placed successfully!');
    }

    /**
     * Display order success page.
     */

}
