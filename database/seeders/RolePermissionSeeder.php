<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset Cached Roles/Permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. สร้าง Permissions ทั้งหมด
        $permissions = [
            'view_dashboard',
            'access_pos',
            'view_orders',
            'manage_products',
            'manage_customers', // ✅ เพิ่ม Permission จัดการลูกค้า
            'manage_users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. สร้าง Roles และ Assign Permissions

        // Role: Admin (ได้ทุกอย่าง)
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions(Permission::all());

        // Role: Employee (ได้บางอย่าง)
        $employeeRole = Role::firstOrCreate(['name' => 'employee']);
        $employeeRole->syncPermissions([
            'view_dashboard',
            'access_pos',
            'view_orders',
            'manage_customers', // ✅ ให้พนักงานจัดการลูกค้าได้ (เพิ่ม/ค้นหาลูกค้าตอนขาย)
        ]);

        // 4. Create Users (สร้าง User ทดสอบ)

        // Admin
        $admin = User::firstOrCreate([
            'email' => 'admin@pgas.com',
        ], [
            'name' => 'Admin Owner',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // Staff
        $staff = User::firstOrCreate([
            'email' => 'staff@pgas.com',
        ], [
            'name' => 'Staff A',
            'password' => Hash::make('password'),
        ]);
        $staff->assignRole('employee');
    }
}
