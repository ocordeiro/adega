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
        $imagePath = database_path('seeders/images/absolute.png');

        $spirits = [
            [
                'name'            => 'Absolut Vodka',
                'spirit_type'     => 'Vodka',
                'alcohol_content' => 40.00,
                'country_code'    => 'SE',
                'barcode'         => '7312040017072',
                'description'     => 'Vodka sueca premium, destilada em Åhus. Sabor suave e puro, ideal para coquetéis clássicos.',
            ],
        ];

        $created = 0;
        foreach ($spirits as $data) {
            if (Spirit::where('name', $data['name'])->exists()) {
                continue;
            }

            $type    = SpiritType::where('name', $data['spirit_type'])->first();
            $country = Country::where('code', $data['country_code'])->first();

            $spirit = Spirit::create([
                'name'            => $data['name'],
                'slug'            => Str::slug($data['name']),
                'spirit_type_id'  => $type?->id,
                'country_id'      => $country?->id,
                'alcohol_content' => $data['alcohol_content'],
                'barcode'         => $data['barcode'],
                'description'     => $data['description'],
                'is_active'       => true,
            ]);

            if ($imagePath && file_exists($imagePath)) {
                try {
                    $spirit->addMedia($imagePath)
                        ->preservingOriginal()
                        ->toMediaCollection('photos');
                } catch (\Exception $e) {
                    $this->command->warn("Não foi possível anexar imagem ao destilado \"{$spirit->name}\": {$e->getMessage()}");
                }
            }

            $created++;
        }

        $this->command->info("SpiritSeeder: {$created} destilados criados.");
    }
}
