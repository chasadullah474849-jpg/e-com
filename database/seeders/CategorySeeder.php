<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Support\Uuid;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Electronics',
                'description' => 'Gadgets, devices, and electronic appliances.',
                'status'      => 'active',
            ],
            [
                'name'        => 'Fashion',
                'description' => 'Clothing, footwear, and modern apparel accessories.',
                'status'      => 'active',
            ],
            [
                'name'        => 'Sports',
                'description' => 'Fitness gear, outdoor equipment, and sportswear.',
                'status'      => 'active',
            ],
            [
                'name'        => 'Home & Kitchen',
                'description' => 'Furniture, decor, and essential kitchen utilities.',
                'status'      => 'active',
            ],
            [
                'name'        => 'Books & Stationery',
                'description' => 'Educational resources, novels, and office supplies.',
                'status'      => 'inactive', // Example of an inactive category
            ],
        ];


    

        foreach($categories as $item)
        {

            Category::create([

                'uuid' => (string) Str::uuid(),

                'name' => $item['name'],

                'slug' => Str::slug($item['name']),

                'description' => $item['description'],

                'status' => 1

            ]);

        }

    }
}
