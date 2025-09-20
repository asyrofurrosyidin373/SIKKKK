{{-- resources/views/deteksi/hasil.blade.php --}}
@extends('layouts.app')

@section('title', 'Hasil Deteksi Hama dan Penyakit')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Beranda</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('deteksi.index') }}">Deteksi</a></li>
                    <li class="breadcrumb-item active">Hasil</li>
                </ol>
            </nav>
            
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2 text-success"><i class="fas fa-search me-2"></i>Hasil Deteksi Hama & Penyakit</h1>
                    <p class="text-muted">Berdasarkan gejala yang dipilih pada tanaman kedelai</p>
                </div>
                <a href="{{ route('deteksi.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-redo me-2"></i>Deteksi Ulang
                </a>
            </div>
        </div>
    </div>
    
    @if($results->count() > 0)
        <!-- Results -->
        <div class="row">
            @foreach($results as $index => $result)
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $result->nama_penyakit }}</h5>
                                <div>
                                    @if($result->terjangkit == 'Hama')
                                        <span class="badge bg-danger"><i class="fas fa-bug me-1"></i>Hama</span>
                                    @elseif($result->terjangkit == 'Penyakit')
                                        <span class="badge bg-warning"><i class="fas fa-virus me-1"></i>Penyakit</span>
                                    @else
                                        <span class="badge bg-info">{{ ucfirst($result->terjangkit) }}</span>
                                    @endif
                                    
                                    <!-- Confidence Score -->
                                    @php
                                        $confidence = round($result->confidence_score);
                                        $badgeClass = $confidence >= 80 ? 'bg-success' : ($confidence >= 60 ? 'bg-warning' : 'bg-secondary');
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $confidence }}% cocok</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            @if($result->gambar)
                                <div class="text-center mb-3">
                                    <img src="{{ asset('storage/' . $result->gambar) }}" 
                                         class="img-fluid rounded" alt="{{ $result->nama_penyakit }}"
                                         style="height: 150px; object-fit: cover;">
                                </div>
                            @else
                                <div class="text-center mb-3">
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded" style="height: 150px;">
                                        @if($result->terjangkit == 'Hama')
                                            <i class="fas fa-bug fa-3x text-danger"></i>
                                        @else
                                            <i class="fas fa-virus fa-3x text-warning"></i>
                                        @endif
                                    </div>
                                </div>
                            @endif
                            
                            <!-- ID Penyakit -->
                            <div class="mb-2">
                                <small class="text-muted">ID: {{ $result->id_penyakit }}</small>
                            </div>
                            
                            <!-- Matched Symptoms -->
                            <div class="mb-3">
                                <h6 class="text-primary">Gejala yang Cocok:</h6>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($result->matched_symptoms as $gejala)
                                        <span class="badge bg-success small">{{ $gejala->daerah }}: {{ $gejala->gejala }}</span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Bagian Tanaman Terserang -->
                            <div class="mb-3">
                                <h6 class="text-info">Bagian Tanaman:</h6>
                                <div class="d-flex flex-wrap gap-1">
                                    @php
                                        $bagianTanaman = $result->matched_symptoms->pluck('daerah')->unique();
                                    @endphp
                                    @foreach($bagianTanaman as $bagian)
                                        <span class="badge bg-light text-dark small">
                                            @if($bagian == 'Akar')
                                                <i class="fas fa-seedling me-1"></i>
                                            @elseif($bagian == 'Batang')
                                                <i class="fas fa-tree me-1"></i>
                                            @elseif($bagian == 'Daun')
                                                <i class="fas fa-leaf me-1"></i>
                                            @endif
                                            {{ $bagian }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Control Methods Available -->
                            <div class="mb-3">
                                <h6 class="text-success">Metode Pengendalian:</h6>
                                <div class="d-flex flex-wrap gap-1">
                                    @if($result->kultur_teknis)
                                        <span class="badge bg-outline-success small">
                                            <i class="fas fa-tools me-1"></i>Kultur Teknis
                                        </span>
                                    @endif
                                    @if($result->fisik_mekanis)
                                        <span class="badge bg-outline-info small">
                                            <i class="fas fa-hand-paper me-1"></i>Fisik Mekanis
                                        </span>
                                    @endif
                                    @if($result->hayati)
                                        <span class="badge bg-outline-warning small">
                                            <i class="fas fa-leaf me-1"></i>Hayati
                                        </span>
                                    @endif
                                    @if($result->kimiawi)
                                        <span class="badge bg-outline-danger small">
                                            <i class="fas fa-flask me-1"></i>Kimiawi
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent border-0">
                            <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#detailModal{{ $result->id_penyakit }}">
                                <i class="fas fa-eye me-2"></i>Lihat Detail & Pengendalian
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Detail Modal -->
                <div class="modal fade" id="detailModal{{ $result->id_penyakit }}" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $result->nama_penyakit }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <h6 class="text-primary">Informasi Umum</h6>
                                        <p><strong>Jenis:</strong> {{ $result->terjangkit }}</p>
                                        <p><strong>Tanaman:</strong> {{ $result->jenis_tanaman }}</p>
                                        <p><strong>ID:</strong> {{ $result->id_penyakit }}</p>
                                    </div>
                                    
                                    <div class="col-12 mb-3">
                                        <h6 class="text-primary">Gejala</h6>
                                        <ul class="list-unstyled">
                                            @foreach($result->matched_symptoms as $gejala)
                                                <li><i class="fas fa-check text-success me-2"></i>{{ $gejala->daerah }}: {{ $gejala->gejala }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    
                                    <div class="col-12">
                                        <ul class="nav nav-tabs" id="controlTabs{{ $result->id_penyakit }}" role="tablist">
                                            @if($result->kultur_teknis)
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="kultur-tab{{ $result->id_penyakit }}" data-bs-toggle="tab" data-bs-target="#kultur{{ $result->id_penyakit }}" type="button">
                                                        Kultur Teknis
                                                    </button>
                                                </li>
                                            @endif
                                            @if($result->fisik_mekanis)
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{ !$result->kultur_teknis ? 'active' : '' }}" id="fisik-tab{{ $result->id_penyakit }}" data-bs-toggle="tab" data-bs-target="#fisik{{ $result->id_penyakit }}" type="button">
                                                        Fisik Mekanis
                                                    </button>
                                                </li>
                                            @endif
                                            @if($result->hayati)
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{ !$result->kultur_teknis && !$result->fisik_mekanis ? 'active' : '' }}" id="hayati-tab{{ $result->id_penyakit }}" data-bs-toggle="tab" data-bs-target="#hayati{{ $result->id_penyakit }}" type="button">
                                                        Hayati
                                                    </button>
                                                </li>
                                            @endif
                                            @if($result->kimiawi)
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{ !$result->kultur_teknis && !$result->fisik_mekanis && !$result->hayati ? 'active' : '' }}" id="kimiawi-tab{{ $result->id_penyakit }}" data-bs-toggle="tab" data-bs-target="#kimiawi{{ $result->id_penyakit }}" type="button">
                                                        Kimiawi
                                                    </button>
                                                </li>
                                            @endif
                                        </ul>
                                        
                                        <div class="tab-content mt-3" id="controlTabContent{{ $result->id_penyakit }}">
                                            @if($result->kultur_teknis)
                                                <div class="tab-pane fade show active" id="kultur{{ $result->id_penyakit }}" role="tabpanel">
                                                    <div class="alert alert-info">
                                                        <h6><i class="fas fa-tools me-2"></i>Pengendalian Kultur Teknis</h6>
                                                        <div>{!! nl2br(e($result->kultur_teknis)) !!}</div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($result->fisik_mekanis)
                                                <div class="tab-pane fade {{ !$result->kultur_teknis ? 'show active' : '' }}" id="fisik{{ $result->id_penyakit }}" role="tabpanel">
                                                    <div class="alert alert-warning">
                                                        <h6><i class="fas fa-hand-paper me-2"></i>Pengendalian Fisik Mekanis</h6>
                                                        <div>{!! nl2br(e($result->fisik_mekanis)) !!}</div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($result->hayati)
                                                <div class="tab-pane fade {{ !$result->kultur_teknis && !$result->fisik_mekanis ? 'show active' : '' }}" id="hayati{{ $result->id_penyakit }}" role="tabpanel">
                                                    <div class="alert alert-success">
                                                        <h6><i class="fas fa-leaf me-2"></i>Pengendalian Hayati</h6>
                                                        <div>{!! nl2br(e($result->hayati)) !!}</div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($result->kimiawi)
                                                <div class="tab-pane fade {{ !$result->kultur_teknis && !$result->fisik_mekanis && !$result->hayati ? 'show active' : '' }}" id="kimiawi{{ $result->id_penyakit }}" role="tabpanel">
                                                    <div class="alert alert-danger">
                                                        <h6><i class="fas fa-flask me-2"></i>Pengendalian Kimiawi</h6>
                                                        <div>{!! nl2br(e($result->kimiawi)) !!}</div>
                                                    </div>
                                                    
                                                    @if(isset($result->insektisida) && $result->insektisida->count() > 0)
                                                        <div class="mt-3">
                                                            <h6>Rekomendasi Insektisida:</h6>
                                                            <div class="table-responsive">
                                                                <table class="table table-sm">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Nama</th>
                                                                            <th>Bahan Aktif</th>
                                                                            <th>Sasaran</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @foreach($result->insektisida as $insektisida)
                                                                            <tr>
                                                                                <td>{{ $insektisida->nama_insektisida }}</td>
                                                                                <td>{{ $insektisida->bahan_aktif }}</td>
                                                                                <td>{{ $insektisida->hama_sasaran }}</td>
                                                                            </tr>
                                                                        @endforeach
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                <button type="button" class="btn btn-success" onclick="printResult('{{ $result->id_penyakit }}')">
                                    <i class="fas fa-print me-2"></i>Print
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Additional Actions -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card bg-light border-0">
                    <div class="card-body text-center">
                        <h5 class="text-primary mb-3">Langkah Selanjutnya</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="fas fa-seedling fa-2x text-success mb-2"></i>
                                    <h6>Pilih Varietas Tahan</h6>
                                    <a href="{{ route('varietas.index') }}" class="btn btn-success btn-sm">
                                        Lihat Varietas
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="fas fa-phone fa-2x text-primary mb-2"></i>
                                    <h6>Konsultasi Ahli</h6>
                                    <button class="btn btn-primary btn-sm">
                                        Hubungi Penyuluh
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="fas fa-share fa-2x text-info mb-2"></i>
                                    <h6>Bagikan Hasil</h6>
                                    <button class="btn btn-info btn-sm" onclick="shareResults()">
                                        Bagikan
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <i class="fas fa-download fa-2x text-warning mb-2"></i>
                                    <h6>Unduh Laporan</h6>
                                    <button class="btn btn-warning btn-sm" onclick="downloadReport()">
                                        Download PDF
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    @else
        <!-- No Results -->
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                    <h4 class="text-muted">Tidak Ada Hasil yang Cocok</h4>
                    <p class="text-muted mb-4">Gejala yang dipilih tidak cocok dengan database hama dan penyakit kedelai</p>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('deteksi.index') }}" class="btn btn-primary">
                            <i class="fas fa-redo me-2"></i>Coba Lagi
                        </a>
                        <a href="{{ route('hama.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-bug me-2"></i>Lihat Semua Hama & Penyakit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
function shareResults() {
    const resultsText = 'Hasil deteksi hama/penyakit kedelai: ' + 
                       @json($results->pluck('nama_penyakit')->implode(', '));
    
    if (navigator.share) {
        navigator.share({
            title: 'Hasil Deteksi Hama & Penyakit Kedelai',
            text: resultsText,
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href + '\n\n' + resultsText).then(function() {
            alert('Hasil berhasil disalin ke clipboard!');
        });
    }
}

function downloadReport() {
    // Generate PDF report
    window.print();
}

function printResult(idPenyakit) {
    const modal = document.getElementById('detailModal' + idPenyakit);
    const printContent = modal.querySelector('.modal-body').innerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Detail Hama/Penyakit</title>');
    printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<div class="container mt-4">');
    printWindow.document.write(printContent);
    printWindow.document.write('</div></body></html>');
    printWindow.document.close();
    printWindow.print();
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            if (alert.classList.contains('alert-info') || alert.classList.contains('alert-success')) {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }
        });
    }, 5000);
});
</script>
@endpush
@endsection