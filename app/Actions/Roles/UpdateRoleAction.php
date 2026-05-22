<?php

declare(strict_types=1);

namespace App\Actions\Role;

use App\DTOs\RoleDTO;
use App\Enums\RoleTypeEnum;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UpdateRoleAction
{
    public function execute(Role $role, RoleDTO $dto): Role
    {
        // 1. Core Role Protection (Failsafe Level)
        $coreRoles = [RoleTypeEnum::SUPER_ADMIN->value, RoleTypeEnum::OWNER->value];
        if (in_array($role->name, $coreRoles)) {
            throw new InvalidArgumentException("ไม่อนุญาตให้แก้ไขสิทธิ์หรือชื่อของ System Core Role ({$role->name})");
        }

        return DB::transaction(function () use ($role, $dto) {
            // 2. Update Name
            $role->name = $dto->name;
            $role->save();

            // 3. Sync Permissions (Spatie จะเคลียร์ของเก่าและใส่ของใหม่ให้เอง)
            $role->syncPermissions($dto->permissions);

            // 4. Clear Cache ทันทีเพื่อให้สิทธิ์ใหม่ทำงาน
            app()[PermissionRegistrar::class]->forgetCachedPermissions();

            return $role;
        });
    }
}
