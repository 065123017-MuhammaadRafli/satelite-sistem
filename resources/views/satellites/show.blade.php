@extends('layouts.admin')

@section('title', 'Detail Satelit - ' . $satellite->name)
@section('page-title', 'Spesifikasi & Telemetri Armada')
@section('page-subtitle', 'Informasi spesifikasi teknis, lintasan orbit, data TLE, dan stasiun bumi pengontrol.')
@section('page-icon', 'fas fa-satellite')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
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

        /* Core Telemetry Display Grid */
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

        /* TLE Terminal */
        .terminal-header {
            background: #1e293b;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            padding: 0.75rem 1.25rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .terminal-body {
            background: #0f172a !important;
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            padding: 1.25rem;
            font-family: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace;
            font-size: 0.9rem;
            border: 1px solid #1e293b;
            border-top: none;
        }
        .terminal-row {
            display: flex;
            align-items: flex-start;
            line-height: 1.6;
            color: #22c55e !important;
            text-shadow: 0 0 8px rgba(34, 197, 94, 0.3);
        }
        .terminal-num {
            color: #475569;
            font-weight: 700;
            margin-right: 1.25rem;
            user-select: none;
        }
        .terminal-dot {
            width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 5px;
        }

        /* Status Transmitting Glass Badge */
        .status-glowing-box {
            background: #0f172a;
            border-radius: 14px;
            padding: 1.5rem;
            border: 1px solid #1e293b;
        }
        .pulse-glow-active {
            background: rgba(34, 197, 94, 0.12);
            color: #22c55e !important;
            border: 1px solid rgba(34, 197, 94, 0.25);
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 0 15px rgba(34, 197, 94, 0.1);
        }
        .pulse-glow-inactive {
            background: rgba(239, 68, 68, 0.12);
            color: #ef4444 !important;
            border: 1px solid rgba(239, 68, 68, 0.25);
            font-weight: 700;
            letter-spacing: 0.5px;
            box-shadow: 0 0 15px rgba(239, 68, 68, 0.1);
        }

        /* Map Container Customization */
        .map-wrapper {
            border-radius: 0 0 16px 16px;
            overflow: hidden;
            background: #0f172a;
        }
        #sat-map {
            width: 100%;
            height: 380px;
        }

        /* Ikon Satelit Map */
        .custom-sat-marker {
            border-radius: 50%; border: 2px solid #ffffff;
            background-color: #38bdf8;
            box-shadow: 0 0 15px #38bdf8, 0 0 5px #38bdf8 inset;
            position: relative;
            width: 14px; height: 14px;
        }
        .custom-sat-marker::after {
            content: ''; position: absolute; top: -50%; left: -50%; width: 200%; height: 200%;
            border-radius: 50%; border: 1px solid #38bdf8;
            animation: radar-ripple 2s infinite linear; opacity: 0;
        }
        @keyframes radar-ripple {
            0% { transform: scale(0.5); opacity: 0.8; }
            100% { transform: scale(1.5); opacity: 0; }
        }
        .sat-label {
            background-color: rgba(15, 23, 42, 0.85); color: #ffffff;
            border: 1px solid rgba(255,255,255,0.2); border-radius: 6px;
            font-weight: 600; font-size: 12px; padding: 4px 8px;
            backdrop-filter: blur(4px); text-align: center;
        }
        .leaflet-tooltip-bottom.sat-label:before { border-bottom-color: rgba(15, 23, 42, 0.85); }
    </style>
@endpush

@section('content')
<div class="row mb-4 align-items-center g-3">
    <div class="col-6">
        <a href="{{ route('satellites.index') }}" class="btn btn-white btn-md fw-bold rounded-3 shadow-sm px-3">
            <i class="fas fa-arrow-left me-2 text-muted"></i> Kembali ke Daftar
        </a>
    </div>
    <div class="col-6 text-end">
        <a href="{{ route('satellites.edit', $satellite->id) }}" class="btn btn-warning btn-md fw-bold rounded-3 shadow-sm px-3">
            <i class="fas fa-edit me-2"></i> Konfigurasi Satelit
        </a>
    </div>
</div>

<div class="row row-cards g-4">

    <div class="col-12 col-xl-7">
        <div class="card space-bg-card h-100 mb-0">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                <div class="bg-blue-lt p-2 rounded-3 me-3 text-blue d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                    <i class="fas fa-chart-network fs-4"></i>
                </div>
                <h3 class="card-title fw-bold text-dark m-0">Matriks Telemetri & Informasi Utama</h3>
            </div>

            <div class="card-body p-3 p-md-4">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6">
                        <div class="metric-container">
                            <div class="metric-icon-box bg-blue-lt text-blue"><i class="fas fa-fingerprint"></i></div>
                            <div class="text-truncate">
                                <div class="text-muted small fw-medium text-uppercase tracking-wider" style="font-size:0.65rem;">Nama Armada</div>
                                <div class="fw-extrabold text-dark fs-3 text-truncate">{{ $satellite->name }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="metric-container">
                            <div class="metric-icon-box bg-purple-lt text-purple"><i class="fas fa-globe"></i></div>
                            <div class="text-truncate">
                                <div class="text-muted small fw-medium text-uppercase tracking-wider" style="font-size:0.65rem;">Negara Asal</div>
                                <div class="fw-bold text-dark fs-4 text-truncate">{{ $satellite->origin_country ?? 'Global' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="metric-container">
                            <div class="metric-icon-box bg-azure-lt text-azure"><i class="fas fa-ring"></i></div>
                            <div>
                                <div class="text-muted small fw-medium text-uppercase tracking-wider" style="font-size:0.65rem;">Kategori Orbit</div>
                                <div><span class="badge bg-azure text-white rounded-pill px-2 py-0.5 fw-bold font-monospace" style="font-size:0.7rem;">{{ $satellite->orbit_type ?? 'LEO' }}</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6">
                        <div class="metric-container">
                            <div class="metric-icon-box bg-indigo-lt text-indigo"><i class="fas fa-rocket"></i></div>
                            <div>
                                <div class="text-muted small fw-medium text-uppercase tracking-wider" style="font-size:0.65rem;">Waktu Luncur</div>
                                <div class="fw-bold text-dark font-monospace" style="font-size:0.85rem;">
                                    {{ $satellite->launch_date ? \Carbon\Carbon::parse($satellite->launch_date)->translatedFormat('d M Y') : 'N/A' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="metric-container" style="background: rgba(32, 107, 196, 0.02); border-color: rgba(32, 107, 196, 0.15);">
                            <div class="metric-icon-box bg-green-lt text-green"><i class="fas fa-broadcast-tower"></i></div>
                            <div class="text-truncate">
                                <div class="text-muted small fw-medium text-uppercase tracking-wider" style="font-size:0.65rem;">Simpul Stasiun Bumi Terikat</div>
                                <div class="fw-bold text-dark fs-4 text-truncate">
                                    {{ $satellite->groundStation->name ?? 'Tidak Terikat Stasiun Bumi' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-2">
                        <div class="text-muted small fw-bold text-uppercase tracking-wider" style="font-size:0.7rem;">
                            <i class="fas fa-terminal text-success me-1"></i> Konsol Aliran Data Two-Line Element (TLE)
                        </div>
                        <form action="{{ route('satellites.sync-tle', $satellite->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-white fw-bold shadow-sm rounded-2 py-1 w-100 w-sm-auto">
                                <i class="fas fa-sync-alt text-success me-1"></i> Sync Telemetry
                            </button>
                        </form>
                    </div>

                    <div class="shadow-sm overflow-hidden rounded-3">
                        <div class="terminal-header d-flex align-items-center">
                            <span class="terminal-dot bg-danger"></span>
                            <span class="terminal-dot bg-warning"></span>
                            <span class="terminal-dot bg-success"></span>
                            <span class="text-muted small font-monospace ms-2" style="font-size: 0.75rem; color:#94a3b8 !important;">norad_telemetry_stream.log</span>
                        </div>
                        <div class="terminal-body overflow-auto">
                            <div class="terminal-row text-nowrap">
                                <span class="terminal-num">01</span>
                                <span class="font-monospace">{{ $satellite->tle_line1 ?? 'NO_DATA_AVAILABLE' }}</span>
                            </div>
                            <div class="terminal-row mt-1 text-nowrap">
                                <span class="terminal-num">02</span>
                                <span class="font-monospace">{{ $satellite->tle_line2 ?? 'NO_DATA_AVAILABLE' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <div class="text-muted small fw-bold text-uppercase tracking-wider mb-2" style="font-size:0.7rem;">
                        <i class="fas fa-file-alt text-blue me-1"></i> Manifest & Deskripsi Operasional Misi
                    </div>
                    <div class="p-3 bg-light rounded-3 text-secondary border font-sans" style="line-height: 1.6; font-size: 0.9rem;">
                        @if($satellite->description)
                            {{ $satellite->description }}
                        @else
                            <span class="text-muted italic"><i class="fas fa-info-circle me-1"></i> Satelit ini beroperasi normal, namun operator belum memasukkan manifest catatan tambahan.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-5">
        <div class="row row-cards g-3">

            <div class="col-12">
                <div class="card space-bg-card mb-0" style="margin-bottom: 0 !important;">
                    <div class="card-body p-3 text-center">
                        <div class="text-muted small fw-bold text-uppercase tracking-wider mb-2" style="font-size:0.65rem;">Kondisi Subsistem Operasional</div>
                        <div class="status-glowing-box text-center p-3">
                            @if(strtolower($satellite->status) == 'active' || strtolower($satellite->status) == 'aktif')
                                <div class="badge pulse-glow-active rounded-pill px-3 py-2 fs-4 d-inline-flex align-items-center justify-content-center w-100">
                                    <i class="fas fa-satellite-dish fa-spin me-2 fs-5"></i> TRANSMITTING / ACTIVE
                                </div>
                            @else
                                <div class="badge pulse-glow-inactive rounded-pill px-3 py-2 fs-4 d-inline-flex align-items-center justify-content-center w-100">
                                    <i class="fas fa-power-off me-2 fs-5"></i> DEACTIVATED / OFFLINE
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card space-bg-card mb-0" style="margin-bottom: 0 !important; border-radius: 16px; overflow: hidden;">
                    <div class="card-header py-3 border-bottom d-flex align-items-center justify-content-between" style="background: #1e293b;">
                        <h3 class="card-title fw-bold text-white m-0">
                            <i class="fas fa-globe-space text-blue me-2"></i> Live Orbit Tracker
                        </h3>
                        <button id="btn-recenter" class="btn btn-sm btn-outline-info fw-bold py-1 px-2 border-0" title="Kembali ke fokus satelit">
                            <i class="fas fa-crosshairs me-1"></i> Recenter
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="map-wrapper">
                            @if($satellite->tle_line1 && $satellite->tle_line2)
                                <div id="sat-map"></div>
                            @else
                                <div class="d-flex flex-column align-items-center justify-content-center bg-dark text-muted" style="height: 380px;">
                                    <i class="fas fa-satellite-slash fa-3x mb-3 opacity-50"></i>
                                    <span class="fw-bold text-white">Data TLE Tidak Tersedia</span>
                                    <span class="small mt-1">Harap sinkronisasi TLE terlebih dahulu untuk melihat radar orbit.</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top p-2 text-center text-muted font-monospace fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <span class="text-danger me-2">LAT: <span id="live-lat">--.---°</span></span>
                        <span class="text-primary me-2">LNG: <span id="live-lng">--.---°</span></span>
                        <span class="text-success">ALT: <span id="live-alt">--- km</span></span>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card space-bg-card mb-0" style="margin-bottom: 0 !important;">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h3 class="card-title fw-bold text-dark m-0">
                            <i class="fas fa-camera text-primary me-2"></i> Dokumentasi Visual
                        </h3>
                    </div>
                    <div class="card-body p-3 text-center">
                        @if($satellite->image)
                            <div class="img-thumbnail border-0 p-0 shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                <img src="{{ asset('storage/' . $satellite->image) }}" class="img-fluid w-100" style="max-height: 180px; object-fit: cover;" alt="Foto Satelit">
                            </div>
                        @else
                            <div class="py-4 border border-2 border-dashed rounded-3 bg-light d-flex flex-column align-items-center justify-content-center text-muted" style="min-height: 150px;">
                                <i class="fas fa-space-shuttle fa-2x mb-2 opacity-30"></i>
                                <span class="small fw-bold">Belum Ada Gambar Terunggah</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

@push('scripts')
    @if($satellite->tle_line1 && $satellite->tle_line2)
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/satellite.js/4.0.0/satellite.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Data TLE & Nama dari Laravel
            var tle1 = "{{ $satellite->tle_line1 }}";
            var tle2 = "{{ $satellite->tle_line2 }}";
            var satName = "{{ $satellite->name }}";

            // Inisiasi Peta Leaflet dengan Dark Mode Satelit Map
            var map = L.map('sat-map', {
                center: [0, 0],
                zoom: 2,
                preferCanvas: true,
                zoomControl: false // Sembunyikan zoom bawaan biar rapi
            });
            L.control.zoom({ position: 'topleft' }).addTo(map);

            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; Esri', maxZoom: 18, noWrap: false
            }).addTo(map);
            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 18, pane: 'markerPane', noWrap: false
            }).addTo(map);

            try {
                // Inisiasi Kalkulasi SGP4 Orbit
                const satrec = satellite.twoline2satrec(tle1, tle2);

                // 1. Gambar Garis Lintasan (Prediksi 50 Menit Ke Depan & Belakang)
                var orbitPoints = [];
                var baseTime = new Date();
                for (let i = -50; i <= 50; i += 2) {
                    let futureTime = new Date(baseTime.getTime() + i * 60000);
                    let posVel = satellite.propagate(satrec, futureTime);
                    let gmst = satellite.gstime(futureTime);
                    if (posVel.position) {
                        let posGd = satellite.eciToGeodetic(posVel.position, gmst);
                        let pLat = satellite.degreesLat(posGd.latitude);
                        let pLng = satellite.degreesLong(posGd.longitude);
                        orbitPoints.push([pLat, pLng]);
                    }
                }

                // Polyline orbit warna cyan neon khas UI modern
                var orbitLine = L.polyline(orbitPoints, {
                    color: '#38bdf8', weight: 2, opacity: 0.7, dashArray: '4, 10'
                }).addTo(map);

                // 2. Buat Marker Objek Satelit
                var customIcon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div class="custom-sat-marker"></div>`,
                    iconSize: [14, 14], iconAnchor: [7, 7]
                });
                var markerObj = L.marker([0, 0], {icon: customIcon}).addTo(map);
                markerObj.bindTooltip(satName, { permanent: true, direction: 'bottom', className: 'sat-label', offset: [0, 10] });

                // 3. Mesin Animasi Update Koordinat Live
                function updateSatPosition() {
                    let now = new Date();
                    let posVel = satellite.propagate(satrec, now);
                    let gmst = satellite.gstime(now);

                    if (posVel.position) {
                        let posGd = satellite.eciToGeodetic(posVel.position, gmst);
                        let currentLat = satellite.degreesLat(posGd.latitude);
                        let currentLng = satellite.degreesLong(posGd.longitude);
                        let currentAlt = posGd.height;

                        // Pindahkan marker di peta
                        markerObj.setLatLng([currentLat, currentLng]);

                        // Update teks live telemetry di bawah peta (HTML DOM)
                        document.getElementById('live-lat').innerText = currentLat.toFixed(3) + '°';
                        document.getElementById('live-lng').innerText = currentLng.toFixed(3) + '°';
                        document.getElementById('live-alt').innerText = currentAlt.toFixed(1) + ' km';
                    }
                    // Loop tiap detik
                    setTimeout(() => requestAnimationFrame(updateSatPosition), 1000);
                }

                // Set Posisi Peta Awal agar langsung menyorot satelit
                let initPosVel = satellite.propagate(satrec, new Date());
                let initGmst = satellite.gstime(new Date());
                let initPosGd = satellite.eciToGeodetic(initPosVel.position, initGmst);
                let initLat = satellite.degreesLat(initPosGd.latitude);
                let initLng = satellite.degreesLong(initPosGd.longitude);
                map.setView([initLat, initLng], 3);

                // Jalankan Animasi
                updateSatPosition();

                // Tombol Recenter
                document.getElementById('btn-recenter').addEventListener('click', function() {
                    let btnNow = new Date();
                    let btnPosVel = satellite.propagate(satrec, btnNow);
                    let btnGmst = satellite.gstime(btnNow);
                    if(btnPosVel.position){
                        let bLat = satellite.degreesLat(satellite.eciToGeodetic(btnPosVel.position, btnGmst).latitude);
                        let bLng = satellite.degreesLong(satellite.eciToGeodetic(btnPosVel.position, btnGmst).longitude);
                        map.flyTo([bLat, bLng], 3, { animate: true, duration: 1.5 });
                    }
                });

            } catch (error) {
                console.error("Gagal merender orbit satelit:", error);
            }

            // Fix masalah Leaflet map blank (abu-abu)
            setTimeout(function() { map.invalidateSize(); }, 300);
        });
    </script>
    @endif
@endpush
