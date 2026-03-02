<?php

namespace App\Livewire\Admin;

use App\Livewire\Forms\ProductFrom;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads; // สำหรับอัปโหลดรูป
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class ProductComponent extends Component
{
    use WithPagination, WithFileUploads, AuthorizesRequests;

    protected $paginationTheme = 'bootstrap';

    public ProductFrom $form;

    public $search = '';

    // รีเซ็ตหน้าเมื่อมีการค้นหา ป้องกันบัคหน้าเปล่าเวลาอยู่หน้า 2 แล้ว search ไม่เจอ
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFormCategoryId($value)
    {
        if (!$value) return;

        $category = Category::find($value);

        // เช็คผ่าน Model Method เพื่อซ่อน Business Logic
        if ($category && !$category->isTrackingStock()) {
            $this->form->is_tracking_stock = false;
            $this->form->stock_qty = 0; // Set default ทันที
        } else {
            $this->form->is_tracking_stock = true;
        }
    }

    public function create()
    {
        $this->form->reset();
        $this->form->is_active = true;

        $this->dispatch('show-modal');
    }

    public function edit(int $id): void
    {
        // ใช้ findOrFail เสมอ เพื่อลดบัคเงียบ (Silent fails)
        $product = Product::findOrFail($id);

        // $this->authorize('update', $product);

        // ดึงสถานะการเช็คสต็อกมาเพื่อแสดงในฟอร์มให้ถูกต้อง
        $isTrackingStock = $product->category ? $product->category->isTrackingStock() : true;

        $this->form->setProduct($product, $isTrackingStock);

        $this->dispatch('show-modal');
    }

    public function save(): void
    {
        // Business logic ทั้งหมดถูกรันใน Form Object
        $this->form->storeOrUpdate();

        $this->dispatch('close-modal');
        $this->dispatch('notify', ['type' => 'success', 'message' => 'บันทึกข้อมูลสินค้าสำเร็จ']);
    }

    public function delete(int $id): void
    {
        $product = Product::findOrFail($id);

        // $this->authorize('delete', $product);

        // [Security Check] ตรวจสอบว่ามีประวัติการขายหรือไม่?
        // สมมติว่าใน Model Product มี relation orderItems()
        if (method_exists($product, 'orderItems') && $product->orderItems()->exists()) {
            // ถ้าระบบเคยขายไปแล้ว เราไม่ควรลบ (ควรขึ้นแจ้งเตือน)
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'ไม่สามารถลบได้ เนื่องจากสินค้านี้มีประวัติการขาย แนะนำให้ปิดสถานะการใช้งานแทน'
            ]);
            return;
        }

        // หากลบได้ ควรใช้ SoftDeletes ฝั่ง Model เสมอ
        $product->delete();

        $this->dispatch('notify', ['type' => 'success', 'message' => 'ลบข้อมูลสินค้าเรียบร้อย']);
    }

    public function render()
    {
        // ใช้วิธี Caching หรือดึงแค่ข้อมูลที่ใช้จริงๆ ลด Load ฐานข้อมูล
        $categories = Category::select('id', 'name')->orderBy('name')->get();

        $products = clone Product::query()
            ->with('category:id,name') // Select เฉพาะฟิลด์ที่ต้องการ ป้องกัน N+1 แบบประหยัด RAM
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.admin.product-component', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}
