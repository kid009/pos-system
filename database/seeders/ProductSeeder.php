<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. ดึง Admin มาใส่เป็นคนสร้าง
        $admin = User::where('role', 'admin')->first();
        $adminId = $admin ? $admin->id : null;

        // 2. ดึง Category ทั้งหมดมา
        $categories = Category::all();

        // 3. เตรียมข้อมูลตัวอย่าง (แยกตามชื่อหมวดหมู่)
        // ถ้าชื่อหมวดหมู่ตรงกับ Key ให้สร้างสินค้านั้นๆ
        $productMap = [
            'Beverages (เครื่องดื่ม)' => [
                ['name' => 'Coke Original 325ml', 'cost' => 10, 'price' => 15],
                ['name' => 'Pepsi 325ml', 'cost' => 10, 'price' => 15],
                ['name' => 'Water 600ml', 'cost' => 4, 'price' => 7],
                ['name' => 'Green Tea Honey', 'cost' => 12, 'price' => 20],
                ['name' => 'Black Coffee Canned', 'cost' => 15, 'price' => 25],
            ],
            'Snacks (ขนมขบเคี้ยว)' => [
                ['name' => 'Potato Chips BBQ', 'cost' => 18, 'price' => 30],
                ['name' => 'Seaweed Snack', 'cost' => 15, 'price' => 20],
                ['name' => 'Chocolate Bar', 'cost' => 12, 'price' => 25],
                ['name' => 'Biscuits Milk', 'cost' => 8, 'price' => 12],
            ],
            'Fresh Food (อาหารสด)' => [
                ['name' => 'Sandwich Ham Cheese', 'cost' => 20, 'price' => 35],
                ['name' => 'Burger Chicken', 'cost' => 25, 'price' => 40],
            ],
            'Personal Care (ของใช้ส่วนตัว)' => [
                ['name' => 'Shampoo 70ml', 'cost' => 15, 'price' => 29],
                ['name' => 'Soap Bar', 'cost' => 10, 'price' => 15],
                ['name' => 'Toothpaste', 'cost' => 12, 'price' => 25],
            ],
            'Household (ของใช้ในบ้าน)' => [
                ['name' => 'Dish Soap', 'cost' => 18, 'price' => 30],
                ['name' => 'Trash Bags (S)', 'cost' => 20, 'price' => 35],
            ],
        ];

        // ตัวแปรสำหรับ Gen รหัสสินค้าสมมติ (P0001, P0002, ...)
        $runningNumber = 1;

        foreach ($categories as $category) {
            // เช็คว่าหมวดหมู่นี้ มีรายการสินค้าที่เราเตรียมไว้ไหม
            if (isset($productMap[$category->name])) {
                $items = $productMap[$category->name];

                foreach ($items as $item) {
                    // Gen รหัสสินค้าเทียมๆ (P0001)
                    $fakeBarcode = 'P' . str_pad($runningNumber, 4, '0', STR_PAD_LEFT);

                    Product::updateOrCreate(
                        ['name' => $item['name']], // เช็คจากชื่อ ถ้ามีแล้วให้อัปเดต
                        [
                            'category_id' => $category->id,
                            'barcode' => $fakeBarcode, // ใส่รหัสเทียมไปก่อนตามกฏ DB
                            'cost' => $item['cost'],
                            'price' => $item['price'],
                            'stock_qty' => rand(10, 100), // สุ่มสต็อก 10-100 ชิ้น
                            'is_active' => true,
                            'created_by' => $adminId,
                            'updated_by' => $adminId,
                        ]
                    );

                    $runningNumber++;
                }
            }
        }
    }
}
