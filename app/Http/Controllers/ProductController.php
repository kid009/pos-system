<?php

namespace App\Http\Controllers;

use App\Actions\Product\CreateProductAction;
use App\Actions\Product\UpdateProductAction;
use App\DTOs\Product\ProductDTO;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $products = Product::with(['category', 'prices', 'affiliateLinks'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('product.index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('product.create', [
            'product' => new Product,
            'categories' => $this->getCategories(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request, CreateProductAction $action): RedirectResponse
    {
        $data = $request->validated();
        $data['image_path'] = $this->storeImage($request);

        $dto = ProductDTO::formRequest($data);
        $product = $action->execute($dto);

        return redirect()->route('product.index')->with('success', 'Created Product: '.$product->name);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        return view('product.show', [
            'product' => $product->load(['category', 'prices', 'affiliateLinks']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        return view('product.edit', [
            'product' => $product->load(['prices', 'affiliateLinks']),
            'categories' => $this->getCategories(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product, UpdateProductAction $action): RedirectResponse
    {
        $data = $request->validated();
        $oldImagePath = $product->image_path;
        $data['image_path'] = $this->storeImage($request, $oldImagePath);

        $dto = ProductDTO::formRequest($data);
        $action->execute($product, $dto);

        return redirect()->route('product.index')->with('success', 'Updated Product: '.$product->fresh()->name);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $name = $product->name;

        if ($product->image_path !== null) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('product.index')->with('delete', 'Deleted Product: '.$name);
    }

    /**
     * @return Collection<int, Category>
     */
    private function getCategories(): Collection
    {
        return Category::orderBy('name')
            ->where('is_active', true)
            ->get();
    }

    private function storeImage(StoreProductRequest|UpdateProductRequest $request, ?string $oldImagePath = null): ?string
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $path = $request->file('image')->store('products', 'public');

        if ($oldImagePath !== null) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return $path;
    }
}
