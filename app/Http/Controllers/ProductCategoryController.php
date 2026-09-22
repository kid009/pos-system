<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\ProductCategory\CreateProductCategoryAction;
use App\Actions\ProductCategory\DeleteProductCategoryAction;
use App\Actions\ProductCategory\UpdateProductCategoryAction;
use App\Http\Requests\ProductCategory\StoreProductCategoryRequest;
use App\Http\Requests\ProductCategory\UpdateProductCategoryRequest;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProductCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $categories = ProductCategory::query()
            ->filter($request->only(['search', 'status']))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('product-categories.index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', ProductCategory::class);

        return view('product-categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreProductCategoryRequest $request,
        CreateProductCategoryAction $action
    ): RedirectResponse {
        $category = $action->execute($request->toDto());

        return redirect()
            ->route('product-categories.index')
            ->with('success', "Category '{$category->name}' was created successfully.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProductCategory $productCategory): View
    {
        Gate::authorize('update', $productCategory);

        return view('product-categories.edit', [
            'productCategory' => $productCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateProductCategoryRequest $request,
        ProductCategory $productCategory,
        UpdateProductCategoryAction $action
    ): RedirectResponse {
        $updatedProductCategory = $action->execute($productCategory, $request->toDto());

        return redirect()
            ->route('product-categories.index')
            ->with('success', "Category '{$updatedProductCategory->name}' updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        ProductCategory $productCategory,
        DeleteProductCategoryAction $action
    ): RedirectResponse {
        Gate::authorize('delete', $productCategory);

        $action->execute($productCategory);

        return redirect()
            ->route('product-categories.index')
            ->with('success', 'Product category deleted successfully.');
    }
}
