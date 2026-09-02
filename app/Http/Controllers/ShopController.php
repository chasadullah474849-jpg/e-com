<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
  public function category($uuid)
{
    $category = Category::where('uuid', $uuid)
        ->firstOrFail();

    $products = Product::with('images')
        ->where('category_id', $category->id)
        ->get();

    return view('shop.category', compact(
        'category',
        'products'
    ));
}


    public function product($id)
    {
        $product = Product::with('images')
            ->findOrFail($id);

        return view('shop.product', compact('product'));
    }
}
