<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // สร้าง Role พื้นฐาน
        Role::insert([
            ['name' => 'admin',      'label' => 'ผู้ดูแลระบบสูงสุด', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'shop_owner', 'label' => 'เจ้าของร้าน',      'created_at' => now(), 'updated_at' => now()],
            ['name' => 'staff',      'label' => 'พนักงานทั่วไป',    'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
