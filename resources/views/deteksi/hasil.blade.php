{{-- resources/views/deteksi/hasil.blade.php --}}
@extends('layouts.app')

@section('title', 'Hasil Deteksi Hama dan Penyakit')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12 hasil-page">
            <style>
                :root { --green-700: #15803d; }
                .text-success, .text-primary, .text-info, .text-warning, .text-danger { color: var(--green-700) !important; }
                .badge.bg-success, .badge.bg-warning, .badge.bg-danger, .badge.bg-info, .badge.bg-light.text-dark { background-color: var(--green-700) !important; color: #fff !important; }
                .card-header h5, .h2 { color: var(--green-700) !important; }
                .alert, .alert-info, .alert-warning, .alert-danger, .alert-success { border-color: var(--green-700) !important; }
                .alert-info, .alert-warning, .alert-danger, .alert-success { background-color: #e6f4ea !important; color: #0f5132 !important; }
                .nav-tabs .nav-link { color: var(--green-700) !important; }
                .nav-tabs .nav-link.active { border-color: var(--green-700) !important; color: var(--green-700) !important; }
                /* Spacing normalization: use 16px rhythm */
                .hasil-page .mb-4 { margin-bottom: 16px !important; }
                .hasil-page .row { row-gap: 16px; }
                .hasil-page .card { margin-bottom: 16px; }
                .hasil-page .card-body > .mb-3, .hasil-page .card-body > .mb-2 { margin-bottom: 16px !important; }
                /* Tabs spacing */
                .hasil-page .nav-tabs { margin-bottom: 16px; }
                .modal .nav-tabs { margin-bottom: 16px; }
                .modal .tab-content { margin-top: 0; }
            </style>
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h2">Hasil Deteksi Hama & Penyakit</h1>
                </div>
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
                                        <span class="badge bg-danger">Hama</span>
                                    @elseif($result->terjangkit == 'Penyakit')
                                        <span class="badge bg-warning">Penyakit</span>
                                    @else
                                        <span class="badge bg-info">{{ ucfirst($result->terjangkit) }}</span>
                                    @endif
                                    
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
                                        <span class="text-muted">Tidak ada gambar</span>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- ID Penyakit -->
                            <div class="mb-2">
                                <small class="text-muted">ID: {{ $result->id_penyakit }}</small>
                            </div>
                            
                            <!-- Matched Symptoms -->
                            <div class="mb-3">
                                <h6 class="text-success">Gejala yang Cocok:</h6>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($result->matched_symptoms as $gejala)
                                        <span class="badge bg-success small">{{ $gejala->daerah }}: {{ $gejala->gejala }}</span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Bagian Tanaman Terserang -->
                            <div class="mb-3">
                                <h6 class="text-success">Bagian Tanaman:</h6>
                                <div class="d-flex flex-wrap gap-1">
                                    @php
                                        $bagianTanaman = $result->matched_symptoms->pluck('daerah')->unique();
                                    @endphp
                                    @foreach($bagianTanaman as $bagian)
                                        <span class="badge bg-light text-dark small">{{ $bagian }}</span>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Control Methods Available -->
                            <div class="mb-3">
                                <h6 class="text-success">Metode Pengendalian:</h6>
                                <div class="d-flex flex-wrap gap-1">
                                    @if($result->kultur_teknis)
                                        <span class="badge bg-success small">Kultur Teknis</span>
                                    @endif
                                    @if($result->fisik_mekanis)
                                        <span class="badge bg-success small">Fisik Mekanis</span>
                                    @endif
                                    @if($result->hayati)
                                        <span class="badge bg-success small">Hayati</span>
                                    @endif
                                    @if($result->kimiawi)
                                        <span class="badge bg-success small">Kimiawi</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent border-0">
                            <button class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#detailModal{{ $result->id_penyakit }}">
                                Lihat Detail & Pengendalian
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Detail Modal -->
                <div class="modal fade" id="detailModal{{ $result->id_penyakit }}" tabindex="-1">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
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
                                                <li>{{ $gejala->daerah }}: {{ $gejala->gejala }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    
                                    <div class="col-12">
                                        <ul class="nav nav-tabs" id="controlTabs{{ $result->id_penyakit }}" role="tablist">
                                            @if($result->kultur_teknis)
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="kultur-tab{{ $result->id_penyakit }}" data-bs-toggle="tab" data-bs-target="#kultur{{ $result->id_penyakit }}" type="button" role="tab" aria-controls="kultur{{ $result->id_penyakit }}" aria-selected="true">
                                                        Kultur Teknis
                                                    </button>
                                                </li>
                                            @endif
                                            @if($result->fisik_mekanis)
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{ !$result->kultur_teknis ? 'active' : '' }}" id="fisik-tab{{ $result->id_penyakit }}" data-bs-toggle="tab" data-bs-target="#fisik{{ $result->id_penyakit }}" type="button" role="tab" aria-controls="fisik{{ $result->id_penyakit }}" aria-selected="{{ !$result->kultur_teknis ? 'true' : 'false' }}">
                                                        Fisik Mekanis
                                                    </button>
                                                </li>
                                            @endif
                                            @if($result->hayati)
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{ !$result->kultur_teknis && !$result->fisik_mekanis ? 'active' : '' }}" id="hayati-tab{{ $result->id_penyakit }}" data-bs-toggle="tab" data-bs-target="#hayati{{ $result->id_penyakit }}" type="button" role="tab" aria-controls="hayati{{ $result->id_penyakit }}" aria-selected="{{ !$result->kultur_teknis && !$result->fisik_mekanis ? 'true' : 'false' }}">
                                                        Hayati
                                                    </button>
                                                </li>
                                            @endif
                                            @if($result->kimiawi)
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link {{ !$result->kultur_teknis && !$result->fisik_mekanis && !$result->hayati ? 'active' : '' }}" id="kimiawi-tab{{ $result->id_penyakit }}" data-bs-toggle="tab" data-bs-target="#kimiawi{{ $result->id_penyakit }}" type="button" role="tab" aria-controls="kimiawi{{ $result->id_penyakit }}" aria-selected="{{ !$result->kultur_teknis && !$result->fisik_mekanis && !$result->hayati ? 'true' : 'false' }}">
                                                        Kimiawi
                                                    </button>
                                                </li>
                                            @endif
                                        </ul>
                                        
                                        <div class="tab-content mt-3" id="controlTabContent{{ $result->id_penyakit }}">
                                            @if($result->kultur_teknis)
                                                <div class="tab-pane fade show active" id="kultur{{ $result->id_penyakit }}" role="tabpanel" aria-labelledby="kultur-tab{{ $result->id_penyakit }}">
                                                    <div class="alert alert-info">
                                                        <h6>Pengendalian Kultur Teknis</h6>
                                                        <div>{!! nl2br(e($result->kultur_teknis)) !!}</div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($result->fisik_mekanis)
                                                <div class="tab-pane fade {{ !$result->kultur_teknis ? 'show active' : '' }}" id="fisik{{ $result->id_penyakit }}" role="tabpanel" aria-labelledby="fisik-tab{{ $result->id_penyakit }}">
                                                    <div class="alert alert-warning">
                                                        <h6>Pengendalian Fisik Mekanis</h6>
                                                        <div>{!! nl2br(e($result->fisik_mekanis)) !!}</div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($result->hayati)
                                                <div class="tab-pane fade {{ !$result->kultur_teknis && !$result->fisik_mekanis ? 'show active' : '' }}" id="hayati{{ $result->id_penyakit }}" role="tabpanel" aria-labelledby="hayati-tab{{ $result->id_penyakit }}">
                                                    <div class="alert alert-success">
                                                        <h6>Pengendalian Hayati</h6>
                                                        <div>{!! nl2br(e($result->hayati)) !!}</div>
                                                    </div>
                                                </div>
                                            @endif
                                            
                                            @if($result->kimiawi)
                                                <div class="tab-pane fade {{ !$result->kultur_teknis && !$result->fisik_mekanis && !$result->hayati ? 'show active' : '' }}" id="kimiawi{{ $result->id_penyakit }}" role="tabpanel" aria-labelledby="kimiawi-tab{{ $result->id_penyakit }}">
                                                    <div class="alert alert-danger">
                                                        <h6>Pengendalian Kimiawi</h6>
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
                            <div class="modal-footer d-none"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        
        
    @else
        <!-- No Results -->
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <h4 class="text-muted">Tidak Ada Hasil yang Cocok</h4>
                    <p class="text-muted mb-4">Gejala yang dipilih tidak cocok dengan database hama dan penyakit kedelai</p>
                    <div class="d-flex justify-content-center gap-3"></div>
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