<?php

namespace Database\Seeders;

use App\Models\SpiritType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SpiritTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Vodka',
            'Whiskey',
            'Rum',
            'Gin',
            'Tequila',
            'Cachaça',
            'Licor',
            'Conhaque',
            'Bourbon',
            'Absinto',
            'Mezcal',
            'Amaretto',
            'Sake',
            'Grappa',
        ];

        foreach ($types as $name) {
            SpiritType::firstOrCreate(
                ['name' => $name],
                ['slug' => Str::slug($name)]
            );
        }
    }
}
