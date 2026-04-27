<?php

namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'ก๊าซหุงต้ม', 'is_active' => true],
            ['name' => 'น้ำดื่ม', 'is_active' => true],
            ['name' => 'อุปกรณ์เตาแก๊ส', 'is_active' => true],
            ['name' => 'อุปกรณ์ห้องครัว', 'is_active' => true],
            ['name' => 'อาหารแห้ง', 'is_active' => true],
            ['name' => 'เครื่องดื่ม', 'is_active' => true],
            ['name' => 'ขนมขบเคี้ยว', 'is_active' => false],
            ['name' => 'อุปกรณ์ซ่อมแซม', 'is_active' => false],
        ];

        foreach ($categories as $category) {
            ProductCategory::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}