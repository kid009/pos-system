<?php

declare(strict_types=1);

namespace App\Actions\Roles;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;

/**
 * Action สำหรับดึงรายการ Role
 */
class ListRolesAction
{
    /**
     * ดึงรายการ Role พร้อม filters
     *
     * @param  array<string, mixed>  $filters
     */
    public function execute(array $filters = []): LengthAwarePaginator
    {
        return Role::query()
            ->withCount('permissions', 'users') // Eager load counts
            ->when($filters['search'] ?? null, function ($query, string $search): void {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($filters['guard_name'] ?? null, function ($query, string $guardName): void {
                $query->where('guard_name', $guardName);
            })
            ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_order'] ?? 'desc')
            ->paginate($filters['per_page'] ?? 15);
    }
}
