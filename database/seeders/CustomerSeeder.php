<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. ดึง ID ของ Admin มาใส่เป็นคนสร้างข้อมูล
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : null;

        // 2. ลูกค้าขาจร (Walk-in) - สำคัญมาก ต้องมีในระบบ POS
        Customer::updateOrCreate(
            ['phone' => null, 'name' => 'General Customer (Walk-in)'], // ใช้เงื่อนไขชื่อและเบอร์ว่าง
            [
                'points' => 0,
                'created_by' => $adminId,
                'updated_by' => $adminId,
            ]
        );

        // 3. รายชื่อลูกค้าสมาชิกตัวอย่าง
        $customers = [
            ['name' => 'Somchai Jaidee', 'phone' => '0812345678', 'points' => 150],
            ['name' => 'Somsri Rakrian', 'phone' => '0898765432', 'points' => 500],
            ['name' => 'John Doe', 'phone' => '0911112222', 'points' => 0],
            ['name' => 'Jane Smith', 'phone' => '0866667777', 'points' => 1250], // VIP point เยอะ
            ['name' => 'Tony Stark', 'phone' => '0809998888', 'points' => 50],
        ];

        // 4. วนลูปสร้างข้อมูล
        foreach ($customers as $data) {
            Customer::updateOrCreate(
                ['phone' => $data['phone']], // เช็คจากเบอร์โทร (Unique Key)
                [
                    'name' => $data['name'],
                    'points' => $data['points'],
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                ]
            );
        }
    }
}
