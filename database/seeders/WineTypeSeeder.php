<?php

namespace Database\Seeders;

use App\Models\WineType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class WineTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Tinto',
            'Branco',
            'Rosé',
            'Espumante Branco',
            'Espumante Rosé',
            'Sobremesa',
            'Laranja',
            'Fortificado',
        ];

        foreach ($types as $name) {
            WineType::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)]
            );
        }
    }
}
