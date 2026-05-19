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

    /* GPS Coordinate Highlight */
    .gps-value {
        font-family: 'JetBrains Mono', 'Fira Code', 'Courier New', monospace;
        color: #3b82f6 !important;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    /* Table modern styling */
    .modern-table th {
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        color: #64748b;
        background-color: #f8fafc !important;
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    .modern-table td {
        vertical-align: middle;
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
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
                <div class="bg-indigo-lt p-2 rounded-3 me-3 text-indigo d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                    <i class="fas fa-satellite fs-4"></i>
                </div>
                <h3 class="card-title fw-bold text-dark m-0">Armada Satelit yang Dipantau ({{ $groundStation->satellites_count ?? $groundStation->satellites->count() }})</h3>
            </div>

            <div class="table-responsive">
                <table class="table table-vcenter modern-table card-table table-striped mb-0">
                    <thead>
                        <tr>
                            <th class="w-1 text-center">#</th>
                            <th>Nama Satelit</th>
                            <th>Tipe Orbit</th>
                            <th>Status</th>
                            <th class="w-1">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groundStation->satellites as $index => $satellite)
                        <tr>
                            <td class="text-center text-muted">{{ $index + 1 }}</td>
                            <td class="fw-bold text-dark fs-4">{{ $satellite->name }}</td>
                            <td>
                                @if($satellite->orbit_type == 'LEO')
                                    <span class="badge bg-cyan-lt fw-bold">{{ $satellite->orbit_type }}</span>
                                @elseif($satellite->orbit_type == 'GEO')
                                    <span class="badge bg-purple-lt fw-bold">{{ $satellite->orbit_type }}</span>
                                @else
                                    <span class="badge bg-blue-lt fw-bold">{{ $satellite->orbit_type }}</span>
                                @endif
                            </td>
                            <td>
                                @if($satellite->status == 'active')
                                    <span class="badge bg-green-lt fw-bold px-2 py-1"><i class="fas fa-check-circle me-1"></i> Active</span>
                                @else
                                    <span class="badge bg-red-lt fw-bold px-2 py-1"><i class="fas fa-times-circle me-1"></i> Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('satellites.show', $satellite->id) }}" class="btn btn-sm btn-light border shadow-sm fw-medium">
                                    <i class="fas fa-eye me-1 text-blue"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <div class="d-flex flex-column align-items-center justify-content-center py-4">
                                    <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3" style="width:60px; height:60px;">
                                        <i class="fas fa-satellite-dish fs-2 text-secondary opacity-50"></i>
                                    </div>
                                    <span class="fw-medium">Belum ada satelit yang terhubung ke stasiun ini.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
