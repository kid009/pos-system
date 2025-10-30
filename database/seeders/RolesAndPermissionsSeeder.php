<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ล้าง cache ของ roles และ permissions ก่อน
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. สร้าง Roles ที่ต้องการ
        $adminRole = Role::create(['name' => 'admin']);
        $staffRole = Role::create(['name' => 'staff']);
        $employeeRole = Role::create(['name' => 'employee']);

        // (Optional) สร้าง Permissions ตัวอย่าง (ถ้าต้องการ)
        // Permission::create(['name' => 'edit articles']);

        // 2. สร้าง Admin User เริ่มต้น
        $adminUser = User::create([
            'name' => 'Admin P-Gas',
            'email' => 'admin@pgas.com', // อีเมลสำหรับ login
            'password' => Hash::make('password') // *** ตั้งรหัสผ่านที่ปลอดภัย!
        ]);

        // 3. ผูก Role 'admin' ให้กับ User นี้
        $adminUser->assignRole($adminRole);

        // (Optional) สร้าง Staff User ตัวอย่าง
        $staffUser = User::create([
            'name' => 'Staff P-Gas',
            'email' => 'staff@pgas.com',
            'password' => Hash::make('password')
        ]);
        $staffUser->assignRole($staffRole);
    }
}
