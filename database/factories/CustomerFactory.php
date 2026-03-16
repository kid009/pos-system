<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            // สุ่มให้ลูกค้าอยู่สาขา 1 หรือ 2 (สมมติว่าคุณมี 2 สาขาในระบบ)
            'shop_id' => $this->faker->numberBetween(1, 2),

            // สุ่มชื่อ-นามสกุล
            'name' => $this->faker->name(),

            // สุ่มเบอร์โทร (รูปแบบสมมติ 10 หลัก)
            'phone' => $this->faker->numerify('08########'),

            // สุ่มที่อยู่
            'address' => $this->faker->address(),

            // สุ่มเลขผู้เสียภาษี 13 หลัก (มีโอกาส 70% ที่จะเป็นค่าว่าง null เพราะลูกค้าทั่วไปมักไม่ขอใบกำกับภาษี)
            'tax_id' => $this->faker->optional(0.3)->numerify('#############'),

            // สุ่มแต้มสะสม 0 ถึง 500 แต้ม
            'points' => $this->faker->numberBetween(0, 500),

            // ให้ลูกค้าใช้งานได้ปกติ 95% และโดนระงับ 5%
            'is_active' => $this->faker->boolean(95),
        ];
    }
}
