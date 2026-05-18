<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SatelliteController;
use App\Http\Controllers\GroundStationController;
use Illuminate\Support\Facades\Auth;
use App\Models\Satellite;

Route::redirect('/', '/login');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');

    Route::get('/satellites/live-tracking', [SatelliteController::class, 'liveTracking'])->name('satellites.live');

    // =========================================================================
    // CODE API FIX: AGAR TIDAK DOBEL DAN HANYA MENAMPILKAN SATELIT BARU (ISS)
    // =========================================================================
    Route::get('/api/live-satellites', function () {
        return response()->json(
            Satellite::where('status', 'active')
                ->where('name', 'NOT LIKE', '%LAPAN%') // KUNCI: Abaikan jika namanya mengandung kata LAPAN
                ->whereNotNull('tle_line1')
                ->whereNotNull('tle_line2')
                ->get(['id', 'name', 'tle_line1', 'tle_line2'])
        );
    })->name('api.satellites.live');
    // =========================================================================

    Route::resource('satellites', SatelliteController::class);
    Route::post('/satellites/{satellite}/sync-tle', [App\Http\Controllers\SatelliteController::class, 'syncSingleTLE'])->name('satellites.sync-tle');

    Route::resource('ground-stations', GroundStationController::class);
});

Route::redirect('/home', '/dashboard');
