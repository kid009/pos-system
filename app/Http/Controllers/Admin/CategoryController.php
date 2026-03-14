<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * ดึง Shop ปัจจุบัน (ในอนาคตควรดึงจาก Auth::user()->shop_id)
     */
    private function getCurrentShopId()
    {
        // กรณี Single Store ดึงร้านแรกเสมอ
        return Shop::value('id') ?? 1;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();

        // 💡 Architectural Fix: ดึงข้อมูล Shop มาด้วยเสมอ (Eager Loading) ป้องกัน N+1
        $query = Category::with(['shop'])->withCount('products');

        // ถ้าไม่ใช่ Admin ให้กรองเฉพาะร้านของตัวเอง
        if ($user->role !== 'admin') {
            $query->where('shop_id', $this->getCurrentShopId());
        }

        $categories = $query->when($search, function ($q, $search) {
                return $q->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.category.index', [
            'categories' => $categories,
            'search' => $search,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // ถ้าเป็น Admin ให้ส่งรายชื่อร้านค้าทั้งหมดไปให้เลือกใน Dropdown
        $shops = Auth::user()->role === 'admin' ? Shop::all() : collect();

        return view('admin.category.create', [
            'shops' => $shops,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ];

        // ถ้าเป็น Admin บังคับให้ต้องเลือกร้านค้า
        if ($user->role === 'admin') {
            $rules['shop_id'] = 'required|exists:shops,id';
        }

        $request->validate($rules);

        $data = $request->only(['name']);
        $data['is_active'] = $request->boolean('is_active');

        // 💡 กำหนด shop_id ตามสิทธิ์ของผู้ใช้
        $data['shop_id'] = $user->role === 'admin' ? $request->shop_id : $this->getCurrentShopId();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('category.index')->with('success', 'เพิ่มหมวดหมู่เรียบร้อยแล้ว');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::findOrFail($id);

        $user = Auth::user();

        // ถ้าไม่ใช่ Admin และพยายามแก้ของร้านอื่น ให้ดีดออก
        abort_if($user->role !== 'admin' && $category->shop_id !== $this->getCurrentShopId(), 403, 'Unauthorized Access');

        $shops = $user->role === 'admin' ? Shop::all() : collect();

        return view('admin.category.edit', [
            'shop' => $shops,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $category = Category::findOrFail($id);

        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ];

        if ($user->role === 'admin') {
            $rules['shop_id'] = 'required|exists:shops,id';
        }

        $request->validate($rules);

        $data = $request->only(['name']);
        $data['is_active'] = $request->boolean('is_active');

        if ($user->role === 'admin') {
            $data['shop_id'] = $request->shop_id;
        }

        if ($request->hasFile('image')) {
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            $data['image_path'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('category.index')->with('success', 'อัปเดตหมวดหมู่เรียบร้อยแล้ว');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::findOrFail($id);

        $user = Auth::user();

        abort_if($user->role !== 'admin' && $category->shop_id !== $this->getCurrentShopId(), 403);

        if ($category->products()->count() > 0) {
            return redirect()->back()->with('error', 'ไม่สามารถลบได้ เนื่องจากมีสินค้าใช้งานหมวดหมู่นี้อยู่');
        }

        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }

        $category->delete();

        return redirect()->route('category.index')->with('success', 'ลบหมวดหมู่เรียบร้อยแล้ว');
    }
}
