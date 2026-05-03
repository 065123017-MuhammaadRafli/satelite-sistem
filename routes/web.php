<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SatelliteController;
use App\Http\Controllers\GroundStationController;
use Illuminate\Support\Facades\Auth;

// Redirect root ke login
Route::redirect('/', '/login');

// Auth routes (Login, Register, dll)
Auth::routes();

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');
    Route::resource('satellites', SatelliteController::class);
    Route::resource('ground-stations', GroundStationController::class);
});

// Redirect /home ke /dashboard
Route::redirect('/home', '/dashboard');