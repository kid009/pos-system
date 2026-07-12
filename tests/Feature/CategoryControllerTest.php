<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_index_page_displays_categories(): void
    {
        $category = Category::factory()->create(['name' => 'Gas Stoves']);

        $response = $this->actingAs($this->user)->get(route('category.index'));

        $response->assertOk();
        $response->assertSee('Gas Stoves');
    }

    public function test_create_page_is_displayed(): void
    {
        $response = $this->actingAs($this->user)->get(route('category.create'));

        $response->assertOk();
    }

    public function test_category_can_be_stored(): void
    {
        $response = $this->actingAs($this->user)->post(route('category.store'), [
            'name' => 'Gas Stoves',
            'description' => 'All gas stoves',
            'sort_order' => 1,
            'is_active' => true,
            'parent_id' => null,
        ]);

        $response->assertRedirect(route('category.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'name' => 'Gas Stoves',
            'slug' => 'gas-stoves',
            'description' => 'All gas stoves',
            'sort_order' => 1,
            'is_active' => true,
        ]);
    }

    public function test_category_can_be_updated(): void
    {
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($this->user)->put(route('category.update', $category), [
            'name' => 'Updated Name',
            'description' => 'Updated description',
            'sort_order' => 5,
            'is_active' => false,
            'parent_id' => null,
        ]);

        $response->assertRedirect(route('category.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'name' => 'Updated Name',
            'slug' => 'updated-name',
            'description' => 'Updated description',
            'sort_order' => 5,
            'is_active' => false,
        ]);
    }

    public function test_category_cannot_set_itself_as_parent(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)
            ->from(route('category.edit', $category))
            ->put(route('category.update', $category), [
                'name' => $category->name,
                'parent_id' => $category->id,
            ]);

        $response->assertSessionHasErrors('parent_id');
    }

    public function test_category_can_be_deleted(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('category.destroy', $category));

        $response->assertRedirect(route('category.index'));
        $response->assertSessionHas('delete');

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }
}
