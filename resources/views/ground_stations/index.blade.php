@extends('layouts.admin')

@section('title', 'Stasiun Bumi')
@section('page-title', 'Manajemen Stasiun Bumi')
@section('page-subtitle', 'Kelola daftar infrastruktur stasiun bumi (Ground Stations) yang terdaftar di sistem.')
@section('page-icon', 'fas fa-broadcast-tower')

@section('content')
<div class="row mb-3 align-items-center d-print-none">
    <div class="col-auto ms-auto d-print-none">
        <a href="{{ route('ground-stations.create') }}" class="btn btn-primary fw-bold shadow-sm rounded-3">
            <i class="fas fa-plus me-2"></i> Tambah Stasiun Bumi
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom py-3">
        <h3 class="card-title fw-bold text-dark">
            <i class="fas fa-list-ul text-primary me-2"></i> Daftar Stasiun Bumi
        </h3>
    </div>

    <div class="card-body bg-light border-bottom p-3">
        <form method="GET" action="{{ route('ground-stations.index') }}" class="m-0">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <div class="input-icon">
                        <span class="input-icon-addon"><i class="fas fa-search"></i></span>
                        <input type="text" name="search" class="form-control" placeholder="Cari nama atau lokasi stasiun..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="country" class="form-select">
                        <option value="">Semua Negara</option>
                        <option value="Indonesia" {{ request('country') == 'Indonesia' ? 'selected' : '' }}>Indonesia</option>
                        <option value="United States" {{ request('country') == 'United States' ? 'selected' : '' }}>United States</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-primary fw-bold flex-grow-1">Cari</button>
                    <a href="{{ route('ground-stations.index') }}" class="btn btn-white fw-bold">Reset</a>
                </div>
            </div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table card-table table-vcenter table-hover text-nowrap fs-5">
            <thead class="bg-white">
                <tr>
                    <th class="w-1 text-muted fw-bold py-3 text-center">No</th>
                    <th class="text-muted fw-bold py-3">Nama Stasiun</th>
                    <th class="text-muted fw-bold py-3">Lokasi Wilayah</th>
                    <th class="text-muted fw-bold py-3">Negara</th>
                    <th class="text-muted fw-bold py-3">Titik Koordinat (GPS)</th>
                    <th class="text-muted fw-bold py-3 text-center">Satelit Terhubung</th>
                    <th class="w-1 text-center text-muted fw-bold py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($groundStations as $station)
                    <tr>
                        <td class="text-muted align-middle text-center">
                            {{ $loop->iteration + ($groundStations->currentPage() - 1) * $groundStations->perPage() }}
                        </td>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <div class="bg-green-lt text-green rounded p-2 me-3"><i class="fas fa-broadcast-tower"></i></div>
                                <span class="fw-bold text-dark fs-4">{{ $station->name }}</span>
                            </div>
                        </td>
                        <td class="align-middle text-secondary">{{ $station->location }}</td>
                        <td class="align-middle fw-medium">{{ $station->country }}</td>
                        <td class="align-middle">
                            <span class="font-monospace text-muted bg-light border px-2 py-1 rounded small">
                                <i class="fas fa-map-marker-alt text-danger me-1"></i> {{ $station->latitude }}, {{ $station->longitude }}
                            </span>
                        </td>
                        <td class="align-middle text-center">
                            @if($station->satellites_count > 0)
                                <span class="badge bg-blue-lt px-3 py-2 fs-6 rounded-pill">
                                    {{ $station->satellites_count }} Satelit
                                </span>
                            @else
                                <span class="badge bg-secondary-lt px-3 py-2 fs-6 rounded-pill text-muted">
                                    0 Satelit
                                </span>
                            @endif
                        </td>
                        <td class="align-middle">
                            <div class="btn-list flex-nowrap justify-content-center">
                                <a href="{{ route('ground-stations.show', $station) }}" class="btn btn-icon btn-white btn-sm text-info shadow-sm" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('ground-stations.edit', $station) }}" class="btn btn-icon btn-white btn-sm text-warning shadow-sm" title="Edit Data">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('ground-stations.destroy', $station) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus stasiun bumi ini? Data yang dihapus tidak dapat dikembalikan.')">
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
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="fas fa-tower-broadcast fa-3x mb-3 text-gray-300"></i><br>
                            <span class="fs-4">Tidak ada data stasiun bumi yang ditemukan.</span>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer bg-white d-flex align-items-center">
        <p class="m-0 text-muted small">
            Menampilkan <strong>{{ $groundStations->firstItem() ?? 0 }}</strong> - <strong>{{ $groundStations->lastItem() ?? 0 }}</strong> dari total <strong>{{ $groundStations->total() }}</strong> data
        </p>
        <div class="ms-auto">
            {{ $groundStations->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>
@endsection
