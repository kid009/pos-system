<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\MenuSeeder;
use Database\Seeders\CustomerSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class, // สร้าง Role/Permission ก่อน
            MenuSeeder::class,           // สร้าง Menu
            CustomerSeeder::class,       // นำเข้าข้อมูลลูกค้า (ถ้ามี)
        ]);
    }
}
