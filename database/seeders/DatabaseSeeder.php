<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------
        // 1. สร้าง Users หลักสำหรับการทดสอบระบบ (RBAC)
        // ---------------------------------------------------
        $admin = User::firstOrCreate(
            ['email' => 'admin@pgas.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        $staff = User::firstOrCreate(
            ['email' => 'staff@pgas.com'],
            [
                'name' => 'พนักงานขาย หน้าร้าน',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'is_active' => true,
            ]
        );

        // ---------------------------------------------------
        // 2. สร้าง Shops สมจริง 2 สาขา
        // ---------------------------------------------------
        $shops = [
            Shop::create([
                'name' => 'สาขาหลัก (ขามทะเลสอ)',
                'branch_code' => '00000',
                'phone' => '081-111-1111',
                'is_active' => true,
                'created_by' => $admin->id,
            ]),
            Shop::create([
                'name' => 'สาขา 2 (ในเมือง)',
                'branch_code' => '00001',
                'phone' => '082-222-2222',
                'is_active' => true,
                'created_by' => $admin->id,
            ]),
        ];

        // กำหนดให้ Staff สังกัดสาขาหลัก (ถ้าในตาราง users มีคอลัมน์ shop_id)
        // $staff->update(['shop_id' => $shops[0]->id]);

        // ---------------------------------------------------
        // 3. หมวดหมู่และสินค้าสมจริง (Business Domain Data)
        // ---------------------------------------------------
        $businessData = [
            'ก๊าซหุงต้ม' => [
                ['name' => 'น้ำแก๊ส ปตท. ขนาด 15 กก', 'price' => 455, 'cost' => 385, 'unit' => 'ถัง', 'sku' => 'PTT-15KG'],
                ['name' => 'น้ำแก๊ส ปตท. ขนาด 7 กก', 'price' => 265, 'cost' => 216, 'unit' => 'ถัง', 'sku' => 'PTT-7KG'],
                ['name' => 'น้ำแก๊ส เวิลด์แก๊ส ขนาด 15 กก', 'price' => 455, 'cost' => 385, 'unit' => 'ถัง', 'sku' => 'WG-15KG'],
                ['name' => 'น้ำแก๊ส พีเอพี ขนาด 48 กก', 'price' => 1500, 'cost' => 1378, 'unit' => 'ถัง', 'sku' => 'PAP-48KG'],
            ],
            'น้ำดื่ม' => [
                ['name' => 'น้ำดื่ม ยี่ห้อ ซีซั่น ขนาด 600 มล', 'price' => 40, 'cost' => 30, 'unit' => 'แพ็ค', 'sku' => 'WTR-600ML'],
                ['name' => 'น้ำดื่ม ยี่ห้อ ซีซั่น ขนาด 1500 มล', 'price' => 45, 'cost' => 35, 'unit' => 'แพ็ค', 'sku' => 'WTR-1500ML'],
                ['name' => 'น้ำถ้วย ยี่ห้อ ซีซั่น', 'price' => 35, 'cost' => 25, 'unit' => 'ลัง', 'sku' => 'WTR-CUP'],
            ],
            'อุปกรณ์เตาแก๊ส' => [
                ['name' => 'หัวปรับแก๊สแรงดันสูง SCG', 'price' => 280, 'cost' => 200, 'unit' => 'ชิ้น', 'sku' => 'ACC-SCG-01'],
                ['name' => 'สายแก๊สหนา (เมตร)', 'price' => 50, 'cost' => 30, 'unit' => 'เมตร', 'sku' => 'ACC-CABLE'],
                ['name' => 'ปืนจุดเตาแก๊ส', 'price' => 25, 'cost' => 15, 'unit' => 'ชิ้น', 'sku' => 'ACC-GUN'],
            ]
        ];

        // ---------------------------------------------------
        // 4. ร้อยเรียงข้อมูลเข้าด้วยกัน (Tenant Data Assignment)
        // ---------------------------------------------------
        foreach ($shops as $shop) {
            foreach ($businessData as $categoryName => $products) {
                // ก. สร้างหมวดหมู่ ผูกกับร้านค้า
                $category = Category::create([
                    'shop_id' => $shop->id,
                    'name' => $categoryName,
                    'is_active' => true,
                    'created_by' => $admin->id,
                ]);

                // ข. สร้างสินค้า ผูกกับร้านค้าและหมวดหมู่
                foreach ($products as $prod) {
                    Product::create([
                        'shop_id' => $shop->id,
                        'category_id' => $category->id,
                        'name' => $prod['name'],
                        'sku' => $prod['sku'] . '-' . $shop->id, // ทำให้ SKU ไม่ซ้ำกันระหว่างสาขา
                        'price' => $prod['price'],
                        'cost' => $prod['cost'],
                        'unit' => $prod['unit'],
                        'is_active' => true,
                        'created_by' => $admin->id,
                    ]);
                }
            }

            // ค. สร้างสินค้าจำลองเพิ่มเติม (Stress Test Data) สาขาละ 50 ชิ้น ด้วย Factory
            Product::factory()->count(50)->create([
                'shop_id' => $shop->id,
                // สุ่ม Category เฉพาะของร้านนี้
                'category_id' => Category::where('shop_id', $shop->id)->inRandomOrder()->first()->id,
                'created_by' => $admin->id,
            ]);
        }

        $this->command->info('จำลองข้อมูลระบบ POS (Shops, Categories, Products) เสร็จสมบูรณ์!');
        $this->command->info('Admin Login: admin@pgas.com / password');
        $this->command->info('Staff Login: staff@pgas.com / password');
    }
}
