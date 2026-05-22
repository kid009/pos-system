<?php

namespace App\DTOs\MasterData;

readonly class InboundStockDTO
{
    public function __construct(
        public int $productId,
        public int $warehouseId,
        public int $qty,
        public float $unitCost,
        public float $shippingFee = 0.00,
        public ?string $reference = null,
    ) {}
}
