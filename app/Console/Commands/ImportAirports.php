<?php

namespace App\Console\Commands;

use App\Models\Airport;
use App\Models\City;
use App\Models\Country;
use Illuminate\Console\Command;

class ImportAirports extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-airports';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import airports and cities from airports.dat.txt file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = base_path('database/data/airports.dat.txt');
        // $skippedFile = storage_path('app/skipped_airports.csv');
        // file_put_contents($skippedFile, "Airport Name,City,Country,IATA,Reason\n");

        if (!file_exists($path)) {
            $this->error('file is not exist');
            return;
        }
        $imported = 0;
        $lines = file($path);
        foreach ($lines as $line) {
            $fields = str_getcsv($line);
            [$airportID, $airportName, $cityName, $countryName, $iata] = $fields;

            $countryKey = strtolower(trim($countryName));
            $country = Country::whereRaw('LOWER(name) = ?', [$countryKey])->first();
            if(!$country){continue;}
            $city = City::firstOrCreate(
                ['name'=>$cityName,'country_id'=>$country->id],
            [
                'name' => $cityName,
                'country_id' => $country->id
                ]
            );

            Airport::updateOrCreate(
                ['id' => $airportID],
                [
                    'name' => $airportName,
                    'city_id' => $city->id,
                    'IATA_code' => $iata
                ]
            );

            $imported++;
            // if($imported == 150) break ;
        }
        $this->info("Imported: $imported Airports");
        // $this->info("Skipped: $skipped Airports");

    }
}
