<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. สร้าง Permissions (สิทธิ์การใช้งาน)
        $permissions = [
            'view_dashboard',
            'view_pos',
            'manage_products',
            'manage_categories',
            'manage_staff',
            'manage_expenses',
            'view_reports',
            'manage_shops' // สิทธิ์พิเศษสำหรับ admin
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 2. สร้าง Roles และกำหนดสิทธิ์ (แยกตามระดับ)

        // 🔹 admin (Global - ไม่มี Team)
        $superAdmin = Role::firstOrCreate([
            'name' => 'admin'
        ]);
        // (เราจะไม่ผูก Permission ให้ admin ตรงๆ แต่จะใช้ Gate::before แทนในตอนหลัง)

        // 🔹 shop_owner (ทำได้เกือบทุกอย่างในร้าน)
        $shopOwner = Role::firstOrCreate([
            'name' => 'shop_owner'
        ]);
        $shopOwner->syncPermissions([
            'view_dashboard',
            'view_pos',
            'manage_products',
            'manage_categories',
            'manage_staff',
            'manage_expenses',
            'view_reports'
        ]);

        // 🔹 Staff (พนักงานขาย)
        $staff = Role::firstOrCreate([
            'name' => 'staff'
        ]);
        $staff->syncPermissions([
            'view_pos'
        ]);
    }
}
