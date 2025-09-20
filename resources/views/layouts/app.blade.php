<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistem Informasi Komoditas Kacang-Kacangan</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #2E7D32;
            --secondary-color: #4CAF50;
            --accent-color: #81C784;
            --text-dark: #1B5E20;
            --bg-light: #F1F8E9;
            --navbar-height: 76px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            padding-top: var(--navbar-height);
        }

        .navbar {
            height: var(--navbar-height);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 0;
            transition: none;
        }

        .navbar .container {
            height: 100%;
            display: flex;
            align-items: center;
            padding: 12px 15px;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.25rem;
            color: var(--primary-color) !important;
            text-decoration: none;
            display: flex;
            align-items: center;
            height: 100%;
            margin: 0;
        }

        .navbar-brand i {
            margin-right: 8px;
            font-size: 1.25rem;
        }

        .navbar-nav {
            height: 100%;
            align-items: center;
        }

        .navbar-nav .nav-item {
            height: 100%;
            display: flex;
            align-items: center;
        }

        .navbar-nav .nav-link {
            color: var(--text-dark) !important;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 8px 16px !important;
            border-radius: 6px;
            margin: 0 2px;
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            height: auto;
            line-height: 1.4;
        }

        .navbar-nav .nav-link:hover {
            background-color: var(--bg-light);
            color: var(--primary-color) !important;
            transform: none;
        }

        .navbar-nav .nav-link.active {
            background-color: var(--bg-light);
            color: var(--primary-color) !important;
            font-weight: 600;
        }

        .navbar-nav .dropdown-toggle::after {
            margin-left: 6px;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            margin-top: 8px;
        }

        .dropdown-item {
            padding: 8px 16px;
            font-size: 0.9rem;
            color: var(--text-dark);
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: var(--bg-light);
            color: var(--primary-color);
        }

        .navbar .d-flex {
            align-items: center;
            height: 100%;
            margin: 0;
        }

        .navbar .form-control {
            height: 38px;
            font-size: 0.9rem;
            border: 1px solid #ddd;
            border-radius: 20px;
            padding: 6px 12px;
            width: 200px;
            transition: all 0.2s ease;
        }

        .navbar .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(46, 125, 50, 0.25);
        }

        .navbar .btn-outline-success {
            height: 38px;
            width: 38px;
            padding: 0;
            border-radius: 20px;
            border-color: var(--primary-color);
            color: var(--primary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .navbar .btn-outline-success:hover {
            color: #fff !important;
            background-color: #198754 !important;
            border-color: #198754 !important;
        }

        

        @media (max-width: 991.98px) {
            .navbar-collapse {
                margin-top: 1rem;
                padding-top: 1rem;
                border-top: 1px solid #eee;
            }

            .navbar-nav .nav-link {
                padding: 10px 0 !important;
                margin: 0;
                border-radius: 0;
            }

            .navbar .d-flex {
                margin-top: 1rem;
                width: 100%;
            }

            .navbar .form-control {
                width: 100%;
                flex: 1;
            }
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .card {
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 80px 0;
        }

        .stats-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }

        .stats-card i {
            color: var(--secondary-color);
            margin-bottom: 15px;
        }

        .feature-card {
            padding: 40px 20px;
            text-align: center;
            height: 100%;
            background: white;
            border-radius: 15px;
        }

        .sidebar {
            background: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            min-height: calc(100vh - var(--navbar-height));
        }

        .sidebar .nav-link {
            color: var(--text-dark);
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 0;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: var(--bg-light);
            color: var(--primary-color);
        }

        .map-container {
            height: 600px;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .filter-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .varietas-card .card-img-top {
            height: 200px;
            object-fit: cover;
        }

        .badge-custom {
            background-color: var(--accent-color);
            color: white;
        }

        .footer {
            background-color: var(--primary-color);
            color: white;
            padding: 40px 0 20px;
            margin-top: 80px;
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-leaf"></i>
                SiKoKa
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('peta') ? 'active' : '' }}" href="{{ route('peta') }}">
                            Peta
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('varietas.*') ? 'active' : '' }}"
                            href="#" role="button" data-bs-toggle="dropdown">
                            Varietas
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('varietas.kedelai') }}">Kedelai</a></li>
                            <li><a class="dropdown-item" href="{{ route('varietas.kacang-tanah') }}">Kacang Tanah</a>
                            </li>
                            <li><a class="dropdown-item" href="{{ route('varietas.kacang-hijau') }}">Kacang Hijau</a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('opt.*') || request()->routeIs('deteksi.*') ? 'active' : '' }}"
                            href="{{ route('deteksi.index') }}">
                            Deteksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <!-- Search Form -->
                        <form class="d-flex" action="{{ route('search') }}" method="GET">
                            <input class="form-control me-2" type="search" name="q" placeholder="Cari..."
                                value="{{ request('q') }}">
                            <button class="btn btn-outline-success" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-leaf me-2"></i>SiKoKa</h5>
                    <p class="mb-0">Platform terpadu untuk informasi persebaran, varietas unggulan, dan pengendalian
                        hama penyakit komoditas kedelai, kacang tanah, dan kacang hijau di seluruh Indonesia.</p>
                </div>
                <div class="col-md-3">
                    <h6>Menu</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('peta') }}" class="text-white-50">Peta Persebaran</a></li>
                        <li><a href="{{ route('varietas.index') }}" class="text-white-50">Database Varietas</a></li>
                        <li><a href="{{ route('opt.index') }}" class="text-white-50">Deteksi Hama & Penyakit</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Kontak</h6>
                    <p class="mb-0 text-white-50">
                        <i class="fas fa-envelope me-2"></i>brmp@pertanian.go.id<br>
                        <i class="fas fa-phone me-2"></i>(021) 780 6202
                    </p>
                </div>
            </div>
            <hr class="my-4">
            <div class="row">
                <div class="col-12 text-center">
                    <p class="mb-0 text-white-50">&copy; 2024 SiKoKa. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.js"></script>

    <script>
        // Global CSRF token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5'
        });
    </script>

    @stack('scripts')
</body>

</html>
