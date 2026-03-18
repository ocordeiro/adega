<?php

namespace Tests\Feature\Resources;

use App\Models\Country;
use App\Models\Producer;
use Tests\TestCase;

class ProducerResourceTest extends TestCase
{
    public function test_can_list_producers(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/admin/producers');

        $response->assertStatus(200);
    }

    public function test_can_access_create_producer_page(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/admin/producers/create');

        $response->assertStatus(200);
    }

    public function test_can_access_edit_producer_page(): void
    {
        $user     = $this->createAdminUser();
        $country  = Country::create(['name' => 'Brasil', 'code' => 'BR']);
        $producer = Producer::create(['name' => 'Miolo', 'country_id' => $country->id]);

        $response = $this->actingAs($user)->get("/admin/producers/{$producer->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Miolo');
    }
}
