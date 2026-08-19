<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Billboard;
use Illuminate\Support\Str;

class BillboardController extends Controller
{
    public function index()
    {
    $billboard = Billboard::first();

        return view('admin.billboards.index', compact('billboard'));
    }

    public function create()
    {
        return view('admin.billboards.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required'
        ]);

        Billboard::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' => Str::slug($request->title).'-'.time(),
            'status' => $request->status ?? 1,
        ]);

        return redirect()
            ->route('admin.billboards.index')
            ->with('success', 'Billboard Added Successfully');
    }

    public function edit($id)
    {
        $billboard = Billboard::findOrFail($id);

        return view('admin.billboards.edit', compact('billboard'));
    }

    public function update(Request $request, $id)
    {
        $billboard = Billboard::findOrFail($id);

        $billboard->update([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.billboards.index')
            ->with('success', 'Billboard Updated Successfully');
    }

    public function destroy($id)
    {
        Billboard::findOrFail($id)->delete();

        return redirect()
            ->route('billboards.index')
            ->with('success', 'Billboard Deleted Successfully');
    }
}
