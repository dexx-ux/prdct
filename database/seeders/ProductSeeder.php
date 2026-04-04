<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
   
    public function run(): void
    {
        // Get category IDs
        $computersCat = Category::where('name', 'Computers')->first();
        $mobilePhonesCat = Category::where('name', 'Mobile Phones')->first();
        $accessoriesCat = Category::where('name', 'Accessories')->first();
        $electronicsCat = Category::where('name', 'Electronics')->first();

        // Create products WITHOUT images first (fast)
        $products = [
            [
                'name' => "Laptop",
                'description' => "High performance laptop",
                'quantity' => 10,
                'price' => 50000,
                'discount_value' => "10%",
                'category_id' => $computersCat?->id,
                'image' => null,
            ],
            [
                'name' => "Smartphone",
                'description' => "Latest Android phone",
                'quantity' => 15,
                'price' => 25000,
                'discount_value' => "5%",
                'category_id' => $mobilePhonesCat?->id,
                'image' => null,
            ],
            [
                'name' => "Headphones",
                'description' => "Noise cancelling headphones",
                'quantity' => 20,
                'price' => 8000,
                'discount_value' => null,
                'category_id' => $accessoriesCat?->id,
                'image' => null,
            ],
            [
                'name' => "Keyboard",
                'description' => "Mechanical keyboard",
                'quantity' => 12,
                'price' => 3000,
                'discount_value' => "500",
                'category_id' => $accessoriesCat?->id,
                'image' => null,
            ],
            [
                'name' => "Monitor",
                'description' => "27-inch 4K monitor",
                'quantity' => 8,
                'price' => 15000,
                'discount_value' => "15%",
                'category_id' => $computersCat?->id,
                'image' => null,
            ],
        ];

        // Create products quickly
        foreach ($products as $productData) {
            Product::create($productData);
        }

        // Create 15 random products using factory (without images for speed)
        Product::factory()->count(15)->create(['image' => null]);

        $this->command->info('20 products created successfully without images!');
        $this->command->info('You can add images later through the admin panel.');
    }
}