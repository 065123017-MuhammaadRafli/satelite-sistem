<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SatelliteController;
use App\Http\Controllers\GroundStationController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('satellites', SatelliteController::class);
    Route::resource('ground-stations', GroundStationController::class);
});

Route::get('/home', function () {
    return redirect()->route('dashboard');
});