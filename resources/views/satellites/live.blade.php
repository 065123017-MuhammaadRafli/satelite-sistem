@extends('layouts.admin')

@section('title', 'Global Live Tracking')
@section('page-title', 'Global Live Tracking')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <style>
        .leaflet-container {
            border-bottom-left-radius: 12px;
            border-bottom-right-radius: 12px;
            z-index: 1;
            background-color: #0f172a;
        }

        /* Filter Menu Premium */
        .filter-dropdown {
            width: 260px;
            background: rgba(15, 23, 42, 0.95) !important;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.1) !important;
            border-radius: 12px !important;
            color: #f8fafc;
        }
        .filter-dropdown .form-check-label { color: #cbd5e1 !important; }
        .filter-dropdown .border-bottom { border-color: rgba(255,255,255,0.1) !important; }
        .form-switch .form-check-input:checked { background-color: #f59e0b; border-color: #f59e0b; }

        /* Ikon Satelit Glowing */
        .custom-sat-marker {
            border-radius: 50%; border: 2px solid #ffffff;
            box-shadow: 0 0 12px currentColor, 0 0 4px currentColor inset;
            position: relative;
            transition: transform 0.2s;
        }
        .custom-sat-marker::after {
            content: ''; position: absolute;
            top: -50%; left: -50%; width: 200%; height: 200%;
            border-radius: 50%; border: 1px solid currentColor;
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

        /* Kartu Telemetri Premium Modern */
        .telemetry-value {
            font-family: 'JetBrains Mono', 'Courier New', monospace;
            font-weight: 700; letter-spacing: -0.5px; transition: color 0.3s;
        }
        .satellite-card {
            cursor: pointer;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            border: 1px solid rgba(0,0,0,0.03) !important;
            border-radius: 12px !important;
            overflow: hidden;
        }
        .satellite-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.06) !important;
        }

        /* State Kartu Aktif Mengikuti (Tracking) */
        .tracking-active {
            background-color: #ffffff;
            border-color: #f59e0b !important;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.15) !important;
            transform: translateY(-4px);
        }
        .tracking-indicator { display: none; }
        .tracking-active .tracking-indicator { display: inline-block; color: #f59e0b; animation: blink 1s infinite; }
        @keyframes blink { 50% { opacity: 0.3; } }
    </style>
@endpush

@section('content')
<div class="row row-cards mb-4 mt-2" id="cards-container">
    </div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-dark text-white py-3 border-bottom-0 d-flex justify-content-between align-items-center">
                <h3 class="card-title fw-bold m-0 fs-3 d-flex align-items-center">
                    <i class="fas fa-satellite-dish text-warning me-2"></i> Real-Time Orbit Tracker
                </h3>

                <div class="d-flex gap-2">
                    <div class="dropdown">
                        <button class="btn btn-warning fw-bold dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                            <i class="fas fa-filter me-1"></i> Radar Filter
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-3 shadow-lg filter-dropdown" id="filter-dropdown-menu">
                            <label class="form-check form-switch mb-3 pb-3 border-bottom">
                                <input class="form-check-input" type="checkbox" id="filter-all">
                                <span class="form-check-label fw-bold text-white">Tampilkan Semua</span>
                            </label>
                        </div>
                    </div>

                    <button id="btn-recenter" class="btn btn-outline-light fw-bold" title="Temukan dan Bingkai Seluruh Satelit">
                        <i class="fas fa-expand-arrows-alt me-1"></i> Smart Reset View
                    </button>
                </div>
            </div>

            <div class="card-body p-0 position-relative">
                <div id="map" style="width: 100%; height: 75vh;"></div>
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

            // 1. SETTING PETA LUAS (BEBAS POTONGAN HITAM)
            var map = L.map('map', {
                center: [0, 0],
                zoom: 2,
                minZoom: 2,
                maxZoom: 18,
                // Kunci kutub Utara/Selatan, Bebaskan Timur/Barat
                maxBounds: [[-90, -Infinity], [90, Infinity]],
                maxBoundsViscosity: 1.0,
                worldCopyJump: true,
                zoomAnimation: true,
                preferCanvas: true
            });

            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '&copy; Esri',
                noWrap: false // Peta dibiarkan melingkar sempurna tanpa batas pinggir
            }).addTo(map);

            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                pane: 'markerPane',
                noWrap: false
            }).addTo(map);

            var satellites = [];
            var trackedSatId = null;

            // ANIMASI PERGERAKAN ARMADA
            function animateSatellites() {
                const now = new Date();

                satellites.forEach(sat => {
                    var newLat, newLng;

                    if (sat.isDatabase) {
                        try {
                            const satrec = satellite.twoline2satrec(sat.tle1, sat.tle2);
                            const positionAndVelocity = satellite.propagate(satrec, now);
                            const gmst = satellite.gstime(now);
                            const positionGd = satellite.eciToGeodetic(positionAndVelocity.position, gmst);

                            newLat = satellite.degreesLat(positionGd.latitude);
                            newLng = satellite.degreesLong(positionGd.longitude);

                            sat.markerObj.setLatLng([newLat, newLng]);

                            const txtLat = document.getElementById(`lat-${sat.id}`);
                            const txtLng = document.getElementById(`lng-${sat.id}`);
                            if (txtLat && txtLng) {
                                txtLat.innerText = newLat.toFixed(3) + '°';
                                txtLng.innerText = newLng.toFixed(3) + '°';
                            }
                        } catch(e) { }
                    } else {
                        sat.currentLng += sat.speed;
                        if (sat.currentLng > 180) sat.currentLng -= 360;
                        newLat = sat.inclination * Math.sin((sat.currentLng - sat.offset) * Math.PI / 180);
                        newLng = sat.currentLng;
                        sat.markerObj.setLatLng([newLat, newLng]);
                    }

                    if (trackedSatId === sat.id) {
                        map.panTo([newLat, newLng], { animate: false });
                    }
                });
                requestAnimationFrame(animateSatellites);
            }

            map.on('dragstart', function() {
                if (trackedSatId !== null) {
                    trackedSatId = null;
                    document.querySelectorAll('.satellite-card').forEach(c => {
                        c.classList.remove('tracking-active');
                        c.querySelector('.action-label').innerText = "Klik untuk Ikuti";
                    });
                }
            });

            setInterval(() => {
                satellites.forEach(sat => {
                    if (!sat.isDatabase) {
                        var currentLat = sat.inclination * Math.sin((sat.currentLng - sat.offset) * Math.PI / 180);
                        var latElement = document.getElementById('lat-' + sat.id);
                        var lngElement = document.getElementById('lng-' + sat.id);

                        if(latElement && lngElement) {
                            latElement.innerText = currentLat.toFixed(3) + '°';
                            lngElement.innerText = sat.currentLng.toFixed(3) + '°';
                            latElement.style.color = sat.color; lngElement.style.color = sat.color;
                            setTimeout(() => { latElement.style.color = ''; lngElement.style.color = ''; }, 300);
                        }
                    }
                });
            }, 3000);

            function bindCardClickEvent(cardElement) {
                cardElement.addEventListener('click', function() {
                    var satId = this.getAttribute('data-sat');
                    if (trackedSatId === satId) {
                        trackedSatId = null;
                        this.classList.remove('tracking-active');
                        this.querySelector('.action-label').innerText = "Klik untuk Ikuti";
                        return;
                    }
                    document.querySelectorAll('.satellite-card').forEach(c => {
                        c.classList.remove('tracking-active');
                        c.querySelector('.action-label').innerText = "Klik untuk Ikuti";
                    });
                    trackedSatId = satId;
                    this.classList.add('tracking-active');
                    this.querySelector('.action-label').innerText = "Berhenti Ikuti";

                    var sat = satellites.find(s => s.id === satId);
                    var pos = sat.markerObj.getLatLng();
                    map.flyTo([pos.lat, pos.lng], 5, { animate: true, duration: 1.5 });
                });
            }

            var filterAll = document.getElementById('filter-all');

            function applyFilters() {
                let currentState = JSON.parse(localStorage.getItem('satTrackerState')) || {};

                satellites.forEach(sat => {
                    var colWrapper = document.getElementById('col-' + sat.id);
                    var cb = document.querySelector(`.sat-filter[value="${sat.id}"]`);

                    if (cb) {
                        currentState[sat.id] = cb.checked;
                        if (cb.checked) {
                            if (!map.hasLayer(sat.markerObj)) map.addLayer(sat.markerObj);
                            if (sat.orbitLine && !map.hasLayer(sat.orbitLine)) map.addLayer(sat.orbitLine);
                            if (colWrapper) colWrapper.style.display = 'block';
                        } else {
                            if (trackedSatId === sat.id) trackedSatId = null;
                            if (map.hasLayer(sat.markerObj)) map.removeLayer(sat.markerObj);
                            if (sat.orbitLine && map.hasLayer(sat.orbitLine)) map.removeLayer(sat.orbitLine);
                            if (colWrapper) colWrapper.style.display = 'none';
                        }
                    }
                });
                localStorage.setItem('satTrackerState', JSON.stringify(currentState));
            }

            // =========================================================
            // FUNGSI INTI BARU: SMART DYNAMIC FRAMING
            // =========================================================
            function resetGlobalView() {
                // Matikan mode tracking satu satelit
                trackedSatId = null;
                document.querySelectorAll('.satellite-card').forEach(c => {
                    c.classList.remove('tracking-active');
                    c.querySelector('.action-label').innerText = "Klik untuk Ikuti";
                });

                // Kumpulkan koordinat seluruh satelit yang sedang AKTIF/Dicentang
                var bounds = L.latLngBounds();
                var activeCount = 0;

                satellites.forEach(sat => {
                    if (map.hasLayer(sat.markerObj)) {
                        var pos = sat.markerObj.getLatLng();
                        bounds.extend(pos);
                        activeCount++;
                    }
                });

                if (activeCount > 0 && bounds.isValid()) {
                    // Kamera akan terbang dan membingkai seluruh satelit secara dinamis
                    // padding: memberikan ruang aman di pinggir layar agar satelit tidak terpotong tepi layar
                    // maxZoom: membatasi seberapa dekat kamera boleh zoom jika satelitnya berdekatan
                    map.flyToBounds(bounds, { padding: [80, 80], maxZoom: 3, animate: true, duration: 1.8 });
                } else {
                    // Jika tidak ada satelit aktif (semua di-uncheck), kembali ke standar dunia
                    map.flyTo([0, 0], 2, { animate: true, duration: 1.5 });
                }
            }

            // Pasang fungsi cerdas ini ke tombol Reset
            document.getElementById('btn-recenter').addEventListener('click', resetGlobalView);


            // 2. FUNGSI PEMBUAT SATELIT MANUAL
            function injectManualSatellites() {
                const manualSats = [
                    { id: 'tubsat', name: "LAPAN-TUBSAT", color: "#ef4444", inclination: 81, offset: 52, currentLng: -38.107, speed: 0.003, isDb: false },
                    { id: 'a2', name: "LAPAN-A2", color: "#22c55e", inclination: 15, offset: 0, currentLng: -89.607, speed: 0.004, isDb: false },
                    { id: 'a3', name: "LAPAN-A3", color: "#3b82f6", inclination: 78, offset: 78, currentLng: 168.239, speed: 0.002, isDb: false }
                ];

                const cardsContainer = document.getElementById('cards-container');
                const filterMenu = document.getElementById('filter-dropdown-menu');

                manualSats.forEach(sat => {
                    const cardHtml = `
                        <div class="col-sm-6 col-md-4 col-xl-3 sat-col-wrapper" id="col-${sat.id}" style="display: none;">
                            <div class="card shadow-sm border-0 border-top border-3 satellite-card" style="border-top-color: ${sat.color} !important;" data-sat="${sat.id}" id="card-${sat.id}">
                                <div class="card-body p-3">
                                    <h4 class="fw-bold mb-3 text-dark d-flex align-items-center justify-content-between">
                                        <div class="text-truncate me-2"><i class="fas fa-satellite me-2" style="color: ${sat.color};"></i> ${sat.name}</div>
                                        <span class="badge bg-green-lt rounded-pill px-2 py-1 small fw-bold">ONLINE</span>
                                    </h4>
                                    <div class="row g-2 text-muted fs-5 mb-3">
                                        <div class="col-6">Lat: <span class="telemetry-value text-dark" id="lat-${sat.id}">-°</span></div>
                                        <div class="col-6">Lng: <span class="telemetry-value text-dark" id="lng-${sat.id}">-°</span></div>
                                        <div class="col-6">Alt: <span class="telemetry-value text-success">600 km</span></div>
                                        <div class="col-6">Spd: <span class="telemetry-value text-primary">7.5 km/s</span></div>
                                    </div>
                                    <div class="text-end border-top pt-2">
                                        <span class="text-muted small fw-bold status-text">
                                            <i class="fas fa-crosshairs me-1"></i> <span class="action-label">Klik untuk Ikuti</span>
                                            <span class="tracking-indicator ms-2"><i class="fas fa-circle text-warning fs-6"></i> Tracking</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>`;
                    cardsContainer.insertAdjacentHTML('beforeend', cardHtml);

                    const filterHtml = `
                        <label class="form-check form-switch mb-3" id="filter-label-${sat.id}">
                            <input class="form-check-input sat-filter" type="checkbox" value="${sat.id}">
                            <span class="form-check-label fw-medium text-white"><i class="fas fa-circle me-1 fs-6" style="color: ${sat.color};"></i> ${sat.name}</span>
                        </label>`;
                    filterMenu.insertAdjacentHTML('beforeend', filterHtml);

                    var orbitSegments = [];
                    var currentSegment = [];
                    var prevLng = null;

                    for (var lng = -180; lng <= 180; lng += 2) {
                        var lat = sat.inclination * Math.sin((lng - sat.offset) * Math.PI / 180);
                        if (prevLng !== null && Math.abs(lng - prevLng) > 180) {
                            orbitSegments.push(currentSegment);
                            currentSegment = [];
                        }
                        currentSegment.push([lat, lng]);
                        prevLng = lng;
                    }
                    orbitSegments.push(currentSegment);

                    var orbitLineObj = L.polyline(orbitSegments, { color: sat.color, weight: 2.5, opacity: 0.85, dashArray: '8, 12' });

                    var customIcon = L.divIcon({
                        className: 'custom-div-icon',
                        html: `<div class="custom-sat-marker" style="background-color: ${sat.color}; color: ${sat.color}; width: 14px; height: 14px;"></div>`,
                        iconSize: [14, 14], iconAnchor: [7, 7]
                    });

                    var initialLat = sat.inclination * Math.sin((sat.currentLng - sat.offset) * Math.PI / 180);
                    var markerObj = L.marker([initialLat, sat.currentLng], {icon: customIcon});
                    markerObj.bindTooltip(sat.name, { permanent: true, direction: 'bottom', className: 'sat-label', offset: [0, 10] });

                    satellites.push({
                        id: sat.id, name: sat.name, color: sat.color,
                        inclination: sat.inclination, offset: sat.offset, currentLng: sat.currentLng, speed: sat.speed,
                        isDatabase: false, markerObj: markerObj, orbitLine: orbitLineObj
                    });

                    bindCardClickEvent(document.getElementById(`card-${sat.id}`));
                });
            }

            injectManualSatellites();

            // 3. AMBIL DATA SATELLITE DARI DATABASE
            fetch("{{ route('api.satellites.live') }}")
                .then(response => response.json())
                .then(dbSatellites => {
                    const cardsContainer = document.getElementById('cards-container');
                    const filterDropdownMenu = document.getElementById('filter-dropdown-menu');
                    let savedState = JSON.parse(localStorage.getItem('satTrackerState')) || {};

                    dbSatellites.forEach(sat => {
                        const uniqueId = 'db-' + sat.id;
                        const randomColor = '#f59e0b';

                        const cardHtml = `
                            <div class="col-sm-6 col-md-4 col-xl-3 sat-col-wrapper" id="col-${uniqueId}" style="display: none;">
                                <div class="card shadow-sm border-0 border-top border-warning border-3 satellite-card" data-sat="${uniqueId}" id="card-${uniqueId}">
                                    <div class="card-body p-3">
                                        <h4 class="fw-bold mb-3 text-dark d-flex align-items-center justify-content-between">
                                            <div class="text-truncate me-2"><i class="fas fa-satellite text-warning me-2"></i> ${sat.name}</div>
                                            <span class="badge bg-yellow-lt rounded-pill px-2 py-1 small fw-bold">LIVE DB</span>
                                        </h4>
                                        <div class="row g-2 text-muted fs-5 mb-3">
                                            <div class="col-6">Lat: <span class="telemetry-value text-dark" id="lat-${uniqueId}">-°</span></div>
                                            <div class="col-6">Lng: <span class="telemetry-value text-dark" id="lng-${uniqueId}">-°</span></div>
                                            <div class="col-6">Alt: <span class="telemetry-value text-success">Real TLE</span></div>
                                            <div class="col-6">Spd: <span class="telemetry-value text-primary">7.66 km/s</span></div>
                                        </div>
                                        <div class="text-end border-top pt-2">
                                            <span class="text-muted small fw-bold status-text">
                                                <i class="fas fa-crosshairs me-1"></i> <span class="action-label">Klik untuk Ikuti</span>
                                                <span class="tracking-indicator ms-2"><i class="fas fa-circle text-warning fs-6"></i> Tracking</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>`;
                        cardsContainer.insertAdjacentHTML('beforeend', cardHtml);

                        const filterHtml = `
                            <label class="form-check form-switch mb-3" id="filter-label-${uniqueId}">
                                <input class="form-check-input sat-filter" type="checkbox" value="${uniqueId}">
                                <span class="form-check-label fw-medium text-white"><i class="fas fa-circle text-warning me-1 fs-6"></i> ${sat.name}</span>
                            </label>`;
                        filterDropdownMenu.insertAdjacentHTML('beforeend', filterHtml);

                        var orbitSegments = [];
                        var currentSegment = [];
                        var prevLng = null;

                        try {
                            const satrec = satellite.twoline2satrec(sat.tle_line1, sat.tle_line2);
                            const baseTime = new Date();

                            for (let i = -50; i <= 50; i += 2) {
                                const futureTime = new Date(baseTime.getTime() + i * 60000);
                                const positionAndVelocity = satellite.propagate(satrec, futureTime);
                                const gmst = satellite.gstime(futureTime);

                                if (positionAndVelocity.position) {
                                    const positionGd = satellite.eciToGeodetic(positionAndVelocity.position, gmst);
                                    const pLat = satellite.degreesLat(positionGd.latitude);
                                    const pLng = satellite.degreesLong(positionGd.longitude);

                                    if (prevLng !== null && Math.abs(pLng - prevLng) > 180) {
                                        orbitSegments.push(currentSegment);
                                        currentSegment = [];
                                    }

                                    currentSegment.push([pLat, pLng]);
                                    prevLng = pLng;
                                }
                            }
                            orbitSegments.push(currentSegment);
                        } catch(err) { }

                        var orbitLineObj = null;
                        if (orbitSegments.length > 0) {
                            orbitLineObj = L.polyline(orbitSegments, { color: randomColor, weight: 2.5, opacity: 0.85, dashArray: '8, 12' });
                        }

                        var customIcon = L.divIcon({
                            className: 'custom-div-icon',
                            html: `<div class="custom-sat-marker" style="background-color: ${randomColor}; color: ${randomColor}; width: 14px; height: 14px;"></div>`,
                            iconSize: [14, 14], iconAnchor: [7, 7]
                        });

                        var markerObj = L.marker([0, 0], {icon: customIcon});
                        markerObj.bindTooltip(sat.name, { permanent: true, direction: 'bottom', className: 'sat-label', offset: [0, 10] });

                        satellites.push({
                            id: uniqueId, name: sat.name, color: randomColor,
                            tle1: sat.tle_line1, tle2: sat.tle_line2,
                            isDatabase: true, markerObj: markerObj, orbitLine: orbitLineObj
                        });

                        bindCardClickEvent(document.getElementById(`card-${uniqueId}`));
                    });

                    document.querySelectorAll('.sat-col-wrapper').forEach(el => {
                        el.classList.add('col-sm-6', 'col-md-4', 'col-xl-3');
                    });

                    const allFilters = document.querySelectorAll('.sat-filter');
                    allFilters.forEach(cb => {
                        cb.checked = savedState[cb.value] === true;
                        cb.addEventListener('change', function() {
                            filterAll.checked = Array.from(allFilters).every(c => c.checked);
                            applyFilters();
                        });
                    });

                    filterAll.addEventListener('change', function() {
                        allFilters.forEach(cb => cb.checked = this.checked);
                        applyFilters();
                    });

                    filterAll.checked = allFilters.length > 0 && Array.from(allFilters).every(c => c.checked);
                    applyFilters();
                })
                .finally(() => {
                    animateSatellites();

                    // Eksekusi Auto-Framing pintar saat pertama kali Peta Dimuat
                    setTimeout(resetGlobalView, 600);
                });

            setTimeout(function() { map.invalidateSize(); }, 500);
        });
    </script>
@endpush
