<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    /**
     * Display all blogs
     */
    public function index()
    {
        $blogs = Blog::with('category')
            ->latest()
            ->get();

        return view('admin.blogs.index', compact('blogs'));
    }


    /**
     * Show create blog form
     */
    public function create()
    {
        $categories = Category::all();

        return view('admin.blogs.create', compact('categories'));
    }


    /**
     * Store new blog
     */
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'details' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'nullable|in:0,1',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Upload Image
        |--------------------------------------------------------------------------
        */

        $imageName = null;

        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = time() . '_' . $image->getClientOriginalName();

            $uploadPath = public_path('uploads/blogs');

            /*
            | Create directory if it does not exist
            */

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $image->move(
                $uploadPath,
                $imageName
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Create Blog
        |--------------------------------------------------------------------------
        */

        $blog = new Blog();

        $blog->category_id = $request->category_id;
        $blog->name = $request->name;
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->details = $request->details;
        $blog->image = $imageName;
        $blog->status = $request->status ?? 1;

        $blog->save();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog Created Successfully');
    }


    /**
     * Show edit blog form
     */
    public function edit($id)
    {
        /*
        |--------------------------------------------------------------------------
        | Find Blog By ID
        |--------------------------------------------------------------------------
        */

        $blog = Blog::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Get Categories
        |--------------------------------------------------------------------------
        */

        $categories = Category::all();


        /*
        |--------------------------------------------------------------------------
        | Return Edit View
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.blogs.edit',
            compact('blog', 'categories')
        );
    }


    /**
     * Update blog
     */
    public function update(Request $request, $id)
    {
        /*
        |--------------------------------------------------------------------------
        | Find Blog By ID
        |--------------------------------------------------------------------------
        */

        $blog = Blog::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'details' => 'required|string',
            'status' => 'required|in:0,1',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);


        /*
        |--------------------------------------------------------------------------
        | Update Text Fields
        |--------------------------------------------------------------------------
        */

        $blog->category_id = $request->category_id;
        $blog->name = $request->name;
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->details = $request->details;
        $blog->status = $request->status;


        /*
        |--------------------------------------------------------------------------
        | Update Image
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {

            /*
            |--------------------------------------------------------------------------
            | Delete Old Image
            |--------------------------------------------------------------------------
            */

            if (!empty($blog->image)) {

                $oldImagePath = public_path(
                    'uploads/blogs/' . $blog->image
                );

                if (File::exists($oldImagePath)) {
                    File::delete($oldImagePath);
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Upload New Image
            |--------------------------------------------------------------------------
            */

            $image = $request->file('image');

            $filename = time() . '_' . $image->getClientOriginalName();

            $uploadPath = public_path('uploads/blogs');

            if (!File::exists($uploadPath)) {
                File::makeDirectory($uploadPath, 0755, true);
            }

            $image->move(
                $uploadPath,
                $filename
            );


            /*
            |--------------------------------------------------------------------------
            | Save New Image Name
            |--------------------------------------------------------------------------
            */

            $blog->image = $filename;
        }


        /*
        |--------------------------------------------------------------------------
        | Save Blog
        |--------------------------------------------------------------------------
        */

        $blog->save();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog updated successfully.');
    }


    /**
     * Delete blog
     */
    public function destroy($id)
    {
        /*
        |--------------------------------------------------------------------------
        | Find Blog By ID
        |--------------------------------------------------------------------------
        */

        $blog = Blog::findOrFail($id);


        /*
        |--------------------------------------------------------------------------
        | Delete Image
        |--------------------------------------------------------------------------
        */

        if (!empty($blog->image)) {

            $imagePath = public_path(
                'uploads/blogs/' . $blog->image
            );

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Blog
        |--------------------------------------------------------------------------
        */

        $blog->delete();


        /*
        |--------------------------------------------------------------------------
        | Redirect
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.blogs.index')
            ->with('success', 'Blog Deleted Successfully');
    }
}
