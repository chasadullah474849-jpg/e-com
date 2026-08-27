<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Billboard;
use App\Models\Feature;
use App\Models\Collection;
use App\Models\CollectionPro;
use App\Models\Category;
use App\Models\Product;
use App\Models\Blog;

class HomeController extends Controller
{
    public function index()
    {
        // Active Billboard
        $billboard = Billboard::where('status', 1)->first();

        // Active Features
        $features = Feature::where('status', 1)->latest()->take(4)->get();

        // Active Collections (Multiple Images/Items)
        $collections = Collection::where('status', 'active')->latest()->get();

        // Single Latest Collection for Hero Banner Section
        $collectionss = Collection::where('status', 'active')->latest()->first();

        // Single Classic Winter / Featured Item (Collection Pro)
        $collectionPro = CollectionPro::where('status', 1)->latest()->first();

        // Active Categories
        $categories = Category::where('status', 'active')->latest()->get();

        // Latest Products
        $products = Product::with(['images', 'category', 'subcategory'])
            ->latest()
            ->take(10)
            ->get();

         $blogs = Blog::where('status', 1)
        ->latest()
        ->take(3)
        ->get();

        return view('home.index', compact(
            'billboard',
            'features',
            'collections',
            'collectionss',
            'collectionPro',
            'categories',
            'products',
            'blogs'
        ));
    }

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

    public function products()
    {
        $products = Product::with('images')
            ->where('status', 1)
            ->latest()
            ->paginate(12);

        return view('home.products', compact('products'));
    }

    public function productDetails($uuid)
    {
        $product = Product::with('images')
            ->where('uuid', $uuid)
            ->where('status', 1)
            ->firstOrFail();

        return view('home.product_details', compact('product'));
    }
  public function blogs()
    {
        $blogs = Blog::latest()->get();

        // Change to 'admin.blogs.index' if you are using your admin table layout
        // Or keep 'home.blogs' if you created resources/views/home/blogs.blade.php
        return view('admin.blogs.index', compact('blogs'));
    }

    public function blogDetails($uuid)
    {
        $blog = Blog::where('uuid', $uuid)->firstOrFail();

        return view('home.blog-details', compact('blog'));
    }
}
