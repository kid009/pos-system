<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. หา User ที่เป็น Admin มาสักคนเพื่อสมมติว่าเป็นคนสร้างข้อมูล
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : null;

        // 2. รายชื่อหมวดหมู่ตัวอย่าง (ครอบคลุมร้านมินิมาร์ท)
        $categories = [
            'Beverages (เครื่องดื่ม)',
            'Snacks (ขนมขบเคี้ยว)',
            'Fresh Food (อาหารสด)',
            'Frozen Food (อาหารแช่แข็ง)',
            'Personal Care (ของใช้ส่วนตัว)',
            'Household (ของใช้ในบ้าน)',
            'Electronics (อุปกรณ์อิเล็กทรอนิกส์)',
            'Stationery (เครื่องเขียน)',
            'Toys (ของเล่น)',
            'Others (อื่นๆ)'
        ];

        // 3. วนลูปสร้างข้อมูล
        foreach ($categories as $categoryName) {
            Category::updateOrCreate(
                ['name' => $categoryName], // เช็คจากชื่อ ถ้ามีแล้วให้อัปเดต
                [
                    'created_by' => $adminId,
                    'updated_by' => $adminId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
