<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ProductCategory::all();

        if ($categories->isEmpty()) {
            $this->command->info('Please run ProductCategorySeeder first!');
            return;
        }

        $gasCategory = $categories->firstWhere('name', 'ก๊าซหุงต้ม');
        $waterCategory = $categories->firstWhere('name', 'น้ำดื่ม');
        $accessoryCategory = $categories->firstWhere('name', 'อุปกรณ์เตาแก๊ส');
        $kitchenCategory = $categories->firstWhere('name', 'อุปกรณ์ห้องครัว');

        $products = [
            // ก๊าซหุงต้ม
            [
                'sku' => 'GAS-PTT-15',
                'name' => 'ถังแก๊ส ปตท. 15 กก. (สีเขียว)',
                'price' => 455.00,
                'cost' => 385.00,
                'stock_qty' => 50,
                'unit' => 'ถัง',
                'is_active' => true,
                'category_id' => $gasCategory?->id,
            ],
            [
                'sku' => 'GAS-PTT-7',
                'name' => 'ถังแก๊ส ปตท. 7 กก. (สีเขียว)',
                'price' => 265.00,
                'cost' => 216.00,
                'stock_qty' => 30,
                'unit' => 'ถัง',
                'is_active' => true,
                'category_id' => $gasCategory?->id,
            ],
            [
                'sku' => 'GAS-WG-15',
                'name' => 'ถังแก๊ส เวิลด์แก๊ส 15 กก. (สีชมพู)',
                'price' => 455.00,
                'cost' => 385.00,
                'stock_qty' => 25,
                'unit' => 'ถัง',
                'is_active' => true,
                'category_id' => $gasCategory?->id,
            ],
            [
                'sku' => 'GAS-PAP-48',
                'name' => 'ถังแก๊ส พีเอพี 48 กก. (อุตสาหกรรม)',
                'price' => 1500.00,
                'cost' => 1378.00,
                'stock_qty' => 10,
                'unit' => 'ถัง',
                'is_active' => true,
                'category_id' => $gasCategory?->id,
            ],
            // น้ำดื่ม
            [
                'sku' => 'WTR-SS-600',
                'name' => 'น้ำดื่ม ซีซั่น 600 มล. (แพ็ค 12 ขวด)',
                'price' => 85.00,
                'cost' => 65.00,
                'stock_qty' => 100,
                'unit' => 'แพ็ค',
                'is_active' => true,
                'category_id' => $waterCategory?->id,
            ],
            [
                'sku' => 'WTR-SS-1500',
                'name' => 'น้ำดื่ม ซีซั่น 1500 มล. (แพ็ค 6 ขวด)',
                'price' => 95.00,
                'cost' => 75.00,
                'stock_qty' => 80,
                'unit' => 'แพ็ค',
                'is_active' => true,
                'category_id' => $waterCategory?->id,
            ],
            [
                'sku' => 'WTR-SS-CUP',
                'name' => 'น้ำถ้วย ซีซั่น 230 มล. (ลัง 48 ถ้วย)',
                'price' => 180.00,
                'cost' => 150.00,
                'stock_qty' => 40,
                'unit' => 'ลัง',
                'is_active' => true,
                'category_id' => $waterCategory?->id,
            ],
            // อุปกรณ์เตาแก๊ส
            [
                'sku' => 'ACC-REG-SCG',
                'name' => 'หัวปรับแก๊สแรงดันสูง SCG',
                'price' => 280.00,
                'cost' => 200.00,
                'stock_qty' => 20,
                'unit' => 'ชิ้น',
                'is_active' => true,
                'category_id' => $accessoryCategory?->id,
            ],
            [
                'sku' => 'ACC-HOSE-1M',
                'name' => 'สายแก๊สหนา 1 เมตร',
                'price' => 50.00,
                'cost' => 30.00,
                'stock_qty' => 60,
                'unit' => 'เมตร',
                'is_active' => true,
                'category_id' => $accessoryCategory?->id,
            ],
            [
                'sku' => 'ACC-HOSE-2M',
                'name' => 'สายแก๊สหนา 2 เมตร',
                'price' => 90.00,
                'cost' => 55.00,
                'stock_qty' => 45,
                'unit' => 'เส้น',
                'is_active' => true,
                'category_id' => $accessoryCategory?->id,
            ],
            [
                'sku' => 'ACC-LGHT',
                'name' => 'ปืนจุดเตาแก๊ส',
                'price' => 25.00,
                'cost' => 15.00,
                'stock_qty' => 100,
                'unit' => 'ชิ้น',
                'is_active' => true,
                'category_id' => $accessoryCategory?->id,
            ],
            [
                'sku' => 'ACC-STV-SML',
                'name' => 'เตาแก๊สหัวเดี่ยว (ตั้งโต๊ะ)',
                'price' => 350.00,
                'cost' => 250.00,
                'stock_qty' => 15,
                'unit' => 'ชิ้น',
                'is_active' => true,
                'category_id' => $accessoryCategory?->id,
            ],
            // อุปกรณ์ห้องครัว
            [
                'sku' => 'KIT-PAN-30',
                'name' => 'กระทะเคลือบ Non-stick 30 ซม.',
                'price' => 299.00,
                'cost' => 180.00,
                'stock_qty' => 12,
                'unit' => 'ชิ้น',
                'is_active' => true,
                'category_id' => $kitchenCategory?->id,
            ],
            [
                'sku' => 'KIT-KTL-3L',
                'name' => 'กาต้มน้ำสแตนเลส 3 ลิตร',
                'price' => 450.00,
                'cost' => 280.00,
                'stock_qty' => 8,
                'unit' => 'ชิ้น',
                'is_active' => true,
                'category_id' => $kitchenCategory?->id,
            ],
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(
                ['sku' => $product['sku']],
                $product
            );
        }

        $this->command->info('✅ สร้างข้อมูลสินค้าเรียบร้อยแล้ว ('.count($products).' รายการ)');
    }
}