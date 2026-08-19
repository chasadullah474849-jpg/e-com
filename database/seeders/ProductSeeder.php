<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\Product;
use Illuminate\Support\Str;


class ProductSeeder extends Seeder
{
    public function run(): void
    {


        // Categories

        $electronics = Category::create([
            'name'=>'Electronics',
            'status'=>1
        ]);


        $fashion = Category::create([
            'name'=>'Fashion',
            'status'=>1
        ]);


        $home = Category::create([
            'name'=>'Home & Living',
            'status'=>1
        ]);




        // Sub Categories


        Subcategory::create([
            'name'=>'Mobile Phones',
            'category_id'=>$electronics->id,
            'status'=>1
        ]);


        Subcategory::create([
            'name'=>'Laptops',
            'category_id'=>$electronics->id,
            'status'=>1
        ]);


        Subcategory::create([
            'name'=>'Men Clothing',
            'category_id'=>$fashion->id,
            'status'=>1
        ]);


        Subcategory::create([
            'name'=>'Women Clothing',
            'category_id'=>$fashion->id,
            'status'=>1
        ]);


        Subcategory::create([
            'name'=>'Furniture',
            'category_id'=>$home->id,
            'status'=>1
        ]);


        Subcategory::create([
            'name'=>'Kitchen Items',
            'category_id'=>$home->id,
            'status'=>1
        ]);




        // Products


        Product::create([

            'uuid'=>Str::uuid(),

            'name'=>'iPhone 15 Pro',

            'description'=>'Apple latest smartphone',

            'price'=>1500,

            'stock'=>10,

            'category_id'=>$electronics->id,

            'subcategory_id'=>1,

            'status'=>1

        ]);



        Product::create([

            'uuid'=>Str::uuid(),

            'name'=>'Android',

            'description'=>'Android mobile phone',

            'price'=>500,

            'stock'=>20,

            'category_id'=>$electronics->id,

            'subcategory_id'=>1,

            'status'=>1

        ]);

    }
}
