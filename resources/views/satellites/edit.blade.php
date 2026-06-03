@extends('layouts.admin')

@section('title', 'Edit Satelit - ' . $satellite->name)
@section('page-title', 'Update Data Satelit')
@section('page-subtitle', 'Perbarui informasi detail dan data telemetri satelit.')
@section('page-icon', 'fas fa-edit')

@section('content')
<div class="row">
    <div class="col-12 col-xl-10 mx-auto">

        <div class="row mb-3 align-items-center d-print-none">
            <div class="col-auto">
                <a href="{{ route('satellites.index') }}" class="btn btn-white fw-bold shadow-sm rounded-3 text-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Kembali ke Daftar
                </a>
            </div>
        </div>
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-4 border-bottom">
                <h3 class="card-title fw-bold text-dark">
                    <i class="fas fa-edit text-warning me-2"></i> Edit Satelit: <span class="text-primary">{{ $satellite->name }}</span>
                </h3>
            </div>

            <form action="{{ route('satellites.update', $satellite->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body p-4">

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label required fw-medium">Nama Satelit</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: LAPAN-A2" value="{{ old('name', $satellite->name) }}" required>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required fw-medium">Tipe Orbit</label>
                            <select name="orbit_type" class="form-select" required>
                                <option value="LEO" {{ old('orbit_type', $satellite->orbit_type) == 'LEO' ? 'selected' : '' }}>LEO (Low Earth Orbit)</option>
                                <option value="MEO" {{ old('orbit_type', $satellite->orbit_type) == 'MEO' ? 'selected' : '' }}>MEO (Medium Earth Orbit)</option>
                                <option value="GEO" {{ old('orbit_type', $satellite->orbit_type) == 'GEO' ? 'selected' : '' }}>GEO (Geostationary Orbit)</option>
                            </select>
                            @error('orbit_type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label required fw-medium">Negara Asal</label>
                            <input type="text" name="country" class="form-control" placeholder="Contoh: Indonesia" value="{{ old('country', $satellite->country) }}" required>
                            @error('country') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required fw-medium">Status Operasional</label>
                            <select name="status" class="form-select" required>
                                <option value="active" {{ old('status', $satellite->status) == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status', $satellite->status) == 'inactive' ? 'selected' : '' }}>Inaktif</option>
                                <option value="maintenance" {{ old('status', $satellite->status) == 'maintenance' ? 'selected' : '' }}>Maintenance (Pemeliharaan)</option>
                            </select>
                            @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label required fw-medium">Tanggal Peluncuran</label>
                            <input type="date" name="launch_date" class="form-control" value="{{ old('launch_date', \Carbon\Carbon::parse($satellite->launch_date)->format('Y-m-d')) }}" required>
                            @error('launch_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Stasiun Bumi (Opsional)</label>
                            <select name="ground_station_id" class="form-select">
                                <option value="">Tidak terikat stasiun bumi</option>
                                @if(isset($groundStations))
                                    @foreach($groundStations as $station)
                                        <option value="{{ $station->id }}" {{ old('ground_station_id', $satellite->ground_station_id) == $station->id ? 'selected' : '' }}>
                                            {{ $station->name }}
                                        </option>
                                    @endforeach
                                @endif
                                </select>
                            @error('ground_station_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="hr-text hr-text-left mt-5 mb-4 text-primary">Two-Line Element (TLE) Data</div>

                    <div class="bg-light p-4 rounded-3 border mb-4">
                        <div class="mb-3">
                            <label class="form-label fw-medium">Line 1</label>
                            <input type="text" name="tle_line1" class="form-control font-monospace text-muted" value="{{ old('tle_line1', $satellite->tle_line1) }}">
                            @error('tle_line1') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label class="form-label fw-medium">Line 2</label>
                            <input type="text" name="tle_line2" class="form-control font-monospace text-muted" value="{{ old('tle_line2', $satellite->tle_line2) }}">
                            <small class="form-hint mt-2"><i class="fas fa-info-circle me-1"></i> Setiap baris harus berisi persis 69 karakter (termasuk spasi).</small>
                            @error('tle_line2') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label fw-medium">Gambar Satelit Baru</label>
                            @if($satellite->image)
                                <div class="mb-2 d-flex align-items-center bg-light p-2 rounded border">
                                    <img src="{{ asset('storage/' . $satellite->image) }}" alt="Current Image" class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                    <span class="small text-muted">Gambar saat ini</span>
                                </div>
                            @endif
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="form-hint mt-2">Biarkan kosong jika tidak ingin mengubah gambar.</small>
                            @error('image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-medium d-flex align-items-center">
                                Dynamic API URL
                                <span class="badge bg-secondary text-white ms-2 fw-normal" style="font-size: 0.65rem;">Opsional</span>
                            </label>
                            <div class="input-group shadow-sm rounded-3 overflow-hidden border">
                                <span class="input-group-text bg-light text-muted border-0 pe-2">
                                    <i class="fas fa-link"></i>
                                </span>
                                <input type="url" name="dynamic_api_url" id="dynamic_api_url"
                                       class="form-control border-0 px-2 @error('dynamic_api_url') is-invalid @enderror"
                                       value="{{ old('dynamic_api_url', $satellite->dynamic_api_url) }}"
                                       placeholder="http://10.35.0.104/tle/LAPANSAT-TLE.txt">
                            </div>
                            @error('dynamic_api_url')
                                <div class="text-danger mt-1 small"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                            <small class="form-hint mt-2">
                                <i class="fas fa-info-circle me-1 text-primary"></i> Jika diisi, link ini akan diprioritaskan saat proses <b>Sync TLE</b>.
                            </small>
                        </div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-medium">Deskripsi Satelit</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Masukkan deskripsi atau spesifikasi teknis satelit di sini...">{{ old('description', $satellite->description) }}</textarea>
                        @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                </div>

                <div class="card-footer bg-light text-end py-3 rounded-bottom-3">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('satellites.index') }}" class="btn btn-white fw-bold">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-warning fw-bold">
                            <i class="fas fa-save me-2"></i> Update Data Satelit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
