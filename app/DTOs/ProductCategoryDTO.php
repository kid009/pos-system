<?php

declare(strict_types=1);

namespace App\DTOs;

use App\Http\Requests\ProductCategoryRequest;

/**
 * Data Transfer Object สำหรับหมวดหมู่สินค้า
 */
readonly class ProductCategoryDTO
{
    public function __construct(
        public string $name,
        public bool $isActive = true,
    ) {}

    /**
     * สร้าง DTO จาก FormRequest
     */
    public static function fromRequest(ProductCategoryRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            isActive: $request->boolean('is_active', true),
        );
    }

    /**
     * แปลงเป็น array สำหรับ insert/update
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'is_active' => $this->isActive,
        ];
    }
}
