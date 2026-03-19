<?php

namespace Database\Seeders;

use App\Models\Food;
use App\Models\FoodCategory;
use Illuminate\Database\Seeder;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        // Descriptions indexed by food name
        $descriptions = [
            // Carnes Vermelhas
            'Picanha'        => 'Corte nobre brasileiro, suculento e saboroso, ideal na brasa ou no forno.',
            'Costela'        => 'Carne bovina de preparo lento, rica em colágeno, perfeita assada ou defumada.',
            'Filé Mignon'    => 'O mais macio dos cortes bovinos, com textura aveludada e sabor delicado.',
            'Cordeiro'       => 'Carne de sabor pronunciado, macia e aromática, tradicional em churrascos especiais.',
            'Javali'         => 'Carne escura e intensa, ligeiramente adocicada, com personalidade marcante.',

            // Carnes Brancas
            'Frango Grelhado' => 'Versátil e leve, ganha complexidade com ervas, limão e boas marinadas.',
            'Peru'           => 'Carne magra e saborosa, clássica em ocasiões especiais com recheios aromáticos.',
            'Pato'           => 'Rica em gordura natural, com sabor profundo e pele crocante irresistível.',
            'Porco Assado'   => 'Suculento e aromático, especialmente com ervas mediterrâneas e frutas.',

            // Frutos do Mar
            'Lagosta'        => 'Fruto do mar luxuoso, com carne firme e doce, excepcional com manteiga e limão.',
            'Camarão'        => 'Versátil e delicado, perfeito salteado, grelhado ou em caldos aromáticos.',
            'Ostra'          => 'Sabor oceânico intenso e textura cremosa — a harmonização clássica com espumantes.',
            'Polvo'          => 'Textura única e sabor marinho, excelente grelhado com azeite e ervas.',
            'Lula'           => 'Delicada e versátil, ótima frita, grelhada ou no molho ao tinto.',

            // Peixes
            'Salmão'         => 'Peixe rico em ômega-3, com carne gordurosa e saborosa, ótimo grelhado ou defumado.',
            'Atum'           => 'Carne firme e intensa, perfeito mal passado com crosta de gergelim.',
            'Bacalhau'       => 'Tradicional da culinária ibérica, com textura robusta e sabor inconfundível.',
            'Robalo'         => 'Peixe nobre de carne branca e delicada, excelente ao vapor ou grelhado.',
            'Tilápia'        => 'Peixe de sabor suave, ideal para preparos com molhos aromáticos.',

            // Queijos
            'Brie'           => 'Queijo francês de casca branca, cremoso e levemente amanteigado com toque terroso.',
            'Camembert'      => 'Irmão do Brie, de sabor mais intenso, perfeito derretido com geleia de frutas.',
            'Gorgonzola'     => 'Queijo azul italiano, picante e cremoso, poderoso companheiro de tintos encorpados.',
            'Parmesão'       => 'Queijo italiano duro, salgado e umami, excelente ralado ou em lascas.',
            'Cheddar'        => 'Queijo inglês firme, levemente ácido, que evolui com a idade.',
            'Gruyère'        => 'Queijo suíço de sabor adocicado e de nozes, imprescindível na fondue.',

            // Embutidos
            'Presunto Parma'  => 'Presunto italiano curado, delicado e levemente adocicado, perfeito em tábuas.',
            'Salame'         => 'Embutido curado de sabor intenso e especiado, clássico em antipastos.',
            'Chorizo'        => 'Linguiça espanhola defumada com páprica, robusta e aromática.',
            'Pepperoni'      => 'Embutido americano de inspiração italiana, picante e saboroso.',
            'Bresaola'       => 'Carne bovina curada italiana, magra e delicada, perfeita com rúcula e parmesão.',

            // Massas
            'Lasanha'        => 'Clássico italiano em camadas com molho à bolonhesa, bechamel e queijo gratinado.',
            'Fettuccine'     => 'Massa larga que abraça molhos encorpados, perfeita com ragù ou cogumelos.',
            'Rigatoni'       => 'Massa tubular com textura que captura molhos densos e carnudos.',
            'Ravioli'        => 'Massa recheada com ricota, espinafre, carne ou trufas.',
            'Gnocchi'        => 'Nhoque de batata, macio e delicado, ótimo com molho de tomate ou manteiga com sálvia.',

            // Risotos
            'Risoto de Funghi'   => 'Arroz cremoso com cogumelos secos italianos, manteiga e parmesão — umami puro.',
            'Risoto de Camarão'  => 'Risoto aromático com camarões frescos, vinho branco e ervas do mar.',
            'Risoto de Trufas'   => 'A mais luxuosa das combinações: arroz cremoso com raspas ou óleo de trufas negras.',

            // Pizzas
            'Pizza Margherita'    => 'A original napolitana: molho de tomate San Marzano, mozzarella de búfala e manjericão.',
            'Pizza Pepperoni'     => 'A pizza mais amada do mundo, com generosas fatias de pepperoni picante.',
            'Pizza de Cogumelos'  => 'Combinação terrosa de shiitake, portobello e shimeji com cream cheese.',

            // Vegetariano
            'Cogumelos Salteados' => 'Mix de cogumelos frescos salteados em azeite com alho e tomilho.',
            'Ratatouille'        => 'Clássico provençal de legumes mediterrâneos assados em camadas.',
            'Berinjela Assada'   => 'Berinjela caramelizada ao forno com azeite e ervas, rica em sabor.',
            'Tomate Seco'        => 'Tomates concentrados em óleo, com sabor intenso umami.',

            // Sobremesas
            'Chocolate Amargo'  => 'Cacau acima de 70%, profundo e complexo, par perfeito de vinhos do Porto e tintos maduros.',
            'Crème Brûlée'      => 'Creme de baunilha com crosta de caramelo crocante — elegância francesa.',
            'Tiramisù'          => 'Sobremesa italiana de mascarpone, espresso e biscoitos savoiardi.',
            'Torta de Frutas'   => 'Torta fresca com frutas da estação e creme pâtissière.',

            // Aperitivos
            'Azeitonas'         => 'Azeitonas marinadas em azeite, ervas e especiarias — aperitivo mediterrâneo clássico.',
            'Bruschetta'        => 'Pão italiano tostado com tomate, alho, azeite e manjericão fresco.',
            'Tábua de Frios'    => 'Seleção de queijos, embutidos, frutas e pães artesanais para partilhar.',
            'Patê'              => 'Patê de fígado de pato ou galinha, cremoso e intenso, sobre torradas.',
        ];

        $updated = 0;
        foreach ($descriptions as $foodName => $desc) {
            $rows = Food::where('name', $foodName)->get();
            foreach ($rows as $food) {
                if (! $food->description) {
                    $food->update(['description' => $desc]);
                    $updated++;
                }
            }
        }

        $this->command->info("FoodSeeder: {$updated} alimentos atualizados com descrição.");
    }
}
