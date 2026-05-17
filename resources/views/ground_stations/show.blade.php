@extends('layouts.admin')

@section('title', 'Detail Stasiun Bumi - ' . $groundStation->name)
@section('page-title', 'Detail Stasiun Bumi')
@section('page-subtitle', 'Informasi lengkap dan lokasi stasiun bumi di peta.')
@section('page-icon', 'fas fa-broadcast-tower')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <style>
        .leaflet-container {
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
            z-index: 1;
        }
        /* Desain Ikon Marker Stasiun Bumi (Hijau Tabler) */
        .gs-marker {
            background-color: #2fb344;
            border-radius: 50%;
            border: 2px solid white;
            box-shadow: 0 0 10px rgba(0,0,0,0.6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }
    </style>
@endpush

@section('content')
<div class="row mb-4 align-items-center d-print-none">
    <div class="col-auto">
        <a href="{{ route('ground-stations.index') }}" class="btn btn-white fw-bold shadow-sm rounded-3 text-secondary">
            <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
        </a>
    </div>
    <div class="col-auto ms-auto">
        <a href="{{ route('ground-stations.edit', $groundStation) }}" class="btn btn-warning fw-bold shadow-sm rounded-3">
            <i class="fas fa-edit me-2"></i> Edit Stasiun
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-5 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white py-3 border-bottom">
                <h3 class="card-title fw-bold text-dark">
                    <i class="fas fa-info-circle text-primary me-2"></i> Informasi Stasiun
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-vcenter table-striped mb-0 fs-5">
                    <tbody>
                        <tr>
                            <td class="w-40 text-muted fw-bold py-3 px-4">Nama Stasiun</td>
                            <td class="fw-bold text-dark">{{ $groundStation->name }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold py-3 px-4">Negara</td>
                            <td class="text-secondary">{{ $groundStation->country }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold py-3 px-4">Lokasi Wilayah</td>
                            <td class="text-secondary">{{ $groundStation->location }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold py-3 px-4">Latitude</td>
                            <td class="font-monospace text-primary fw-bold">{{ $groundStation->latitude }}°</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold py-3 px-4">Longitude</td>
                            <td class="font-monospace text-primary fw-bold">{{ $groundStation->longitude }}°</td>
                        </tr>
                        <tr>
                            <td class="text-muted fw-bold py-3 px-4">Satelit Terhubung</td>
                            <td>
                                <span class="badge bg-blue-lt px-3 py-1 fs-6 rounded-pill">
                                    {{ $groundStation->satellites ? $groundStation->satellites->count() : 0 }} Satelit
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            @if($groundStation->description)
            <div class="card-footer bg-light p-4 border-top">
                <div class="text-muted fw-bold mb-2"><i class="fas fa-align-left me-1"></i> Deskripsi Tambahan:</div>
                <p class="mb-0 text-secondary" style="line-height: 1.6;">{{ $groundStation->description }}</p>
            </div>
            @endif
        </div>
    </div>

    <div class="col-lg-7 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-dark text-white py-3 d-flex justify-content-between align-items-center rounded-top-3">
                <h3 class="card-title fw-bold m-0 fs-4">
                    <i class="fas fa-map-marker-alt text-danger me-2"></i> Peta Lokasi Infrastruktur
                </h3>
                <button id="btn-recenter" class="btn btn-sm btn-outline-light fw-bold">
                    <i class="fas fa-crosshairs me-1"></i> Fokus Lokasi
                </button>
            </div>
            <div class="card-body p-0 position-relative">
                <div id="map" style="width: 100%; height: 100%; min-height: 500px; background-color: #0f172a;"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Mengambil titik koordinat stasiun bumi dari database
            var lat = {{ $groundStation->latitude ?? 0 }};
            var lng = {{ $groundStation->longitude ?? 0 }};
            var stationName = "{{ $groundStation->name }}";

            // Inisialisasi peta di koordinat stasiun bumi dengan zoom dekat (level 7)
            var map = L.map('map').setView([lat, lng], 7);

            // Layer Peta Satelit (Esri World Imagery)
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; Esri',
                maxZoom: 18
            }).addTo(map);

            // Layer Label/Teks Negara
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 18,
                pane: 'markerPane'
            }).addTo(map);

            // Ikon Kustom untuk Ground Station (Tower)
            var gsIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div class="gs-marker" style="width: 32px; height: 32px;"><i class="fas fa-broadcast-tower"></i></div>`,
                iconSize: [32, 32],
                iconAnchor: [16, 16]
            });

            // Tambahkan Marker ke peta
            var marker = L.marker([lat, lng], {icon: gsIcon}).addTo(map)
                .bindTooltip(`<b>${stationName}</b><br><span class="text-success small">Ground Station Active</span>`, {
                    permanent: true,
                    direction: 'top',
                    offset: [0, -15],
                    className: 'bg-white text-dark border-0 shadow-sm fw-bold px-3 py-2 text-center'
                });

            // Tombol Recenter
            document.getElementById('btn-recenter').addEventListener('click', function() {
                map.flyTo([lat, lng], 7, { animate: true, duration: 1.5 });
            });

            // Mencegah error render Leaflet di dalam Tabler Card
            setTimeout(function() { map.invalidateSize(); }, 300);
        });
    </script>
@endpush
