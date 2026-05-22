<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Actions\Role\UpdateRoleAction;
use App\Enums\ModuleTypeEnum;
use App\Enums\RoleTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Role::class);

        // 1. รับค่าคำค้นหาจาก Query String (ถ้าไม่มีให้เป็น null)
        $search = $request->input('search');

        // 2. สร้าง Query แบบ Optimize Performance
        $roles = Role::query()
            // ให้ Database ทำการนับความสัมพันธ์และคืนค่าเป็น attribute: permissions_count, users_count
            ->withCount(['permissions', 'users'])
            // หากมีการค้นหา ให้เพิ่มเงื่อนไข WHERE LIKE
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            // เรียงลำดับจากวันที่อัปเดตล่าสุด หรือเรียงตามชื่อ
            ->latest('id')
            // แบ่งหน้าละ 20 รายการ
            ->paginate(20)
            // สำคัญมาก: ให้ Pagination link ห้อย ?search=... ไปด้วยเวลาเปลี่ยนหน้า
            ->withQueryString();

        // 3. ส่งข้อมูลกลับไปยัง Blade
        return view('admin.roles.index', [
            'roles' => $roles,
            'search' => $search,
        ]);
    }

    public function create()
    {
        Gate::authorize('create', Role::class);

        $permissions = $this->getGroupedPermissions();

        return view('admin.roles.create', compact('permissions'));
    }

    public function edit(Role $role)
    {
        Gate::authorize('update', $role);

        // ป้องกันการเข้ามาผ่าน URL ตรงๆ
        if (in_array($role->name, [RoleTypeEnum::SUPER_ADMIN->value, RoleTypeEnum::OWNER->value])) {
            return redirect()->route('roles.index')->with('error', 'ไม่อนุญาตให้แก้ไข Core Role');
        }

        $groupedPermissions = $this->getGroupedPermissions();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', compact('role', 'groupedPermissions', 'rolePermissions'));
    }

    public function update(RoleRequest $request, Role $role, UpdateRoleAction $action)
    {
        Gate::authorize('update', $role);

        try {
            $action->execute($role, $request->toDTO());

            return redirect()->route('roles.index')->with('success', 'อัปเดตสิทธิ์สำเร็จ');
        } catch (\InvalidArgumentException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Helper สำหรับจัดกลุ่มสิทธิ์ตาม Module ส่งให้ Blade
     */
    private function getGroupedPermissions(): array
    {
        $permissions = Permission::all();
        $grouped = [];

        foreach (ModuleTypeEnum::cases() as $module) {
            // ค้นหาสิทธิ์ที่ลงท้ายด้วยชื่อ Module
            $grouped[$module->value] = $permissions->filter(function ($perm) use ($module) {
                return str_ends_with($perm->name, $module->value);
            });
        }

        return $grouped;
    }
}
