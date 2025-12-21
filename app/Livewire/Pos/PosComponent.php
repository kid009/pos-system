<?php

namespace App\Livewire\Pos;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;

#[Layout('components.layouts.pos')] // ✅ เรียกใช้ Layout ใหม่
#[Title('Point of Sale')]
class PosComponent extends Component
{
    public $category_id = null; // ต้องมีตัวแปรนี้

    public function checkout($cart, $totalAmount, $receivedAmount, $paymentMethod = 'cash', $customerId = null)
    {
        // 1. Validation เบื้องต้น
        if (empty($cart)) {
            $this->dispatch('notify', message: 'Cart is empty!', type: 'error');
            return;
        }

        // 2. เริ่ม Transaction Database (กันข้อมูลพังครึ่งๆ กลางๆ)
        DB::beginTransaction();

        try {
            // A. สร้างหัวบิล (Transaction)
            $transaction = Transaction::create([
                'reference_no' => 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6)), // Gen เลขบิลแบบง่าย
                'user_id' => Auth::user()->id,
                'customer_id' => $customerId,
                'total_amount' => $totalAmount,
                'received_amount' => $receivedAmount,
                'change_amount' => $receivedAmount - $totalAmount,
                'payment_method' => $paymentMethod,
                'status' => 'completed'
            ]);

            // B. วนลูปสินค้า เพื่อสร้าง Detail และตัดสต็อก
            foreach ($cart as $item) {
                // บันทึกรายการสินค้า
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['qty'],
                    'total_price' => $item['price'] * $item['qty'],
                ]);

                // ตัดสต็อกสินค้า
                $product = Product::find($item['id']);
                if ($product) {
                    $product->decrement('stock_qty', $item['qty']);
                }
            }

            DB::commit(); // บันทึกจริง

            // C. แจ้งเตือน และ สั่งเคลียร์ตะกร้าหน้าบ้าน
            $this->dispatch('notify', message: 'Payment Successful! Change: ' . number_format($transaction->change_amount, 2), type: 'success');

            // ส่ง Event กลับไปบอก Alpine ให้เคลียร์ตะกร้า
            $this->dispatch('transaction-completed');
        } catch (\Exception $e) {
            DB::rollBack(); // ยกเลิกทั้งหมดถ้า error
            $this->dispatch('notify', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function render()
    {
        $products = Product::query()->when($this->category_id, function ($q) {
            $q->where('category_id', $this->category_id);
        })
            ->where('is_active', true)
            ->take(30)
            ->get();

        $categories = Category::all();

        $customers = Customer::orderBy('name')->get();

        return view('livewire.pos.pos-component', [
            'products' => $products,
            'categories' => $categories,
            'customers' => $customers,
        ]);
    }
}
