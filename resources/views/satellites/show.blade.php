@extends('layouts.admin')

@section('title', 'Detail Spesifikasi Satelit')
@section('page-title', 'Spesifikasi & Telemetri Armada')
@section('page-subtitle', 'Informasi spesifikasi teknis, lintasan orbit, data TLE, dan stasiun bumi pengontrol.')
@section('page-icon', 'fas fa-satellite')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<style>
    /* Styling Card Modern & Clean */
    .glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }

    /* Box Info Mini untuk Grid Sebelah Foto */
    .info-box-mini {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 14px;
        background-color: #fcfcfd;
        display: flex;
        align-items: center;
        transition: all 0.2s ease;
        height: 100%;
    }
    .info-box-mini:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.04);
        background-color: #ffffff;
    }

    .info-icon-mini {
        width: 38px; height: 38px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 12px; flex-shrink: 0;
        font-size: 1rem;
    }

    /* Pembungkus Foto Satelit Modern (Placeholder Siber) */
    .satellite-image-wrapper {
        width: 100%;
        height: 100%;
        min-height: 200px;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
        position: relative;
    }
    .satellite-image-wrapper img {
        width: 100%; height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    .satellite-image-wrapper:hover img {
        transform: scale(1.05);
    }

    .no-image-placeholder {
        background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
        width: 100%; height: 100%;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        color: #475569;
        text-align: center;
    }

    .img-overlay-badge {
        position: absolute;
        top: 10px; right: 10px;
        background: rgba(15, 23, 42, 0.75);
        backdrop-filter: blur(4px);
        color: white;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.65rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        border: 1px solid rgba(255,255,255,0.1);
    }

    /* Box Ground Station Bawah Grid */
    .info-box-wide {
        border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px;
        background-color: #fcfcfd; display: flex; align-items: center;
        border-left: 4px solid #22c55e;
    }

    /* TLE Terminal Console */
    .terminal-console {
        background-color: #0f172a; border-radius: 12px; border: 1px solid #1e293b;
        overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .terminal-header {
        background-color: #1e293b; padding: 10px 16px; display: flex; align-items: center; border-bottom: 1px solid #334155;
    }
    .mac-dots { display: flex; gap: 6px; margin-right: 14px; }
    .mac-dot { width: 12px; height: 12px; border-radius: 50%; }
    .dot-red { background-color: #ef4444; }
    .dot-yellow { background-color: #eab308; }
    .dot-green { background-color: #22c55e; }

    .terminal-title { color: #94a3b8; font-family: 'JetBrains Mono', monospace; font-size: 0.75rem; letter-spacing: 0.5px; }
    .terminal-body { padding: 16px; overflow-x: auto; }
    .tle-text { font-family: 'JetBrains Mono', 'Courier New', monospace; color: #4ade80; font-size: 0.85rem; line-height: 1.7; white-space: nowrap; }
    .tle-line-num { color: #475569; margin-right: 14px; user-select: none; }

    /* Custom Map Marker & Koordinat Box */
    .sat-marker-mini {
        width: 14px; height: 14px; background-color: #38bdf8;
        border-radius: 50%; border: 2px solid #ffffff; box-shadow: 0 0 10px #38bdf8;
    }
    .map-coord-box {
        background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 8px 0; flex: 1; text-align: center; margin: 0 4px;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('satellites.index') }}" class="btn btn-white shadow-sm border fw-bold px-3">
        <i class="fas fa-arrow-left me-2 text-muted"></i> Kembali ke Daftar
    </a>
    <a href="{{ route('satellites.edit', $satellite->id) }}" class="btn btn-warning shadow-sm fw-bold px-4 text-dark">
        <i class="fas fa-edit me-2"></i> Konfigurasi Satelit
    </a>
</div>

<div class="row align-items-stretch">
    <div class="col-lg-7 col-xl-7 mb-4">
        <div class="card glass-card h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-3">
                <h3 class="card-title fw-bold m-0 d-flex align-items-center fs-3">
                    <div class="bg-indigo-lt text-indigo rounded-3 p-2 me-3 shadow-sm"><i class="fas fa-microchip"></i></div>
                    Matriks Telemetri & Visual
                </h3>
            </div>

            <div class="card-body pt-0">

                <div class="row g-3 mb-3 align-items-stretch">

                    <div class="col-md-5">
                        <div class="satellite-image-wrapper shadow-sm">
                            @if($satellite->image)
                                <img src="{{ asset('storage/' . $satellite->image) }}" alt="Visual {{ $satellite->name }}">
                                <div class="img-overlay-badge bg-success border-success text-white">
                                    <i class="fas fa-check-circle me-1"></i> VISUAL AKTIF
                                </div>
                            @else
                                <div class="no-image-placeholder">
                                    <i class="fas fa-satellite fa-3x mb-2 opacity-25"></i>
                                    <div class="fw-bold mt-2 text-white opacity-50" style="font-size: 0.75rem; letter-spacing: 1px;">VISUAL BELUM TERSEDIA</div>
                                    <div class="small mt-1 opacity-25" style="font-size: 0.65rem;">(Unggah via Konfigurasi Satelit)</div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-7">
                        <div class="row g-2 h-100">
                            <div class="col-sm-6">
                                <div class="info-box-mini shadow-sm">
                                    <div class="info-icon-mini bg-blue-lt text-blue"><i class="fas fa-satellite"></i></div>
                                    <div class="overflow-hidden">
                                        <div class="text-muted fw-bold" style="font-size: 0.6rem; letter-spacing: 0.5px;">NAMA ARMADA</div>
                                        <div class="fw-bolder text-dark text-truncate fs-5" style="line-height: 1.2;">{{ $satellite->name }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="info-box-mini shadow-sm">
                                    <div class="info-icon-mini bg-pink-lt text-pink"><i class="fas fa-globe-americas"></i></div>
                                    <div class="overflow-hidden">
                                        <div class="text-muted fw-bold" style="font-size: 0.6rem; letter-spacing: 0.5px;">NEGARA ASAL</div>
                                        <div class="fw-bolder text-dark text-truncate fs-5" style="line-height: 1.2;">{{ $satellite->country ?? 'Global' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="info-box-mini shadow-sm">
                                    <div class="info-icon-mini bg-cyan-lt text-cyan"><i class="fas fa-ring"></i></div>
                                    <div class="overflow-hidden">
                                        <div class="text-muted fw-bold" style="font-size: 0.6rem; letter-spacing: 0.5px;">TIPE ORBIT</div>
                                        <div class="mt-1">
                                            <span class="badge bg-blue text-white px-2 py-1 shadow-sm" style="font-size: 0.65rem;">{{ $satellite->orbit_type ?? 'LEO' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="info-box-mini shadow-sm">
                                    <div class="info-icon-mini bg-purple-lt text-purple"><i class="fas fa-rocket"></i></div>
                                    <div class="overflow-hidden">
                                        <div class="text-muted fw-bold" style="font-size: 0.6rem; letter-spacing: 0.5px;">WAKTU LUNCUR</div>
                                        <div class="fw-bolder text-dark text-truncate" style="font-size: 0.9rem; line-height: 1.2;">
                                            {{ \Carbon\Carbon::parse($satellite->launch_date)->translatedFormat('d M Y') ?? 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="info-box-wide shadow-sm mb-4">
                    <div class="info-icon-mini bg-success-lt text-success" style="width: 42px; height: 42px;"><i class="fas fa-satellite-dish fa-lg"></i></div>
                    <div class="overflow-hidden ms-2">
                        <div class="text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">SIMPUL STASIUN BUMI PENGONTROL (GROUND STATION)</div>
                        <div class="fw-bolder text-dark text-truncate fs-4" style="line-height: 1.2;">
                            {{ $satellite->groundStation->name ?? 'Belum Terikat ke Stasiun Manapun' }}
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
                    <h4 class="fw-bold text-dark fs-5 m-0 d-flex align-items-center">
                        <i class="fas fa-code text-muted me-2"></i> Data Orbit TLE
                    </h4>
                    <a href="#" class="btn btn-outline-success btn-sm fw-bold rounded-2 px-3 d-flex align-items-center transition-all" onclick="alert('Fitur sinkronisasi API sedang dalam pengembangan.'); return false;">
                        <i class="fas fa-sync-alt me-2"></i> Sync TLE
                    </a>
                </div>

                <div class="terminal-console mb-4">
                    <div class="terminal-header">
                        <div class="mac-dots">
                            <div class="mac-dot dot-red"></div>
                            <div class="mac-dot dot-yellow"></div>
                            <div class="mac-dot dot-green"></div>
                        </div>
                        <div class="terminal-title">norad_telemetry_stream.log</div>
                    </div>
                    <div class="terminal-body">
                        <div class="tle-text"><span class="tle-line-num">01</span>{{ $satellite->tle_line1 ?? 'DATA TLE BARIS 1 KOSONG' }}</div>
                        <div class="tle-text mt-1"><span class="tle-line-num">02</span>{{ $satellite->tle_line2 ?? 'DATA TLE BARIS 2 KOSONG' }}</div>
                    </div>
                </div>

                <h4 class="fw-bold text-dark fs-5 mb-2 mt-4"><i class="fas fa-file-alt text-muted me-2"></i> MANIFEST & DESKRIPSI OPERASIONAL</h4>
                <div class="p-3 bg-light rounded-3 border text-muted" style="font-size: 0.9rem; min-height: 80px; line-height: 1.6;">
                    {{ $satellite->description ?: 'Tidak ada deskripsi manifest misi untuk armada satelit ini.' }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 col-xl-5 mb-4">

        <div class="card glass-card overflow-hidden h-100">
            <div class="card-header bg-dark text-white border-bottom-0 py-3 d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold m-0 fs-4">Live Orbit Tracker</h3>
                <button id="btn-recenter" class="btn btn-sm btn-outline-light border-0 fw-bold"><i class="fas fa-crosshairs me-1"></i> Recenter</button>
            </div>
            <div class="card-body p-0 position-relative d-flex flex-column">

                <div id="sat-map" style="width: 100%; min-height: 400px; background-color: #0f172a; flex-grow: 1;"></div>

                <div class="bg-white border-top p-3 d-flex justify-content-between">
                    <div class="map-coord-box shadow-sm">
                        <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem;">LINTANG (LAT)</div>
                        <div id="map-lat" class="text-danger fw-bolder font-monospace" style="font-size: 0.85rem;">-</div>
                    </div>
                    <div class="map-coord-box shadow-sm">
                        <div class="text-muted fw-bold mb-1" style="font-size: 0.65rem;">BUJUR (LNG)</div>
                        <div id="map-lng" class="text-primary fw-bolder font-monospace" style="font-size: 0.85rem;">-</div>
                    </div>
                    <div class="map-coord-box shadow-sm" style="background: rgba(34,197,94,0.05); border-color: rgba(34,197,94,0.2);">
                        <div class="text-success fw-bold mb-1" style="font-size: 0.65rem;">ELEVASI (ALT)</div>
                        <div id="map-alt" class="text-success fw-bolder font-monospace" style="font-size: 0.85rem;">-</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/satellite.js/4.0.0/satellite.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {

        var tleLine1 = "{{ $satellite->tle_line1 }}";
        var tleLine2 = "{{ $satellite->tle_line2 }}";
        var satName = "{{ $satellite->name }}";

        var map = L.map('sat-map', { zoomControl: false }).setView([0, 0], 2);
        L.control.zoom({ position: 'bottomleft' }).addTo(map);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: '&copy; Esri', maxZoom: 18
        }).addTo(map);

        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
            maxZoom: 18
        }).addTo(map);

        var customIcon = L.divIcon({
            className: 'custom-div-icon',
            html: `<div class="sat-marker-mini"></div>`,
            iconSize: [14, 14], iconAnchor: [7, 7]
        });

        var marker = L.marker([0, 0], {icon: customIcon}).addTo(map);
        marker.bindTooltip(satName, { permanent: true, direction: 'right', offset: [10, 0], className: 'fw-bold bg-dark text-white border-0' });

        var satrec = null;
        try {
            if(tleLine1 && tleLine2) {
                satrec = satellite.twoline2satrec(tleLine1, tleLine2);

                var orbitPoints = [];
                var baseTime = new Date();
                for (var i = -50; i <= 50; i += 2) {
                    var futureTime = new Date(baseTime.getTime() + i * 60000);
                    var posVel = satellite.propagate(satrec, futureTime);
                    var gmst = satellite.gstime(futureTime);
                    if (posVel.position) {
                        var posGd = satellite.eciToGeodetic(posVel.position, gmst);
                        orbitPoints.push([satellite.degreesLat(posGd.latitude), satellite.degreesLong(posGd.longitude)]);
                    }
                }
                if (orbitPoints.length > 0) {
                    L.polyline(orbitPoints, {color: '#38bdf8', weight: 2, opacity: 0.6, dashArray: '5, 10'}).addTo(map);
                }
            }
        } catch(e) { console.log("Gagal memproses TLE."); }

        function updatePosition() {
            if(!satrec) return;
            var now = new Date();
            var posVel = satellite.propagate(satrec, now);
            var gmst = satellite.gstime(now);

            if (posVel.position) {
                var posGd = satellite.eciToGeodetic(posVel.position, gmst);
                var lat = satellite.degreesLat(posGd.latitude);
                var lng = satellite.degreesLong(posGd.longitude);
                var alt = posGd.height;

                marker.setLatLng([lat, lng]);

                document.getElementById('map-lat').innerText = lat.toFixed(3) + '°';
                document.getElementById('map-lng').innerText = lng.toFixed(3) + '°';
                document.getElementById('map-alt').innerText = alt.toFixed(1) + ' km';
            }
            requestAnimationFrame(updatePosition);
        }

        updatePosition();

        document.getElementById('btn-recenter').addEventListener('click', function() {
            var pos = marker.getLatLng();
            map.flyTo([pos.lat, pos.lng], 4, { animate: true, duration: 1 });
        });

        setTimeout(() => {
            var pos = marker.getLatLng();
            if(pos.lat !== 0 || pos.lng !== 0) {
                map.setView([pos.lat, pos.lng], 3);
            }
            map.invalidateSize();
        }, 500);
    });
</script>
@endpush
