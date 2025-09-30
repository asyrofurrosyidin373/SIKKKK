@extends('layouts.app')

@section('title', 'Organisme Pengganggu Tanaman (OPT)')

@section('content')
<div class="container opt-page">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-success">Organisme Pengganggu Tanaman (OPT)</h1>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-green h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Hama
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_hama'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bug fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-green h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Penyakit
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_penyakit'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-virus fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-green h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Gejala
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_gejala'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-left-green h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Deteksi Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['recent_detections'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-search fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('opt.index') }}" id="filterForm">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="jenis" class="form-label">Jenis OPT</label>
                                <select name="jenis" id="jenis" class="form-select">
                                    <option value="">Semua Jenis</option>
                                    <option value="Hama" {{ request('jenis') == 'Hama' ? 'selected' : '' }}>Hama</option>
                                    <option value="Penyakit" {{ request('jenis') == 'Penyakit' ? 'selected' : '' }}>Penyakit</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="tanaman" class="form-label">Jenis Tanaman</label>
                                <select name="tanaman" id="tanaman" class="form-select">
                                    <option value="">Semua Tanaman</option>
                                    <option value="Kedelai" {{ request('tanaman') == 'Kedelai' ? 'selected' : '' }}>Kacang Kedelai</option>
                                    <option value="Kacang Tanah" {{ request('tanaman') == 'Kacang Tanah' ? 'selected' : '' }}>Kacang Tanah</option>
                                    <option value="Kacang Hijau" {{ request('tanaman') == 'Kacang Hijau' ? 'selected' : '' }}>Kacang Hijau</option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- OPT Cards -->
    <div class="row" id="optCards">
        @forelse($opts as $opt)
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card shadow-sm h-100 opt-card" data-opt-id="{{ $opt->id }}">
                <div class="card-header d-flex justify-content-between align-items-start bg-white">
                    @php
                        $meta = $opt->metadata ?? null;
                        $common = is_array($meta) ? ($meta['common_name'] ?? null) : (is_object($meta) ? ($meta->common_name ?? null) : null);
                        $latin  = is_array($meta) ? ($meta['latin_name'] ?? null)  : (is_object($meta) ? ($meta->latin_name ?? null)  : null);
                        $baseName = $opt->nama_penyakit ?: ($common ?: ($latin ?: (optional($opt->gejala->first())->gejala ?: 'Tanpa Nama')));
                        $displayName = ($latin && $latin !== $baseName) ? ($baseName . ' (' . $latin . ')') : $baseName;
                    @endphp
                    <h6 class="mb-0 text-dark" title="{{ $displayName }}">
                        <span class="badge bg-success me-2">
                            {{ $opt->terjangkit }}
                        </span>
                        {{ $displayName }}
                    </h6>
                    <small class="text-muted">{{ $opt->jenis_tanaman }}</small>
                </div>
                
                @if($opt->gambar)
                <div class="card-img-top-container" style="height: 200px; overflow: hidden;">
                    <img src="{{ $opt->gambar_url }}" class="card-img-top" alt="{{ $opt->nama_penyakit }}" 
                         style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                @endif

                <div class="card-body">
                    <p class="card-text text-muted small">
                        {{ Str::limit($opt->deskripsi, 100) }}
                    </p>
                    
                    <div class="mb-3">
                        <small class="text-muted">Gejala yang terdeteksi:</small>
                        <div class="mt-1">
                            @foreach($opt->gejala->take(3) as $gejala)
                            <span class="badge bg-light text-dark me-1 mb-1">{{ Str::limit($gejala->gejala, 20) }}</span>
                            @endforeach
                            @if($opt->gejala->count() > 3)
                            <span class="badge bg-success">+{{ $opt->gejala->count() - 3 }} lainnya</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($opt->hasControlMethods())
                            <span class="badge bg-success">Ada Pengendalian</span>
                            @else
                            <span class="badge bg-success">Belum Ada Data</span>
                            @endif
                        </div>
                        <small class="text-muted">
                            Prioritas: {{ $opt->priority }}
                        </small>
                    </div>
                </div>

                <div class="card-footer bg-transparent">
                    <div class="d-grid">
                        <a href="{{ route('opt.show', $opt->id) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-eye me-1"></i> Lihat Detail
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">Tidak ada OPT yang ditemukan</h4>
                <p class="text-muted">Coba ubah filter atau kata kunci pencarian Anda</p>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($opts->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center align-items-center gap-2 my-3">
                <a class="btn btn-success btn-sm {{ $opts->onFirstPage() ? 'disabled' : '' }}" href="{{ $opts->previousPageUrl() ?? '#' }}">
                    <i class="fas fa-chevron-left"></i>
                </a>
                <span class="text-success">Halaman {{ $opts->currentPage() }} dari {{ $opts->lastPage() }}</span>
                <a class="btn btn-success btn-sm {{ $opts->hasMorePages() ? '' : 'disabled' }}" href="{{ $opts->nextPageUrl() ?? '#' }}">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
.opt-page .row { row-gap: 16px; }
.opt-page .card { margin-bottom: 16px; }
.opt-card { cursor: pointer; }
.opt-card:hover { transform: none; box-shadow: none !important; }
.border-left-green { border-left: 0.25rem solid #15803d !important; }
.card-img-top-container {
    position: relative;
}

.card-img-top-container::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 30px;
    background: linear-gradient(transparent, rgba(0,0,0,0.1));
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit filter form when select changes
    document.getElementById('jenis').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    document.getElementById('tanaman').addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });

    // Add click event to OPT cards
    document.querySelectorAll('.opt-card').forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('a, button')) {
                const optId = this.dataset.optId;
                window.location.href = `/opt/${optId}`;
            }
        });
    });
});

</script>
@endpush