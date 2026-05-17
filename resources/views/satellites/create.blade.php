@extends('layouts.admin')

@section('title', 'Tambah Satelit Baru')
@section('page-title', 'Tambah Satelit Baru')
@section('page-subtitle', 'Masukkan detail informasi dan data TLE satelit baru ke dalam sistem.')
@section('page-icon', 'fas fa-plus-circle')

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
                    <i class="fas fa-info-circle text-primary me-2"></i> Informasi Satelit
                </h3>
            </div>

            <form action="{{ route('satellites.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body p-4">

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label required fw-medium">Nama Satelit</label>
                            <input type="text" name="name" class="form-control" placeholder="Contoh: LAPAN-A2" value="{{ old('name') }}" required>
                            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required fw-medium">Tipe Orbit</label>
                            <select name="orbit_type" class="form-select" required>
                                <option value="" disabled {{ old('orbit_type') ? '' : 'selected' }}>Pilih Tipe Orbit</option>
                                <option value="LEO" {{ old('orbit_type') == 'LEO' ? 'selected' : '' }}>LEO (Low Earth Orbit)</option>
                                <option value="MEO" {{ old('orbit_type') == 'MEO' ? 'selected' : '' }}>MEO (Medium Earth Orbit)</option>
                                <option value="GEO" {{ old('orbit_type') == 'GEO' ? 'selected' : '' }}>GEO (Geostationary Orbit)</option>
                            </select>
                            @error('orbit_type') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label required fw-medium">Negara Asal</label>
                            <input type="text" name="country" class="form-control" placeholder="Contoh: Indonesia" value="{{ old('country') }}" required>
                            @error('country') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label required fw-medium">Status Operasional</label>
                            <select name="status" class="form-select" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                            </select>
                            @error('status') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label class="form-label required fw-medium">Tanggal Peluncuran</label>
                            <div class="input-icon">
                                <span class="input-icon-addon"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" name="launch_date" class="form-control" value="{{ old('launch_date') }}" required>
                            </div>
                            @error('launch_date') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-medium">Stasiun Bumi (Opsional)</label>
                            <select name="ground_station_id" class="form-select">
                                <option value="">Tidak terikat stasiun bumi</option>
                                @if(isset($groundStations))
                                    @foreach($groundStations as $station)
                                        <option value="{{ $station->id }}" {{ old('ground_station_id') == $station->id ? 'selected' : '' }}>
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
                            <label class="form-label required fw-medium">Line 1</label>
                            <input type="text" name="tle_line1" class="form-control font-monospace" placeholder="1 25544U 98067A   23001.50000000  .00000000  00000-0  00000-0 0  9999" value="{{ old('tle_line1') }}" required>
                            @error('tle_line1') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-2">
                            <label class="form-label required fw-medium">Line 2</label>
                            <input type="text" name="tle_line2" class="form-control font-monospace" placeholder="2 25544  51.6438 180.0000 0001000  0.0000 180.0000 15.50000000    01" value="{{ old('tle_line2') }}" required>
                            @error('tle_line2') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <small class="form-hint"><i class="fas fa-info-circle me-1"></i> Setiap baris harus berisi persis 69 karakter (termasuk spasi).</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-medium">Gambar Satelit (Opsional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="form-hint mt-1">Format: JPG, PNG. Maksimal 2MB.</small>
                        @error('image') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-medium">Deskripsi Satelit (Opsional)</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Masukkan detail antena, misi, atau catatan lainnya di sini...">{{ old('description') }}</textarea>
                        @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                </div>

                <div class="card-footer bg-light text-end py-3 rounded-bottom-3">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('satellites.index') }}" class="btn btn-white fw-bold shadow-sm">
                            Batal
                        </a>
                        <button type="submit" class="btn btn-primary fw-bold shadow-sm">
                            <i class="fas fa-save me-2"></i> Simpan Satelit Baru
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
