<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            WineTypeSeeder::class,
            CountrySeeder::class,
            GrapeVarietySeeder::class,
            FoodCategorySeeder::class,
            FoodSeeder::class,
            ProducerSeeder::class,
            AdminUserSeeder::class,
            WineSeeder::class,
            RecipeSeeder::class,
            OccasionSeeder::class,
            SpiritTypeSeeder::class,
            SpiritSeeder::class,
            DrinkRecipeSeeder::class,
        ]);
    }
}
