<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\MainCategory;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class MainCategoryComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // ตัวแปรรับค่า
    public $name;
    public $search = '';

    // ตัวแปรเช็คสถานะแก้ไข
    public $editingId = null;

    // 2. เปิด Modal เพิ่มข้อมูล (Create)
    public function create()
    {
        $this->reset(['name', 'editingId']); // ล้างค่า
        $this->dispatch('show-modal');       // เปิด Modal
    }

    // 3. เปิด Modal แก้ไขข้อมูล (Edit)
    public function edit($id)
    {
        $category = MainCategory::find($id);
        if ($category) {
            $this->editingId = $id;
            $this->name = $category->name;
            $this->dispatch('show-modal');
        }
    }

    // 4. บันทึกข้อมูล (Save - รองรับทั้งเพิ่มและแก้ไข)
    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:200|unique:main_categories,name,' . $this->editingId,
            // unique: เช็คชื่อซ้ำ (แต่ยกเว้นตัวเองตอนแก้ไข)
        ]);

        if ($this->editingId) {
            // --- กรณีแก้ไข (Update) ---
            MainCategory::find($this->editingId)->update([
                'name' => $this->name,
                'updated_by' => Auth::id(), // เก็บ ID คนแก้ไขล่าสุด
            ]);
        } else {
            // --- กรณีเพิ่มใหม่ (Create) ---
            MainCategory::create([
                'name' => $this->name,
                'created_by' => Auth::id(), // เก็บ ID คนสร้าง
            ]);
        }

        $this->dispatch('close-modal'); // ปิด Modal
        // $this->dispatch('notify', 'บันทึกเรียบร้อย'); // ถ้ามีระบบแจ้งเตือน
    }

    // 5. ลบข้อมูล (Delete)
    public function delete($id)
    {
        MainCategory::find($id)->delete();
    }

    public function render()
    {
        $categories = MainCategory::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.main-category-component', [
            'categories' => $categories
        ]);
    }
}
