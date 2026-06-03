@extends('layouts.admin')

@section('title', 'Direktori Armada Satelit')
@section('page-title', 'Direktori Armada Satelit')
@section('page-subtitle', 'Pusat kendali, filter inventaris, dan pemantauan status pembaruan Epoch TLE seluruh armada.')
@section('page-icon', 'fas fa-satellite')

@push('styles')
<style>
    /* Styling Filter Bar */
    .filter-bar {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.02);
    }

    /* Styling List Card Modern (ASTRALINK Theme) */
    .sat-list-header {
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 1px;
        padding-bottom: 12px;
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 16px;
    }

    .sat-card-row {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 20px;
        margin-bottom: 12px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(0,0,0,0.01);
        display: flex;
        align-items: center;
    }

    .sat-card-row:hover {
        border-color: #cbd5e1;
        box-shadow: 0 8px 20px rgba(0,0,0,0.04);
        transform: translateY(-2px);
    }

    /* Ikon Kiri */
    .sat-icon-box {
        width: 46px; height: 46px;
        background-color: #eff6ff;
        color: #3b82f6;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
        margin-right: 16px;
    }

    /* Badge Epoch Presisi ala Cyber Terminal */
    .epoch-badge {
        display: inline-flex; align-items: center; gap: 6px;
        background-color: #0f172a;
        color: #f8fafc;
        padding: 4px 10px;
        border-radius: 6px;
        font-family: 'JetBrains Mono', 'Courier New', monospace;
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-top: 6px;
        border: 1px solid #1e293b;
        box-shadow: inset 0 2px 4px rgba(0,0,0,0.5);
    }
    .epoch-badge i { color: #f59e0b; }

    /* Info Badge Kecil */
    .info-tag {
        display: inline-flex; align-items: center; gap: 4px;
        border: 1px solid #e2e8f0;
        padding: 3px 8px; border-radius: 6px;
        font-size: 0.7rem; font-weight: 700; color: #64748b;
        background-color: #f8fafc;
    }

    /* Tombol Aksi */
    .action-btn-circle {
        width: 36px; height: 36px;
        border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        border: 1px solid #e2e8f0;
        background-color: #ffffff;
        color: #64748b;
        transition: all 0.2s;
    }
    .action-btn-circle:hover { background-color: #f1f5f9; color: #0f172a; border-color: #cbd5e1; }

    .btn-sync { color: #22c55e; background-color: #f0fdf4; border-color: #bbf7d0; }
    .btn-sync:hover { background-color: #dcfce7; color: #16a34a; border-color: #86efac; }

    .btn-view { color: #3b82f6; background-color: #eff6ff; border-color: #bfdbfe; }
    .btn-view:hover { background-color: #dbeafe; color: #2563eb; border-color: #93c5fd; }

    .btn-edit { color: #f59e0b; background-color: #fffbeb; border-color: #fde68a; }
    .btn-edit:hover { background-color: #fef3c7; color: #d97706; border-color: #fcd34d; }

    .btn-delete { color: #ef4444; background-color: #fef2f2; border-color: #fecaca; }
    .btn-delete:hover { background-color: #fee2e2; color: #dc2626; border-color: #fca5a5; }

    /* Kolom Responsif */
    .col-identitas { flex: 0 0 35%; max-width: 35%; }
    .col-spesifikasi { flex: 0 0 20%; max-width: 20%; }
    .col-stasiun { flex: 0 0 20%; max-width: 20%; }
    .col-status { flex: 0 0 10%; max-width: 10%; text-align: center; }
    .col-aksi { flex: 1; text-align: right; }
</style>
@endpush

@section('content')

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm py-3 px-4 mb-4 rounded-3 d-flex align-items-center">
        <i class="fas fa-check-circle fs-4 me-2 text-success"></i>
        <div class="fw-bold">{{ session('success') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm py-3 px-4 mb-4 rounded-3 d-flex align-items-center">
        <i class="fas fa-exclamation-triangle fs-4 me-2 text-danger"></i>
        <div class="fw-bold">{{ session('error') }}</div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="close"></button>
    </div>
@endif

<div class="container-xl p-0">

    <div class="filter-bar">
        <form action="{{ route('satellites.index') }}" method="GET" class="row g-3 align-items-center">

            <div class="col-12 col-md-4">
                <div class="input-icon">
                    <span class="input-icon-addon"><i class="fas fa-search text-muted"></i></span>
                    <input type="text" name="search" class="form-control fw-medium bg-light border-0" placeholder="Cari nama armada atau ID..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="col-12 col-md-2">
                <select name="country" class="form-select fw-medium bg-light border-0">
                    <option value="">Semua Negara</option>
                    @foreach($countries as $c)
                        @if($c) <option value="{{ $c }}" {{ request('country') == $c ? 'selected' : '' }}>{{ $c }}</option> @endif
                    @endforeach
                </select>
            </div>

            <div class="col-12 col-md-2">
                <select name="orbit" class="form-select fw-medium bg-light border-0">
                    <option value="">Semua Orbit</option>
                    <option value="LEO" {{ request('orbit') == 'LEO' ? 'selected' : '' }}>LEO</option>
                    <option value="MEO" {{ request('orbit') == 'MEO' ? 'selected' : '' }}>MEO</option>
                    <option value="GEO" {{ request('orbit') == 'GEO' ? 'selected' : '' }}>GEO</option>
                </select>
            </div>

            <div class="col-12 col-md-4 d-flex justify-content-end gap-2">
                <button type="submit" class="btn btn-primary fw-bold px-4 shadow-sm">
                    Filter Data
                </button>
                <a href="{{ route('satellites.create') }}" class="btn btn-dark fw-bold px-4 shadow-sm d-flex align-items-center">
                    <i class="fas fa-plus me-2"></i> Tambah Armada
                </a>
            </div>
        </form>
    </div>

    <div class="d-none d-lg-flex sat-list-header px-4">
        <div class="col-identitas">IDENTITAS ARMADA & EPOCH TLE</div>
        <div class="col-spesifikasi">SPESIFIKASI ASAL</div>
        <div class="col-stasiun">NODE STASIUN BUMI</div>
        <div class="col-status">STATUS JARINGAN</div>
        <div class="col-aksi">AKSI KONTROL</div>
    </div>

    <div class="d-flex flex-column">
        @forelse($satellites as $sat)
            <div class="sat-card-row">

                <div class="col-identitas d-flex align-items-start">
                    <div class="sat-icon-box shadow-sm">
                        <i class="fas fa-satellite"></i>
                    </div>
                    <div>
                        <div class="fw-bolder text-dark fs-4" style="line-height: 1.2;">{{ $sat->name }}</div>

                        @php
                            $epochStr = 'Belum Ada Data TLE';
                            if(!empty($sat->tle_line1) && strlen($sat->tle_line1) > 32) {
                                try {
                                    $yy = substr($sat->tle_line1, 18, 2);
                                    $year = ($yy > 50 ? 1900 : 2000) + (int)$yy;
                                    $dayOfYear = (float)substr($sat->tle_line1, 20, 12);

                                    $epochDate = \DateTime::createFromFormat('Y z', $year . ' ' . (floor($dayOfYear) - 1));
                                    if($epochDate) {
                                        $fraction = $dayOfYear - floor($dayOfYear);
                                        $seconds = round($fraction * 86400);

                                        // Menghitung Milidetik (3 digit)
                                        $milliseconds = round(($fraction * 86400 - $seconds) * 1000);
                                        $milliseconds = abs($milliseconds); // Pastikan positif
                                        $msString = str_pad($milliseconds, 3, '0', STR_PAD_LEFT);

                                        $epochDate->add(new \DateInterval('PT' . $seconds . 'S'));

                                        // FORMAT: 2026-05-30 18:43:29.089 UTC
                                        $epochStr = $epochDate->format('Y-m-d H:i:s') . '.' . $msString . ' UTC';
                                    }
                                } catch(\Exception $e) {
                                    $epochStr = 'Format TLE Tidak Valid';
                                }
                            }
                        @endphp

                        @if($epochStr != 'Belum Ada Data TLE' && $epochStr != 'Format TLE Tidak Valid')
                            <div class="epoch-badge" title="Waktu Kalkulasi TLE Terakhir">
                                <i class="fas fa-clock"></i> EPOCH: {{ $epochStr }}
                            </div>
                        @else
                            <div class="epoch-badge" style="background-color: #fef2f2; color: #dc2626; border-color: #fecaca; box-shadow: none;">
                                <i class="fas fa-exclamation-triangle text-danger"></i> {{ $epochStr }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-spesifikasi">
                    <div class="fw-bold text-dark mb-1 d-flex align-items-center">
                        <i class="fas fa-globe-americas text-muted me-2"></i> {{ $sat->country ?? 'Global' }}
                    </div>
                    <div class="d-flex gap-2">
                        <span class="info-tag text-blue border-blue-lt bg-blue-lt">
                            <i class="fas fa-ring"></i> {{ $sat->orbit_type ?? 'LEO' }}
                        </span>
                        <span class="info-tag">
                            # SAT-{{ str_pad($sat->id, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    </div>
                </div>

                <div class="col-stasiun">
                    @if($sat->groundStation)
                        <div class="info-tag text-success border-success-lt bg-success-lt px-3 py-2">
                            <i class="fas fa-satellite-dish me-1"></i> {{ $sat->groundStation->name }}
                        </div>
                    @else
                        <div class="info-tag text-muted px-3 py-2">
                            <i class="fas fa-unlink me-1"></i> Belum Terikat
                        </div>
                    @endif
                </div>

                <div class="col-status">
                    @if($sat->status == 'active')
                        <div class="d-inline-flex align-items-center text-success fw-bold" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                            <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> AKTIF
                        </div>
                    @else
                        <div class="d-inline-flex align-items-center text-danger fw-bold" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                            <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i> OFFLINE
                        </div>
                    @endif
                </div>

                <div class="col-aksi">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('satellites.sync-tle', $sat->id) }}" class="action-btn-circle btn-sync" title="Sync TLE Sekarang">
                            <i class="fas fa-sync-alt"></i>
                        </a>
                        <a href="{{ route('satellites.show', $sat->id) }}" class="action-btn-circle btn-view" title="Detail Satelit">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('satellites.edit', $sat->id) }}" class="action-btn-circle btn-edit" title="Edit Data">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('satellites.destroy', $sat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus armada satelit ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn-circle btn-delete" title="Hapus Permanen">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div> @empty
            <div class="text-center py-5 bg-white rounded-3 border">
                <i class="fas fa-satellite-dish fa-3x text-muted mb-3 opacity-50"></i>
                <h3 class="fw-bold text-dark">Tidak Ada Armada Ditemukan</h3>
                <p class="text-muted">Inventaris satelit Anda masih kosong atau tidak cocok dengan filter pencarian.</p>
                <a href="{{ route('satellites.create') }}" class="btn btn-primary fw-bold mt-2">
                    <i class="fas fa-plus me-2"></i> Tambah Armada Pertama
                </a>
            </div>
        @endforelse
    </div>

    @if($satellites->hasPages())
    <div class="d-flex justify-content-center mt-4 mb-5">
        {{ $satellites->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    @endif

</div>
@endsection
