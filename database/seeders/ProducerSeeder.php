<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Producer;
use Illuminate\Database\Seeder;

class ProducerSeeder extends Seeder
{
    public function run(): void
    {
        $producers = [
            ['name' => 'Miolo Wine Group',         'country' => 'BR'],
            ['name' => 'Casa Valduga',              'country' => 'BR'],
            ['name' => 'Salton',                    'country' => 'BR'],
            ['name' => 'Lidio Carraro',             'country' => 'BR'],
            ['name' => 'Catena Zapata',             'country' => 'AR'],
            ['name' => 'Achaval Ferrer',            'country' => 'AR'],
            ['name' => 'Luigi Bosca',               'country' => 'AR'],
            ['name' => 'Château Margaux',           'country' => 'FR'],
            ['name' => 'Domaine de la Romanée-Conti', 'country' => 'FR'],
            ['name' => 'Louis Jadot',               'country' => 'FR'],
            ['name' => 'Antinori',                  'country' => 'IT'],
            ['name' => 'Sassicaia - Tenuta San Guido', 'country' => 'IT'],
            ['name' => 'Gaja',                      'country' => 'IT'],
            ['name' => 'Vega Sicilia',              'country' => 'ES'],
            ['name' => 'Torres',                    'country' => 'ES'],
            ['name' => 'Quinta do Crasto',          'country' => 'PT'],
            ['name' => 'Esporão',                   'country' => 'PT'],
            ['name' => 'Concha y Toro',             'country' => 'CL'],
            ['name' => 'Almaviva',                  'country' => 'CL'],
            ['name' => 'Robert Mondavi',            'country' => 'US'],
            ['name' => 'Opus One',                  'country' => 'US'],
        ];

        foreach ($producers as $data) {
            $country = Country::where('code', $data['country'])->first();
            Producer::firstOrCreate(
                ['name' => $data['name']],
                ['country_id' => $country?->id]
            );
        }
    }
}
