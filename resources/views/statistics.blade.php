@extends('layouts.admin')

@section('title', 'Statistik Sistem')
@section('page-title', 'Statistik Satelit & Infrastruktur')
@section('page-subtitle', 'Analisis visual komposisi satelit dan sebaran stasiun bumi.')
@section('page-icon', 'fas fa-chart-pie')

@section('content')
<style>
    /* Style Responsif & Interaktif untuk Kartu yang Bisa Di-klik */
    .card-clickable {
        border-radius: 10px;
        text-decoration: none !important;
        display: block;
        transition: transform 0.22s ease, box-shadow 0.22s ease, background-color 0.2s ease;
        cursor: pointer;
    }

    /* Efek Mengangkat & Bayangan Elegan saat Kursor Mengarah ke Kartu */
    .card-clickable:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(0, 0, 0, 0.07) !important;
        background-color: #fafbfc; /* Memberikan sedikit feedback warna cerah */
    }

    /* Memastikan link tidak merusak tata letak teks */
    .card-clickable .h1, .card-clickable .subheader {
        color: inherit;
    }
</style>

<div class="row row-cards mb-4">

    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('satellites.index') }}" class="card card-clickable shadow-sm border-0">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="subheader text-uppercase fw-bold text-muted" style="letter-spacing: 0.5px;">Total Satellites</div>
                        <div class="h1 mb-0 mt-1 text-dark fw-bolder" style="font-size: 1.8rem;">3</div>
                    </div>
                    <div class="bg-blue-lt text-blue rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-satellite fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-sm mt-3" style="height: 6px; border-radius: 4px;">
                    <div class="progress-bar bg-blue" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('satellites.index') }}?status=active" class="card card-clickable shadow-sm border-0">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="subheader text-uppercase fw-bold text-muted" style="letter-spacing: 0.5px;">Active Satellites</div>
                        <div class="h1 mb-0 mt-1 text-dark fw-bolder" style="font-size: 1.8rem;">3</div>
                    </div>
                    <div class="bg-success-lt text-success rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-sm mt-3" style="height: 6px; border-radius: 4px;">
                    <div class="progress-bar bg-green" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('satellites.index') }}?status=inactive" class="card card-clickable shadow-sm border-0">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="subheader text-uppercase fw-bold text-muted" style="letter-spacing: 0.5px;">Inactive Satellites</div>
                        <div class="h1 mb-0 mt-1 text-dark fw-bolder" style="font-size: 1.8rem;">0</div>
                    </div>
                    <div class="bg-warning-lt text-warning rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-times-circle fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-sm mt-3" style="height: 6px; border-radius: 4px;">
                    <div class="progress-bar bg-orange" style="width: 0%" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 col-xl-3">
        <a href="{{ route('satellites.index') }}" class="card card-clickable shadow-sm border-0">
            <div class="card-body p-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <div class="subheader text-uppercase fw-bold text-muted" style="letter-spacing: 0.5px;">Ground Stations</div>
                        <div class="h1 mb-0 mt-1 text-dark fw-bolder" style="font-size: 1.8rem;">5</div>
                    </div>
                    <div class="bg-danger-lt text-danger rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-broadcast-tower fa-lg"></i>
                    </div>
                </div>
                <div class="progress progress-sm mt-3" style="height: 6px; border-radius: 4px;">
                    <div class="progress-bar bg-red" style="width: 100%" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row row-deck row-cards">
    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h3 class="card-title fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-globe text-primary"></i> Satellites by Orbit Type
                </h3>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center p-3">
                <div id="chart-orbit-type" style="width: 100%; min-height: 280px;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h3 class="card-title fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-shield-alt text-success"></i> Satellites by Status
                </h3>
            </div>
            <div class="card-body d-flex align-items-center justify-content-center p-3">
                <div id="chart-status" style="width: 100%; min-height: 280px;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h3 class="card-title fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-flag text-info"></i> Satellites by Country
                </h3>
            </div>
            <div class="card-body p-3">
                <div id="chart-sat-country" style="width: 100%; min-height: 280px;"></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-3">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h3 class="card-title fw-bold text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-map-marker-alt text-danger"></i> Ground Stations by Country
                </h3>
            </div>
            <div class="card-body p-3">
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
            const globalFont = 'Inter, sans-serif';

            // --- 1. CHART ORBIT TYPE (Pie Chart) ---
            var optionsOrbit = {
                series: [3],
                labels: ['LEO'],
                chart: { type: 'pie', height: 280, fontFamily: globalFont },
                colors: ['#206bc4', '#4299e1', '#63b3ed'],
                legend: { position: 'bottom' },
                stroke: { width: 2, colors: ['#fff'] },
                dataLabels: { enabled: true, style: { fontWeight: 'bold' } }
            };
            new ApexCharts(document.querySelector("#chart-orbit-type"), optionsOrbit).render();

            // --- 2. CHART STATUS (Donut Chart) ---
            var optionsStatus = {
                series: [3, 0],
                labels: ['Active', 'Inactive'],
                chart: { type: 'donut', height: 280, fontFamily: globalFont },
                colors: ['#2fb344', '#f76707'],
                legend: { position: 'bottom' },
                stroke: { width: 2, colors: ['#fff'] },
                plotOptions: {
                    pie: { donut: { size: '70%', labels: { show: true, name: { show: true }, value: { show: true, fontWeight: 'bold' } } } }
                }
            };
            new ApexCharts(document.querySelector("#chart-status"), optionsStatus).render();

            // --- 3. CHART SATELLITES BY COUNTRY (Vertical Bar Chart) ---
            var optionsSatCountry = {
                series: [{ name: 'Satellites', data: [3] }],
                chart: { type: 'bar', height: 280, fontFamily: globalFont, toolbar: { show: false } },
                plotOptions: { bar: { borderRadius: 4, columnWidth: '35%' } },
                colors: ['#206bc4'],
                xaxis: { categories: ['Indonesia'] },
                grid: { strokeDashArray: 4 },
                dataLabels: { enabled: false }
            };
            new ApexCharts(document.querySelector("#chart-sat-country"), optionsSatCountry).render();

            // --- 4. CHART GROUND STATIONS BY COUNTRY (Horizontal Bar Chart) ---
            var optionsGsCountry = {
                series: [{ name: 'Ground Stations', data: [3, 1, 1, 1] }],
                chart: { type: 'bar', height: 280, fontFamily: globalFont, toolbar: { show: false } },
                plotOptions: { bar: { borderRadius: 4, horizontal: true, barHeight: '45%' } },
                colors: ['#d63939'],
                xaxis: { categories: ['Indonesia', 'United States', 'Germany', 'Japan'] },
                grid: { strokeDashArray: 4 },
                dataLabels: { enabled: false }
            };
            new ApexCharts(document.querySelector("#chart-gs-country"), optionsGsCountry).render();

        });
    </script>
@endpush
