<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    private function getCurrentShopId()
    {
        return Shop::value('id') ?? 1;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $user = Auth::user();

        // โหลดข้อมูลหมวดหมู่และร้านค้า ป้องกัน N+1 Query
        $query = Product::with(['category', 'shop']);

        if ($user->role !== 'admin') {
            $query->where('shop_id', $this->getCurrentShopId());
        }

        $products = $query->when($search, function ($q, $search) {
                return $q->where('name', 'like', "%{$search}%")
                         ->orWhere('sku', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15); // สินค้ามีเยอะ ปรับเป็นหน้าละ 15

        return view('admin.products.index', [
            'products' => $products,
            'search' => $search,
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            // Admin: ดึงร้านค้าทั้งหมด พร้อมหมวดหมู่ของร้านนั้นๆ
            $shops = Shop::with('categories')->get();
            $categories = collect(); // ไม่ได้ใช้ตรงๆ จะดึงผ่าน Shop
        } else {
            // Staff: ดึงเฉพาะหมวดหมู่ของร้านตัวเอง
            $shops = collect();
            $categories = Category::where('shop_id', $this->getCurrentShopId())->get();
        }

        return view('admin.products.create', [
            'shops' => $shops,
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'image' => 'nullable|image|max:2048',
        ];

        if ($user->role === 'admin') {
            $rules['shop_id'] = 'required|exists:shops,id';
        }

        $request->validate($rules);

        $data = $request->only(['name', 'category_id', 'sku', 'price', 'cost', 'unit']);
        $data['is_active'] = $request->boolean('is_active');
        $data['shop_id'] = $user->role === 'admin' ? $request->shop_id : $this->getCurrentShopId();

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'เพิ่มสินค้าเรียบร้อยแล้ว');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user();
        abort_if($user->role !== 'admin' && $product->shop_id !== $this->getCurrentShopId(), 403);

        if ($user->role === 'admin') {
            $shops = Shop::with('categories')->get();
            $categories = collect();
        } else {
            $shops = collect();
            $categories = Category::where('shop_id', $this->getCurrentShopId())->get();
        }

        return view('admin.products.edit', [
            'shops' => $shops,
            'categories' => $categories,
            'product' => $product,
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user();
        abort_if($user->role !== 'admin' && $product->shop_id !== $this->getCurrentShopId(), 403);

        $rules = [
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'sku' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0',
            'cost' => 'required|numeric|min:0',
            'unit' => 'nullable|string|max:50',
            'image' => 'nullable|image|max:2048',
        ];

        if ($user->role === 'admin') {
            $rules['shop_id'] = 'required|exists:shops,id';
        }

        $request->validate($rules);

        $data = $request->only(['name', 'category_id', 'sku', 'price', 'cost', 'unit']);
        $data['is_active'] = $request->boolean('is_active');

        if ($user->role === 'admin') {
            $data['shop_id'] = $request->shop_id;
        }

        if ($request->hasFile('image')) {
            if ($product->image_path) {
                Storage::disk('public')->delete($product->image_path);
            }
            $data['image_path'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'อัปเดตสินค้าเรียบร้อยแล้ว');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $user = Auth::user();
        abort_if($user->role !== 'admin' && $product->shop_id !== $this->getCurrentShopId(), 403);

        // TODO: ในอนาคตต้องเช็คว่าสินค้านี้ถูกผูกกับ Transaction ขายแล้วหรือยัง ห้ามลบถ้ามีการขายไปแล้ว (Safe Delete)

        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'ลบสินค้าเรียบร้อยแล้ว');
    }
}
