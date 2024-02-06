<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\{
    Location,
    LocationInformation,
    LocationType
};
use File;

class StationsLocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Location::truncate();
        LocationInformation::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $station_id = LocationType::where('name', 'station')->first()->id;

        $json = File::get('database/data/stations_data.json');
        $locations = json_decode($json, true);
  
        foreach ($locations as $key => $value) {
            $information = isset($value['information']) ? $value['information'] : '';

            $location = Location::create([
                'name' => isset($value['name']) ? $value['name'] : '-',
                'type_id' => $station_id,
                'latitude' => $value['Latitude'],
                'longitude' => $value['Longitude'],
                'collects_caps' => str_contains($information, 'капачки'),
                'collects_bottles' => str_contains($information, 'бутилки') || str_contains($information, 'шишета'),
                'collects_cans' =>  str_contains($information, 'кенчета')
            ]);

            $location->information()->create([
                'first_name' => isset($value['first_name']) ? $value['first_name'] : '-',
                'last_name' => isset($value['last_name']) ? $value['last_name'] : '-',
                'email' => isset($value['email']) ? $value['email'] : '-',
                'phone' => isset($value['phone']) ? $value['phone'] : '-',
                'working_time' => isset($value['working_time']) ? $value['working_time'] : '-'
            ]);
        };
    }
}
