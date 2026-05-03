<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Satellite extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country',
        'launch_date',
        'orbit_type',
        'tle',
        'status',
        'description',
        'image',
        'ground_station_id'
    ];

    protected $casts = [
        'launch_date' => 'date',
    ];

    // Relasi: Satellite belongs to Ground Station
    public function groundStation()
    {
        return $this->belongsTo(GroundStation::class);
    }

    // Query Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByOrbit($query, $orbit)
    {
        return $query->where('orbit_type', $orbit);
    }
}