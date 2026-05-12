<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SatelliteController;
use App\Http\Controllers\GroundStationController;
use Illuminate\Support\Facades\Auth;

Route::redirect('/', '/login');

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');
    
    Route::get('/satellites/live-tracking', [SatelliteController::class, 'liveTracking'])->name('satellites.live');
    
    Route::resource('satellites', SatelliteController::class);
    Route::resource('ground-stations', GroundStationController::class);
});

Route::redirect('/home', '/dashboard');