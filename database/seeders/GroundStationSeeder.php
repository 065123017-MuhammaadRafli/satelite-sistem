<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GroundStation;

class GroundStationSeeder extends Seeder
{
    public function run(): void
    {
        $groundStations = [
            [
                'name' => 'LAPAN Station Rumpin',
                'location' => 'Rumpin, Bogor',
                'country' => 'Indonesia',
                'latitude' => -6.5167,
                'longitude' => 106.5333,
                'description' => 'Main satellite ground station in Indonesia operated by BRIN'
            ],
            [
                'name' => 'LAPAN Station Pare-Pare',
                'location' => 'Pare-Pare, South Sulawesi',
                'country' => 'Indonesia',
                'latitude' => -4.0167,
                'longitude' => 119.6333,
                'description' => 'Secondary ground station for satellite monitoring'
            ],
            [
                'name' => 'NASA Goddard Space Flight Center',
                'location' => 'Greenbelt, Maryland',
                'country' => 'United States',
                'latitude' => 38.9967,
                'longitude' => -76.8492,
                'description' => 'Major NASA facility for satellite operations'
            ],
            [
                'name' => 'ESA ESOC Darmstadt',
                'location' => 'Darmstadt',
                'country' => 'Germany',
                'latitude' => 49.8728,
                'longitude' => 8.6226,
                'description' => 'European Space Operations Centre'
            ],
            [
                'name' => 'JAXA Tsukuba Space Center',
                'location' => 'Tsukuba, Ibaraki',
                'country' => 'Japan',
                'latitude' => 36.0833,
                'longitude' => 140.0833,
                'description' => 'Japan Aerospace Exploration Agency ground station'
            ],
        ];

        foreach ($groundStations as $gs) {
            GroundStation::create($gs);
        }
    }
}