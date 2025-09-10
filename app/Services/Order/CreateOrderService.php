<?php

namespace App\Services\Order;

use App\Models\BranchProduct;
use App\Models\Order;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateOrderService
{
    /**
     * Handle the entire checkout process.
     *
     * @param array $cartItems The items in the cart.
     * @param int|null $customerId The ID of the selected customer.
     * @param User $user The user performing the action (cashier).
     * @return Order
     * @throws \Exception
     */
    public function handle(array $cartItems, ?int $customerId, User $user): Order
    {
        return DB::transaction(function () use ($cartItems, $customerId, $user) {
            // Step 1: Pre-check stock availability
            foreach ($cartItems as $item) {
                $inventory = BranchProduct::where('branch_id', $user->branch_id)
                    ->where('product_id', $item['product_id'])
                    ->first();

                if (!$inventory || $inventory->quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$item['name']}");
                }
            }

            // Step 2: Create the Order Header
            $subtotal = collect($cartItems)->sum(fn($item) => $item['price'] * $item['quantity']);

            $order = Order::create([
                'tenant_id' => $user->tenant_id,
                'branch_id' => $user->branch_id,
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'customer_id' => $customerId,
                'user_id' => $user->id,
                'sub_total' => $subtotal,
                'discount' => 0, // Placeholder for now
                'total_amount' => $subtotal, // Placeholder for now
                'status' => 'completed',
                'payment_status' => 'paid',
                'created_by' => $user->id,
            ]);

            // Step 3: Loop through items to create order items, decrement stock, and create movements
            foreach ($cartItems as $item) {
                // 3a. Create Order Item
                $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['quantity'] * $item['price'],
                ]);

                // 3b. Decrement stock
                $inventory = BranchProduct::where('branch_id', $user->branch_id)
                    ->where('product_id', $item['product_id'])->first();
                $inventory->decrement('quantity', $item['quantity']);

                // 3c. Create Stock Movement
                StockMovement::create([
                    'tenant_id' => $user->tenant_id,
                    'branch_id' => $user->branch_id,
                    'product_id' => $item['product_id'],
                    'type' => 'sale',
                    'quantity' => -$item['quantity'], // Use negative for sales
                    'reference_type' => Order::class,
                    'reference_id' => $order->id,
                    'remaining_quantity' => $inventory->quantity,
                    'created_by' => $user->id,
                ]);
            }

            return $order;
        });
    }
}