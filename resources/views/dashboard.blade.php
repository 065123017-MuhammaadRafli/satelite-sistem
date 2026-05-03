@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
    <!-- Info boxes -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $total_satellites }}</h3>
                    <p>Total Satellites</p>
                </div>
                <div class="icon">
                    <i class="fas fa-satellite"></i>
                </div>
                <a href="{{ route('satellites.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $active_satellites }}</h3>
                    <p>Active Satellites</p>
                </div>
                <div class="icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <a href="{{ route('satellites.index', ['status' => 'active']) }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $inactive_satellites }}</h3>
                    <p>Inactive Satellites</p>
                </div>
                <div class="icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <a href="{{ route('satellites.index', ['status' => 'inactive']) }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>

        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $total_ground_stations }}</h3>
                    <p>Ground Stations</p>
                </div>
                <div class="icon">
                    <i class="fas fa-broadcast-tower"></i>
                </div>
                <a href="{{ route('ground-stations.index') }}" class="small-box-footer">
                    More info <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Satellites by Orbit -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Satellites by Orbit Type
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="orbitChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- Satellites by Country -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Top 5 Countries
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="countryChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Satellites -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-list mr-1"></i>
                        Recent Satellites
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Country</th>
                                <th>Orbit Type</th>
                                <th>Launch Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_satellites as $satellite)
                                <tr>
                                    <td>{{ $satellite->name }}</td>
                                    <td>{{ $satellite->country }}</td>
                                    <td><span class="badge badge-info">{{ $satellite->orbit_type }}</span></td>
                                    <td>{{ $satellite->launch_date->format('d M Y') }}</td>
                                    <td>
                                        @if($satellite->status == 'active')
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No satellites found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    // Orbit Chart
    const orbitData = {!! json_encode($satellites_by_orbit) !!};
    const orbitLabels = orbitData.map(item => item.orbit_type);
    const orbitCounts = orbitData.map(item => item.count);

    const orbitCtx = document.getElementById('orbitChart').getContext('2d');
    new Chart(orbitCtx, {
        type: 'pie',
        data: {
            labels: orbitLabels,
            datasets: [{
                data: orbitCounts,
                backgroundColor: ['#007bff', '#28a745', '#ffc107']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Country Chart
    const countryData = {!! json_encode($satellites_by_country) !!};
    const countryLabels = countryData.map(item => item.country);
    const countryCounts = countryData.map(item => item.count);

    const countryCtx = document.getElementById('countryChart').getContext('2d');
    new Chart(countryCtx, {
        type: 'bar',
        data: {
            labels: countryLabels,
            datasets: [{
                label: 'Satellites',
                data: countryCounts,
                backgroundColor: '#007bff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
@endpush