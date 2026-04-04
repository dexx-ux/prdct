<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $price = $this->faker->randomFloat(2, 1000, 50000);
        $discountValue = null;

        if ($this->faker->boolean(50)) {
            if ($this->faker->boolean(50)) {
                $discountValue = $this->faker->numberBetween(1, 50) . '%';
            } else {
                $discountValue = (string) $this->faker->randomFloat(2, 100, $price * 0.5);
            }
        }

        $randomCategory = Category::inRandomOrder()->first();

        return [
            'name' => $this->faker->sentence(2),
            'description' => $this->faker->sentence(),
            'quantity' => $this->faker->numberBetween(0, 50),
            'price' => $price,
            'discount_value' => $discountValue,
            'category_id' => $randomCategory?->id,
            'image' => null, // No image for now
        ];
    }
}