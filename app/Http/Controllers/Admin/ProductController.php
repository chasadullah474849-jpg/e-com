<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\ProductImage; // Added the ProductImage model
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; // Added for handling image deletions

class ProductController extends Controller
{
    public function index()
    {
        // Eager load images along with category and subcategory
        $products = Product::with([
            'category',
            'subcategory',
            'images'
        ])->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        $products = Product::all();

        return view('admin.products.create', compact('categories', 'subcategories', 'products'));
    }

   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'category_id' => 'required|exists:categories,id',
        'subcategory_id' => 'required|exists:sub_categories,id',
        'status' => 'required',
        'images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    DB::beginTransaction();

    try {

        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'status' => $request->status,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Save Product Images
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('images')) {

            $uploadPath = public_path('uploads/products');

            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0777, true);
            }

            foreach ($request->file('images') as $image) {

                $filename = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

                $image->move($uploadPath, $filename);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $filename,
                ]);
            }
        }

        DB::commit();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->with('error', $e->getMessage());
    }
}

    public function edit(Product $product)
    {
        $categories = Category::all();
        $subcategories = Subcategory::all();
        // Load existing images so you can see or manage them in your edit view
        $product->load('images');

        return view('admin.products.edit', compact('product', 'categories', 'subcategories'));
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required',
        'description' => 'nullable|string',
        'price' => 'required|numeric',
        'stock' => 'required|integer',
        'category_id' => 'required|exists:categories,id',
        'subcategory_id' => 'required|exists:sub_categories,id',
        'status' => 'required',
        'images' => 'nullable',
        'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    $product = Product::findOrFail($id);

    $product->update([
        'name' => $request->name,
        'description' => $request->description,
        'price' => $request->price,
        'stock' => $request->stock,
        'category_id' => $request->category_id,
        'subcategory_id' => $request->subcategory_id,
        'status' => $request->status,
    ]);

    if ($request->hasFile('images')) {

        foreach ($request->file('images') as $image) {

            $filename = time().'_'.uniqid().'_'.$image->getClientOriginalName();

            $image->move(public_path('uploads/products'), $filename);

            ProductImage::create([
                'product_id' => $product->id,
                'image' => $filename,
            ]);
        }
    }

    return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product Updated Successfully');
}

    public function destroy($id)
{
    $product = Product::with('images')->findOrFail($id);

    foreach ($product->images as $image) {

        $file = public_path('uploads/products/'.$image->image);

        if(file_exists($file)){
            unlink($file);
        }

        $image->delete();
    }

    $product->delete();

    return response()->json([
        'status' => true
    ]);
}
    public function getSubcategory($category_id)
    {
        $subcategories = Subcategory::where('category_id', $category_id)->get();

        return response()->json($subcategories);
    }

    public function deleteImage($id)
{
    $image = ProductImage::findOrFail($id);

    $file = public_path('uploads/products/'.$image->image);

    if(file_exists($file)){
        unlink($file);
    }

    $image->delete();

    return back()->with('success','Image deleted successfully.');
}

public function replaceImage(Request $request, $id)
{
    $request->validate([
        'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
    ]);

    $productImage = ProductImage::findOrFail($id);

    $old = public_path('uploads/products/'.$productImage->image);

    if(file_exists($old)){
        unlink($old);
    }

    $filename = time().'_'.$request->file('image')->getClientOriginalName();

    $request->file('image')->move(public_path('uploads/products'),$filename);

    $productImage->update([
        'image'=>$filename
    ]);

    return back()->with('success','Image updated successfully.');
}
public function showVariety($id)
    {
        $variety = Variety::findOrFail($id); // Fetch item by ID/UUID

        return view('varieties.show', compact('variety'));
    }
public function allProducts(Request $request)
    {
        $query = Product::query();

        // Optional search filter
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Fetch paginated products
        $products = $query->paginate(12);

        return view('home.products', compact('products'));
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('home.product-details', compact('product'));
    }
}
