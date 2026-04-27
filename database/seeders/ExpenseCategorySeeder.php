<?php

namespace Database\Seeders;

use App\Models\ExpenseCategory;
use Illuminate\Database\Seeder;

class ExpenseCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // ค่าใช้จ่ายทั่วไป
            ['name' => 'ค่าน้ำมัน', 'group_name' => 'ค่าใช้จ่ายทั่วไป', 'is_active' => true],
            ['name' => 'ค่าไฟฟ้า', 'group_name' => 'ค่าใช้จ่ายทั่วไป', 'is_active' => true],
            ['name' => 'ค่าน้ำประปา', 'group_name' => 'ค่าใช้จ่ายทั่วไป', 'is_active' => true],
            ['name' => 'ค่าโทรศัพท์/อินเทอร์เน็ต', 'group_name' => 'ค่าใช้จ่ายทั่วไป', 'is_active' => true],
            ['name' => 'ค่าเช่าพื้นที่', 'group_name' => 'ค่าใช้จ่ายทั่วไป', 'is_active' => true],

            // ค่าใช้จ่ายพนักงาน
            ['name' => 'เงินเดือนพนักงาน', 'group_name' => 'ค่าใช้จ่ายพนักงาน', 'is_active' => true],
            ['name' => 'ค่าล่วงเวลา (OT)', 'group_name' => 'ค่าใช้จ่ายพนักงาน', 'is_active' => true],
            ['name' => 'ค่าคอมมิชชั่น', 'group_name' => 'ค่าใช้จ่ายพนักงาน', 'is_active' => true],
            ['name' => 'ค่ารักษาพยาบาล', 'group_name' => 'ค่าใช้จ่ายพนักงาน', 'is_active' => true],
            ['name' => 'ค่าประกันสังคม', 'group_name' => 'ค่าใช้จ่ายพนักงาน', 'is_active' => true],

            // ค่าใช้จ่ายการตลาด
            ['name' => 'ค่าโฆษณา', 'group_name' => 'ค่าใช้จ่ายการตลาด', 'is_active' => true],
            ['name' => 'ค่าพิมพ์สื่อโปรโมชั่น', 'group_name' => 'ค่าใช้จ่ายการตลาด', 'is_active' => true],
            ['name' => 'ค่าออกบูธ/อีเวนต์', 'group_name' => 'ค่าใช้จ่ายการตลาด', 'is_active' => true],

            // ค่าใช้จ่ายอื่นๆ
            ['name' => 'ค่าซ่อมบำรุง', 'group_name' => 'ค่าใช้จ่ายอื่นๆ', 'is_active' => true],
            ['name' => 'ค่าอุปกรณ์สำนักงาน', 'group_name' => 'ค่าใช้จ่ายอื่นๆ', 'is_active' => true],
            ['name' => 'ค่าขนส่ง/ค่าส่งสินค้า', 'group_name' => 'ค่าใช้จ่ายอื่นๆ', 'is_active' => true],
            ['name' => 'ค่าใช้จ่ายเดินทาง', 'group_name' => 'ค่าใช้จ่ายอื่นๆ', 'is_active' => true],
            ['name' => 'ค่าธรรมเนียมธนาคาร', 'group_name' => 'ค่าใช้จ่ายอื่นๆ', 'is_active' => true],
            ['name' => 'ค่าภาษี/ค่าธรรมเนียมรัฐ', 'group_name' => 'ค่าใช้จ่ายอื่นๆ', 'is_active' => true],
            ['name' => 'ค่าอาหาร/เครื่องดื่ม', 'group_name' => 'ค่าใช้จ่ายอื่นๆ', 'is_active' => true],
        ];

        foreach ($categories as $category) {
            ExpenseCategory::firstOrCreate(
                ['name' => $category['name'], 'group_name' => $category['group_name']],
                $category
            );
        }

        $this->command->info('✅ สร้างหมวดหมู่ค่าใช้จ่ายเรียบร้อยแล้ว ('.count($categories).' รายการ)');
    }
}
