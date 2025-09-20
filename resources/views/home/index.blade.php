{{-- resources/views/home/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold mb-4">Sistem Informasi Komoditas Kacang-Kacangan</h1>
                    <p class="lead mb-4">Platform terpadu untuk informasi persebaran, varietas unggulan, dan pengendalian
                        hama penyakit komoditas kedelai, kacang tanah, dan kacang hijau di seluruh Indonesia.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('peta') }}" class="btn btn-light btn-lg">
                            Jelajahi Peta
                        </a>
                        <a href="{{ route('varietas.index') }}" class="btn btn-outline-light btn-lg">
                            Lihat Varietas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background:
                linear-gradient(to right, rgba(34, 139, 34, 0.9) 40%, rgba(34, 139, 34, 0.2) 70%, rgba(34, 139, 34, 0) 100%),
                url("https://images.unsplash.com/photo-1574323347407-f5e1ad6d020b?w=1200&h=800&fit=crop");
            background-size: cover;
            background-position: center right;
            color: #fff;
        }
    </style>

    <!-- Statistics Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="stats-card shadow-sm">
                        <i class="fas fa-map-marked-alt fa-3x"></i>
                        <h3 class="h2 text-primary">{{ $stats['total_provinsi'] }}</h3>
                        <p class="mb-0">Provinsi</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card shadow-sm">
                        <i class="fas fa-city fa-3x"></i>
                        <h3 class="h2 text-primary">{{ $stats['total_kabupaten'] }}</h3>
                        <p class="mb-0">Kabupaten</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card shadow-sm">
                        <i class="fas fa-home fa-3x"></i>
                        <h3 class="h2 text-primary">{{ $stats['total_kecamatan'] }}</h3>
                        <p class="mb-0">Kecamatan</p>
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
                    <h2 class="display-5 fw-bold text-success">Fitur Utama</h2>
                    <p class="lead text-muted">Akses informasi lengkap tentang komoditas kacang-kacangan</p>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="feature-card shadow-sm">
                        <i class="fas fa-map fa-3x text-success mb-3"></i>
                        <h5>Peta Interaktif</h5>
                        <p class="text-muted">Jelajahi persebaran komoditas di seluruh Indonesia dengan filter berdasarkan
                            wilayah</p>
                        <a href="{{ route('peta') }}" class="btn btn-primary px-5">Buka Peta</a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card shadow-sm">
                        <i class="fas fa-seedling fa-3x text-success mb-3"></i>
                        <h5>Database Varietas</h5>
                        <p class="text-muted">Informasi lengkap varietas unggulan beserta karakteristik dan rekomendasi</p>
                        <a href="{{ route('varietas.index') }}" class="btn btn-primary px-5">Lihat Varietas</a>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="feature-card shadow-sm">
                        <i class="fas fa-bug fa-3x text-success mb-3"></i>
                        <h5>Deteksi Hama & Penyakit</h5>
                        <p class="text-muted">Identifikasi dan pengendalian organisme pengganggu tanaman kacang</p>
                        <a href="{{ route('opt.index') }}" class="btn btn-primary px-5">Pelajari OPT</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
