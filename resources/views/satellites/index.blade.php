@extends('layouts.admin')

@section('title', 'Manajemen Armada Satelit')
@section('page-title', 'Direktori Armada Satelit')
@section('page-subtitle', 'Pusat kendali, filter inventaris, dan pemantauan status pembaruan Epoch TLE seluruh armada.')
@section('page-icon', 'fas fa-satellite')

@section('content')
<style>
    /* Animasi Radar Hijau */
    .pulse-dot {
        width: 8px; height: 8px; border-radius: 50%; display: inline-block;
        background-color: #22c55e;
        box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); animation: pulsing 1.5s infinite;
    }
    @keyframes pulsing {
        0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(34, 197, 94, 0); }
        100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
    }

    /* CARD & FILTER OVERRIDES */
    .space-bg-card {
        background: #ffffff; border: 1px solid #e2e8f0;
        border-radius: 14px; box-shadow: 0 4px 20px rgba(15, 23, 42, 0.03);
    }
    .search-modern {
        border-radius: 10px; border: 1px solid #cbd5e1;
        font-size: 0.85rem; transition: all 0.2s; background-color: #f8fafc; height: 42px;
    }
    .search-modern:focus {
        border-color: #3b82f6; background-color: #ffffff;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1); outline: none;
    }
    .input-icon-search {
        position: absolute; left: 16px; top: 50%; transform: translateY(-50%);
        color: #94a3b8; font-size: 0.9rem;
    }

    /* TABLE TYPOGRAPHY */
    .table-modern th {
        background-color: #f8fafc !important; text-transform: uppercase;
        font-size: 0.7rem; letter-spacing: 0.5px; font-weight: 800;
        color: #64748b; border-bottom: 2px solid #e2e8f0; padding: 1.2rem 1rem;
    }
    .table-modern td {
        padding: 1rem; vertical-align: middle; border-bottom: 1px solid #f1f5f9;
    }
    .table-modern tbody tr:hover { background-color: #fbfcfe; }

    /* ICONS, BADGES & BUTTONS */
    .sat-icon-box {
        width: 44px; height: 44px; border-radius: 10px; display: flex;
        align-items: center; justify-content: center; flex-shrink: 0;
        background: rgba(59, 130, 246, 0.08); border: 1px solid rgba(59, 130, 246, 0.15);
    }
    .epoch-badge {
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
        background: #0f172a; color: #38bdf8; padding: 4px 10px; border-radius: 8px;
        font-size: 0.65rem; font-weight: 600; border: 1px solid rgba(56, 189, 248, 0.2);
        display: inline-flex; align-items: center; margin-top: 6px; box-shadow: 0 2px 4px rgba(15, 23, 42, 0.1);
    }
    .btn-action-outline {
        width: 36px; height: 36px; padding: 0; display: inline-flex;
        align-items: center; justify-content: center; border-radius: 8px;
        transition: all 0.2s; background: #ffffff; border: 1px solid #e2e8f0;
    }
    .btn-action-outline:hover {
        transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }

    /* MICRO-CARDS (Style Spesifikasi & Node) */
    .mini-avatar-box {
        width: 24px; height: 24px; border-radius: 6px; display: flex;
        align-items: center; justify-content: center; background: #f1f5f9;
        border: 1px solid #e2e8f0; box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .node-badge {
        display: inline-flex; align-items: center; padding: 0.35rem 0.6rem;
        border-radius: 8px; border: 1px solid transparent; font-size: 0.75rem; font-weight: 700;
    }
    .node-badge-success { background: rgba(34, 197, 94, 0.08); border-color: rgba(34, 197, 94, 0.2); color: #16a34a; }
    .node-badge-light { background: #f8fafc; border-color: #e2e8f0; color: #64748b; }
    .node-icon {
        width: 20px; height: 20px; border-radius: 50%; display: flex;
        align-items: center; justify-content: center; margin-right: 8px; background: #ffffff;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
</style>

<div class="card space-bg-card mb-4 mt-2">
    <div class="card-body p-3">
        <form action="{{ route('satellites.index') }}" method="GET" class="m-0">
            <div class="row g-2 align-items-center">
                <div class="col-12 col-lg-4 position-relative">
                    <i class="fas fa-search input-icon-search"></i>
                    <input type="text" name="search" class="form-control search-modern w-100 ps-5"
                           placeholder="Cari nama armada atau ID..." value="{{ request('search') }}">
                </div>
                <div class="col-6 col-lg-2">
                    <select name="country" class="form-select search-modern text-muted fw-medium">
                        <option value="">Semua Negara</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-6 col-lg-2">
                    <select name="orbit" class="form-select search-modern text-muted fw-medium">
                        <option value="">Semua Orbit</option>
                        <option value="LEO" {{ request('orbit') == 'LEO' ? 'selected' : '' }}>LEO</option>
                        <option value="MEO" {{ request('orbit') == 'MEO' ? 'selected' : '' }}>MEO</option>
                        <option value="GEO" {{ request('orbit') == 'GEO' ? 'selected' : '' }}>GEO</option>
                    </select>
                </div>
                <div class="col-12 col-lg-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-bold rounded-3 flex-grow-1" style="height: 42px;">
                        Filter Data
                    </button>
                    @if(request()->anyFilled(['search', 'country', 'orbit']))
                        <a href="{{ route('satellites.index') }}" class="btn btn-white rounded-3 border shadow-sm px-3" style="height: 42px; display:flex; align-items:center;" title="Reset Filter">
                            <i class="fas fa-undo text-muted"></i>
                        </a>
                    @endif
                </div>
                <div class="col-12 col-lg-2 text-lg-end">
                    <a href="{{ route('satellites.create') }}" class="btn btn-dark fw-bold rounded-3 w-100 shadow-sm" style="height: 42px; display:inline-flex; align-items:center; justify-content:center;">
                        <i class="fas fa-plus me-2 text-info"></i> Tambah Armada
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
                    <th>Identitas Armada</th>
                    <th>Spesifikasi Asal & Sistem</th>
                    <th>Node Stasiun Bumi</th>
                    <th class="text-center">Status Jaringan</th>
                    <th class="text-end pe-4">Aksi Kontrol</th>
                </tr>
            </thead>
            <tbody>
                @forelse($satellites as $sat)
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="sat-icon-box text-blue me-3">
                                <i class="fas fa-satellite fs-4"></i>
                            </div>
                            <div>
                                <div class="fw-bolder text-dark fs-4" style="letter-spacing: -0.2px; line-height: 1;">{{ $sat->name }}</div>

                                @if($sat->tle_line1 && strlen($sat->tle_line1) >= 32)
                                    @php
                                        $epochYear = substr($sat->tle_line1, 18, 2);
                                        $epochDay = substr($sat->tle_line1, 20, 6);
                                    @endphp
                                    <div class="epoch-badge">
                                        <i class="fas fa-clock text-warning me-1"></i> EPOCH: 20{{ $epochYear }} / D.{{ $epochDay }}
                                    </div>
                                @else
                                    <div class="badge bg-red-lt text-red fw-bold rounded-2 px-2 mt-2 border border-red-lt" style="font-size: 0.65rem;">
                                        <i class="fas fa-exclamation-triangle me-1"></i> TLE MISSING
                                    </div>
                                @endif
                            </div>
                        </div>
                    </td>

                    <td>
                        <div class="d-flex flex-column gap-2">
                            <div class="d-flex align-items-center">
                                <div class="mini-avatar-box me-2">
                                    <i class="fas fa-globe-americas text-primary" style="font-size: 0.75rem;"></i>
                                </div>
                                <span class="fw-bold text-dark" style="font-size: 0.85rem;">{{ $sat->origin_country ?? $sat->country ?? 'Global' }}</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="px-2 py-1 bg-azure-lt text-azure fw-bold rounded-2 border border-azure-lt d-inline-flex align-items-center" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    <i class="fas fa-route me-1" style="font-size: 0.55rem;"></i> {{ $sat->orbit_type ?? 'LEO' }}
                                </span>
                                <span class="px-2 py-1 bg-light text-secondary fw-bold rounded-2 border font-monospace" style="font-size: 0.65rem;">
                                    <i class="fas fa-hashtag text-muted me-1" style="font-size: 0.55rem;"></i>SAT-{{ str_pad($sat->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                        </div>
                    </td>

                    <td>
                        @if($sat->groundStation)
                            <div class="node-badge node-badge-success">
                                <div class="node-icon text-success">
                                    <i class="fas fa-satellite-dish" style="font-size: 0.6rem;"></i>
                                </div>
                                {{ $sat->groundStation->name }}
                            </div>
                        @else
                            <div class="node-badge node-badge-light">
                                <div class="node-icon text-muted">
                                    <i class="fas fa-unlink" style="font-size: 0.6rem;"></i>
                                </div>
                                Belum Terkonfigurasi
                            </div>
                        @endif
                    </td>

                    <td class="text-center">
                        @if(strtolower($sat->status) == 'active' || strtolower($sat->status) == 'aktif')
                            <div class="d-inline-flex align-items-center px-3 py-1.5 rounded-pill border border-success bg-success-lt text-success fw-bold shadow-sm" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                <span class="pulse-dot me-2"></span> AKTIF
                            </div>
                        @else
                            <div class="d-inline-flex align-items-center px-3 py-1.5 rounded-pill border border-danger bg-danger-lt text-danger fw-bold shadow-sm" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                <i class="fas fa-power-off me-1"></i> OFFLINE
                            </div>
                        @endif
                    </td>

                    <td class="text-end pe-4">
                        <div class="d-flex justify-content-end gap-2">

                            <form action="{{ route('satellites.sync-tle', $sat->id) }}" method="POST" class="d-inline form-sync-tle">
                                @csrf
                                <button type="submit" class="btn-action-outline text-success border-success-lt bg-success-lt" title="Sync TLE" data-bs-toggle="tooltip">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </form>

                            <a href="{{ route('satellites.show', $sat->id) }}" class="btn-action-outline text-info border-info-lt bg-info-lt" title="Lihat Radar Monitor" data-bs-toggle="tooltip">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('satellites.edit', $sat->id) }}" class="btn-action-outline text-warning border-warning-lt bg-warning-lt" title="Konfigurasi Data" data-bs-toggle="tooltip">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('satellites.destroy', $sat->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Menghapus armada akan memutus koneksi stasiun bumi. Lanjutkan?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action-outline text-danger border-danger-lt bg-danger-lt" title="Hapus Armada" data-bs-toggle="tooltip">
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
                                <i class="fas fa-satellite-slash fa-2x text-muted opacity-50"></i>
                            </div>
                        </div>
                        <h4 class="text-dark fw-bold mb-1">Direktori Kosong</h4>
                        <p class="text-muted small mb-3">Belum ada armada satelit yang terdaftar di database.</p>
                        <a href="{{ route('satellites.create') }}" class="btn btn-dark btn-sm rounded-3 fw-bold px-3 py-2 shadow-sm">
                            <i class="fas fa-plus me-1 text-info"></i> Tambah Armada Baru
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($satellites->hasPages())
    <div class="card-footer bg-white border-top py-3 d-flex justify-content-between align-items-center">
        <div class="text-muted small fw-medium">
            Menampilkan <span class="fw-bold text-dark">{{ $satellites->firstItem() ?? 0 }}</span> - <span class="fw-bold text-dark">{{ $satellites->lastItem() ?? 0 }}</span> dari total <span class="fw-bold text-dark">{{ $satellites->total() }}</span> armada.
        </div>
        <div class="m-0 pagination-modern">
            {{ $satellites->links('pagination::bootstrap-5') }}
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Tooltip Interaktif untuk tombol aksi
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // 2. Animasi Loading Spinner pada tombol Sync TLE
        document.querySelectorAll('.form-sync-tle').forEach(function(form) {
            form.addEventListener('submit', function() {
                var btn = this.querySelector('button');
                var icon = btn.querySelector('i');

                // Hapus ikon sync biasa dan ubah jadi ikon spinner putar bawaan font-awesome
                icon.classList.remove('fa-sync-alt');
                icon.classList.add('fa-spinner', 'fa-spin');

                // Mencegah double click
                btn.style.opacity = '0.7';
                btn.style.pointerEvents = 'none';
            });
        });
    });
</script>
@endpush
