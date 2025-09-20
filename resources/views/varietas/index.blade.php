{{-- resources/views/varietas/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Database Varietas')

@section('content')
    <div class="container py-4">
        <!-- Header -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="text-center">
                    <h1 class="display-5 fw-bold text-success">Database Varietas Kacang-Kacangan</h1>
                    <h4 class="text-muted">Jelajahi koleksi lengkap varietas unggulan</h4>
                </div>
            </div>
        </div>

        <!-- Navigation Cards -->
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <img src="{{ asset('kackedelai.jpg') }}" alt="Gambar Kacang Kedelai"
                                class="img-fluid rounded mb-3" style="height: 200px; object-fit: cover; width: 100%;">
                        </div>
                        <h4 class="card-title text-success">Varietas Kedelai</h4>
                        <p class="card-text text-muted">Koleksi varietas kedelai unggulan dengan produktivitas tinggi dan
                            tahan hama.</p>
                        <a href="{{ route('varietas.kedelai') }}" class="mt-3 btn btn-outline-success w-100">
                            Jelajahi Kedelai
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <img src="{{ asset('kactanah.jpg') }}" alt="Gambar Kacang Tanah"
                                class="img-fluid rounded mb-3" style="height: 200px; object-fit: cover; width: 100%;">
                        </div>
                        <h4 class="card-title text-success">Varietas Kacang Tanah</h4>
                        <p class="card-text text-muted">Varietas kacang tanah dengan kandungan minyak tinggi dan adaptasi
                            luas.</p>
                        <a href="{{ route('varietas.kacang-tanah') }}" class="mt-3 btn btn-outline-success w-100">
                            Jelajahi Kacang Tanah
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <img src="{{ asset('kachijau.jpg') }}" alt="Gambar Kacang Hijau"
                                class="img-fluid rounded mb-3" style="height: 200px; object-fit: cover; width: 100%;">
                        </div>
                        <h4 class="card-title text-success">Varietas Kacang Hijau</h4>
                        <p class="card-text text-muted">Varietas kacang hijau dengan protein tinggi dan umur genjah.</p>
                        <a href="{{ route('varietas.kacang-hijau') }}" class="mt-3 btn btn-outline-success w-100">
                            </i>Jelajahi Kacang Hijau
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-4">
                                <h3 class="text-success">50+</h3>
                                <p class="mb-0">Varietas Kedelai</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="text-success">30+</h3>
                                <p class="mb-0">Varietas Kacang Tanah</p>
                            </div>
                            <div class="col-md-4">
                                <h3 class="text-success">25+</h3>
                                <p class="mb-0">Varietas Kacang Hijau</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
