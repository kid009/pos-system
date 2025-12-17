<?php

namespace App\Livewire\Role;

use Livewire\Component;
use Spatie\Permission\Models\Role;

class RoleIndex extends Component
{
    public function delete($id)
    {
        // ป้องกันไม่ให้ลบ Super Admin
        if ($id == 1) {
            session()->flash('error', 'ไม่สามารถลบ Super Admin ได้');
            return;
        }

        Role::find($id)->delete();
        session()->flash('success', 'ลบข้อมูลเรียบร้อยแล้ว');
    }

    public function render()
    {
        $roles = Role::with('permissions')->orderBy('id')->get();

        return view('livewire.role.role-index', compact('roles'));
    }
}
