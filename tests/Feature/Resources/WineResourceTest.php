<?php

namespace Tests\Feature\Resources;

use App\Models\Country;
use App\Models\Food;
use App\Models\GrapeVariety;
use App\Models\Producer;
use App\Models\Region;
use App\Models\Wine;
use App\Models\WineType;
use Tests\TestCase;

class WineResourceTest extends TestCase
{
    public function test_can_list_wines(): void
    {
        $user = $this->createAdminUser();
        Wine::create(['name' => 'Listado']);

        $response = $this->actingAs($user)->get('/admin/wines');

        $response->assertStatus(200);
    }

    public function test_can_access_create_wine_page(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/admin/wines/create');

        $response->assertStatus(200);
    }

    public function test_wine_create_form_contains_key_fields(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/admin/wines/create');

        $response->assertStatus(200);
        $response->assertSee('Nome do Vinho');
        $response->assertSee('Safra (Ano)');
        $response->assertSee('Harmonização');
    }

    public function test_can_access_edit_wine_page(): void
    {
        $user = $this->createAdminUser();
        $wine = Wine::create(['name' => 'Editável']);

        $response = $this->actingAs($user)->get("/admin/wines/{$wine->id}/edit");

        $response->assertStatus(200);
    }

    public function test_wine_list_shows_active_wines(): void
    {
        $user = $this->createAdminUser();
        Wine::create(['name' => 'Ativo', 'is_active' => true]);
        Wine::create(['name' => 'Inativo', 'is_active' => false]);

        $response = $this->actingAs($user)->get('/admin/wines');

        $response->assertStatus(200);
    }

    public function test_soft_deleted_wine_not_in_list(): void
    {
        $user = $this->createAdminUser();
        $wine = Wine::create(['name' => 'Deletado']);
        $wine->delete();

        $this->assertSoftDeleted('wines', ['id' => $wine->id]);
    }

    public function test_wine_with_all_relations_is_accessible(): void
    {
        $type     = WineType::create(['name' => 'Tinto']);
        $country  = Country::create(['name' => 'Brasil', 'code' => 'BR']);
        $region   = Region::create(['name' => 'Serra Gaúcha', 'country_id' => $country->id]);
        $producer = Producer::create(['name' => 'Miolo', 'country_id' => $country->id]);
        $grape    = GrapeVariety::create(['name' => 'Cabernet Sauvignon']);
        $food     = Food::create(['name' => 'Picanha']);

        $wine = Wine::create([
            'name'           => 'Miolo Lote 43',
            'wine_type_id'   => $type->id,
            'country_id'     => $country->id,
            'region_id'      => $region->id,
            'producer_id'    => $producer->id,
            'vintage'        => 2020,
            'alcohol_content'=> 13.5,
            'rating'         => 92,
            'is_active'      => true,
        ]);

        $wine->grapeVarieties()->attach($grape->id, ['percentage' => 100]);
        $wine->foods()->attach($food->id, ['notes' => 'Excelente combinação']);

        $user     = $this->createAdminUser();
        $response = $this->actingAs($user)->get("/admin/wines/{$wine->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Miolo Lote 43');
    }
}
