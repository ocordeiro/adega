<?php

namespace Database\Seeders;

use App\Models\DrinkRecipe;
use App\Models\Food;
use App\Models\Occasion;
use Illuminate\Database\Seeder;

class OccasionSeeder extends Seeder
{
    public function run(): void
    {
        $occasions = [
            ['icon' => '🥩', 'name' => 'Churrasco',                'sort_order' => 1,  'description' => 'Carnes grelhadas na brasa, costela, picanha e assados ao ar livre.'],
            ['icon' => '🍽️', 'name' => 'Jantar Romântico',          'sort_order' => 2,  'description' => 'Ambiente intimista, refeição a dois com atenção aos detalhes.'],
            ['icon' => '🥂', 'name' => 'Celebração',               'sort_order' => 3,  'description' => 'Aniversários, casamentos, formaturas e momentos especiais.'],
            ['icon' => '💼', 'name' => 'Almoço de Negócios',        'sort_order' => 4,  'description' => 'Reuniões corporativas e refeições formais com clientes.'],
            ['icon' => '🌿', 'name' => 'Almoço em Família',         'sort_order' => 5,  'description' => 'Refeições descontraídas em reuniões familiares de fim de semana.'],
            ['icon' => '🎉', 'name' => 'Confraternização',           'sort_order' => 6,  'description' => 'Eventos com muitas pessoas, happy hour e celebrações coletivas.'],
            ['icon' => '🌅', 'name' => 'Aperitivo',                 'sort_order' => 7,  'description' => 'Entrada antes da refeição principal, petiscos e finger foods.'],
            ['icon' => '🏠', 'name' => 'Fim de Semana em Casa',     'sort_order' => 8,  'description' => 'Momento de relaxamento casual, séries, filmes e descanso.'],
            ['icon' => '🎁', 'name' => 'Presente Especial',         'sort_order' => 9,  'description' => 'Presente para colecionar ou oferecer em datas comemorativas.'],
            ['icon' => '🌊', 'name' => 'Praia e Campo',             'sort_order' => 10, 'description' => 'Ambientes ao ar livre, piqueniques e viagens de lazer.'],
        ];

        foreach ($occasions as $data) {
            Occasion::firstOrCreate(['name' => $data['name']], $data);
        }

        // Link occasions to foods
        $foodPairings = [
            'Picanha'           => ['Churrasco', 'Almoço em Família', 'Fim de Semana em Casa'],
            'Costela'           => ['Churrasco', 'Almoço em Família'],
            'Filé Mignon'       => ['Jantar Romântico', 'Almoço de Negócios', 'Celebração'],
            'Cordeiro'          => ['Jantar Romântico', 'Celebração', 'Presente Especial'],
            'Salmão'            => ['Jantar Romântico', 'Almoço de Negócios'],
            'Camarão'           => ['Jantar Romântico', 'Celebração', 'Aperitivo'],
            'Lagosta'           => ['Jantar Romântico', 'Celebração', 'Presente Especial'],
            'Ostra'             => ['Aperitivo', 'Jantar Romântico', 'Celebração'],
            'Brie'              => ['Aperitivo', 'Fim de Semana em Casa', 'Praia e Campo'],
            'Gorgonzola'        => ['Jantar Romântico', 'Aperitivo'],
            'Parmesão'          => ['Almoço em Família', 'Fim de Semana em Casa'],
            'Presunto Parma'    => ['Aperitivo', 'Praia e Campo', 'Confraternização'],
            'Bruschetta'        => ['Aperitivo', 'Confraternização', 'Praia e Campo'],
            'Tábua de Frios'    => ['Aperitivo', 'Confraternização', 'Fim de Semana em Casa'],
            'Lasanha'           => ['Almoço em Família', 'Fim de Semana em Casa'],
            'Chocolate Amargo'  => ['Jantar Romântico', 'Presente Especial', 'Fim de Semana em Casa'],
            'Frango Grelhado'   => ['Almoço em Família', 'Fim de Semana em Casa', 'Praia e Campo'],
            'Azeitonas'         => ['Aperitivo', 'Praia e Campo', 'Confraternização'],
        ];

        foreach ($foodPairings as $foodName => $occasionNames) {
            $food = Food::where('name', $foodName)->first();
            if (! $food) continue;

            $ids = Occasion::whereIn('name', $occasionNames)->pluck('id');
            $food->occasions()->syncWithoutDetaching($ids);
        }

        // Link occasions to drink recipes
        $drinkPairings = [
            'Caipirinha'          => ['Churrasco', 'Confraternização', 'Fim de Semana em Casa'],
            'Mojito'              => ['Praia e Campo', 'Confraternização', 'Aperitivo'],
            'Negroni'             => ['Aperitivo', 'Jantar Romântico', 'Almoço de Negócios'],
            'Old Fashioned'       => ['Jantar Romântico', 'Almoço de Negócios', 'Presente Especial'],
            'Whiskey Sour'        => ['Confraternização', 'Fim de Semana em Casa', 'Celebração'],
            'Margarita'           => ['Praia e Campo', 'Confraternização', 'Churrasco'],
            'Cosmopolitan'        => ['Celebração', 'Jantar Romântico', 'Confraternização'],
            'Espresso Martini'    => ['Celebração', 'Jantar Romântico', 'Confraternização'],
            'Aperol Spritz'       => ['Aperitivo', 'Praia e Campo', 'Confraternização'],
            'Gin Tônica'          => ['Aperitivo', 'Almoço de Negócios', 'Fim de Semana em Casa'],
            'Sex on the Beach'    => ['Praia e Campo', 'Confraternização', 'Celebração'],
            'Piña Colada'         => ['Praia e Campo', 'Fim de Semana em Casa', 'Celebração'],
            'Manhattan'           => ['Jantar Romântico', 'Almoço de Negócios', 'Presente Especial'],
            'Daiquiri'            => ['Aperitivo', 'Praia e Campo', 'Confraternização'],
            'Tom Collins'         => ['Aperitivo', 'Almoço em Família', 'Fim de Semana em Casa'],
            'Moscow Mule'         => ['Aperitivo', 'Confraternização', 'Fim de Semana em Casa'],
            'Jack & Coke'         => ['Confraternização', 'Churrasco', 'Fim de Semana em Casa'],
            'Tequila Sunrise'     => ['Praia e Campo', 'Confraternização', 'Celebração'],
            'Paloma'              => ['Aperitivo', 'Praia e Campo', 'Confraternização'],
            'Caipirinha de Morango' => ['Churrasco', 'Confraternização', 'Praia e Campo'],
            'Batida de Maracujá'  => ['Praia e Campo', 'Confraternização', 'Fim de Semana em Casa'],
            'Long Island Iced Tea'=> ['Confraternização', 'Celebração', 'Praia e Campo'],
        ];

        foreach ($drinkPairings as $drinkName => $occasionNames) {
            $drink = DrinkRecipe::where('name', $drinkName)->first();
            if (! $drink) continue;

            $ids = Occasion::whereIn('name', $occasionNames)->pluck('id');
            $drink->occasions()->syncWithoutDetaching($ids);
        }

        $this->command->info('OccasionSeeder: ' . count($occasions) . ' ocasiões criadas.');
    }
}
