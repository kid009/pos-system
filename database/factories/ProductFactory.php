<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            // จำลอง SKU เช่น SKU-8A2F-9012
            'sku' => $this->faker->unique()->bothify('SKU-####-????'),
            'name' => ucfirst($this->faker->words(3, true)),
            'description' => $this->faker->sentence(),
            // สุ่มราคาขาย 50.00 ถึง 5000.00
            'price' => $this->faker->randomFloat(2, 50, 5000),
            'is_active' => true,
        ];
    }

    /**
     * Factory State: ใช้เฉพาะตอนเขียน PHPUnit Test ที่ต้องการข้อมูลสต็อกตั้งต้นแบบเร่งด่วน
     * (ช่วย Bypass ข้อจำกัด Mass Assignment ในฝั่ง Test)
     */
    public function withStock(int $qty = 10, float $cost = 100.00): static
    {
        return $this->state(fn(array $attributes) => [
            'stock' => $qty,
            'average_cost' => $cost,
        ]);
    }
}
