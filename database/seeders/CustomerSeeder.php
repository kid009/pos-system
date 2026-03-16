<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // 💡 1. สร้าง "ลูกค้าทั่วไป" แบบ Fix ค่าไว้เป็น ID ที่ 1 และ 2 เสมอ (สำหรับให้หน้า POS เรียกใช้เป็นค่าเริ่มต้น)
        Customer::create([
            'shop_id' => 1,
            'name' => 'ลูกค้าทั่วไป (สาขา 1)',
            'phone' => '-',
            'address' => '-',
            'points' => 0,
            'is_active' => true,
        ]);

        Customer::create([
            'shop_id' => 2,
            'name' => 'ลูกค้าทั่วไป (สาขา 2)',
            'phone' => '-',
            'address' => '-',
            'points' => 0,
            'is_active' => true,
        ]);

        // 💡 2. สั่ง Factory ให้ผลิตลูกค้าจำลองเพิ่มอีก 50 คน
        Customer::factory(50)->create();

        $this->command->info('สร้างข้อมูลลูกค้าจำลอง 52 รายการเสร็จสมบูรณ์!');
    }
}
