<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. สร้าง Roles และ Admin User (จากสปริ้นต์ก่อนหน้า)
        $this->call(
            RoleAndPermissionSeeder::class,
            // UserSeeder::class,
        );

        $this->command->info('Roles, Permissions seeded.');

        // 2. สร้างหมวดหมู่ 5 หมวด
        $categories = Category::factory(5)->create();

        $this->command->info('5 Categories seeded.');

        // 3. สร้างสินค้า 20 รายการ และสุ่มจับคู่กับหมวดหมู่ที่สร้างไว้ในข้อ 2
        Product::factory(20)->create(function () use ($categories) {
            return [
                'category_id' => $categories->random()->id,
            ];
        });

        // 4. สร้างสินค้าที่ไม่มีหมวดหมู่ (Uncategorized) เพื่อทดสอบ Edge Case
        Product::factory(3)->create([
            'category_id' => null,
        ]);

        $this->command->info('23 Products seeded (20 categorized, 3 uncategorized).');
        $this->command->info('Database seeding completed successfully!');
    }
}
