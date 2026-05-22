<?php

namespace App\Actions\MasterData\Inventory;

use App\DTOs\MasterData\InboundStockDTO;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RecordStockInboundAction
{
    /**
     * Execute the atomic transaction for stock inventory intake.
     */
    public function execute(InboundStockDTO $dto): StockMovement
    {
        // Financial-Grade ACID Protection Wrapper
        return DB::transaction(function () use ($dto) {

            // 1. Lock Product Row for update to prevent Concurrency Race Conditions
            $product = Product::lockForUpdate()->findOrFail($dto->productId);

            // 2. Append directly to the immutable stock movements ledger (Audit Trail)
            $movement = StockMovement::create([
                'product_id'   => $product->id,
                'warehouse_id' => $dto->warehouseId,
                'user_id'      => Auth::user()->id,
                'type'         => 'in', // Explicit inbound token
                'qty'          => $dto->qty,
                'unit_cost'    => $dto->unitCost,
                'reference'    => $dto->reference,
            ]);

            // 3. Update the aggregated physical stock count inside products table
            $product->stock += $dto->qty;
            $product->save();

            // Note: [S1-06] Moving Average Cost calculation will hook right here in the next step.

            return $movement;
        });
    }
}
