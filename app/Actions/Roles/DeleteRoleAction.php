<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/**
 * Action สำหรับลบ Role
 */
class DeleteRoleAction
{
    /**
     * ลบ Role
     *
     * @throws \Exception เมื่อไม่สามารถลบได้ (เช่น มี users ผูกอยู่)
     */
    public function execute(Role $role): void
    {
        DB::transaction(function () use ($role): void {
            // ตรวจสอบว่ามี users ผูกกับ role นี้หรือไม่
            if ($role->users()->exists()) {
                throw new \Exception("ไม่สามารถลบ Role '{$role->name}' ได้ เพราะมีผู้ใช้งานผูกอยู่");
            }

            $roleName = $role->name;

            // Detach permissions ก่อนลบ
            $role->permissions()->detach();

            $role->delete();

            // บันทึก activity log
            activity()
                ->causedBy(auth()->user())
                ->log("ลบ Role: {$roleName}");
        });
    }
}
