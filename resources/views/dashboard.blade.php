@extends('layouts.admin')

@section('title', 'Dashboard Utama')
@section('page-title', 'Dashboard Overview')
@section('page-subtitle', 'Pusat kendali dan monitoring data sistem satelit terintegrasi.')
@section('page-icon', 'fas fa-chart-line')

@push('styles')
    <style>
        /* --- BANNER SELAMAT DATANG --- */
        .welcome-banner {
            background: linear-gradient(135deg, #0b132b 0%, #1a569d 100%);
            border-radius: 16px;
            color: #ffffff;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(26, 86, 157, 0.2);
        }
        .welcome-banner::after {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            background: url('data:image/svg+xml;utf8,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><path d="M10 10h10v10H10z" fill="rgba(255,255,255,0.03)"/></svg>') repeat;
            opacity: 0.5;
            pointer-events: none;
        }

        /* --- KARTU METRIK MODERN --- */
        .stat-card {
            background: #ffffff;
            border: 1px solid rgba(0,0,0,0.04);
            border-radius: 16px;
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
            border-color: rgba(32, 107, 196, 0.2);
        }
        .icon-box {
            width: 54px; height: 54px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 14px;
            font-size: 1.5rem;
            transition: transform 0.3s ease;
        }
        .stat-card:hover .icon-box {
            transform: scale(1.1) rotate(5deg);
        }

        /* --- TABEL MODERN --- */
        .modern-table th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 1px;
            color: #64748b !important;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 12px;
        }
        .modern-table td {
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
        }
        .modern-table tbody tr {
            transition: background-color 0.2s ease;
        }
        .modern-table tbody tr:hover {
            background-color: #f8fafc;
        }
    </style>
@endpush

@section('content')

<div class="row mb-4">
    <div class="col-12">
        <div class="welcome-banner p-4 p-md-5 d-flex align-items-center justify-content-between">
            <div class="position-relative" style="z-index: 2;">
                <h2 class="fw-bolder mb-2" style="font-size: 1.8rem;">
                    Selamat Datang, {{ Auth::user()->name ?? 'Komandan' }}! 👋
                </h2>
                <p class="mb-0 text-white-50 fs-5">
                    Sistem pemantauan satelit dan infrastruktur bumi beroperasi secara optimal hari ini.
                </p>
            </div>
            <div class="d-none d-md-block opacity-50" style="z-index: 2;">
                <i class="fas fa-satellite" style="font-size: 5rem;"></i>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mb-5">

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card shadow-sm h-100 p-3">
            <div class="d-flex align-items-center mb-3">
                <div class="icon-box bg-blue-lt text-blue me-3">
                    <i class="fas fa-satellite"></i>
                </div>
                <div>
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total Satellites</div>
                    <div class="fs-2 fw-bolder text-dark lh-1 mt-1">15</div> </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                <span class="text-success small fw-bold"><i class="fas fa-arrow-up me-1"></i>2 Baru</span>
                <a href="{{ route('satellites.index') ?? '#' }}" class="text-muted small fw-bold text-decoration-none hover-primary">View Details <i class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i></a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card shadow-sm h-100 p-3">
            <div class="d-flex align-items-center mb-3">
                <div class="icon-box bg-green-lt text-green me-3">
                    <i class="fas fa-broadcast-tower"></i>
                </div>
                <div>
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Ground Stations</div>
                    <div class="fs-2 fw-bolder text-dark lh-1 mt-1">4</div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                <span class="text-success small fw-bold"><i class="fas fa-check-circle me-1"></i>All Active</span>
                <a href="{{ route('ground-stations.index') ?? '#' }}" class="text-muted small fw-bold text-decoration-none hover-primary">View Details <i class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i></a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card shadow-sm h-100 p-3">
            <div class="d-flex align-items-center mb-3">
                <div class="icon-box bg-orange-lt text-orange me-3">
                    <i class="fas fa-globe-asia"></i>
                </div>
                <div>
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Coverage Countries</div>
                    <div class="fs-2 fw-bolder text-dark lh-1 mt-1">12</div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                <span class="text-secondary small fw-bold"><i class="fas fa-map me-1"></i>Global Reach</span>
                <a href="#" class="text-muted small fw-bold text-decoration-none hover-primary">More Info <i class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i></a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card shadow-sm h-100 p-3">
            <div class="d-flex align-items-center mb-3">
                <div class="icon-box bg-red-lt text-danger me-3">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div class="text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">System Status</div>
                    <div class="fs-3 fw-bolder text-dark lh-1 mt-1 text-uppercase">Secure</div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                <span class="text-success small fw-bold"><i class="fas fa-lock me-1"></i>Encrypted</span>
                <a href="#" class="text-muted small fw-bold text-decoration-none hover-primary">Check Logs <i class="fas fa-chevron-right ms-1" style="font-size: 0.7rem;"></i></a>
            </div>
        </div>
    </div>

</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0" style="border-radius: 16px; overflow: hidden;">
            <div class="card-header bg-white py-4 border-bottom">
                <h3 class="card-title fw-bold text-dark m-0 d-flex align-items-center">
                    <i class="fas fa-history text-primary me-2"></i> Latest Satellite Activities
                </h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table modern-table table-vcenter text-nowrap mb-0 fs-5">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Nama Satelit</th>
                                <th class="py-3">Negara</th>
                                <th class="py-3">Status</th>
                                <th class="pe-4 py-3 text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-blue-lt text-blue rounded p-2 me-3"><i class="fas fa-satellite"></i></div>
                                        <div class="fw-bold text-dark">Satelit Merah Putih</div>
                                    </div>
                                </td>
                                <td class="py-3 text-secondary fw-medium">Indonesia</td>
                                <td class="py-3">
                                    <span class="badge bg-green-lt px-3 py-2 rounded-pill fw-bold" style="letter-spacing: 0.5px;">ACTIVE</span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <a href="#" class="btn btn-sm btn-light text-primary fw-bold px-3 rounded-3 shadow-sm">Details</a>
                                </td>
                            </tr>

                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-blue-lt text-blue rounded p-2 me-3"><i class="fas fa-satellite"></i></div>
                                        <div class="fw-bold text-dark">LAPAN-A2</div>
                                    </div>
                                </td>
                                <td class="py-3 text-secondary fw-medium">Indonesia</td>
                                <td class="py-3">
                                    <span class="badge bg-green-lt px-3 py-2 rounded-pill fw-bold" style="letter-spacing: 0.5px;">ACTIVE</span>
                                </td>
                                <td class="pe-4 py-3 text-end">
                                    <a href="#" class="btn btn-sm btn-light text-primary fw-bold px-3 rounded-3 shadow-sm">Details</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white border-top py-3 text-center">
                <a href="{{ route('satellites.index') ?? '#' }}" class="text-primary fw-bold text-decoration-none">
                    Lihat Semua Data Satelit <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
