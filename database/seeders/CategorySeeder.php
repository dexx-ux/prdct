<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic devices and gadgets'],
            ['name' => 'Computers', 'description' => 'Laptops, desktops, and computer accessories'],
            ['name' => 'Mobile Phones', 'description' => 'Smartphones and mobile devices'],
            ['name' => 'Accessories', 'description' => 'Phone and computer accessories'],
            ['name' => 'Office Equipment', 'description' => 'Office supplies and equipment'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate($category);
        }
    }
}
