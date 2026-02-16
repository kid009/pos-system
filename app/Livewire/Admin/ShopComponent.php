<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads; // สำหรับอัปโหลดรูป
use App\Models\Shop;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopComponent extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    // ตัวแปรรับค่า
    public $name, $address, $phone, $logo;
    public $oldLogo; // เก็บ path รูปเก่า

    public $search = '';
    public $editingId = null;

    // 2. เปิด Modal เพิ่มร้าน
    public function create()
    {
        $this->reset(['name', 'address', 'phone', 'logo', 'oldLogo', 'editingId']);
        $this->dispatch('show-modal');
    }

    // 3. เปิด Modal แก้ไขร้าน
    public function edit($id)
    {
        // ตรวจสิทธิ์: ถ้าไม่ใช่ Admin ต้องเช็คว่าเป็นเจ้าของร้านไหม
        $user = Auth::user();
        if ($user->role !== 'admin' && !$user->isOwnerOf($id)) {
            $this->dispatch('notify', message: 'คุณไม่มีสิทธิ์แก้ไขร้านนี้', type: 'error');
            return;
        }

        $shop = Shop::find($id);
        if ($shop) {
            $this->editingId = $id;
            $this->name = $shop->name;
            $this->address = $shop->address;
            $this->phone = $shop->phone;
            $this->oldLogo = $shop->logo_path;

            $this->dispatch('show-modal');
        }
    }

    // 4. บันทึกข้อมูล (Save)
    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'logo' => 'nullable|image|max:2048', // Max 2MB
        ]);

        // จัดการรูปภาพ
        $logoPath = $this->oldLogo;
        if ($this->logo) {
            // ลบรูปเก่าถ้ามี (เพื่อไม่ให้ขยะรก Server)
            if ($this->oldLogo) {
                Storage::disk('public')->delete($this->oldLogo);
            }
            $logoPath = $this->logo->store('shops', 'public');
        }

        if ($this->editingId) {
            // --- Update ---
            Shop::find($this->editingId)->update([
                'name' => $this->name,
                'address' => $this->address,
                'phone' => $this->phone,
                'logo_path' => $logoPath,
            ]);
        } else {
            // --- Create ---
            $shop = Shop::create([
                'name' => $this->name,
                'address' => $this->address,
                'phone' => $this->phone,
                'logo_path' => $logoPath,
            ]);

            // 🔥 สำคัญ: สร้างร้านแล้ว ต้องผูก User คนสร้างเป็น "เจ้าของร้าน" ทันที
            Auth::user()->shops()->attach($shop->id, ['role' => 'shop_owner']);
        }

        $this->dispatch('close-modal');
        $this->dispatch('notify', message: 'บันทึกข้อมูลเรียบร้อย', type: 'success');
    }

    // 5. ลบร้าน
    public function delete($id)
    {
        $user = Auth::user();

        // Admin ลบได้ทุกคน, Owner ลบได้แค่ร้านตัวเอง
        if ($user->role !== 'admin' && !$user->isOwnerOf($id)) {
            $this->dispatch('notify', message: 'คุณไม่มีสิทธิ์ลบร้านนี้', type: 'error');
            return;
        }

        $shop = Shop::find($id);
        if ($shop) {
            // ลบรูป
            if ($shop->logo_path) {
                Storage::disk('public')->delete($shop->logo_path);
            }
            $shop->delete(); // Cascade จะลบ shop_user, categories, products ให้เอง (ถ้าตั้งไว้ใน Migration)

            $this->dispatch('notify', message: 'ลบร้านเรียบร้อย', type: 'success');
        }
    }

    // 1. Render แสดงผล
    public function render()
    {
        $user = Auth::user();

        // กรณี Admin เห็นทุกร้าน
        if ($user->role === 'admin') {
            $shops = Shop::where('name', 'like', '%'.$this->search.'%')
                ->orderBy('id', 'desc')
                ->paginate(10);
        } else {
            // กรณี User ทั่วไป เห็นแค่ร้านตัวเอง
            $shops = $user->shops()
                ->where('name', 'like', '%'.$this->search.'%')
                ->orderBy('id', 'desc')
                ->paginate(10);
        }

        return view('livewire.admin.shop-component', [
            'shops' => $shops
        ]);
    }
}
