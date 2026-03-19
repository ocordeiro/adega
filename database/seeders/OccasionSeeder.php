<?php

namespace Database\Seeders;

use App\Models\Occasion;
use App\Models\Spirit;
use App\Models\Wine;
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
            ['icon' => '🎉', 'name' => 'Festa e Confraternização',  'sort_order' => 6,  'description' => 'Eventos com muitas pessoas, happy hour e celebrações coletivas.'],
            ['icon' => '🌅', 'name' => 'Aperitivo',                 'sort_order' => 7,  'description' => 'Entrada antes da refeição principal, petiscos e finger foods.'],
            ['icon' => '🏠', 'name' => 'Fim de Semana em Casa',     'sort_order' => 8,  'description' => 'Momento de relaxamento casual, séries, filmes e descanso.'],
            ['icon' => '🎁', 'name' => 'Presente Especial',         'sort_order' => 9,  'description' => 'Presente para colecionar ou oferecer em datas comemorativas.'],
            ['icon' => '🌊', 'name' => 'Praia e Campo',             'sort_order' => 10, 'description' => 'Ambientes ao ar livre, piqueniques e viagens de lazer.'],
        ];

        foreach ($occasions as $data) {
            Occasion::firstOrCreate(['name' => $data['name']], $data);
        }

        // Link occasions to seeded wines
        $pairings = [
            'Miolo Lote 43'              => ['Jantar Romântico', 'Almoço em Família', 'Fim de Semana em Casa'],
            'Catena Zapata Adrianna'     => ['Churrasco', 'Celebração', 'Almoço de Negócios', 'Presente Especial'],
            'Château Margaux Grand Vin'  => ['Jantar Romântico', 'Celebração', 'Almoço de Negócios', 'Presente Especial'],
            'Miolo Brut Nature'          => ['Aperitivo', 'Celebração', 'Festa e Confraternização'],
            'Concha y Toro Don Melchor'  => ['Churrasco', 'Celebração', 'Jantar Romântico', 'Presente Especial'],
            'Esporão Reserva Branco'     => ['Almoço em Família', 'Praia e Campo', 'Aperitivo', 'Fim de Semana em Casa'],
        ];

        foreach ($pairings as $wineName => $occasionNames) {
            $wine = Wine::where('name', $wineName)->first();
            if (! $wine) continue;

            $ids = Occasion::whereIn('name', $occasionNames)->pluck('id');
            $wine->occasions()->syncWithoutDetaching($ids);
        }

        // Link occasions to seeded spirits
        $spiritPairings = [
            'Absolut Vodka'           => ['Festa e Confraternização', 'Aperitivo', 'Fim de Semana em Casa'],
            'Jack Daniel\'s Old No. 7' => ['Churrasco', 'Fim de Semana em Casa', 'Celebração', 'Almoço de Negócios'],
            'Bacardi Carta Branca'    => ['Festa e Confraternização', 'Praia e Campo', 'Aperitivo'],
            'Tanqueray London Dry'    => ['Aperitivo', 'Festa e Confraternização', 'Jantar Romântico'],
            'José Cuervo Especial'    => ['Festa e Confraternização', 'Praia e Campo', 'Celebração'],
            'Ypióca Prata'            => ['Churrasco', 'Festa e Confraternização', 'Almoço em Família', 'Praia e Campo'],
        ];

        foreach ($spiritPairings as $spiritName => $occasionNames) {
            $spirit = Spirit::where('name', $spiritName)->first();
            if (! $spirit) continue;

            $ids = Occasion::whereIn('name', $occasionNames)->pluck('id');
            $spirit->occasions()->syncWithoutDetaching($ids);
        }

        $this->command->info('OccasionSeeder: ' . count($occasions) . ' ocasiões criadas.');
    }
}
