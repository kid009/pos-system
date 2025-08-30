<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ดึงข้อมูล Role ทั้งหมด, เรียงจากล่าสุด, แบ่งหน้า
        // พร้อมนับจำนวน permissions ที่ผูกกับแต่ละ Role มาด้วยเพื่อประสิทธิภาพ
        $roles = Role::withCount('permissions')->orderBy('id', 'asc')->paginate(10);
        
        return view('admin.roles.index', [
            'roles' => $roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ดึง Permissions ทั้งหมด และจัดกลุ่มตามชื่อ (เช่น user.view, user.create)
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('.', $permission->name)[0]; // จัดกลุ่มด้วยคำข้างหน้าสุด เช่น 'product'
        });
        
        return view('admin.roles.create', [
            'permissions' => $permissions,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|unique:roles,name',
            'permissions' => 'nullable|array' // permissions อาจจะไม่ถูกส่งมาก็ได้
        ]);

        // สร้าง Role ใหม่จากชื่อที่ส่งมา
        $role = Role::create(['name' => $validated['name']]);

        // ตรวจสอบว่ามีการส่ง permissions มาหรือไม่
        if ($request->has('permissions')) {
            // syncPermissions คือฟังก์ชันของ Spatie ที่จะผูก Permissions ที่เลือกเข้ากับ Role นี้
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // ดึง Permissions ทั้งหมดมาเพื่อแสดงในฟอร์ม และจัดกลุ่ม
        $permissions = Permission::all()->groupBy(function($permission) {
            return explode('.', $permission->name)[0];
        });

        $role = Role::find($id);

        // ดึงชื่อ permission ที่ role นี้มีอยู่แล้ว มาเก็บในรูปแบบ array เพื่อใช้ใน View
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('admin.roles.edit', [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $role = Role::find($id);

        $validated = $request->validate([
            'name' => 'required|string|min:3|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array'
        ]);

        $role->update(['name' => $validated['name']]);

        // ใช้ ?? [] เพื่อป้องกัน error หากไม่มีการเลือก permission เลย (จะส่งค่าเป็น array ว่าง)
        $role->syncPermissions($request->permissions ?? []);

        return to_route('admin.roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);

        // ป้องกันการลบ Role สำคัญ
        if ($role->name === 'super-admin') 
        {
            return back()->with('error', 'Cannot delete the Super Admin role.');
        }

        // (แนะนำ) ป้องกันการลบ Role ที่มี User ใช้งานอยู่
        if ($role->users()->count() > 0) 
        {
            return back()->with('error', "Cannot delete the '{$role->name}' role because it is assigned to {$role->users()->count()} users.");
        }

        $role->delete();

        return back()->with('success', 'Role deleted successfully.');
    }
}
