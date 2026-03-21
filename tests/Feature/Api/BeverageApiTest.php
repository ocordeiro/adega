<?php

namespace Tests\Feature\Api;

use App\Models\BeverageReport;
use App\Models\Country;
use App\Models\DrinkRecipe;
use App\Models\DrinkRecipeIngredient;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\GrapeVariety;
use App\Models\Occasion;
use App\Models\Producer;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use App\Models\Region;
use App\Models\Spirit;
use App\Models\SpiritType;
use App\Models\Wine;
use App\Models\WineType;
use Tests\TestCase;

class BeverageApiTest extends TestCase
{
    private string $token = 'test-api-token';

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.api.token' => $this->token]);
    }

    private function apiHeaders(): array
    {
        return ['Authorization' => 'Bearer '.$this->token];
    }

    // ── Autenticação ────────────────────────────────────────

    public function test_returns_401_without_token(): void
    {
        $response = $this->getJson('/api/v1/bebida/123');

        $response->assertStatus(401)
            ->assertJson(['message' => 'Unauthenticated.']);
    }

    public function test_returns_401_with_wrong_token(): void
    {
        $response = $this->getJson('/api/v1/bebida/123', [
            'Authorization' => 'Bearer wrong-token',
        ]);

        $response->assertStatus(401);
    }

    public function test_returns_404_for_nonexistent_barcode(): void
    {
        $response = $this->getJson('/api/v1/bebida/nonexistent', $this->apiHeaders());

        $response->assertStatus(404)
            ->assertJson(['message' => 'Bebida não encontrada.']);
    }

    // ── Vinho ───────────────────────────────────────────────

    public function test_returns_wine_by_barcode(): void
    {
        $wineType = WineType::create(['name' => 'Tinto', 'slug' => 'tinto']);
        $country = Country::create(['name' => 'Portugal', 'code' => 'PT']);
        $region = Region::create(['name' => 'Douro', 'country_id' => $country->id]);
        $producer = Producer::create(['name' => 'Quinta do Crasto', 'country_id' => $country->id]);

        $wine = Wine::create([
            'name' => 'Crasto Reserva',
            'vintage' => 2020,
            'barcode' => '5601012345678',
            'wine_type_id' => $wineType->id,
            'country_id' => $country->id,
            'region_id' => $region->id,
            'producer_id' => $producer->id,
            'description' => 'Um vinho encorpado.',
            'alcohol_content' => 14.50,
            'serving_temp_min' => 16,
            'serving_temp_max' => 18,
            'rating' => 4,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/bebida/5601012345678', $this->apiHeaders());

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'type' => 'wine',
                    'id' => $wine->id,
                    'name' => 'Crasto Reserva',
                    'vintage' => 2020,
                    'barcode' => '5601012345678',
                    'description' => 'Um vinho encorpado.',
                    'alcohol_content' => '14.50',
                    'rating' => 4,
                    'wine_type' => ['name' => 'Tinto', 'slug' => 'tinto'],
                    'country' => ['name' => 'Portugal', 'code' => 'PT'],
                    'region' => ['name' => 'Douro'],
                    'producer' => ['name' => 'Quinta do Crasto'],
                ],
            ]);
    }

    public function test_wine_includes_grape_varieties(): void
    {
        $wine = Wine::create(['name' => 'Blend', 'barcode' => 'GV001', 'is_active' => true]);
        $touriga = GrapeVariety::create(['name' => 'Touriga Nacional']);
        $tinta = GrapeVariety::create(['name' => 'Tinta Roriz']);

        $wine->grapeVarieties()->attach([
            $touriga->id => ['percentage' => 60],
            $tinta->id => ['percentage' => 40],
        ]);

        $response = $this->getJson('/api/v1/bebida/GV001', $this->apiHeaders());

        $response->assertOk();

        $grapes = $response->json('data.grape_varieties');
        $this->assertCount(2, $grapes);
        $this->assertEquals('Touriga Nacional', $grapes[0]['name']);
        $this->assertEquals(60, $grapes[0]['percentage']);
    }

    public function test_wine_includes_foods_with_pairing_notes(): void
    {
        $wine = Wine::create(['name' => 'Tinto', 'barcode' => 'FOOD01', 'is_active' => true]);
        $category = FoodCategory::create(['name' => 'Carnes']);
        $food = Food::create(['name' => 'Picanha', 'food_category_id' => $category->id]);

        $wine->foods()->attach($food->id, ['notes' => 'Combinação perfeita']);

        $response = $this->getJson('/api/v1/bebida/FOOD01', $this->apiHeaders());

        $response->assertOk();

        $foods = $response->json('data.foods');
        $this->assertCount(1, $foods);
        $this->assertEquals('Picanha', $foods[0]['name']);
        $this->assertEquals('Carnes', $foods[0]['category']);
        $this->assertEquals('Combinação perfeita', $foods[0]['notes']);
    }

    public function test_food_includes_active_occasions(): void
    {
        $wine = Wine::create(['name' => 'Espumante', 'barcode' => 'OCC01', 'is_active' => true]);
        $category = FoodCategory::create(['name' => 'Carnes']);
        $food = Food::create(['name' => 'Picanha', 'food_category_id' => $category->id]);

        $wine->foods()->attach($food->id, ['notes' => null]);

        $active = Occasion::create(['name' => 'Celebração', 'icon' => '🎉', 'is_active' => true, 'sort_order' => 1]);
        $inactive = Occasion::create(['name' => 'Inativa', 'icon' => '❌', 'is_active' => false, 'sort_order' => 2]);

        $food->occasions()->attach([$active->id, $inactive->id]);

        $response = $this->getJson('/api/v1/bebida/OCC01', $this->apiHeaders());

        $response->assertOk();

        $foods = $response->json('data.foods');
        $this->assertCount(1, $foods);

        $occasions = $foods[0]['occasions'];
        $this->assertCount(1, $occasions);
        $this->assertEquals('Celebração', $occasions[0]['name']);
    }

    public function test_wine_includes_active_recipes(): void
    {
        $wine = Wine::create(['name' => 'Merlot', 'barcode' => 'REC01', 'is_active' => true]);

        $active = Recipe::create(['name' => 'Risoto', 'instructions' => 'Passo a passo...', 'is_active' => true]);
        $inactive = Recipe::create(['name' => 'Inativa', 'instructions' => '...', 'is_active' => false]);

        $wine->recipes()->attach([
            $active->id => ['notes' => 'Ótima combinação'],
            $inactive->id => ['notes' => 'Não exibir'],
        ]);

        $response = $this->getJson('/api/v1/bebida/REC01', $this->apiHeaders());

        $response->assertOk();

        $recipes = $response->json('data.recipes');
        $this->assertCount(1, $recipes);
        $this->assertEquals('Risoto', $recipes[0]['name']);
        $this->assertEquals('Ótima combinação', $recipes[0]['notes']);
    }

    public function test_recipe_includes_ingredients(): void
    {
        $wine = Wine::create(['name' => 'Chardonnay', 'barcode' => 'ING01', 'is_active' => true]);

        $recipe = Recipe::create(['name' => 'Risoto de Cogumelos', 'instructions' => 'Passo a passo...', 'is_active' => true]);

        RecipeIngredient::create(['recipe_id' => $recipe->id, 'name' => 'Arroz arbóreo', 'quantity' => '200', 'unit' => 'g', 'sort_order' => 1]);
        RecipeIngredient::create(['recipe_id' => $recipe->id, 'name' => 'Cogumelos', 'quantity' => '100', 'unit' => 'g', 'sort_order' => 2]);

        $wine->recipes()->attach($recipe->id, ['notes' => null]);

        $response = $this->getJson('/api/v1/bebida/ING01', $this->apiHeaders());

        $response->assertOk();

        $ingredients = $response->json('data.recipes.0.ingredients');
        $this->assertCount(2, $ingredients);
        $this->assertEquals('Arroz arbóreo', $ingredients[0]['name']);
        $this->assertEquals('200', $ingredients[0]['quantity']);
        $this->assertEquals('g', $ingredients[0]['unit']);
    }

    public function test_inactive_wine_returns_404(): void
    {
        Wine::create(['name' => 'Inativo', 'barcode' => 'INACT01', 'is_active' => false]);

        $response = $this->getJson('/api/v1/bebida/INACT01', $this->apiHeaders());

        $response->assertStatus(404);
    }

    // ── Destilado ───────────────────────────────────────────

    public function test_returns_spirit_by_barcode(): void
    {
        $type = SpiritType::create(['name' => 'Gin', 'slug' => 'gin']);
        $country = Country::create(['name' => 'Inglaterra', 'code' => 'GB']);
        $producer = Producer::create(['name' => 'Tanqueray', 'country_id' => $country->id]);

        $spirit = Spirit::create([
            'name' => 'Tanqueray London Dry',
            'barcode' => '5000291020706',
            'spirit_type_id' => $type->id,
            'country_id' => $country->id,
            'producer_id' => $producer->id,
            'description' => 'Um gin clássico.',
            'alcohol_content' => 43.10,
            'is_active' => true,
        ]);

        $response = $this->getJson('/api/v1/bebida/5000291020706', $this->apiHeaders());

        $response->assertOk()
            ->assertJson([
                'data' => [
                    'type' => 'spirit',
                    'id' => $spirit->id,
                    'name' => 'Tanqueray London Dry',
                    'barcode' => '5000291020706',
                    'alcohol_content' => '43.10',
                    'spirit_type' => ['name' => 'Gin', 'slug' => 'gin'],
                    'country' => ['name' => 'Inglaterra', 'code' => 'GB'],
                    'producer' => ['name' => 'Tanqueray'],
                ],
            ]);
    }

    public function test_spirit_includes_drink_recipes(): void
    {
        $spirit = Spirit::create([
            'name' => 'Gin Test',
            'barcode' => 'DRINK01',
            'is_active' => true,
        ]);

        $recipe = DrinkRecipe::create([
            'name' => 'Gin Tônica',
            'description' => 'Clássico.',
            'instructions' => 'Misture tudo.',
            'prep_time' => 5,
            'difficulty' => 'fácil',
            'is_active' => true,
        ]);

        DrinkRecipeIngredient::create([
            'drink_recipe_id' => $recipe->id,
            'spirit_id' => $spirit->id,
            'name' => 'Gin',
            'quantity' => '50',
            'unit' => 'ml',
            'sort_order' => 1,
        ]);

        DrinkRecipeIngredient::create([
            'drink_recipe_id' => $recipe->id,
            'spirit_id' => null,
            'name' => 'Água tônica',
            'quantity' => '150',
            'unit' => 'ml',
            'sort_order' => 2,
        ]);

        $response = $this->getJson('/api/v1/bebida/DRINK01', $this->apiHeaders());

        $response->assertOk();

        $drinks = $response->json('data.drink_recipes');
        $this->assertCount(1, $drinks);
        $this->assertEquals('Gin Tônica', $drinks[0]['name']);
        $this->assertEquals('fácil', $drinks[0]['difficulty']);

        $ingredients = $drinks[0]['ingredients'];
        $this->assertCount(2, $ingredients);
        $this->assertEquals('Gin', $ingredients[0]['name']);
        $this->assertEquals('Gin Test', $ingredients[0]['spirit_name']);
        $this->assertEquals('Água tônica', $ingredients[1]['name']);
        $this->assertNull($ingredients[1]['spirit_name']);
    }

    public function test_drink_recipe_includes_active_occasions(): void
    {
        $spirit = Spirit::create(['name' => 'Rum', 'barcode' => 'OCC_DRINK01', 'is_active' => true]);

        $recipe = DrinkRecipe::create([
            'name' => 'Mojito',
            'instructions' => 'Amasse o limão...',
            'is_active' => true,
        ]);

        DrinkRecipeIngredient::create([
            'drink_recipe_id' => $recipe->id,
            'spirit_id' => $spirit->id,
            'name' => 'Rum',
            'quantity' => '50',
            'unit' => 'ml',
            'sort_order' => 1,
        ]);

        $active = Occasion::create(['name' => 'Praia e Campo', 'icon' => '🌊', 'is_active' => true, 'sort_order' => 1]);
        $inactive = Occasion::create(['name' => 'Inativa', 'icon' => '❌', 'is_active' => false, 'sort_order' => 2]);

        $recipe->occasions()->attach([$active->id, $inactive->id]);

        $response = $this->getJson('/api/v1/bebida/OCC_DRINK01', $this->apiHeaders());

        $response->assertOk();

        $drinks = $response->json('data.drink_recipes');
        $this->assertCount(1, $drinks);

        $occasions = $drinks[0]['occasions'];
        $this->assertCount(1, $occasions);
        $this->assertEquals('Praia e Campo', $occasions[0]['name']);
        $this->assertEquals('🌊', $occasions[0]['icon']);
    }

    public function test_spirit_excludes_inactive_drink_recipes(): void
    {
        $spirit = Spirit::create(['name' => 'Vodka', 'barcode' => 'DRINK02', 'is_active' => true]);

        $inactive = DrinkRecipe::create([
            'name' => 'Receita Inativa',
            'instructions' => '...',
            'is_active' => false,
        ]);

        DrinkRecipeIngredient::create([
            'drink_recipe_id' => $inactive->id,
            'spirit_id' => $spirit->id,
            'name' => 'Vodka',
            'quantity' => '50',
            'unit' => 'ml',
            'sort_order' => 1,
        ]);

        $response = $this->getJson('/api/v1/bebida/DRINK02', $this->apiHeaders());

        $response->assertOk();
        $this->assertCount(0, $response->json('data.drink_recipes'));
    }

    public function test_inactive_spirit_returns_404(): void
    {
        Spirit::create(['name' => 'Inativo', 'barcode' => 'INACT02', 'is_active' => false]);

        $response = $this->getJson('/api/v1/bebida/INACT02', $this->apiHeaders());

        $response->assertStatus(404);
    }

    // ── Prioridade: vinho antes de destilado ────────────────

    public function test_wine_takes_priority_over_spirit_with_same_barcode(): void
    {
        Wine::create(['name' => 'Vinho', 'barcode' => 'SAME01', 'is_active' => true]);
        Spirit::create(['name' => 'Destilado', 'barcode' => 'SAME01', 'is_active' => true]);

        $response = $this->getJson('/api/v1/bebida/SAME01', $this->apiHeaders());

        $response->assertOk()
            ->assertJson(['data' => ['type' => 'wine', 'name' => 'Vinho']]);
    }

    // ── Reportar bebida ─────────────────────────────────────

    public function test_report_creates_beverage_report_for_wine(): void
    {
        $response = $this->postJson('/api/v1/bebida/reportar', [
            'barcode' => '7891234567890',
            'type' => 'wine',
        ], $this->apiHeaders());

        $response->assertOk()
            ->assertJson(['message' => 'Reportado com sucesso.']);

        $this->assertDatabaseHas('beverage_reports', [
            'barcode' => '7891234567890',
            'type' => 'wine',
            'is_resolved' => false,
        ]);
    }

    public function test_report_creates_beverage_report_for_spirit(): void
    {
        $response = $this->postJson('/api/v1/bebida/reportar', [
            'barcode' => '7891234567891',
            'type' => 'spirit',
        ], $this->apiHeaders());

        $response->assertOk()
            ->assertJson(['message' => 'Reportado com sucesso.']);

        $this->assertDatabaseHas('beverage_reports', [
            'barcode' => '7891234567891',
            'type' => 'spirit',
        ]);
    }

    public function test_report_returns_401_without_token(): void
    {
        $response = $this->postJson('/api/v1/bebida/reportar', [
            'barcode' => '7891234567890',
            'type' => 'wine',
        ]);

        $response->assertStatus(401);
    }

    public function test_report_validates_required_barcode(): void
    {
        $response = $this->postJson('/api/v1/bebida/reportar', [
            'type' => 'wine',
        ], $this->apiHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors('barcode');
    }

    public function test_report_validates_required_type(): void
    {
        $response = $this->postJson('/api/v1/bebida/reportar', [
            'barcode' => '7891234567890',
        ], $this->apiHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors('type');
    }

    public function test_report_validates_type_must_be_wine_or_spirit(): void
    {
        $response = $this->postJson('/api/v1/bebida/reportar', [
            'barcode' => '7891234567890',
            'type' => 'beer',
        ], $this->apiHeaders());

        $response->assertStatus(422)
            ->assertJsonValidationErrors('type');
    }

    public function test_report_allows_duplicate_barcodes(): void
    {
        $this->postJson('/api/v1/bebida/reportar', [
            'barcode' => '7891234567890',
            'type' => 'wine',
        ], $this->apiHeaders());

        $response = $this->postJson('/api/v1/bebida/reportar', [
            'barcode' => '7891234567890',
            'type' => 'spirit',
        ], $this->apiHeaders());

        $response->assertOk();

        $this->assertCount(2, BeverageReport::where('barcode', '7891234567890')->get());
    }
}
