<?php

namespace App\Livewire\Admin;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class UserComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // ตัวแปรรับค่า Form
    public $name, $email, $password, $role, $shop_id;
    public $search = '';
    public $editingId = null;

    // 1. Render แสดงผล
    public function render()
    {
        $currentUser = Auth::user();

        // ดึงค่า Shop ID จาก Session (ใส่ default เป็น 0 ไว้กันเหนียวเผื่อ session หลุด)
        $currentShopId = $currentUser->shops()->first()->id ?? 0;

        // เริ่มต้น Query
        $query = User::query();

        // 🔒 1. Data Visibility Logic (บังคับกรองร้านค้า "ก่อน" เสมอ)
        if ($currentUser->role === 'admin') {

            // Admin: เห็นทุกคนในระบบ (ไม่ต้องใช้ whereHas กรองร้าน)
            $shops = Shop::orderBy('name')->get();

        } else {

            // Shop Owner / Staff: เห็นเฉพาะในร้านตัวเอง
            // 🐛 จุดแก้บั๊ก: เปลี่ยน 'shops.id' เป็น 'id' เฉยๆ เพื่อไม่ให้ Laravel สับสน alias ตาราง
            $query->whereHas('shops', function($q) use ($currentShopId) {
                $q->where('shops.id', $currentShopId);
            });

            $shops = []; // Owner ไม่ต้องเลือกร้าน
        }

        // 🔍 2. Search Logic (ทำทีหลังการกรองร้าน เพื่อไม่ให้เผลอไปดึงคนร้านอื่นมา)
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        // 📋 3. จัดเรียงและแบ่งหน้า
        // จัดให้ Owner อยู่บน Staff เสมอ
        $users = $query->orderByRaw("FIELD(role, 'admin', 'shop_owner', 'staff')")
                       ->orderBy('id', 'desc')
                       ->paginate(10);

        return view('livewire.admin.user-component', [
            'users' => $users,
            'shops' => $shops,
            'isAdmin' => $currentUser->role === 'admin'
        ]);
    }

    // 2. เปิด Modal เพิ่มผู้ใช้
    public function create()
    {
        $this->reset(['name', 'email', 'password', 'role', 'shop_id', 'editingId']);

        // ค่า Default
        if (Auth::user()->role !== 'admin') {
            $this->role = 'staff'; // ถ้าไม่ใช่ Admin บังคับสร้างได้แค่ Staff
        }

        $this->dispatch('show-modal');
    }

    // 3. เปิด Modal แก้ไข
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();
        $currentShopId = $currentUser->shops()->first()->id ?? 0;

        // 🛡️ Security Check: ห้าม Owner แอบแก้ User ข้ามร้านโดยเด็ดขาด
        if ($currentUser->role !== 'admin') {
            $isInSameShop = $user->shops()->where('shops.id', $currentShopId)->exists();
            if (!$isInSameShop) {
                $this->dispatch('notify', message: '⚠️ ไม่อนุญาต! คุณไม่มีสิทธิ์แก้ไขพนักงานของร้านอื่น', type: 'error');
                return;
            }
        }

        $this->editingId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = ''; // ปล่อยว่างไว้ ให้กรอกเฉพาะตอนอยากเปลี่ยนรหัส

        // ถ้าเป็น Admin ให้โหลด shop_id ปัจจุบันของ User มาโชว์
        if ($currentUser->role === 'admin') {
            $this->shop_id = $user->shops->first()->id ?? null;
        }

        $this->dispatch('show-modal');
    }

    // 4. บันทึก (Save)
    public function save()
    {
        $currentUser = Auth::user();

        // --- 1. Validation Rules ---
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->editingId)],
            'role' => 'required',
        ];

        if (!$this->editingId) {
            $rules['password'] = 'required|min:6'; // สร้างใหม่บังคับใส่รหัส
        } else {
            $rules['password'] = 'nullable|min:6'; // แก้ไขไม่ต้องใส่ก็ได้
        }

        // ถ้า Admin สร้างพนักงานทั่วไป (ไม่ใช่ Admin) "ต้องเลือกร้าน"
        if ($currentUser->role === 'admin' && $this->role !== 'admin') {
            $rules['shop_id'] = 'required|exists:shops,id';
        }

        $this->validate($rules);

        // --- 2. Create / Update User ---
        if ($this->editingId) {
            $user = User::find($this->editingId);
            $data = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role,
            ];
            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }
            $user->update($data);
        } else {
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'role' => $this->role,
                'is_active' => true,
            ]);
        }

        // 🎯 เตรียมชื่อ Role เพื่อให้ตรงกับในฐานข้อมูลของ Spatie (พิมพ์เล็ก/ใหญ่)
        $spatieRoleName = match($this->role) {
            'admin' => 'Super Admin',
            'shop_owner' => 'Shop Owner',
            'staff' => 'Staff',
            default => 'Staff'
        };

        // --- 3. Shop Assignment & Spatie RBAC Logic (1 คน 1 ร้าน) ---
        if ($currentUser->role === 'admin') {

            if ($this->role === 'admin') {
                // กรณีตั้งเป็น Admin ระบบ -> ลบร้านค้าทิ้งทั้งหมด และให้สิทธิ์แบบ Global
                $user->shops()->detach();
                setPermissionsTeamId(null);
                $user->syncRoles(['Super Admin']);
            } else {
                // กรณีเลือกเป็น Owner/Staff -> ยัดเข้าร้านที่เลือก (ใช้ sync เพื่อลบร้านเก่าออกอัตโนมัติ)
                $user->shops()->sync([$this->shop_id => ['role' => $this->role]]);
                setPermissionsTeamId($this->shop_id);
                $user->syncRoles([$spatieRoleName]);
            }

        } else {
            // กรณีผู้สร้างคือ Shop Owner -> บังคับยัดเข้า "ร้านปัจจุบัน" เท่านั้น
            $currentShopId = $currentUser->shops()->first()->id ?? 0;

            // ใช้ sync เพื่อเคลียร์ร้านอื่นออก (กรณี User เคยอยู่ร้านอื่นมาก่อน)
            $user->shops()->sync([$currentShopId => ['role' => $this->role]]);

            setPermissionsTeamId($currentShopId);
            $user->syncRoles([$spatieRoleName]);
        }

        $this->dispatch('close-modal');
        $this->dispatch('notify', message: 'บันทึกข้อมูลและอัปเดตสิทธิ์เรียบร้อย', type: 'success');
    }

    // 5. ลบผู้ใช้
    public function delete($id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        // 🛡️ ป้องกันการลบตัวเอง
        if ($user->id === $currentUser->id) {
            $this->dispatch('notify', message: '⚠️ คุณไม่สามารถลบตัวเองออกจากระบบได้', type: 'error');
            return;
        }

        // 🛡️ Security Check: ห้าม Owner แอบลบพนักงานร้านอื่น
        if ($currentUser->role !== 'admin') {
            $currentShopId = $currentUser->shops()->first()->id ?? 0;
            $isInSameShop = $user->shops()->where('shops.id', $currentShopId)->exists();

            if (!$isInSameShop) {
                $this->dispatch('notify', message: '⚠️ ไม่อนุญาต! คุณไม่มีสิทธิ์ลบพนักงานร้านอื่น', type: 'error');
                return;
            }
        }

        // หากผ่านเงื่อนไขให้ลบได้เลย
        $user->delete();
        $this->dispatch('notify', message: 'ลบผู้ใช้งานเรียบร้อย', type: 'success');
    }
}
