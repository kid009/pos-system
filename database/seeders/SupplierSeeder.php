<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'บริษัท ปตท. จำกัด (มหาชน)',
                'contact_name' => 'คุณสมชาย ใจดี',
                'phone' => '02-123-4567',
                'address' => '555 ถนนวิภาวดีรังสิต แขวงจตุจักร เขตจตุจักร กรุงเทพฯ 10900',
                'tax_id' => '0107542000153',
                'is_active' => true,
            ],
            [
                'name' => 'บริษัท เวิลด์แก๊ส จำกัด',
                'contact_name' => 'คุณวิชัย แก๊สดี',
                'phone' => '02-234-5678',
                'address' => '123 ถนนสุขุมวิท แขวงบางนา เขตบางนา กรุงเทพฯ 10260',
                'tax_id' => '0105543000264',
                'is_active' => true,
            ],
            [
                'name' => 'บริษัท ซีซั่น แบรนด์ จำกัด',
                'contact_name' => 'คุณนภา น้ำใส',
                'phone' => '02-345-6789',
                'address' => '456 ถนนรามคำแหง แขวงหัวหมาก เขตบางกะปิ กรุงเทพฯ 10240',
                'tax_id' => '0106544000375',
                'is_active' => true,
            ],
            [
                'name' => 'บริษัท เอสซีจี ดิสทริบิวชั่น จำกัด',
                'contact_name' => 'คุณประสิทธิ์ แข็งแรง',
                'phone' => '02-456-7890',
                'address' => '789 ถนนรัชดาภิเษก แขวงดินแดง เขตดินแดง กรุงเทพฯ 10400',
                'tax_id' => '0107545000486',
                'is_active' => true,
            ],
            [
                'name' => 'ร้านค้าส่งก๊าซหุงต้ม หนองคาย',
                'contact_name' => 'คุณประเสริฐ มีทรัพย์',
                'phone' => '042-123-456',
                'address' => '12 ถนนมิตรภาพ ตำบลในเมือง อำเภอเมือง จังหวัดหนองคาย 43000',
                'tax_id' => '0412345678901',
                'is_active' => true,
            ],
            [
                'name' => 'บริษัท น้ำดื่มสะอาด จำกัด',
                'contact_name' => 'คุณสุดใจ สะอาด',
                'phone' => '02-567-8901',
                'address' => '321 ถนนพระราม 9 แขวงดินแดง เขตดินแดง กรุงเทพฯ 10400',
                'tax_id' => '0108546000597',
                'is_active' => false,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(['name' => $supplier['name']], $supplier);
        }
    }
}