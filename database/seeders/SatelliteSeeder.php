<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Satellite;
use Carbon\Carbon;

class SatelliteSeeder extends Seeder
{
    public function run(): void
    {
        Satellite::truncate();

        $satellites = [
            [
                'name' => 'LAPAN-A2',
                'country' => 'Indonesia',
                'launch_date' => Carbon::parse('2015-09-28'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 1,
                'description' => 'Indonesian Earth observation satellite for monitoring natural disasters',
                'tle' => "1 40931U 15052B   23001.50000000  .00000000  00000-0  00000-0 0  9999\n2 40931  97.9000 180.0000 0001000   0.0000 180.0000 14.50000000"
            ],
            [
                'name' => 'LAPAN-A3',
                'country' => 'Indonesia',
                'launch_date' => Carbon::parse('2016-06-22'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 1,
                'description' => 'Maritime monitoring and AIS receiver satellite',
                'tle' => "1 41588U 16033C   23001.50000000  .00000000  00000-0  00000-0 0  9999\n2 41588  97.9000 180.0000 0001000   0.0000 180.0000 14.50000000"
            ],
            [
                'name' => 'Telkom-4',
                'country' => 'Indonesia',
                'launch_date' => Carbon::parse('2018-08-07'),
                'orbit_type' => 'GEO',
                'status' => 'active',
                'ground_station_id' => 1,
                'description' => 'Communication satellite serving Indonesia',
                'tle' => "1 43587U 18064A   23001.50000000  .00000000  00000-0  00000-0 0  9999\n2 43587   0.0123 180.0000 0001000   0.0000 180.0000  1.00270000"
            ],
            [
                'name' => 'Hubble Space Telescope',
                'country' => 'United States',
                'launch_date' => Carbon::parse('1990-04-24'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 3,
                'description' => 'Space telescope for astronomical observations',
                'tle' => "1 20580U 90037B   23001.50000000  .00000000  00000-0  00000-0 0  9999\n2 20580  28.4690 180.0000 0001000   0.0000 180.0000 15.00000000"
            ],
            [
                'name' => 'ISS (International Space Station)',
                'country' => 'United States',
                'launch_date' => Carbon::parse('1998-11-20'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 3,
                'description' => 'Habitable artificial satellite in low Earth orbit',
                'tle' => "1 25544U 98067A   23124.52445602  .00016717  00000-0  10270-3 0  9111\n2 25544  51.6442  21.3781 0006317  69.4523  56.2333 15.50031429"
            ],
            [
                'name' => 'Sentinel-1A',
                'country' => 'Europe',
                'launch_date' => Carbon::parse('2014-04-03'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 4,
                'description' => 'European radar imaging satellite for land and ocean monitoring',
                'tle' => "1 39634U 14016A   23001.50000000  .00000000  00000-0  00000-0 0  9999\n2 39634  98.1818 100.0000 0001188   0.0000 100.0000 14.59000000"
            ],
            [
                'name' => 'Sentinel-2A',
                'country' => 'Europe',
                'launch_date' => Carbon::parse('2015-06-23'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 4,
                'description' => 'Multispectral imaging satellite',
                'tle' => "1 40697U 15028A   23001.50000000  .00000000  00000-0  00000-0 0  9999\n2 40697  98.5670 120.0000 0001142   0.0000 120.0000 14.30000000"
            ],
            [
                'name' => 'Himawari-8',
                'country' => 'Japan',
                'launch_date' => Carbon::parse('2014-10-07'),
                'orbit_type' => 'GEO',
                'status' => 'active',
                'ground_station_id' => 5,
                'description' => 'Weather observation satellite',
                'tle' => "1 40267U 14060A   23001.50000000  .00000000  00000-0  00000-0 0  9999\n2 40267   0.0210 140.0000 0001000   0.0000 140.0000  1.00270000"
            ],
            [
                'name' => 'ALOS-2',
                'country' => 'Japan',
                'launch_date' => Carbon::parse('2014-05-24'),
                'orbit_type' => 'LEO',
                'status' => 'inactive',
                'ground_station_id' => 5,
                'description' => 'Advanced Land Observing Satellite',
                'tle' => "1 39766U 14029A   23001.50000000  .00000000  00000-0  00000-0 0  9999\n2 39766  97.9150 150.0000 0001000   0.0000 150.0000 14.70000000"
            ],
            [
                'name' => 'GPS IIF-12',
                'country' => 'United States',
                'launch_date' => Carbon::parse('2016-02-05'),
                'orbit_type' => 'MEO',
                'status' => 'inactive',
                'ground_station_id' => 3,
                'description' => 'Global Positioning System satellite',
                'tle' => "1 41328U 16007A   23001.50000000  .00000000  00000-0  00000-0 0  9999\n2 41328  55.0000 160.0000 0001000   0.0000 160.0000  2.00560000"
            ],
        ];

        foreach ($satellites as $satellite) {
            Satellite::create($satellite);
        }
    }
}