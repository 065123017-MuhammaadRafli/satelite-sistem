<?php

namespace App\Http\Controllers;

use App\Models\Satellite;
use App\Models\GroundStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class SatelliteController extends Controller
{
    public function index(Request $request)
    {
        $query = Satellite::with('groundStation');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Filter by country
        if ($request->has('country') && $request->country != '') {
            $query->byCountry($request->country);
        }

        // Filter by orbit
        if ($request->has('orbit') && $request->orbit != '') {
            $query->byOrbit($request->orbit);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            if ($request->status == 'active') {
                $query->active();
            } else {
                $query->inactive();
            }
        }

        $satellites = $query->latest()->paginate(10);
        $countries = Satellite::distinct()->pluck('country');

        return view('satellites.index', compact('satellites', 'countries'));
    }

    public function create()
    {
        $groundStations = GroundStation::all();
        return view('satellites.create', compact('groundStations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'launch_date' => 'required|date',
            'orbit_type' => 'required|in:LEO,MEO,GEO',
            'tle_line1' => 'nullable|string|size:69',
            'tle_line2' => 'nullable|string|size:69',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'ground_station_id' => 'nullable|exists:ground_stations,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('satellites', 'public');
        }

        Satellite::create($validated);

        return redirect()->route('satellites.index')
            ->with('success', 'Satellite created successfully!');
    }

    public function show(Satellite $satellite)
    {
        return view('satellites.show', compact('satellite'));
    }

    public function edit(Satellite $satellite)
    {
        $groundStations = GroundStation::all();
        return view('satellites.edit', compact('satellite', 'groundStations'));
    }

    public function update(Request $request, Satellite $satellite)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'launch_date' => 'required|date',
            'orbit_type' => 'required|in:LEO,MEO,GEO',
            'tle_line1' => 'nullable|string|size:69',
            'tle_line2' => 'nullable|string|size:69',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
            'ground_station_id' => 'nullable|exists:ground_stations,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('image')) {
            if ($satellite->image) {
                Storage::disk('public')->delete($satellite->image);
            }
            $validated['image'] = $request->file('image')->store('satellites', 'public');
        }

        $satellite->update($validated);

        return redirect()->route('satellites.index')
            ->with('success', 'Satellite updated successfully!');
    }

    public function destroy(Satellite $satellite)
    {
        if ($satellite->image) {
            Storage::disk('public')->delete($satellite->image);
        }

        $satellite->delete();

        return redirect()->route('satellites.index')
            ->with('success', 'Satellite deleted successfully!');
    }

    public function liveTracking()
    {
        // Mengambil semua satelit aktif yang TLE-nya terisi
        $satellites = Satellite::active()
            ->whereNotNull('tle_line1')
            ->whereNotNull('tle_line2')
            ->get();

        return view('satellites.live', compact('satellites'));
    }

    // Fungsi Baru: Menarik data TLE terbaru khusus untuk satu satelit
    // Fungsi Menarik Data TLE dengan Smart Fallback (BRIN -> Celestrak)
    public function syncSingleTLE(Satellite $satellite)
    {
        // 1. PRIORITAS UTAMA: Coba tarik dari server lokal BRIN (Timeout disingkat jadi 3 detik agar tidak hanging lama)
        $urlBrin = 'http://10.35.0.104/tle/LAPANSAT-TLE.txt';

        try {
            $responseBrin = Http::timeout(3)->get($urlBrin);

            if ($responseBrin->successful()) {
                $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $responseBrin->body())));
                $lines = array_values($lines);

                for ($i = 0; $i < count($lines); $i += 3) {
                    if (isset($lines[$i + 2])) {
                        $nameFromTxt = trim($lines[$i]);
                        if (stripos($nameFromTxt, $satellite->name) !== false || stripos($satellite->name, $nameFromTxt) !== false) {
                            $satellite->update([
                                'tle_line1' => trim($lines[$i + 1]),
                                'tle_line2' => trim($lines[$i + 2]),
                            ]);
                            return redirect()->back()->with('success', "Koneksi Cepat: TLE {$satellite->name} berhasil ditarik dari Server Lokal BRIN.");
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            // BRIN lambat atau mati? Abaikan error, kita biarkan sistem turun ke Fallback (Langkah 2)
        }

        // 2. FALLBACK PLAN: Jika server BRIN down/lambat, otomatis tembak ke API Publik Celestrak
        try {
            // Celestrak merespon sangat cepat karena mencari berdasarkan nama (bukan baca file txt besar)
            $urlCelestrak = 'https://celestrak.org/NORAD/elements/gp.php?NAME=' . urlencode($satellite->name) . '&FORMAT=tle';
            $responseCelestrak = Http::timeout(5)->get($urlCelestrak);

            if ($responseCelestrak->successful() && !empty(trim($responseCelestrak->body()))) {
                $tleData = explode("\n", trim($responseCelestrak->body()));

                if (count($tleData) >= 3) {
                    $satellite->update([
                        'tle_line1' => trim($tleData[1]),
                        'tle_line2' => trim($tleData[2]),
                    ]);
                    // Beri tahu user bahwa data diambil dari Celestrak karena server lokal sibuk
                    return redirect()->back()->with('success', "Server Lokal sibuk. TLE {$satellite->name} otomatis ditarik via Satelit Publik (Celestrak).");
                }
            }

            return redirect()->back()->with('error', "Gagal: TLE {$satellite->name} tidak ditemukan di BRIN maupun Celestrak.");

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Semua jalur transmisi (Lokal & Internasional) sedang tidak merespon. Coba lagi nanti.');
        }
    }
}
