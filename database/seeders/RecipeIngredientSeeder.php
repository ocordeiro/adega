<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\RecipeIngredient;
use Illuminate\Database\Seeder;

class RecipeIngredientSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'Picanha na Brasa com Chimichurri' => [
                ['name' => 'Picanha',             'quantity' => '1',   'unit' => 'kg'],
                ['name' => 'Sal grosso',           'quantity' => '3',   'unit' => 'colheres'],
                ['name' => 'Alho',                 'quantity' => '4',   'unit' => 'dentes'],
                ['name' => 'Salsinha fresca',      'quantity' => '1',   'unit' => 'maço'],
                ['name' => 'Orégano seco',         'quantity' => '1',   'unit' => 'colher'],
                ['name' => 'Azeite de oliva',      'quantity' => '4',   'unit' => 'colheres'],
                ['name' => 'Vinagre de vinho tinto','quantity' => '2',  'unit' => 'colheres'],
                ['name' => 'Pimenta-do-reino',     'quantity' => null,  'unit' => 'a gosto'],
            ],
            'Risoto de Funghi com Parmesão' => [
                ['name' => 'Arroz arbóreo',        'quantity' => '300', 'unit' => 'g'],
                ['name' => 'Funghi secchi',         'quantity' => '30',  'unit' => 'g'],
                ['name' => 'Caldo de legumes',      'quantity' => '1',   'unit' => 'litro'],
                ['name' => 'Cebola',                'quantity' => '1',   'unit' => 'unidade'],
                ['name' => 'Alho',                  'quantity' => '2',   'unit' => 'dentes'],
                ['name' => 'Vinho branco seco',     'quantity' => '100', 'unit' => 'ml'],
                ['name' => 'Manteiga',              'quantity' => '50',  'unit' => 'g'],
                ['name' => 'Queijo parmesão',       'quantity' => '80',  'unit' => 'g'],
                ['name' => 'Azeite de oliva',       'quantity' => '2',   'unit' => 'colheres'],
            ],
            'Bacalhau à Brás' => [
                ['name' => 'Bacalhau dessalgado',   'quantity' => '500', 'unit' => 'g'],
                ['name' => 'Batata palha',          'quantity' => '200', 'unit' => 'g'],
                ['name' => 'Ovos',                  'quantity' => '6',   'unit' => 'unidades'],
                ['name' => 'Cebola',                'quantity' => '2',   'unit' => 'unidades'],
                ['name' => 'Alho',                  'quantity' => '3',   'unit' => 'dentes'],
                ['name' => 'Azeite de oliva',       'quantity' => '4',   'unit' => 'colheres'],
                ['name' => 'Azeitonas pretas',      'quantity' => '50',  'unit' => 'g'],
                ['name' => 'Salsinha fresca',       'quantity' => null,  'unit' => 'a gosto'],
            ],
            'Espaguete ao Ragù Bolognese' => [
                ['name' => 'Espaguete',             'quantity' => '400', 'unit' => 'g'],
                ['name' => 'Carne moída bovina',    'quantity' => '500', 'unit' => 'g'],
                ['name' => 'Tomate pelado',         'quantity' => '400', 'unit' => 'g'],
                ['name' => 'Cebola',                'quantity' => '1',   'unit' => 'unidade'],
                ['name' => 'Cenoura',               'quantity' => '1',   'unit' => 'unidade'],
                ['name' => 'Salsão',                'quantity' => '1',   'unit' => 'talo'],
                ['name' => 'Vinho tinto seco',      'quantity' => '150', 'unit' => 'ml'],
                ['name' => 'Extrato de tomate',     'quantity' => '2',   'unit' => 'colheres'],
                ['name' => 'Azeite de oliva',       'quantity' => '3',   'unit' => 'colheres'],
            ],
            'Ostra com Mignonette de Champanhe' => [
                ['name' => 'Ostras frescas',        'quantity' => '12',  'unit' => 'unidades'],
                ['name' => 'Champanhe ou espumante','quantity' => '60',  'unit' => 'ml'],
                ['name' => 'Echalota',              'quantity' => '1',   'unit' => 'unidade'],
                ['name' => 'Vinagre de vinho branco','quantity' => '30', 'unit' => 'ml'],
                ['name' => 'Pimenta-do-reino',      'quantity' => null,  'unit' => 'moída na hora'],
                ['name' => 'Gelo',                  'quantity' => null,  'unit' => 'para servir'],
            ],
            'Cordeiro Assado com Ervas e Alho' => [
                ['name' => 'Pernil de cordeiro',    'quantity' => '1,5', 'unit' => 'kg'],
                ['name' => 'Alho',                  'quantity' => '6',   'unit' => 'dentes'],
                ['name' => 'Alecrim fresco',        'quantity' => '4',   'unit' => 'ramos'],
                ['name' => 'Tomilho fresco',        'quantity' => '4',   'unit' => 'ramos'],
                ['name' => 'Azeite de oliva',       'quantity' => '4',   'unit' => 'colheres'],
                ['name' => 'Mostarda Dijon',        'quantity' => '2',   'unit' => 'colheres'],
                ['name' => 'Sal',                   'quantity' => null,  'unit' => 'a gosto'],
                ['name' => 'Pimenta-do-reino',      'quantity' => null,  'unit' => 'a gosto'],
            ],
            'Ceviche de Peixe Branco' => [
                ['name' => 'Peixe branco (tilápia ou linguado)', 'quantity' => '500', 'unit' => 'g'],
                ['name' => 'Suco de limão',         'quantity' => '150', 'unit' => 'ml'],
                ['name' => 'Cebola roxa',           'quantity' => '1',   'unit' => 'unidade'],
                ['name' => 'Pimenta dedo-de-moça',  'quantity' => '1',   'unit' => 'unidade'],
                ['name' => 'Coentro fresco',        'quantity' => null,  'unit' => 'a gosto'],
                ['name' => 'Sal',                   'quantity' => null,  'unit' => 'a gosto'],
                ['name' => 'Milho cozido',          'quantity' => '100', 'unit' => 'g'],
            ],
            'Filé Mignon ao Molho Madeira' => [
                ['name' => 'Filé mignon',           'quantity' => '600', 'unit' => 'g'],
                ['name' => 'Vinho Madeira',         'quantity' => '150', 'unit' => 'ml'],
                ['name' => 'Caldo de carne',        'quantity' => '200', 'unit' => 'ml'],
                ['name' => 'Manteiga',              'quantity' => '40',  'unit' => 'g'],
                ['name' => 'Farinha de trigo',      'quantity' => '1',   'unit' => 'colher'],
                ['name' => 'Cebola',                'quantity' => '1',   'unit' => 'unidade'],
                ['name' => 'Champignon fatiado',    'quantity' => '100', 'unit' => 'g'],
                ['name' => 'Sal e pimenta',         'quantity' => null,  'unit' => 'a gosto'],
            ],
        ];

        foreach ($data as $recipeName => $ingredients) {
            $recipe = Recipe::where('name', $recipeName)->first();
            if (! $recipe) continue;

            foreach ($ingredients as $order => $ing) {
                RecipeIngredient::firstOrCreate(
                    ['recipe_id' => $recipe->id, 'name' => $ing['name']],
                    ['quantity' => $ing['quantity'], 'unit' => $ing['unit'], 'sort_order' => $order + 1]
                );
            }
        }

        $this->command->info('RecipeIngredientSeeder: ingredientes cadastrados para ' . count($data) . ' receitas.');
    }
}
