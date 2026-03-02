<?php

namespace App\Livewire\Forms;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Form;

class ProductFrom extends Form
{
    public ?Product $product = null;

    public $category_id;
    public $name = '';
    public $cost = 0;
    public $price = 0;
    public $stock_qty = 0;
    public $is_active = true;

    // สำหรับจัดการรูปภาพ
    public $image;
    public $oldImage;

    // เราจะรับค่าว่า Category นี้ต้องเช็คสต็อกหรือไม่
    public $is_tracking_stock = true;

    public function setProduct(Product $product, bool $isTrackingStock = true)
    {
        $this->product = $product;
        $this->category_id = $product->category_id;
        $this->name = $product->name;
        $this->cost = $product->cost;
        $this->price = $product->price;
        $this->stock_qty = $product->stock_qty;
        $this->is_active = (bool) $product->is_active;
        $this->oldImage = $product->image_path;

        $this->is_tracking_stock = $isTrackingStock;
    }

    public function rules(): array
    {
        $productId = $this->product ? $this->product->id : null;

        return [
            'category_id' => ['required', 'exists:categories,id'],
            'name'        => ['required', 'string', 'max:255', Rule::unique('products')->ignore($productId)],
            'cost'        => ['required', 'numeric', 'min:0'],
            'price'       => ['required', 'numeric', 'min:0'],
            'image'       => ['nullable', 'image', 'max:1024'],
            // Conditional Validation สำหรับ Stock
            'stock_qty'   => [
                $this->is_tracking_stock ? 'required' : 'nullable',
                'integer',
                'min:0'
            ],
        ];
    }

    public function storeOrUpdate()
    {
        // 1. ตรวจสอบข้อมูล (ใช้ rules() ที่เราเขียนไว้)
        $this->validate();

        // 2. ใช้ DB Transaction (สำคัญมากในระบบ Enterprise ป้องกันข้อมูลเซฟไม่ครบ)
        DB::transaction(function () {
            $imagePath = $this->oldImage;

            // 3. จัดการไฟล์รูปภาพ
            if ($this->image) {
                // เคลียร์รูปเก่าทิ้ง ป้องกัน Disk เต็มในระยะยาว (12 เดือนขึ้นไป)
                if ($this->oldImage && Storage::disk('public')->exists($this->oldImage)) {
                    Storage::disk('public')->delete($this->oldImage);
                }

                $imagePath = $this->image->store('products', 'public');
            }

            // 4. เตรียม Payload
            $data = [
                'category_id' => $this->category_id,
                'name'        => $this->name,
                'cost'        => $this->cost,
                'price'       => $this->price,
                'stock_qty'   => $this->stock_qty ?: 0,
                'image_path'  => $imagePath,
                'is_active'   => $this->is_active,
            ];

            // 5. บันทึกข้อมูล
            if ($this->product) {
                $data['updated_by'] = Auth::id();
                $this->product->update($data);
            } else {
                $data['created_by'] = Auth::id();
                Product::create($data);
            }
        });

        // คืนค่าฟอร์มกลับเป็นค่าเริ่มต้น
        $this->reset();
    }
}
