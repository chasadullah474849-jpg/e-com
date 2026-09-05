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

        // Blog status check
        $blogs = Blog::where('status', 'Active')
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

    // --- Public Collections Page & Search ---
    public function collections(Request $request)
    {
        $query = Collection::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $collections = $query->paginate(9);

        return view('home.collections', compact('collections'));
    }

    // --- Single Collection Details Method ---
    public function collectionDetails($uuid)
    {
        $collection = Collection::with('category.subcategories.products.images')
            ->where('uuid', $uuid)
            ->firstOrFail();

        $products = collect();
        if ($collection->category && $collection->category->subcategories) {
            $products = $collection->category->subcategories
                ->flatMap(function ($subcategory) {
                    return $subcategory->products;
                })
                ->unique('id');
        }

        if ($products->isEmpty() && $collection->category_id) {
            $products = Product::with('images')
                ->where('category_id', $collection->category_id)
                ->where('status', 1)
                ->get();
        }

        // Yahan hum wahi 'home.collections' view use kar rahe hain
        return view('home.collections', compact('collection', 'products'));
    }

    // --- Public Products Page & Search ---
   public function products(Request $request)
    {
        $query = Product::query();

        // Agar search query ho
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Products fetch karein with pagination
        $products = $query->latest()->paginate(12);

        return view('home.products', compact('products'));
    }

   public function productDetails($id)
    {
        $product = Product::findOrFail($id);
        return view('home.product-details', compact('product'));
    }

    public function blogs()
    {
        $blogs = Blog::latest()->get();

        return view('admin.blogs.index', compact('blogs'));
    }

    public function blogDetails($uuid)
    {
        $blog = Blog::where('uuid', $uuid)->firstOrFail();

        return view('home.blog-details', compact('blog'));
    }

    public function contact()
    {
        return view('home.contact');
    }

    public function sendContactForm(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        return back()->with('success', 'Your message has been sent successfully!');
    }
}
