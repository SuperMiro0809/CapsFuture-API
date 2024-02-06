<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LocationType;

class LocationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LocationType::updateOrCreate(['name' => 'heart'],[
            'display_name' => 'heart'
		]);

        LocationType::updateOrCreate(['name' => 'station'],[
            'display_name' => 'station'
		]);
    }
}
