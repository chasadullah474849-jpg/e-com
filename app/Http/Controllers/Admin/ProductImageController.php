<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;


class ProductImageController extends Controller
{


    public function replace(Request $request,$id)
    {

        $request->validate([
            'image'=>'required|image|mimes:jpg,jpeg,png,webp|max:2048'
        ]);



        $image = ProductImage::findOrFail($id);



        // delete old image

        $oldImage = public_path(
            'uploads/products/'.$image->image
        );


        if(File::exists($oldImage))
        {
            File::delete($oldImage);
        }



        // upload new image

        $file = $request->file('image');


        $filename = time().'_'.$file->getClientOriginalName();


        $file->move(
            public_path('uploads/products'),
            $filename
        );



        $image->update([

            'image'=>$filename

        ]);



        return back()->with(
            'success',
            'Image replaced successfully'
        );


    }






    public function delete($id)
    {


        $image = ProductImage::findOrFail($id);



        $file = public_path(
            'uploads/products/'.$image->image
        );



        if(File::exists($file))
        {
            File::delete($file);
        }



        $image->delete();



        return back()->with(
            'success',
            'Image deleted successfully'
        );


    }



}
