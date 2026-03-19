<?php

namespace Database\Seeders;

use App\Models\Recipe;
use App\Models\Wine;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RecipeSeeder extends Seeder
{
    public function run(): void
    {
        $recipes = [
            [
                'name'        => 'Picanha na Brasa com Chimichurri',
                'description' => 'Corte nobre grelhado na brasa, regado com molho verde argentino.',
                'difficulty'  => 'fácil',
                'prep_time'   => 45,
                'instructions' => "1. Tempere a picanha com sal grosso 30 minutos antes de grelhar.\n2. Leve à brasa em fogo alto por 4 min de cada lado para mal passada.\n3. Para o chimichurri: misture salsinha, orégano, alho, vinagre de vinho, azeite, sal e pimenta.\n4. Fatie a picanha na diagonal e sirva com o molho por cima.",
                'wines'       => ['Miolo Lote 43', 'Catena Zapata Adrianna', 'Concha y Toro Don Melchor'],
                'wine_notes'  => 'Os taninos do Cabernet e Malbec complementam a gordura marmoreada da picanha.',
            ],
            [
                'name'        => 'Risoto de Funghi com Parmesão',
                'description' => 'Cremoso risoto de cogumelos secos italianos com parmesão ralado na hora.',
                'difficulty'  => 'médio',
                'prep_time'   => 40,
                'instructions' => "1. Reidrate 30g de funghi porcini em 200ml de água quente por 20 min.\n2. Refogue cebola e alho em manteiga. Adicione o arroz arbóreo e toste por 2 min.\n3. Acrescente vinho branco seco e mexa até absorver.\n4. Adicione o caldo quente aos poucos (concha por concha) mexendo sempre.\n5. Incorpore o funghi escorrido e um fio do líquido de reidratação coado.\n6. Finalize com manteiga gelada e parmesão. Ajuste sal e pimenta.",
                'wines'       => ['Miolo Lote 43', 'Château Margaux Grand Vin'],
                'wine_notes'  => 'A terrosa suavidade do funghi ecoa as notas de terra e especiarias dos tintos elegantes.',
            ],
            [
                'name'        => 'Bacalhau à Brás',
                'description' => 'Clássico português com bacalhau desfiado, batatas palha, ovos e azeitonas.',
                'difficulty'  => 'médio',
                'prep_time'   => 60,
                'instructions' => "1. Dessalgue o bacalhau de véspera trocando a água 3x.\n2. Cozinhe o bacalhau, desfie e retire as espinhas.\n3. Doure cebola fatiada em rodelas no azeite até caramelizar.\n4. Adicione o bacalhau desfiado e misture bem.\n5. Acrescente as batatas palha e mexa rapidamente.\n6. Junte os ovos batidos e mexa em fogo baixo até cremoso.\n7. Finalize com salsa picada e azeitonas pretas.",
                'wines'       => ['Esporão Reserva Branco'],
                'wine_notes'  => 'A estrutura e acidez do branco alentejano equilibra a riqueza do bacalhau.',
            ],
            [
                'name'        => 'Espaguete ao Ragù Bolognese',
                'description' => 'Molho italiano de carne moída cozido lentamente com tomates e vinho tinto.',
                'difficulty'  => 'médio',
                'prep_time'   => 120,
                'instructions' => "1. Refogue cebola, cenoura e salsão picados em azeite e manteiga (soffritto).\n2. Adicione carne moída (misto de boi e porco) e doure bem.\n3. Acrescente vinho tinto e deixe evaporar o álcool.\n4. Junte tomates pelados amassados, sal, pimenta e noz-moscada.\n5. Cozinhe em fogo baixo por pelo menos 1h30 mexendo ocasionalmente.\n6. Cozinhe o espaguete al dente e sirva com o ragù e bastante parmesão.",
                'wines'       => ['Miolo Lote 43', 'Catena Zapata Adrianna'],
                'wine_notes'  => 'A acidez do tomate pede um tinto com boa estrutura de taninos para harmonizar.',
            ],
            [
                'name'        => 'Ostra com Mignonette de Champanhe',
                'description' => 'Ostras frescas com molho clássico francês de echalota e vinagre.',
                'difficulty'  => 'fácil',
                'prep_time'   => 15,
                'instructions' => "1. Para a mignonette: pique 2 echalotes finamente. Misture com 60ml de vinagre de vinho branco, pimenta-do-reino moída na hora e uma pitada de sal.\n2. Abra as ostras com faca própria com cuidado.\n3. Disponha as ostras sobre pedras de gelo num prato.\n4. Sirva com a mignonette e fatias de pão de centeio.",
                'wines'       => ['Miolo Brut Nature'],
                'wine_notes'  => 'A mineralidade e borbulhas do espumante Brut Nature realçam o sabor oceânico das ostras.',
            ],
            [
                'name'        => 'Cordeiro Assado com Ervas e Alho',
                'description' => 'Pernil de cordeiro assado lentamente com ervas mediterrâneas e alho.',
                'difficulty'  => 'difícil',
                'prep_time'   => 180,
                'instructions' => "1. Faça cortes no cordeiro e insira lâminas de alho e ramos de alecrim.\n2. Misture azeite, mostarda Dijon, alho amassado, alecrim e tomilho. Esfregue por todo o cordeiro.\n3. Cubra e marinar por pelo menos 12h na geladeira.\n4. Retire da geladeira 1h antes. Asse a 220°C por 20 min para dourar.\n5. Reduza para 160°C e asse por mais 2h regando com o próprio suco a cada 30 min.\n6. Descanse por 15 min antes de fatiar.",
                'wines'       => ['Catena Zapata Adrianna', 'Château Margaux Grand Vin'],
                'wine_notes'  => 'A intensidade do cordeiro pede um tinto de altitude com taninos maduros e bouquet complexo.',
            ],
            [
                'name'        => 'Ceviche de Peixe Branco',
                'description' => 'Peixe branco curado no limão com cebola roxa, coentro e pimenta dedo-de-moça.',
                'difficulty'  => 'fácil',
                'prep_time'   => 20,
                'instructions' => "1. Corte 400g de peixe branco (tilápia ou robalo) em cubos de 2cm.\n2. Tempere com sal e cubra completamente com suco de limão (≈ 150ml). Deixe curar por 10 min.\n3. Escorra metade do suco. Adicione cebola roxa em fatias finas, coentro picado e pimenta a gosto.\n4. Ajuste sal e sirva imediatamente com chips de mandioca ou torradas.",
                'wines'       => ['Miolo Brut Nature', 'Esporão Reserva Branco'],
                'wine_notes'  => 'A acidez cítrica do prato pede um vinho de boa frescura e mineralidade para equilibrar.',
            ],
            [
                'name'        => 'Filé Mignon ao Molho Madeira',
                'description' => 'Medalhões de filé mignon selados na manteiga com clássico molho de vinho Madeira.',
                'difficulty'  => 'médio',
                'prep_time'   => 30,
                'instructions' => "1. Tempere os medalhões com sal e pimenta. Sele em frigideira bem quente com manteiga e azeite, 2 min por lado.\n2. Reserve a carne e na mesma frigideira refogue 1 cebola pequena picada até dourar.\n3. Acrescente 100ml de vinho Madeira e raspe o fundo da frigideira. Deixe reduzir à metade.\n4. Adicione 200ml de caldo de carne e 1 colher de extrato de tomate. Cozinhe por 5 min.\n5. Finalize com 1 colher de manteiga gelada para brilho. Sirva os medalhões cobertos com o molho.",
                'wines'       => ['Concha y Toro Don Melchor', 'Miolo Lote 43'],
                'wine_notes'  => 'O Cabernet Sauvignon encorpado dialoga perfeitamente com os taninos do molho de vinho reduzido.',
            ],
        ];

        $created = 0;
        foreach ($recipes as $data) {
            if (Recipe::where('name', $data['name'])->exists()) {
                continue;
            }

            $recipe = Recipe::create([
                'name'         => $data['name'],
                'description'  => $data['description'],
                'difficulty'   => $data['difficulty'],
                'prep_time'    => $data['prep_time'],
                'instructions' => $data['instructions'],
                'is_active'    => true,
            ]);

            // Link to wines
            foreach ($data['wines'] as $wineName) {
                $wine = Wine::where('name', $wineName)->first();
                if ($wine) {
                    $recipe->wines()->attach($wine->id, ['notes' => $data['wine_notes']]);
                }
            }

            $created++;
        }

        $this->command->info("RecipeSeeder: {$created} receitas criadas.");
    }
}
