<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    /**
     * Add product to cart using AJAX
     */
   public function addToCart(Request $request, $uuid = null)
    {
        // Extract product identifier from route param OR form input
        $productUuid = $uuid ?? $request->input('uuid') ?? $request->input('product_id') ?? $request->input('id');

        if (!$productUuid) {
            return back()->with('error', 'Product ID or UUID is required.');
        }

        // Search for product by UUID or primary ID
        $product = Product::where('uuid', $productUuid)
            ->orWhere('id', $productUuid)
            ->first();

        if (!$product) {
            return back()->with('error', 'Product not found.');
        }

        $quantity = (int) $request->input('quantity', 1);
        if ($quantity < 1) {
            $quantity = 1;
        }

        $cart = session()->get('cart', []);

        $cartKey = $product->uuid ?? $product->id;

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity'] += $quantity;
        } else {
            $cart[$cartKey] = [
                'name'     => $product->title ?? $product->name,
                'quantity' => $quantity,
                'price'    => $product->price,
                'image'    => $product->image ?? $product->primary_image ?? null,
                'uuid'     => $cartKey,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Product added to cart successfully!');
    }


    /**
     * Get product image
     */
    private function getProductImage(Product $product)
    {
        /*
        |--------------------------------------------------------------------------
        | Product Images Relationship
        |--------------------------------------------------------------------------
        */

        if (
            method_exists($product, 'images') &&
            $product->images()->exists()
        ) {

            $image = $product->images()->first();


            if ($image && !empty($image->image)) {

                return $image->image;

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Product image column fallback
        |--------------------------------------------------------------------------
        */

        if (
            isset($product->image) &&
            !empty($product->image)
        ) {

            return $product->image;

        }


        return null;
    }


    /**
     * Show cart
     */
    public function cart()
    {
        $cart = session()->get('cart', []);


        $total = 0;

        $cartCount = 0;


        foreach ($cart as $item) {

            $price =
                (float) ($item['price'] ?? 0);

            $quantity =
                (int) ($item['quantity'] ?? 1);


            $total +=
                $price *
                $quantity;


            $cartCount += $quantity;

        }


        /*
        |--------------------------------------------------------------------------
        | Your Existing Cart View
        |--------------------------------------------------------------------------
        */

        return view(
            'home.cart',
            compact(
                'cart',
                'total',
                'cartCount'
            )
        );
    }


    /**
     * Remove product from cart
     *
     * UUID version
     */
    public function remove(Request $request, $uuid)
    {
        try {

            $cart = session()->get('cart', []);


            /*
            |--------------------------------------------------------------------------
            | Find Cart Item
            |--------------------------------------------------------------------------
            */

            if (!isset($cart[$uuid])) {

                return response()->json([
                    'success' => false,
                    'message' => 'Product not found in cart.'
                ], 404);

            }


            /*
            |--------------------------------------------------------------------------
            | Remove Product
            |--------------------------------------------------------------------------
            */

            unset($cart[$uuid]);


            session()->put('cart', $cart);


            /*
            |--------------------------------------------------------------------------
            | Recalculate Cart
            |--------------------------------------------------------------------------
            */

            $cartCount = 0;

            $cartTotal = 0;


            foreach ($cart as $cartItem) {

                $quantity =
                    (int) ($cartItem['quantity'] ?? 0);

                $price =
                    (float) ($cartItem['price'] ?? 0);


                $cartCount += $quantity;

                $cartTotal +=
                    $price *
                    $quantity;

            }


            return response()->json([

                'success' => true,

                'message' =>
                    'Product removed from cart.',

                'cart_count' =>
                    $cartCount,

                'cart_total' =>
                    number_format(
                        $cartTotal,
                        2
                    ),

            ]);


        } catch (\Throwable $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Something went wrong.',

                'error' =>
                    config('app.debug')
                        ? $e->getMessage()
                        : null,

            ], 500);

        }
    }


    /**
     * Remove product from cart
     *
     * Supports both UUID and Product ID
     */
    public function removeFromCart(Request $request, $id)
    {
        try {

            $cart = session()->get('cart', []);


            $removed = false;


            /*
            |--------------------------------------------------------------------------
            | Direct Cart Key
            |--------------------------------------------------------------------------
            */

            if (isset($cart[$id])) {

                unset($cart[$id]);

                $removed = true;

            }


            /*
            |--------------------------------------------------------------------------
            | Search Inside Cart
            |--------------------------------------------------------------------------
            */

            if (!$removed) {

                foreach ($cart as $key => $item) {

                    $itemId =
                        is_object($item)
                            ? ($item->id ?? null)
                            : ($item['id'] ?? null);


                    $itemUuid =
                        is_object($item)
                            ? ($item->uuid ?? null)
                            : ($item['uuid'] ?? null);


                    if (
                        (string) $key ===
                        (string) $id
                        ||
                        (string) $itemId ===
                        (string) $id
                        ||
                        (string) $itemUuid ===
                        (string) $id
                    ) {

                        unset($cart[$key]);

                        $removed = true;

                        break;

                    }

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Product Not Found
            |--------------------------------------------------------------------------
            */

            if (!$removed) {

                if (
                    $request->ajax() ||
                    $request->wantsJson()
                ) {

                    return response()->json([

                        'success' => false,

                        'message' =>
                            'Product not found in cart.'

                    ], 404);

                }


                return redirect()
                    ->back()
                    ->with(
                        'error',
                        'Product not found in cart.'
                    );

            }


            /*
            |--------------------------------------------------------------------------
            | Save Updated Cart
            |--------------------------------------------------------------------------
            */

            session()->put(
                'cart',
                $cart
            );


            /*
            |--------------------------------------------------------------------------
            | Recalculate Count
            |--------------------------------------------------------------------------
            */

            $cartCount = 0;

            $cartTotal = 0;


            foreach ($cart as $item) {

                $quantity =
                    (int) ($item['quantity'] ?? 0);

                $price =
                    (float) ($item['price'] ?? 0);


                $cartCount +=
                    $quantity;

                $cartTotal +=
                    $price *
                    $quantity;

            }


            /*
            |--------------------------------------------------------------------------
            | AJAX Response
            |--------------------------------------------------------------------------
            */

            if (
                $request->ajax() ||
                $request->wantsJson()
            ) {

                return response()->json([

                    'success' => true,

                    'message' =>
                        'Product removed successfully.',

                    'cart_count' =>
                        $cartCount,

                    'cart_total' =>
                        number_format(
                            $cartTotal,
                            2
                        ),

                ]);

            }


            /*
            |--------------------------------------------------------------------------
            | Normal Request
            |--------------------------------------------------------------------------
            */

            return redirect()
                ->back()
                ->with(
                    'success',
                    'Product removed successfully.'
                );


        } catch (\Throwable $e) {

            return response()->json([

                'success' => false,

                'message' =>
                    'Something went wrong.',

                'error' =>
                    config('app.debug')
                        ? $e->getMessage()
                        : null,

            ], 500);

        }
    }
 public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $productId = $request->input('product_id');
        $requestedQty = (int) $request->input('quantity', 1);

        $product = Product::findOrFail($productId);

        // Check if stock is sufficient
        if ($product->stock < $requestedQty) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Only ' . $product->stock . ' units available in stock.'
            ], 400);
        }

        // Deduct stock from database
        $product->stock -= $requestedQty;
        $product->save();

        // Manage session cart
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $requestedQty;
        } else {
            $cart[$product->id] = [
                'name'     => $product->name ?? $product->title,
                'quantity' => $requestedQty,
                'price'    => $product->price,
                'image'    => $product->images->first()->image ?? $product->image ?? null,
                'uuid'     => $product->uuid ?? $product->id,
            ];
        }

        session()->put('cart', $cart);

        // Calculate total cart quantity across all items
        $totalCartCount = array_sum(array_column($cart, 'quantity'));

        return response()->json([
            'status'         => 'success',
            'message'        => 'Product added to cart successfully!',
            'cartCount'      => $totalCartCount,
            'remainingStock' => $product->stock,
        ]);
    }
    /**
 * Update cart quantity
 */
public function updateQuantity(Request $request)
{
    $request->validate([
        'item_id' => 'required|string',
        'quantity' => 'required|integer|min:1',
    ]);

    $itemId = $request->item_id;
    $newQuantity = (int) $request->quantity;

    $cart = session()->get('cart', []);

    if (!isset($cart[$itemId])) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found in cart.'
        ], 404);
    }

    /*
    |--------------------------------------------------------------------------
    | Find Product
    |--------------------------------------------------------------------------
    */

    $product = Product::where('uuid', $itemId)->first();

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Product not found.'
        ], 404);
    }

    /*
    |--------------------------------------------------------------------------
    | Stock Check
    |--------------------------------------------------------------------------
    */

    if ($newQuantity > $product->stock) {
        return response()->json([
            'success' => false,
            'message' => 'Only ' . $product->stock . ' units are available.'
        ], 422);
    }

    /*
    |--------------------------------------------------------------------------
    | Update Quantity
    |--------------------------------------------------------------------------
    */

    $cart[$itemId]['quantity'] = $newQuantity;

    /*
    |--------------------------------------------------------------------------
    | Make Sure Price Is Current
    |--------------------------------------------------------------------------
    */

    $cart[$itemId]['price'] = $product->price;

    session()->put('cart', $cart);

    /*
    |--------------------------------------------------------------------------
    | Item Total
    |--------------------------------------------------------------------------
    */

    $itemTotal = $product->price * $newQuantity;

    /*
    |--------------------------------------------------------------------------
    | Cart Total
    |--------------------------------------------------------------------------
    */

    $cartTotal = 0;

    foreach ($cart as $cartItem) {

        $price = (float) ($cartItem['price'] ?? 0);

        $quantity = (int) ($cartItem['quantity'] ?? 0);

        $cartTotal += $price * $quantity;
    }

    /*
    |--------------------------------------------------------------------------
    | Cart Count
    |--------------------------------------------------------------------------
    */

    $cartCount = 0;

    foreach ($cart as $cartItem) {
        $cartCount += (int) ($cartItem['quantity'] ?? 0);
    }

    return response()->json([
        'success' => true,
        'message' => 'Cart updated successfully.',
        'quantity' => $newQuantity,
        'item_total' => number_format($itemTotal, 2),
        'total' => number_format($cartTotal, 2),
        'cart_count' => $cartCount,
        'remaining_stock' => $product->stock,
    ]);
}
}
