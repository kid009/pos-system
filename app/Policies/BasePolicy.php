<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

abstract class BasePolicy
{
    /**
     * โมดูลที่ Policy นี้รับผิดชอบ (บังคับให้ Sub-class ต้องประกาศ)
     */
    abstract protected function getModule(): string;

    protected function checkPermission(User $user, string $action): Response
    {
        $permissionName = "{$action} {$this->getModule()}";

        return $user->hasPermissionTo($permissionName)
            ? Response::allow()
            : Response::deny("คุณไม่มีสิทธิ์: {$permissionName} กรุณาแจ้ง Owner เพื่อขอสิทธิ์เพิ่มเติม");
    }

    public function viewAny(User $user): Response
    {
        return $this->checkPermission($user, 'viewAny');
    }

    public function view(User $user, mixed $model): Response
    {
        return $this->checkPermission($user, 'view');
    }

    public function create(User $user): Response
    {
        return $this->checkPermission($user, 'create');
    }

    public function update(User $user, mixed $model): Response
    {
        return $this->checkPermission($user, 'update');
    }

    public function delete(User $user, mixed $model): Response
    {
        return $this->checkPermission($user, 'delete');
    }
}
