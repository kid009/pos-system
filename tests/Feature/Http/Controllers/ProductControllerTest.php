<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected User $manager;

    protected User $admin;

    protected User $cashier;

    protected function setUp(): void
    {
        parent::setUp();

        $this->manager = User::factory()->create(['role' => UserRole::MANAGER]);
        $this->admin = User::factory()->create(['role' => UserRole::ADMIN]);
        $this->cashier = User::factory()->create(['role' => UserRole::CASHIER]);
    }

    public function test_index_displays_products(): void
    {
        $category = ProductCategory::factory()->create();
        $products = Product::factory()->count(3)->create(['product_category_id' => $category->id]);

        $response = $this->actingAs($this->cashier)->get(route('products.index'));

        $response->assertStatus(200);
        $response->assertViewIs('products.index');
        $response->assertViewHas('products');

        foreach ($products as $product) {
            $response->assertSee($product->name);
        }
    }

    public function test_create_displays_form_for_manager(): void
    {
        $response = $this->actingAs($this->manager)->get(route('products.create'));

        $response->assertStatus(200);
        $response->assertViewIs('products.create');
    }

    public function test_cashier_cannot_access_create_form(): void
    {
        $response = $this->actingAs($this->cashier)->get(route('products.create'));

        $response->assertStatus(403);
    }

    public function test_store_creates_product(): void
    {
        Storage::fake('public');

        $category = ProductCategory::factory()->create();
        $image = UploadedFile::fake()->image('cover.jpg', 800, 600)->size(1024);

        $data = [
            'product_category_id' => $category->id,
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 99.99,
            'link_affiliate' => 'https://example.com',
            'image' => $image,
            'is_active' => true,
        ];

        $response = $this->actingAs($this->manager)->post(route('products.store'), $data);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'product_category_id' => $category->id,
            'name' => 'Test Product',
            'description' => 'Test description',
            'price' => 99.99,
            'link_affiliate' => 'https://example.com',
            'is_active' => true,
        ]);

        $product = Product::where('name', 'Test Product')->first();
        $this->assertNotNull($product->image);
        Storage::disk('public')->assertExists($product->image);
    }

    public function test_store_requires_name_and_price(): void
    {
        $response = $this->actingAs($this->manager)->post(route('products.store'), [
            'product_category_id' => '',
            'name' => '',
            'price' => '',
        ]);

        $response->assertSessionHasErrors(['product_category_id', 'name', 'price']);
    }

    public function test_store_requires_cover_image(): void
    {
        $category = ProductCategory::factory()->create();

        $response = $this->actingAs($this->manager)->post(route('products.store'), [
            'product_category_id' => $category->id,
            'name' => 'Test Product',
            'price' => 99.99,
        ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_store_rejects_invalid_cover_image(): void
    {
        $category = ProductCategory::factory()->create();

        $response = $this->actingAs($this->manager)->post(route('products.store'), [
            'product_category_id' => $category->id,
            'name' => 'Test Product',
            'price' => 99.99,
            'image' => UploadedFile::fake()->create('document.pdf', 100),
        ]);

        $response->assertSessionHasErrors('image');
    }

    public function test_cashier_cannot_store_product(): void
    {
        $category = ProductCategory::factory()->create();

        $response = $this->actingAs($this->cashier)->post(route('products.store'), [
            'product_category_id' => $category->id,
            'name' => 'Test Product',
            'price' => 99.99,
        ]);

        $response->assertStatus(403);
    }

    public function test_edit_displays_form_with_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->manager)->get(route('products.edit', $product));

        $response->assertStatus(200);
        $response->assertViewIs('products.edit');
        $response->assertViewHas('product', $product);
    }

    public function test_cashier_cannot_access_edit_form(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->cashier)->get(route('products.edit', $product));

        $response->assertStatus(403);
    }

    public function test_update_modifies_product(): void
    {
        $product = Product::factory()->create();
        $newCategory = ProductCategory::factory()->create();

        $data = [
            'product_category_id' => $newCategory->id,
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 199.99,
            'link_affiliate' => 'https://updated.example.com',
            'is_active' => false,
        ];

        $response = $this->actingAs($this->manager)->put(route('products.update', $product), $data);

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'product_category_id' => $newCategory->id,
            'name' => 'Updated Product',
            'description' => 'Updated description',
            'price' => 199.99,
            'link_affiliate' => 'https://updated.example.com',
            'is_active' => false,
        ]);
    }

    public function test_update_requires_name_and_price(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->manager)->put(route('products.update', $product), [
            'product_category_id' => '',
            'name' => '',
            'price' => '',
        ]);

        $response->assertSessionHasErrors(['product_category_id', 'name', 'price']);
    }

    public function test_cashier_cannot_update_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->cashier)->put(route('products.update', $product), [
            'name' => 'Updated Product',
            'price' => 99.99,
        ]);

        $response->assertStatus(403);
    }

    public function test_destroy_deletes_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->manager)->delete(route('products.destroy', $product));

        $response->assertRedirect(route('products.index'));
        $response->assertSessionHas('success');

        $this->assertModelMissing($product);
    }

    public function test_cashier_cannot_delete_product(): void
    {
        $product = Product::factory()->create();

        $response = $this->actingAs($this->cashier)->delete(route('products.destroy', $product));

        $response->assertStatus(403);
        $this->assertModelExists($product);
    }
}
