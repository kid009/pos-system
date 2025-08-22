<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Reset Spatie Cache (สำคัญมาก)
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. สร้าง Permissions จาก Array (ถ้ายังไม่มี)
        // Array นี้คือ "Source of Truth" ของสิทธิ์ทั้งหมดในระบบ
        $permissions = [
            // User Management
            'user.viewAny',
            'user.create',
            'user.update',
            'user.delete',
            // Role & Permission Management
            'role.viewAny',
            'role.create',
            'role.update',
            'role.delete',
            'permission.viewAny',
            'permission.create',
            'permission.update',
            'permission.delete',
            // Tenant & Branch Management
            'tenant.viewAny',
            'tenant.create',
            'tenant.update',
            'tenant.delete',
            'branch.viewAny',
            'branch.create',
            'branch.update',
            'branch.delete',
            // Product Management
            'product.viewAny',
            'product.create',
            'product.update',
            'product.delete',
            'category.viewAny',
            'category.create',
            'category.update',
            'category.delete',
            // Customer Management
            'customer.viewAny',
            'customer.create',
            'customer.update',
            'customer.delete',
            // Order & Sale Management
            'order.viewAny',
            'order.create',
            'order.update',
            'order.delete',
            'order.cancel',
            // Inventory Management
            'inventory.receive',
            'inventory.adjust',
            // Report Management
            'report.viewSales',
            'report.viewInventory',
        ];

        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
        }

        // 3. สร้าง Roles จาก Array (ถ้ายังไม่มี)
        $salesStaffRole    = Role::firstOrCreate(['name' => 'sales-staff']);
        $branchManagerRole = Role::firstOrCreate(['name' => 'branch-manager']);
        $superAdminRole    = Role::firstOrCreate(['name' => 'super-admin']);

        // 4. กำหนดสิทธิ์ให้แต่ละ Role (ส่วนของการ Update)
        // ใช้ syncPermissions เพื่อให้สิทธิ์ตรงกับ Array ด้านล่างเสมอ

        // Role: Sales Staff
        $salesStaffRole->syncPermissions([
            'customer.viewAny',
            'customer.create',
            'customer.update',
            'product.viewAny',
            'order.viewAny',
            'order.create',
        ]);

        // Role: Branch Manager
        $branchManagerRole->syncPermissions([
            'user.viewAny',
            'user.create',
            'user.update',
            'product.viewAny',
            'product.create',
            'product.update',
            'product.delete',
            'category.viewAny',
            'category.create',
            'category.update',
            'category.delete',
            'customer.viewAny',
            'customer.create',
            'customer.update',
            'customer.delete',
            'order.viewAny',
            'order.create',
            'order.update',
            'order.delete',
            'order.cancel',
            'inventory.receive',
            'inventory.adjust',
            'report.viewSales',
            'report.viewInventory',
        ]);

        // Role: Super Admin
        // Super Admin จะได้สิทธิ์ทั้งหมดที่มีในระบบเสมอ
        $superAdminRole->syncPermissions(Permission::all());
    }
}
