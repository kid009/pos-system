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

    // ==========================================
    // 1. ระบบชำระเงิน (Checkout)
    // ==========================================
    public function checkout($cart, $totalAmount, $receivedAmount, $paymentMethod, $customerId, $deliveryFee = 0, $discount = 0, $note = null, $transactionDate = null)
    {
        $currentUser = Auth::user();
        $currentShopId = $currentUser->shops()->first()->id ?? 0;

        // 1. Validation
        if (empty($cart)) {
            $this->dispatch('notify', message: 'Cart is empty!', type: 'error');
            return;
        }

        DB::beginTransaction();

        try {
            // A. วนลูปเช็คสต็อก
            foreach ($cart as $item) {
                // 🔒 Security: ค้นหาสินค้าที่อยู่ใน "ร้านปัจจุบัน" เท่านั้น ป้องกันการแฮ็กส่ง ID สินค้าร้านอื่นมาซื้อ
                $product = Product::where('id', $item['id'])
                    ->whereHas('category', function($q) use ($currentShopId) {
                        $q->where('shop_id', $currentShopId);
                    })->first();

                if (!$product) {
                    throw new Exception("ไม่พบสินค้า '{$item['name']}' ในระบบของร้านนี้");
                }

                // เช็คว่าต้องนับสต็อกไหม (อิงจาก Category Model ที่เราเขียนไว้)
                // หรือใช้ $isService = !$product->category->isTrackingStock(); ก็ได้ถ้าเพิ่มใน Model แล้ว
                $isService = $product->category && in_array($product->category->name, ['น้ำแก๊ส', 'บริการ', 'ค่าขนส่ง']);

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
                'shop_id'        => $currentShopId, // ✅ บันทึก Shop ID ลงบิล
            ]);

            // D. สร้าง Detail
            foreach ($cart as $item) {
                $product = Product::find($item['id']);
                if (!$product) continue;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id'     => $item['id'],
                    'product_name'   => $item['name'],
                    'gas_status'     => $item['gas_status'] ?? null,
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

            // E. ส่งผลลัพธ์กลับไปปริ้นใบเสร็จ
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

    // ==========================================
    // 2. Render แสดงผลหน้า POS
    // ==========================================
    public function render()
    {
        $currentUser = Auth::user();
        $currentShopId = $currentUser->shops()->first()->id ?? 0;

        // 🏷️ 1. ดึงหมวดหมู่ (เฉพาะของร้านตัวเอง)
        $categories = Category::where('shop_id', $currentShopId)->orderBy('name')->get();

        // 📦 2. ดึงสินค้า (เฉพาะสินค้าที่อยู่ในหมวดหมู่ของร้านตัวเอง)
        $products = Product::query()
            ->whereHas('category', function ($q) use ($currentShopId) {
                $q->where('shop_id', $currentShopId); // 🔒 ล็อกร้านค้า
            })
            ->when($this->category_id, function ($q) {
                $q->where('category_id', $this->category_id);
            })
            ->where('is_active', true)
            ->take(50)
            ->get();

        // 👥 3. ดึงข้อมูลลูกค้า (เฉพาะลูกค้าร้านตัวเอง)
        $customers = Customer::query()
            ->where('shop_id', $currentShopId) // 🔒 ล็อกร้านค้า
            ->when($this->customerSearch, function($q) {
                // 🐛 BUG FIX: ต้องเอา () มาครอบ where(...) orWhere(...) ไม่งั้น Query จะพังไปดึงลูกค้าข้ามร้าน
                $q->where(function($subQ) {
                    $subQ->where('name', 'like', '%' . $this->customerSearch . '%')
                         ->orWhere('phone', 'like', '%' . $this->customerSearch . '%');
                });
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
