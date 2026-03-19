<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Producer;
use App\Models\Spirit;
use App\Models\SpiritType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpiritSeeder extends Seeder
{
    public function run(): void
    {
        $spirits = [
            [
                'name'            => 'Absolut Vodka',
                'spirit_type'     => 'Vodka',
                'alcohol_content' => 40.00,
                'country_code'    => 'SE',
                'barcode'         => '7312040017072',
                'description'     => 'Vodka sueca premium, destilada em Åhus. Sabor suave e puro, ideal para coquetéis clássicos.',
            ],
            [
                'name'            => 'Jack Daniel\'s Old No. 7',
                'spirit_type'     => 'Whiskey',
                'alcohol_content' => 40.00,
                'country_code'    => 'US',
                'barcode'         => '5099873089798',
                'description'     => 'Tennessee Whiskey filtrado em carvão de bordo. Notas de caramelo, baunilha e carvalho.',
            ],
            [
                'name'            => 'Bacardi Carta Branca',
                'spirit_type'     => 'Rum',
                'alcohol_content' => 37.50,
                'country_code'    => 'PR',
                'barcode'         => '5010677012003',
                'description'     => 'Rum branco leve e refrescante, envelhecido e filtrado em carvão. Base clássica para mojitos e daiquiris.',
            ],
            [
                'name'            => 'Tanqueray London Dry',
                'spirit_type'     => 'Gin',
                'alcohol_content' => 47.30,
                'country_code'    => 'GB',
                'barcode'         => '5000291020706',
                'description'     => 'Gin londrino clássico com botânicos selecionados: zimbro, coentro, angélica e alcaçuz.',
            ],
            [
                'name'            => 'José Cuervo Especial',
                'spirit_type'     => 'Tequila',
                'alcohol_content' => 38.00,
                'country_code'    => 'MX',
                'barcode'         => '7501035010109',
                'description'     => 'Tequila gold mexicana, blend de tequilas reposado e jovem. Sabor suave com notas de agave.',
            ],
            [
                'name'            => 'Ypióca Prata',
                'spirit_type'     => 'Cachaça',
                'alcohol_content' => 39.00,
                'country_code'    => 'BR',
                'barcode'         => '7896065200012',
                'description'     => 'Cachaça cearense armazenada em tonéis de freijó. Sabor suave, perfeita para caipirinha.',
            ],
        ];

        $created = 0;
        foreach ($spirits as $data) {
            if (Spirit::where('name', $data['name'])->exists()) {
                continue;
            }

            $type    = SpiritType::where('name', $data['spirit_type'])->first();
            $country = Country::where('code', $data['country_code'])->first();

            Spirit::create([
                'name'            => $data['name'],
                'slug'            => Str::slug($data['name']),
                'spirit_type_id'  => $type?->id,
                'country_id'      => $country?->id,
                'alcohol_content' => $data['alcohol_content'],
                'barcode'         => $data['barcode'],
                'description'     => $data['description'],
                'is_active'       => true,
            ]);

            $created++;
        }

        $this->command->info("SpiritSeeder: {$created} destilados criados.");
    }
}
