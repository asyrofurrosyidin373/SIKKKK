<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIG BRMP Aneka Kacang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-green: #2d5016;
            --secondary-green: #4a7c59;
            --light-green: #6b8e23;
            --accent-green: #8fbc8f;
            --dark-green: #1a3d0a;
            --text-dark: #2c3e50;
            --text-light: #ffffff;
            --bg-light: #f8f9fa;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        /* Header */
        .top-bar {
            background-color: var(--primary-green);
            color: var(--text-light);
            padding: 8px 0;
            font-size: 0.9rem;
        }

        .top-bar a {
            color: var(--text-light);
            text-decoration: none;
            margin-right: 20px;
        }

        .top-bar a:hover {
            color: var(--accent-green);
        }

        .navbar-custom {
            background-color: var(--text-light);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 15px 0;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            font-weight: bold;
            color: var(--primary-green);
        }

        .logo-circle {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--light-green), var(--secondary-green));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: white;
            font-size: 1.5rem;
        }

        .nav-link {
            color: var(--text-dark);
            font-weight: 500;
            margin: 0 10px;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-green);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(rgba(45, 80, 22, 0.8), rgba(74, 124, 89, 0.8)), 
                        url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 600"><rect fill="%23f0f8f0" width="1200" height="600"/><circle fill="%23d4edda" cx="200" cy="150" r="80"/><circle fill="%23c3e6cb" cx="400" cy="200" r="60"/><circle fill="%23b8dacc" cx="600" cy="180" r="70"/><circle fill="%23a3d9a4" cx="800" cy="160" r="50"/><circle fill="%238fbc8f" cx="1000" cy="190" r="65"/></svg>');
            background-size: cover;
            background-position: center;
            min-height: 70vh;
            display: flex;
            align-items: center;
            color: var(--text-light);
            position: relative;
        }

        .hero-content {
            text-align: center;
            z-index: 2;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Stats Cards */
        .stats-section {
            margin-top: -50px;
            position: relative;
            z-index: 3;
        }

        .stat-card {
            background: var(--text-light);
            border-radius: 15px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--light-green), var(--secondary-green));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-green);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: var(--text-dark);
            font-weight: 500;
        }

        /* Feature Cards */
        .feature-card {
            background: var(--text-light);
            border-radius: 15px;
            padding: 2.5rem;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--light-green), var(--secondary-green));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
            color: white;
            font-size: 2rem;
        }

        .feature-title {
            color: var(--primary-green);
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .feature-description {
            color: var(--text-dark);
            margin-bottom: 1.5rem;
            line-height: 1.6;
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-green), var(--secondary-green));
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            color: var(--text-light);
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(45, 80, 22, 0.3);
            color: var(--text-light);
        }

        .btn-outline-custom {
            border: 2px solid var(--primary-green);
            color: var(--primary-green);
            background: transparent;
            border-radius: 25px;
            padding: 10px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-outline-custom:hover {
            background: var(--primary-green);
            color: var(--text-light);
            transform: translateY(-2px);
        }

        /* Footer */
        .footer {
            background-color: var(--primary-green);
            color: var(--text-light);
            padding: 3rem 0 1rem;
            margin-top: 4rem;
        }

        .footer h5 {
            color: var(--accent-green);
            margin-bottom: 1rem;
        }

        .footer a {
            color: var(--text-light);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer a:hover {
            color: var(--accent-green);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 2.5rem;
            }
            
            .hero-subtitle {
                font-size: 1.1rem;
            }
            
            .stat-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <i class="fas fa-map-marker-alt me-2"></i>
                    <span>Jl. Raya Kendalpayak km 8, Kab. Malang 65101</span>
                    <i class="fas fa-phone ms-3 me-2"></i>
                    <span>(0341) 801-468</span>
                    <i class="fas fa-envelope ms-3 me-2"></i>
                    <span>brmp.anekakacang@pertanian.go.id</span>
                </div>
                <div class="col-md-4 text-end">
                    <a href="#"><i class="fab fa-whatsapp me-2"></i></a>
                    <a href="#"><i class="fab fa-facebook me-2"></i></a>
                    <a href="#"><i class="fab fa-youtube me-2"></i></a>
                    <a href="#"><i class="fab fa-instagram me-2"></i></a>
                    <a href="#"><i class="fab fa-twitter me-2"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="/">
                <div class="logo-circle">
                    <i class="fas fa-seedling"></i>
                </div>
                <div>
                    <div style="font-size: 0.9rem; line-height: 1.2;">BALAI PERAKITAN DAN PENGUJIAN</div>
                    <div style="font-size: 0.8rem; line-height: 1.2;">TANAMAN ANEKA KACANG</div>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">BERANDA</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/deteksi">DETEKSI</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/opt">OPT</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/varietas">VARIETAS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/map">PETA</a>
                    </li>
                </ul>
                <div class="ms-3">
                    <i class="fas fa-search"></i>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">SIG BRMP Aneka Kacang</h1>
                <p class="hero-subtitle">
                    Sistem Informasi Geografis untuk Budidaya, Riset, dan Manajemen Produksi Aneka Kacang. 
                    Platform canggih untuk mendeteksi, mengidentifikasi, dan mengendalikan hama serta penyakit 
                    pada tanaman kedelai, kacang tanah, dan kacang hijau dengan teknologi AI.
                </p>
                <div class="mt-4">
                    <a href="/deteksi" class="btn-primary-custom me-3">
                        <i class="fas fa-search me-2"></i>Deteksi Sekarang
                    </a>
                    <a href="/opt" class="btn-outline-custom">
                        <i class="fas fa-database me-2"></i>Lihat Database OPT
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-bug"></i>
                        </div>
                        <div class="stat-number">23</div>
                        <div class="stat-label">Hama & Penyakit</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <div class="stat-number">25</div>
                        <div class="stat-label">Gejala Terdaftar</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-seedling"></i>
                        </div>
                        <div class="stat-number">3</div>
                        <div class="stat-label">Varietas Tanaman</div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="stat-number">0</div>
                        <div class="stat-label">Deteksi Hari Ini</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <h2 class="mb-3" style="color: var(--primary-green);">Fitur Utama</h2>
                    <p class="text-muted">Platform lengkap untuk manajemen dan deteksi hama penyakit tanaman kacang</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>
                        <h5 class="feature-title">Peta Tematik</h5>
                        <p class="feature-description">Visualisasi data geografis dengan filter canggih untuk analisis produksi aneka kacang</p>
                        <a href="/map" class="btn-primary-custom">Lihat Peta</a>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <h5 class="feature-title">Deteksi Hama</h5>
                        <p class="feature-description">Identifikasi hama dan penyakit dengan teknologi AI dan database komprehensif</p>
                        <a href="/deteksi" class="btn-primary-custom">Deteksi</a>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-leaf"></i>
                        </div>
                        <h5 class="feature-title">Varietas</h5>
                        <p class="feature-description">Database varietas kacang terlengkap dengan informasi budidaya dan karakteristik</p>
                        <a href="/varietas" class="btn-primary-custom">Lihat Varietas</a>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h5 class="feature-title">Responsive Design</h5>
                        <p class="feature-description">Akses mudah dari berbagai perangkat dengan tampilan yang optimal dan user-friendly</p>
                        <a href="/dashboard" class="btn-primary-custom">Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-seedling me-2"></i>SIG BRMP Aneka Kacang</h5>
                    <p>Sistem Informasi Geografis untuk Budidaya, Riset, dan Manajemen Produksi Aneka Kacang</p>
                </div>
                <div class="col-md-4">
                    <h5>Menu Utama</h5>
                    <ul class="list-unstyled">
                        <li><a href="/">Beranda</a></li>
                        <li><a href="/deteksi">Deteksi</a></li>
                        <li><a href="/map">Peta</a></li>
                        <li><a href="/varietas">Varietas</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-envelope me-2"></i>brmp.anekakacang@pertanian.go.id</li>
                        <li><i class="fas fa-phone me-2"></i>(0341) 801-468</li>
                        <li><i class="fas fa-map-marker-alt me-2"></i>Malang, Indonesia</li>
                    </ul>
                </div>
            </div>
            <hr style="border-color: var(--accent-green);">
            <div class="row">
                <div class="col-12 text-center">
                    <p>&copy; 2024 BRMP Aneka Kacang. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>