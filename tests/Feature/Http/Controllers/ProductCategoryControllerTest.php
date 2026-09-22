<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ProductCategoryControllerTest extends TestCase
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

    public function test_index_displays_categories(): void
    {
        $categories = ProductCategory::factory()->count(3)->create();

        $response = $this->actingAs($this->cashier)->get(route('product-categories.index'));

        $response->assertStatus(200);
        $response->assertViewIs('product-categories.index');
        $response->assertViewHas('categories');

        foreach ($categories as $category) {
            $response->assertSee($category->name);
        }
    }

    public function test_create_displays_form_for_manager(): void
    {
        $response = $this->actingAs($this->manager)->get(route('product-categories.create'));

        $response->assertStatus(200);
        $response->assertViewIs('product-categories.create');
    }

    public function test_cashier_cannot_access_create_form(): void
    {
        $response = $this->actingAs($this->cashier)->get(route('product-categories.create'));

        $response->assertStatus(403);
    }

    public function test_store_creates_product_category(): void
    {
        $data = [
            'name' => 'Electronics',
            'description' => 'Electronic devices',
            'is_active' => true,
        ];

        $response = $this->actingAs($this->manager)->post(route('product-categories.store'), $data);

        $response->assertRedirect(route('product-categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('product_categories', [
            'name' => 'Electronics',
            'description' => 'Electronic devices',
            'is_active' => true,
        ]);
    }

    public function test_store_requires_name(): void
    {
        $response = $this->actingAs($this->manager)->post(route('product-categories.store'), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_cashier_cannot_store_product_category(): void
    {
        $response = $this->actingAs($this->cashier)->post(route('product-categories.store'), [
            'name' => 'Electronics',
            'is_active' => true,
        ]);

        $response->assertStatus(403);
    }

    public function test_edit_displays_form_with_category(): void
    {
        $category = ProductCategory::factory()->create();

        $response = $this->actingAs($this->manager)->get(route('product-categories.edit', $category));

        $response->assertStatus(200);
        $response->assertViewIs('product-categories.edit');
        $response->assertViewHas('productCategory', $category);
    }

    public function test_cashier_cannot_access_edit_form(): void
    {
        $category = ProductCategory::factory()->create();

        $response = $this->actingAs($this->cashier)->get(route('product-categories.edit', $category));

        $response->assertStatus(403);
    }

    public function test_update_modifies_product_category(): void
    {
        $category = ProductCategory::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'description' => 'Updated description',
            'is_active' => false,
        ];

        $response = $this->actingAs($this->manager)->put(route('product-categories.update', $category), $data);

        $response->assertRedirect(route('product-categories.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('product_categories', [
            'id' => $category->id,
            'name' => 'Updated Name',
            'description' => 'Updated description',
            'is_active' => false,
        ]);
    }

    public function test_update_requires_name(): void
    {
        $category = ProductCategory::factory()->create();

        $response = $this->actingAs($this->manager)->put(route('product-categories.update', $category), [
            'name' => '',
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_cashier_cannot_update_product_category(): void
    {
        $category = ProductCategory::factory()->create();

        $response = $this->actingAs($this->cashier)->put(route('product-categories.update', $category), [
            'name' => 'Updated Name',
            'is_active' => true,
        ]);

        $response->assertStatus(403);
    }

    public function test_destroy_deletes_product_category(): void
    {
        $category = ProductCategory::factory()->create();

        $response = $this->actingAs($this->admin)->delete(route('product-categories.destroy', $category));

        $response->assertRedirect(route('product-categories.index'));
        $response->assertSessionHas('success');

        $this->assertModelMissing($category);
    }

    public function test_destroy_prevents_deletion_when_category_has_products(): void
    {
        $category = ProductCategory::factory()->create();
        Product::factory()->create(['product_category_id' => $category->id]);

        $response = $this->from(route('product-categories.index'))
            ->actingAs($this->admin)
            ->delete(route('product-categories.destroy', $category));

        $response->assertRedirect(route('product-categories.index'));
        $response->assertSessionHas('error');

        $this->assertModelExists($category);
    }

    public function test_manager_cannot_delete_product_category(): void
    {
        $category = ProductCategory::factory()->create();

        $response = $this->actingAs($this->manager)->delete(route('product-categories.destroy', $category));

        $response->assertStatus(403);
        $this->assertModelExists($category);
    }
}
