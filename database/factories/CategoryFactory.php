<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            // สุ่มชื่อหมวดหมู่ให้ดูเป็นธรรมชาติ และรับประกันว่าไม่ซ้ำกัน (unique)
            'name' => ucfirst($this->faker->unique()->words(2, true)) . ' Category',
            'is_active' => true,
        ];
    }

    /**
     * Factory State: สำหรับจำลองหมวดหมู่ที่ถูกปิดใช้งาน (Inactive)
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_active' => false,
        ]);
    }
}
