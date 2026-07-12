<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->randomNumber(5),
            'brand' => fake()->company(),
            'model_number' => fake()->bothify('MODEL-####'),
            'description' => fake()->paragraph(),
            'image_path' => null,
            'is_active' => fake()->boolean(80),
        ];
    }
}
