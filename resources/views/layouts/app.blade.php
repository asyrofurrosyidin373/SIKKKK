<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'SIG BRMP Aneka Kacang')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    <style>
        :root {
            /* Green palette: use green-700 */
            --primary-green: #15803d; /* green-700 */
            --secondary-green: #22c55e; /* green-500 */
            --light-green: #86efac; /* green-300 */
            --accent-green: #4ade80; /* green-400 */
            --dark-green: #15803d; /* green-700 */
            --text-dark: #1f2937; /* gray-800 */
            --text-light: #ffffff;
            --bg-light: #f0fdf4; /* green-50 */
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-light);
        }

        .navbar-brmp {
            background: #ffffff !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.06);
        }

        .navbar-brand-brmp {
            display: flex;
            align-items: center;
            font-weight: bold;
            color: var(--primary-green) !important;
        }

        .logo-circle {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            color: var(--text-light);
            font-size: 1.2rem;
        }

        .nav-link {
            color: var(--primary-green) !important;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--dark-green) !important;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border-radius: 8px;
        }

        /* Ensure navbar dropdown does not change navbar height */
        .navbar { overflow: visible; }
        .navbar .navbar-nav { flex-wrap: nowrap !important; align-items: center; }
        .navbar .dropdown { position: relative; }
        .navbar .dropdown-menu { position: absolute !important; top: 100% !important; left: 0 !important; margin-top: 0.5rem; z-index: 2000; }
        /* Optional: keep navbar height stable and always above map */
        .navbar.navbar-brmp { min-height: 64px; position: sticky; top: 0; z-index: 3000; }

        .dropdown-item {
            color: var(--text-dark);
            transition: all 0.3s ease;
        }

        .dropdown-item:hover {
            background-color: var(--primary-green);
            color: var(--text-light);
        }

        .footer-brmp {
            background: var(--primary-green) !important; /* solid green-700 */
            color: var(--text-light);
        }

        .footer-brmp h5, .footer-brmp h6 {
            color: #ffffff;
        }

        .footer-brmp a {
            color: #ffffff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-brmp a:hover {
            color: #ffffff;
        }

        .social-links a {
            color: #ffffff !important;
            transition: color 0.3s ease;
        }

        .social-links a:hover {
            color: #ffffff !important;
        }

        .footer-brmp p {
            color: #ffffff !important;
        }

        /* Search input styling: white bg with green ring */
        .search-input {
            background-color: #ffffff !important;
            border: 1.5px solid var(--accent-green) !important; /* subtle green border */
            box-shadow: 0 0 0 0.15rem rgba(22, 163, 74, 0.15); /* green ring */
            color: var(--text-dark);
        }
        .search-input:focus {
            outline: none;
            border-color: var(--primary-green) !important;
            box-shadow: 0 0 0 0.22rem rgba(22, 163, 74, 0.25);
        }
        /* Global tabs spacing */
        .nav-tabs { margin-bottom: 16px; }
        .tab-content { margin-top: 0; }

        /* Mobile offcanvas: 70% width and above navbar */
        .offcanvas { z-index: 5000 !important; }
        @media (max-width: 991.98px) {
            .offcanvas-start {
                width: 70vw !important;
                max-width: 70vw !important;
            }
            /* Center sidebar nav links on mobile */
            #mobileNav .offcanvas-body {
                display: flex;
                flex-direction: column;
                gap: 0.5rem;
                align-items: center; /* center items horizontally */
            }
            #mobileNav .nav-link,
            #mobileNav .dropdown-toggle {
                width: 100%;
                text-align: center; /* center text */
            }
            #mobileNav .dropdown-menu {
                inset: auto !important; /* let it size below toggle */
                position: static !important; /* keep inside flow */
                width: 100%;
                text-align: center;
                margin-top: 0.25rem;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-light navbar-brmp shadow-sm bg-white sticky top-0 z-50">
        <div class="container d-flex align-items-center">
            <!-- Mobile: Hamburger on the left -->
            <button class="btn d-lg-none me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNav" aria-controls="mobileNav" aria-label="Open navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Brand: on mobile aligned to the right, on desktop normal -->
            <a class="navbar-brand navbar-brand-brmp fw-bold d-flex align-items-center ms-auto ms-lg-0 order-2 order-lg-1" href="{{ route('home') }}">
                <i class="fas fa-leaf me-2"></i>
                <span>SIAKA</span>
            </a>
            <!-- Desktop nav: visible on lg+, hidden on mobile -->
            <ul class="navbar-nav ms-auto align-items-lg-center d-none d-lg-flex flex-row gap-3 order-lg-2">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('peta') ? 'active' : '' }}" href="{{ route('peta') }}">Peta</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ request()->routeIs('varietas.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">Varietas</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('varietas.index') }}">Semua Varietas</a></li>
                        <li><a class="dropdown-item" href="{{ route('varietas.kedelai') }}">Kacang Kedelai</a></li>
                        <li><a class="dropdown-item" href="{{ route('varietas.kacang-tanah') }}">Kacang Tanah</a></li>
                        <li><a class="dropdown-item" href="{{ route('varietas.kacang-hijau') }}">Kacang Hijau</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('deteksi.*') ? 'active' : '' }}" href="{{ route('deteksi.index') }}">Deteksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('opt.*') ? 'active' : '' }}" href="{{ route('opt.index') }}">Opt</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Mobile Offcanvas Sidebar Navigation -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileNav" aria-labelledby="mobileNavLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="mobileNavLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column gap-2">
            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Beranda</a>
            <a class="nav-link {{ request()->routeIs('peta') ? 'active' : '' }}" href="{{ route('peta') }}">Peta</a>
            <div class="dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('varietas.*') ? 'active' : '' }}" href="#" id="varietasMobile" role="button" data-bs-toggle="dropdown" aria-expanded="false">Varietas</a>
                <ul class="dropdown-menu" aria-labelledby="varietasMobile">
                    <li><a class="dropdown-item" href="{{ route('varietas.index') }}">Semua Varietas</a></li>
                    <li><a class="dropdown-item" href="{{ route('varietas.kedelai') }}">Kedelai</a></li>
                    <li><a class="dropdown-item" href="{{ route('varietas.kacang-tanah') }}">Kacang Tanah</a></li>
                    <li><a class="dropdown-item" href="{{ route('varietas.kacang-hijau') }}">Kacang Hijau</a></li>
                </ul>
            </div>
            <a class="nav-link {{ request()->routeIs('deteksi.*') ? 'active' : '' }}" href="{{ route('deteksi.index') }}">Deteksi</a>
            <a class="nav-link {{ request()->routeIs('opt.*') ? 'active' : '' }}" href="{{ route('opt.index') }}">Opt</a>
        </div>
    </div>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer-brmp py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="mb-1 d-flex align-items-center"><i class="fas fa-leaf me-2"></i> SIAKA</h5>
                    <p class="mb-1">Sistem Informasi Aneka Kacang</p>
                    <p class="mb-3">
                        Aplikasi SIG dan sistem pakar untuk komoditas aneka kacang: memetakan sebaran, mendeteksi hama & penyakit, serta memberi rujukan varietas dan pengendalian.
                    </p>
                </div>
                <div class="col-md-3">
                    <h6>Menu Utama</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}">Beranda</a></li>
                        <li><a href="{{ route('peta') }}">Peta</a></li>
                        <li><a href="{{ route('varietas.index') }}">Varietas</a></li>
                        <li><a href="{{ route('deteksi.index') }}">Deteksi</a></li>
                        <li><a href="{{ route('opt.index') }}">Opt</a></li>
                        
                        
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Kontak</h6>
                    <div class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> brmp.anekakacang@pertanian.go.id</li>
                        <li><i class="fas fa-phone me-2"></i> (0341) 801-468</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Jl. Raya Kendalpayak km 8, Kab. Malang 65101</li>
                    </div>
                </div>
            </div>
            <hr style="border-color: var(--accent-green);" class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0" style="color: rgba(255,255,255,0.8);">
                        &copy; {{ date('Y') }} BRMP Aneka Kacang. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="social-links">
                        <a href="https://wa.me/6285707096784" class="me-3"><i class="fab fa-whatsapp"></i></a>
                        <a href="https://www.facebook.com/brmpanekakacang" class="me-3"><i class="fab fa-facebook"></i></a>
                        <a href="https://www.youtube.com/@brmpanekakacang" class="me-3"><i class="fab fa-youtube"></i></a>
                        <a href="https://www.instagram.com/brmpanekakacang" class="me-3"><i class="fab fa-instagram"></i></a>
                        <a href="https://x.com/brmpanekakacang" class="me-3"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.tiktok.com/@brmpanekakacang"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    <!-- Custom JS -->
    <script>
        // CSRF token setup for AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Global error handler
        $(document).ajaxError(function(event, xhr, settings, thrownError) {
            if (xhr.status === 419) {
                alert('Session expired. Please refresh the page.');
                location.reload();
            }
        });

        // Loading overlay
        function showLoading() {
            $('body').append('<div id="loading-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-50" style="z-index: 9999;"><div class="spinner-border text-light" role="status"><span class="visually-hidden">Loading...</span></div></div>');
        }

        function hideLoading() {
            $('#loading-overlay').remove();
        }

        // Auto-hide alerts
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Tooltip initialization
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
    
    @stack('scripts')
</body>
</html>