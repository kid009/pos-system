<?php

namespace Database\Seeders;

use App\Enums\ActionTypeEnum;
use App\Enums\ModuleTypeEnum;
use App\Enums\RoleTypeEnum;
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
        // 1. Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Dynamic Permission Generation
        $allPermissions = [];

        foreach (ModuleTypeEnum::cases() as $module) {
            foreach (ActionTypeEnum::cases() as $action) {
                // Generate name: e.g. "create categories", "viewAny roles"
                $permissionName = "{$action->value} {$module->value}";
                $allPermissions[] = $permissionName;

                Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
            }
        }

        // 3. Create Roles (Idempotent)
        $superAdminRole = Role::firstOrCreate(['name' => RoleTypeEnum::SUPER_ADMIN->value]);
        $ownerRole = Role::firstOrCreate(['name' => RoleTypeEnum::OWNER->value]);
        $employeeRole = Role::firstOrCreate(['name' => RoleTypeEnum::EMPLOYEE->value]);

        // 4. Assign Permissions to Roles (Super Admin ไม่ต้อง Assign)
        // Owner ให้ทั้งหมด
        $ownerRole->syncPermissions($allPermissions);

        // Employee
        $employeeTypeModule = [
            ModuleTypeEnum::PRODUCT_CATEGORY,
        ];

        $employeePermissions = [];
        foreach ($employeeTypeModule as $module) {
            $employeePermissions[] = ActionTypeEnum::VIEW_ANY->value.' '.$module->value;
            $employeePermissions[] = ActionTypeEnum::VIEW->value.' '.$module->value;
        }
        $employeeRole->syncPermissions($employeePermissions);

        // 5. Create Default Users (Idempotent)
        $this->seedDefaultUser('Super Admin', 'superadmin@mail.com', RoleTypeEnum::SUPER_ADMIN->value);
        $this->seedDefaultUser('Owner', 'owner@mail.com', RoleTypeEnum::OWNER->value);
        $this->seedDefaultUser('Employee', 'employee@mail.com', RoleTypeEnum::EMPLOYEE->value);
    }

    /**
     * Helper สำหรับสร้าง User ให้เขียนครั้งเดียวใช้ซ้ำได้ (DRY)
     */
    private function seedDefaultUser(string $name, string $email, string $role): void
    {
        // อัปเดตเฉพาะชื่อและรหัสผ่าน หากอีเมลนี้มีอยู่แล้ว (อิงจาก Email เป็นหลัก)
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make('password'),
                'is_banned' => false, // Set ค่าเริ่มต้น
                // 'uuid' => Str::uuid()->toString(), // กรณีตารางคุณบังคับใช้ UUID
            ]
        );

        // Assign Role
        if (! $user->hasRole($role)) {
            $user->assignRole($role);
        }
    }
}
