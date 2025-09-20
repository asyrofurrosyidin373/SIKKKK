@extends('layouts.app')

@section('title', $opt->nama_opt)

@section('content')
<div class="container py-4">  
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-3">
                <h1 class="h2 mb-0 me-3">{{ $opt->nama_opt }}</h1>
                @if($opt->jenis == 'hama')
                    <span class="badge bg-danger fs-6">Hama</span>
                @elseif($opt->jenis == 'penyakit')
                    <span class="badge bg-warning fs-6">Penyakit</span>
                @else
                    <span class="badge bg-info fs-6">{{ ucfirst($opt->jenis) }}</span>
                @endif
            </div>
        </div>
        <div class="col-lg-4 text-end">
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Cetak
            </button>
            <a href="{{ route('deteksi.index') }}" class="btn btn-success">
                <i class="fas fa-search me-2"></i>Deteksi Sekarang
            </a>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="row">
        <!-- Image & Basic Info -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                @if($opt->gambar)
                    <img src="{{ asset('storage/' . $opt->gambar) }}" 
                         class="card-img-top" alt="{{ $opt->nama_opt }}"
                         style="height: 300px; object-fit: cover;">
                @else
                    <div class="bg-danger d-flex align-items-center justify-content-center text-white" style="height: 300px;">
                        <i class="fas fa-bug fa-4x"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <h6 class="text-danger mb-3">Klasifikasi</h6>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Jenis:</span>
                        <strong>{{ ucfirst($opt->jenis) }}</strong>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Detailed Information -->
        <div class="col-lg-8">
            <!-- Symptoms -->
            @if($opt->gejala && $opt->gejala->count() > 0)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Gejala Serangan</h5>
                    </div>
                    <div class="card-body">
                        @foreach($opt->gejala->groupBy('bagian_tanaman') as $bagian => $gejalaGroup)
                            <div class="mb-4">
                                <h6 class="text-primary">{{ ucfirst($bagian) }}</h6>
                                <div class="row">
                                    @foreach($gejalaGroup as $gejala)
                                        <div class="col-md-6 mb-3">
                                            <div class="border-start border-primary border-3 ps-3">
                                                <p class="mb-0">{{ $gejala->deskripsi }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Control Methods -->
            @if($opt->pengendalian && $opt->pengendalian->count() > 0)
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Metode Pengendalian</h5>
                    </div>
                    <div class="card-body">
                        @foreach($opt->pengendalian->groupBy('jenis') as $jenis => $pengendalianGroup)
                            <div class="mb-4">
                                <h6 class="text-success">{{ ucfirst($jenis) }}</h6>
                                @foreach($pengendalianGroup as $pengendalian)
                                    <div class="mb-3 p-3 bg-light rounded">
                                        <p class="mb-0">{{ $pengendalian->deskripsi }}</p>
                                        
                                        @if($pengendalian->insektisida && $pengendalian->insektisida->count() > 0)
                                            <div class="mt-2">
                                                <small class="text-muted">Insektisida yang direkomendasikan:</small>
                                                <div class="d-flex flex-wrap gap-2 mt-1">
                                                    @foreach($pengendalian->insektisida as $insektisida)
                                                        <span class="badge bg-secondary">
                                                            {{ $insektisida->nama }}
                                                            @if($insektisida->bahan_aktif)
                                                                ({{ $insektisida->bahan_aktif }})
                                                            @endif
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Resistant Varieties -->
            @php
                $resistantVarietas = collect();
                if($opt->varietasKedelai) $resistantVarietas = $resistantVarietas->merge($opt->varietasKedelai->map(function($v) { $v->type = 'kedelai'; return $v; }));
                if($opt->varietasKacangTanah) $resistantVarietas = $resistantVarietas->merge($opt->varietasKacangTanah->map(function($v) { $v->type = 'kacang-tanah'; return $v; }));
                if($opt->varietasKacangHijau) $resistantVarietas = $resistantVarietas->merge($opt->varietasKacangHijau->map(function($v) { $v->type = 'kacang-hijau'; return $v; }));
            @endphp
            
            @if($resistantVarietas->count() > 0)
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="fas fa-seedling me-2"></i>Varietas Tahan</h5>
                    </div>
                    <div class="card-body">
                        <p class="text-muted mb-3">Varietas yang memiliki ketahanan terhadap {{ $opt->nama_opt }}:</p>
                        <div class="row">
                            @foreach($resistantVarietas as $varietas)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center p-3 bg-light rounded">
                                        <div class="me-3">
                                            @if($varietas->type == 'kedelai')
                                                <i class="fas fa-seedling text-success fa-2x"></i>
                                            @elseif($varietas->type == 'kacang-tanah')
                                                <i class="fas fa-seedling text-warning fa-2x"></i>
                                            @else
                                                <i class="fas fa-seedling text-info fa-2x"></i>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $varietas->nama_varietas }}</h6>
                                            <small class="text-muted">{{ ucfirst(str_replace('-', ' ', $varietas->type)) }}</small>
                                        </div>
                                        <div>
                                            <a href="{{ route('varietas.show', ['type' => $varietas->type, 'id' => $varietas->id]) }}" 
                                               class="btn btn-sm btn-outline-primary">
                                                Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection