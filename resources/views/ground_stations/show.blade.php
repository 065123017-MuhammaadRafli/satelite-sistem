@extends('layouts.admin')

@section('title', 'Detail Stasiun - ' . $groundStation->name)
@section('page-title', 'Pusat Kendali Infrastruktur Bumi')
@section('page-subtitle', 'Informasi spesifikasi teknis, koordinat geografis, dan jangkauan operasional stasiun bumi.')
@section('page-icon', 'fas fa-broadcast-tower')

@section('content')
<style>
    /* Global Control Theme Overrides */
    .space-bg-card {
        background: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 16px !important;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03) !important;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
    }

    /* Core Metric Display Grid */
    .metric-container {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        height: 100%;
    }
    .metric-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    /* FIX MAP CONTAINER: Mengunci ukuran tinggi agar peta tidak abu-abu polos */
    .map-wrapper {
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: inset 0 0 10px rgba(0,0,0,0.05);
        background: #0f172a; /* Latar belakang gelap agar transisi peta halus */
    }
    #map {
        width: 100%;
        height: 480px; /* Dikunci ke ukuran absolut agar Leaflet langsung merender jelas */
    }

    /* GPS Coordinate Highlight */
    .gps-value {
        font-family: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace;
        color: #3b82f6 !important;
        font-weight: 700;
        letter-spacing: -0.5px;
    }
</style>

<div class="row mb-4 align-items-center g-3">
    <div class="col-6">
        <a href="{{ route('ground-stations.index') }}" class="btn btn-white btn-md fw-bold rounded-3 shadow-sm px-3">
            <i class="fas fa-arrow-left me-2 text-muted"></i> Kembali ke Daftar
        </a>
    </div>
    <div class="col-6 text-end">
        <a href="{{ route('ground-stations.edit', $groundStation->id) }}" class="btn btn-warning btn-md fw-bold rounded-3 shadow-sm px-3">
            <i class="fas fa-edit me-2"></i> Edit Stasiun
        </a>
    </div>
</div>

<div class="row row-cards g-4">

    <div class="col-12 col-lg-5">
        <div class="card space-bg-card h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                <div class="bg-blue-lt p-2 rounded-3 me-3 text-blue d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                    <i class="fas fa-info-circle fs-4"></i>
                </div>
                <h3 class="card-title fw-bold text-dark m-0">Informasi & Identitas Stasiun</h3>
            </div>

            <div class="card-body p-3 p-md-4">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="metric-container">
                            <div class="metric-icon-box bg-blue-lt text-blue"><i class="fas fa-broadcast-tower"></i></div>
                            <div class="text-truncate">
                                <div class="text-muted small fw-bold text-uppercase tracking-wider" style="font-size:0.65rem;">Nama Stasiun Bumi</div>
                                <div class="fw-extrabold text-dark fs-3 text-truncate">{{ $groundStation->name }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="metric-container">
                            <div class="metric-icon-box bg-purple-lt text-purple"><i class="fas fa-flag"></i></div>
                            <div class="text-truncate">
                                <div class="text-muted small fw-bold text-uppercase tracking-wider" style="font-size:0.65rem;">Negara</div>
                                <div class="fw-bold text-dark fs-4 text-truncate">{{ $groundStation->country }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="metric-container">
                            <div class="metric-icon-box bg-azure-lt text-azure"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="text-truncate">
                                <div class="text-muted small fw-bold text-uppercase tracking-wider" style="font-size:0.65rem;">Wilayah</div>
                                <div class="fw-bold text-dark fs-4 text-truncate">{{ $groundStation->location }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="metric-container">
                            <div class="metric-icon-box bg-indigo-lt text-indigo"><i class="fas fa-arrows-alt-v"></i></div>
                            <div>
                                <div class="text-muted small fw-bold text-uppercase tracking-wider" style="font-size:0.65rem;">Garis Lintang (Lat)</div>
                                <div class="gps-value fs-4">{{ number_format($groundStation->latitude, 6) }}°</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="metric-container">
                            <div class="metric-icon-box bg-indigo-lt text-indigo"><i class="fas fa-arrows-alt-h"></i></div>
                            <div>
                                <div class="text-muted small fw-bold text-uppercase tracking-wider" style="font-size:0.65rem;">Garis Bujur (Lng)</div>
                                <div class="gps-value fs-4">{{ number_format($groundStation->longitude, 6) }}°</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="metric-container" style="background: rgba(32, 107, 196, 0.02); border-color: rgba(32, 107, 196, 0.15);">
                            <div class="metric-icon-box bg-green-lt text-green"><i class="fas fa-link"></i></div>
                            <div>
                                <div class="text-muted small fw-bold text-uppercase tracking-wider" style="font-size:0.65rem;">Satelit Terhubung Saat Ini</div>
                                <div class="fw-bold text-dark fs-3">
                                    {{ $groundStation->satellites_count ?? $groundStation->satellites->count() }} <span class="text-muted fs-5 fw-medium">Unit Armada</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <div class="text-muted small fw-bold text-uppercase tracking-wider mb-2" style="font-size:0.7rem;">
                        <i class="fas fa-align-left text-blue me-1"></i> Catatan & Deskripsi Tambahan
                    </div>
                    <div class="p-3 bg-light rounded-3 text-secondary border fs-4" style="line-height: 1.6;">
                        @if($groundStation->description)
                            {{ $groundStation->description }}
                        @else
                            <span class="text-muted italic"><i class="fas fa-info-circle me-1"></i> Tidak ada deskripsi tambahan untuk stasiun bumi ini.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-7">
        <div class="card space-bg-card h-100">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center">
                    <div class="bg-red-lt p-2 rounded-3 me-3 text-red d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                        <i class="fas fa-map-marked-alt fs-4"></i>
                    </div>
                    <h3 class="card-title fw-bold text-dark m-0">Visualisasi Lokasi Infrastruktur</h3>
                </div>
                <span class="badge bg-green-lt text-green rounded-pill px-2 py-0.5 fw-bold" style="font-size: 0.7rem;">LIVE VIEW</span>
            </div>
            <div class="card-body p-3">
                <div class="map-wrapper">
                    <div id="map"></div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var lat = {{ $groundStation->latitude }};
        var lng = {{ $groundStation->longitude }};
        var stationName = "{{ $groundStation->name }}";

        // Inisialisasi Peta (Gunakan zoom level 12 agar peta langsung fokus tajam ke lokasi stasiun)
        var map = L.map('map', {
            center: [lat, lng],
            zoom: 12,
            zoomAnimation: true,
            preferCanvas: true
        });

        // Menggunakan tile layer resolusi tinggi yang bersih
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{y}/{x}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        // Custom Marker Glowing Biru Sesuai Tema Astra Link
        var customIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `
                <div style="background-color: #3b82f6; width: 32px; height: 32px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 15px rgba(59, 130, 246, 0.6); display: flex; align-items: center; justify-content: center; color: white;">
                    <i class="fas fa-broadcast-tower" style="font-size: 13px;"></i>
                </div>`,
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        // Tampilkan Marker dan Popup teks secara otomatis saat halaman dibuka
        var marker = L.marker([lat, lng], {icon: customIcon}).addTo(map)
            .bindPopup("<div style='font-family:sans-serif; padding:2px;'><b style='font-size:13px; color:#1e293b;'>" + stationName + "</b><br><span style='color:#22c55e; font-weight:bold; font-size:11px;'><i class='fas fa-circle me-1' style='font-size:8px;'></i> Ground Station Active</span></div>")
            .openPopup();

        // FIX TRICK: Paksa Leaflet menghitung ulang ukuran kontainer agar render benua langsung keluar jelas
        setTimeout(function() {
            map.invalidateSize();
        }, 100);
    });
</script>
@endpush
