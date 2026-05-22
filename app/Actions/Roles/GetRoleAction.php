<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use Spatie\Permission\Models\Role;

/**
 * Action สำหรับดึงข้อมูล Role
 */
class GetRoleAction
{
    /**
     * ดึงข้อมูล Role พร้อม eager loading
     */
    public function execute(Role $role): Role
    {
        return $role->load([
            'permissions',
            'users' => fn ($query) => $query->limit(10),
        ]);
    }
}
