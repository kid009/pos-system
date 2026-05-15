<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset cached roles and permissions (จำเป็นมากสำหรับ Spatie)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. สร้าง Permissions ขั้นต้น
        $permissions = [
            'view_reports',
            'manage_users',
            'manage_inventory',
            'create_orders',
            'cancel_orders'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 3. สร้าง Roles และผูกสิทธิ์
        $roleOwner = Role::firstOrCreate(['name' => 'Owner']);
        $roleOwner->givePermissionTo(Permission::all()); // Owner ได้ทุกสิทธิ์

        $roleAdmin = Role::firstOrCreate(['name' => 'Admin']);
        $roleAdmin->givePermissionTo(['manage_inventory', 'create_orders', 'cancel_orders']);

        $roleStaff = Role::firstOrCreate(['name' => 'Stock_Staff']);
        $roleStaff->givePermissionTo(['manage_inventory']);

        // 4. สร้าง User ระดับ Owner ไว้สำหรับ Login เข้าระบบครั้งแรก
        $ownerUser = User::firstOrCreate([
            'email' => 'owner@example.com'
        ], [
            'name' => 'System Owner',
            'password' => Hash::make('password'),
        ]);

        // ผูก Role ให้ User
        if (!$ownerUser->hasRole('Owner')) {
            $ownerUser->assignRole($roleOwner);
        }
    }
}
