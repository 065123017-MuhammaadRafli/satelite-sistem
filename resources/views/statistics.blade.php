@extends('layouts.admin')

@section('title', 'Statistik Sistem')
@section('page-title', 'Statistik Satelit & Infrastruktur')
@section('page-subtitle', 'Analisis visual komposisi satelit dan sebaran stasiun bumi.')
@section('page-icon', 'fas fa-chart-pie')

@section('content')
<div class="row row-cards mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="subheader text-uppercase fw-bold text-muted">Total Satellites</div>
                </div>
                <div class="d-flex align-items-baseline mt-2">
                    <div class="h1 mb-0 me-2 text-dark fw-bolder">3</div>
                    <div class="me-auto">
                        <span class="text-blue d-inline-flex align-items-center lh-1">
                            <i class="fas fa-satellite fa-sm me-1"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-blue" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="subheader text-uppercase fw-bold text-muted">Active Satellites</div>
                </div>
                <div class="d-flex align-items-baseline mt-2">
                    <div class="h1 mb-0 me-2 text-dark fw-bolder">3</div>
                    <div class="me-auto">
                        <span class="text-green d-inline-flex align-items-center lh-1">
                            <i class="fas fa-check-circle fa-sm me-1"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-green" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="subheader text-uppercase fw-bold text-muted">Inactive Satellites</div>
                </div>
                <div class="d-flex align-items-baseline mt-2">
                    <div class="h1 mb-0 me-2 text-dark fw-bolder">0</div>
                    <div class="me-auto">
                        <span class="text-orange d-inline-flex align-items-center lh-1">
                            <i class="fas fa-times-circle fa-sm me-1"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-orange" style="width: 0%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card shadow-sm border-0">
            <div class="card-body p-3">
                <div class="d-flex align-items-center">
                    <div class="subheader text-uppercase fw-bold text-muted">Ground Stations</div>
                </div>
                <div class="d-flex align-items-baseline mt-2">
                    <div class="h1 mb-0 me-2 text-dark fw-bolder">5</div>
                    <div class="me-auto">
                        <span class="text-red d-inline-flex align-items-center lh-1">
                            <i class="fas fa-broadcast-tower fa-sm me-1"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div class="progress progress-sm">
                <div class="progress-bar bg-red" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div>
</div>

<div class="row row-deck row-cards">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom">
                <h3 class="card-title fw-bold text-dark">
                    <i class="fas fa-globe text-primary me-2"></i> Satellites by Orbit Type
                </h3>
            </div>
            <div class="card-body p-4 d-flex align-items-center justify-content-center">
                <div id="chart-orbit-type" style="width: 100%; min-height: 280px;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom">
                <h3 class="card-title fw-bold text-dark">
                    <i class="fas fa-shield-alt text-success me-2"></i> Satellites by Status
                </h3>
            </div>
            <div class="card-body p-4 d-flex align-items-center justify-content-center">
                <div id="chart-status" style="width: 100%; min-height: 280px;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom">
                <h3 class="card-title fw-bold text-dark">
                    <i class="fas fa-flag text-info me-2"></i> Satellites by Country
                </h3>
            </div>
            <div class="card-body p-4">
                <div id="chart-sat-country" style="width: 100%; min-height: 280px;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 border-bottom">
                <h3 class="card-title fw-bold text-dark">
                    <i class="fas fa-map-marker-alt text-danger me-2"></i> Ground Stations by Country
                </h3>
            </div>
            <div class="card-body p-4">
                <div id="chart-gs-country" style="width: 100%; min-height: 280px;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {

            // --- 1. CHART ORBIT TYPE (Pie Chart) ---
            var optionsOrbit = {
                series: [3], // Data dummy awal
                labels: ['LEO'],
                chart: { type: 'pie', height: 280, fontFamily: 'Inter, sans-serif' },
                colors: ['#206bc4', '#4299e1', '#63b3ed'],
                legend: { position: 'bottom' },
                stroke: { width: 2, colors: ['#fff'] },
                dataLabels: { enabled: true, style: { fontWeight: 'bold' } }
            };
            new ApexCharts(document.querySelector("#chart-orbit-type"), optionsOrbit).render();

            // --- 2. CHART STATUS (Donut Chart) ---
            var optionsStatus = {
                series: [3, 0], // Data dummy awal: [Aktif, Inaktif]
                labels: ['Active', 'Inactive'],
                chart: { type: 'donut', height: 280, fontFamily: 'Inter, sans-serif' },
                colors: ['#2fb344', '#f76707'],
                legend: { position: 'bottom' },
                stroke: { width: 2, colors: ['#fff'] },
                plotOptions: {
                    pie: { donut: { size: '65%', labels: { show: true, name: { show: true }, value: { show: true } } } }
                }
            };
            new ApexCharts(document.querySelector("#chart-status"), optionsStatus).render();

            // --- 3. CHART SATELLITES BY COUNTRY (Vertical Bar Chart) ---
            var optionsSatCountry = {
                series: [{ name: 'Satellites', data: [3] }], // Data dummy awal
                chart: { type: 'bar', height: 280, fontFamily: 'Inter, sans-serif', toolbar: { show: false } },
                plotOptions: { bar: { borderRadius: 4, columnWidth: '40%' } },
                colors: ['#206bc4'],
                xaxis: { categories: ['Indonesia'] },
                grid: { strokeDashArray: 4, padding: { top: 0, right: 0, bottom: 0, left: 0 } },
                dataLabels: { enabled: false }
            };
            new ApexCharts(document.querySelector("#chart-sat-country"), optionsSatCountry).render();

            // --- 4. CHART GROUND STATIONS BY COUNTRY (Horizontal Bar Chart) ---
            var optionsGsCountry = {
                series: [{ name: 'Ground Stations', data: [3, 1, 1, 1] }], // Data dummy awal
                chart: { type: 'bar', height: 280, fontFamily: 'Inter, sans-serif', toolbar: { show: false } },
                plotOptions: { bar: { borderRadius: 4, horizontal: true, barHeight: '50%' } },
                colors: ['#d63939'],
                xaxis: { categories: ['Indonesia', 'United States', 'Germany', 'Japan'] },
                grid: { strokeDashArray: 4 },
                dataLabels: { enabled: false }
            };
            new ApexCharts(document.querySelector("#chart-gs-country"), optionsGsCountry).render();

        });
    </script>
@endpush
