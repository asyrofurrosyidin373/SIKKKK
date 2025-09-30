@extends('layouts.app')

@section('title', 'Sistem Informasi Geografis Komoditas Kacang-kacangan')

@section('content')
    <div class="container home-page">
        <!-- Hero Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="text-white rounded-xl px-6 md:px-8 py-12 md:py-24"
                    style="background: linear-gradient(to right, rgba(25,135,84,0.95) 0%, rgba(25,135,84,0.85) 30%, rgba(25,135,84,0.0) 60%), url('{{ asset('kackedelai.jpg') }}'); background-size: cover; background-position: center right; background-repeat: no-repeat;">
                    <div class="row align-items-center h-[450px]">
                        <div class="col-lg-10">
                            <h1 class="text-4xl md:text-4xl font-semibold mb-3">
                                Sistem Informasi Aneka Kacang
                            </h1>
                            <p class="text-lg md:text-xl mb-4">
                                Aplikasi SIG dan sistem pakar untuk komoditas aneka kacang: memetakan sebaran, mendeteksi
                                hama & penyakit, serta memberi rujukan varietas dan pengendalian.
                            </p>
                            <div class="d-flex gap-3">
                                <a href="{{ route('peta') }}"
                                    class="inline-flex items-center rounded-lg bg-white text-success border-2 border-white px-4 py-2 text-lg font-medium hover:bg-gray-100">
                                    Lihat Peta
                                </a>
                                <a href="{{ route('varietas.index') }}"
                                    class="inline-flex items-center rounded-lg bg-white text-success border-2 border-white px-4 py-2 text-lg font-medium hover:bg-gray-100">
                                    Lihat varietas
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="text-white rounded-xl px-6 md:px-8 py-12 md:py-12 bg-success">
                    <div class="row align-items-center">
                        <div class="col-lg-8">
                            <h1 class="text-3xl md:text-4xl font-semibold mb-3">
                                Sistem Pakar Deteksi Hama dan Penyakit
                            </h1>
                            <p class="text-lg md:text-xl mb-4">
                                Platform canggih untuk mendeteksi, mengidentifikasi, dan mengendalikan hama serta penyakit
                                pada tanaman kedelai, kacang tanah, dan kacang hijau.
                            </p>
                        </div>
                        <div class="col-lg-4 text-center">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="bg-white text-gray-800 rounded-lg p-3">
                                        <h3 class="mb-1">{{ $stats['total_diseases'] ?? 0 }}</h3>
                                        <small>Hama & Penyakit</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-white text-gray-800 rounded-lg p-3">
                                        <h3 class="mb-1">{{ $stats['total_symptoms'] ?? 0 }}</h3>
                                        <small>Gejala Terdaftar</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-white text-gray-800 rounded-lg p-3">
                                        <h3 class="mb-1">{{ $stats['total_varieties'] ?? 0 }}</h3>
                                        <small>Varietas Tanaman</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-white text-gray-800 rounded-lg p-3">
                                        <h3 class="mb-1">{{ $stats['recent_detections'] ?? 0 }}</h3>
                                        <small>Deteksi Hari Ini</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="mb-0 text-center text-success text-xl">
                            Fitur Unggulan Sistem
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center mb-3">
                                <div class="feature-icon bg-success text-white rounded-circle mx-auto mb-3">
                                    <i class="fas fa-map fa-2x"></i>
                                </div>
                                <h4 class="mb-2">Peta Interaktif</h4>
                                <p class="text-muted">Peta interaktif untuk visualisasi sebaran komoditas dan informasi
                                    wilayah</p>
                            </div>
                            <div class="col-md-3 text-center mb-3">
                                <div class="feature-icon bg-success text-white rounded-circle mx-auto mb-3">
                                    <i class="fas fa-seedling fa-2x"></i>
                                </div>
                                <h4 class="mb-2">Varietas Tanaman Kacang</h4>
                                <p class="text-muted">Katalog varietas kedelai, kacang tanah, dan kacang hijau beserta
                                    detailnya</p>
                            </div>
                            <div class="col-md-3 text-center mb-3">
                                <div class="feature-icon bg-success text-white rounded-circle mx-auto mb-3">
                                    <i class="fas fa-search fa-2x"></i>
                                </div>
                                <h4 class="mb-2">Deteksi Hama & Penyakit</h4>
                                <p class="text-muted">Deteksi hama dan penyakit dari gejala yang Anda pilih</p>
                            </div>
                            <div class="col-md-3 text-center mb-3">
                                <div class="feature-icon bg-success text-white rounded-circle mx-auto mb-3">
                                    <i class="fas fa-bug fa-2x"></i>
                                </div>
                                <h4 class="mb-2">Database OPT</h4>
                                <p class="text-muted">Basis data OPT lengkap: hama, penyakit, gejala, dan pengendalian</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .home-page .row {
            row-gap: 16px;
        }

        .home-page .card {
            margin-bottom: 16px;
        }

        .home-page .card-body>*:not(:last-child) {
            margin-bottom: 16px;
        }

        .hero-section {
            background: #198754;
            /* Bootstrap success */
            min-height: 300px;
        }

        .border-left-green {
            border-left: 0.25rem solid #198754 !important;
            /* Bootstrap success */
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .activity-icon {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-card {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hero-stats .stat-card h3 {
            font-size: 1.5rem;
            font-weight: bold;
        }

        /* Custom white buttons (on green hero background) */
        .btn-white {
            background-color: #ffffff;
            color: #198754;
            border: 2px solid #ffffff;
        }

        .btn-white:hover,
        .btn-white:focus {
            background-color: #f8f9fa;
            border-color: #f8f9fa;
            color: #146c43;
        }

        /* Keep outline-white name but make background white as requested */
        .btn-outline-white {
            background-color: #ffffff;
            color: #198754;
            border: 2px solid #ffffff;
        }

        .btn-outline-white:hover,
        .btn-outline-white:focus {
            background-color: #f8f9fa;
            border-color: #f8f9fa;
            color: #146c43;
        }

        @media (max-width: 768px) {
            .hero-section {
                text-align: center;
            }

            .hero-section .display-4 {
                font-size: 2rem;
            }

            .hero-section .lead {
                font-size: 1rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-refresh stats every 5 minutes
            setInterval(function() {
                fetch('/api/dashboard/stats')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update stats if needed
                            console.log('Stats updated:', data.data);
                        }
                    })
                    .catch(error => {
                        console.error('Error updating stats:', error);
                    });
            }, 300000); // 5 minutes
        });
    </script>
@endpush
