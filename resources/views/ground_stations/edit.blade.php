@extends('layouts.admin')

@section('title', 'Edit Stasiun Bumi - ' . $groundStation->name)
@section('page-title', 'Update Stasiun Bumi')
@section('page-subtitle', 'Perbarui informasi dan titik koordinat stasiun bumi yang sudah terdaftar.')
@section('page-icon', 'fas fa-edit')

@section('content')
<div class="row">
    <div class="col-12 col-xl-10 mx-auto">

        <div class="row mb-3 align-items-center d-print-none">
            <div class="col-auto">
                <a href="{{ route('ground-stations.index') }}" class="btn btn-white fw-bold shadow-sm rounded-3 text-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                </a>
            </div>
        </div>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-4 border-bottom">
                <h3 class="card-title fw-bold text-dark">
                    <i class="fas fa-edit text-warning me-2"></i> Edit Stasiun: <span class="text-primary">{{ $groundStation->name }}</span>
                </h3>
            </div>

            <form action="{{ route('ground-stations.update', $groundStation->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card-body p-4">

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label required fw-medium">Nama Stasiun (Station Name)</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: LAPAN Station Rumpin" value="{{ old('name', $groundStation->name) }}" required>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required fw-medium">Negara (Country)</label>
                            <input type="text" name="country" class="form-control" placeholder="Contoh: Indonesia" value="{{ old('country', $groundStation->country) }}" required>
                            @error('country') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label required fw-medium">Detail Lokasi Wilayah</label>
                        <input type="text" name="location" class="form-control" placeholder="Contoh: Rumpin, Bogor, West Java" value="{{ old('location', $groundStation->location) }}" required>
                        @error('location') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="hr-text hr-text-left mt-5 mb-4 text-warning">Titik Koordinat Geografis (GPS)</div>

                    <div class="bg-light p-4 rounded-3 border mb-4">
                        <div class="row">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label required fw-medium">Latitude (Garis Lintang)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-ruler-vertical text-muted"></i></span>
                                    <input type="number" step="any" name="latitude" class="form-control font-monospace" value="{{ old('latitude', $groundStation->latitude) }}" required>
                                </div>
                                <small class="form-hint mt-2">Rentang yang diizinkan: -90 hingga 90</small>
                                @error('latitude') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label required fw-medium">Longitude (Garis Bujur)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-ruler-horizontal text-muted"></i></span>
                                    <input type="number" step="any" name="longitude" class="form-control font-monospace" value="{{ old('longitude', $groundStation->longitude) }}" required>
                                </div>
                                <small class="form-hint mt-2">Rentang yang diizinkan: -180 hingga 180</small>
                                @error('longitude') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-medium">Deskripsi / Spesifikasi Tambahan (Opsional)</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Masukkan detail antena, frekuensi, atau catatan lainnya di sini...">{{ old('description', $groundStation->description) }}</textarea>
                        @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                </div>

                <div class="card-footer bg-light text-end py-3 rounded-bottom-3">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('ground-stations.index') }}" class="btn btn-white fw-bold">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-warning fw-bold">
                            <i class="fas fa-save me-2"></i> Update Stasiun Bumi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
