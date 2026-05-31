@extends('layouts.admin')

@section('title', 'Manajemen Stasiun Bumi')
@section('page-title', 'Direktori Infrastruktur Bumi')
@section('page-subtitle', 'Kelola daftar stasiun penerima (Ground Stations), koordinat spasial, dan konektivitas armada.')
@section('page-icon', 'fas fa-satellite-dish')

@section('content')
<style>
    /* Premium Card Overrides */
    .space-bg-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }

    /* Search & Filter Inputs */
    .search-modern {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 0.85rem;
        transition: all 0.2s;
        background-color: #f8fafc;
        height: 40px;
    }
    .search-modern:focus {
        border-color: #3b82f6;
        background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        outline: none;
    }
    .input-icon-search {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 0.85rem;
    }

    /* Table Typography & Spacing */
    .table-modern th {
        background-color: #f8fafc !important;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.5px;
        font-weight: 800;
        color: #64748b;
        border-bottom: 2px solid #e2e8f0;
        padding: 1.2rem 1rem;
    }
    .table-modern td {
        padding: 1rem;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }
    .table-modern tbody tr:hover {
        background-color: #fbfcfe;
    }

    /* Station Icon (Diperbaiki: Lebih Elegan) */
    .station-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        background: rgba(59, 130, 246, 0.08); /* Biru elegan, bukan hijau kaku */
        border: 1px solid rgba(59, 130, 246, 0.15);
        color: #3b82f6;
    }

    /* Kotak Koordinat Super Rapi */
    .coord-box {
        font-family: 'JetBrains Mono', 'Courier New', monospace;
        font-size: 0.75rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        padding: 8px 12px;
        border-radius: 8px;
        width: 100%;
        max-width: 200px;
    }

    /* Animasi Radar Link */
    .pulse-dot-link {
        width: 6px; height: 6px; border-radius: 50%; display: inline-block;
        background-color: #22c55e;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); animation: pulsing-link 1.5s infinite;
    }
    @keyframes pulsing-link {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(34, 197, 94, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }

    /* Tombol Aksi */
    .btn-action-outline {
        width: 34px;
        height: 34px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
        background: transparent;
    }
    .btn-action-outline:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }
</style>

<div class="card space-bg-card mb-4 mt-2">
    <div class="card-body p-3">
        <form action="{{ route('ground-stations.index') }}" method="GET" class="m-0">
            <div class="row g-2 align-items-center">

                <div class="col-12 col-md-12 col-lg-5 position-relative">
                    <i class="fas fa-search input-icon-search"></i>
                    <input type="text" name="search" class="form-control search-modern w-100 ps-4"
                           placeholder="Cari nama stasiun atau lokasi..." value="{{ request('search') }}">
                </div>

                <div class="col-6 col-md-4 col-lg-3">
                    <select name="country" class="form-select search-modern text-muted">
                        <option value="">Semua Negara</option>
                        @if(isset($countries))
                            @foreach($countries as $country)
                                <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>

                <div class="col-6 col-md-4 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-bold rounded-2 px-3 flex-grow-1" style="height: 40px;">
                        Terapkan
                    </button>
                    @if(request()->anyFilled(['search', 'country']))
                        <a href="{{ route('ground-stations.index') }}" class="btn btn-white fw-bold rounded-2 border shadow-sm px-3" style="height: 40px; display:flex; align-items:center;" title="Reset Filter">
                            <i class="fas fa-undo text-muted"></i>
                        </a>
                    @endif
                </div>

                <div class="col-12 col-md-4 col-lg-2 ms-auto text-lg-end mt-3 mt-lg-0">
                    <a href="{{ route('ground-stations.create') }}" class="btn btn-dark fw-bold rounded-2 px-3 w-100" style="height: 40px; display:inline-flex; align-items:center; justify-content:center;">
                        <i class="fas fa-plus me-2 text-info"></i> Registrasi Node
                    </a>
                </div>

            </div>
        </form>
    </div>
</div>

<div class="card space-bg-card overflow-hidden">
    <div class="table-responsive">
        <table class="table table-vcenter table-modern mb-0">
            <thead>
                <tr>
                    <th>Identitas Stasiun</th>
                    <th>Wilayah Operasional</th>
                    <th>Koordinat & Ketinggian</th>
                    <th class="text-center">Koneksi Satelit</th>
                    <th class="text-end pe-4">Aksi Kontrol</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groundStations as $station)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="station-icon-box shadow-sm me-3">
                                <i class="fas fa-satellite-dish fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-bolder text-dark fs-4 mb-1" style="letter-spacing: -0.2px; line-height: 1.1;">{{ $station->name }}</div>
                                <div class="text-muted small font-monospace mt-1">
                                    <i class="fas fa-hashtag me-1" style="font-size: 0.6rem;"></i>GS-{{ str_pad($station->id, 4, '0', STR_PAD_LEFT) }}
                                </div>
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="d-flex flex-column gap-1">
                            <div class="fw-bold text-dark d-flex align-items-center" style="font-size: 0.9rem;">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i> {{ $station->location }}
                            </div>
                            <div class="d-flex align-items-center mt-1 text-muted" style="font-size: 0.85rem;">
                                <i class="fas fa-globe-americas text-muted me-2"></i> {{ $station->country }}
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="coord-box shadow-sm">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-muted">LAT</span>
                                <span class="text-dark fw-bold">{{ number_format($station->latitude, 5) }}&deg;</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pb-1">
                                <span class="text-muted">LNG</span>
                                <span class="text-dark fw-bold">{{ number_format($station->longitude, 5) }}&deg;</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-top pt-1 mt-1 border-gray-200">
                                <span class="text-muted fw-bold text-primary">ALT</span>
                                <span class="text-primary fw-bolder">{{ number_format($station->altitude ?? 0, 0) }} m</span>
                            </div>
                        </div>
                    </td>

                    <td class="text-center">
                        @php
                            $satCount = $station->satellites_count ?? $station->satellites->count();
                        @endphp

                        @if($satCount > 0)
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <span class="badge bg-success-lt text-success px-3 py-1.5 rounded-pill fw-bold border border-success-lt mb-1 shadow-sm d-inline-flex align-items-center">
                                    <i class="fas fa-link me-2"></i> {{ $satCount }} Armada
                                </span>
                                <span class="text-success fw-bolder mt-1 d-flex align-items-center gap-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    <span class="pulse-dot-link"></span> ACTIVE LINK
                                </span>
                            </div>
                        @else
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <span class="badge bg-light text-secondary px-3 py-1.5 rounded-pill fw-bold border mb-1 d-inline-flex align-items-center">
                                    <i class="fas fa-unlink me-2"></i> 0 Armada
                                </span>
                                <span class="text-muted fw-bold mt-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">STANDBY</span>
                            </div>
                        @endif
                    </td>

                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">

                            <a href="{{ route('ground-stations.show', $station->id) }}" class="btn btn-outline-info btn-action-outline" title="Lihat Peta Stasiun" data-bs-toggle="tooltip">
                                <i class="fas fa-map-marked-alt"></i>
                            </a>

                            <a href="{{ route('ground-stations.edit', $station->id) }}" class="btn btn-outline-warning btn-action-outline" title="Konfigurasi Data" data-bs-toggle="tooltip">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('ground-stations.destroy', $station->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Peringatan: Menghapus stasiun ini akan memutuskan koneksi ke semua armada satelit yang terikat. Lanjutkan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-action-outline" title="Hapus Stasiun" data-bs-toggle="tooltip">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="text-muted mb-3">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center shadow-inner" style="width: 80px; height: 80px;">
                                <i class="fas fa-satellite-dish fa-2x text-muted opacity-50"></i>
                            </div>
                        </div>
                        <h4 class="text-dark fw-bold mb-1">Infrastruktur Kosong</h4>
                        <p class="text-muted small mb-3">Belum ada stasiun bumi yang terdaftar di database sistem.</p>
                        <a href="{{ route('ground-stations.create') }}" class="btn btn-dark btn-sm rounded-2 fw-bold px-3 py-2 shadow-sm">
                            <i class="fas fa-plus me-1 text-info"></i> Registrasi Node Baru
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($groundStations->hasPages())
    <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
        <div class="text-muted small fw-medium">
            Menampilkan <span class="fw-bold text-dark">{{ $groundStations->firstItem() ?? 0 }}</span> - <span class="fw-bold text-dark">{{ $groundStations->lastItem() ?? 0 }}</span> dari total <span class="fw-bold text-dark">{{ $groundStations->total() }}</span> stasiun.
        </div>
        <div class="m-0 pagination-modern">
            {{ $groundStations->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
