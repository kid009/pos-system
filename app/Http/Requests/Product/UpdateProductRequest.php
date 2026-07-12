<?php

namespace App\Http\Requests\Product;

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
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('products', 'slug')->ignore($product),
            ],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model_number' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
            'affiliate_links' => ['nullable', 'array'],
            'affiliate_links.*.id' => ['nullable', 'integer', 'exists:product_affiliate_links,id'],
            'affiliate_links.*.platform' => ['required_with:affiliate_links', 'string', 'max:255'],
            'affiliate_links.*.affiliate_url' => ['required_with:affiliate_links', 'url', 'max:1000'],
            'affiliate_links.*.is_active' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => filter_var($this->input('is_active', true), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
        ]);

        if ($this->has('affiliate_links') && is_array($this->input('affiliate_links'))) {
            $affiliateLinks = $this->input('affiliate_links');
            foreach ($affiliateLinks as $key => $link) {
                $affiliateLinks[$key]['is_active'] = filter_var(
                    $link['is_active'] ?? true,
                    FILTER_VALIDATE_BOOLEAN,
                    FILTER_NULL_ON_FAILURE
                );
            }
            $this->merge(['affiliate_links' => $affiliateLinks]);
        }
    }
}
