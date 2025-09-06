<?php

namespace App\Http\Controllers\Store;

use Log;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Http\Request;
use App\Models\BranchProduct;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // ดึงข้อมูลการสั่งซื้อของสาขาที่ login อยู่เท่านั้น
        // พร้อมดึงข้อมูลผู้สร้าง (creator) มาด้วยเพื่อแสดงผล (Eager Loading)
        $purchases = Purchase::where('branch_id', auth()->user()->branch_id)
            ->with('creator')
            ->latest('purchase_date')
            ->paginate(15);
            
        return view('store.purchases.index', [
            'purchases' => $purchases
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $products = Product::where('tenant_id', auth()->user()->tenant_id)->orderBy('name')->get();
        return view('store.purchases.create', [
            // 'products' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $purchase = Purchase::find($id);

        // ตรวจสอบสิทธิ์ว่าผู้ใช้เป็นเจ้าของข้อมูลหรือไม่
        abort_if($purchase->branch_id != auth()->user()->branch_id, 403);

        // ดึงข้อมูลรายการสินค้าและข้อมูลสินค้าที่เกี่ยวข้องมาด้วย
        $purchase->load(['items', 'items.product', 'creator']);

        return view('store.purchases.show', [
            'purchase' => $purchase
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Purchase $purchase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Purchase $purchase)
    {
        //
    }
}
