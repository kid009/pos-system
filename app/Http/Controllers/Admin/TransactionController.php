<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. รับค่าจากฟอร์มค้นหา (ถ้าไม่มีค่า default จะเป็น null)
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $search = $request->input('search');

        // 2. ดึงข้อมูล พร้อม Eager Loading (ลดปัญหา N+1 Query)
        $query = Transaction::with(['shop', 'cashier']);

        // 3. จัดการสิทธิ์ (Admin เห็นทุกร้าน, Staff เห็นแค่ร้านตัวเอง)
        if ($user->role !== 'admin') {
            $query->where('shop_id', $user->shop_id ?? 1);
        }

        // 4. เงื่อนไขการค้นหาด้วย "เลขที่บิล"
        if ($search) {
            $query->where('invoice_no', 'like', "%{$search}%");
        }

        // 5. เงื่อนไขการค้นหาด้วย "วันที่" (Date Range Filter)
        if ($startDate) {
            // ใช้ whereDate เพื่อให้ครอบคลุมตั้งแต่ 00:00:00
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            // ใช้ whereDate เพื่อให้ครอบคลุมถึง 23:59:59
            $query->whereDate('created_at', '<=', $endDate);
        }

        // 6. เรียงจากล่าสุด และแบ่งหน้า (หน้าละ 20 บิล)
        // 💡 withQueryString() สำคัญมาก! เพื่อให้ตอนกดเปลี่ยนหน้าเว็บ ค่าว้นที่ยังจำอยู่
        $transactions = $query->latest()->paginate(20)->withQueryString();

        return view('admin.transaction.index', [
            'transactions' => $transactions,
            'search' => $search,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ]);
    }

    public function show($id)
    {
        $user = Auth::user();
        $transaction = Transaction::with(['shop', 'cashier', 'details.product'])->findOrFail($id);

        // จัดการสิทธิ์ (Admin เห็นทุกร้าน, Staff เห็นแค่ร้านตัวเอง)
        if ($user->role !== 'admin' && $transaction->shop_id !== $user->shop_id) {
            abort(403, 'Unauthorized action.');
        }

        return view('admin.transaction.show', compact('transaction'));
    }
}
