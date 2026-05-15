---
name: laravel-best-practices
description: Use when working with Laravel code in this POS system. Triggers on creating models, controllers, migrations, routes, APIs, tests, DTOs, Actions, CRUD operations, queries, soft deletes, relationships. Ensures PHP 8.3, Laravel 13 conventions.
---

# Laravel Best Practices for POS System - Complete CRUD Guide

## Architecture Overview
```
FormRequest → DTO → Action → Controller → Response
     ↓           ↓       ↓          ↓
 Validation   Data   Business   Orchestrate   API Resource
              Transfer  Logic      & Return      /View
```

---

## 1. FormRequest - Validation Layer

### Base Request Pattern
```php
// app/Http/Requests/ProductRequest.php
class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return match($this->method()) {
            'POST' => auth()->user()->can('create', Product::class),
            'PUT', 'PATCH' => auth()->user()->can('update', $this->product),
            default => false,
        };
    }
    
    public function rules(): array
    {
        $productId = $this->route('product')?->id;
        
        return [
            'name' => ['required', 'string', 'max:255'],
            'sku' => ['required', 'string', Rule::unique('products')->ignore($productId)],
            'price' => ['required', 'numeric', 'min:0'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['exists:categories,id'],
        ];
    }
    
    public function toDTO(): ProductDTO
    {
        return new ProductDTO(...$this->validated());
    }
}
```

---

## 2. DTOs - Data Transfer Objects

```php
// app/DTOs/ProductDTO.php
readonly class ProductDTO
{
    public function __construct(
        public string $name,
        public string $sku,
        public float $price,
        public ?array $categoryIds = null,
    ) {}
    
    public static function fromRequest(FormRequest $request): self
    {
        return $request->toDTO();
    }
}
```

---

## 3. CRUD Actions - Business Logic Layer

### Create Action
```php
// app/Actions/Products/CreateProductAction.php
class CreateProductAction
{
    public function execute(ProductDTO $dto, int $shopId): Product
    {
        return DB::transaction(function () use ($dto, $shopId) {
            $product = Product::create([
                'name' => $dto->name,
                'sku' => $dto->sku,
                'price' => $dto->price,
                'shop_id' => $shopId,
            ]);
            
            if ($dto->categoryIds) {
                $product->categories()->attach($dto->categoryIds);
            }
            
            activity()->log("Created product: {$product->name}");
            
            return $product->load('categories');
        });
    }
}
```

### Read Actions (Query Builder)
```php
// app/Actions/Products/ListProductsAction.php
class ListProductsAction
{
    public function execute(array $filters = []): LengthAwarePaginator
    {
        return Product::query()
            ->with(['categories', 'shop']) // Eager loading
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('sku', 'like', "%{$search}%");
                });
            })
            ->when($filters['category_id'] ?? null, function ($query, $categoryId) {
                $query->whereHas('categories', fn($q) => $q->where('id', $categoryId));
            })
            ->when($filters['min_price'] ?? null, fn($q, $price) => $q->where('price', '>=', $price))
            ->when($filters['max_price'] ?? null, fn($q, $price) => $q->where('price', '<=', $price))
            ->orderBy($filters['sort_by'] ?? 'created_at', $filters['sort_order'] ?? 'desc')
            ->paginate($filters['per_page'] ?? 15);
    }
}

// app/Actions/Products/GetProductAction.php
class GetProductAction
{
    public function execute(Product $product): Product
    {
        return $product->load([
            'categories',
            'shop',
            'transactions' => fn($q) => $q->latest()->limit(5),
        ]);
    }
}
```

### Update Action
```php
// app/Actions/Products/UpdateProductAction.php
class UpdateProductAction
{
    public function execute(Product $product, ProductDTO $dto): Product
    {
        return DB::transaction(function () use ($product, $dto) {
            $oldData = $product->only(['name', 'sku', 'price']);
            
            $product->update([
                'name' => $dto->name,
                'sku' => $dto->sku,
                'price' => $dto->price,
            ]);
            
            if ($dto->categoryIds !== null) {
                $product->categories()->sync($dto->categoryIds);
            }
            
            activity()
                ->causedBy(auth()->user())
                ->performedOn($product)
                ->withProperties(['old' => $oldData, 'new' => $dto])
                ->log("Updated product: {$product->name}");
            
            return $product->fresh()->load('categories');
        });
    }
}
```

### Delete Actions (Soft & Force)
```php
// app/Actions/Products/DeleteProductAction.php
class DeleteProductAction
{
    public function execute(Product $product, bool $force = false): void
    {
        DB::transaction(function () use ($product, $force) {
            if ($product->transactions()->exists()) {
                throw new \Exception("Cannot delete: Product has transactions");
            }
            
            $force 
                ? $product->forceDelete()
                : $product->delete();
            
            activity()->log("Deleted product: {$product->name}" . ($force ? ' (permanent)' : ''));
        });
    }
}

// app/Actions/Products/RestoreProductAction.php
class RestoreProductAction
{
    public function execute(int $productId): Product
    {
        $product = Product::withTrashed()->findOrFail($productId);
        $product->restore();
        
        activity()->log("Restored product: {$product->name}");
        
        return $product;
    }
}

// app/Actions/Products/BulkDeleteAction.php
class BulkDeleteAction
{
    public function execute(array $ids, bool $force = false): int
    {
        return DB::transaction(function () use ($ids, $force) {
            $query = Product::whereIn('id', $ids);
            
            $count = $force ? $query->forceDelete() : $query->delete();
            
            activity()->log("Bulk deleted {$count} products");
            
            return $count;
        });
    }
}
```

---

## 4. Controller - Complete CRUD

```php
// app/Http/Controllers/MasterData/ProductController.php
class ProductController extends Controller
{
    public function __construct(
        private ListProductsAction $listAction,
        private GetProductAction $getAction,
        private CreateProductAction $createAction,
        private UpdateProductAction $updateAction,
        private DeleteProductAction $deleteAction,
        private RestoreProductAction $restoreAction,
    ) {}
    
    // ===== READ =====
    
    public function index(Request $request): View
    {
        $products = $this->listAction->execute($request->only([
            'search', 'category_id', 'min_price', 'max_price',
            'sort_by', 'sort_order', 'per_page',
        ]));
        
        return view('master-data.products.index', compact('products'));
    }
    
    public function show(Product $product): View
    {
        $product = $this->getAction->execute($product);
        return view('master-data.products.show', compact('product'));
    }
    
    public function create(): View
    {
        return view('master-data.products.create', [
            'categories' => Category::all(),
        ]);
    }
    
    public function edit(Product $product): View
    {
        return view('master-data.products.edit', [
            'product' => $product->load('categories'),
            'categories' => Category::all(),
        ]);
    }
    
    // ===== CREATE =====
    
    public function store(ProductRequest $request): RedirectResponse
    {
        $product = $this->createAction->execute(
            dto: $request->toDTO(),
            shopId: auth()->user()->shop_id,
        );
        
        return redirect()
            ->route('products.show', $product)
            ->with('success', "Product {$product->name} created");
    }
    
    // ===== UPDATE =====
    
    public function update(ProductRequest $request, Product $product): RedirectResponse
    {
        $product = $this->updateAction->execute(
            product: $product,
            dto: $request->toDTO(),
        );
        
        return redirect()
            ->route('products.show', $product)
            ->with('success', "Product {$product->name} updated");
    }
    
    // ===== DELETE =====
    
    public function destroy(Product $product, Request $request): RedirectResponse
    {
        try {
            $this->deleteAction->execute(
                product: $product,
                force: $request->boolean('force'),
            );
            
            return redirect()
                ->route('products.index')
                ->with('success', 'Product deleted');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    
    public function restore(int $id): RedirectResponse
    {
        $product = $this->restoreAction->execute($id);
        
        return redirect()
            ->route('products.show', $product)
            ->with('success', "Product {$product->name} restored");
    }
    
    // ===== BULK OPERATIONS =====
    
    public function bulkDelete(Request $request, BulkDeleteAction $action): JsonResponse
    {
        $count = $action->execute(
            ids: $request->input('ids', []),
            force: $request->boolean('force'),
        );
        
        return response()->json([
            'message' => "Deleted {$count} products",
            'count' => $count,
        ]);
    }
}
```

---

## 5. Query Optimization Guidelines

### Eager Loading (Prevent N+1)
```php
// ❌ Bad - N+1 Problem
$products = Product::all();
foreach ($products as $product) {
    echo $product->category->name; // Query เพิ่มทุกรอบ!
}

// ✅ Good - Eager Loading
$products = Product::with('category')->get();
foreach ($products as $product) {
    echo $product->category->name; // ใช้ข้อมูลที่ load มาแล้ว
}

// ✅ Better - Load Multiple Relations
$products = Product::with(['category', 'shop', 'transactions' => function ($q) {
    $q->latest()->limit(5); // Constraint on relation
}])->get();

// ✅ Nested Eager Loading
$products = Product::with('category.parent', 'shop.owner')->get();
```

### Select Specific Columns
```php
// ✅ Select only needed columns
Product::select(['id', 'name', 'price'])->get();

// ✅ For relations
Product::with(['category:id,name', 'shop:id,name'])->get();
```

### Query Caching
```php
// ✅ Cache frequently accessed queries
$categories = Cache::remember('categories.all', 3600, function () {
    return Category::all();
});

// ✅ Cache with tags (for selective flush)
$products = Cache::tags(['products'])->remember('products.popular', 3600, function () {
    return Product::popular()->limit(10)->get();
});
```

---

## 6. Soft Deletes Implementation

```php
// Migration
Schema::table('products', function (Blueprint $table) {
    $table->softDeletes(); // Adds deleted_at column
});

// Model
class Product extends Model
{
    use SoftDeletes, HasFactory;
    
    protected $dates = ['deleted_at'];
}

// Routes
Route::get('products/trashed', [ProductController::class, 'trashed'])->name('products.trashed');
Route::post('products/{id}/restore', [ProductController::class, 'restore'])->name('products.restore');
Route::delete('products/{id}/force', [ProductController::class, 'forceDelete'])->name('products.force-delete');
```

---

## 7. API Resources (for JSON responses)

```php
// app/Http/Resources/ProductResource.php
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => $this->price,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'created_at' => $this->created_at->toISOString(),
            'deleted_at' => $this->when($this->deleted_at, fn() => $this->deleted_at->toISOString()),
        ];
    }
}

// Controller usage
return new ProductResource($product);
return ProductResource::collection($products);
```

---

## 8. Testing CRUD Operations

```php
// tests/Feature/ProductTest.php
class ProductTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_can_create_product(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('products.store'), [
                'name' => 'Test Product',
                'sku' => 'TEST-001',
                'price' => 100,
            ]);
        
        $response->assertCreated()
            ->assertJsonPath('data.name', 'Test Product');
        
        $this->assertDatabaseHas('products', ['sku' => 'TEST-001']);
    }
    
    public function test_can_list_products_with_filters(): void
    {
        Product::factory()->count(5)->create(['shop_id' => $this->user->shop_id]);
        
        $response = $this->actingAs($this->user)
            ->getJson(route('products.index', ['search' => 'test']));
        
        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'sku', 'price']
                ],
                'links',
                'meta',
            ]);
    }
    
    public function test_can_update_product(): void
    {
        $product = Product::factory()->create();
        
        $response = $this->actingAs($this->user)
            ->putJson(route('products.update', $product), [
                'name' => 'Updated Name',
                'sku' => $product->sku,
                'price' => 200,
            ]);
        
        $response->assertOk();
        $this->assertDatabaseHas('products', ['name' => 'Updated Name']);
    }
    
    public function test_can_delete_product(): void
    {
        $product = Product::factory()->create();
        
        $response = $this->actingAs($this->user)
            ->deleteJson(route('products.destroy', $product));
        
        $response->assertRedirect();
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }
    
    public function test_can_restore_deleted_product(): void
    {
        $product = Product::factory()->create();
        $product->delete();
        
        $response = $this->actingAs($this->user)
            ->post(route('products.restore', $product->id));
        
        $response->assertRedirect();
        $this->assertDatabaseHas('products', ['id' => $product->id, 'deleted_at' => null]);
    }
}
```

---

## 9. Directory Structure (Complete)

```
app/
├── Http/
│   ├── Controllers/
│   │   └── MasterData/
│   │       └── ProductController.php
│   ├── Requests/
│   │   └── ProductRequest.php
│   └── Resources/
│       └── ProductResource.php
├── DTOs/
│   └── ProductDTO.php
├── Actions/
│   └── Products/
│       ├── CreateProductAction.php
│       ├── ListProductsAction.php
│       ├── GetProductAction.php
│       ├── UpdateProductAction.php
│       ├── DeleteProductAction.php
│       ├── RestoreProductAction.php
│       └── BulkDeleteAction.php
├── Models/
│   └── Product.php
└── Policies/
    └── ProductPolicy.php
```

---

## 10. Checklist ก่อนเริ่มงานใหม่

- [ ] สร้าง Migration พร้อม soft deletes (ถ้าจำเป็น)
- [ ] สร้าง Model พร้อม relationships
- [ ] สร้าง Policy สำหรับ authorization
- [ ] สร้าง FormRequest พร้อม validation rules
- [ ] สร้าง DTO สำหรับ data transfer
- [ ] สร้าง Actions สำหรับแต่ละ operation
- [ ] สร้าง Controller พร้อม methods ครบ CRUD
- [ ] สร้าง API Resource (ถ้าทำ API)
- [ ] สร้าง Views หรือ return responses
- [ ] สร้าง Tests ครบทุก cases
- [ ] รัน `vendor/bin/pint --dirty` ก่อน commit

---

## 11. Project-Specific Conventions

### Controller Organization
- `app/Http/Controllers/Admin/` - Admin functions
- `app/Http/Controllers/Inventory/` - Inventory management
- `app/Http/Controllers/MasterData/` - CRUD for reference data
- `app/Http/Controllers/Report/` - Reporting

### PHP Standards
- Constructor property promotion
- Explicit return types and type hints
- TitleCase for Enum keys
- PHPDoc blocks over inline comments
- Use array shape type definitions in PHPDoc

### Multi-tenancy
- ทุก query ต้อง scope ด้วย `shop_id`
- ใช้ `auth()->user()->shop_id` หรือ `auth()->user()->shop`

### Activity Logging
- บันทึกทุกการเปลี่ยนแปลงด้วย `spatie/laravel-activitylog`
- ใช้ `activity()->log()` หรือ `activity()->causedBy()->performedOn()->log()`

### Testing
- ใช้ **PHPUnit only** (ไม่ใช้ Pest)
- ใช้ factories สำหรับสร้าง test data
- รัน `php artisan test --compact --filter=testName` สำหรับ test เดี่ยว
- รัน `php artisan test --compact` สำหรับทั้งหมด

### Code Quality
- รัน `vendor/bin/pint --dirty` ก่อน finalize
- ไม่ใช้ flag `--test` กับ pint
- ตรวจสอบ sibling files ก่อนสร้างใหม่

### Routes
- ใช้ named routes
- ใช้ `route()` helper
- ใช้ route model binding เมื่อเป็นไปได้

### Views
- Partials เก็บใน `*/partials/`
- ใช้ Blade components ที่มีอยู่ก่อนสร้างใหม่

---

## 12. Common Patterns

### Mass Assignment Protection
```php
// Model
class Product extends Model
{
    protected $fillable = ['name', 'sku', 'price', 'shop_id'];
    // หรือ
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
```

### Route Model Binding with Soft Deletes
```php
// Route
Route::get('products/{product}', [ProductController::class, 'show'])
    ->withTrashed(); // Allow access to soft-deleted models

// Controller
public function show(Product $product)
{
    // $product จะถูกโหลดแม้จะถูก soft delete
}
```

### Authorization in Controller
```php
public function update(ProductRequest $request, Product $product)
{
    $this->authorize('update', $product);
    // ...
}

// หรือใน FormRequest
public function authorize(): bool
{
    return $this->user()->can('update', $this->product);
}
```

---

## 13. Troubleshooting

### N+1 Query Problem
```php
// ใช้ Laravel Debugbar หรือ Clockwork ตรวจสอบ
// แก้ไขด้วย eager loading
Product::with(['category', 'shop'])->get();

// หรือใช้ strict mode ใน development
Model::preventLazyLoading(! app()->isProduction());
```

### Mass Assignment Error
```php
// เพิ่ม field ลงใน $fillable หรือใช้ $guarded = []
// หรือใช้ forceCreate/forceFill (ไม่แนะนำในทาง production)
```

### Authorization Errors
```php
// ตรวจสอบ Policy ถูก register ใน AuthServiceProvider
// ตรวจสอบ user มี permission ที่ถูกต้อง
// ใช้ $this->authorize() หรือ authorize() helper
```

---

**Note**: อ่าน AGENTS.md ใน root directory สำหรับข้อมูลเพิ่มเติมเกี่ยวกับ project conventions และ Laravel Boost tools.
