<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionService
{
    /**
     * ดำเนินการสร้าง Transaction (Checkout)
     */
    public function checkout(array $data)
    {
        return DB::transaction(function () use ($data) {
            $date = isset($data['transaction_date']) ? Carbon::parse($data['transaction_date']) : now();
            $datePrefix = 'REC' . $date->format('Ymd');

            $lastTx = Transaction::where('invoice_no', 'like', $datePrefix . '%')
                ->lockForUpdate()
                ->orderBy('invoice_no', 'desc')
                ->first();

            $queue = 1;
            if ($lastTx) {
                $lastQueue = (int) substr($lastTx->invoice_no, -4);
                $queue = $lastQueue + 1;
            }

            $invoiceNo = $datePrefix . str_pad($queue, 4, '0', STR_PAD_LEFT);

            // สร้างหัวบิล
            $tx = Transaction::create([
                'invoice_no' => $invoiceNo,
                'transaction_date' => $date->format('Y-m-d'),
                'shop_id' => $data['shop_id'],
                'customer_id' => $data['customer_id'] ?? null,
                'user_id' => Auth::id(),
                'total_amount' => 0,
                'discount_amount' => $data['discount_amount'] ?? 0,
                'shipping_amount' => $data['shipping_amount'] ?? 0,
                'receive_amount' => $data['receive_amount'],
                'change_amount' => 0,
                'payment_method' => $data['payment_method'],
                'created_at' => $date,
            ]);

            $calculatedTotal = 0;

            foreach ($data['cart'] as $item) {
                $product = Product::find($item['id']);
                if (!$product) {
                    throw new Exception("ไม่พบสินค้า " . ($item['name'] ?? 'ID: ' . $item['id']));
                }

                // หักสต็อกสินค้า
                $product->decrement('stock', $item['qty']);

                $subtotal = (float)$item['price'] * (float)$item['qty'];
                $calculatedTotal += $subtotal;

                $tx->details()->create([
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'cost' => $product->cost,
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal
                ]);
            }

            // สรุปยอดเงิน (ยอดสินค้า + ค่าขนส่ง - ส่วนลด)
            $finalTotal = $calculatedTotal + ($data['shipping_amount'] ?? 0) - ($data['discount_amount'] ?? 0);

            $tx->update([
                'total_amount' => $finalTotal,
                'change_amount' => max(0, $data['receive_amount'] - $finalTotal)
            ]);

            return $tx;
        });
    }
}
