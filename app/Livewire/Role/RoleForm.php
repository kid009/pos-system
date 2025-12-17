<?php

namespace App\Livewire\Role;

use Livewire\Component;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleForm extends Component
{
    public $roleId; // ถ้ามีค่า = แก้ไข, ถ้า null = สร้างใหม่
    public $name;
    public $selectedPermissions = []; // เก็บ ID ของ permission ที่ถูกติ๊ก

    public function mount($role = null)
    {
        if ($role) {
            // โหมดแก้ไข: ดึงข้อมูลเดิมมาใส่
            $roleModel = Role::findOrFail($role);
            $this->roleId = $roleModel->id;
            $this->name = $roleModel->name;
            // ดึง permission ที่ Role นี้มีอยู่แล้วมาใส่ array
            $this->selectedPermissions = $roleModel->permissions->pluck('name')->toArray();
        }
    }

    public function save()
    {
        // 1. Validate
        $this->validate([
            'name' => 'required|unique:roles,name,' . $this->roleId,
            'selectedPermissions' => 'array'
        ]);

        // 2. Create or Update
        if ($this->roleId) {
            $role = Role::find($this->roleId);
            $role->update(['name' => $this->name]);
        } else {
            $role = Role::create(['name' => $this->name]);
        }

        // 3. Sync Permissions (หัวใจสำคัญ: อัปเดตตาราง pivot)
        // ต้องแปลงชื่อเป็น object permission ก่อน sync หรือ sync ด้วยชื่อตาม config spatie
        $role->syncPermissions($this->selectedPermissions);

        // 4. Redirect
        session()->flash('success', 'บันทึกข้อมูลเรียบร้อยแล้ว');
        return $this->redirect(route('roles.index'), navigate: true);
    }

    public function render()
    {
        // จัดกลุ่ม Permission เพื่อให้ดูง่ายในหน้าจอ (Optional)
        $permissions = Permission::all();

        return view('livewire.role.role-form', [
            'permissions' => $permissions
        ]);
    }
}
