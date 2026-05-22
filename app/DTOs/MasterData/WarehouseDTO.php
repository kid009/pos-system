<?php

namespace App\DTOs;

readonly class WarehouseDTO
{
    public function __construct(
        public string $name,
        public string $code,
        public bool $isActive = true
    ) {}
}
