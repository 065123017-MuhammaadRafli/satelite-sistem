@extends('layouts.admin')

@section('title', 'Ground Station Details')
@section('page-title', 'Ground Station Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('ground-stations.index') }}">Ground Stations</a></li>
    <li class="breadcrumb-item active">{{ $groundStation->name }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-5">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Station Information</h3>
                    <div class="card-tools">
                        <a href="{{ route('ground-stations.edit', $groundStation) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Station Name:</th>
                            <td>{{ $groundStation->name }}</td>
                        </tr>
                        <tr>
                            <th>Location:</th>
                            <td>{{ $groundStation->location }}</td>
                        </tr>
                        <tr>
                            <th>Country:</th>
                            <td>{{ $groundStation->country }}</td>
                        </tr>
                        <tr>
                            <th>Latitude:</th>
                            <td>{{ number_format($groundStation->latitude, 7) }}°</td>
                        </tr>
                        <tr>
                            <th>Longitude:</th>
                            <td>{{ number_format($groundStation->longitude, 7) }}°</td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $groundStation->description ?: 'No description available' }}</td>
                        </tr>
                    </table>

                    <hr>
                    <div class="row text-muted">
                        <div class="col-12">
                            <small><i class="fas fa-calendar"></i> Created: {{ $groundStation->created_at->format('d M Y H:i') }}</small>
                        </div>
                        <div class="col-12">
                            <small><i class="fas fa-edit"></i> Updated: {{ $groundStation->updated_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-satellite"></i> 
                        Monitored Satellites ({{ $groundStation->satellites->count() }})
                    </h3>
                </div>
                <div class="card-body p-0">
                    @if($groundStation->satellites->count() > 0)
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Satellite Name</th>
                                    <th>Orbit</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groundStation->satellites as $satellite)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $satellite->name }}</td>
                                        <td><span class="badge badge-info">{{ $satellite->orbit_type }}</span></td>
                                        <td>
                                            @if($satellite->status == 'active')
                                                <span class="badge badge-success">Active</span>
                                            @else
                                                <span class="badge badge-danger">Inactive</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('satellites.show', $satellite) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-3x"></i>
                            <p class="mt-2">No satellites assigned to this ground station</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection