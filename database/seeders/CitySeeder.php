<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\City;
use App\Models\Country;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        // $json = File::get(database_path('data/states.json'));
        // $cities = json_decode($json , true);
        // foreach($cities as $city){
        //     City::updateOrCreate(
        //         ['id' => $city['id']],
        //         [
        //             'name' => $city['name'],
        //             'country_id' => $city['country_id'],
        //         ]
        //     );
        // }
    }
}

