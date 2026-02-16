<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Shop;

class ShopSeeder extends Seeder
{
    public function run(): void
    {
        // สร้างร้านตัวอย่าง 2 ประเภท
        Shop::create([
            'name' => 'P-Gas (ร้านแก๊ส)',
            'address' => '123 ถ.มิตรภาพ โคราช',
            'phone' => '081-111-1111',
        ]);

        Shop::create([
            'name' => 'P-Coffee (ร้านกาแฟ)',
            'address' => '456 ถ.สืบศิริ โคราช',
            'phone' => '082-222-2222',
        ]);
    }
}
