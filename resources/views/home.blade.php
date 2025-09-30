@extends('layouts.app')

@section('content')
<div class="container">
    <div class="jumbotron bg-primary text-white text-center py-5 rounded mb-4">
        <h1 class="display-4"><i class="fas fa-leaf"></i> Sistem Informasi Geografis Pertanian</h1>
        <p class="lead">Platform terpadu untuk informasi peta tematik, database varietas tanaman, dan deteksi hama penyakit</p>
        <hr class="my-4 bg-white">
        <p>Temukan informasi lengkap tentang komoditas unggulan di berbagai wilayah Indonesia</p>
    </div>

    <!-- Sistem Informasi Geografis Komoditas Kacang-kacangan -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-lg" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                <div class="card-body text-white py-5">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                <i class="fas fa-seedling fa-3x me-3"></i>
                                <div>
                                    <h2 class="mb-1">Sistem Informasi Geografis Komoditas Kacang-kacangan</h2>
                                    <p class="mb-0 opacity-75">Platform canggih untuk mendeteksi, mengidentifikasi, dan mengendalikan hama serta penyakit pada tanaman kedelai, kacang tanah, dan kacang hijau dengan teknologi AI.</p>
                                </div>
                            </div>
                            
                            <div class="row text-center mt-4">
                                <div class="col-6 col-md-3">
                                    <div class="bg-white bg-opacity-20 rounded p-3 mb-2">
                                        <h4 class="mb-1">{{ $stats['total_kecamatan'] ?? '4' }}</h4>
                                        <small>Kecamatan</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="bg-white bg-opacity-20 rounded p-3 mb-2">
                                        <h4 class="mb-1">{{ $stats['with_coordinates'] ?? '4' }}</h4>
                                        <small>Koordinat</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="bg-white bg-opacity-20 rounded p-3 mb-2">
                                        <h4 class="mb-1">{{ $stats['varietas_tanaman'] ?? '1' }}</h4>
                                        <small>Varietas Tanaman</small>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="bg-white bg-opacity-20 rounded p-3 mb-2">
                                        <h4 class="mb-1">{{ $stats['deteksi_hari_ini'] ?? '0' }}</h4>
                                        <small>Deteksi Hari Ini</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="mb-3">
                                <i class="fas fa-map-marked-alt fa-5x opacity-75"></i>
                            </div>
                            <a href="/peta" class="btn btn-light btn-lg px-4 py-2 shadow">
                                <i class="fas fa-search me-2"></i>Deteksi Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-success">
                <div class="card-body text-center">
                    <i class="fas fa-map fa-4x text-success mb-3"></i>
                    <h5 class="card-title text-success">Peta Komoditas Kacang-kacangan</h5>
                    <p class="card-text">Jelajahi peta interaktif untuk melihat sebaran komoditas kedelai, kacang tanah, dan kacang hijau di berbagai wilayah.</p>
                </div>
                <div class="card-footer bg-light">
                    <a href="/peta" class="btn btn-success w-100">
                        <i class="fas fa-map me-1"></i> Buka Peta
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-seedling fa-4x text-success mb-3"></i>
                    <h5 class="card-title">Database Varietas</h5>
                    <p class="card-text">Cari dan bandingkan varietas kedelai, kacang tanah, dan kacang hijau.</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('varietas.index') }}" class="btn btn-success w-100">
                        <i class="fas fa-seedling"></i> Cari Varietas
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-search fa-4x text-warning mb-3"></i>
                    <h5 class="card-title">Deteksi Hama & Penyakit</h5>
                    <p class="card-text">Diagnosa hama dan penyakit tanaman berdasarkan gejala.</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('deteksi.index') }}" class="btn btn-warning w-100">
                        <i class="fas fa-search"></i> Mulai Deteksi
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection