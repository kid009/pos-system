<?php

namespace App\Actions\Inventory;

use App\Models\Warehouse;
use App\DTOs\WarehouseDTO;

class CreateWarehouseAction
{
    public function execute(WarehouseDTO $dto): Warehouse
    {
        return Warehouse::create([
            'name'      => $dto->name,
            'code'      => $dto->code,
            'is_active' => $dto->isActive,
        ]);
    }
}
