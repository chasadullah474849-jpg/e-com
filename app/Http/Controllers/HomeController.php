<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billboard;
use App\Models\Feature;
use App\Models\Collection;
use App\Models\Category;
use App\Models\Product;



class HomeController extends Controller
{
    /**
     * Display Homepage
     */
    public function index()
    {
        // Active Billboard
        $billboard = Billboard::where('status', 1)->first();

        // Active Features
        $features = Feature::where('status', 1)
            ->latest()
            ->take(4)
            ->get();

        // Active Collections
        $collections = Collection::where('status', 'active')
            ->latest()
            ->get();

        // Active Categories
        $categories = Category::where('status', 'active')
            ->latest()
            ->get();

        // Latest Collection
        $collectionss = Collection::where('status', 'active')
            ->latest()
            ->first();

        // Latest Products
        $products = Product::with(['images', 'category', 'subcategory'])
            ->latest()
            ->take(10)
            ->get();

        return view('home.index', compact(
            'billboard',
            'features',
            'collections',
            'categories',
            'collectionss',
            'products'
        ));
    }

    /**
     * Collection Details
     */
    public function collectionDetails($uuid)
    {
        $collection = Collection::with('category.subcategories.products.images')
            ->where('uuid', $uuid)
            ->firstOrFail();

        $products = $collection->category->subcategories
            ->flatMap(function ($subcategory) {
                return $subcategory->products;
            })
            ->unique('id');

        return view('home.collections', compact('collection', 'products'));
    }

    /**
     * All Products Page
     */
  public function products()
{
    // dd('asd');
    $products = Product::with('images')
                ->where('status', 1)
                ->latest()
                ->paginate(12);

    return view('home.products', compact('products'));
}
    /**
     * Product Details
     */
 public function productDetails($uuid)
{
    $product = Product::with('images')
        ->where('uuid', $uuid)
        ->where('status', 1)
        ->firstOrFail();

    return view('home.product_details', compact('product'));
}
}
