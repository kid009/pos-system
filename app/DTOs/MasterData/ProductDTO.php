<?php

namespace App\DTOs\MasterData;

readonly class ProductDTO
{
    public function __construct(
        public string $sku,
        public string $name,
        public ?string $description,
        public float $price,
        public ?int $categoryId,
        public bool $isActive = true,
    ) {}

    /**
     * แปลงเป็น array สำหรับ insert/update
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'category_id' => $this->categoryId,
            'is_active' => $this->isActive,
        ];
    }
}
