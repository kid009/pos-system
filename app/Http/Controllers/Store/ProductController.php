<?php

namespace App\Http\Controllers\Store;

use Log;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('productCategory') // Eager load หมวดหมู่
            ->where('tenant_id', auth()->user()->tenant_id)
            ->latest()
            ->paginate(10);

        return view('store.products.index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ดึงหมวดหมู่ย่อยของร้านตัวเองมาให้เลือก
        $categories = ProductCategory::where('tenant_id', auth()->user()->tenant_id)->get();
        
        return view('store.products.create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255',
            'product_category_id' => 'required|exists:product_categories,id',
            'description' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024', // Validation สำหรับรูปภาพ
        ]);

        try 
        {
            DB::beginTransaction();

            $productData = $validated;
            $productData['tenant_id'] = auth()->user()->tenant_id;
            $productData['created_by'] = auth()->id();

            // จัดการการอัปโหลดรูปภาพ
            if ($request->hasFile('image')) 
            {
                // คำสั่งนี้จะบันทึกไฟล์ไปที่ public/uploads/products/ โดยอัตโนมัติ
                $path = $request->file('image')->store('products', 'public');
                $productData['image'] = $path;
            }

            Product::create($productData);

            DB::commit();

            return redirect()->route('store.products.index')->with('success', 'Product created successfully.');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            // (แนะนำ) บันทึก Error ไว้ใน Log เพื่อตรวจสอบ
            Log::error('Product creation failed: ' . $e->getMessage());

            return back()->with('error', 'Failed to create product. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::find($id);

        // ตรวจสอบสิทธิ์ว่าผู้ใช้เป็นเจ้าของข้อมูลหรือไม่ (Optional but recommended)
        if ($product->tenant_id != auth()->user()->tenant_id) 
        {
            abort(403);
        }

        $categories = ProductCategory::where('tenant_id', auth()->user()->tenant_id)->get();

        return view('store.products.edit', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::find($id);

        // ตรวจสอบสิทธิ์
        if ($product->tenant_id != auth()->user()->tenant_id) 
        {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:255|unique:products,sku,' . $product->id, // unique rule สำหรับ update
            'product_category_id' => 'required|exists:product_categories,id',
            'description' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try 
        {
            DB::beginTransaction();

            $productData = $validated;
            $productData['updated_by'] = auth()->id();

            // จัดการการอัปเดต/ลบรูปภาพเก่า
            if ($request->hasFile('image')) {
                // 1. ลบรูปเก่า (ถ้ามี)
                if ($product->image) 
                {
                    Storage::disk('public')->delete($product->image);
                }

                // 2. อัปโหลดรูปใหม่
                $path = $request->file('image')->store('products', 'public');
                $productData['image'] = $path;
            }

            $product->update($productData);

            DB::commit();

            return redirect()->route('store.products.index')->with('success', 'Product updated successfully.');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            Log::error('Product update failed: ' . $e->getMessage());

            return back()->with('error', 'Failed to update product. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);

        // ตรวจสอบสิทธิ์
        if ($product->tenant_id != auth()->user()->tenant_id) 
        {
            abort(403);
        }

        // ลบไฟล์รูปภาพที่เกี่ยวข้องก่อน
        if ($product->image) 
        {
            Storage::disk('public')->delete($product->image);
        }

        // จากนั้นจึงลบข้อมูลออกจากฐานข้อมูล
        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }
}
