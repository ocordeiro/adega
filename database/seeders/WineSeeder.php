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
        $imagePath = database_path('seeders/images/wine.png');

        $wines = [
            [
                'name'        => 'Miolo Lote 43',
                'barcode'     => '7891164000016',
                'country'     => 'BR',
                'region'      => 'Vale dos Vinhedos',
                'producer'    => 'Miolo Wine Group',
                'wine_type'      => 'Tinto',
                'classification' => 'seco',
                'vintage'        => 2021,
                'description' => 'Blend elegante com aromas de ameixa madura, especiarias e toque de carvalho. Taninos sedosos e final longo com notas de chocolate amargo.',
                'alcohol'     => 13.5,
                'temp_min'    => 16,
                'temp_max'    => 18,
                'rating'      => 4,
                'grapes'      => ['Cabernet Sauvignon' => 60, 'Merlot' => 40],
                'foods'       => ['Picanha', 'Filé Mignon', 'Tábua de Frios', 'Costela', 'Chocolate Amargo'],
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
                'classification'   => $data['classification'] ?? null,
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
