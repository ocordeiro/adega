<?php

namespace Tests\Unit\Models;

use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\Wine;
use Tests\TestCase;

class FoodTest extends TestCase
{
    public function test_uses_foods_table(): void
    {
        $food = new Food();
        $this->assertEquals('foods', $food->getTable());
    }

    public function test_belongs_to_food_category(): void
    {
        $category = FoodCategory::create(['name' => 'Carnes Vermelhas']);
        $food     = Food::create(['name' => 'Picanha', 'food_category_id' => $category->id]);

        $this->assertEquals('Carnes Vermelhas', $food->foodCategory->name);
    }

    public function test_belongs_to_many_wines(): void
    {
        $food = Food::create(['name' => 'Queijo Brie']);
        $wine = Wine::create(['name' => 'Chardonnay']);

        $food->wines()->attach($wine->id, ['notes' => 'Ótima combinação']);

        $this->assertCount(1, $food->wines);
        $this->assertEquals('Ótima combinação', $food->wines->first()->pivot->notes);
    }

    public function test_nullable_category(): void
    {
        $food = Food::create(['name' => 'Sem categoria']);

        $this->assertNull($food->food_category_id);
        $this->assertNull($food->foodCategory);
    }

    public function test_nullable_description(): void
    {
        $food = Food::create(['name' => 'Simples']);

        $this->assertNull($food->description);
    }
}
