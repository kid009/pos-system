<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProductCategory;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreProductCategoryRequest;
use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Admin\UpdateProductCategoryRequest;
use App\Services\ProductCategoryService;

class ProductCategoryController extends Controller
{
    protected $categoryService;

    public function __construct(ProductCategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = $this->categoryService->listPaginated();

        return view('admin.product-categories.index', [
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.product-categories.create', [
            'category' => new ProductCategory()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductCategoryRequest $request)
    {
        try {
            $this->categoryService->createCategory($request->validated());

            return redirect()->route('admin.product-categories.index')->with('success', 'บันทึกหมวดหมู่ใหม่เรียบร้อยแล้ว');
        } catch (\Exception $e) {
            // บันทึก Log สำหรับ Developer
            Log::error('Error storing category: ' . $e->getMessage()); 
            // แจ้งเตือนผู้ใช้งาน
            return redirect()->back()
                             ->with('error', 'เกิดข้อผิดพลาดในการบันทึก: ' . $e->getMessage())
                             ->withInput(); // ส่งข้อมูลที่กรอกไว้กลับไป
        }
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
        $category = ProductCategory::find($id);

        return view('admin.product-categories.edit', [
            'category' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductCategoryRequest $request, string $id)
    {
        try {
            $category = ProductCategory::find($id);
            // $request->validated() คือข้อมูลที่ผ่านการตรวจสอบแล้ว
            $this->categoryService->updateCategory($request->validated(), $category);

            return redirect()->route('admin.product-categories.index')
                             ->with('success', 'อัปเดตหมวดหมู่เรียบร้อยแล้ว');

        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'เกิดข้อผิดพลาดในการอัปเดต: ' . $e->getMessage())
                             ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $category = ProductCategory::find($id);
            $this->categoryService->deleteCategory($category);
            
            return redirect()->route('admin.product-categories.index')
                             ->with('success', 'ลบหมวดหมู่เรียบร้อยแล้ว');

        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            // ในที่นี้ เราคาดหวัง $e->getMessage() ที่เราโยนมาจาก Service
            return redirect()->route('admin.product-categories.index')
                             ->with('error', $e->getMessage());
        }
    }
}
