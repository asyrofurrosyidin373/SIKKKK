<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', 'Sistem Deteksi Hama & Penyakit Tanaman')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">
                <i class="fas fa-seedling me-2"></i>
                SIKP
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('deteksi.*') ? 'active' : '' }}" href="{{ route('deteksi.index') }}">
                            <i class="fas fa-search me-1"></i> Deteksi
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('opt.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bug me-1"></i> OPT
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('opt.index') }}">Database OPT</a></li>
                            <li><a class="dropdown-item" href="{{ route('opt.index', ['jenis' => 'hama']) }}">Hama</a></li>
                            <li><a class="dropdown-item" href="{{ route('opt.index', ['jenis' => 'penyakit']) }}">Penyakit</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('varietas.*') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-seedling me-1"></i> Varietas
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('varietas.index') }}">Semua Varietas</a></li>
                            <li><a class="dropdown-item" href="{{ route('varietas.kedelai') }}">Kedelai</a></li>
                            <li><a class="dropdown-item" href="{{ route('varietas.kacang-tanah') }}">Kacang Tanah</a></li>
                            <li><a class="dropdown-item" href="{{ route('varietas.kacang-hijau') }}">Kacang Hijau</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('peta') ? 'active' : '' }}" href="{{ route('peta') }}">
                            <i class="fas fa-map me-1"></i> Peta
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('search') }}">
                            <i class="fas fa-search me-1"></i> Pencarian
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>
                        <i class="fas fa-seedling me-2"></i>
                        Sistem Deteksi Hama & Penyakit Tanaman
                    </h5>
                    <p class="text-muted">
                        Platform canggih untuk mendeteksi dan mengendalikan hama serta penyakit pada tanaman kedelai, kacang tanah, dan kacang hijau.
                    </p>
                </div>
                <div class="col-md-3">
                    <h6>Menu Utama</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-muted text-decoration-none">Beranda</a></li>
                        <li><a href="{{ route('deteksi.index') }}" class="text-muted text-decoration-none">Deteksi</a></li>
                        <li><a href="{{ route('opt.index') }}" class="text-muted text-decoration-none">Database OPT</a></li>
                        <li><a href="{{ route('varietas.index') }}" class="text-muted text-decoration-none">Varietas</a></li>
                        <li><a href="{{ route('peta') }}" class="text-muted text-decoration-none">Peta</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Kontak</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i> info@sikp.com</li>
                        <li><i class="fas fa-phone me-2"></i> +62 123 456 789</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i> Indonesia</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-muted">
                        &copy; {{ date('Y') }} Sistem Deteksi Hama & Penyakit Tanaman. All rights reserved.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="social-links">
                        <a href="#" class="text-muted me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-muted me-3"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-muted me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-muted"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
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