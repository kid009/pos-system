<?php

namespace App\Livewire\Pos;

use Exception;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

// Models
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\TransactionDetail;

#[Layout('components.layouts.pos')]
#[Title('Point of Sale')]
class PosComponent extends Component
{
    public $category_id = null;
    public $lastTransaction = null;

    // ค้นหาลูกค้า
    public $customerSearch = '';

    // รับค่า checkout (มี $deliveryFee, $discount, $note, $customDate เพิ่มเข้ามา)
    public function checkout($cart, $totalAmount, $receivedAmount, $paymentMethod, $customerId, $deliveryFee = 0, $discount = 0, $note = null, $transactionDate = null)
    {
        // 1. Validation
        if (empty($cart)) {
            $this->dispatch('notify', message: 'Cart is empty!', type: 'error');
            return;
        }

        DB::beginTransaction();

        try {
            // A. วนลูปเช็คสต็อก
            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                if (!$product) continue;

                // เงื่อนไข: ไม่เช็คสต็อกถ้าเป็น 'บริการ', 'ค่าขนส่ง'
                // หรือถ้าเป็น 'น้ำแก๊ส' แต่เลือกสถานะ 'คืนถังเปล่า' (สมมติ)
                $isService = $product->category && in_array($product->category->name, ['น้ำแก๊ส']);

                if (!$isService && $product->stock_qty < $item['qty']) {
                     throw new Exception("สินค้า '{$item['name']}' มีไม่พอ (เหลือ {$product->stock_qty})");
                }
            }

            // B. คำนวณตัวเลข
            $finalTotal = $totalAmount + $deliveryFee - $discount;
            $changeAmount = $receivedAmount - $finalTotal;
            $transactionDate = $transactionDate ? Carbon::parse($transactionDate) : now();

            // C. สร้าง Transaction
            $transaction = Transaction::create([
                'reference_no'   => 'INV-' . $transactionDate->format('YmdHis') . '-' . strtoupper(Str::random(4)),
                'user_id'        => Auth::id(),
                'customer_id'    => $customerId ?: null,
                'total_amount'   => $finalTotal,
                'delivery_fee'   => $deliveryFee,
                'discount_amount'=> $discount,
                'received_amount'=> $receivedAmount,
                'change_amount'  => $changeAmount,
                'payment_method' => $paymentMethod,
                'status'         => 'completed',
                'note'           => $note,
                'transaction_date'=> $transactionDate,
                'shop_id' => session('current_shop_id'),
            ]);

            // D. สร้าง Detail
            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                if (!$product) continue;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['id'],
                    'product_name'   => $item['name'],
                    'gas_status'     => $item['gas_status'] ?? null, // ✅ บันทึกสถานะถัง
                    'price'          => $item['price'],
                    'cost'           => $product->cost,
                    'quantity'       => $item['qty'],
                    'total_price'    => $item['price'] * $item['qty'],
                ]);

                // ตัดสต็อก
                $isService = $product->category && in_array($product->category->name, ['บริการ', 'ค่าขนส่ง', 'Services']);
                if (!$isService) {
                     $product->decrement('stock_qty', $item['qty']);
                }
            }

            DB::commit();

            // E. ส่งผลลัพธ์กลับ
            $this->lastTransaction = Transaction::with(['details', 'user', 'customer'])->find($transaction->id);

            $this->dispatch('print-receipt');
            $this->dispatch('transaction-completed');

            $this->dispatch('notify',
                message: 'บันทึกสำเร็จ! เงินทอน: ' . number_format($changeAmount, 2),
                type: 'success'
            );

        } catch (Exception $e) {
            DB::rollBack();
            $this->dispatch('notify', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    }

    public function render()
    {
        // ... (Render Logic เดิม)
        $products = Product::query()
            ->when($this->category_id, function ($q) {
                $q->where('category_id', $this->category_id);
            })
            ->where('is_active', true)
            ->take(50)
            ->get();

        $categories = Category::orderBy('name')->get();

        $customers = Customer::query()
            ->when($this->customerSearch, function($q) {
                $q->where('name', 'like', '%' . $this->customerSearch . '%')
                  ->orWhere('phone', 'like', '%' . $this->customerSearch . '%');
            })
            ->orderBy('name')
            ->take(20)
            ->get();

        return view('livewire.pos.pos-component', [
            'products'   => $products,
            'categories' => $categories,
            'customers'  => $customers,
        ]);
    }
}
