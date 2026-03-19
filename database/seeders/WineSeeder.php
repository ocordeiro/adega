<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Food;
use App\Models\GrapeVariety;
use App\Models\Producer;
use App\Models\Region;
use App\Models\Wine;
use App\Models\WineType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WineSeeder extends Seeder
{
    public function run(): void
    {
        $images = [
            base_path('storage/app/public/wines/wine-1.jpg'),
            base_path('storage/app/public/wines/wine-2.jpg'),
        ];

        $wines = [
            [
                'name'           => 'Miolo Lote 43',
                'barcode'        => '7891164000016',
                'country'        => 'BR',
                'region'         => 'Vale dos Vinhedos',
                'producer'       => 'Miolo Wine Group',
                'wine_type'      => 'Tinto',
                'vintage'        => 2021,
                'description'    => 'Blend elegante com aromas de ameixa madura, especiarias e toque de carvalho. Taninos sedosos e final longo com notas de chocolate amargo.',
                'alcohol'        => 13.5,
                'temp_min'       => 16,
                'temp_max'       => 18,
                'rating'         => 4,
                'grapes'         => ['Cabernet Sauvignon' => 60, 'Merlot' => 40],
                'foods'          => ['Picanha', 'Filé Mignon', 'Costela', 'Queijos', 'Chocolate Amargo'],
                'image_index'    => 0,
            ],
            [
                'name'           => 'Catena Zapata Adrianna',
                'barcode'        => '7790036100025',
                'country'        => 'AR',
                'region'         => 'Mendoza',
                'producer'       => 'Catena Zapata',
                'wine_type'      => 'Tinto',
                'vintage'        => 2020,
                'description'    => 'Malbec de altitude excepcional. Violeta e frutas negras na cor, aromas de mirtilo, amora e pétalas de rosa. Estrutura vibrante, frescor e mineralidade únicos.',
                'alcohol'        => 14.0,
                'temp_min'       => 16,
                'temp_max'       => 18,
                'rating'         => 5,
                'grapes'         => ['Malbec' => 100],
                'foods'          => ['Costela', 'Cordeiro', 'Gorgonzola', 'Tábua de Frios'],
                'image_index'    => 1,
            ],
            [
                'name'           => 'Château Margaux Grand Vin',
                'barcode'        => '3354020000031',
                'country'        => 'FR',
                'region'         => 'Bordeaux',
                'producer'       => 'Château Margaux',
                'wine_type'      => 'Tinto',
                'vintage'        => 2018,
                'description'    => 'Elegância bordalesa em sua forma mais pura. Cassis, cedro, grafite e flores silvestres. Estrutura impecável com taninos polidos e retrogusto interminável.',
                'alcohol'        => 13.0,
                'temp_min'       => 16,
                'temp_max'       => 18,
                'rating'         => 5,
                'grapes'         => ['Cabernet Sauvignon' => 75, 'Merlot' => 20, 'Cabernet Franc' => 5],
                'foods'          => ['Filé Mignon', 'Cordeiro', 'Brie', 'Camembert'],
                'image_index'    => 0,
            ],
            [
                'name'           => 'Miolo Brut Nature',
                'barcode'        => '7891164000047',
                'country'        => 'BR',
                'region'         => 'Serra Gaúcha',
                'producer'       => 'Miolo Wine Group',
                'wine_type'      => 'Espumante Branco',
                'vintage'        => 2022,
                'description'    => 'Espumante Brut Nature elaborado pelo método tradicional. Borbulhas finas e persistentes, aromas de pão fresco, frutas cítricas e flores brancas. Frescor e vivacidade.',
                'alcohol'        => 12.0,
                'temp_min'       => 6,
                'temp_max'       => 8,
                'rating'         => 4,
                'grapes'         => ['Chardonnay' => 70, 'Pinot Noir' => 30],
                'foods'          => ['Ostra', 'Lagosta', 'Camarão', 'Salmão', 'Brie'],
                'image_index'    => 1,
            ],
            [
                'name'           => 'Concha y Toro Don Melchor',
                'barcode'        => '7804320000052',
                'country'        => 'CL',
                'region'         => 'Maipo',
                'producer'       => 'Concha y Toro',
                'wine_type'      => 'Tinto',
                'vintage'        => 2019,
                'description'    => 'Ícone chileno com 30 anos de história. Frutas negras intensas, tabaco, couro e especiarias. Envelhecido em carvalho francês, expressa o terroir único do Alto Maipo.',
                'alcohol'        => 14.5,
                'temp_min'       => 16,
                'temp_max'       => 18,
                'rating'         => 5,
                'grapes'         => ['Cabernet Sauvignon' => 90, 'Cabernet Franc' => 10],
                'foods'          => ['Picanha', 'Javali', 'Parmesão', 'Bresaola'],
                'image_index'    => 0,
            ],
            [
                'name'           => 'Esporão Reserva Branco',
                'barcode'        => '5601072000063',
                'country'        => 'PT',
                'region'         => 'Alentejo',
                'producer'       => 'Esporão',
                'wine_type'      => 'Branco',
                'vintage'        => 2022,
                'description'    => 'Branco alentejano de personalidade marcante. Pêssego, manga, flores brancas e leve toque mineral. Fresco, encorpado, com final elegante e persistente.',
                'alcohol'        => 13.0,
                'temp_min'       => 10,
                'temp_max'       => 12,
                'rating'         => 4,
                'grapes'         => ['Verdejo' => 50, 'Viognier' => 30, 'Chardonnay' => 20],
                'foods'          => ['Bacalhau', 'Polvo', 'Frango Grelhado', 'Queijos', 'Azeitonas'],
                'image_index'    => 1,
            ],
        ];

        foreach ($wines as $data) {
            // pula se já existe
            if (Wine::where('barcode', $data['barcode'])->exists()) {
                continue;
            }

            $country  = Country::where('code', $data['country'])->first();
            $region   = $country ? Region::where('country_id', $country->id)->where('name', $data['region'])->first() : null;
            $producer = Producer::where('name', $data['producer'])->first();
            $wineType = WineType::where('name', $data['wine_type'])->first();

            $slug = Str::slug($data['name'] . ' ' . $data['vintage']);

            $wine = Wine::create([
                'name'             => $data['name'],
                'slug'             => $slug,
                'barcode'          => $data['barcode'],
                'country_id'       => $country?->id,
                'region_id'        => $region?->id,
                'producer_id'      => $producer?->id,
                'wine_type_id'     => $wineType?->id,
                'vintage'          => $data['vintage'],
                'description'      => $data['description'],
                'alcohol_content'  => $data['alcohol'],
                'serving_temp_min' => $data['temp_min'],
                'serving_temp_max' => $data['temp_max'],
                'rating'           => $data['rating'],
                'is_active'        => true,
            ]);

            // grape varieties
            foreach ($data['grapes'] as $grapeName => $pct) {
                $grape = GrapeVariety::where('name', $grapeName)->first();
                if ($grape) {
                    $wine->grapeVarieties()->attach($grape->id, ['percentage' => $pct]);
                }
            }

            // food pairings
            $foodIds = [];
            foreach ($data['foods'] as $foodName) {
                $food = Food::where('name', $foodName)->first();
                if ($food) {
                    $foodIds[$food->id] = ['notes' => null];
                }
            }
            if ($foodIds) {
                $wine->foods()->sync($foodIds);
            }

            // photo
            $imagePath = $images[$data['image_index']] ?? null;
            if ($imagePath && file_exists($imagePath)) {
                try {
                    $wine->addMedia($imagePath)
                        ->preservingOriginal()
                        ->toMediaCollection('photos');
                } catch (\Exception $e) {
                    $this->command->warn("Não foi possível anexar imagem ao vinho \"{$wine->name}\": {$e->getMessage()}");
                }
            }
        }

        $this->command->info('WineSeeder: ' . count($wines) . ' vinhos processados.');
    }
}
