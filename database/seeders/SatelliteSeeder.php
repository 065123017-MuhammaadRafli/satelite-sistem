<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Satellite;
use Carbon\Carbon;

class SatelliteSeeder extends Seeder
{
    public function run(): void
    {
        $satellites = [
            [
                'name' => 'LAPAN-A2',
                'country' => 'Indonesia',
                'launch_date' => Carbon::parse('2015-09-28'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 1,
                'description' => 'Indonesian Earth observation satellite for monitoring natural disasters',
                'tle' => "1 40931U 15052B   23001.50000000  .00000000  00000-0  00000-0 0  9999\n2 40931  97.9000 180.0000 0001000   0.0000 180.0000 14.50000000000000"
            ],
            [
                'name' => 'LAPAN-A3',
                'country' => 'Indonesia',
                'launch_date' => Carbon::parse('2016-06-22'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 1,
                'description' => 'Maritime monitoring and AIS receiver satellite',
                'tle' => "1 41588U 16033C   23001.50000000  .00000000  00000-0  00000-0 0  9999\n2 41588  97.9000 180.0000 0001000   0.0000 180.0000 14.50000000000000"
            ],
            [
                'name' => 'Telkom-4',
                'country' => 'Indonesia',
                'launch_date' => Carbon::parse('2018-08-07'),
                'orbit_type' => 'GEO',
                'status' => 'active',
                'ground_station_id' => 1,
                'description' => 'Communication satellite serving Indonesia',
            ],
            [
                'name' => 'Hubble Space Telescope',
                'country' => 'United States',
                'launch_date' => Carbon::parse('1990-04-24'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 3,
                'description' => 'Space telescope for astronomical observations',
            ],
            [
                'name' => 'ISS (International Space Station)',
                'country' => 'United States',
                'launch_date' => Carbon::parse('1998-11-20'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 3,
                'description' => 'Habitable artificial satellite in low Earth orbit',
            ],
            [
                'name' => 'Sentinel-1A',
                'country' => 'Europe',
                'launch_date' => Carbon::parse('2014-04-03'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 4,
                'description' => 'European radar imaging satellite for land and ocean monitoring',
            ],
            [
                'name' => 'Sentinel-2A',
                'country' => 'Europe',
                'launch_date' => Carbon::parse('2015-06-23'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 4,
                'description' => 'Multispectral imaging satellite',
            ],
            [
                'name' => 'Himawari-8',
                'country' => 'Japan',
                'launch_date' => Carbon::parse('2014-10-07'),
                'orbit_type' => 'GEO',
                'status' => 'active',
                'ground_station_id' => 5,
                'description' => 'Weather observation satellite',
            ],
            [
                'name' => 'ALOS-2',
                'country' => 'Japan',
                'launch_date' => Carbon::parse('2014-05-24'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 5,
                'description' => 'Advanced Land Observing Satellite',
            ],
            [
                'name' => 'GPS IIF-12',
                'country' => 'United States',
                'launch_date' => Carbon::parse('2016-02-05'),
                'orbit_type' => 'MEO',
                'status' => 'active',
                'ground_station_id' => 3,
                'description' => 'Global Positioning System satellite',
            ],
        ];

        foreach ($satellites as $satellite) {
            Satellite::create($satellite);
        }
    }
}