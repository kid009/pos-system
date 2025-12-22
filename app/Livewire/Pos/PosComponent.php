<?php

namespace App\Livewire\Pos;

use Exception;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Services\LogService;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.pos')] // ✅ เรียกใช้ Layout ใหม่
#[Title('Point of Sale')]
class PosComponent extends Component
{
    public $category_id = null; // ต้องมีตัวแปรนี้
    public $lastTransaction = null;

    public function checkout($cart, $totalAmount, $receivedAmount, $paymentMethod, $customerId)
    {
        // 1. Validation เบื้องต้น
        if (empty($cart)) {
            $this->dispatch('notify', message: 'Cart is empty!', type: 'error');
            return;
        }

        // 2. เริ่ม Transaction Database
        DB::beginTransaction();

        try {
            // A. วนลูปเช็คสต็อกก่อน (สำคัญมาก! ต้องเช็คก่อนสร้างบิล)
            foreach ($cart as $item) {
                $product = Product::find($item['id']);

                // ถ้าหาไม่เจอ หรือ สต็อกไม่พอ
                if (!$product || $product->stock_qty < $item['qty']) {

                    // 🚨 LOG CRITICAL: แจ้งเตือนสินค้าไม่พอขาย
                    LogService::critical('POS Stock Mismatch Alert', [
                        'product_id' => $item['id'],
                        'product_name' => $item['name'],
                        'req_qty' => $item['qty'],
                        'current_stock' => $product ? $product->stock_qty : 'Not Found'
                    ]);

                    throw new Exception("สินค้า {$item['name']} มีไม่พอจำหน่าย (เหลือ {$product->stock_qty})");
                }
            }

            // B. สร้างหัวบิล (Transaction)
            $transaction = Transaction::create([
                'reference_no' => 'INV-' . date('YmdHis') . '-' . strtoupper(Str::random(4)),
                'user_id' => auth()->id(), // ใช้ auth()->id() สั้นกว่า
                'customer_id' => $customerId,
                'total_amount' => $totalAmount,
                'received_amount' => $receivedAmount,
                'change_amount' => $receivedAmount - $totalAmount,
                'payment_method' => $paymentMethod,
                'status' => 'completed'
            ]);

            // C. บันทึกรายการสินค้า และ ตัดสต็อกจริง
            foreach ($cart as $item) {
                $product = Product::find($item['id']);

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'cost' => $product->cost, // เก็บต้นทุน ณ วันที่ขาย
                    'quantity' => $item['qty'],
                    'total_price' => $item['price'] * $item['qty'],
                ]);

                // ตัดสต็อก
                $product->decrement('stock_qty', $item['qty']);
            }

            DB::commit(); // ✅ บันทึกข้อมูลลงฐานข้อมูล

            // ✅ 1. เก็บข้อมูลบิลล่าสุดไว้ เพื่อเอาไปแสดงในใบเสร็จ
            // ต้องโหลด details มาด้วย ไม่งั้นหน้าใบเสร็จจะ Loop ไม่ได้
            $this->lastTransaction = Transaction::with(['details', 'user', 'customer'])
                                        ->find($transaction->id);

            // ✅ 2. สั่ง Event ให้ Frontend เปิดหน้าต่าง Print
            $this->dispatch('print-receipt');

            // 📝 LOG INFO: บันทึกเมื่อสร้างบิลสำเร็จ
            LogService::info("POS Transaction Created", [
                'ref_no' => $transaction->reference_no,
                'amount' => $totalAmount,
                'payment_method' => $paymentMethod,
                'items_count' => count($cart)
            ]);

            // D. แจ้งเตือนหน้าจอ
            $this->dispatch('notify', message: 'Payment Successful! Change: ' . number_format($transaction->change_amount, 2), type: 'success');
            $this->dispatch('transaction-completed');
        } catch (Exception $e) {
            DB::rollBack(); // ❌ ยกเลิกทั้งหมดถ้า error

            // 📝 LOG ERROR: บันทึก Error (ส่ง Exception เข้าไปเลย Service จะแตกข้อมูลให้)
            LogService::error("POS Checkout Failed", $e, [
                'cart_data' => $cart, // เก็บข้อมูลตะกร้าไว้ดูว่าลูกค้าพยายามซื้ออะไร
                'total_amount' => $totalAmount
            ]);

            $this->dispatch('notify', message: 'Error: ' . $e->getMessage(), type: 'error');
        }
    }

    public function logout()
    {
        // 📝 LOG INFO: ใคร Logout
        LogService::info("User Logout", [
            'role' => auth()->user()->role ?? 'staff'
        ]);

        auth()->logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/login');
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
