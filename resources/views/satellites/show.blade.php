@extends('layouts.admin')

@section('title', 'Detail Satelit - ' . ($satellite->name ?? 'Data'))
@section('page-title', 'Detail Satelit')
@section('page-subtitle', 'Pemantauan spesifikasi dan orbit satelit real-time.')
@section('page-icon', 'fas fa-satellite')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <style>
        /* ==========================================
           KARTU PROFIL SATELIT (SUPER PREMIUM)
           ========================================== */
        .profile-card {
            border-radius: 20px;
            border: none;
            background: #ffffff;
            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.08) !important;
            overflow: hidden;
        }

        /* Gambar & Placeholder (Blueprint Style) */
        .profile-img-wrapper {
            position: relative;
            height: 240px;
            width: 100%;
        }
        .img-placeholder {
            height: 100%; width: 100%;
            background-color: #0f172a; /* Slate 900 */
            /* Membuat efek grid/kertas cetak biru (blueprint) */
            background-image:
                radial-gradient(circle at center, rgba(59,130,246,0.15) 0%, transparent 70%),
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 100% 100%, 20px 20px, 20px 20px;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
        }
        .img-placeholder i {
            color: #3b82f6;
            font-size: 3.5rem;
            margin-bottom: 1rem;
            filter: drop-shadow(0 0 12px rgba(59,130,246,0.6)); /* Efek Glowing */
        }
        .img-placeholder span {
            color: #94a3b8; font-size: 0.75rem; font-weight: 800; letter-spacing: 3px;
        }

        /* Header Profil */
        .profile-header {
            padding: 2rem 1.5rem 1.5rem;
            text-align: center;
            background: #ffffff;
            position: relative;
        }
        .profile-header::after {
            content: ''; position: absolute; bottom: 0; left: 10%; right: 10%;
            height: 1px; background: linear-gradient(90deg, transparent, #e2e8f0, transparent);
        }

        /* Container Spesifikasi */
        .spec-container {
            padding: 1.5rem;
            background: #f8fafc; /* Latar abu-abu sangat muda */
        }

        /* Kotak Item Spesifikasi (Floating Items) */
        .spec-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.9rem 1.25rem; margin-bottom: 0.75rem;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            transition: all 0.25s ease;
        }
        .spec-item:last-child { margin-bottom: 0; }
        .spec-item:hover {
            border-color: #cbd5e1;
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        }

        /* Tipografi Spesifikasi */
        .spec-label {
            font-size: 0.7rem; color: #64748b; font-weight: 800;
            text-transform: uppercase; letter-spacing: 0.5px;
            display: flex; align-items: center; gap: 10px;
        }
        .spec-label i { color: #94a3b8; font-size: 0.9rem; }
        .spec-value {
            font-size: 0.85rem; color: #0f172a; font-weight: 700; text-align: right;
        }

        /* ==========================================
           ELEMEN LAINNYA (HUD, MAP, TLE)
           ========================================== */
        .hud-card {
            background: #ffffff; border-radius: 12px; border: 1px solid rgba(0,0,0,0.05);
            padding: 1.25rem; display: flex; align-items: center; gap: 1rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .hud-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
        .hud-icon { width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
        .hud-title { font-size: 0.65rem; color: #64748b; font-weight: 800; letter-spacing: 1px; margin-bottom: 0.2rem; }
        .hud-data { font-size: 1.2rem; font-family: 'JetBrains Mono', monospace; font-weight: 800; color: #1e293b; line-height: 1; }

        .map-container { border-radius: 16px; overflow: hidden; border: 1px solid rgba(0,0,0,0.05); }
        .map-header { background: #0f172a; color: #ffffff; }
        .leaflet-container { background-color: #0f172a; z-index: 1; }

        .custom-sat-marker {
            border-radius: 50%; border: 2px solid #ffffff;
            box-shadow: 0 0 12px currentColor, 0 0 4px currentColor inset; position: relative;
        }
        .custom-sat-marker::after {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            border-radius: 50%; border: 1px solid currentColor;
            animation: radar-ripple 2s infinite linear; opacity: 0;
        }
        @keyframes radar-ripple { 0% { transform: scale(0.5); opacity: 0.8; } 100% { transform: scale(1.5); opacity: 0; } }

        .tle-box {
            background: #0f172a; border-radius: 12px; padding: 1.25rem 1.5rem;
            border: 1px solid rgba(255,255,255,0.1); overflow-x: auto;
            box-shadow: inset 0 4px 15px rgba(0,0,0,0.3);
        }
        .tle-lbl { color: #64748b; font-size: 0.65rem; font-weight: 800; letter-spacing: 1.5px; margin-bottom: 0.2rem; }
        .tle-txt { font-family: 'JetBrains Mono', 'Courier New', monospace; font-size: 0.85rem; letter-spacing: 1px; white-space: nowrap; }
    </style>
@endpush

@section('content')

<div class="row mb-3 align-items-center d-print-none">
    <div class="col-auto">
        <a href="{{ route('satellites.index') }}" class="btn btn-white fw-bold shadow-sm rounded-3 text-secondary border">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>
    <div class="col-auto ms-auto">
        <a href="{{ route('satellites.edit', $satellite->id ?? 1) }}" class="btn btn-primary fw-bold shadow-sm rounded-3">
            <i class="fas fa-edit me-2"></i> Edit Data
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="profile-card h-100 d-flex flex-column">

            <div class="profile-img-wrapper">
                @if(isset($satellite->image) && $satellite->image)
                    <img src="{{ asset('storage/' . $satellite->image) }}" alt="Gambar" class="w-100 h-100 object-fit-cover">
                @else
                    <div class="img-placeholder">
                        <i class="fas fa-satellite-dish"></i>
                        <span>TANPA VISUAL</span>
                    </div>
                @endif
            </div>

            <div class="profile-header">
                <h2 class="fw-bolder m-0 text-dark text-uppercase mb-3" style="letter-spacing: -0.5px;">{{ $satellite->name ?? 'LAPAN-TUBSAT' }}</h2>
                @if(($satellite->status ?? 'active') == 'active')
                    <span class="badge bg-green-lt px-3 py-2 rounded-pill fw-bold" style="letter-spacing: 0.5px;">
                        <i class="fas fa-circle blink-soft me-1"></i> STATUS: OPERASIONAL
                    </span>
                @else
                    <span class="badge bg-red-lt px-3 py-2 rounded-pill fw-bold" style="letter-spacing: 0.5px;">
                        STATUS: OFFLINE
                    </span>
                @endif
            </div>

            <div class="spec-container flex-grow-1">

                <div class="spec-item">
                    <div class="spec-label"><i class="fas fa-flag"></i> Negara Asal</div>
                    <div class="spec-value">{{ $satellite->country ?? 'Indonesia' }}</div>
                </div>

                <div class="spec-item">
                    <div class="spec-label"><i class="fas fa-calendar-alt"></i> Tgl Peluncuran</div>
                    <div class="spec-value">{{ isset($satellite->launch_date) ? \Carbon\Carbon::parse($satellite->launch_date)->format('d M Y') : '10 Jan 2007' }}</div>
                </div>

                <div class="spec-item">
                    <div class="spec-label"><i class="fas fa-globe"></i> Tipe Orbit</div>
                    <div class="spec-value"><span class="badge bg-purple-lt px-2">{{ $satellite->orbit_type ?? 'LEO' }}</span></div>
                </div>

                <div class="spec-item">
                    <div class="spec-label"><i class="fas fa-broadcast-tower"></i> Stasiun Bumi</div>
                    <div class="spec-value text-truncate" style="max-width: 140px;" title="{{ $satellite->groundStation->name ?? 'LAPAN Station Rumpin' }}">
                        {{ $satellite->groundStation->name ?? 'LAPAN Station Rumpin' }}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-lg-8 mb-4">

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="hud-card shadow-sm">
                    <div class="hud-icon bg-blue-lt text-blue"><i class="fas fa-ruler-vertical"></i></div>
                    <div>
                        <div class="hud-title">LATITUDE</div>
                        <div class="hud-data" id="val-lat">-77.407°</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hud-card shadow-sm">
                    <div class="hud-icon bg-green-lt text-green"><i class="fas fa-ruler-horizontal"></i></div>
                    <div>
                        <div class="hud-title">LONGITUDE</div>
                        <div class="hud-data" id="val-lng">-134.82°</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="hud-card shadow-sm">
                    <div class="hud-icon bg-orange-lt text-orange"><i class="fas fa-location-arrow"></i></div>
                    <div>
                        <div class="hud-title">ALTITUDE</div>
                        <div class="hud-data" id="val-alt">632.8 km</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm map-container mb-3 border-0">
            <div class="card-header map-header py-3 d-flex justify-content-between align-items-center border-0">
                <h3 class="card-title fw-bold m-0 fs-4 text-white">
                    <i class="fas fa-satellite-dish text-warning me-2"></i> Real-Time Orbit
                </h3>
                <button id="btn-recenter" class="btn btn-sm btn-outline-light fw-bold border-white">
                    <i class="fas fa-crosshairs me-1"></i> Pusatkan
                </button>
            </div>
            <div class="card-body p-0">
                <div id="satellite-map" style="height: 380px; width: 100%;"></div>
            </div>
        </div>

        <div class="tle-box">
            <div class="d-flex align-items-center mb-3">
                <div class="bg-primary rounded-circle me-2" style="width: 8px; height: 8px; box-shadow: 0 0 8px #3b82f6;"></div>
                <h4 class="text-white m-0 fw-bold" style="font-size: 0.85rem; letter-spacing: 0.5px;">RAW TELEMETRY (TLE)</h4>
            </div>

            <div class="mb-2">
                <div class="tle-lbl">LINE 1</div>
                <div class="tle-txt text-info">{{ $satellite->tle_line1 ?? '1 29709U 07001A   26132.90143921  .00000627  00000-0  12345-4 0  9991' }}</div>
            </div>
            <div>
                <div class="tle-lbl">LINE 2</div>
                <div class="tle-txt text-success">{{ $satellite->tle_line2 ?? '2 29709  97.9000  12.3456 0012345 123.4567 345.6789 14.1234567812345' }}</div>
            </div>
        </div>

    </div>
</div>

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var map = L.map('satellite-map', {
                center: [-77.4079, -134.8268],
                zoom: 4,
                zoomAnimation: true
            });

            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; Esri', maxZoom: 18
            }).addTo(map);

            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 18, pane: 'markerPane'
            }).addTo(map);

            var satColor = "#f59e0b";
            var customIcon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div class="custom-sat-marker" style="background-color: ${satColor}; color: ${satColor}; width: 14px; height: 14px;"></div>`,
                iconSize: [14, 14], iconAnchor: [7, 7]
            });

            var marker = L.marker([-77.4079, -134.8268], {icon: customIcon}).addTo(map);

            document.getElementById('btn-recenter').addEventListener('click', function() {
                var currentPos = marker.getLatLng();
                map.flyTo(currentPos, 4, { animate: true, duration: 1.5 });
            });

            setTimeout(() => { map.invalidateSize(); }, 500);
        });
    </script>
@endpush
