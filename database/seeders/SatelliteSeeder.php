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
                'name' => 'LAPAN-TUBSAT',
                'country' => 'Indonesia',
                'launch_date' => Carbon::parse('2007-01-10'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 1,
                'description' => 'Satelit mikro pertama Indonesia, hasil kerja sama LAPAN dengan Technical University of Berlin.',
                'tle_line1' => '1 29709U 07001A   26130.88125376  .00000550  00000+0  69612-4 0  9994',
                'tle_line2' => '2 29709  98.1350 129.2664 0011111 312.3840  47.6433 14.85912519 45619'
            ],
            [
                'name' => 'LAPAN-A2',
                'country' => 'Indonesia',
                'launch_date' => Carbon::parse('2015-09-28'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 1,
                'description' => 'Satelit ekuatorial pertama Indonesia untuk pemantauan maritim dan komunikasi radio amatir.',
                'tle_line1' => '1 40931U 00000    26131.02275463  .00000000  00000-0 -12415-2 0    08',
                'tle_line2' => '2 40931   5.9991  75.7095 0012645 205.0834  75.8077 14.79203565 28203'
            ],
            [
                'name' => 'LAPAN-A3',
                'country' => 'Indonesia',
                'launch_date' => Carbon::parse('2016-06-22'),
                'orbit_type' => 'LEO',
                'status' => 'active',
                'ground_station_id' => 1,
                'description' => 'Satelit observasi bumi multispektral untuk pemantauan lahan pertanian dan maritim.',
                'tle_line1' => '1 41603U 16040E   26130.85389569  .00002601  00000+0  84148-4 0  9997',
                'tle_line2' => '2 41603  97.1491 128.2353 0011503 141.3371 218.8697 15.32868575549099'
            ]
        ];

        foreach ($satellites as $satellite) {
            Satellite::create($satellite);
        }
    }
}