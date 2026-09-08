<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class ShopController extends Controller
{
  public function category(string $uuid)
{
    $category = Category::query()
        ->where(function ($query) use ($uuid) {
            $query->where('uuid', $uuid);

            if (ctype_digit($uuid)) {
                $query->orWhere('id', (int) $uuid);
            }
        })
        ->firstOrFail();

    $products = Product::with([
            'images',
            'category',
            'subcategory',
        ])
        ->where('category_id', $category->id)
        ->latest()
        ->paginate(12);

    return view('home.category-products', compact(
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
