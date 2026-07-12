<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_redirects_guest_to_login(): void
    {
        $response = $this->get('/');

        $response->assertRedirectToRoute('login');
    }

    public function test_homepage_redirects_authenticated_user_to_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirectToRoute('dashboard');
    }
}
