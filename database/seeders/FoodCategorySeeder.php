<?php

namespace Database\Seeders;

use App\Models\FoodCategory;
use App\Models\Food;
use Illuminate\Database\Seeder;

class FoodCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Carnes Vermelhas'  => ['Picanha', 'Costela', 'Filé Mignon', 'Cordeiro', 'Javali'],
            'Carnes Brancas'    => ['Frango Grelhado', 'Peru', 'Pato', 'Porco Assado'],
            'Frutos do Mar'     => ['Lagosta', 'Camarão', 'Ostra', 'Polvo', 'Lula'],
            'Peixes'            => ['Salmão', 'Atum', 'Bacalhau', 'Robalo', 'Tilápia'],
            'Queijos'           => ['Brie', 'Camembert', 'Gorgonzola', 'Parmesão', 'Cheddar', 'Gruyère'],
            'Embutidos'         => ['Presunto Parma', 'Salame', 'Chorizo', 'Pepperoni', 'Bresaola'],
            'Massas'            => ['Lasanha', 'Fettuccine', 'Rigatoni', 'Ravioli', 'Gnocchi'],
            'Risotos'           => ['Risoto de Funghi', 'Risoto de Camarão', 'Risoto de Trufas'],
            'Pizzas'            => ['Pizza Margherita', 'Pizza Pepperoni', 'Pizza de Cogumelos'],
            'Vegetariano'       => ['Cogumelos Salteados', 'Ratatouille', 'Berinjela Assada', 'Tomate Seco'],
            'Sobremesas'        => ['Chocolate Amargo', 'Crème Brûlée', 'Tiramisù', 'Torta de Frutas'],
            'Aperitivos'        => ['Azeitonas', 'Bruschetta', 'Tábua de Frios', 'Patê'],
        ];

        foreach ($categories as $categoryName => $foods) {
            $category = FoodCategory::firstOrCreate(['name' => $categoryName]);

            foreach ($foods as $foodName) {
                Food::firstOrCreate([
                    'food_category_id' => $category->id,
                    'name'             => $foodName,
                ]);
            }
        }
    }
}
