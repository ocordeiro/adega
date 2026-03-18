<?php

namespace Database\Seeders;

use App\Models\GrapeVariety;
use Illuminate\Database\Seeder;

class GrapeVarietySeeder extends Seeder
{
    public function run(): void
    {
        $varieties = [
            // Tintas
            'Cabernet Sauvignon',
            'Merlot',
            'Syrah/Shiraz',
            'Pinot Noir',
            'Malbec',
            'Tempranillo',
            'Grenache',
            'Nebbiolo',
            'Sangiovese',
            'Touriga Nacional',
            'Tannat',
            'Zinfandel',
            'Cabernet Franc',
            'Carménère',
            'Barbera',
            // Brancas
            'Chardonnay',
            'Sauvignon Blanc',
            'Riesling',
            'Pinot Grigio',
            'Viognier',
            'Gewürztraminer',
            'Albariño',
            'Muscat',
            'Chenin Blanc',
            'Torrontés',
            'Verdejo',
            'Moscato',
        ];

        foreach ($varieties as $name) {
            GrapeVariety::firstOrCreate(['name' => $name]);
        }
    }
}
