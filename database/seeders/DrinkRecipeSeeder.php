<?php

namespace Database\Seeders;

use App\Models\DrinkRecipe;
use App\Models\DrinkRecipeIngredient;
use App\Models\Spirit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DrinkRecipeSeeder extends Seeder
{
    public function run(): void
    {
        $drinks = [

            // ── VODKA (Absolut) ──────────────────────────────────────────────
            [
                'name'         => 'Moscow Mule',
                'description'  => 'Coquetel refrescante de vodka com ginger beer e limão, servido na icônica caneca de cobre.',
                'difficulty'   => 'fácil',
                'prep_time'    => 5,
                'instructions' => "1. Encha a caneca de cobre (ou copo) com gelo.\n2. Despeje a vodka.\n3. Esprema o suco de meio limão.\n4. Complete com ginger beer.\n5. Mexa delicadamente e decore com uma fatia de limão e ramo de hortelã.",
                'ingredients'  => [
                    ['spirit' => 'Absolut Vodka', 'name' => 'Absolut Vodka', 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Ginger beer', 'quantity' => '120', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Suco de limão', 'quantity' => '15', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => null, 'unit' => 'a gosto'],
                    ['spirit' => null, 'name' => 'Hortelã', 'quantity' => '1', 'unit' => 'ramo'],
                ],
            ],
            [
                'name'         => 'Cosmopolitan',
                'description'  => 'Elegante coquetel rosé de vodka com triple sec, cranberry e limão siciliano.',
                'difficulty'   => 'médio',
                'prep_time'    => 5,
                'instructions' => "1. Na coqueteleira com gelo, adicione a vodka, triple sec, suco de cranberry e suco de limão siciliano.\n2. Agite vigorosamente por 15 segundos.\n3. Coe para uma taça martini gelada.\n4. Decore com uma casca de laranja ou limão siciliano.",
                'ingredients'  => [
                    ['spirit' => 'Absolut Vodka', 'name' => 'Absolut Vodka', 'quantity' => '45', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Triple sec', 'quantity' => '15', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Suco de cranberry', 'quantity' => '30', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Suco de limão siciliano', 'quantity' => '15', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => null, 'unit' => 'para coqueteleira'],
                ],
            ],
            [
                'name'         => 'Espresso Martini',
                'description'  => 'Sofisticado coquetel de vodka com café expresso e licor de café, perfeito após o jantar.',
                'difficulty'   => 'médio',
                'prep_time'    => 5,
                'instructions' => "1. Prepare um expresso e deixe esfriar levemente.\n2. Na coqueteleira com gelo, adicione a vodka, licor de café e o expresso.\n3. Agite com força por 20 segundos — isso cria a espuma característica.\n4. Coe para uma taça martini gelada.\n5. Decore com 3 grãos de café.",
                'ingredients'  => [
                    ['spirit' => 'Absolut Vodka', 'name' => 'Absolut Vodka', 'quantity' => '50', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Licor de café (Kahlúa)', 'quantity' => '30', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Café expresso', 'quantity' => '30', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Xarope de açúcar', 'quantity' => '10', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => null, 'unit' => 'para coqueteleira'],
                ],
            ],

            // ── WHISKEY (Jack Daniel's) ───────────────────────────────────────
            [
                'name'         => 'Jack & Coke',
                'description'  => 'O highball mais popular do mundo: Jack Daniel\'s e Coca-Cola, simples e irresistível.',
                'difficulty'   => 'fácil',
                'prep_time'    => 2,
                'instructions' => "1. Encha um copo long drink com gelo.\n2. Despeje o Jack Daniel's.\n3. Complete com Coca-Cola gelada, despejando pela borda do copo.\n4. Mexa suavemente uma única vez.\n5. Decore com uma fatia de limão, se desejar.",
                'ingredients'  => [
                    ['spirit' => "Jack Daniel's Old No. 7", 'name' => "Jack Daniel's Old No. 7", 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Coca-Cola', 'quantity' => '180', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => null, 'unit' => 'a gosto'],
                ],
            ],
            [
                'name'         => 'Whiskey Sour',
                'description'  => 'Clássico coquetel azedo e equilibrado: whiskey, suco de limão e açúcar, com espuma sedosa.',
                'difficulty'   => 'médio',
                'prep_time'    => 5,
                'instructions' => "1. Na coqueteleira sem gelo, adicione o whiskey, suco de limão, xarope de açúcar e a clara de ovo.\n2. Agite a seco por 10 segundos (dry shake) para emulsionar a clara.\n3. Adicione gelo e agite novamente vigorosamente.\n4. Coe para um copo rocks com gelo.\n5. Decore com algumas gotas de angostura e uma cereja.",
                'ingredients'  => [
                    ['spirit' => "Jack Daniel's Old No. 7", 'name' => "Jack Daniel's Old No. 7", 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Suco de limão', 'quantity' => '30', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Xarope de açúcar', 'quantity' => '20', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Clara de ovo', 'quantity' => '1', 'unit' => 'unidade'],
                    ['spirit' => null, 'name' => 'Angostura bitters', 'quantity' => '2', 'unit' => 'dashs'],
                ],
            ],
            [
                'name'         => 'Old Fashioned',
                'description'  => 'O coquetel mais antigo do mundo: whiskey, açúcar, angostura e casca de laranja.',
                'difficulty'   => 'médio',
                'prep_time'    => 5,
                'instructions' => "1. Em um copo rocks, coloque o cubo de açúcar e umedeça com as dashs de angostura e um pouco de água.\n2. Macere o açúcar até dissolver.\n3. Adicione o whiskey e mexa bem.\n4. Adicione um grande cubo de gelo.\n5. Expresse a casca de laranja sobre o coquetel, esfregue na borda do copo e use como decoração.",
                'ingredients'  => [
                    ['spirit' => "Jack Daniel's Old No. 7", 'name' => "Jack Daniel's Old No. 7", 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Angostura bitters', 'quantity' => '3', 'unit' => 'dashs'],
                    ['spirit' => null, 'name' => 'Cubo de açúcar', 'quantity' => '1', 'unit' => 'unidade'],
                    ['spirit' => null, 'name' => 'Água', 'quantity' => '1', 'unit' => 'colher de chá'],
                    ['spirit' => null, 'name' => 'Casca de laranja', 'quantity' => '1', 'unit' => 'tira'],
                ],
            ],

            // ── RUM (Bacardi) ─────────────────────────────────────────────────
            [
                'name'         => 'Mojito',
                'description'  => 'Refrescante coquetel cubano com rum, hortelã fresca e limão.',
                'difficulty'   => 'fácil',
                'prep_time'    => 5,
                'instructions' => "1. Coloque as folhas de hortelã e o açúcar no copo.\n2. Macere delicadamente para liberar os óleos da hortelã.\n3. Esprema o suco de meio limão.\n4. Adicione o rum e mexa.\n5. Encha com gelo e complete com água com gás.\n6. Decore com ramo de hortelã.",
                'ingredients'  => [
                    ['spirit' => 'Bacardi Carta Branca', 'name' => 'Bacardi Carta Branca', 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Hortelã fresca', 'quantity' => '8', 'unit' => 'folhas'],
                    ['spirit' => null, 'name' => 'Limão', 'quantity' => '1/2', 'unit' => 'unidade'],
                    ['spirit' => null, 'name' => 'Açúcar', 'quantity' => '2', 'unit' => 'colheres de chá'],
                    ['spirit' => null, 'name' => 'Água com gás', 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => null, 'unit' => 'a gosto'],
                ],
            ],
            [
                'name'         => 'Daiquiri',
                'description'  => 'Clássico coquetel cubano: rum, suco de limão e açúcar, simples e perfeito.',
                'difficulty'   => 'fácil',
                'prep_time'    => 5,
                'instructions' => "1. Na coqueteleira com gelo, adicione o rum, suco de limão e xarope de açúcar.\n2. Agite vigorosamente por 15 segundos.\n3. Coe para uma taça daiquiri ou copo martini gelado.\n4. Decore com uma fatia de limão na borda.",
                'ingredients'  => [
                    ['spirit' => 'Bacardi Carta Branca', 'name' => 'Bacardi Carta Branca', 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Suco de limão', 'quantity' => '30', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Xarope de açúcar', 'quantity' => '15', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => null, 'unit' => 'para coqueteleira'],
                ],
            ],
            [
                'name'         => 'Piña Colada',
                'description'  => 'Tropical e cremoso coquetel caribenho com rum, creme de coco e abacaxi.',
                'difficulty'   => 'fácil',
                'prep_time'    => 5,
                'instructions' => "1. No liquidificador, adicione o rum, suco de abacaxi, creme de coco e gelo.\n2. Bata até ficar cremoso e homogêneo.\n3. Despeje em um copo alto ou taça de piña colada.\n4. Decore com uma fatia de abacaxi e uma cereja.",
                'ingredients'  => [
                    ['spirit' => 'Bacardi Carta Branca', 'name' => 'Bacardi Carta Branca', 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Suco de abacaxi', 'quantity' => '90', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Creme de coco', 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => '1', 'unit' => 'xícara'],
                ],
            ],

            // ── GIN (Tanqueray) ───────────────────────────────────────────────
            [
                'name'         => 'Gin Tônica',
                'description'  => 'O clássico highball britânico: gin, água tônica e botânicos frescos.',
                'difficulty'   => 'fácil',
                'prep_time'    => 3,
                'instructions' => "1. Encha uma taça balão com bastante gelo.\n2. Despeje o gin.\n3. Complete com água tônica, despejando lentamente pela borda.\n4. Mexa com cuidado uma única vez.\n5. Decore com fatia de limão e ramo de alecrim.",
                'ingredients'  => [
                    ['spirit' => 'Tanqueray London Dry', 'name' => 'Tanqueray London Dry', 'quantity' => '50', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Água tônica', 'quantity' => '150', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Limão siciliano', 'quantity' => '1', 'unit' => 'fatia'],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => null, 'unit' => 'a gosto'],
                ],
            ],
            [
                'name'         => 'Negroni',
                'description'  => 'Aperitivo italiano elegante e amargo: gin, Campari e vermute tinto em partes iguais.',
                'difficulty'   => 'fácil',
                'prep_time'    => 3,
                'instructions' => "1. Em um copo rocks com gelo, adicione o gin, Campari e vermute tinto.\n2. Mexa delicadamente por 20 segundos para misturar e resfriar sem diluir demais.\n3. Adicione um grande cubo de gelo.\n4. Expresse uma casca de laranja sobre o coquetel e use como decoração.",
                'ingredients'  => [
                    ['spirit' => 'Tanqueray London Dry', 'name' => 'Tanqueray London Dry', 'quantity' => '30', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Campari', 'quantity' => '30', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Vermute tinto', 'quantity' => '30', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Casca de laranja', 'quantity' => '1', 'unit' => 'tira'],
                ],
            ],
            [
                'name'         => 'Tom Collins',
                'description'  => 'Refrescante coquetel britânico de verão: gin, limão, açúcar e água com gás.',
                'difficulty'   => 'fácil',
                'prep_time'    => 5,
                'instructions' => "1. Na coqueteleira com gelo, adicione o gin, suco de limão e xarope de açúcar.\n2. Agite bem e coe para um copo Collins cheio de gelo.\n3. Complete com água com gás.\n4. Mexa suavemente e decore com rodela de limão e cereja.",
                'ingredients'  => [
                    ['spirit' => 'Tanqueray London Dry', 'name' => 'Tanqueray London Dry', 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Suco de limão', 'quantity' => '30', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Xarope de açúcar', 'quantity' => '20', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Água com gás', 'quantity' => '90', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => null, 'unit' => 'a gosto'],
                ],
            ],

            // ── TEQUILA (José Cuervo) ─────────────────────────────────────────
            [
                'name'         => 'Margarita',
                'description'  => 'Coquetel mexicano clássico com tequila, triple sec e suco de limão, servido com borda de sal.',
                'difficulty'   => 'médio',
                'prep_time'    => 5,
                'instructions' => "1. Passe limão na borda do copo e mergulhe em sal grosso.\n2. Na coqueteleira, adicione gelo, tequila, triple sec e suco de limão.\n3. Agite vigorosamente por 15 segundos.\n4. Coe para o copo preparado com gelo.\n5. Decore com uma rodela de limão.",
                'ingredients'  => [
                    ['spirit' => 'José Cuervo Especial', 'name' => 'José Cuervo Especial', 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Triple sec', 'quantity' => '30', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Suco de limão', 'quantity' => '30', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Sal grosso', 'quantity' => null, 'unit' => 'para a borda'],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => null, 'unit' => 'a gosto'],
                ],
            ],
            [
                'name'         => 'Tequila Sunrise',
                'description'  => 'Coquetel vibrante com tequila, suco de laranja e granadine, que cria o efeito de um amanhecer.',
                'difficulty'   => 'fácil',
                'prep_time'    => 3,
                'instructions' => "1. Encha um copo alto com gelo.\n2. Despeje a tequila e o suco de laranja e mexa.\n3. Lentamente, adicione a granadine pela borda do copo — ela afundará e criará o efeito degradê.\n4. Não misture! Decore com uma rodela de laranja e uma cereja.",
                'ingredients'  => [
                    ['spirit' => 'José Cuervo Especial', 'name' => 'José Cuervo Especial', 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Suco de laranja', 'quantity' => '120', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Granadine', 'quantity' => '20', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => null, 'unit' => 'a gosto'],
                ],
            ],
            [
                'name'         => 'Paloma',
                'description'  => 'O coquetel mais popular do México: tequila, toronja (grapefruit) e toque de sal.',
                'difficulty'   => 'fácil',
                'prep_time'    => 3,
                'instructions' => "1. Passe limão na borda do copo e mergulhe em sal.\n2. Encha o copo com gelo.\n3. Adicione a tequila e o suco de toronja (ou grapefruit).\n4. Esprema um toque de limão.\n5. Complete com refrigerante de toronja ou água com gás.\n6. Mexa suavemente e decore com uma fatia de toronja.",
                'ingredients'  => [
                    ['spirit' => 'José Cuervo Especial', 'name' => 'José Cuervo Especial', 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Suco de toronja (grapefruit)', 'quantity' => '90', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Suco de limão', 'quantity' => '15', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Água com gás', 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Sal grosso', 'quantity' => null, 'unit' => 'para a borda'],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => null, 'unit' => 'a gosto'],
                ],
            ],

            // ── CACHAÇA (Ypióca) ──────────────────────────────────────────────
            [
                'name'         => 'Caipirinha',
                'description'  => 'O drink mais famoso do Brasil, feito com cachaça, limão e açúcar.',
                'difficulty'   => 'fácil',
                'prep_time'    => 5,
                'instructions' => "1. Corte meio limão em pedaços e coloque no copo.\n2. Adicione 2 colheres de açúcar.\n3. Macere bem o limão com o açúcar.\n4. Adicione gelo até encher o copo.\n5. Despeje a cachaça e mexa bem.\n6. Sirva imediatamente.",
                'ingredients'  => [
                    ['spirit' => 'Ypióca Prata', 'name' => 'Ypióca Prata', 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Limão', 'quantity' => '1/2', 'unit' => 'unidade'],
                    ['spirit' => null, 'name' => 'Açúcar', 'quantity' => '2', 'unit' => 'colheres de sopa'],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => null, 'unit' => 'a gosto'],
                ],
            ],
            [
                'name'         => 'Caipirinha de Morango',
                'description'  => 'Versão frutada da caipirinha clássica, com morangos frescos e cachaça.',
                'difficulty'   => 'fácil',
                'prep_time'    => 5,
                'instructions' => "1. No copo, coloque os morangos cortados ao meio e o açúcar.\n2. Macere bem os morangos com o açúcar.\n3. Adicione as rodelas de limão e macere levemente.\n4. Encha com gelo triturado.\n5. Despeje a cachaça e mexa bem.\n6. Decore com um morango na borda do copo.",
                'ingredients'  => [
                    ['spirit' => 'Ypióca Prata', 'name' => 'Ypióca Prata', 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Morangos frescos', 'quantity' => '5', 'unit' => 'unidades'],
                    ['spirit' => null, 'name' => 'Limão', 'quantity' => '1/4', 'unit' => 'unidade'],
                    ['spirit' => null, 'name' => 'Açúcar', 'quantity' => '2', 'unit' => 'colheres de sopa'],
                    ['spirit' => null, 'name' => 'Gelo triturado', 'quantity' => null, 'unit' => 'a gosto'],
                ],
            ],
            [
                'name'         => 'Batida de Maracujá',
                'description'  => 'Drink cremoso e tropical brasileiro com cachaça, maracujá e leite condensado.',
                'difficulty'   => 'fácil',
                'prep_time'    => 5,
                'instructions' => "1. No liquidificador, adicione a cachaça, suco de maracujá, leite condensado e gelo.\n2. Bata por 30 segundos até ficar homogêneo e espumoso.\n3. Prove e ajuste a doçura com mais leite condensado se necessário.\n4. Sirva em copo alto, decorado com sementes de maracujá.",
                'ingredients'  => [
                    ['spirit' => 'Ypióca Prata', 'name' => 'Ypióca Prata', 'quantity' => '60', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Suco de maracujá', 'quantity' => '100', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Leite condensado', 'quantity' => '40', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => '1', 'unit' => 'xícara'],
                ],
            ],

            // ── MULTIESPÍRITO: usa várias bebidas ────────────────────────────
            [
                'name'         => 'Long Island Iced Tea',
                'description'  => 'Potente coquetel americano que usa 4 destilados diferentes — parece um chá gelado, mas não é.',
                'difficulty'   => 'médio',
                'prep_time'    => 5,
                'instructions' => "1. Encha um copo Collins com gelo.\n2. Na coqueteleira com gelo, adicione a vodka, rum, gin, tequila, triple sec e suco de limão.\n3. Agite rapidamente — apenas para misturar.\n4. Coe sobre o gelo no copo.\n5. Complete com uma leve quantidade de cola.\n6. Mexa uma vez e decore com limão.",
                'ingredients'  => [
                    ['spirit' => 'Absolut Vodka', 'name' => 'Absolut Vodka', 'quantity' => '15', 'unit' => 'ml'],
                    ['spirit' => 'Bacardi Carta Branca', 'name' => 'Bacardi Carta Branca', 'quantity' => '15', 'unit' => 'ml'],
                    ['spirit' => 'Tanqueray London Dry', 'name' => 'Tanqueray London Dry', 'quantity' => '15', 'unit' => 'ml'],
                    ['spirit' => 'José Cuervo Especial', 'name' => 'José Cuervo Especial', 'quantity' => '15', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Triple sec', 'quantity' => '15', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Suco de limão', 'quantity' => '30', 'unit' => 'ml'],
                    ['spirit' => null, 'name' => 'Coca-Cola', 'quantity' => 'splash', 'unit' => null],
                    ['spirit' => null, 'name' => 'Gelo', 'quantity' => null, 'unit' => 'a gosto'],
                ],
            ],
        ];

        $created = 0;
        foreach ($drinks as $data) {
            if (DrinkRecipe::where('name', $data['name'])->exists()) {
                continue;
            }

            $recipe = DrinkRecipe::create([
                'name'         => $data['name'],
                'slug'         => Str::slug($data['name']),
                'description'  => $data['description'],
                'difficulty'   => $data['difficulty'],
                'prep_time'    => $data['prep_time'],
                'instructions' => $data['instructions'],
                'is_active'    => true,
            ]);

            foreach ($data['ingredients'] as $i => $ing) {
                $spiritId = null;
                if ($ing['spirit']) {
                    $spirit = Spirit::where('name', $ing['spirit'])->first();
                    $spiritId = $spirit?->id;
                }

                DrinkRecipeIngredient::create([
                    'drink_recipe_id' => $recipe->id,
                    'spirit_id'       => $spiritId,
                    'name'            => $ing['name'],
                    'quantity'        => $ing['quantity'],
                    'unit'            => $ing['unit'],
                    'sort_order'      => $i,
                ]);
            }

            $created++;
        }

        $this->command->info("DrinkRecipeSeeder: {$created} drinks criados.");
    }
}
