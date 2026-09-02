<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BlogController extends Controller
{
    /**
     * Display all blogs.
     */
    public function index()
    {
        $blogs = Blog::latest()->paginate(10);

        return view('admin.blogs.index', compact('blogs'));
    }

    /**
     * Show create blog form.
     */
    public function create()
    {
        return view('admin.blogs.create');
    }

    /**
     * Store a new blog.
     */

 public function store(Request $request)
    {
        // Accept Published, Draft, active, inactive, 1, 0 (case-insensitive)
        $request->validate([
            'name'        => 'required|string|max:255',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'status'      => 'required|in:Published,Draft,published,draft,active,inactive,1,0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $blog = new Blog();
        $blog->uuid        = (string) Str::uuid();
        $blog->name        = $request->name;
        $blog->title       = $request->title;
        $blog->description = $request->description;

        // Normalize status to match your DB schema (or save directly)
        $blog->status      = $request->status;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/blogs'), $filename);
            $blog->image = $filename;
        }

        $blog->save();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog created successfully!');
    }
    /**
     * Display one blog.
     */
    public function show(Blog $blog)
    {
        return view('admin.blogs.show', compact('blog'));
    }

    /**
     * Show edit blog form.
     */
   public function edit($id)
{
    // Find by UUID or ID
    $blog = Blog::where('uuid', $id)->orWhere('id', $id)->firstOrFail();

    return view('admin.blogs.edit', compact('blog'));
}

    /**
     * Update an existing blog.
     */
  public function update(Request $request, $id)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'status'      => 'required|in:Published,Draft,published,draft,active,inactive,1,0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $blog = Blog::where('uuid', $id)->orWhere('id', $id)->firstOrFail();
        $blog->name        = $request->name;
        $blog->title       = $request->title;
        $blog->description = $request->description;
        $blog->status      = $request->status;

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/blogs'), $filename);

            if ($blog->image && file_exists(public_path('uploads/blogs/' . $blog->image))) {
                @unlink(public_path('uploads/blogs/' . $blog->image));
            }

            $blog->image = $filename;
        }

        $blog->save();

        return redirect()->route('admin.blogs.index')->with('success', 'Blog updated successfully!');
    }

    /**
     * Delete blog.
     */
    public function destroy(Blog $blog)
    {
        if (
            $blog->image &&
            Storage::disk('public')->exists($blog->image)
        ) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->delete();

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog deleted successfully.');
    }

    /**
     * Remove blog image.
     */
    public function removeImage(Blog $blog)
    {
        if (
            $blog->image &&
            Storage::disk('public')->exists($blog->image)
        ) {
            Storage::disk('public')->delete($blog->image);
        }

        $blog->update([
            'image' => null,
        ]);

        return back()->with(
            'success',
            'Blog image removed successfully.'
        );
    }
}
