<?php

namespace Tests\Unit\Models;

use App\Models\Country;
use App\Models\Producer;
use App\Models\Region;
use App\Models\Wine;
use Tests\TestCase;

class CountryRegionTest extends TestCase
{
    public function test_country_has_many_regions(): void
    {
        $country = Country::create(['name' => 'França', 'code' => 'FR']);
        Region::create(['name' => 'Bordeaux', 'country_id' => $country->id]);
        Region::create(['name' => 'Borgonha', 'country_id' => $country->id]);

        $this->assertCount(2, $country->regions);
    }

    public function test_country_has_many_producers(): void
    {
        $country = Country::create(['name' => 'Itália', 'code' => 'IT']);
        Producer::create(['name' => 'Antinori', 'country_id' => $country->id]);

        $this->assertCount(1, $country->producers);
    }

    public function test_country_has_many_wines(): void
    {
        $country = Country::create(['name' => 'Argentina', 'code' => 'AR']);
        Wine::create(['name' => 'Malbec', 'country_id' => $country->id, 'stock_quantity' => 1, 'stock_unit' => 'bottle']);

        $this->assertCount(1, $country->wines);
    }

    public function test_region_belongs_to_country(): void
    {
        $country = Country::create(['name' => 'Espanha', 'code' => 'ES']);
        $region  = Region::create(['name' => 'Rioja', 'country_id' => $country->id]);

        $this->assertEquals('Espanha', $region->country->name);
    }

    public function test_region_cascade_deletes_when_country_deleted(): void
    {
        $country = Country::create(['name' => 'Teste', 'code' => 'TT']);
        $region  = Region::create(['name' => 'Região Teste', 'country_id' => $country->id]);

        $country->delete();

        $this->assertNull(Region::find($region->id));
    }
}
