@extends('layouts.admin')

@section('title', 'Detail Satelit - ' . $satellite->name)
@section('page-title', 'Spesifikasi & Telemetri Armada')
@section('page-subtitle', 'Informasi spesifikasi teknis, lintasan orbit, data TLE, dan stasiun bumi pengontrol.')
@section('page-icon', 'fas fa-satellite')

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

    /* TLE Terminal - PENYELARASAN SELARAS DASHBOARD UTAMA */
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
        color: #22c55e !important; /* Hijau Neon stabil selaras halaman Tracker Orbit */
        text-shadow: 0 0 8px rgba(34, 197, 94, 0.3);
    }
    .terminal-num {
        color: #475569;
        font-weight: 700;
        margin-right: 1.25rem;
        user-select: none;
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

    /* Terminal Dot Utility */
    .terminal-dot {
        width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 5px;
    }
</style>

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

    <div class="col-12 col-lg-8">
        <div class="card space-bg-card">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                <div class="bg-blue-lt p-2 rounded-3 me-3 text-blue d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                    <i class="fas fa-chart-network fs-4"></i>
                </div>
                <h3 class="card-title fw-bold text-dark m-0">Matriks Telemetri & Informasi Utama</h3>
            </div>

            <div class="card-body p-3 p-md-4">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="metric-container">
                            <div class="metric-icon-box bg-blue-lt text-blue"><i class="fas fa-fingerprint"></i></div>
                            <div class="text-truncate">
                                <div class="text-muted small fw-medium text-uppercase tracking-wider" style="font-size:0.65rem;">Nama Armada</div>
                                <div class="fw-extrabold text-dark fs-3 text-truncate">{{ $satellite->name }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="metric-container">
                            <div class="metric-icon-box bg-purple-lt text-purple"><i class="fas fa-globe"></i></div>
                            <div class="text-truncate">
                                <div class="text-muted small fw-medium text-uppercase tracking-wider" style="font-size:0.65rem;">Negara Asal</div>
                                <div class="fw-bold text-dark fs-4 text-truncate">{{ $satellite->origin_country ?? 'Global' }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
                        <div class="metric-container">
                            <div class="metric-icon-box bg-azure-lt text-azure"><i class="fas fa-ring"></i></div>
                            <div>
                                <div class="text-muted small fw-medium text-uppercase tracking-wider" style="font-size:0.65rem;">Kategori Orbit</div>
                                <div><span class="badge bg-azure text-white rounded-pill px-2 py-0.5 fw-bold font-monospace" style="font-size:0.7rem;">{{ $satellite->orbit_type ?? 'LEO' }}</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-6 col-md-4">
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

                    <div class="col-12 col-md-8">
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
                                <span class="font-monospace">{{ $satellite->tle_line1 }}</span>
                            </div>
                            <div class="terminal-row mt-1 text-nowrap">
                                <span class="terminal-num">02</span>
                                <span class="font-monospace">{{ $satellite->tle_line2 }}</span>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle text-success me-1"></i> Vektor posisi dikalkulasi otomatis berdasar ephemeris internasional SGP4/NORAD.
                    </small>
                </div>

                <div class="mt-4 pt-3 border-top">
                    <div class="text-muted small fw-bold text-uppercase tracking-wider mb-2" style="font-size:0.7rem;">
                        <i class="fas fa-file-alt text-blue me-1"></i> Manifest & Deskripsi Operasional Misi
                    </div>
                    <div class="p-3 bg-light rounded-3 text-secondary border font-sans" style="line-height: 1.6; font-size: 0.9rem;">
                        @if($satellite->description)
                            {{ $satellite->description }}
                        @else
                            <span class="text-muted italic"><i class="fas fa-info-circle me-1"></i> Satelit ini beroperasi normal menjalankan misi pemantauan bumi global, namun operator belum memasukkan manifest catatan tertulis tambahan ke dalam sistem database.</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-4">
        <div class="row row-cards g-3">

            <div class="col-12">
                <div class="card space-bg-card">
                    <div class="card-body p-4 text-center">
                        <div class="text-muted small fw-bold text-uppercase tracking-wider mb-3" style="font-size:0.65rem;">Kondisi Subsistem Operasional</div>
                        <div class="status-glowing-box text-center">
                            @if(strtolower($satellite->status) == 'active' || strtolower($satellite->status) == 'aktif')
                                <div class="badge pulse-glow-active rounded-pill px-3 py-2 fs-3 d-inline-flex align-items-center justify-content-center w-100">
                                    <i class="fas fa-satellite-dish fa-spin me-2 fs-4"></i> TRANSMITTING / ACTIVE
                                </div>
                            @else
                                <div class="badge pulse-glow-inactive rounded-pill px-3 py-2 fs-3 d-inline-flex align-items-center justify-content-center w-100">
                                    <i class="fas fa-power-off me-2 fs-4"></i> DEACTIVATED / OFFLINE
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card space-bg-card">
                    <div class="card-header bg-white py-3 border-bottom">
                        <h3 class="card-title fw-bold text-dark m-0">
                            <i class="fas fa-camera text-primary me-2"></i> Dokumentasi Visual
                        </h3>
                    </div>
                    <div class="card-body p-3 text-center">
                        @if($satellite->image)
                            <div class="img-thumbnail border-0 p-0 shadow-sm" style="border-radius: 10px; overflow: hidden;">
                                <img src="{{ asset('storage/' . $satellite->image) }}" class="img-fluid w-100" style="max-height: 220px; object-fit: cover;" alt="Foto Satelit">
                            </div>
                        @else
                            <div class="py-5 border border-2 border-dashed rounded-3 bg-light d-flex flex-column align-items-center justify-content-center text-muted" style="min-height: 210px; border-radius: 10px !important;">
                                <div class="p-3 bg-white shadow-sm rounded-circle text-muted mb-3" style="width:54px; height:54px; display:flex; align-items:center; justify-content:center;">
                                    <i class="fas fa-space-shuttle fa-2x opacity-40"></i>
                                </div>
                                <span class="small fw-bold text-secondary">Belum Ada Gambar Terunggah</span>
                                <span class="text-muted" style="font-size:0.75rem;">Gunakan menu konfigurasi untuk upload</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection
