<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>{{ config('app.name', 'ASTRALINK') }} - @yield('title')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    @vite(['resources/js/app.js'])

    <style>
        :root { --tblr-font-sans-serif: 'Inter var', sans-serif; }
        body { background-color: #f4f7fa; color: #1e293b; }

        /* =========================================================
           DESAIN SIDEBAR PREMIUM (MODERN MINIMALIST)
        ========================================================= */
        .navbar-vertical {
            background-color: #0f172a !important;
            border-right: 1px solid rgba(255,255,255,0.05);
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
        }

        /* --- BRAND LOGO PREMIUM --- */
        .navbar-brand-autodark {
            padding: 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            margin-bottom: 0.5rem;
        }
        .brand-container {
            transition: opacity 0.3s ease;
        }
        .brand-container:hover { opacity: 0.85; }
        .brand-icon-wrapper {
            position: relative; display: flex; align-items: center; justify-content: center;
            width: 38px; height: 38px;
            background: rgba(59, 130, 246, 0.15); /* Latar kotak biru transparan */
            border-radius: 10px;
            border: 1px solid rgba(59, 130, 246, 0.3);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.2);
        }
        .brand-logo-icon {
            font-size: 1.1rem;
            background: linear-gradient(135deg, #93c5fd, #3b82f6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .brand-text-wrapper {
            font-size: 1.35rem;
            letter-spacing: 1px;
            display: flex; align-items: center;
        }
        .brand-text-primary { color: #ffffff; font-weight: 800; }
        .brand-text-secondary { color: #60a5fa; font-weight: 300; margin-left: 2px; }

        /* Label Kategori Menu */
        .sidebar-heading {
            color: #64748b; font-size: 0.65rem; font-weight: 800;
            letter-spacing: 1.5px; text-transform: uppercase;
            padding: 1.5rem 1.5rem 0.5rem; margin: 0;
        }

        /* Item Menu Navigasi Default */
        .nav-link {
            color: #94a3b8 !important; border-radius: 8px;
            padding: 0.65rem 1rem !important; margin: 0.25rem 1rem;
            font-size: 0.9rem; font-weight: 500;
            transition: all 0.25s ease; display: flex; align-items: center;
        }
        .nav-link-icon {
            color: #475569; width: 24px; text-align: center;
            font-size: 1.1rem; margin-right: 0.75rem; transition: color 0.25s ease;
        }

        /* Efek Hover */
        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.04);
            color: #e2e8f0 !important; transform: translateX(4px);
        }
        .nav-link:hover .nav-link-icon { color: #94a3b8; }

        /* STATE MENU AKTIF */
        .nav-item.active .nav-link {
            background-color: rgba(59, 130, 246, 0.1) !important;
            color: #60a5fa !important; font-weight: 600; position: relative;
        }
        .nav-item.active .nav-link::before {
            content: ''; position: absolute; left: -1rem; top: 15%;
            height: 70%; width: 4px; background-color: #3b82f6;
            border-radius: 0 4px 4px 0; box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }
        .nav-item.active .nav-link-icon { color: #60a5fa !important; }

        /* BADGE "LIVE" PREMIUM */
        .badge-live {
            background: rgba(239, 68, 68, 0.15); color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
            padding: 0.25rem 0.5rem; border-radius: 6px;
            font-size: 0.6rem; font-weight: 700; letter-spacing: 0.5px;
            display: flex; align-items: center; gap: 4px; margin-left: auto;
        }
        .pulse-dot {
            height: 6px; width: 6px; background-color: #ef4444; border-radius: 50%;
            display: inline-block; animation: pulse-red 1.5s infinite;
        }
        @keyframes pulse-red {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 5px rgba(239, 68, 68, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }

        /* Header Atas Navigasi */
        .page-title-header { font-size: 1.3rem; font-weight: 700; color: #1e293b; margin-bottom: 0; letter-spacing: -0.5px; }
        .page-subtitle-header { font-size: 0.85rem; color: #64748b; margin-top: 2px; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="page">
        <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <h1 class="navbar-brand navbar-brand-autodark">
                    <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none brand-container w-100">
                        <div class="brand-icon-wrapper me-3">
                            <i class="fas fa-satellite-dish brand-logo-icon"></i>
                        </div>
                        <div class="brand-text-wrapper">
                            <span class="brand-text-primary">ASTRA</span><span class="brand-text-secondary">LINK</span>
                        </div>
                    </a>
                </h1>

                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-2">

                        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <span class="nav-link-icon"><i class="fas fa-th-large"></i></span>
                                <span class="nav-link-title">Dashboard Utama</span>
                            </a>
                        </li>

                        <div class="sidebar-heading">Pusat Operasional</div>

                        <li class="nav-item {{ request()->is('satellites*') && !request()->routeIs('satellites.live') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('satellites.index') ?? '#' }}">
                                <span class="nav-link-icon"><i class="fas fa-space-shuttle"></i></span>
                                <span class="nav-link-title">Armada Satelit</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->routeIs('satellites.live') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('satellites.live') }}">
                                <span class="nav-link-icon"><i class="fas fa-globe-asia"></i></span>
                                <span class="nav-link-title">Global Tracking</span>
                                <span class="badge-live"><span class="pulse-dot"></span> LIVE</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->routeIs('ground-stations.*') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('ground-stations.index') }}">
                                <span class="nav-link-icon"><i class="fas fa-broadcast-tower"></i></span>
                                <span class="nav-link-title">Infrastruktur Bumi</span>
                            </a>
                        </li>

                        <div class="sidebar-heading">Data & Analisis</div>

                        <li class="nav-item {{ request()->routeIs('statistics') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('statistics') }}">
                                <span class="nav-link-icon"><i class="fas fa-chart-pie"></i></span>
                                <span class="nav-link-title">Analitik Sistem</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <div class="page-wrapper">
            <header class="navbar navbar-expand-md navbar-light sticky-top bg-white border-bottom shadow-sm py-2">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-blue-lt text-blue p-2 rounded-3 me-3 d-none d-md-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                            <i class="@yield('page-icon', 'fas fa-th-large') fs-3"></i>
                        </div>
                        <div>
                            <h2 class="page-title-header">@yield('page-title')</h2>
                            <div class="page-subtitle-header d-none d-sm-block">@yield('page-subtitle')</div>
                        </div>
                    </div>

                    <div class="navbar-nav flex-row order-md-last ms-auto">
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link d-flex lh-1 text-reset p-0 align-items-center" data-bs-toggle="dropdown" aria-label="Open user menu">
                                <div class="d-none d-xl-block ps-3 text-end me-3">
                                    <div class="fw-bold text-dark fs-5">{{ Auth::user()->name ?? 'Administrator' }}</div>
                                    <div class="mt-1 small text-muted fw-medium text-uppercase tracking-wide">{{ Auth::user()->role ?? 'USER' }}</div>
                                </div>
                                <span class="avatar avatar-md bg-blue text-white fw-bold border shadow-sm" style="border-radius: 12px;">
                                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow shadow-lg border-0 mt-2 p-2" style="border-radius: 12px;">
                                <a href="{{ route('logout') ?? '#' }}" class="dropdown-item text-danger fw-medium rounded" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Keluar Aplikasi
                                </a>
                                <form id="logout-form" action="{{ route('logout') ?? '#' }}" method="POST" class="d-none">@csrf</form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="page-body mt-4">
                <div class="container-fluid px-4">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script>
    @stack('scripts')
</body>
</html>
