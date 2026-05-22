<?php

namespace App\Http\Controllers\MasterData;

use App\Actions\MasterData\Product\CreateProductAction;
use App\Actions\MasterData\Product\UpdateProductAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\MasterData\Product\StoreProductRequest;
use App\Http\Requests\MasterData\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Product::class);

        $search = $request->input('search');

        $products = Product::query()
            ->with('category') // ป้องกัน N+1
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('master-data.product.index', [
            'search' => '',
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Product::class);

        $categories = Category::where('is_active', true)->get();

        return view('master-data.product.create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request, CreateProductAction $action)
    {
        Gate::authorize('create', Product::class);

        try {
            $action->execute($request->toDTO());

            return redirect()
                ->route('product.index')
                ->with('success', 'Product Created Success');
        } catch (\Throwable $th) {
            Log::error('Failed to create product', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        Gate::authorize('view', $product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        Gate::authorize('update', $product);

        $categories = Category::where('is_active', true)->get();

        return view('master-data.product.edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product, UpdateProductAction $action)
    {
        Gate::authorize('update', $product);

        try {
            $action->execute($product, $request->toDTO());

            return redirect()
                ->route('product.index')
                ->with('success', 'Product Updated Success');
        } catch (\Throwable $th) {
            Log::error('Failed to update product', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Gate::authorize('delete', $product);

        try {
            $product->delete();

            return redirect()
                ->route('product.index')
                ->with('success', 'Product Deleted Success');
        } catch (\Throwable $th) {
            Log::error('Failed to delete product', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->with('error', $th->getMessage());
        }
    }
}
