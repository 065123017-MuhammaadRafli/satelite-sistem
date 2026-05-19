@extends('layouts.admin')

@section('title', 'Dashboard Utama')
@section('page-title', 'SATELLITE SISTEM Command Center')
@section('page-subtitle', 'Pusat kendali telemetri satelit dan infrastruktur stasiun bumi terintegrasi.')
@section('page-icon', 'fas fa-network-wired')

@push('styles')
    <style>
        /* --- STATUS & SERVER TIME BAR --- */
        .status-time-bar {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid rgba(226, 232, 240, 0.8);
            box-shadow: 0 4px 20px -5px rgba(15, 23, 42, 0.04);
        }

        /* Animasi Lampu Indikator Status */
        .status-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            display: inline-block;
        }
        .pulsing-green {
            background-color: #10b981;
            box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            animation: pulse-green 2s infinite;
        }
        @keyframes pulse-green {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        /* --- KARTU METRIK MODERN --- */
        .stat-card {
            background: #ffffff;
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px -5px rgba(15, 23, 42, 0.05);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px -10px rgba(15, 23, 42, 0.1);
        }
        .icon-box {
            width: 50px; height: 50px;
            display: flex; align-items: center; justify-content: center;
            border-radius: 14px;
            font-size: 1.4rem;
            transition: transform 0.3s ease;
        }
        .stat-card:hover .icon-box {
            transform: scale(1.05);
        }

        /* --- GRID UTAMA & KARTU KONTEN --- */
        .dashboard-card {
            background: #ffffff;
            border-radius: 20px;
            border: none;
            box-shadow: 0 10px 40px -10px rgba(15, 23, 42, 0.05);
            overflow: hidden;
        }

        /* --- TABEL MODERN SELANG-SELING --- */
        .modern-table th {
            background-color: #f8fafc !important;
            text-transform: uppercase;
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 1px;
            color: #64748b;
            border-bottom: 2px solid #e2e8f0 !important;
            padding: 1rem 1.5rem;
        }
        .modern-table td {
            vertical-align: middle;
            border-bottom: 1px solid #f1f5f9;
            padding: 1.2rem 1.5rem;
            color: #334155;
        }
        .modern-table tbody tr {
            transition: all 0.2s ease;
        }
        /* Efek Selang-Seling (Zebra) */
        .modern-table tbody tr:nth-child(even) {
            background-color: #f8fafc !important;
        }
        .modern-table tbody tr:hover {
            background-color: #f1f5f9 !important;
            transform: scale(1.001);
        }

        /* --- LIVE MONITORING STATION PANEL --- */
        .station-mini-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem;
            transition: all 0.2s ease;
        }
        .station-mini-card:hover {
            border-color: #cbd5e1;
            background: #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }
    </style>
@endpush

@section('content')

<div class="row mb-4 mt-2">
    <div class="col-12">
        <div class="status-time-bar p-3 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex align-items-center">
                <span class="text-uppercase fw-bold me-2" style="font-size: 0.7rem; letter-spacing: 0.5px; color: #64748b;">System Status:</span>
                <span class="badge bg-green-lt d-flex align-items-center fw-bold px-2 py-1 rounded-2 text-green" style="font-size: 0.75rem;">
                    <span class="status-dot pulsing-green me-1.5"></span> Operational Optimal
                </span>
            </div>
            <div class="d-flex align-items-center">
                <i class="far fa-clock me-2" style="color: #64748b; font-size: 0.85rem;"></i>
                <span class="text-uppercase fw-bold me-2" style="font-size: 0.7rem; letter-spacing: 0.5px; color: #64748b;">Server Time (WIB):</span>
                <span class="fw-bold text-dark" style="font-family: 'JetBrains Mono', monospace; font-size: 0.85rem;">
                    {{ \Carbon\Carbon::now()->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                </span>
            </div>
        </div>
    </div>
</div>

<div class="row row-cards mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card h-100 p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-box bg-blue-lt text-blue me-3">
                    <i class="fas fa-satellite"></i>
                </div>
                <div>
                    <div class="text-slate-400 fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.8px;">Total Satellites</div>
                    <div class="fs-1 fw-bolder text-dark lh-1 mt-1">15</div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3" style="border-color: #f1f5f9 !important;">
                <span class="text-success small fw-bold"><i class="fas fa-arrow-up me-1"></i>2 Baru</span>
                <a href="{{ route('satellites.index') }}" class="text-primary small fw-bold text-decoration-none">View Details <i class="fas fa-chevron-right ms-1" style="font-size: 0.6rem;"></i></a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card h-100 p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-box bg-green-lt text-green me-3">
                    <i class="fas fa-broadcast-tower"></i>
                </div>
                <div>
                    <div class="text-slate-400 fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.8px;">Ground Stations</div>
                    <div class="fs-1 fw-bolder text-dark lh-1 mt-1">4</div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3" style="border-color: #f1f5f9 !important;">
                <span class="text-success small fw-bold"><i class="fas fa-check-circle me-1"></i>All Active</span>
                <a href="{{ route('ground-stations.index') }}" class="text-primary small fw-bold text-decoration-none">View Details <i class="fas fa-chevron-right ms-1" style="font-size: 0.6rem;"></i></a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card h-100 p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-box bg-orange-lt text-orange me-3">
                    <i class="fas fa-globe-asia"></i>
                </div>
                <div>
                    <div class="text-slate-400 fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.8px;">Coverage Countries</div>
                    <div class="fs-1 fw-bolder text-dark lh-1 mt-1">12</div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3" style="border-color: #f1f5f9 !important;">
                <span class="text-secondary small fw-bold"><i class="fas fa-map me-1"></i>Global Reach</span>
                <a href="#" class="text-primary small fw-bold text-decoration-none">More Info <i class="fas fa-chevron-right ms-1" style="font-size: 0.6rem;"></i></a>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="stat-card h-100 p-4">
            <div class="d-flex align-items-center mb-4">
                <div class="icon-box bg-red-lt text-danger me-3">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <div>
                    <div class="text-slate-400 fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.8px;">System Status</div>
                    <div class="fs-2 fw-bolder text-dark lh-1 mt-1 text-uppercase">Secure</div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3" style="border-color: #f1f5f9 !important;">
                <span class="text-success small fw-bold"><i class="fas fa-lock me-1"></i>Encrypted</span>
                <a href="#" class="text-primary small fw-bold text-decoration-none">Check Logs <i class="fas fa-chevron-right ms-1" style="font-size: 0.6rem;"></i></a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-7">
        <div class="card dashboard-card h-100">
            <div class="card-header bg-white py-4 border-bottom d-flex align-items-center">
                <div class="bg-indigo-lt p-2 rounded-3 me-3 text-indigo d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                    <i class="fas fa-history fs-5"></i>
                </div>
                <h3 class="card-title fw-bolder text-dark m-0 fs-3" style="letter-spacing: -0.5px;">Latest Satellite Activities</h3>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table modern-table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Nama Satelit</th>
                                <th>Negara</th>
                                <th>Status</th>
                                <th class="pe-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bolder text-dark fs-4">Satelit Merah Putih</div>
                                </td>
                                <td><div class="text-secondary fw-medium">Indonesia</div></td>
                                <td><span class="badge bg-green-lt px-2 py-1 rounded-2 fw-bold" style="font-size: 0.75rem;"><i class="fas fa-check-circle me-1"></i> ACTIVE</span></td>
                                <td class="pe-4 text-center">
                                    <a href="#" class="btn btn-sm btn-white border shadow-sm rounded-pill px-3 fw-bold text-primary">View</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bolder text-dark fs-4">LAPAN-A2</div>
                                </td>
                                <td><div class="text-secondary fw-medium">Indonesia</div></td>
                                <td><span class="badge bg-green-lt px-2 py-1 rounded-2 fw-bold" style="font-size: 0.75rem;"><i class="fas fa-check-circle me-1"></i> ACTIVE</span></td>
                                <td class="pe-4 text-center">
                                    <a href="#" class="btn btn-sm btn-white border shadow-sm rounded-pill px-3 fw-bold text-primary">View</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white py-3 text-center border-top">
                <a href="{{ route('satellites.index') }}" class="text-primary fw-bold text-decoration-none fs-5">
                    Lihat Semua Data Satelit <i class="fas fa-arrow-right ms-1 fs-6"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-5">
        <div class="card dashboard-card h-100">
            <div class="card-header bg-white py-4 border-bottom d-flex align-items-center">
                <div class="bg-purple-lt p-2 rounded-3 me-3 text-purple d-flex align-items-center justify-content-center" style="width:36px; height:36px;">
                    <i class="fas fa-broadcast-tower fs-5"></i>
                </div>
                <h3 class="card-title fw-bolder text-dark m-0 fs-3" style="letter-spacing: -0.5px;">Infrastruktur Bumi</h3>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="station-mini-card d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-server text-indigo me-3 fs-3"></i>
                                <div>
                                    <div class="fw-bold text-dark">Stasiun Pusat Rumpin</div>
                                    <div class="text-muted small">ID: GS-01 • Utama</div>
                                </div>
                            </div>
                            <span class="badge bg-green-lt text-green fw-bold rounded-pill px-2.5 py-1">ONLINE</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="station-mini-card d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-server text-indigo me-3 fs-3"></i>
                                <div>
                                    <div class="fw-bold text-dark">Stasiun Biak</div>
                                    <div class="text-muted small">ID: GS-02 • Regional</div>
                                </div>
                            </div>
                            <span class="badge bg-green-lt text-green fw-bold rounded-pill px-2.5 py-1">ONLINE</span>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="station-mini-card d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-server text-indigo me-3 fs-3"></i>
                                <div>
                                    <div class="fw-bold text-dark">Stasiun Parepare</div>
                                    <div class="text-muted small">ID: GS-03 • Regional</div>
                                </div>
                            </div>
                            <span class="badge bg-green-lt text-green fw-bold rounded-pill px-2.5 py-1">ONLINE</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-white py-3 text-center border-top mt-auto">
                <a href="{{ route('ground-stations.index') }}" class="text-primary fw-bold text-decoration-none fs-5">
                    Lihat Semua Stasiun <i class="fas fa-arrow-right ms-1 fs-6"></i>
                </a>
            </div>
        </div>
    </div>
</div>

@endsection
