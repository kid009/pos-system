<?php

namespace Database\Factories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'shop_id' => Shop::factory(), // จะถูก Override ตอนรัน Seeder
            'name' => $this->faker->word(),
            'is_active' => true,
        ];
    }
}
