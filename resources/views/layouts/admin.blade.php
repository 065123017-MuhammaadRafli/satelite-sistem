<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <title>{{ config('app.name', 'SATELITE SISTEM') }} - @yield('title')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/css/tabler.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />

    @vite(['resources/js/app.js'])

    <style>
        :root { --tblr-font-sans-serif: 'Inter var', sans-serif; }
        body { background-color: #f1f5f9; color: #1e293b; overflow-x: hidden; }
        .page { display: flex; width: 100%; min-height: 100vh; flex-direction: column; }

        /* =========================================================
           1. EFEK MUNCUL HALAMAN (HALUS & TANPA DELAY)
        ========================================================= */
        .page-wrapper {
            animation: smoothEntry 0.4s cubic-bezier(0.22, 1, 0.36, 1) forwards;
            opacity: 0;
            transform: translateY(15px);
        }
        @keyframes smoothEntry {
            to { opacity: 1; transform: translateY(0); }
        }

        #nprogress .bar { background: #3b82f6 !important; height: 3px !important; }
        #nprogress .peg { box-shadow: 0 0 10px #3b82f6, 0 0 5px #3b82f6 !important; }
        #nprogress .spinner-icon { display: none !important; }

        /* =========================================================
           2. HEADER GLASSMORPHISM
        ========================================================= */
        .container-optimum { width: 100% !important; max-width: 100% !important; padding-left: 2rem !important; padding-right: 2rem !important; }
        .header-glass {
            background: linear-gradient(135deg, #f8fafc 40%, #e0e7ff 100%) !important;
            border-bottom: 1px solid #c7d2fe !important; z-index: 1030;
            padding-top: 0.65rem !important; padding-bottom: 0.65rem !important;
            box-shadow: 0 4px 20px rgba(79, 70, 229, 0.03);
        }
        .page-title-header { font-size: 1.05rem !important; font-weight: 800 !important; color: #1e3a8a !important; margin-bottom: 0; letter-spacing: -0.3px; line-height: 1.2; white-space: nowrap !important; }
        .page-subtitle-header { font-size: 0.75rem !important; color: #475569 !important; margin-top: 2px; line-height: 1.3; white-space: nowrap !important; }
        .header-icon-container { width: 36px !important; height: 36px !important; background: #ffffff !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04); border-radius: 8px !important; flex-shrink: 0; }

        /* =========================================================
           3. SIDEBAR (ANIMASI LIPAT ELEGANT)
        ========================================================= */
        .navbar-vertical {
            background-color: #0f172a !important; border-right: 1px solid rgba(255,255,255,0.05); box-shadow: 4px 0 20px rgba(0,0,0,0.08); z-index: 1040; overflow-x: hidden;
        }

        @media (min-width: 992px) {
            .navbar-vertical {
                position: fixed !important; top: 0; left: 0; bottom: 0;
                width: 260px !important;
                transition: width 0.35s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
            }
            .page-wrapper {
                margin-left: 260px !important; width: calc(100% - 260px) !important;
                transition: margin-left 0.35s cubic-bezier(0.25, 0.8, 0.25, 1), width 0.35s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
            }

            /* --- KETIKA MENU DILIPAT --- */
            body.sidebar-collapsed .navbar-vertical { width: 85px !important; }
            body.sidebar-collapsed .page-wrapper { margin-left: 85px !important; width: calc(100% - 85px) !important; }

            .brand-text-wrapper, .nav-link-title, .badge-live, .sidebar-heading {
                transition: opacity 0.2s ease;
                opacity: 1; visibility: visible; white-space: nowrap; overflow: hidden;
            }

            body.sidebar-collapsed .brand-text-wrapper,
            body.sidebar-collapsed .nav-link-title,
            body.sidebar-collapsed .badge-live,
            body.sidebar-collapsed .sidebar-heading {
                opacity: 0; visibility: hidden; width: 0 !important; margin: 0 !important; padding: 0 !important;
            }

            body.sidebar-collapsed .brand-container { padding-left: 0 !important; justify-content: center !important; }
            body.sidebar-collapsed .nav-link { justify-content: center !important; padding: 0.8rem 0 !important; margin: 0.25rem 0.6rem !important; }
            body.sidebar-collapsed .nav-link-icon { margin-right: 0 !important; font-size: 1.25rem !important; }
            body.sidebar-collapsed .nav-item.active .nav-link::before { left: 0; width: 3px; border-radius: 4px; }
        }

        /* =========================================================
           4. STYLING ELEMEN DALAM SIDEBAR (DIKOREKSI AGAR RAPIH)
        ========================================================= */
        /* Padding disejajarkan dengan posisi margin menu */
        .navbar-brand-autodark { padding: 1.25rem 1.5rem !important; border-bottom: 1px solid rgba(255,255,255,0.05); margin-bottom: 0.5rem; display: flex; align-items: center; }
        .brand-container { display: flex; align-items: center; text-decoration: none; position: relative; z-index: 20; width: 100%; }
        .brand-container:hover { opacity: 0.85; }
        .brand-icon-wrapper { display: flex; align-items: center; justify-content: center; width: 38px; height: 38px; background: rgba(59, 130, 246, 0.12); border-radius: 10px; border: 1px solid rgba(59, 130, 246, 0.35); flex-shrink: 0; }
        .brand-logo-icon { font-size: 1.15rem; background: linear-gradient(135deg, #bae6fd, #3b82f6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; transform: rotate(-45deg); }

        /* Font size dikecilkan jadi 1.1rem agar tidak terpotong */
        .brand-text-wrapper { font-size: 1.1rem; letter-spacing: 0.3px; line-height: 1; display: flex; align-items: center; padding-left: 0.75rem; }
        .brand-text-primary { color: #ffffff; font-weight: 800; }
        .brand-text-secondary { color: #93c5fd; font-weight: 400; margin-left: 4px; }

        .sidebar-heading { color: #475569; font-size: 0.62rem; font-weight: 800; letter-spacing: 1.5px; text-transform: uppercase; padding: 1.25rem 1.25rem 0.35rem 1.5rem; margin: 0; }
        .nav-link { color: #94a3b8 !important; border-radius: 8px; padding: 0.6rem 0.85rem !important; margin: 0.25rem 1rem; font-size: 0.85rem; font-weight: 500; display: flex; align-items: center; position: relative; z-index: 10; transition: all 0.2s ease; }
        .nav-link-icon { color: #475569; width: 20px; text-align: center; font-size: 1.05rem; margin-right: 0.75rem; transition: all 0.2s ease; flex-shrink: 0; }
        .nav-link:hover { background-color: rgba(255, 255, 255, 0.04); color: #e2e8f0 !important; }
        .nav-link:hover .nav-link-icon { color: #94a3b8; }
        .nav-item.active .nav-link { background-color: rgba(59, 130, 246, 0.1) !important; color: #60a5fa !important; font-weight: 600; }
        .nav-item.active .nav-link::before { content: ''; position: absolute; left: -1rem; top: 15%; height: 70%; width: 4px; background-color: #3b82f6; border-radius: 0 4px 4px 0; box-shadow: 0 0 10px rgba(59, 130, 246, 0.5); }
        .nav-item.active .nav-link-icon { color: #60a5fa !important; }

        .badge-live { background: rgba(239, 68, 68, 0.15); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.3); padding: 0.2rem 0.4rem; border-radius: 5px; font-size: 0.58rem; font-weight: 700; letter-spacing: 0.5px; display: flex; align-items: center; gap: 4px; margin-left: auto; }
        .pulse-dot { height: 5px; width: 5px; background-color: #ef4444; border-radius: 50%; display: inline-block; animation: pulse-red 1.5s infinite; }
        @keyframes pulse-red { 0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); } 70% { box-shadow: 0 0 0 5px rgba(239, 68, 68, 0); } 100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); } }

        /* TOMBOL GARIS 3 */
        .btn-sidebar-toggle { width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; color: #64748b; transition: all 0.2s; cursor: pointer; box-shadow: 0 2px 4px rgba(0,0,0,0.02); flex-shrink: 0; }
        .btn-sidebar-toggle:hover { background: #f8fafc; color: #3b82f6; border-color: #cbd5e1; }
    </style>
    @stack('styles')
</head>
<body>
    <script>
        // Anti-Flicker: Set status dilipat detik pertama dimuat
        if(localStorage.getItem('sidebarState') === 'collapsed') {
            document.body.classList.add('sidebar-collapsed');
        }
    </script>

    <div class="page">
        <aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
            <div class="container-fluid p-0">
                <button class="navbar-toggler ms-3" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="navbar-brand-autodark w-100">
                    <a href="{{ route('dashboard') }}" class="brand-container nav-trigger">
                        <div class="brand-icon-wrapper">
                            <i class="fas fa-satellite brand-logo-icon"></i>
                        </div>
                        <div class="brand-text-wrapper">
                            <span class="brand-text-primary">SATELITE</span><span class="brand-text-secondary">SYSTEM</span>
                        </div>
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="sidebar-menu">
                    <ul class="navbar-nav pt-3 w-100">
                        <li class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <a class="nav-link nav-trigger" href="{{ route('dashboard') }}">
                                <span class="nav-link-icon"><i class="fas fa-th-large"></i></span>
                                <span class="nav-link-title">Dashboard Utama</span>
                            </a>
                        </li>

                        <div class="sidebar-heading">Pusat Operasional</div>

                        <li class="nav-item {{ request()->is('satellites*') && !request()->routeIs('satellites.live') ? 'active' : '' }}">
                            <a class="nav-link nav-trigger" href="{{ route('satellites.index') }}">
                                <span class="nav-link-icon"><i class="fas fa-space-shuttle"></i></span>
                                <span class="nav-link-title">Armada Satelit</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->routeIs('satellites.live') ? 'active' : '' }}">
                            <a class="nav-link nav-trigger" href="{{ route('satellites.live') }}">
                                <span class="nav-link-icon"><i class="fas fa-globe-asia"></i></span>
                                <span class="nav-link-title">Global Tracking</span>
                                <span class="badge-live"><span class="pulse-dot"></span> LIVE</span>
                            </a>
                        </li>

                        <li class="nav-item {{ request()->routeIs('ground-stations.*') ? 'active' : '' }}">
                            <a class="nav-link nav-trigger" href="{{ route('ground-stations.index') }}">
                                <span class="nav-link-icon"><i class="fas fa-broadcast-tower"></i></span>
                                <span class="nav-link-title">Ground Station</span>
                            </a>
                        </li>

                        <div class="sidebar-heading mt-2">Data & Analisis</div>

                        <li class="nav-item {{ request()->routeIs('statistics') ? 'active' : '' }}">
                            <a class="nav-link nav-trigger" href="{{ route('statistics') }}">
                                <span class="nav-link-icon"><i class="fas fa-chart-pie"></i></span>
                                <span class="nav-link-title">Analitik Sistem</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </aside>

        <div class="page-wrapper">

            <header class="navbar navbar-expand-md navbar-light sticky-top header-glass">
                <div class="container-fluid container-optimum">

                    <div class="d-flex align-items-center me-auto gap-3" style="min-width: 0;">
                        <div class="btn-sidebar-toggle d-none d-lg-flex shadow-sm" id="desktop-sidebar-toggle">
                            <i class="fas fa-bars fs-5" id="sidebar-icon"></i>
                        </div>

                        <div class="bg-blue-lt text-blue p-2 d-none d-md-flex align-items-center justify-content-center header-icon-container">
                            <i class="@yield('page-icon', 'fas fa-th-large') fs-5"></i>
                        </div>

                        <div style="min-width: 0;">
                            <h2 class="page-title-header text-truncate">@yield('page-title')</h2>
                            <div class="page-subtitle-header d-none d-sm-block text-truncate">@yield('page-subtitle')</div>
                        </div>
                    </div>

                    <div class="navbar-nav flex-row order-md-last flex-shrink-0">
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link d-flex lh-1 text-reset p-0 align-items-center" data-bs-toggle="dropdown">
                                <div class="d-none d-xl-block ps-2 text-end me-3">
                                    <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ Auth::user()->name ?? 'Administrator' }}</div>
                                    <div class="small text-muted fw-bold text-uppercase mt-1" style="letter-spacing: 0.5px; font-size: 0.65rem;">{{ Auth::user()->role ?? 'USER' }}</div>
                                </div>
                                <span class="avatar avatar-md bg-blue text-white fw-bold border shadow-sm" style="border-radius: 8px;">
                                    {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 p-2" style="border-radius: 10px;">
                                <a href="#" class="dropdown-item text-danger fw-medium rounded py-2" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Keluar Aplikasi
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </div>
                        </div>
                    </div>

                </div>
            </header>

            <div class="page-body mt-4 mb-5">
                <div class="container-fluid container-optimum">
                    @yield('content')
                </div>
            </div>

            <footer class="footer footer-transparent d-print-none mt-auto border-top py-3 bg-white">
                <div class="container-fluid container-optimum text-center text-md-start d-flex justify-content-between align-items-center">
                    <div class="text-muted small fw-medium">
                        &copy; {{ date('Y') }} <strong>Satellite System</strong>. All rights reserved.
                    </div>
                    <div class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">
                        Build v.1.0.0
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.0.0-beta20/dist/js/tabler.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {

            // 1. Matikan Loading Bar saat halaman selesai dirender
            NProgress.done();

            // 2. Transisi Klik Instan
            const navLinks = document.querySelectorAll('.nav-trigger');
            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    const href = this.getAttribute('href');
                    if (!href || href === '#' || href.startsWith('javascript') || this.getAttribute('target') === '_blank') return;

                    // Nyalakan garis biru loading di atas
                    NProgress.start();
                });
            });

            // 3. Logika Toggle Sidebar & Perubahan Ikon Garis 3
            const toggleBtn = document.getElementById('desktop-sidebar-toggle');
            const sidebarIcon = document.getElementById('sidebar-icon');

            if(toggleBtn && sidebarIcon) {
                // Set ikon pertama kali
                if(document.body.classList.contains('sidebar-collapsed')) {
                    sidebarIcon.className = 'fas fa-angle-double-right fs-5';
                }

                // Saat tombol diklik
                toggleBtn.addEventListener('click', function() {
                    document.body.classList.toggle('sidebar-collapsed');

                    if(document.body.classList.contains('sidebar-collapsed')) {
                        localStorage.setItem('sidebarState', 'collapsed');
                        sidebarIcon.className = 'fas fa-angle-double-right fs-5';
                    } else {
                        localStorage.setItem('sidebarState', 'expanded');
                        sidebarIcon.className = 'fas fa-bars fs-5';
                    }
                });
            }
        });

        // Jalankan progress bar sesaat sebelum DOM utuh untuk sensasi kecepatan
        NProgress.start();
    </script>

    @stack('scripts')
</body>
</html>
