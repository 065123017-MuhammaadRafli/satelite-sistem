<?php

namespace App\Http\Controllers;

use App\Models\Satellite;
use App\Models\GroundStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
}