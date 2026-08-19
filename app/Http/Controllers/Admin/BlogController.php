<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::with('category')->latest()->get();

        return view('admin.blogs.index', compact('blogs'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.blogs.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id'=>'required',
            'name'=>'required',
            'title'=>'required',
            'description'=>'required',
            'details'=>'required',
            'image'=>'required|image'
        ]);

        $imageName = null;

        if($request->hasFile('image'))
        {
            $image = $request->file('image');
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('uploads/blogs'),$imageName);
        }

        Blog::create([
            'category_id'=>$request->category_id,
            'name'=>$request->name,
            'image'=>$imageName,
            'title'=>$request->title,
            'description'=>$request->description,
            'details'=>$request->details,
            'status'=>$request->status ?? 1
        ]);

        return redirect()->route('admin.blogs.index')
            ->with('success','Blog Created Successfully');
    }

    public function edit($id)
    {
        $blog = Blog::findOrFail($id);

        $categories = Category::all();

        return view('admin.blogs.edit',compact('blog','categories'));
    }

    public function update(Request $request,$id)
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'category_id'=>'required',
            'name'=>'required',
            'title'=>'required',
            'description'=>'required',
            'details'=>'required'
        ]);

        if($request->hasFile('image'))
        {
            if(File::exists(public_path('uploads/blogs/'.$blog->image)))
            {
                File::delete(public_path('uploads/blogs/'.$blog->image));
            }

            $image = $request->file('image');

            $imageName = time().'.'.$image->getClientOriginalExtension();

            $image->move(public_path('uploads/blogs'),$imageName);

            $blog->image = $imageName;
        }

        $blog->category_id = $request->category_id;
        $blog->name = $request->name;
        $blog->title = $request->title;
        $blog->description = $request->description;
        $blog->details = $request->details;
        $blog->status = $request->status ?? 1;

        $blog->save();

        return redirect()->route('admin.blogs.index')
            ->with('success','Updated Successfully');
    }

    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        if(File::exists(public_path('uploads/blogs/'.$blog->image)))
        {
            File::delete(public_path('uploads/blogs/'.$blog->image));
        }

        $blog->delete();

        return redirect()->back()->with('success','Deleted Successfully');
    }
}
