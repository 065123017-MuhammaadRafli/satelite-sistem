@extends('layouts.admin')

@section('title', 'Detail Stasiun Bumi')
@section('page-title', 'Pusat Kendali Infrastruktur Bumi')
@section('page-subtitle', 'Informasi spesifikasi teknis, koordinat geografis, dan jangkauan operasional stasiun bumi.')
@section('page-icon', 'fas fa-satellite-dish')

@push('styles')
<style>
    /* Styling Card & Box Modern */
    .glass-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }

    .info-box-modern {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 14px 16px;
        background-color: #ffffff;
        display: flex;
        align-items: center;
        transition: all 0.2s;
    }

    .info-box-modern:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 12px rgba(0,0,0,0.03);
    }

    .info-icon-wrapper {
        width: 42px; height: 42px;
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 14px; flex-shrink: 0;
    }

    /* Box Khusus Koordinat 3 Kolom */
    .coord-box-mini {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 12px 8px;
        background-color: #f8fafc;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        height: 100%;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <a href="{{ route('ground-stations.index') }}" class="btn btn-white shadow-sm border fw-bold px-3">
        <i class="fas fa-arrow-left me-2 text-muted"></i> Kembali ke Daftar
    </a>
    <a href="{{ route('ground-stations.edit', $groundStation->id) }}" class="btn btn-warning shadow-sm fw-bold px-4 text-dark">
        <i class="fas fa-edit me-2"></i> Edit Stasiun
    </a>
</div>

<div class="row">
    <div class="col-lg-5 col-xl-5 mb-4">
        <div class="card glass-card h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2">
                <h3 class="card-title fw-bold m-0 d-flex align-items-center fs-3">
                    <div class="bg-blue-lt text-blue rounded p-2 me-3 shadow-sm"><i class="fas fa-info-circle fa-sm"></i></div>
                    Informasi & Identitas Stasiun
                </h3>
            </div>

            <div class="card-body">
                <div class="info-box-modern mb-3 shadow-sm">
                    <div class="info-icon-wrapper bg-blue-lt text-blue">
                        <i class="fas fa-satellite-dish fa-lg"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">NAMA STASIUN BUMI</div>
                        <div class="fw-bolder text-dark fs-4" style="line-height: 1.2;">{{ $groundStation->name }}</div>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <div class="info-box-modern h-100 shadow-sm px-3">
                            <div class="info-icon-wrapper bg-pink-lt text-pink" style="width: 32px; height: 32px; margin-right: 10px;">
                                <i class="fas fa-flag fa-sm"></i>
                            </div>
                            <div class="overflow-hidden">
                                <div class="text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">NEGARA</div>
                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;">{{ $groundStation->country }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="info-box-modern h-100 shadow-sm px-3">
                            <div class="info-icon-wrapper bg-cyan-lt text-cyan" style="width: 32px; height: 32px; margin-right: 10px;">
                                <i class="fas fa-map-marker-alt fa-sm"></i>
                            </div>
                            <div class="overflow-hidden">
                                <div class="text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">WILAYAH</div>
                                <div class="fw-bold text-dark text-truncate" style="font-size: 0.9rem;">{{ $groundStation->location }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <div class="coord-box-mini shadow-sm">
                            <div class="text-primary mb-1 mt-1"><i class="fas fa-arrows-alt-v fa-lg"></i></div>
                            <div class="text-muted fw-bold mt-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">LINTANG (LAT)</div>
                            <div class="fw-bolder text-dark font-monospace mt-1" style="font-size: 0.8rem;">{{ number_format($groundStation->latitude, 5) }}&deg;</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="coord-box-mini shadow-sm">
                            <div class="text-primary mb-1 mt-1"><i class="fas fa-arrows-alt-h fa-lg"></i></div>
                            <div class="text-muted fw-bold mt-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">BUJUR (LNG)</div>
                            <div class="fw-bolder text-dark font-monospace mt-1" style="font-size: 0.8rem;">{{ number_format($groundStation->longitude, 5) }}&deg;</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="coord-box-mini shadow-sm" style="background: rgba(34, 197, 94, 0.05); border-color: rgba(34, 197, 94, 0.2);">
                            <div class="text-success mb-1 mt-1"><i class="fas fa-mountain fa-lg"></i></div>
                            <div class="text-success fw-bold mt-1" style="font-size: 0.6rem; letter-spacing: 0.5px;">ELEVASI (ALT)</div>
                            <div class="fw-bolder text-success font-monospace mt-1" style="font-size: 0.8rem;">{{ number_format($groundStation->altitude ?? 0, 0) }} m</div>
                        </div>
                    </div>
                </div>

                <div class="info-box-modern mb-4 shadow-sm">
                    <div class="info-icon-wrapper bg-success-lt text-success">
                        <i class="fas fa-link"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">KONEKSI AKTIF SAAT INI</div>
                        <div class="fw-bolder text-dark fs-4">
                            @php $satCount = $groundStation->satellites->count() ?? 0; @endphp
                            {{ $satCount }} Unit Armada
                        </div>
                    </div>
                </div>

                <h4 class="fw-bold text-dark fs-5 mb-2"><i class="fas fa-align-left text-muted me-2" style="font-size: 1rem;"></i> Catatan & Deskripsi Tambahan</h4>
                <div class="p-3 bg-light rounded-3 border text-muted" style="font-size: 0.9rem; min-height: 80px; line-height: 1.6;">
                    {{ $groundStation->description ?: 'Tidak ada catatan tambahan untuk stasiun ini.' }}
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7 col-xl-7">
        <div class="card glass-card h-100">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-3">
                <h3 class="card-title fw-bold m-0 d-flex align-items-center fs-3">
                    <div class="bg-indigo-lt text-indigo rounded p-2 me-3 shadow-sm"><i class="fas fa-satellite fa-sm"></i></div>
                    Armada Satelit yang Dipantau ({{ $groundStation->satellites->count() ?? 0 }})
                </h3>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-vcenter table-hover table-nowrap mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">NO</th>
                                <th class="text-muted fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">NAMA SATELIT</th>
                                <th class="text-muted fw-bold text-center" style="font-size: 0.7rem; letter-spacing: 0.5px;">TIPE ORBIT</th>
                                <th class="text-muted fw-bold text-center" style="font-size: 0.7rem; letter-spacing: 0.5px;">STATUS</th>
                                <th class="text-muted fw-bold text-end" style="font-size: 0.7rem; letter-spacing: 0.5px; padding-right: 1.5rem;">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groundStation->satellites as $index => $sat)
                            <tr>
                                <td class="text-muted text-center" style="width: 50px;">{{ $index + 1 }}</td>
                                <td class="fw-bold text-dark fs-5">{{ $sat->name }}</td>
                                <td class="text-center">
                                    <span class="badge bg-cyan-lt text-cyan px-2 py-1">{{ $sat->orbit_type ?? 'LEO' }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-success-lt text-success px-2 py-1"><i class="fas fa-check-circle me-1"></i> Active</span>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('satellites.show', $sat->id) }}" class="btn btn-sm btn-outline-primary fw-bold rounded-2">
                                        <i class="fas fa-eye me-1"></i> View
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted mb-2"><i class="fas fa-unlink fa-3x opacity-25"></i></div>
                                    <div class="fw-bold text-dark fs-4">Belum Ada Satelit</div>
                                    <div class="small text-muted mt-1">Stasiun ini belum dihubungkan dengan armada satelit mana pun di database.</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
