<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use App\Services\LogService; // ✅ เรียกใช้ LogService
use Exception;

#[Title('Manage Categories')]
class CategoryComponent extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $categoryId; // ใช้เก็บ ID ตอน Edit
    public $isOpen = false; // State สำหรับเปิด/ปิด Modal

    protected $rules = [
        'name' => 'required|min:3|unique:categories,name',
    ];

    public function create()
    {
        $this->reset(['name', 'categoryId']);
        $this->isOpen = true;
    }

    public function store()
    {
        $this->validate();

        try {
            // ✅ สร้างข้อมูล
            $category = Category::create(['name' => $this->name]);

            // 📝 LOG INFO: บันทึกว่ามีการสร้างหมวดหมู่ใหม่
            LogService::info('Category Created', [
                'category_id' => $category->id,
                'category_name' => $category->name
            ]);

            $this->isOpen = false;
            $this->dispatch('notify', message: 'Category created successfully.', type: 'success');
        } catch (Exception $e) {
            // 📝 LOG ERROR: บันทึกเมื่อ Database พัง
            LogService::error('Category Create Failed', $e, [
                'name_attempt' => $this->name
            ]);

            $this->dispatch('notify', message: 'Error creating category.', type: 'error');
        }
    }

    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
            $this->categoryId = $id;
            $this->name = $category->name;
            $this->isOpen = true;
        } catch (Exception $e) {
            LogService::warning('Category Edit Not Found', ['id' => $id]);
            $this->dispatch('notify', message: 'Category not found.', type: 'error');
        }
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3|unique:categories,name,' . $this->categoryId,
        ]);

        try {
            $category = Category::findOrFail($this->categoryId);

            // เก็บชื่อเดิมไว้ Log ก่อนเปลี่ยน
            $oldName = $category->name;

            $category->update(['name' => $this->name]);

            // 📝 LOG INFO: บันทึกการแก้ไข
            LogService::info('Category Updated', [
                'category_id' => $category->id,
                'old_name' => $oldName,
                'new_name' => $this->name
            ]);

            $this->isOpen = false;
            $this->dispatch('notify', message: 'Category updated successfully.', type: 'success');
        } catch (Exception $e) {
            LogService::error('Category Update Failed', $e, [
                'category_id' => $this->categoryId
            ]);
            $this->dispatch('notify', message: 'Error updating category.', type: 'error');
        }
    }

    public function delete($id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                $this->dispatch('notify', message: 'Category not found.', type: 'error');
                return;
            }

            // 1. เช็คว่ามีสินค้าผูกอยู่ไหม
            if ($category->products()->exists()) {

                // 📝 LOG WARNING: แจ้งเตือนว่าลบไม่ได้เพราะติด Relation
                LogService::warning('Category Delete Blocked (Has Products)', [
                    'category_id' => $id,
                    'category_name' => $category->name
                ]);

                $this->dispatch('notify', message: 'Cannot delete! This category has products.', type: 'error');
                return;
            }

            // เก็บชื่อไว้ Log
            $categoryName = $category->name;

            // 2. ลบข้อมูล
            $category->delete();

            // 📝 LOG INFO: ลบสำเร็จ
            LogService::info('Category Deleted', [
                'category_id' => $id,
                'category_name' => $categoryName
            ]);

            $this->dispatch('notify', message: 'Category deleted successfully.', type: 'success'); // แก้ type เป็น success

        } catch (Exception $e) {
            LogService::error('Category Delete Failed', $e, ['category_id' => $id]);
            $this->dispatch('notify', message: 'Error deleting category.', type: 'error');
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

    public function render()
    {
        $categories = Category::orderBy('id', 'desc')->paginate(10);

        return view('livewire.admin.category-component', [
            'categories' => $categories,
        ]);
    }
}
