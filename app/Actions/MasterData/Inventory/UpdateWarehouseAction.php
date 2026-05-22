<?php

namespace App\Actions\Inventory;

use App\Models\Warehouse;
use App\DTOs\WarehouseDTO;

class UpdateWarehouseAction
{
    public function execute(Warehouse $warehouse, WarehouseDTO $dto): Warehouse
    {
        $warehouse->update([
            'name'      => $dto->name,
            'code'      => $dto->code,
            'is_active' => $dto->isActive,
        ]);

        return $warehouse;
    }
}
