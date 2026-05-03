@extends('layouts.admin')

@section('title', 'Satellite Details')
@section('page-title', 'Satellite Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item"><a href="{{ route('satellites.index') }}">Satellites</a></li>
    <li class="breadcrumb-item active">{{ $satellite->name }}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Satellite Image</h3>
                </div>
                <div class="card-body text-center">
                    @if($satellite->image)
                        <img src="{{ asset('storage/' . $satellite->image) }}" 
                             alt="{{ $satellite->name }}" 
                             class="img-fluid rounded">
                    @else
                        <div class="text-muted py-5">
                            <i class="fas fa-satellite fa-5x"></i>
                            <p class="mt-3">No image available</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Quick Info</h3>
                </div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-5">Status:</dt>
                        <dd class="col-sm-7">
                            @if($satellite->status == 'active')
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-danger">Inactive</span>
                            @endif
                        </dd>

                        <dt class="col-sm-5">Orbit Type:</dt>
                        <dd class="col-sm-7">
                            <span class="badge badge-info">{{ $satellite->orbit_type }}</span>
                        </dd>

                        <dt class="col-sm-5">Country:</dt>
                        <dd class="col-sm-7">{{ $satellite->country }}</dd>

                        <dt class="col-sm-5">Launch Date:</dt>
                        <dd class="col-sm-7">{{ $satellite->launch_date->format('d F Y') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Satellite Information</h3>
                    <div class="card-tools">
                        <a href="{{ route('satellites.edit', $satellite) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('satellites.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="30%">Satellite Name:</th>
                            <td>{{ $satellite->name }}</td>
                        </tr>
                        <tr>
                            <th>Country:</th>
                            <td>{{ $satellite->country }}</td>
                        </tr>
                        <tr>
                            <th>Launch Date:</th>
                            <td>{{ $satellite->launch_date->format('d F Y') }}</td>
                        </tr>
                        <tr>
                            <th>Orbit Type:</th>
                            <td><span class="badge badge-info">{{ $satellite->orbit_type }}</span></td>
                        </tr>
                        <tr>
                            <th>Status:</th>
                            <td>
                                @if($satellite->status == 'active')
                                    <span class="badge badge-success">Active</span>
                                @else
                                    <span class="badge badge-danger">Inactive</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Ground Station:</th>
                            <td>
                                @if($satellite->groundStation)
                                    <a href="{{ route('ground-stations.show', $satellite->groundStation) }}">
                                        {{ $satellite->groundStation->name }}
                                    </a>
                                    <br>
                                    <small class="text-muted">
                                        {{ $satellite->groundStation->location }}, {{ $satellite->groundStation->country }}
                                    </small>
                                @else
                                    <span class="text-muted">Not assigned</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Description:</th>
                            <td>{{ $satellite->description ?: 'No description available' }}</td>
                        </tr>
                    </table>

                    @if($satellite->tle)
                        <hr>
                        <h5>TLE (Two-Line Element Set)</h5>
                        <pre class="bg-light p-3 rounded">{{ $satellite->tle }}</pre>
                    @endif

                    <hr>
                    <div class="row text-muted">
                        <div class="col-md-6">
                            <small><i class="fas fa-calendar"></i> Created: {{ $satellite->created_at->format('d M Y H:i') }}</small>
                        </div>
                        <div class="col-md-6 text-right">
                            <small><i class="fas fa-edit"></i> Updated: {{ $satellite->updated_at->format('d M Y H:i') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection