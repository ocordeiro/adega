<?php

namespace Tests\Feature\Resources;

use App\Models\Country;
use Tests\TestCase;

class CountryResourceTest extends TestCase
{
    public function test_can_list_countries(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/admin/countries');

        $response->assertStatus(200);
    }

    public function test_can_access_create_country_page(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/admin/countries/create');

        $response->assertStatus(200);
    }

    public function test_can_access_edit_country_page(): void
    {
        $user    = $this->createAdminUser();
        $country = Country::create(['name' => 'França', 'code' => 'FR']);

        $response = $this->actingAs($user)->get("/admin/countries/{$country->id}/edit");

        $response->assertStatus(200);
    }
}
