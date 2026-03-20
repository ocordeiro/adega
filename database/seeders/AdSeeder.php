<?php

namespace Database\Seeders;

use App\Models\Ad;
use Illuminate\Database\Seeder;

class AdSeeder extends Seeder
{
    public function run(): void
    {
        $ad = Ad::firstOrCreate(
            ['title' => 'Anúncio de exemplo'],
            ['is_active' => true, 'sort_order' => 0]
        );

        if (! $ad->getFirstMedia('video')) {
            $videoPath = database_path('seeders/videos/sample.webm');

            if (file_exists($videoPath)) {
                $ad->addMedia($videoPath)
                    ->preservingOriginal()
                    ->toMediaCollection('video');
            }
        }
    }
}
