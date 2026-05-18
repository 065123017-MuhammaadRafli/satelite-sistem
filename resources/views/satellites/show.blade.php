@extends('layouts.admin')

@section('title', 'Detail Satelit - ' . $satellite->name)
@section('page-title', 'Detail Data Satelit')
@section('page-subtitle', 'Informasi spesifikasi teknis, data telemetri TLE, dan status operasional armada.')
@section('page-icon', 'fas fa-satellite')

@section('content')
<style>
    .modern-card {
        border-radius: 12px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.02);
        border: 1px solid rgba(0,0,0,0.04);
        background: #ffffff;
    }
    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: #64748b;
        font-weight: 700;
    }
    .info-value {
        color: #1e293b;
        font-weight: 600;
    }
    /* Terminal TLE Style */
    .tle-terminal {
        background: #0f172a;
        padding: 1.25rem;
        border-radius: 10px;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.3);
    }
    .tle-code {
        font-family: 'Courier New', Courier, monospace;
        letter-spacing: 1.5px;
        font-size: 0.95rem;
        white-space: pre-wrap;
        word-break: break-all;
    }
    /* Radar Animasi untuk Status Aktif */
    .status-dot-animated {
        animation: pulse-soft 2s infinite;
    }
    @keyframes pulse-soft {
        0% { transform: scale(0.95); opacity: 1; }
        50% { transform: scale(1.15); opacity: 0.4; }
        100% { transform: scale(0.95); opacity: 1; }
    }
</style>

<div class="row mb-4 align-items-center d-print-none">
    <div class="col-auto">
        <a href="{{ route('satellites.index') }}" class="btn btn-white fw-bold shadow-sm rounded-3 text-secondary">
            <i class="fas fa-arrow-left text-primary me-2"></i> Kembali ke Daftar
        </a>
    </div>
    <div class="col-auto ms-auto">
        <a href="{{ route('satellites.edit', $satellite) }}" class="btn btn-warning fw-bold shadow-sm text-dark px-3">
            <i class="fas fa-edit me-2"></i> Edit Data Satelit
        </a>
    </div>
</div>

<div class="row g-4">

    <div class="col-lg-8">
        <div class="card modern-card mb-4">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h3 class="card-title fw-bold text-dark m-0">
                    <i class="fas fa-info-circle text-primary me-2"></i> Spesifikasi Armada
                </h3>
            </div>
            <div class="card-body p-4 pt-2">
                <div class="row g-3 mb-4 border-bottom pb-4">
                    <div class="col-sm-6 col-md-4">
                        <div class="info-label">Nama Satelit</div>
                        <div class="info-value fs-2 text-primary fw-bold mt-1">{{ $satellite->name }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="info-label">Negara Asal</div>
                        <div class="info-value fs-4 mt-1"><i class="fas fa-flag text-secondary me-2"></i>{{ $satellite->country }}</div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="info-label">Tipe Orbit</div>
                        <div class="info-value mt-1">
                            <span class="badge bg-indigo-lt px-3 py-1 fw-bold fs-5">{{ $satellite->orbit_type }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="info-label">Tanggal Peluncuran</div>
                        <div class="info-value mt-1">
                            <i class="far fa-calendar-alt text-muted me-2"></i>{{ $satellite->launch_date ? $satellite->launch_date->format('d M Y') : '-' }}
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-8">
                        <div class="info-label">Stasiun Bumi Terhubung</div>
                        <div class="info-value mt-1">
                            @if($satellite->groundStation)
                                <span class="text-success fw-bold">
                                    <i class="fas fa-broadcast-tower me-2"></i>{{ $satellite->groundStation->name }}
                                </span>
                            @else
                                <span class="text-muted fst-italic"><i class="fas fa-unlink me-2"></i>Belum Terhubung</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="info-label mb-2">Two-Line Element (TLE) Data</div>
                <div class="tle-terminal mb-2">
                    <div class="tle-code text-info mb-2">{{ $satellite->tle_line1 ?? 'Tidak ada data Line 1' }}</div>
                    <div class="tle-code text-success">{{ $satellite->tle_line2 ?? 'Tidak ada data Line 2' }}</div>
                </div>
                <small class="text-muted d-block mb-4"><i class="fas fa-info-circle me-1"></i> Format koordinat telemetri astronomi standar NORAD.</small>

                <div class="info-label mb-2">Deskripsi Misi</div>
                <div class="p-3 bg-light rounded-3 text-secondary" style="line-height: 1.6;">
                    {{ $satellite->description ?? 'Tidak ada deskripsi tambahan untuk satelit ini.' }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card modern-card mb-4 text-center p-4">
            <div class="info-label mb-3">Status Operasional</div>
            <div class="mb-2">
                @if($satellite->status == 'active')
                    <span class="badge bg-success-lt px-4 py-2 rounded-pill fs-4 fw-bold">
                        <span class="status-dot bg-success me-2 status-dot-animated"></span> ACTIVE
                    </span>
                @else
                    <span class="badge bg-danger-lt px-4 py-2 rounded-pill fs-4 fw-bold">
                        <span class="status-dot bg-danger me-2"></span> INACTIVE
                    </span>
                @endif
            </div>
        </div>

        <div class="card modern-card p-3">
            <div class="info-label mb-3"><i class="fas fa-image me-1"></i> Visual Dokumentasi</div>
            <div class="border rounded-3 p-2 bg-light d-flex align-items-center justify-content-center overflow-hidden" style="min-height: 220px;">
                @if($satellite->image)
                    <img src="{{ asset('storage/' . $satellite->image) }}" alt="{{ $satellite->name }}" class="img-fluid rounded shadow-sm" style="max-height: 250px; width: 100%; object-fit: cover;">
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-satellite fa-3x mb-2 opacity-25"></i>
                        <div class="small fst-italic">Belum ada gambar terunggah</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>
@endsection
