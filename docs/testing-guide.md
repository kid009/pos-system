# Laravel Testing Guide - POS System

Complete testing guide for Laravel POS System following PHP 8.3 and Laravel 13 conventions.

---

## Table of Contents

1. [Overview](#1-overview)
2. [Project Structure](#2-project-structure)
3. [Unit Tests - Business Logic](#3-unit-tests---business-logic)
4. [Feature Tests - HTTP Layer](#4-feature-tests---http-layer)
5. [Test Utilities](#5-test-utilities)
6. [Real-World Examples](#6-real-world-examples)
7. [Best Practices](#7-best-practices)

---

## 1. Overview

### Testing Philosophy

```
Unit Tests → Business Logic (Actions, DTOs, Policies)
Feature Tests → HTTP Layer (Controllers, Routes, Validation)
```

### Key Principles

- **Fast**: Unit tests should run in milliseconds
- **Isolated**: Each test should be independent
- **Repeatable**: Same result every time
- **Self-documenting**: Test names describe behavior

### Running Tests

```bash
# Run all tests
php artisan test --compact

# Run specific test
php artisan test --compact --filter=test_can_create_product

# Run tests in a file
php artisan test --compact tests/Feature/ProductTest.php

# Run with coverage
php artisan test --coverage
```

---

## 2. Project Structure

```
tests/
├── Unit/
│   ├── Actions/
│   │   └── Products/
│   │       ├── CreateProductActionTest.php
│   │       ├── UpdateProductActionTest.php
│   │       ├── DeleteProductActionTest.php
│   │       ├── ListProductsActionTest.php
│   │       └── GetProductActionTest.php
│   ├── DTOs/
│   │   └── ProductDTOTest.php
│   └── Policies/
│       └── ProductPolicyTest.php
├── Feature/
│   ├── ProductTest.php
│   ├── ProductValidationTest.php
│   ├── ProductAuthorizationTest.php
│   └── Api/
│       └── ProductApiTest.php
├── TestCase.php
└── CreatesApplication.php
```

---

## 3. Unit Tests - Business Logic

### 3.1 Testing Actions

#### CreateProductActionTest

```php
<?php

namespace Tests\Unit\Actions\Products;

use App\Actions\Products\CreateProductAction;
use App\DTOs\ProductDTO;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateProductActionTest extends TestCase
{
    use RefreshDatabase;

    private CreateProductAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new CreateProductAction();
    }

    public function test_can_create_product(): void
    {
        $dto = new ProductDTO(
            name: 'Test Product',
            sku: 'TEST-001',
            price: 100.00,
            categoryIds: null
        );

        $product = $this->action->execute($dto, shopId: 1);

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Test Product', $product->name);
        $this->assertEquals('TEST-001', $product->sku);
        $this->assertEquals(100.00, $product->price);
        $this->assertDatabaseHas('products', ['sku' => 'TEST-001']);
    }

    public function test_can_create_product_with_categories(): void
    {
        $categories = Category::factory()->count(2)->create();
        
        $dto = new ProductDTO(
            name: 'Product With Categories',
            sku: 'TEST-002',
            price: 150.00,
            categoryIds: $categories->pluck('id')->toArray()
        );

        $product = $this->action->execute($dto, shopId: 1);

        $this->assertCount(2, $product->categories);
        $this->assertTrue($product->categories->pluck('id')->contains($categories[0]->id));
    }

    public function test_creates_activity_log_on_create(): void
    {
        $dto = new ProductDTO(
            name: 'Logged Product',
            sku: 'TEST-003',
            price: 99.99
        );

        $this->action->execute($dto, shopId: 1);

        $this->assertDatabaseHas('activity_log', [
            'description' => 'Created product: Logged Product'
        ]);
    }

    public function test_created_product_belongs_to_correct_shop(): void
    {
        $dto = new ProductDTO(
            name: 'Shop Product',
            sku: 'TEST-004',
            price: 50.00
        );

        $product = $this->action->execute($dto, shopId: 5);

        $this->assertEquals(5, $product->shop_id);
    }
}
```

#### UpdateProductActionTest

```php
<?php

namespace Tests\Unit\Actions\Products;

use App\Actions\Products\UpdateProductAction;
use App\DTOs\ProductDTO;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateProductActionTest extends TestCase
{
    use RefreshDatabase;

    private UpdateProductAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new UpdateProductAction();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    public function test_can_update_product(): void
    {
        $product = Product::factory()->create([
            'name' => 'Old Name',
            'price' => 50.00
        ]);
        
        $dto = new ProductDTO(
            name: 'New Name',
            sku: $product->sku,
            price: 100.00
        );

        $updatedProduct = $this->action->execute($product, $dto);

        $this->assertEquals('New Name', $updatedProduct->name);
        $this->assertEquals(100.00, $updatedProduct->price);
        $this->assertDatabaseHas('products', ['name' => 'New Name']);
    }

    public function test_can_sync_categories_on_update(): void
    {
        $product = Product::factory()->create();
        $oldCategories = Category::factory()->count(2)->create();
        $product->categories()->attach($oldCategories);
        
        $newCategories = Category::factory()->count(3)->create();
        
        $dto = new ProductDTO(
            name: $product->name,
            sku: $product->sku,
            price: $product->price,
            categoryIds: $newCategories->pluck('id')->toArray()
        );

        $this->action->execute($product, $dto);

        $this->assertCount(3, $product->fresh()->categories);
        $this->assertFalse(
            $product->fresh()->categories->pluck('id')->contains($oldCategories[0]->id)
        );
    }

    public function test_does_not_sync_categories_when_null(): void
    {
        $product = Product::factory()->create();
        $categories = Category::factory()->count(2)->create();
        $product->categories()->attach($categories);
        
        $dto = new ProductDTO(
            name: 'Updated Name',
            sku: $product->sku,
            price: $product->price,
            categoryIds: null
        );

        $this->action->execute($product, $dto);

        $this->assertCount(2, $product->fresh()->categories);
    }

    public function test_logs_activity_on_update(): void
    {
        $product = Product::factory()->create(['name' => 'Original']);
        
        $dto = new ProductDTO(
            name: 'Updated',
            sku: $product->sku,
            price: $product->price
        );

        $this->action->execute($product, $dto);

        $this->assertActivityLogged('Updated product');
    }

    public function test_update_preserves_created_at(): void
    {
        $product = Product::factory()->create();
        $originalCreatedAt = $product->created_at;
        
        sleep(1);
        
        $dto = new ProductDTO(
            name: 'Updated Name',
            sku: $product->sku,
            price: $product->price
        );

        $this->action->execute($product, $dto);

        $this->assertEquals($originalCreatedAt, $product->fresh()->created_at);
    }
}
```

#### ListProductsActionTest

```php
<?php

namespace Tests\Unit\Actions\Products;

use App\Actions\Products\ListProductsAction;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListProductsActionTest extends TestCase
{
    use RefreshDatabase;

    private ListProductsAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new ListProductsAction();
    }

    public function test_returns_paginated_products(): void
    {
        Product::factory()->count(20)->create();

        $result = $this->action->execute([]);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
        $this->assertCount(15, $result->items()); // Default per_page
    }

    public function test_can_filter_by_search(): void
    {
        Product::factory()->create(['name' => 'Apple iPhone']);
        Product::factory()->create(['name' => 'Samsung Galaxy']);
        Product::factory()->create(['sku' => 'APPLE-001']);

        $result = $this->action->execute(['search' => 'Apple']);

        $this->assertCount(2, $result->items());
    }

    public function test_can_filter_by_category(): void
    {
        $category = Category::factory()->create();
        $productWithCategory = Product::factory()->create();
        $productWithCategory->categories()->attach($category);
        Product::factory()->create(); // Product without category

        $result = $this->action->execute(['category_id' => $category->id]);

        $this->assertCount(1, $result->items());
        $this->assertEquals($productWithCategory->id, $result->items()[0]->id);
    }

    public function test_can_filter_by_price_range(): void
    {
        Product::factory()->create(['price' => 50]);
        Product::factory()->create(['price' => 100]);
        Product::factory()->create(['price' => 200]);

        $result = $this->action->execute(['min_price' => 75, 'max_price' => 150]);

        $this->assertCount(1, $result->items());
        $this->assertEquals(100, $result->items()[0]->price);
    }

    public function test_can_sort_products(): void
    {
        Product::factory()->create(['name' => 'C Product', 'price' => 30]);
        Product::factory()->create(['name' => 'A Product', 'price' => 10]);
        Product::factory()->create(['name' => 'B Product', 'price' => 20]);

        $result = $this->action->execute(['sort_by' => 'name', 'sort_order' => 'asc']);

        $names = collect($result->items())->pluck('name')->toArray();
        $this->assertEquals(['A Product', 'B Product', 'C Product'], $names);
    }

    public function test_eager_loads_relationships(): void
    {
        $product = Product::factory()->create();
        $product->categories()->attach(Category::factory()->create());

        $result = $this->action->execute([]);

        $this->assertTrue($result->items()[0]->relationLoaded('categories'));
        $this->assertTrue($result->items()[0]->relationLoaded('shop'));
    }

    public function test_can_change_per_page(): void
    {
        Product::factory()->count(25)->create();

        $result = $this->action->execute(['per_page' => 5]);

        $this->assertCount(5, $result->items());
        $this->assertEquals(5, $result->perPage());
    }

    public function test_empty_search_returns_all_products(): void
    {
        Product::factory()->count(5)->create();

        $result = $this->action->execute(['search' => '']);

        $this->assertCount(5, $result->items());
    }
}
```

#### DeleteProductActionTest

```php
<?php

namespace Tests\Unit\Actions\Products;

use App\Actions\Products\DeleteProductAction;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteProductActionTest extends TestCase
{
    use RefreshDatabase;

    private DeleteProductAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new DeleteProductAction();
    }

    public function test_can_soft_delete_product(): void
    {
        $product = Product::factory()->create();

        $this->action->execute($product, force: false);

        $this->assertSoftDeleted('products', ['id' => $product->id]);
        $this->assertDatabaseHas('products', ['id' => $product->id]);
    }

    public function test_can_force_delete_product(): void
    {
        $product = Product::factory()->create();

        $this->action->execute($product, force: true);

        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_throws_exception_when_product_has_transactions(): void
    {
        $product = Product::factory()->create();
        Transaction::factory()->create(['product_id' => $product->id]);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Cannot delete: Product has transactions');

        $this->action->execute($product);
    }

    public function test_logs_activity_on_soft_delete(): void
    {
        $product = Product::factory()->create(['name' => 'To Delete']);

        $this->action->execute($product, force: false);

        $this->assertActivityLogged('Deleted product: To Delete');
    }

    public function test_logs_activity_on_force_delete(): void
    {
        $product = Product::factory()->create(['name' => 'To Force Delete']);

        $this->action->execute($product, force: true);

        $this->assertActivityLogged('Deleted product: To Force Delete (permanent)');
    }
}
```

#### RestoreProductActionTest

```php
<?php

namespace Tests\Unit\Actions\Products;

use App\Actions\Products\RestoreProductAction;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RestoreProductActionTest extends TestCase
{
    use RefreshDatabase;

    private RestoreProductAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new RestoreProductAction();
    }

    public function test_can_restore_soft_deleted_product(): void
    {
        $product = Product::factory()->create();
        $product->delete();
        $this->assertSoftDeleted('products', ['id' => $product->id]);

        $restoredProduct = $this->action->execute($product->id);

        $this->assertFalse($restoredProduct->trashed());
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'deleted_at' => null
        ]);
    }

    public function test_throws_exception_for_non_deleted_product(): void
    {
        $product = Product::factory()->create();

        $this->expectException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

        $this->action->execute($product->id);
    }

    public function test_logs_activity_on_restore(): void
    {
        $product = Product::factory()->create(['name' => 'Restored Product']);
        $product->delete();

        $this->action->execute($product->id);

        $this->assertActivityLogged('Restored product: Restored Product');
    }
}
```

#### BulkDeleteActionTest

```php
<?php

namespace Tests\Unit\Actions\Products;

use App\Actions\Products\BulkDeleteAction;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulkDeleteActionTest extends TestCase
{
    use RefreshDatabase;

    private BulkDeleteAction $action;

    protected function setUp(): void
    {
        parent::setUp();
        $this->action = new BulkDeleteAction();
    }

    public function test_can_bulk_soft_delete_products(): void
    {
        $products = Product::factory()->count(5)->create();
        $ids = $products->pluck('id')->toArray();

        $count = $this->action->execute($ids, force: false);

        $this->assertEquals(5, $count);
        foreach ($ids as $id) {
            $this->assertSoftDeleted('products', ['id' => $id]);
        }
    }

    public function test_can_bulk_force_delete_products(): void
    {
        $products = Product::factory()->count(3)->create();
        $ids = $products->pluck('id')->toArray();

        $count = $this->action->execute($ids, force: true);

        $this->assertEquals(3, $count);
        foreach ($ids as $id) {
            $this->assertDatabaseMissing('products', ['id' => $id]);
        }
    }

    public function test_handles_empty_array(): void
    {
        $count = $this->action->execute([], force: false);

        $this->assertEquals(0, $count);
    }

    public function test_logs_activity_on_bulk_delete(): void
    {
        $products = Product::factory()->count(5)->create();
        $ids = $products->pluck('id')->toArray();

        $this->action->execute($ids, force: false);

        $this->assertActivityLogged('Bulk deleted 5 products');
    }
}
```

### 3.2 Testing DTOs

```php
<?php

namespace Tests\Unit\DTOs;

use App\DTOs\ProductDTO;
use App\Http\Requests\ProductRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductDTOTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_dto_from_array(): void
    {
        $dto = new ProductDTO(
            name: 'Test Product',
            sku: 'TEST-001',
            price: 100.00,
            categoryIds: [1, 2, 3]
        );

        $this->assertEquals('Test Product', $dto->name);
        $this->assertEquals('TEST-001', $dto->sku);
        $this->assertEquals(100.00, $dto->price);
        $this->assertEquals([1, 2, 3], $dto->categoryIds);
    }

    public function test_can_create_dto_without_categories(): void
    {
        $dto = new ProductDTO(
            name: 'Test Product',
            sku: 'TEST-001',
            price: 100.00
        );

        $this->assertNull($dto->categoryIds);
    }

    public function test_can_create_dto_from_request(): void
    {
        $request = new ProductRequest([
            'name' => 'Request Product',
            'sku' => 'REQ-001',
            'price' => 99.99,
            'category_ids' => [1, 2]
        ]);
        $request->setContainer($this->app);

        $dto = ProductDTO::fromRequest($request);

        $this->assertEquals('Request Product', $dto->name);
        $this->assertEquals([1, 2], $dto->categoryIds);
    }
}
```

### 3.3 Testing Policies

```php
<?php

namespace Tests\Unit\Policies;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Policies\ProductPolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPolicyTest extends TestCase
{
    use RefreshDatabase;

    private ProductPolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new ProductPolicy();
    }

    public function test_admin_can_view_any_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();

        $this->assertTrue($this->policy->view($admin, $product));
    }

    public function test_user_can_view_own_shop_product(): void
    {
        $shop = Shop::factory()->create();
        $user = User::factory()->create(['shop_id' => $shop->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id]);

        $this->assertTrue($this->policy->view($user, $product));
    }

    public function test_user_cannot_view_other_shop_product(): void
    {
        $shop1 = Shop::factory()->create();
        $shop2 = Shop::factory()->create();
        $user = User::factory()->create(['shop_id' => $shop1->id]);
        $product = Product::factory()->create(['shop_id' => $shop2->id]);

        $this->assertFalse($this->policy->view($user, $product));
    }

    public function test_admin_can_create_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($this->policy->create($admin));
    }

    public function test_user_with_permission_can_create_product(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $this->assertTrue($this->policy->create($user));
    }

    public function test_admin_can_update_any_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();

        $this->assertTrue($this->policy->update($admin, $product));
    }

    public function test_user_cannot_update_other_shop_product(): void
    {
        $shop1 = Shop::factory()->create();
        $shop2 = Shop::factory()->create();
        $user = User::factory()->create(['shop_id' => $shop1->id]);
        $product = Product::factory()->create(['shop_id' => $shop2->id]);

        $this->assertFalse($this->policy->update($user, $product));
    }

    public function test_cannot_delete_product_with_transactions(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        \App\Models\Transaction::factory()->create(['product_id' => $product->id]);

        $this->assertFalse($this->policy->delete($user, $product));
    }
}
```

---

## 4. Feature Tests - HTTP Layer

### 4.1 Main Feature Test

```php
<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    // ===== CREATE TESTS =====

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

    public function test_unauthenticated_user_cannot_create_product(): void
    {
        $response = $this->postJson(route('products.store'), [
            'name' => 'Test Product',
            'sku' => 'TEST-001',
            'price' => 100,
        ]);

        $response->assertUnauthorized();
    }

    public function test_cannot_create_product_with_duplicate_sku(): void
    {
        Product::factory()->create(['sku' => 'TEST-001']);

        $response = $this->actingAs($this->user)
            ->postJson(route('products.store'), [
                'name' => 'Another Product',
                'sku' => 'TEST-001',
                'price' => 100,
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['sku']);
    }

    public function test_validates_required_fields_on_create(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('products.store'), []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'sku', 'price']);
    }

    public function test_validates_price_must_be_positive(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('products.store'), [
                'name' => 'Test Product',
                'sku' => 'TEST-001',
                'price' => -10,
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['price']);
    }

    public function test_can_create_product_with_categories(): void
    {
        $categories = Category::factory()->count(2)->create();

        $response = $this->actingAs($this->user)
            ->postJson(route('products.store'), [
                'name' => 'Categorized Product',
                'sku' => 'TEST-002',
                'price' => 150,
                'category_ids' => $categories->pluck('id')->toArray(),
            ]);

        $response->assertCreated();
        
        $product = Product::where('sku', 'TEST-002')->first();
        $this->assertCount(2, $product->categories);
    }

    // ===== READ TESTS =====

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

    public function test_can_show_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)
            ->getJson(route('products.show', $product));

        $response->assertOk()
            ->assertJsonPath('data.id', $product->id)
            ->assertJsonPath('data.name', $product->name);
    }

    public function test_returns_404_for_nonexistent_product(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('products.show', ['product' => 99999]));

        $response->assertNotFound();
    }

    public function test_list_respects_per_page_parameter(): void
    {
        Product::factory()->count(25)->create();

        $response = $this->actingAs($this->user)
            ->getJson(route('products.index', ['per_page' => 10]));

        $response->assertOk()
            ->assertJsonPath('meta.per_page', 10)
            ->assertJsonCount(10, 'data');
    }

    public function test_list_products_includes_relationships(): void
    {
        $product = Product::factory()->create();
        $product->categories()->attach(Category::factory()->create());

        $response = $this->actingAs($this->user)
            ->getJson(route('products.show', $product));

        $response->assertOk()
            ->assertJsonPath('data.categories.0.id', $product->categories->first()->id);
    }

    // ===== UPDATE TESTS =====

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

    public function test_unauthorized_user_cannot_update_product(): void
    {
        $product = Product::factory()->create();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->putJson(route('products.update', $product), [
                'name' => 'Updated Name',
                'sku' => $product->sku,
                'price' => 200,
            ]);

        $response->assertForbidden();
    }

    public function test_can_update_product_with_categories(): void
    {
        $product = Product::factory()->create();
        $categories = Category::factory()->count(2)->create();

        $response = $this->actingAs($this->user)
            ->putJson(route('products.update', $product), [
                'name' => 'Updated Product',
                'sku' => $product->sku,
                'price' => $product->price,
                'category_ids' => $categories->pluck('id')->toArray(),
            ]);

        $response->assertOk();
        $product->refresh();
        $this->assertCount(2, $product->categories);
    }

    public function test_update_validates_unique_sku_except_self(): void
    {
        $product1 = Product::factory()->create(['sku' => 'SKU-001']);
        $product2 = Product::factory()->create(['sku' => 'SKU-002']);

        $response = $this->actingAs($this->user)
            ->putJson(route('products.update', $product1), [
                'name' => $product1->name,
                'sku' => 'SKU-002', // Duplicate
                'price' => $product1->price,
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['sku']);
    }

    // ===== DELETE TESTS =====

    public function test_can_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)
            ->deleteJson(route('products.destroy', $product));

        $response->assertRedirect();
        $this->assertSoftDeleted('products', ['id' => $product->id]);
    }

    public function test_can_force_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->user)
            ->deleteJson(route('products.destroy', $product) . '?force=1');

        $response->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    public function test_cannot_delete_product_with_transactions(): void
    {
        $product = Product::factory()->create();
        Transaction::factory()->create(['product_id' => $product->id]);

        $response = $this->actingAs($this->user)
            ->deleteJson(route('products.destroy', $product));

        $response->assertSessionHas('error', 'Cannot delete: Product has transactions');
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

    // ===== BULK OPERATIONS TESTS =====

    public function test_can_bulk_delete_products(): void
    {
        $products = Product::factory()->count(3)->create();
        $ids = $products->pluck('id')->toArray();

        $response = $this->actingAs($this->user)
            ->postJson(route('products.bulk-delete'), ['ids' => $ids]);

        $response->assertOk()
            ->assertJson(['count' => 3]);

        foreach ($ids as $id) {
            $this->assertSoftDeleted('products', ['id' => $id]);
        }
    }

    public function test_bulk_delete_validates_ids_array(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('products.bulk-delete'), ['ids' => 'not-an-array']);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    }

    public function test_bulk_delete_requires_at_least_one_id(): void
    {
        $response = $this->actingAs($this->user)
            ->postJson(route('products.bulk-delete'), ['ids' => []]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['ids']);
    }
}
```

### 4.2 Validation Test with Data Provider

```php
<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ProductValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public static function invalidProductDataProvider(): array
    {
        return [
            'empty name' => [
                ['name' => '', 'sku' => 'TEST-001', 'price' => 100],
                ['name']
            ],
            'name too long' => [
                ['name' => str_repeat('a', 256), 'sku' => 'TEST-001', 'price' => 100],
                ['name']
            ],
            'empty sku' => [
                ['name' => 'Test', 'sku' => '', 'price' => 100],
                ['sku']
            ],
            'negative price' => [
                ['name' => 'Test', 'sku' => 'TEST-001', 'price' => -1],
                ['price']
            ],
            'non-numeric price' => [
                ['name' => 'Test', 'sku' => 'TEST-001', 'price' => 'abc'],
                ['price']
            ],
            'price too high' => [
                ['name' => 'Test', 'sku' => 'TEST-001', 'price' => 999999999.99],
                ['price']
            ],
            'invalid category_ids type' => [
                ['name' => 'Test', 'sku' => 'TEST-001', 'price' => 100, 'category_ids' => 'not-array'],
                ['category_ids']
            ],
            'non-existent category' => [
                ['name' => 'Test', 'sku' => 'TEST-001', 'price' => 100, 'category_ids' => [9999]],
                ['category_ids.0']
            ],
            'duplicate sku on create' => [
                ['name' => 'Test', 'sku' => 'DUPLICATE', 'price' => 100],
                ['sku']
            ],
        ];
    }

    #[DataProvider('invalidProductDataProvider')]
    public function test_validation_rules(array $data, array $expectedErrors): void
    {
        // Create existing product for duplicate sku test
        if (isset($data['sku']) && $data['sku'] === 'DUPLICATE') {
            Product::factory()->create(['sku' => 'DUPLICATE']);
        }

        $response = $this->actingAs($this->user)
            ->postJson(route('products.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors($expectedErrors);
    }

    public function test_sku_unique_validation_ignores_self_on_update(): void
    {
        $product = Product::factory()->create(['sku' => 'KEEP-001']);

        $response = $this->actingAs($this->user)
            ->putJson(route('products.update', $product), [
                'name' => 'Updated Name',
                'sku' => 'KEEP-001',
                'price' => $product->price,
            ]);

        $response->assertOk();
    }

    public function test_category_ids_must_be_array_of_integers(): void
    {
        Category::factory()->create(); // ID 1

        $response = $this->actingAs($this->user)
            ->postJson(route('products.store'), [
                'name' => 'Test',
                'sku' => 'TEST-001',
                'price' => 100,
                'category_ids' => [1, 'invalid', 3.5],
            ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['category_ids.1', 'category_ids.2']);
    }
}
```

### 4.3 Authorization Tests

```php
<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_any_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();

        $this->actingAs($admin)
            ->getJson(route('products.show', $product))
            ->assertOk();
    }

    public function test_user_can_view_own_shop_product(): void
    {
        $shop = Shop::factory()->create();
        $user = User::factory()->create(['shop_id' => $shop->id]);
        $product = Product::factory()->create(['shop_id' => $shop->id]);

        $this->actingAs($user)
            ->getJson(route('products.show', $product))
            ->assertOk();
    }

    public function test_user_cannot_view_other_shop_product(): void
    {
        $shop1 = Shop::factory()->create();
        $shop2 = Shop::factory()->create();
        $user = User::factory()->create(['shop_id' => $shop1->id]);
        $product = Product::factory()->create(['shop_id' => $shop2->id]);

        $this->actingAs($user)
            ->getJson(route('products.show', $product))
            ->assertForbidden();
    }

    public function test_admin_can_update_any_product(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $product = Product::factory()->create();

        $this->actingAs($admin)
            ->putJson(route('products.update', $product), [
                'name' => 'Admin Updated',
                'sku' => $product->sku,
                'price' => $product->price,
            ])
            ->assertOk();
    }

    public function test_user_cannot_update_other_shop_product(): void
    {
        $shop1 = Shop::factory()->create();
        $shop2 = Shop::factory()->create();
        $user = User::factory()->create(['shop_id' => $shop1->id]);
        $product = Product::factory()->create(['shop_id' => $shop2->id]);

        $this->actingAs($user)
            ->putJson(route('products.update', $product), [
                'name' => 'User Updated',
                'sku' => $product->sku,
                'price' => $product->price,
            ])
            ->assertForbidden();
    }

    public function test_guest_cannot_create_product(): void
    {
        $this->postJson(route('products.store'), [
            'name' => 'Test',
            'sku' => 'TEST-001',
            'price' => 100,
        ])->assertUnauthorized();
    }

    public function test_guest_cannot_update_product(): void
    {
        $product = Product::factory()->create();

        $this->putJson(route('products.update', $product), [
            'name' => 'Updated',
            'sku' => $product->sku,
            'price' => $product->price,
        ])->assertUnauthorized();
    }

    public function test_guest_cannot_delete_product(): void
    {
        $product = Product::factory()->create();

        $this->deleteJson(route('products.destroy', $product))
            ->assertUnauthorized();
    }
}
```

---

## 5. Test Utilities

### 5.1 Base TestCase

```php
<?php

namespace Tests;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\DB;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Optional: Disable exception handling for debugging
        // $this->withoutExceptionHandling();
        
        // Optional: Seed test data
        // $this->seed(TestDataSeeder::class);
    }

    /**
     * Create an admin user for testing
     */
    protected function createAdminUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role' => 'admin',
        ], $attributes));
    }

    /**
     * Create a regular user for testing
     */
    protected function createUser(array $attributes = []): User
    {
        return User::factory()->create(array_merge([
            'role' => 'user',
        ], $attributes));
    }

    /**
     * Create a user with a specific shop
     */
    protected function createUserWithShop(array $userAttributes = [], array $shopAttributes = []): User
    {
        $shop = Shop::factory()->create($shopAttributes);
        
        return User::factory()->create(array_merge([
            'shop_id' => $shop->id,
        ], $userAttributes));
    }

    /**
     * Assert that an activity was logged
     */
    protected function assertActivityLogged(string $description, ?string $subjectType = null): void
    {
        $query = DB::table('activity_log')
            ->where('description', 'like', "%{$description}%");
        
        if ($subjectType) {
            $query->where('subject_type', $subjectType);
        }
        
        $this->assertTrue(
            $query->exists(),
            "Failed asserting that activity '{$description}' was logged"
        );
    }

    /**
     * Assert that an activity was not logged
     */
    protected function assertActivityNotLogged(string $description): void
    {
        $this->assertFalse(
            DB::table('activity_log')
                ->where('description', 'like', "%{$description}%")
                ->exists(),
            "Failed asserting that activity '{$description}' was not logged"
        );
    }

    /**
     * Assert JSON structure for paginated response
     */
    protected function assertPaginatedJsonStructure($response, string $resourceKey): void
    {
        $response->assertJsonStructure([
            'data' => [
                '*' => [$resourceKey]
            ],
            'links' => ['first', 'last', 'prev', 'next'],
            'meta' => ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'],
        ]);
    }

    /**
     * Get authenticated headers for API requests
     */
    protected function apiHeaders(User $user): array
    {
        return [
            'Authorization' => 'Bearer ' . $user->createToken('test')->plainTextToken,
            'Accept' => 'application/json',
        ];
    }
}
```

### 5.2 Factory Patterns

```php
<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->productName(),
            'sku' => $this->faker->unique()->regexify('[A-Z]{3}-[0-9]{4}'),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'shop_id' => Shop::factory(),
            'description' => $this->faker->optional()->paragraph(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    /**
     * Set product as inactive
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Set product as deleted (soft delete)
     */
    public function deleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'deleted_at' => now(),
        ]);
    }

    /**
     * Set a specific shop
     */
    public function forShop(Shop|int $shop): static
    {
        $shopId = $shop instanceof Shop ? $shop->id : $shop;
        
        return $this->state(fn (array $attributes) => [
            'shop_id' => $shopId,
        ]);
    }

    /**
     * Set a specific price range
     */
    public function priceRange(float $min, float $max): static
    {
        return $this->state(fn (array $attributes) => [
            'price' => $this->faker->randomFloat(2, $min, $max),
        ]);
    }

    /**
     * Attach categories after creation
     */
    public function withCategories(int $count = 1): static
    {
        return $this->afterCreating(function (Product $product) use ($count) {
            $product->categories()->attach(
                Category::factory()->count($count)->create()
            );
        });
    }

    /**
     * Attach specific categories
     */
    public function withCategoryIds(array $categoryIds): static
    {
        return $this->afterCreating(function (Product $product) use ($categoryIds) {
            $product->categories()->attach($categoryIds);
        });
    }

    /**
     * Set product with transactions
     */
    public function withTransactions(int $count = 1): static
    {
        return $this->afterCreating(function (Product $product) use ($count) {
            \App\Models\Transaction::factory()
                ->count($count)
                ->forProduct($product)
                ->create();
        });
    }

    /**
     * Popular product (high sales)
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Popular ' . $attributes['name'],
        ])->afterCreating(function (Product $product) {
            \App\Models\Transaction::factory()
                ->count(50)
                ->forProduct($product)
                ->create();
        });
    }
}
```

### 5.3 Test Data Seeder (Optional)

```php
<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create test shop
        $shop = Shop::factory()->create([
            'name' => 'Test Shop',
        ]);

        // Create test users
        $admin = User::factory()->create([
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'role' => 'admin',
            'shop_id' => $shop->id,
        ]);

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'user@test.com',
            'role' => 'user',
            'shop_id' => $shop->id,
        ]);

        // Create categories
        $categories = Category::factory()->count(5)->create([
            'shop_id' => $shop->id,
        ]);

        // Create products with categories
        Product::factory()->count(20)->create([
            'shop_id' => $shop->id,
        ])->each(function ($product) use ($categories) {
            $product->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')
            );
        });
    }
}
```

---

## 6. Real-World Examples

### 6.1 Complete Product Testing Suite

ตัวอย่างครบวงจรสำหรับ Product Module:

```php
// tests/Feature/ProductWorkflowTest.php
class ProductWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_complete_product_lifecycle(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        // 1. Create product
        $createResponse = $this->actingAs($user)
            ->postJson(route('products.store'), [
                'name' => 'New Product',
                'sku' => 'NEW-001',
                'price' => 199.99,
                'category_ids' => [$category->id],
            ]);
        
        $createResponse->assertCreated();
        $productId = $createResponse->json('data.id');

        // 2. Verify created
        $this->assertDatabaseHas('products', [
            'id' => $productId,
            'name' => 'New Product',
        ]);

        // 3. Update product
        $updateResponse = $this->actingAs($user)
            ->putJson(route('products.update', $productId), [
                'name' => 'Updated Product',
                'sku' => 'NEW-001',
                'price' => 249.99,
            ]);
        
        $updateResponse->assertOk();

        // 4. Verify update
        $this->assertDatabaseHas('products', [
            'id' => $productId,
            'name' => 'Updated Product',
            'price' => 249.99,
        ]);

        // 5. Soft delete
        $deleteResponse = $this->actingAs($user)
            ->deleteJson(route('products.destroy', $productId));
        
        $deleteResponse->assertRedirect();
        $this->assertSoftDeleted('products', ['id' => $productId]);

        // 6. Restore
        $restoreResponse = $this->actingAs($user)
            ->post(route('products.restore', $productId));
        
        $restoreResponse->assertRedirect();
        $this->assertDatabaseHas('products', [
            'id' => $productId,
            'deleted_at' => null,
        ]);

        // 7. Force delete
        $forceDeleteResponse = $this->actingAs($user)
            ->deleteJson(route('products.destroy', $productId) . '?force=1');
        
        $forceDeleteResponse->assertRedirect();
        $this->assertDatabaseMissing('products', ['id' => $productId]);
    }
}
```

### 6.2 Testing with File Uploads

```php
public function test_can_create_product_with_image(): void
{
    Storage::fake('public');
    
    $file = UploadedFile::fake()->image('product.jpg');
    
    $response = $this->actingAs($this->user)
        ->postJson(route('products.store'), [
            'name' => 'Product With Image',
            'sku' => 'IMG-001',
            'price' => 100,
            'image' => $file,
        ]);
    
    $response->assertCreated();
    
    Storage::disk('public')->assertExists('products/' . $file->hashName());
}
```

### 6.3 Testing Queue Jobs

```php
public function test_product_export_job_is_dispatched(): void
{
    Queue::fake();
    
    $this->actingAs($this->admin)
        ->postJson(route('products.export'));
    
    Queue::assertPushed(ExportProductsJob::class);
}

public function test_notification_sent_after_bulk_operation(): void
{
    Notification::fake();
    
    $products = Product::factory()->count(5)->create();
    
    $this->actingAs($this->admin)
        ->postJson(route('products.bulk-delete'), [
            'ids' => $products->pluck('id')->toArray(),
        ]);
    
    Notification::assertSentTo(
        $this->admin,
        BulkOperationCompleted::class
    );
}
```

---

## 7. Best Practices

### 7.1 Naming Conventions

```php
// ✅ Good - Clear and descriptive
public function test_can_create_product_with_categories(): void
public function test_unauthorized_user_cannot_delete_product(): void
public function test_validation_fails_with_negative_price(): void
public function test_soft_deleted_product_can_be_restored(): void

// ❌ Bad - Vague or unclear
public function test_product(): void
public function test_create(): void
public function test_it_works(): void
```

### 7.2 Test Structure (AAA Pattern)

```php
public function test_can_update_product(): void
{
    // Arrange - Set up test data
    $product = Product::factory()->create(['name' => 'Old Name']);
    $user = User::factory()->create();
    
    // Act - Execute the action
    $response = $this->actingAs($user)
        ->putJson(route('products.update', $product), [
            'name' => 'New Name',
            'sku' => $product->sku,
            'price' => $product->price,
        ]);
    
    // Assert - Check the results
    $response->assertOk();
    $this->assertDatabaseHas('products', ['name' => 'New Name']);
}
```

### 7.3 Test Isolation

```php
// ✅ Good - Each test is independent
public function test_create_product(): void
{
    // Fresh database for each test (use RefreshDatabase trait)
    $this->assertEquals(0, Product::count());
    
    // ... test logic
}

public function test_delete_product(): void
{
    // Fresh database for each test
    $this->assertEquals(0, Product::count());
    
    // ... test logic
}
```

### 7.4 Using Factories Effectively

```php
// ✅ Good - Use states for variations
$product = Product::factory()->inactive()->create();
$product = Product::factory()->withCategories(3)->create();
$product = Product::factory()->deleted()->create();

// ✅ Good - Override specific attributes
$product = Product::factory()->create([
    'price' => 999.99,
    'sku' => 'SPECIAL-001',
]);
```

### 7.5 Assertions

```php
// ✅ Good - Specific assertions
$this->assertDatabaseHas('products', ['sku' => 'TEST-001']);
$this->assertDatabaseMissing('products', ['sku' => 'DELETED']);
$this->assertSoftDeleted('products', ['id' => $product->id]);
$this->assertEquals(5, $products->count());
$this->assertTrue($product->categories->contains($category));

// ✅ Good - HTTP assertions
$response->assertOk();
$response->assertCreated();
$response->assertUnprocessable();
$response->assertJsonPath('data.name', 'Test Product');
$response->assertJsonValidationErrors(['sku']);
$response->assertRedirect(route('products.index'));
```

### 7.6 Performance Tips

```php
// ✅ Good - Use RefreshDatabase trait (resets database once per test class)
class ProductTest extends TestCase
{
    use RefreshDatabase;
    // ...
}

// ✅ Good - Use DatabaseTransactions for faster tests (if not using RefreshDatabase)
class ProductTest extends TestCase
{
    use DatabaseTransactions;
    // ...
}

// ✅ Good - Factory relationships are lazy - only create when needed
$product = Product::factory()->create(); // Fast - no categories
$product = Product::factory()->withCategories()->create(); // Slower - creates categories

// ✅ Good - Use partial mocks for external services
$this->mock(ExternalApiService::class, function ($mock) {
    $mock->shouldReceive('fetchData')->once()->andReturn(['data']);
});
```

### 7.7 Pre-Commit Checklist

ก่อน commit ให้รันคำสั่งเหล่านี้:

```bash
# 1. Run all tests
php artisan test --compact

# 2. Run specific test file
php artisan test --compact tests/Feature/ProductTest.php

# 3. Run with coverage (if configured)
php artisan test --coverage --min=80

# 4. Format code
vendor/bin/pint --dirty
```

---

## Quick Reference

### Common Assertions

| Assertion | Description |
|-----------|-------------|
| `assertDatabaseHas($table, $data)` | Check data exists in database |
| `assertDatabaseMissing($table, $data)` | Check data does not exist |
| `assertSoftDeleted($table, $data)` | Check record is soft deleted |
| `assertOk()` | HTTP 200 |
| `assertCreated()` | HTTP 201 |
| `assertUnprocessable()` | HTTP 422 |
| `assertNotFound()` | HTTP 404 |
| `assertForbidden()` | HTTP 403 |
| `assertUnauthorized()` | HTTP 401 |
| `assertJsonPath($path, $value)` | Check JSON value |
| `assertJsonValidationErrors($keys)` | Check validation errors |

### HTTP Methods

```php
->getJson($uri)
->postJson($uri, $data)
->putJson($uri, $data)
->patchJson($uri, $data)
->deleteJson($uri)
```

### Authentication

```php
$this->actingAs($user)
$this->actingAs($user, 'api')
$this->be($user)
```

---

**Note**: This guide follows Laravel 13, PHP 8.3, and PHPUnit conventions. Always run `vendor/bin/pint --dirty` before committing test files.
