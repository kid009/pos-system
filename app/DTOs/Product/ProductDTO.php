<?php

namespace App\DTOs\Product;

readonly class ProductDTO
{
    /**
     * @param  array<int, ProductAffiliateLinkDTO>  $affiliateLinks
     */
    public function __construct(
        public ?string $name = null,
        public ?string $slug = null,
        public ?string $brand = null,
        public ?string $modelNumber = null,
        public ?string $description = null,
        public ?string $imagePath = null,
        public ?bool $isActive = null,
        public ?int $categoryId = null,
        public ?ProductPriceDTO $price = null,
        public array $affiliateLinks = [],
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'name' => $this->name,
            'slug' => $this->slug,
            'brand' => $this->brand,
            'model_number' => $this->modelNumber,
            'description' => $this->description,
            'image_path' => $this->imagePath,
            'is_active' => $this->isActive,
            'category_id' => $this->categoryId,
        ], fn ($value) => $value !== null);
    }

    public static function formRequest(array $data): self
    {
        $affiliateLinks = [];

        if (isset($data['affiliate_links']) && is_array($data['affiliate_links'])) {
            foreach ($data['affiliate_links'] as $link) {
                if (is_array($link)) {
                    $affiliateLinks[] = ProductAffiliateLinkDTO::formRequest($link);
                }
            }
        }

        return new self(
            name: $data['name'] ?? null,
            slug: $data['slug'] ?? null,
            brand: $data['brand'] ?? null,
            modelNumber: $data['model_number'] ?? null,
            description: $data['description'] ?? null,
            imagePath: $data['image_path'] ?? null,
            isActive: isset($data['is_active']) ? (bool) $data['is_active'] : null,
            categoryId: isset($data['category_id']) ? (int) $data['category_id'] : null,
            price: isset($data['price']) && is_array($data['price'])
                ? ProductPriceDTO::formRequest($data['price'])
                : null,
            affiliateLinks: $affiliateLinks,
        );
    }
}
