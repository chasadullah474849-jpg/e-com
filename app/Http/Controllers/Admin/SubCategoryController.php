<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    /**
     * Display all subcategories
     */
    public function index()
    {
        $subcategories = SubCategory::with('category')
            ->latest()
            ->get();

        return view('admin.subcategories.index', compact('subcategories'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.subcategories.create', compact('categories'));
    }

    /**
     * Store new subcategory
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:0,1',
        ]);

        // Generate base slug
        $slug = Str::slug($validated['name']);

        // Ensure slug uniqueness
        $count = SubCategory::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }

        SubCategory::create([
            'uuid'        => (string) Str::uuid(),
            'category_id' => $validated['category_id'],
            'name'        => $validated['name'],
            'slug'        => $slug,
            'description' => $validated['description'] ?? null,
            'status'      => (int) $validated['status'],
        ]);

        return redirect()
            ->route('subcategories.index')
            ->with('success', 'Subcategory created successfully.');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $categories  = Category::orderBy('name')->get();

        return view('admin.subcategories.edit', compact('subcategory', 'categories'));
    }

    /**
     * Update subcategory
     */
    public function update(Request $request, $id)
    {
        $subcategory = SubCategory::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'required|integer|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:0,1',
        ]);

        // Update slug if name changes
        $slug = Str::slug($validated['name']);
        $count = SubCategory::where('slug', 'LIKE', "{$slug}%")
            ->where('id', '!=', $id)
            ->count();

        if ($count > 0) {
            $slug = "{$slug}-" . ($count + 1);
        }

        $subcategory->update([
            'category_id' => $validated['category_id'],
            'name'        => $validated['name'],
            'slug'        => $slug,
            'description' => $validated['description'] ?? null,
            'status'      => (int) $validated['status'],
        ]);

        return redirect()
            ->route('subcategories.index')
            ->with('success', 'Subcategory updated successfully.');
    }

    /**
     * Delete subcategory
     */
    public function destroy($id)
    {
        $subcategory = SubCategory::findOrFail($id);
        $subcategory->delete();

        return redirect()
            ->route('subcategories.index')
            ->with('success', 'Subcategory deleted successfully.');
    }
    public function show($slug)
    {
        // Find subcategory by slug or name
        $subcategory = Subcategory::where('slug', $slug)->firstOrFail();

        return view('shop.subcategory', compact('subcategory'));
    }
}
