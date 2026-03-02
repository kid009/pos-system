<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\CategoryForm;
use App\Models\Category;
use App\Models\Shop;
use App\Queries\Category\GetCategoryListQuery;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class CategoryComponent extends Component
{
    use WithPagination, AuthorizesRequests;

    protected $paginationTheme = 'bootstrap';

    public CategoryForm $form;

    public string $search = '';

    public bool $isAdmin = false;

    public function mount()
    {
        $user = Auth::user();

        if ($user->role == 'admin') {
            $this->isAdmin = true;
        }
    }

    // รีเซ็ตหน้าทุกครั้งที่พิมพ์ค้นหา ป้องกันบัคหน้าเปล่า
    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function create(): void
    {
        $this->form->reset();
        $this->dispatch('show-modal');
    }

    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);
        $this->form->setCategory($category);

        $this->dispatch('show-modal');
    }

    public function save(): void
    {
        $this->form->storeOrUpdate();

        $this->dispatch('close-modal');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'บันทึกข้อมูลหมวดหมู่สำเร็จ']);
    }

    public function delete(int $id): void
    {
        $category = Category::findOrFail($id);

        // [Security Check] ตรวจสอบว่ามีสินค้าใช้งานหมวดหมู่นี้อยู่หรือไม่?
        // สำคัญมาก: ห้ามลบหมวดหมู่ที่มีสินค้าอยู่เด็ดขาด!
        if ($category->products()->exists()) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'ไม่สามารถลบได้ เนื่องจากมีสินค้า ' . $category->products()->count() . ' รายการอยู่ในหมวดหมู่นี้'
            ]);
            return;
        }

        $category->delete();

        $this->dispatch('notify', ['type' => 'success', 'message' => 'ลบข้อมูลหมวดหมู่เรียบร้อย']);
    }

    public function render(GetCategoryListQuery $query)
    {
        $user = Auth::user();

        // 1. ดึงรายชื่อร้านค้า
        // ใช้ Scope เดียวกันกับ Shop Model ได้เลย (ถ้าสร้างไว้)
        $shops = $this->isAdmin ? Shop::select('id', 'name')->orderBy('name')->get() : $user->shops()->select('shops.id', 'shops.name')->orderBy('shops.name')->get();

        // 2. ดึงหมวดหมู่ (Clean Code)
        $categories = $query->execute($user, $this->search);

        return view('livewire.admin.category-component', [
            'categories' => $categories,
            'shops' => $shops,
        ]);
    }
}
