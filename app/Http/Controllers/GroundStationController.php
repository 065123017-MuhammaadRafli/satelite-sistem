<?php

namespace App\Http\Controllers;

use App\Models\GroundStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GroundStationController extends Controller
{
    public function index()
    {
        $groundStations = GroundStation::withCount('satellites')->latest()->paginate(10);
        return view('ground_stations.index', compact('groundStations'));
    }

    public function create()
    {
        return view('ground_stations.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'country' => 'required|string|max:255',
        'latitude' => 'required|numeric',
        'longitude' => 'required|numeric',
        'description' => 'nullable|string',
    ]);

    // =========================================================================
    // MESIN OTO-KALKULASI ALTITUDE MENGGUNAKAN SATELIT TOPOGRAFI (OPEN-METEO)
    // =========================================================================
    try {
        $lat = $validated['latitude'];
        $lng = $validated['longitude'];

        // Meminta data elevasi daratan dari API publik secara diam-diam
        $url = "https://api.open-meteo.com/v1/elevation?latitude={$lat}&longitude={$lng}";
        $response = Http::timeout(5)->get($url);

        // Jika berhasil mendapat jawaban, simpan angkanya
        if ($response->successful() && isset($response['elevation'][0])) {
            $validated['altitude'] = $response['elevation'][0];
        } else {
            $validated['altitude'] = 0; // Jika tidak ada daratan (di tengah laut)
        }
    } catch (\Exception $e) {
        $validated['altitude'] = 0; // Fallback jika server API sedang down
    }
    // =========================================================================

    // Simpan ke database
    GroundStation::create($validated);

    return redirect()->route('ground-stations.index')
        ->with('success', 'Stasiun bumi berhasil diregistrasi beserta data ketinggiannya!');
}

    public function show(GroundStation $groundStation)
    {
        $groundStation->load('satellites');
        return view('ground_stations.show', compact('groundStation'));
    }

    public function edit(GroundStation $groundStation)
    {
        return view('ground_stations.edit', compact('groundStation'));
    }

    public function update(Request $request, GroundStation $groundStation)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string'
        ]);

        $groundStation->update($validated);

        return redirect()->route('ground-stations.index')
            ->with('success', 'Ground Station updated successfully!');
    }

    public function destroy(GroundStation $groundStation)
    {
        $groundStation->delete();

        return redirect()->route('ground-stations.index')
            ->with('success', 'Ground Station deleted successfully!');
    }
}
