<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\DTOs\ProductCategoryDTO;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request class สำหรับ validate และ authorize การทำงานกับหมวดหมู่สินค้า
 */
class ProductCategoryRequest extends FormRequest
{
    /**
     * ตรวจสอบสิทธิ์การเข้าถึง
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        /** @var Category|null $category */
        $category = $this->route('product_category');
        $categoryId = $category?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($categoryId),
            ],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }

    /**
     * Custom error messages
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'กรุณาระบุชื่อหมวดหมู่',
            'name.string' => 'ชื่อหมวดหมู่ต้องเป็นข้อความ',
            'name.max' => 'ชื่อหมวดหมู่ต้องไม่เกิน 255 ตัวอักษร',
            'name.unique' => 'ชื่อหมวดหมู่นี้มีอยู่ในระบบแล้ว',
            'is_active.boolean' => 'สถานะต้องเป็นค่าถูก/ผิด',
        ];
    }

    /**
     * แปลงเป็น DTO
     */
    public function toDTO(): ProductCategoryDTO
    {
        return ProductCategoryDTO::fromRequest($this);
    }
}
