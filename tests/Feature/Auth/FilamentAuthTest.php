<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Tests\TestCase;

class FilamentAuthTest extends TestCase
{
    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/admin/login');

        $response->assertStatus(200);
    }

    public function test_admin_redirects_to_login_when_unauthenticated(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_user_can_access_admin(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/admin');

        $response->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_wines(): void
    {
        $response = $this->get('/admin/wines');

        $response->assertRedirect('/admin/login');
    }

    public function test_authenticated_user_can_access_wines_list(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/admin/wines');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_access_foods_list(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/admin/food');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_access_producers_list(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/admin/producers');

        $response->assertStatus(200);
    }

    public function test_authenticated_user_can_access_countries_list(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/admin/countries');

        $response->assertStatus(200);
    }

    public function test_user_can_access_panel(): void
    {
        $user = User::factory()->create();

        $this->assertTrue($user->canAccessPanel(
            app(\Filament\Panel::class)
        ));
    }
}
