<?php

namespace Tests\Feature\Resources;

use App\Models\Food;
use App\Models\FoodCategory;
use Tests\TestCase;

class FoodResourceTest extends TestCase
{
    public function test_can_list_foods(): void
    {
        $user = $this->createAdminUser();
        Food::create(['name' => 'Picanha']);

        $response = $this->actingAs($user)->get('/admin/food');

        $response->assertStatus(200);
    }

    public function test_can_access_create_food_page(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/admin/food/create');

        $response->assertStatus(200);
    }

    public function test_food_create_form_contains_key_fields(): void
    {
        $user = $this->createAdminUser();

        $response = $this->actingAs($user)->get('/admin/food/create');

        $response->assertStatus(200);
        $response->assertSee('Nome');
        $response->assertSee('Categoria');
    }

    public function test_can_access_edit_food_page(): void
    {
        $user = $this->createAdminUser();
        $food = Food::create(['name' => 'Queijo']);

        $response = $this->actingAs($user)->get("/admin/food/{$food->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Queijo');
    }

    public function test_food_with_category_is_accessible(): void
    {
        $user     = $this->createAdminUser();
        $category = FoodCategory::create(['name' => 'Queijos']);
        $food     = Food::create(['name' => 'Brie', 'food_category_id' => $category->id]);

        $response = $this->actingAs($user)->get("/admin/food/{$food->id}/edit");

        $response->assertStatus(200);
        $response->assertSee('Brie');
    }
}
