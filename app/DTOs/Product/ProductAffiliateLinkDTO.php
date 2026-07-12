<?php

namespace App\DTOs\Product;

readonly class ProductAffiliateLinkDTO
{
    public function __construct(
        public ?int $id = null,
        public ?string $platform = null,
        public ?string $affiliateUrl = null,
        public ?bool $isActive = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'platform' => $this->platform,
            'affiliate_url' => $this->affiliateUrl,
            'is_active' => $this->isActive,
        ], fn ($value) => $value !== null);
    }

    public static function formRequest(array $data): self
    {
        return new self(
            id: isset($data['id']) ? (int) $data['id'] : null,
            platform: $data['platform'] ?? null,
            affiliateUrl: $data['affiliate_url'] ?? null,
            isActive: isset($data['is_active']) ? (bool) $data['is_active'] : null,
        );
    }
}
