<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads; // สำหรับอัปโหลดรูป
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class ProductComponent extends Component
{
    use WithPagination;
    use WithFileUploads;

    protected $paginationTheme = 'bootstrap';

    // ตัวแปรรับค่า
    public $category_id;
    public $name, $barcode, $cost, $price, $stock_qty;
    public $image; // ไฟล์รูปใหม่ที่อัปโหลด
    public $oldImage; // เก็บ path รูปเก่า
    public $is_active = true;

    public $search = '';
    public $editingId = null;

    // ตัวแปรเช็คสถานะหมวดหมู่ "น้ำแก๊ส"
    public $isGasCategory = false;

    // เมื่อมีการเปลี่ยน Category ให้เช็คทันทีว่าเป็นน้ำแก๊สไหม
    public function updatedCategoryId($value)
    {
        $category = Category::find($value);
        // เช็คว่าชื่อหมวดหมู่มีคำว่า "น้ำแก๊ส" หรือไม่
        $this->isGasCategory = $category && str_contains($category->name, 'น้ำแก๊ส');

        // ถ้าเป็นน้ำแก๊ส อาจจะเซ็ต stock เป็น 0 หรือค่า default
        if ($this->isGasCategory) {
            $this->stock_qty = 0;
        }
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();

        $products = Product::with('category')
            ->where('name', 'like', '%'.$this->search.'%')
            ->orWhere('barcode', 'like', '%'.$this->search.'%')
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('livewire.admin.product-component', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $this->reset(['category_id', 'name', 'barcode', 'cost', 'price', 'stock_qty', 'image', 'oldImage', 'editingId', 'isGasCategory']);
        $this->is_active = true;
        $this->dispatch('show-modal');
    }

    public function edit($id)
    {
        $product = Product::find($id);
        if ($product) {
            $this->editingId = $id;
            $this->category_id = $product->category_id;
            $this->name = $product->name;
            $this->barcode = $product->barcode;
            $this->cost = $product->cost;
            $this->price = $product->price;
            $this->stock_qty = $product->stock_qty;
            $this->is_active = (bool) $product->is_active;
            $this->oldImage = $product->image_path;

            // เช็คสถานะหมวดหมู่ตอนกดแก้ไขด้วย
            $this->updatedCategoryId($this->category_id);

            $this->dispatch('show-modal');
        }
    }

    public function save()
    {
        // 1. กำหนดกฎ Validation พื้นฐาน
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255|unique:products,barcode,' . $this->editingId,
            'cost' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|max:1024', // 1MB
        ];

        // 2. เพิ่มกฎ Stock ตามเงื่อนไข
        // ถ้าไม่ใช่หมวด "น้ำแก๊ส" -> ต้องกรอก stock และต้องเป็นจำนวนเต็ม
        if (!$this->isGasCategory) {
            $rules['stock_qty'] = 'required|integer|min:0';
        } else {
            // ถ้าเป็นน้ำแก๊ส -> อนุโลมให้เป็น nullable (เดี๋ยวเรา set เป็น 0 ตอน save)
            $rules['stock_qty'] = 'nullable';
        }

        $this->validate($rules);

        // 3. จัดการรูปภาพ
        $imagePath = $this->oldImage;
        if ($this->image) {
            $imagePath = $this->image->store('products', 'public');
        }

        // 4. เตรียมข้อมูลบันทึก
        $data = [
            'category_id' => $this->category_id,
            'name' => $this->name,
            'barcode' => $this->barcode,
            'cost' => $this->cost,
            'price' => $this->price,
            'stock_qty' => $this->stock_qty ?? 0, // ถ้าว่างให้เป็น 0
            'image_path' => $imagePath,
            'is_active' => $this->is_active,
        ];

        if ($this->editingId) {
            $data['updated_by'] = Auth::id();
            Product::find($this->editingId)->update($data);
        } else {
            $data['created_by'] = Auth::id();
            Product::create($data);
        }

        $this->dispatch('close-modal');
    }

    public function delete($id)
    {
        Product::find($id)->delete();
    }
}
