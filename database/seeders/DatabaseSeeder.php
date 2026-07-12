<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. สร้างผู้ดูแลระบบส่วนกลาง (Administrator)
        // คุมความปลอดภัยรหัสผ่านผ่านระบบสิ่งแวดล้อมเพื่อป้องกันรหัสหลุดขึ้นคลังโค้ด (Git Repository)
        User::firstOrCreate(
            ['email' => 'admin@mail.com'], // ตรวจสอบไม่ให้สร้างแอดมินซ้ำซ้อนซากถ้ามีอยู่แล้ว
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // 2. สั่งเรียกใช้งานระบบ Seeder ย่อยตามลำดับสถาปัตยกรรมความสัมพันธ์ของข้อมูล (Data Dependency)
        // แก้ไขจุดที่พี่กั๊กเขียนเป็น Class ลอย ๆ ให้ทำงานผ่านคำสั่งหลักของ Laravel
        $this->call([
            CategorySeeder::class,
            // ในอนาคตเมื่อพี่กั๊กขึ้นระบบขายหน้าร้าน POS พี่สามารถซอยย่อยไฟล์ต่อท้ายตรงนี้ได้เลยครับ:
            // ProductSeeder::class,
            // InventoryLedgerSeeder::class,
        ]);
    }
}
