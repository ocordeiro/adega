<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\Region;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = [
            // América do Sul
            ['name' => 'Brasil',           'code' => 'BR', 'regions' => ['Vale dos Vinhedos', 'Serra Gaúcha', 'Vale do São Francisco', 'Campanha Gaúcha', 'Serra do Sudeste', 'Planalto Catarinense']],
            ['name' => 'Argentina',        'code' => 'AR', 'regions' => ['Mendoza', 'Salta', 'San Juan', 'Patagônia', 'La Rioja', 'Neuquén', 'Rio Negro']],
            ['name' => 'Chile',            'code' => 'CL', 'regions' => ['Maipo', 'Colchagua', 'Casablanca', 'Maule', 'Elqui', 'Aconcágua', 'Bío Bío', 'Limarí']],
            ['name' => 'Uruguai',          'code' => 'UY', 'regions' => ['Canelones', 'Colônia', 'Maldonado', 'Rivera', 'Cerro Largo']],
            ['name' => 'Bolívia',          'code' => 'BO', 'regions' => ['Tarija', 'Cinti']],
            ['name' => 'Peru',             'code' => 'PE', 'regions' => ['Ica', 'Lima', 'Arequipa']],
            // Europa
            ['name' => 'França',           'code' => 'FR', 'regions' => ['Bordeaux', 'Borgonha', 'Champagne', 'Rhône', 'Loire', 'Alsácia', 'Languedoc-Roussillon', 'Provence', 'Beaujolais', 'Armagnac', 'Cognac']],
            ['name' => 'Itália',           'code' => 'IT', 'regions' => ['Toscana', 'Piemonte', 'Vêneto', 'Sicília', 'Sardenha', 'Friuli', 'Abruzzo', 'Puglia', 'Campania', 'Lombardia', 'Trentino-Alto Adige', 'Emilia-Romagna']],
            ['name' => 'Espanha',          'code' => 'ES', 'regions' => ['Rioja', 'Ribera del Duero', 'Priorat', 'Galícia', 'Castilla-La Mancha', 'Andaluzia', 'Aragão', 'Catalunha', 'Penedès', 'Rueda', 'Toro', 'Jerez']],
            ['name' => 'Portugal',         'code' => 'PT', 'regions' => ['Alentejo', 'Douro', 'Vinho Verde', 'Lisboa', 'Bairrada', 'Dão', 'Setúbal', 'Algarve', 'Madeira', 'Açores']],
            ['name' => 'Alemanha',         'code' => 'DE', 'regions' => ['Mosela', 'Rheingau', 'Pfalz', 'Baden', 'Nahe', 'Franken', 'Württemberg', 'Ahr', 'Sachsen']],
            ['name' => 'Áustria',          'code' => 'AT', 'regions' => ['Wachau', 'Kamptal', 'Kremstal', 'Burgenland', 'Styria']],
            ['name' => 'Hungria',          'code' => 'HU', 'regions' => ['Tokaj', 'Eger', 'Villány']],
            ['name' => 'Grécia',           'code' => 'GR', 'regions' => ['Santorini', 'Nemea', 'Creta', 'Macedônia']],
            ['name' => 'Romênia',          'code' => 'RO', 'regions' => ['Moldova', 'Muntenia', 'Transilvânia']],
            ['name' => 'Suíça',            'code' => 'CH', 'regions' => ['Valais', 'Vaud', 'Genebra', 'Ticino']],
            // América do Norte
            ['name' => 'Estados Unidos',   'code' => 'US', 'regions' => ['Napa Valley', 'Sonoma', 'Oregon', 'Washington', 'Lodi', 'Paso Robles', 'Santa Barbara', 'Willamette Valley']],
            ['name' => 'Canadá',           'code' => 'CA', 'regions' => ['Okanagan Valley', 'Niagara Peninsula', 'Nova Escócia']],
            ['name' => 'México',           'code' => 'MX', 'regions' => ['Baja California', 'Valle de Guadalupe', 'Sonora']],
            // Oceania
            ['name' => 'Austrália',        'code' => 'AU', 'regions' => ['Barossa Valley', 'McLaren Vale', 'Hunter Valley', 'Margaret River', 'Yarra Valley', 'Clare Valley', 'Eden Valley', 'Coonawarra', 'Rutherglen']],
            ['name' => 'Nova Zelândia',    'code' => 'NZ', 'regions' => ['Marlborough', 'Hawke\'s Bay', 'Central Otago', 'Gisborne', 'Nelson', 'Canterbury']],
            // África
            ['name' => 'África do Sul',    'code' => 'ZA', 'regions' => ['Stellenbosch', 'Paarl', 'Franschhoek', 'Robertson', 'Walker Bay', 'Elgin', 'Swartland']],
            // Oriente Médio
            ['name' => 'Israel',           'code' => 'IL', 'regions' => ['Golan Heights', 'Judean Hills', 'Galilee']],
            ['name' => 'Líbano',           'code' => 'LB', 'regions' => ['Bekaa Valley']],
            // Ásia
            ['name' => 'Geórgia',          'code' => 'GE', 'regions' => ['Kakheti', 'Kartli', 'Imereti']],
            ['name' => 'Japão',            'code' => 'JP', 'regions' => ['Niigata', 'Kyoto', 'Hyogo', 'Akita']],
            // Europa do Norte
            ['name' => 'Reino Unido',      'code' => 'GB', 'regions' => ['Escócia', 'Irlanda do Norte', 'País de Gales', 'Inglaterra']],
            ['name' => 'Suécia',           'code' => 'SE', 'regions' => ['Skåne', 'Södermanland']],
            ['name' => 'Irlanda',          'code' => 'IE', 'regions' => ['County Cork', 'County Antrim']],
            // Caribe
            ['name' => 'Cuba',             'code' => 'CU', 'regions' => ['Santiago de Cuba', 'Santa Cruz del Norte']],
            ['name' => 'Jamaica',          'code' => 'JM', 'regions' => ['Clarendon', 'Westmoreland']],
            ['name' => 'Barbados',         'code' => 'BB', 'regions' => []],
            ['name' => 'Porto Rico',       'code' => 'PR', 'regions' => []],
            // América Central
            ['name' => 'Guatemala',        'code' => 'GT', 'regions' => ['Antigua Guatemala']],
        ];

        foreach ($countries as $data) {
            $regions = $data['regions'];
            unset($data['regions']);

            $country = Country::firstOrCreate(['code' => $data['code']], $data);

            foreach ($regions as $regionName) {
                Region::firstOrCreate([
                    'country_id' => $country->id,
                    'name'       => $regionName,
                ]);
            }
        }
    }
}
