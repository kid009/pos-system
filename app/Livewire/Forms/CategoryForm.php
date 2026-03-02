<?php

namespace App\Livewire\Forms;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Form;

class CategoryForm extends Form
{
    public ?Category $category = null;

    public $name = '';
    public $shop_id; // เพิ่มตัวแปรรับค่า Shop
    // เอาไว้ระบุว่าหมวดหมู่นี้ต้องเช็คสต็อกไหม (แก้ปัญหา Hardcode "น้ำแก๊ส")
    public $is_tracking_stock = true;

    public function setCategory(Category $category)
    {
        $this->category = $category;
        $this->shop_id = $category->shop_id; // ดึงค่าร้านค้าเดิมมา
        $this->name = $category->name;
        // ป้องกัน Error กรณีฐานข้อมูลเก่ายังไม่มี Column นี้
        $this->is_tracking_stock = $category->is_tracking_stock ?? true;
    }

    public function rules(): array
    {
        $categoryId = $this->category ? $this->category->id : null;

        return [
            'shop_id' => [
                'required',
                'exists:shops,id',
            ],
            // เช็คชื่อซ้ำ โดยยกเว้น ID ของตัวเองตอน Edit
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($categoryId)
            ],
            'is_tracking_stock' => [
                'boolean'
            ],
        ];
    }

    public function storeOrUpdate()
    {
        $user = Auth::user();

        if (!$user->hasRole('admin'))
        {
            $this->shop_id = $user->currentShop();
        }

        try
        {
            $this->validate();

            DB::transaction(function () {
                $data = [
                    'shop_id' => $this->shop_id,
                    'name' => $this->name,
                    'is_tracking_stock' => $this->is_tracking_stock,
                ];

                if ($this->category) {
                    $data['updated_by'] = Auth::id();
                    $this->category->update($data);
                } else {
                    $data['created_by'] = Auth::id();
                    Category::create($data);
                }

                $this->reset();
            });

            return true; // บอกว่าทำงานสำเร็จ
        }
        catch (ValidationException $e)
        {
            // ----------------------------------------------------
            // ดัก Error ลำดับที่ 1: ดักจับเฉพาะ Validation Error
            // ----------------------------------------------------
            Log::warning('Category Form Validation Failed', [
                'user_id' => $user->id,
                'attempted_data' => [
                    'shop_id' => $this->shop_id,
                    'name' => $this->name,
                    'is_tracking_stock' => $this->is_tracking_stock,
                ],
                'validation_errors' => $e->errors(),
            ]);

            // สำคัญมากสำหรับ Livewire: ต้อง Throw กลับออกไปเพื่อให้โชว์ UI Error
            throw $e;

        }
        catch (\Throwable $e)
        {
            // แก้ไขการเขียน Log ให้ถูกต้อง Parameter ที่ 2 ต้องเป็น Array
            Log::error('Category Form Database Error', [
                'user_id' => $user->id,
                'category_id' => $this->category ? $this->category->id : null,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
