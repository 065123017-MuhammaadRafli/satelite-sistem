@extends('layouts.admin')

@section('title', 'Dashboard Utama')
@section('page-title', 'SATELLITE SYSTEM COMAND CENTER')
@section('page-subtitle', 'Pusat kendali telemetri satelit dan infrastruktur stasiun bumi terintegrasi.')
@section('page-icon', 'fas fa-server')

@push('styles')
<style>
    /* Styling Dasar Dasbor Modern Aerospace */
    .dashboard-container {
        padding-bottom: 2rem;
    }

    /* TELEMETRY CONSOLE BAR (Panel Atas Gelap - Tidak Diubah) */
    .telemetry-console {
        background: #0f172a;
        border-radius: 8px;
        padding: 14px 24px;
        margin-bottom: 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.15);
        border: 1px solid #1e293b;
        color: #f8fafc;
    }
    .status-indicator-dark {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(34, 197, 94, 0.15); color: #4ade80;
        padding: 4px 14px; border-radius: 4px;
        font-family: 'JetBrains Mono', 'Courier New', monospace;
        font-weight: 700; font-size: 0.75rem; letter-spacing: 1px;
        border: 1px solid rgba(74, 222, 128, 0.3);
    }
    .status-dot-glow {
        width: 8px; height: 8px; border-radius: 50%; background: #4ade80;
        box-shadow: 0 0 10px #4ade80;
        animation: pulse-glow 2s infinite;
    }
    @keyframes pulse-glow { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(0.8); } 100% { opacity: 1; transform: scale(1); } }

    .live-clock-display {
        font-family: 'JetBrains Mono', 'Courier New', monospace;
        font-size: 0.85rem; letter-spacing: 0.5px;
        color: #e2e8f0; background: #1e293b;
        padding: 6px 14px; border-radius: 4px;
        border: 1px solid #334155;
    }

    /* =========================================================
       WHITE HUD CARDS dengan GLOWING ICONS (Desain Baru)
       ========================================================= */
    .hud-white-card {
        background: #ffffff;
        border: 1px solid #e2e8f0; /* Border tipis seperti referensi awal */
        border-radius: 12px;
        padding: 24px;
        position: relative;
        transition: all 0.3s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.02);
    }
    .hud-white-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        border-color: #cbd5e1;
    }

    /* TYPOGRAPHY KARTU */
    .hud-title {
        font-size: 0.65rem;
        font-weight: 800;
        color: #64748b;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }
    .hud-value {
        font-size: 2.5rem;
        font-weight: 900;
        color: #0f172a;
        line-height: 1;
        margin-bottom: 12px;
        font-family: 'Inter', sans-serif;
        letter-spacing: -1px;
    }

    /* =========================================================
       TEKNIK CSS IKON NYALA (NEON/GLOW EFFECT)
       ========================================================= */
    .glowing-icon-container {
        position: absolute;
        right: 24px;
        top: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 48px;
        height: 48px;
        background: #f8fafc; /* Latar belakang ikon agar tidak terlalu sepi */
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }
    .glowing-icon {
        font-size: 1.4rem;
    }

    /* Warna Ikon Nyala (Text-Shadow untuk efek Pijar) */
    .glow-blue {
        color: #1d4ed8; /* Warna teks ikon lebih solid */
        text-shadow: 0 0 12px rgba(59, 130, 246, 0.9), 0 0 20px rgba(59, 130, 246, 0.4);
    }
    .glow-green {
        color: #16a34a;
        text-shadow: 0 0 12px rgba(34, 197, 94, 0.9), 0 0 20px rgba(34, 197, 94, 0.4);
    }
    .glow-orange {
        color: #ea580c;
        text-shadow: 0 0 12px rgba(249, 115, 22, 0.9), 0 0 20px rgba(249, 115, 22, 0.4);
    }
    .glow-red {
        color: #dc2626;
        text-shadow: 0 0 12px rgba(239, 68, 68, 0.9), 0 0 20px rgba(239, 68, 68, 0.4);
    }

    /* CONTENT DATA GRID (Tidak Diubah) */
    .tech-panel {
        background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.02); overflow: hidden;
    }
    .tech-panel-header {
        background: #f8fafc; border-bottom: 1px solid #e2e8f0;
        padding: 16px 20px; display: flex; align-items: center; justify-content: space-between;
    }
    .tech-panel-title {
        font-size: 0.9rem; font-weight: 800; color: #1e293b; letter-spacing: 0.5px; margin: 0; display: flex; align-items: center; gap: 8px;
    }
    .data-stream-item {
        padding: 16px 20px; border-bottom: 1px dashed #e2e8f0;
        display: flex; align-items: center; justify-content: space-between;
        transition: background 0.2s;
    }
    .data-stream-item:hover { background: #fcfcfd; }
    .data-stream-item:last-child { border-bottom: none; }
    .signal-bars { display: flex; gap: 2px; align-items: flex-end; height: 14px; }
    .signal-bar { width: 4px; background-color: #e2e8f0; border-radius: 1px; }
    .signal-active { background-color: #22c55e; }
    .cmd-btn {
        background: #ffffff; border: 1px solid #cbd5e1; color: #475569;
        font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;
        padding: 6px 12px; border-radius: 4px; transition: all 0.2s; text-decoration: none;
    }
    .cmd-btn:hover { background: #f1f5f9; color: #0f172a; border-color: #94a3b8; }
</style>
@endpush

@section('content')
<div class="dashboard-container">

    <div class="telemetry-console">
        <div class="d-flex align-items-center gap-4">
            <div>
                <div class="text-uppercase" style="font-size: 0.6rem; color: #94a3b8; letter-spacing: 1px; margin-bottom: 4px;">System Link Status</div>
                <div class="status-indicator-dark">
                    <div class="status-dot-glow"></div> OPERATIONAL OPTIMAL
                </div>
            </div>
        </div>
        <div class="d-none d-md-block text-end">
            <div class="text-uppercase" style="font-size: 0.6rem; color: #94a3b8; letter-spacing: 1px; margin-bottom: 4px;">Global Sync Time (WIB)</div>
            <div class="live-clock-display">
                <i class="far fa-clock text-blue me-2"></i><span id="live-clock">{{ date('d M Y, H:i:s') }}</span>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">

        <div class="col-sm-6 col-xl-3">
            <div class="hud-white-card position-relative h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="glowing-icon-container shadow-sm">
                        <i class="fas fa-rocket glowing-icon glow-blue"></i>
                    </div>
                    <div class="hud-title">ORBITAL ASSETS</div>
                    <div class="hud-value">{{ \App\Models\Satellite::count() }}</div>
                </div>
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-1" style="border-color: #f1f5f9 !important;">
                    <span class="text-success fw-bold font-monospace" style="font-size: 0.7rem;"><i class="fas fa-caret-up me-1"></i>{{ \App\Models\Satellite::where('status', 'active')->count() }} DEPLOYED</span>
                    <a href="{{ route('satellites.index') }}" class="text-muted fw-bold text-decoration-none stretched-link" style="font-size: 0.7rem;">ACCESS <i class="fas fa-angle-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="hud-white-card position-relative h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="glowing-icon-container shadow-sm">
                        <i class="fas fa-broadcast-tower glowing-icon glow-green"></i>
                    </div>
                    <div class="hud-title">GROUND NODES</div>
                    <div class="hud-value">{{ \App\Models\GroundStation::count() }}</div>
                </div>
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-1" style="border-color: #f1f5f9 !important;">
                    <span class="text-success fw-bold font-monospace" style="font-size: 0.7rem;"><i class="fas fa-check me-1"></i>ALL ONLINE</span>
                    <a href="{{ route('ground-stations.index') }}" class="text-muted fw-bold text-decoration-none stretched-link" style="font-size: 0.7rem;">MAP <i class="fas fa-angle-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="hud-white-card position-relative h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="glowing-icon-container shadow-sm">
                        <i class="fas fa-globe-americas glowing-icon glow-orange"></i>
                    </div>
                    <div class="hud-title">COVERAGE REGIONS</div>
                    <div class="hud-value">{{ \App\Models\Satellite::whereNotNull('country')->distinct('country')->count('country') }}</div>
                </div>
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-1" style="border-color: #f1f5f9 !important;">
                    <span class="text-muted fw-bold font-monospace" style="font-size: 0.7rem;"><i class="fas fa-signal me-1"></i>LINK STABLE</span>
                    <a href="{{ route('satellites.live') }}" class="text-muted fw-bold text-decoration-none stretched-link" style="font-size: 0.7rem;">MAP <i class="fas fa-angle-right ms-1"></i></a>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="hud-white-card position-relative h-100 d-flex flex-column justify-content-between">
                <div>
                    <div class="glowing-icon-container shadow-sm">
                        <i class="fas fa-shield-alt glowing-icon glow-red"></i>
                    </div>
                    <div class="hud-title">SECURITY PROTOCOL</div>
                    <div class="hud-value" style="font-size: 1.8rem; padding: 5px 0;">SECURE</div>
                </div>
                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-1" style="border-color: #f1f5f9 !important;">
                    <span class="text-danger fw-bold font-monospace" style="font-size: 0.7rem;"><i class="fas fa-lock me-1"></i>ENCRYPTED</span>
                    <a href="{{ route('statistics') }}" class="text-muted fw-bold text-decoration-none stretched-link" style="font-size: 0.7rem;">LOGS <i class="fas fa-angle-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">

        <div class="col-lg-7">
            <div class="tech-panel h-100">
                <div class="tech-panel-header">
                    <h3 class="tech-panel-title">
                        <i class="fas fa-stream text-blue"></i> Live Telemetry Stream
                    </h3>
                    <span class="badge bg-blue-lt text-blue font-monospace" style="font-size: 0.65rem;">AUTO-REFRESH</span>
                </div>

                <div class="d-flex flex-column">
                    @forelse(\App\Models\Satellite::latest()->take(3)->get() as $sat)
                    <div class="data-stream-item">
                        <div>
                            <div class="fw-bolder text-dark" style="font-size: 0.95rem;">{{ $sat->name }}</div>
                            <div class="text-muted font-monospace mt-1" style="font-size: 0.7rem; text-transform: uppercase;">
                                {{ $sat->orbit_type ?? 'LEO' }} • REGION: {{ $sat->country ?? 'GLOBAL' }}
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-4">
                            <div class="text-end d-none d-sm-block">
                                <div class="text-success fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 1px;">STATUS TX/RX</div>
                                <div class="signal-bars justify-content-end">
                                    <div class="signal-bar signal-active"></div>
                                    <div class="signal-bar signal-active"></div>
                                    <div class="signal-bar signal-active"></div>
                                    <div class="signal-bar {{ $sat->status == 'active' ? 'signal-active' : '' }}"></div>
                                </div>
                            </div>
                            <a href="{{ route('satellites.show', $sat->id) }}" class="cmd-btn">TRACK</a>
                        </div>
                    </div>
                    @empty
                    <div class="p-4 text-center text-muted">Belum ada data satelit.</div>
                    @endforelse
                </div>

                <div class="p-3 text-center bg-light border-top mt-auto">
                    <a href="{{ route('satellites.index') }}" class="text-decoration-none text-muted fw-bold" style="font-size: 0.75rem;">
                        OPEN FULL DIRECTORY <i class="fas fa-external-link-alt ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="tech-panel h-100">
                <div class="tech-panel-header">
                    <h3 class="tech-panel-title">
                        <i class="fas fa-network-wired text-purple"></i> Earth Control Nodes
                    </h3>
                </div>

                <div class="p-4 d-flex flex-column gap-3 bg-light" style="flex-grow: 1;">

                    @forelse(\App\Models\GroundStation::latest()->take(3)->get() as $gs)
                    <div class="bg-white p-3 border rounded shadow-sm d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="bg-green-lt text-green p-2 rounded"><i class="fas fa-satellite-dish"></i></div>
                            <div>
                                <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $gs->name }}</div>
                                <div class="text-muted font-monospace" style="font-size: 0.65rem;">GS-NODE-{{ str_pad($gs->id, 2, '0', STR_PAD_LEFT) }}</div>
                            </div>
                        </div>
                        <span class="badge bg-success-lt text-success border border-success px-2 py-1" style="font-size: 0.6rem; letter-spacing: 1px;">LINKED</span>
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">Belum ada stasiun bumi.</div>
                    @endforelse

                </div>
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Skrip Real-time Clock tetap utuh, tidak disentuh sama sekali
    setInterval(function() {
        const now = new Date();
        const options = { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        document.getElementById('live-clock').innerText = now.toLocaleDateString('id-ID', options).replace(/\./g, ':');
    }, 1000);
</script>
@endpush
