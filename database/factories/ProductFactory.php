<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'product_category_id' => ProductCategory::factory(),
            'name' => $this->faker->bs(),
            'sku' => $this->faker->unique()->ean8(),
            'cost' => $this->faker->randomFloat(2, 50, 500),
            'price' => $this->faker->randomFloat(2, 60, 1000),
        ];
    }
}
