<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $cost = $this->faker->randomFloat(2, 50, 1000); // ต้นทุน 50 ถึง 1000 บาท
        $price = $cost + $this->faker->randomFloat(2, 20, 300); // ราคาขายบวกกำไรเพิ่ม 20 ถึง 300 บาท

        return [
            'shop_id' => Shop::factory(),
            'category_id' => Category::factory(),
            'name' => $this->faker->words(3, true),
            'sku' => $this->faker->unique()->bothify('SKU-####-???'),
            'price' => $price,
            'cost' => $cost,
            'unit' => $this->faker->randomElement(['ถัง', 'ขวด', 'แพ็ค', 'ชิ้น', 'กิโลกรัม']),
            'is_active' => $this->faker->boolean(90), // โอกาส 90% ที่จะเปิดขาย
        ];
    }
}
