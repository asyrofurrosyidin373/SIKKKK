@extends('layouts.app')

@section('title', 'Organisme Pengganggu Tanaman (OPT)')

@section('content')
<div class="container-fluid">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-bug text-danger me-2"></i>
                        Organisme Pengganggu Tanaman (OPT)
                    </h1>
                    <p class="text-muted">Database lengkap hama dan penyakit tanaman kedelai, kacang tanah, dan kacang hijau</p>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#searchModal">
                        <i class="fas fa-search me-1"></i> Pencarian Lanjutan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Hama
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_hama'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-bug fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Penyakit
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_penyakit'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-virus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Gejala
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_gejala'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Deteksi Hari Ini
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['recent_detections'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-search fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <form method="GET" action="{{ route('opt.index') }}" id="filterForm">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="jenis" class="form-label">Jenis OPT</label>
                                <select name="jenis" id="jenis" class="form-select">
                                    <option value="">Semua Jenis</option>
                                    <option value="Hama" {{ request('jenis') == 'Hama' ? 'selected' : '' }}>Hama</option>
                                    <option value="Penyakit" {{ request('jenis') == 'Penyakit' ? 'selected' : '' }}>Penyakit</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="tanaman" class="form-label">Jenis Tanaman</label>
                                <select name="tanaman" id="tanaman" class="form-select">
                                    <option value="">Semua Tanaman</option>
                                    <option value="Kedelai" {{ request('tanaman') == 'Kedelai' ? 'selected' : '' }}>Kedelai</option>
                                    <option value="Kacang Tanah" {{ request('tanaman') == 'Kacang Tanah' ? 'selected' : '' }}>Kacang Tanah</option>
                                    <option value="Kacang Hijau" {{ request('tanaman') == 'Kacang Hijau' ? 'selected' : '' }}>Kacang Hijau</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="search" class="form-label">Pencarian</label>
                                <input type="text" name="search" id="search" class="form-control" 
                                       placeholder="Cari nama OPT atau gejala..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-filter me-1"></i> Filter
                                    </button>
                                </div>
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
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card shadow-sm h-100 opt-card" data-opt-id="{{ $opt->id }}">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">
                        <span class="badge bg-{{ $opt->terjangkit == 'Hama' ? 'danger' : 'warning' }} me-2">
                            {{ $opt->terjangkit }}
                        </span>
                        {{ $opt->nama_penyakit }}
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
                            <span class="badge bg-light text-dark me-1 mb-1">
                                <i class="{{ $gejala->icon }} me-1"></i>
                                {{ Str::limit($gejala->gejala, 20) }}
                            </span>
                            @endforeach
                            @if($opt->gejala->count() > 3)
                            <span class="badge bg-secondary">+{{ $opt->gejala->count() - 3 }} lainnya</span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            @if($opt->hasControlMethods())
                            <span class="badge bg-success">
                                <i class="fas fa-shield-alt me-1"></i>
                                Ada Pengendalian
                            </span>
                            @else
                            <span class="badge bg-secondary">
                                <i class="fas fa-question-circle me-1"></i>
                                Belum Ada Data
                            </span>
                            @endif
                        </div>
                        <small class="text-muted">
                            Prioritas: {{ $opt->priority }}
                        </small>
                    </div>
                </div>

                <div class="card-footer bg-transparent">
                    <div class="d-grid">
                        <a href="{{ route('opt.show', $opt->id) }}" class="btn btn-outline-primary btn-sm">
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
            <div class="d-flex justify-content-center">
                {{ $opts->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pencarian Lanjutan OPT</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="advancedSearchForm">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="advancedSearch" class="form-label">Kata Kunci</label>
                            <input type="text" id="advancedSearch" class="form-control" placeholder="Masukkan kata kunci...">
                        </div>
                        <div class="col-md-6">
                            <label for="severityFilter" class="form-label">Tingkat Keparahan Minimum</label>
                            <select id="severityFilter" class="form-select">
                                <option value="0">Semua Tingkat</option>
                                <option value="2">Ringan (2+)</option>
                                <option value="4">Sedang (4+)</option>
                                <option value="6">Parah (6+)</option>
                                <option value="8">Sangat Parah (8+)</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <label for="frequencyFilter" class="form-label">Frekuensi Minimum</label>
                            <select id="frequencyFilter" class="form-select">
                                <option value="0">Semua Frekuensi</option>
                                <option value="20">Rendah (20+)</option>
                                <option value="40">Sedang (40+)</option>
                                <option value="60">Tinggi (60+)</option>
                                <option value="80">Sangat Tinggi (80+)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="daerahFilter" class="form-label">Daerah Gejala</label>
                            <select id="daerahFilter" class="form-select">
                                <option value="">Semua Daerah</option>
                                <option value="Akar">Akar</option>
                                <option value="Batang">Batang</option>
                                <option value="Daun">Daun</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" onclick="performAdvancedSearch()">
                    <i class="fas fa-search me-1"></i> Cari
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.opt-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    cursor: pointer;
}

.opt-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.border-left-danger {
    border-left: 0.25rem solid #e74a3b !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

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

function performAdvancedSearch() {
    const searchTerm = document.getElementById('advancedSearch').value;
    const severity = document.getElementById('severityFilter').value;
    const frequency = document.getElementById('frequencyFilter').value;
    const daerah = document.getElementById('daerahFilter').value;

    // Build search URL
    const params = new URLSearchParams();
    if (searchTerm) params.append('search', searchTerm);
    if (severity) params.append('min_severity', severity);
    if (frequency) params.append('min_frequency', frequency);
    if (daerah) params.append('daerah', daerah);

    window.location.href = `/opt?${params.toString()}`;
}
</script>
@endpush