@extends('layouts.admin')

@section('title', 'Data Satelit')
@section('page-title', 'Manajemen Satelit')
@section('page-subtitle', 'Kelola, filter, dan pantau seluruh satelit yang terdaftar di sistem.')
@section('page-icon', 'fas fa-satellite')

@section('content')
<div class="row mb-3 align-items-center d-print-none">
    <div class="col-auto ms-auto d-print-none">
        <a href="{{ route('satellites.create') }}" class="btn btn-primary fw-bold shadow-sm rounded-3">
            <i class="fas fa-plus me-2"></i> Tambah Satelit Baru
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom py-3">
        <h3 class="card-title fw-bold text-dark">
            <i class="fas fa-filter text-primary me-2"></i> Filter Pencarian
        </h3>
    </div>
    <div class="card-body bg-light border-bottom p-3">
        <form method="GET" action="{{ route('satellites.index') }}" class="m-0">
            <div class="row g-2 align-items-center">
                <div class="col-md-3">
                    <div class="input-icon">
                        <span class="input-icon-addon"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama satelit..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2">
                    <select name="country" class="form-select">
                        <option value="">Semua Negara</option>
                        @foreach($countries as $country)
                            <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>
                                {{ $country }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="orbit" class="form-select">
                        <option value="">Semua Orbit</option>
                        <option value="LEO" {{ request('orbit') == 'LEO' ? 'selected' : '' }}>LEO</option>
                        <option value="MEO" {{ request('orbit') == 'MEO' ? 'selected' : '' }}>MEO</option>
                        <option value="GEO" {{ request('orbit') == 'GEO' ? 'selected' : '' }}>GEO</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inaktif</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1 fw-bold">Cari Data</button>
                    <a href="{{ route('satellites.index') }}" class="btn btn-white fw-bold">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table card-table table-vcenter table-hover text-nowrap">
            <thead class="bg-white">
                <tr>
                    <th class="w-1 text-muted fw-bold py-3">No</th>
                    <th class="text-muted fw-bold py-3">Nama Satelit</th>
                    <th class="text-muted fw-bold py-3">Negara</th>
                    <th class="text-muted fw-bold py-3">Tipe Orbit</th>
                    <th class="text-muted fw-bold py-3">Tgl Peluncuran</th>
                    <th class="text-muted fw-bold py-3">Stasiun Bumi</th>
                    <th class="text-muted fw-bold py-3">Status</th>
                    <th class="w-1 text-center text-muted fw-bold py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($satellites as $satellite)
                    <tr>
                        <td class="text-muted align-middle">
                            {{ $loop->iteration + ($satellites->currentPage() - 1) * $satellites->perPage() }}
                        </td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <div class="bg-blue-lt text-blue rounded p-2 me-3"><i class="fas fa-satellite"></i></div>
                                <span class="fw-bold text-dark fs-5">{{ $satellite->name }}</span>
                            </div>
                        </td>
                        <td class="align-middle text-secondary">{{ $satellite->country }}</td>
                        <td class="align-middle">
                            <span class="badge bg-indigo-lt px-2 py-1 fw-bold">{{ $satellite->orbit_type }}</span>
                        </td>
                        <td class="align-middle">
                            <span class="text-secondary"><i class="far fa-calendar-alt me-1"></i> {{ $satellite->launch_date->format('d M Y') }}</span>
                        </td>
                        <td class="align-middle">
                            @if($satellite->groundStation)
                                <span class="fw-medium text-secondary">
                                    <i class="fas fa-broadcast-tower text-success me-1"></i> {{ $satellite->groundStation->name }}
                                </span>
                            @else
                                <span class="text-muted fst-italic">Belum Terhubung</span>
                            @endif
                        </td>
                        <td class="align-middle">
                            @if($satellite->status == 'active')
                                <span class="badge bg-success-lt px-3 py-1 rounded-pill">
                                    <span class="status-dot bg-success me-1"></span> Aktif
                                </span>
                            @else
                                <span class="badge bg-danger-lt px-3 py-1 rounded-pill">
                                    <span class="status-dot bg-danger me-1"></span> Inaktif
                                </span>
                            @endif
                        </td>
                        <td class="align-middle">
                            <div class="btn-list flex-nowrap justify-content-center">
                                <a href="{{ route('satellites.show', $satellite) }}" class="btn btn-icon btn-white btn-sm text-info shadow-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('satellites.edit', $satellite) }}" class="btn btn-icon btn-white btn-sm text-warning shadow-sm" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('satellites.sync-tle', $satellite) }}" method="POST" class="d-inline" onsubmit="return confirm('Tarik data TLE terbaru untuk {{ $satellite->name }}?')">
                                    @csrf
                                    <button type="submit" class="btn btn-icon btn-white btn-sm text-success shadow-sm" title="Update TLE">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </form>
                                <form action="{{ route('satellites.destroy', $satellite) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data satelit ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-white btn-sm text-danger shadow-sm" title="Hapus Data">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="fas fa-satellite fa-3x mb-3 text-gray-300"></i><br>
                            <span class="fs-4">Tidak ada data satelit yang ditemukan.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-white d-flex align-items-center">
        <p class="m-0 text-muted small">
            Menampilkan <strong>{{ $satellites->firstItem() ?? 0 }}</strong> - <strong>{{ $satellites->lastItem() ?? 0 }}</strong> dari total <strong>{{ $satellites->total() }}</strong> data satelit
        </p>
        <div class="ms-auto">
            {{ $satellites->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
