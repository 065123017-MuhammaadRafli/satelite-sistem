<?php

namespace App\Http\Controllers;

use App\Models\Satellite;
use App\Models\GroundStation;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_satellites' => Satellite::count(),
            'active_satellites' => Satellite::active()->count(),
            'inactive_satellites' => Satellite::inactive()->count(),
            'total_ground_stations' => GroundStation::count(),
            'recent_satellites' => Satellite::latest()->take(5)->get(),
            'satellites_by_orbit' => Satellite::selectRaw('orbit_type, COUNT(*) as count')
                ->groupBy('orbit_type')
                ->get(),
            'satellites_by_country' => Satellite::selectRaw('country, COUNT(*) as count')
                ->groupBy('country')
                ->orderBy('count', 'desc')
                ->take(5)
                ->get(),
        ];

        return view('dashboard', $data);
    }
}