<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

#[Title('Manage Categories')]
class CategoryComponent extends Component
{
    use WithPagination;

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

        Category::create(['name' => $this->name]);

        $this->isOpen = false;

        $this->dispatch('notify', message: 'Category created successfully.', type: 'success');
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->categoryId = $id;
        $this->name = $category->name;
        $this->isOpen = true;
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|min:3|unique:categories,name,' . $this->categoryId,
        ]);

        $category = Category::findOrFail($this->categoryId);
        $category->update(['name' => $this->name]);

        $this->isOpen = false;

        $this->dispatch('notify', message: 'Category updated successfully.', type: 'success');
    }

    public function delete($id)
    {
        // Senior Tip: เช็คก่อนลบว่ามีสินค้าผูกอยู่ไหม เพื่อกัน Data Integrity Error
        $category = Category::find($id);
        if ($category->products()->exists()) {
            session()->flash('error', 'Cannot delete! This category has products.');
            return;
        }

        $category->delete();

        $this->dispatch('notify', message: 'Category deleted successfully.', type: 'success');
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
