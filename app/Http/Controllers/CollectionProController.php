<?php

namespace App\Http\Controllers;

use App\Models\CollectionPro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CollectionProController extends Controller
{
    public function index()
    {
        $collectionPros = CollectionPro::latest()->get();
        return view('admin.collection_pro.index', compact('collectionPros'));
    }

    public function create()
    {
        return view('admin.collection_pro.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('collection_pro', 'public');
        }

        CollectionPro::create([
            'title'       => $request->title,
            'description' => $request->description,
            'image'       => $imagePath,
            'status'      => $request->has('status') ? 1 : 0,
        ]);

        return redirect()->route('collection-pro.index')->with('success', 'Collection Pro item created successfully!');
    }

    public function edit($id)
    {
        $item = CollectionPro::findOrFail($id);
        return view('admin.collection_pro.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = CollectionPro::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($item->image && Storage::disk('public')->exists($item->image)) {
                Storage::disk('public')->delete($item->image);
            }
            $item->image = $request->file('image')->store('collection_pro', 'public');
        }

        $item->title       = $request->title;
        $item->description = $request->description;
        $item->status      = $request->has('status') ? 1 : 0;
        $item->save();

        return redirect()->route('collection-pro.index')->with('success', 'Collection Pro item updated successfully!');
    }

    public function destroy($id)
    {
        $item = CollectionPro::findOrFail($id);
        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();

        return redirect()->route('collection-pro.index')->with('success', 'Collection Pro item deleted successfully!');
    }
}
