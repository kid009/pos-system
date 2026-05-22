<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use App\DTOs\RoleDTO;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/**
 * Action สำหรับสร้าง Role ใหม่
 */
class CreateRoleAction
{
    /**
     * สร้าง Role ใหม่
     */
    public function execute(RoleDTO $dto): Role
    {
        return DB::transaction(function () use ($dto): Role {
            $role = Role::create($dto->toArray());

            // Assign permissions ถ้ามี
            if (! empty($dto->permissions)) {
                $role->permissions()->sync($dto->permissions);
            }

            // บันทึก activity log
            activity()
                ->causedBy(auth()->user())
                ->performedOn($role)
                ->log("สร้าง Role: {$role->name}");

            return $role->load('permissions');
        });
    }
}
