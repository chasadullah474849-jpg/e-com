<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CollectionController extends Controller
{
    // Display a listing of the collections
    public function index()
    {
        // Use with('category') so Laravel fetches the category names instantly
        $collections = Collection::with('category')->latest()->get();

        return view('admin.collections.index', compact('collections'));
    }
    // Show the form for creating a new collection
   public function create()
    {
        // Fetches all categories to pass to the view dropdown
        $categories = Category::all();
        return view('admin.collections.create', compact('categories'));
    }

    // Store a newly created collection in storage
   public function store(Request $request)
    {
        // 1. Validate the incoming input fields, including the new image field
        $validatedData = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'status'      => 'required|in:active,inactive',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048', // max 2MB
        ]);

        // 2. Handle Image Upload if a file exists in the request
        if ($request->hasFile('image')) {
            // Stores image in storage/app/public/collections folder
            $imagePath = $request->file('image')->store('collections', 'public');

            // Add the generated path string to your validated data array
            $validatedData['image'] = $imagePath;
        }

        // 3. Save everything to the database
        Collection::create($validatedData);

        // 4. Redirect back to listing page with a success flash message
        return redirect()->route('admin.collections.index')->with('success', 'Collection created successfully!');
    }

    // Show the form for editing the specified collection
   public function edit($id)
{
    // Fetch the collection being edited
    $collection = Collection::findOrFail($id);

    // Fetch only the categories for the dropdown
    $categories = Category::all();

    // Pass only what's needed to the view
    return view('admin.collections.edit', compact('collection', 'categories'));
}

    // Update the specified collection in storage
    public function update(Request $request, $id)
{
    $collection = Collection::findOrFail($id);

    $validatedData = $request->validate([
        'name'        => 'required|string|max:255',
        'description' => 'nullable|string',
        'category_id' => 'required|exists:categories,id',
        'status'      => 'required|in:active,inactive',
        'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
    ]);

    if ($request->hasFile('image')) {
        // Delete old image if it exists
        if ($collection->image) {
            Storage::disk('public')->delete($collection->image);
        }

        // Save new image
        $validatedData['image'] = $request->file('image')->store('admin.collections', 'public');
    }

    $collection->update($validatedData);

    return redirect()->route('admin.collections.index')->with('success', 'Collection updated successfully!');
}

    // Remove the specified collection from storage
    public function destroy(Collection $collection)
    {
        $collection->delete();

        return redirect()->route('admin.collections.index')
            ->with('success', 'Collection deleted successfully.');
    }
    public function show($id)
    {
        // Retrieve dynamic data if needed
        return view('collection.show', compact('id'));
    }
}
