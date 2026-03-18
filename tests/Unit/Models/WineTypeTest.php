<?php

namespace Tests\Unit\Models;

use App\Models\Wine;
use App\Models\WineType;
use Tests\TestCase;

class WineTypeTest extends TestCase
{
    public function test_slug_generated_on_create(): void
    {
        $type = WineType::create(['name' => 'Espumante Branco', 'slug' => 'espumante-branco']);

        $this->assertEquals('espumante-branco', $type->slug);
    }

    public function test_slug_auto_generated_via_boot(): void
    {
        $type = WineType::create(['name' => 'Rosé']);

        $this->assertEquals('rose', $type->slug);
    }

    public function test_slug_updates_on_name_change(): void
    {
        $type = WineType::create(['name' => 'Tinto']);
        $type->update(['name' => 'Tinto Seco']);

        $this->assertEquals('tinto-seco', $type->fresh()->slug);
    }

    public function test_has_many_wines(): void
    {
        $type = WineType::create(['name' => 'Branco']);
        Wine::create(['name' => 'Sauvignon', 'wine_type_id' => $type->id, 'stock_quantity' => 1, 'stock_unit' => 'bottle']);
        Wine::create(['name' => 'Chardonnay', 'wine_type_id' => $type->id, 'stock_quantity' => 1, 'stock_unit' => 'bottle']);

        $this->assertCount(2, $type->wines);
    }
}
