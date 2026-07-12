<?php

namespace App\DTOs\Product;

readonly class ProductPriceDTO
{
    public function __construct(
        public ?float $price = null,
        public ?float $cost = null,
        public ?\DateTimeInterface $startedAt = null,
        public ?\DateTimeInterface $endedAt = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_filter([
            'price' => $this->price,
            'cost' => $this->cost,
            'started_at' => $this->startedAt?->format('Y-m-d H:i:s'),
            'ended_at' => $this->endedAt?->format('Y-m-d H:i:s'),
        ], fn ($value) => $value !== null);
    }

    public static function formRequest(array $data): self
    {
        return new self(
            price: isset($data['price']) ? (float) $data['price'] : null,
            cost: isset($data['cost']) ? (float) $data['cost'] : null,
            startedAt: isset($data['started_at']) ? new \DateTimeImmutable($data['started_at']) : now(),
            endedAt: isset($data['ended_at']) ? new \DateTimeImmutable($data['ended_at']) : null,
        );
    }
}
