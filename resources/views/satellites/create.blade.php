@extends('layouts.admin')

@section('title', 'Create Satellite')
@section('page-title', 'Create New Satellite')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('satellites.index') }}">Satellites</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Satellite Information</h3>
        </div>

        <form action="{{ route('satellites.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Satellite Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="country">Country <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('country') is-invalid @enderror" 
                                   id="country" name="country" value="{{ old('country') }}" required>
                            @error('country')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="launch_date">Launch Date <span class="text-danger">*</span></label>
                            <input type="date" class="form-control @error('launch_date') is-invalid @enderror" 
                                   id="launch_date" name="launch_date" value="{{ old('launch_date') }}" required>
                            @error('launch_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="orbit_type">Orbit Type <span class="text-danger">*</span></label>
                            <select class="form-control @error('orbit_type') is-invalid @enderror" 
                                    id="orbit_type" name="orbit_type" required>
                                <option value="">Select Orbit</option>
                                <option value="LEO" {{ old('orbit_type') == 'LEO' ? 'selected' : '' }}>LEO (Low Earth Orbit)</option>
                                <option value="MEO" {{ old('orbit_type') == 'MEO' ? 'selected' : '' }}>MEO (Medium Earth Orbit)</option>
                                <option value="GEO" {{ old('orbit_type') == 'GEO' ? 'selected' : '' }}>GEO (Geostationary Orbit)</option>
                            </select>
                            @error('orbit_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="ground_station_id">Ground Station</label>
                            <select class="form-control @error('ground_station_id') is-invalid @enderror" 
                                    id="ground_station_id" name="ground_station_id">
                                <option value="">Select Ground Station (Optional)</option>
                                @foreach($groundStations as $gs)
                                    <option value="{{ $gs->id }}" {{ old('ground_station_id') == $gs->id ? 'selected' : '' }}>
                                        {{ $gs->name }} - {{ $gs->country }}
                                    </option>
                                @endforeach
                            </select>
                            @error('ground_station_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="image">Satellite Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                            <small class="form-text text-muted">Max 2MB (jpg, png, gif)</small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="tle">TLE (Two-Line Element)</label>
                    <textarea class="form-control @error('tle') is-invalid @enderror" 
                              id="tle" name="tle" rows="3">{{ old('tle') }}</textarea>
                    @error('tle')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <small class="form-text text-muted">Orbital parameters in TLE format</small>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="4">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Satellite
                </button>
                <a href="{{ route('satellites.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancel
                </a>
            </div>
        </form>
    </div>
@endsection