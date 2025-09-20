@extends('layouts.app')

@section('content')
<div class="container">
    <div class="jumbotron bg-primary text-white text-center py-5 rounded mb-4">
        <h1 class="display-4"><i class="fas fa-leaf"></i> Sistem Informasi Geografis Pertanian</h1>
        <p class="lead">Platform terpadu untuk informasi peta tematik, database varietas tanaman, dan deteksi hama penyakit</p>
        <hr class="my-4 bg-white">
        <p>Temukan informasi lengkap tentang komoditas unggulan di berbagai wilayah Indonesia</p>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-map fa-4x text-primary mb-3"></i>
                    <h5 class="card-title">Peta Tematik</h5>
                    <p class="card-text">Jelajahi peta interaktif untuk melihat sebaran komoditas unggulan.</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('map.index') }}" class="btn btn-primary w-100">
                        <i class="fas fa-map"></i> Buka Peta
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