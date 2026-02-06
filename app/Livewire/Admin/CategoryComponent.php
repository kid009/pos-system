<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;
use App\Models\MainCategory; // อย่าลืมเรียกใช้
use Illuminate\Support\Facades\Auth;

class CategoryComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // ตัวแปรรับค่า
    public $main_category_id;
    public $name;
    public $search = '';

    public $editingId = null;

    public function render()
    {
        // ดึงหมวดหมู่หลักมาใส่ Dropdown
        $mainCategories = MainCategory::orderBy('name')->get();

        // ดึงข้อมูลหมวดหมู่ย่อยมาแสดง (พร้อม Search และ Join)
        $categories = Category::with('mainCategory') // Eager Loading เพื่อลด Query
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhereHas('mainCategory', function($q) { // ค้นหาจากชื่อหมวดหมู่หลักได้ด้วย
                $q->where('name', 'like', '%'.$this->search.'%');
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.category-component', [
            'categories' => $categories,
            'mainCategories' => $mainCategories
        ]);
    }

    public function create()
    {
        $this->reset(['main_category_id', 'name', 'editingId']);
        $this->dispatch('show-modal');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        if ($category) {
            $this->editingId = $id;
            $this->name = $category->name;
            $this->main_category_id = $category->main_category_id;
            $this->dispatch('show-modal');
        }
    }

    public function save()
    {
        $this->validate([
            'main_category_id' => 'nullable|exists:main_categories,id', // ต้องมีอยู่จริงในตาราง main
            'name' => 'required|string|max:255',
        ]);

        $data = [
            'main_category_id' => $this->main_category_id ?: null, // ถ้าไม่เลือกให้เป็น NULL
            'name' => $this->name,
        ];

        if ($this->editingId) {
            $data['updated_by'] = Auth::id();
            Category::find($this->editingId)->update($data);
        } else {
            $data['created_by'] = Auth::id();
            Category::create($data);
        }

        $this->dispatch('close-modal');
    }

    public function delete($id)
    {
        Category::find($id)->delete();
    }
}
