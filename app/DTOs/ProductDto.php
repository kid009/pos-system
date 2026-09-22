<?php

declare(strict_types=1);

namespace App\DTOs;

use Illuminate\Http\UploadedFile;

final readonly class ProductDto
{
    public function __construct(
        public int $productCategoryId,
        public string $name,
        public ?string $description,
        public string $price,
        public ?string $linkAffiliate,
        public bool $isActive = true,
        public ?UploadedFile $image = null
    ) {}

    /**
     * Factory Method สำหรับแปลง FormRequest เป็น DTO
     */
    public static function fromArray(array $data): self
    {
        $productCategoryId = (int) ($data['product_category_id'] ?? 0);
        $name = trim((string) ($data['name'] ?? ''));
        $description = isset($data['description']) && trim((string) $data['description']) !== ''
            ? trim((string) $data['description'])
            : null;
        $price = (string) ($data['price'] ?? '0');
        $linkAffiliate = isset($data['link_affiliate']) && trim((string) $data['link_affiliate']) !== ''
            ? trim((string) $data['link_affiliate'])
            : null;
        $isActive = filter_var($data['is_active'] ?? true, FILTER_VALIDATE_BOOLEAN);
        $image = $data['image'] ?? null;

        return new self(
            productCategoryId: $productCategoryId,
            name: $name,
            description: $description,
            price: $price,
            linkAffiliate: $linkAffiliate,
            isActive: $isActive,
            image: $image instanceof UploadedFile ? $image : null
        );
    }

    /**
     * แปลงกลับเป็น Array หากจำเป็นต้องใช้กับ Eloquent create()
     */
    public function toArray(): array
    {
        return [
            'product_category_id' => $this->productCategoryId,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'link_affiliate' => $this->linkAffiliate,
            'is_active' => $this->isActive,
        ];
    }
}
