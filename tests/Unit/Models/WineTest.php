<?php

namespace Tests\Unit\Models;

use App\Models\Country;
use App\Models\Food;
use App\Models\GrapeVariety;
use App\Models\Producer;
use App\Models\Region;
use App\Models\Wine;
use App\Models\WineType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WineTest extends TestCase
{
    use RefreshDatabase;

    public function test_slug_is_generated_on_create(): void
    {
        $wine = Wine::create([
            'name'           => 'Château Margaux',
            'vintage'        => 2015,
        ]);

        $this->assertEquals('chateau-margaux-2015', $wine->slug);
    }

    public function test_slug_is_generated_without_vintage(): void
    {
        $wine = Wine::create([
            'name'           => 'Gran Reserva',
        ]);

        $this->assertEquals('gran-reserva', $wine->slug);
    }

    public function test_duplicate_slug_gets_numeric_suffix(): void
    {
        Wine::create(['name' => 'Malbec', 'vintage' => 2020]);
        $wine2 = Wine::create(['name' => 'Malbec', 'vintage' => 2020]);

        $this->assertEquals('malbec-2020-1', $wine2->slug);
    }

    public function test_slug_updates_when_name_changes(): void
    {
        $wine = Wine::create(['name' => 'Old Name']);
        $wine->update(['name' => 'New Name']);

        $this->assertEquals('new-name', $wine->fresh()->slug);
    }

    public function test_soft_delete(): void
    {
        $wine = Wine::create(['name' => 'To Delete']);
        $id = $wine->id;

        $wine->delete();

        $this->assertNull(Wine::find($id));
        $this->assertNotNull(Wine::withTrashed()->find($id));
    }

    public function test_belongs_to_wine_type(): void
    {
        $type = WineType::create(['name' => 'Tinto', 'slug' => 'tinto']);
        $wine = Wine::create(['name' => 'Cabernet', 'wine_type_id' => $type->id]);

        $this->assertEquals('Tinto', $wine->wineType->name);
    }

    public function test_belongs_to_country(): void
    {
        $country = Country::create(['name' => 'Brasil', 'code' => 'BR']);
        $wine = Wine::create(['name' => 'Nacional', 'country_id' => $country->id]);

        $this->assertEquals('Brasil', $wine->country->name);
    }

    public function test_belongs_to_region(): void
    {
        $country = Country::create(['name' => 'Brasil', 'code' => 'BR']);
        $region  = Region::create(['name' => 'Serra Gaúcha', 'country_id' => $country->id]);
        $wine    = Wine::create(['name' => 'Serra', 'region_id' => $region->id]);

        $this->assertEquals('Serra Gaúcha', $wine->region->name);
    }

    public function test_belongs_to_producer(): void
    {
        $producer = Producer::create(['name' => 'Miolo']);
        $wine     = Wine::create(['name' => 'Miolo Seleção', 'producer_id' => $producer->id]);

        $this->assertEquals('Miolo', $wine->producer->name);
    }

    public function test_belongs_to_many_grape_varieties(): void
    {
        $wine  = Wine::create(['name' => 'Blend']);
        $cab   = GrapeVariety::create(['name' => 'Cabernet Sauvignon']);
        $merlot = GrapeVariety::create(['name' => 'Merlot']);

        $wine->grapeVarieties()->attach([$cab->id => ['percentage' => 60], $merlot->id => ['percentage' => 40]]);

        $this->assertCount(2, $wine->grapeVarieties);
        $this->assertEquals(60, $wine->grapeVarieties->find($cab->id)->pivot->percentage);
    }

    public function test_belongs_to_many_foods(): void
    {
        $wine  = Wine::create(['name' => 'Tinto Reserva']);
        $food  = Food::create(['name' => 'Picanha']);

        $wine->foods()->attach($food->id, ['notes' => 'Harmoniza muito bem']);

        $this->assertCount(1, $wine->foods);
        $this->assertEquals('Harmoniza muito bem', $wine->foods->first()->pivot->notes);
    }

    public function test_is_active_defaults_to_true(): void
    {
        $wine = Wine::create(['name' => 'Ativo']);

        $this->assertTrue($wine->is_active);
    }

    public function test_classification_can_be_set(): void
    {
        $wine = Wine::create(['name' => 'Seco Test', 'classification' => 'seco']);

        $this->assertEquals('seco', $wine->classification);
    }

    public function test_classification_is_nullable(): void
    {
        $wine = Wine::create(['name' => 'Sem Classificação']);

        $this->assertNull($wine->classification);
    }

    public function test_classification_constants_are_defined(): void
    {
        $this->assertArrayHasKey('seco', Wine::CLASSIFICATIONS);
        $this->assertArrayHasKey('demi_sec', Wine::CLASSIFICATIONS);
        $this->assertArrayHasKey('suave', Wine::CLASSIFICATIONS);
        $this->assertArrayHasKey('doce', Wine::CLASSIFICATIONS);
    }
}
