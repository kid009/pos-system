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
        $currentShopId = session('current_shop_id');

        $query = User::query();

        // 🔍 Search Logic
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%'.$this->search.'%')
                  ->orWhere('email', 'like', '%'.$this->search.'%');
            });
        }

        // 🔒 Permission Logic
        if ($currentUser->role === 'admin') {
            // Admin เห็นทุกคน
            $users = $query->orderBy('id', 'desc')->paginate(10);

            // โหลดร้านค้าทั้งหมดใส่ Dropdown (เผื่อ Admin จะจับคู่ร้านให้ User)
            $shops = Shop::all();
        } else {
            // Shop Owner เห็นเฉพาะคนในร้านตัวเอง (ผ่านตาราง shop_user)
            $users = $query->whereHas('shops', function($q) use ($currentShopId) {
                $q->where('shops.id', $currentShopId);
            })->orderBy('id', 'desc')->paginate(10);

            $shops = []; // Owner ไม่ต้องเลือกร้าน (Auto)
        }

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
            $this->role = 'staff'; // ถ้าไม่ใช่ Admin ให้สร้างได้แค่ Staff
        }

        $this->dispatch('show-modal');
    }

    // 3. เปิด Modal แก้ไข
    public function edit($id)
    {
        $user = User::find($id);

        // Security Check: ห้ามแก้ User ข้ามร้าน
        $currentShopId = session('current_shop_id');
        $currentUser = Auth::user();

        if ($currentUser->role !== 'admin') {
            // เช็คว่า User ที่จะแก้ อยู่ในร้านเดียวกับเราไหม
            $isInSameShop = $user->shops()->where('shop_id', $currentShopId)->exists();
            if (!$isInSameShop) {
                $this->dispatch('notify', message: 'คุณไม่มีสิทธิ์แก้ไขผู้ใช้นี้', type: 'error');
                return;
            }
        }

        $this->editingId = $id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->password = ''; // ไม่ต้องโหลดรหัสเดิมมาแสดง

        // ถ้าเป็น Admin ให้โหลด shop_id ของ User นั้นมาโชว์ (เอาแค่ร้านแรก)
        if ($currentUser->role === 'admin') {
            $this->shop_id = $user->shops->first()->id ?? null;
        }

        $this->dispatch('show-modal');
    }

    // 4. บันทึก (Save)
    public function save()
    {
        // Validation Rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($this->editingId)],
            'role' => 'required',
        ];

        // ถ้าสร้างใหม่ ต้องใส่รหัสผ่าน / ถ้าแก้ไข รหัสผ่านเป็น Optional
        if (!$this->editingId) {
            $rules['password'] = 'required|min:6';
        } else {
            $rules['password'] = 'nullable|min:6';
        }

        $this->validate($rules);

        // --- Create / Update Logic ---
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

        // --- Shop Assignment Logic ---
        $currentUser = Auth::user();

        if ($currentUser->role === 'admin') {
            // Admin: ผูก User กับร้านที่เลือกใน Dropdown
            if ($this->shop_id) {
                // sync = ลบของเก่า ใส่ของใหม่ (User อยู่ได้ทีละร้านในหน้านี้แบบง่าย)
                $user->shops()->sync([$this->shop_id => ['role' => $this->role]]);
            }
        } else {
            // Shop Owner: ผูก User ใหม่เข้ากับร้านปัจจุบันทันที
            $currentShopId = session('current_shop_id');
            // syncWithoutDetaching = เพิ่มร้านนี้เข้าไป โดยไม่ลบร้านอื่น (เผื่อ User อยู่หลายร้าน)
            $user->shops()->syncWithoutDetaching([$currentShopId => ['role' => 'staff']]);
        }

        $this->dispatch('close-modal');
        $this->dispatch('notify', message: 'บันทึกข้อมูลเรียบร้อย', type: 'success');
    }

    // 5. ลบผู้ใช้
    public function delete($id)
    {
        $user = User::find($id);
        if ($user->id === Auth::id()) {
            $this->dispatch('notify', message: 'ไม่สามารถลบตัวเองได้', type: 'error');
            return;
        }

        $user->delete();
        $this->dispatch('notify', message: 'ลบผู้ใช้งานเรียบร้อย', type: 'success');
    }
}
