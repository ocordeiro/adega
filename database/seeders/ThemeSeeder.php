<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    private const THEMES = [
        ['slug' => 'padrao',     'name' => 'Padrão',     'primary' => '#d93f35', 'secondary' => '#b83028', 'background' => '#ffffff', 'text' => '#1a1a2e', 'active' => true],
        ['slug' => 'adega',      'name' => 'Adega',      'primary' => '#c8a96e', 'secondary' => '#e2c98a', 'background' => '#0d0d0f', 'text' => '#f5f0eb', 'active' => false],
        ['slug' => 'vinho',      'name' => 'Vinho',      'primary' => '#9b3a5c', 'secondary' => '#c4506a', 'background' => '#12060a', 'text' => '#f0e8ec', 'active' => false],
        ['slug' => 'champagne',  'name' => 'Champagne',  'primary' => '#b8912a', 'secondary' => '#d4ae57', 'background' => '#faf6ef', 'text' => '#1a1410', 'active' => false],
        ['slug' => 'esmeralda',  'name' => 'Esmeralda',  'primary' => '#2d7a5f', 'secondary' => '#4aad8a', 'background' => '#060f0c', 'text' => '#e8f5f0', 'active' => false],
        ['slug' => 'ebano',      'name' => 'Ébano',      'primary' => '#c0c0c0', 'secondary' => '#e8e8e8', 'background' => '#000000', 'text' => '#f5f5f5', 'active' => false],
        ['slug' => 'rose',       'name' => 'Rosé',       'primary' => '#d4688a', 'secondary' => '#e89ab0', 'background' => '#0f0608', 'text' => '#fce8ef', 'active' => false],
    ];

    public function run(): void
    {
        foreach (self::THEMES as $t) {
            Theme::firstOrCreate(
                ['slug' => $t['slug']],
                [
                    'name'                    => $t['name'],
                    'color_primary'           => $t['primary'],
                    'color_secondary'         => $t['secondary'],
                    'color_background'        => $t['background'],
                    'color_text'              => $t['text'],
                    'default_color_primary'   => $t['primary'],
                    'default_color_secondary' => $t['secondary'],
                    'default_color_background'=> $t['background'],
                    'default_color_text'      => $t['text'],
                    'is_active'               => $t['active'],
                ]
            );
        }
    }
}
