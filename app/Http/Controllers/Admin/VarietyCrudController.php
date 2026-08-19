<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use App\Models\Variety;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Crucial: The class name must be VarietycrudController
class VarietycrudController extends Controller
{
    public function index()
    {
        $varieties = Variety::latest()->get();

        return view('admin.varieties.index', compact('varieties'));
    }

    public function create()
    {
        return view('admin.varieties.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'nullable',
        ]);

        Variety::create([
            'uuid' => Str::uuid(),
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status ?? 1,
        ]);

        return redirect()->route('admin.varieties.index')
            ->with('success', 'Variety created successfully');
    }

    public function edit($id)
    {
        $variety = Variety::findOrFail($id);

        return view('admin.varieties.edit', compact('variety'));
    }

    public function update(Request $request, $id)
    {
        $variety = Variety::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
        ]);

        $variety->update([
            'name' => $request->name,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.varieties.index')
            ->with('success', 'Variety updated successfully');
    }

    public function destroy($id)
    {
        Variety::findOrFail($id)->delete();

        return redirect()->route('admin.varieties.index')
            ->with('success', 'Variety deleted successfully');
    }
}
