<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // ดึง ID ของ category ที่กำลังจะอัปเดตจาก Route
        $categoryId = $this->route('product_category');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_categories')->ignore($categoryId),
            ],
            'description' => 'nullable|string',
        ];
    }
}
