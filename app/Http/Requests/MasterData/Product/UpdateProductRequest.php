<?php

namespace App\Http\Requests\MasterData\Product;

use App\DTOs\MasterData\ProductDTO;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $product = $this->route('product');

        return [
            'sku' => ['required', 'string', 'max:50', Rule::unique('products', 'sku')->ignore($product)],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'is_active' => ['boolean'],
        ];
    }

    public function toDTO(): ProductDTO
    {
        return new ProductDTO(
            sku: $this->validated('sku'),
            name: $this->validated('name'),
            description: $this->validated('description'),
            price: (float) $this->validated('price'),
            categoryId: $this->validated('category_id'),
            isActive: $this->boolean('is_active', true)
        );
    }
}
