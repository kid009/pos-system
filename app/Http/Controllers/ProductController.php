<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Product\CreateProductAction;
use App\Actions\Product\DeleteProductAction;
use App\Actions\Product\UpdateProductAction;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with('category')
            ->filter($request->only(['search', 'status']))
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('products.index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        Gate::authorize('create', Product::class);

        $categories = ProductCategory::orderBy('name')->get();

        return view('products.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        StoreProductRequest $request,
        CreateProductAction $action
    ): RedirectResponse {
        $product = $action->execute($request->toDto());

        return redirect()
            ->route('products.index')
            ->with('success', "Product '{$product->name}' was created successfully.");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        Gate::authorize('update', $product);

        $categories = ProductCategory::orderBy('name')->get();

        return view('products.edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateProductRequest $request,
        Product $product,
        UpdateProductAction $action
    ): RedirectResponse {
        $updatedProduct = $action->execute($product, $request->toDto());

        return redirect()
            ->route('products.index')
            ->with('success', "Product '{$updatedProduct->name}' updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Product $product,
        DeleteProductAction $action
    ): RedirectResponse {
        Gate::authorize('delete', $product);

        $action->execute($product);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
