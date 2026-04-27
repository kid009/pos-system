<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the master data seeders.
     * รวม seeder ทั้งหมดสำหรับข้อมูลพื้นฐาน (Master Data) ของระบบ
     */
    public function run(): void
    {
        $this->command->info('🚀 เริ่มต้น seeding ข้อมูลพื้นฐาน (Master Data)...');
        $this->command->info('');

        // 1. ข้อมูลการเงิน
        $this->command->info('💰 กำลังสร้างข้อมูลธนาคาร...');
        $this->call(BankSeeder::class);

        // 2. ข้อมูลการขนส่ง
        $this->command->info('🚚 กำลังสร้างข้อมูลวิธีการขนส่ง...');
        $this->call(ShippingMethodSeeder::class);

        // 3. ข้อมูลช่องทางการขาย
        $this->command->info('🛒 กำลังสร้างข้อมูลช่องทางการขาย...');
        $this->call(SalesChannelSeeder::class);

        // 4. ข้อมูลซัพพลายเออร์
        $this->command->info('🏭 กำลังสร้างข้อมูลซัพพลายเออร์...');
        $this->call(SupplierSeeder::class);

        // 5. ข้อมูลหมวดหมู่สินค้า
        $this->command->info('📂 กำลังสร้างข้อมูลหมวดหมู่สินค้า...');
        $this->call(ProductCategorySeeder::class);

        // 6. ข้อมูลสินค้า
        $this->command->info('📦 กำลังสร้างข้อมูลสินค้า...');
        $this->call(ProductSeeder::class);

        // 7. ข้อมูลลูกค้า
        $this->command->info('👥 กำลังสร้างข้อมูลลูกค้า...');
        $this->call(CustomerSeeder::class);

        // 8. ข้อมูลหมวดหมู่ค่าใช้จ่าย
        $this->command->info('💸 กำลังสร้างข้อมูลหมวดหมู่ค่าใช้จ่าย...');
        $this->call(ExpenseCategorySeeder::class);

        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('🎉 Master Data Seeding เสร็จสมบูรณ์!');
        $this->command->info('========================================');
        $this->command->info('');
    }
}
