<?php

namespace App\Services\Inventory;

use App\Models\BranchProduct;
use App\Models\Purchase;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class StockInService
{
    /**
     * Handle the process of stocking in new products from a purchase.
     *
     * @param array $validatedData The validated data from the request.
     * @param User $user The user performing the action.
     * @return Purchase
     * @throws \Exception
     */
    public function handle(array $validatedData, User $user): Purchase
    {
        return DB::transaction(function () use ($validatedData, $user) {
            // 1. Create Purchase Header
            $purchase = Purchase::create([
                'tenant_id' => $user->tenant_id,
                'branch_id' => $user->branch_id,
                'purchase_date' => $validatedData['purchase_date'],
                'supplier_name' => $validatedData['supplier_name'],
                'total_cost' => 0, // Will update later
                'created_by' => $user->id,
            ]);

            $totalCost = 0;

            // 2. Loop through items
            foreach ($validatedData['items'] as $item) 
            {
                // 2a. Create Purchase Item
                $purchase->items()->create($item);

                // 2b. Update Inventory (branch_product table)
                $inventory = BranchProduct::firstOrCreate(
                    ['branch_id' => $user->branch_id, 'product_id' => $item['product_id']],
                    ['quantity' => 0]
                );
                
                $inventory->increment('quantity', $item['quantity']);

                // 2c. Create Stock Movement record
                StockMovement::create([
                    'tenant_id' => $user->tenant_id,
                    'branch_id' => $user->branch_id,
                    'product_id' => $item['product_id'],
                    'type' => 'in',
                    'quantity' => $item['quantity'],
                    'reference_type' => Purchase::class,
                    'reference_id' => $purchase->id,
                    'remaining_quantity' => $inventory->quantity,
                    'created_by' => $user->id,
                ]);

                $totalCost += $item['quantity'] * $item['cost'];
            }

            // 3. Update total cost in purchase header
            $purchase->total_cost = $totalCost;
            $purchase->save();

            return $purchase;
        });
    }
}
