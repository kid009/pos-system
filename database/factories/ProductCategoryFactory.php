<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\ProductMainCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductCategory>
 */
class ProductCategoryFactory extends Factory
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
            'product_main_category_id' => ProductMainCategory::factory(),
            'name' => $this->faker->words(2, true),
        ];
    }
}
