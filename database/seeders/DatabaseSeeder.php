<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Product;
use Illuminate\Support\Str;


class DatabaseSeeder extends Seeder
{

    public function run(): void
    {


        // Categories

        $this->call([
            CategorySeeder::class,
        ]);



        $electronics = Category::where('name','Electronics')->first();
        $fashion     = Category::where('name','Fashion')->first();
        $home        = Category::where('name','Home & Living')->first();
        $beauty      = Category::where('name','Beauty')->first();





        // Sub Categories

        SubCategory::insert([


            [
                'uuid'=>Str::uuid(),
                'category_id'=>$electronics->id,
                'name'=>'Mobile Phones',
                'slug'=>Str::slug('Mobile Phones'),
                'description'=>'Smartphones and mobile devices',
                'status'=>1,
            ],


            [
                'uuid'=>Str::uuid(),
                'category_id'=>$electronics->id,
                'name'=>'Laptops',
                'slug'=>Str::slug('Laptops'),
                'description'=>'Laptop computers',
                'status'=>1,
            ],


            [
                'uuid'=>Str::uuid(),
                'category_id'=>$electronics->id,
                'name'=>'Headphones',
                'slug'=>Str::slug('Headphones'),
                'description'=>'Audio devices',
                'status'=>1,
            ],



            [
                'uuid'=>Str::uuid(),
                'category_id'=>$fashion->id,
                'name'=>'Men Clothing',
                'slug'=>Str::slug('Men Clothing'),
                'description'=>'Men fashion clothes',
                'status'=>1,
            ],


            [
                'uuid'=>Str::uuid(),
                'category_id'=>$fashion->id,
                'name'=>'Women Clothing',
                'slug'=>Str::slug('Women Clothing'),
                'description'=>'Women fashion clothes',
                'status'=>1,
            ],



            [
                'uuid'=>Str::uuid(),
                'category_id'=>$home->id,
                'name'=>'Furniture',
                'slug'=>Str::slug('Furniture'),
                'description'=>'Home furniture items',
                'status'=>1,
            ],



            [
                'uuid'=>Str::uuid(),
                'category_id'=>$home->id,
                'name'=>'Kitchen Items',
                'slug'=>Str::slug('Kitchen Items'),
                'description'=>'Kitchen products',
                'status'=>1,
            ],



            [
                'uuid'=>Str::uuid(),
                'category_id'=>$beauty->id,
                'name'=>'Skin Care',
                'slug'=>Str::slug('Skin Care'),
                'description'=>'Beauty products',
                'status'=>1,
            ],


            [
                'uuid'=>Str::uuid(),
                'category_id'=>$beauty->id,
                'name'=>'Perfumes',
                'slug'=>Str::slug('Perfumes'),
                'description'=>'Fragrance products',
                'status'=>1,
            ],


        ]);





        // Get subcategories AFTER insert

        $mobile = SubCategory::where('name','Mobile Phones')->first();

        $laptop = SubCategory::where('name','Laptops')->first();

        $headphones = SubCategory::where('name','Headphones')->first();






        // Products


        Product::insert([


            [
                'uuid'=>Str::uuid(),
                'name'=>'iPhone 15 Pro',
                'description'=>'Apple iPhone 15 Pro smartphone',
                'price'=>150000,
                'stock'=>20,
                'category_id'=>$mobile->category_id,
                'subcategory_id'=>$mobile->id,
                'status'=>1,
            ],



            [
                'uuid'=>Str::uuid(),
                'name'=>'Samsung Galaxy S25',
                'description'=>'Samsung flagship smartphone',
                'price'=>120000,
                'stock'=>25,
                'category_id'=>$mobile->category_id,
                'subcategory_id'=>$mobile->id,
                'status'=>1,
            ],



            [
                'uuid'=>Str::uuid(),
                'name'=>'Google Pixel 9',
                'description'=>'Google Android smartphone',
                'price'=>110000,
                'stock'=>15,
                'category_id'=>$mobile->category_id,
                'subcategory_id'=>$mobile->id,
                'status'=>1,
            ],




            [
                'uuid'=>Str::uuid(),
                'name'=>'Dell XPS 15 Laptop',
                'description'=>'Dell premium laptop',
                'price'=>250000,
                'stock'=>10,
                'category_id'=>$laptop->category_id,
                'subcategory_id'=>$laptop->id,
                'status'=>1,
            ],



            [
                'uuid'=>Str::uuid(),
                'name'=>'HP Pavilion Laptop',
                'description'=>'HP laptop computer',
                'price'=>150000,
                'stock'=>12,
                'category_id'=>$laptop->category_id,
                'subcategory_id'=>$laptop->id,
                'status'=>1,
            ],




            [
                'uuid'=>Str::uuid(),
                'name'=>'Lenovo ThinkPad',
                'description'=>'Business laptop',
                'price'=>180000,
                'stock'=>8,
                'category_id'=>$laptop->category_id,
                'subcategory_id'=>$laptop->id,
                'status'=>1,
            ],



            [
                'uuid'=>Str::uuid(),
                'name'=>'Sony Wireless Headphones',
                'description'=>'Bluetooth headphones',
                'price'=>30000,
                'stock'=>30,
                'category_id'=>$headphones->category_id,
                'subcategory_id'=>$headphones->id,
                'status'=>1,
            ],




            [
                'uuid'=>Str::uuid(),
                'name'=>'Apple AirPods Pro',
                'description'=>'Wireless earbuds',
                'price'=>50000,
                'stock'=>40,
                'category_id'=>$headphones->category_id,
                'subcategory_id'=>$headphones->id,
                'status'=>1,
            ],


        ]);

    }

}
