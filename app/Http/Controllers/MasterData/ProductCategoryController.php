<?php

declare(strict_types=1);

namespace App\Http\Controllers\MasterData;

use App\Actions\ProductCategories\CreateProductCategoryAction;
use App\Actions\ProductCategories\DeleteProductCategoryAction;
use App\Actions\ProductCategories\GetProductCategoryAction;
use App\Actions\ProductCategories\UpdateProductCategoryAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * Controller สำหรับจัดการหมวดหมู่สินค้า
 */
class ProductCategoryController extends Controller
{
    public function __construct(
        private readonly GetProductCategoryAction $getAction,
        private readonly CreateProductCategoryAction $createAction,
        private readonly UpdateProductCategoryAction $updateAction,
        private readonly DeleteProductCategoryAction $deleteAction,
    ) {}

    /**
     * แสดงรายการหมวดหมู่สินค้า
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Category::class);
        $search = $request->search;

        $productCategories = Category::query()
            ->withCount('products') // Eager load จำนวนสินค้า ป้องกัน N+1
            ->when($search, function ($query, string $search): void {
                $query->where('name', 'like', "%{$search}%");
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('master-data.product-category.index', [
            'productCategories' => $productCategories,
            'search' => $search,
        ]);
    }

    /**
     * แสดงฟอร์มสร้างหมวดหมู่ใหม่
     */
    public function create(): View
    {
        Gate::authorize('create', Category::class);

        return view('master-data.product-category.create');
    }

    /**
     * บันทึกหมวดหมู่ใหม่
     */
    public function store(ProductCategoryRequest $request): RedirectResponse
    {
        Gate::authorize('create', Category::class);

        $category = $this->createAction->execute($request->toDTO());

        return redirect()
            ->route('product-categories.index')
            ->with('success', "เพิ่มหมวดหมู่ '{$category->name}' เรียบร้อยแล้ว");
    }

    /**
     * แสดงฟอร์มแก้ไขหมวดหมู่
     */
    public function edit(Category $productCategory): View
    {
        Gate::authorize('update', $productCategory);

        $category = $this->getAction->execute($productCategory);

        return view('master-data.product-category.edit', [
            'productCategory' => $category,
        ]);
    }

    /**
     * อัปเดตข้อมูลหมวดหมู่
     */
    public function update(ProductCategoryRequest $request, Category $productCategory): RedirectResponse
    {
        Gate::authorize('update', $productCategory);

        $category = $this->updateAction->execute($productCategory, $request->toDTO());

        return redirect()
            ->route('product-categories.index')
            ->with('success', "แก้ไขหมวดหมู่ '{$category->name}' เรียบร้อยแล้ว");
    }

    /**
     * ลบ/ปิดใช้งานหมวดหมู่
     */
    public function destroy(Category $productCategory, Request $request): RedirectResponse
    {
        Gate::authorize('delete', $productCategory);

        try {
            $this->deleteAction->execute(
                category: $productCategory,
                force: $request->boolean('force'),
            );

            return redirect()
                ->route('product-categories.index')
                ->with('success', 'ลบหมวดหมู่เรียบร้อยแล้ว');
        } catch (\Exception $e) {
            return redirect()
                ->route('product-categories.index')
                ->with('error', $e->getMessage());
        }
    }
}
