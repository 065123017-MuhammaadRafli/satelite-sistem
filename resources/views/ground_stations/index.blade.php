@extends('layouts.admin')

@section('title', 'Ground Stations')
@section('page-title', 'Ground Stations Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
    <li class="breadcrumb-item active">Ground Stations</li>
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">List of Ground Stations</h3>
            <div class="card-tools">
                <a href="{{ route('ground-stations.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Add New Ground Station
                </a>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">#</th>
                            <th>Station Name</th>
                            <th>Location</th>
                            <th>Country</th>
                            <th>Coordinates</th>
                            <th>Satellites</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($groundStations as $gs)
                            <tr>
                                <td>{{ $loop->iteration + ($groundStations->currentPage() - 1) * $groundStations->perPage() }}</td>
                                <td><strong>{{ $gs->name }}</strong></td>
                                <td>{{ $gs->location }}</td>
                                <td>{{ $gs->country }}</td>
                                <td>
                                    <small>
                                        <i class="fas fa-map-marker-alt text-danger"></i>
                                        {{ number_format($gs->latitude, 4) }}, {{ number_format($gs->longitude, 4) }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $gs->satellites_count }} satellites</span>
                                </td>
                                <td>
                                    <a href="{{ route('ground-stations.show', $gs) }}" class="btn btn-info btn-sm" title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('ground-stations.edit', $gs) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('ground-stations.destroy', $gs) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No ground stations found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $groundStations->links() }}
            </div>
        </div>
    </div>
@endsection