<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ShopFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'ร้าน ' . $this->faker->company(),
            'address' => $this->faker->address(),
            'phone' => $this->faker->numerify('08########'),
            'tax_id' => $this->faker->numerify('13###########'),
            'branch_code' => $this->faker->numerify('0000#'),
            'is_active' => true,
            'settings' => [
                'receipt_header' => 'ยินดีต้อนรับสู่ ' . $this->faker->company(),
                'receipt_footer' => 'ขอบคุณที่ใช้บริการ โอกาสหน้าเชิญใหม่',
                'promptpay_no' => $this->faker->numerify('08########'),
                'promptpay_name' => $this->faker->name(),
            ],
        ];
    }
}
