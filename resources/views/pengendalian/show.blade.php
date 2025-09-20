@extends('layouts.app')

@section('content')
    <div class="container">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-end mb-4 mt-4">
            <div>
                <h1 class="mb-2">
                    Pengendalian {{ $pengendalian->jenis }}
                </h1>
                <span class="badge bg-success mt-3 fs-6">{{ $pengendalian->jenis }}</span>
            </div>
            <a href="{{ route('pengendalian.index') }}" class="btn btn-outline-success">
                <i class="fas fa-arrow-left me-1"></i>
                Kembali ke Daftar
            </a>
        </div>

        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <!-- Deskripsi -->
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle text-success me-2"></i>
                            Deskripsi Pengendalian
                        </h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-0" style="text-align: justify; line-height: 1.6;">
                            {{ $pengendalian->deskripsi }}
                        </p>
                    </div>
                </div>

                <!-- Organisme Terkait -->
                @if ($pengendalian->organisme->isNotEmpty())
                    <div class="card mb-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">
                                <i class="fas fa-bug text-success me-2"></i>
                                Organisme Pengganggu Terkait
                                <span class="badge bg-secondary ms-2">{{ $pengendalian->organisme->count() }}</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($pengendalian->organisme as $organisme)
                                    <div class="col-md-6 mb-3">
                                        <div class="d-flex align-items-center p-3 border rounded">
                                            <div class="flex-shrink-0">
                                                @if($organisme->gambar)
                                                    <img src="{{ asset('storage/' . $organisme->gambar) }}" 
                                                         alt="{{ $organisme->nama_opt }}" 
                                                         class="rounded" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-bug text-muted fa-2x"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1 ms-3">
                                                <h6 class="mb-1">
                                                    <a href="{{ route('opt.show', $organisme->id) }}" 
                                                       class="text-decoration-none">
                                                        {{ $organisme->nama_opt }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted">{{ $organisme->jenis }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Info Summary -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fas fa-chart-bar me-2"></i>
                            Ringkasan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="border-end">
                                    <h4 class="text-success mb-1">{{ $pengendalian->organisme->count() }}</h4>
                                    <small class="text-muted">Organisme</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <h4 class="text-success mb-1">{{ $pengendalian->insektisida->count() }}</h4>
                                <small class="text-muted">Insektisida</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Insektisida -->
                @if ($pengendalian->insektisida->isNotEmpty())
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">
                                <i class="fas fa-spray-can text-success me-2"></i>
                                Insektisida yang Dianjurkan
                                <span class="badge bg-secondary ms-2">{{ $pengendalian->insektisida->count() }}</span>
                            </h6>
                        </div>
                        <div class="card-body">
                            @foreach ($pengendalian->insektisida as $insektisida)
                                <div class="border rounded p-3 mb-3">
                                    <h6 class="text-primary mb-2">{{ $insektisida->nama_insektisida }}</h6>
                                    
                                    @if($insektisida->bahan_aktif)
                                        <p class="mb-2">
                                            <strong>Bahan Aktif:</strong> 
                                            <span class="text-muted">{{ $insektisida->bahan_aktif }}</span>
                                        </p>
                                    @endif
                                    
                                    @if($insektisida->hama_sasaran)
                                        <p class="mb-2">
                                            <strong>Target Hama:</strong> 
                                            <span class="text-muted">{{ $insektisida->hama_sasaran }}</span>
                                        </p>
                                    @endif
                                    
                                    @if($insektisida->dosis)
                                        <p class="mb-2">
                                            <strong>Dosis:</strong> 
                                            <span class="text-success">{{ $insektisida->dosis }}</span>
                                        </p>
                                    @endif
                                    
                                    @if($insektisida->cara_aplikasi)
                                        <p class="mb-0">
                                            <strong>Cara Aplikasi:</strong> 
                                            <span class="text-muted">{{ $insektisida->cara_aplikasi }}</span>
                                        </p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if ($pengendalian->organisme->isEmpty() && $pengendalian->insektisida->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Belum ada data organisme atau insektisida terkait untuk metode pengendalian ini.
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .card {
            border: none;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        
        .card-header {
            border-bottom: none;
            font-weight: 600;
        }
        
        .border {
            transition: all 0.2s;
        }
        
        .border:hover {
            border-color: #0d6efd !important;
            box-shadow: 0 2px 4px rgba(13,110,253,0.15);
        }
        
        .text-muted {
            font-size: 0.9rem;
        }
        
        .badge {
            font-size: 0.75rem;
        }
    </style>
@endsection