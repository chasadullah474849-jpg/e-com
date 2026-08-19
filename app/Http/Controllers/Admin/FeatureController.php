<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FeatureController extends Controller
{
    public function index()
    {
        $features = Feature::latest()->get();

        return view('admin.features.index', compact('features'));
    }

    public function create()
    {
        return view('admin.features.create');
    }

   public function store(Request $request)
{
    $request->validate([
        'icon' => 'nullable|string|max:255',
        'title' => 'required|string|max:255',
        'description' => 'nullable|string',
        'status' => 'required|in:0,1', // <-- Validates that either 0 or 1 is chosen
    ]);

    Feature::create([
        'uuid' => (string) \Illuminate\Support\Str::uuid(),
        'icon' => $request->icon,
        'title' => $request->title,
        'description' => $request->description,
        'status' => $request->status, // <-- Dynamically grabs 1 or 0 from the form selection
    ]);

    return redirect()
        ->route('admin.features.index')
        ->with('success', 'Feature Created Successfully');
}


    public function edit($uuid)
    {
        $feature = Feature::where('uuid', $uuid)->firstOrFail();

        return view('admin.features.edit', compact('feature'));
    }

    public function update(Request $request, $uuid)
    {
        $feature = Feature::where('uuid', $uuid)->firstOrFail();

        $request->validate([
            'icon' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $feature->update([
            'icon' => $request->icon,
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('admin.features.index')
            ->with('success', 'Feature Updated Successfully');
    }

    public function destroy($uuid)
{
    // Find the feature by its UUID and delete it
    $feature = Feature::where('uuid', $uuid)->firstOrFail();
    $feature->delete();

    return redirect()
        ->route('admin.features.index')
        ->with('success', 'Feature Deleted Successfully');
}

    public function status($uuid)
    {
        $feature = Feature::where('uuid', $uuid)->firstOrFail();

        $feature->status = !$feature->status;

        $feature->save();

        return back();
    }
}
