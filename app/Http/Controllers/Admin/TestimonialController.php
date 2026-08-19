<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Testimonial;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->get();

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'main_title'=>'required|max:255',
            'name'=>'required|max:255',
            'description'=>'required',
            'status'=>'required'
        ]);

        Testimonial::create($request->all());

        return redirect()
        ->route('admin.testimonials.index') // <-- Redirects to /admin/testimonials
        ->with('success', 'Testimonial Added Successfully');
}

    public function edit($id)
    {
        $testimonial = Testimonial::findOrFail($id);

        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request,$id)
    {
        $request->validate([
            'main_title'=>'required|max:255',
            'name'=>'required|max:255',
            'description'=>'required',
            'status'=>'required'
        ]);

        $testimonial=Testimonial::findOrFail($id);

        $testimonial->update($request->all());

        return redirect()
            ->route('admin.testimonials.index')
            ->with('success','Updated Successfully');
    }

    public function destroy($id)
    {
        Testimonial::findOrFail($id)->delete();

        return redirect()
        ->route('admin.testimonials.index') // <-- Redirects to /admin/testimonials
        ->with('success', 'Testimonial Deleted Successfully');
}
}
