<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
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
        // ดึงข้อมูลหมวดหมู่ย่อยมาแสดง (พร้อม Search และ Join)
        $categories = Category::where('name', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.category-component', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $this->reset(['name', 'editingId']);
        $this->dispatch('show-modal');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        if ($category) {
            $this->editingId = $id;
            $this->name = $category->name;
            $this->dispatch('show-modal');
        }
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
        ]);

        $data = [
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
