@extends('layouts.app')

@section('title', $opt->nama_penyakit . ' - Detail OPT')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-end">
                <div>
                    <h1 class="h3 mb-2 text-gray-800">
                        <span class="badge bg-success me-3 fs-6">
                            {{ $opt->terjangkit }}
                        </span>
                        {{ $opt->nama_penyakit }}
                    </h1>
                    <p class="text-muted mb-0">
                        <i class="fas fa-seedling me-1"></i>
                        {{ $opt->jenis_tanaman }} • 
                        <i class="fas fa-chart-line me-1"></i>
                        Prioritas: {{ $opt->priority }} • 
                        <i class="fas fa-search me-1"></i>
                        {{ $detectionStats }} deteksi
                    </p>
                </div>
                <div>
                    <button class="btn btn-outline-success " onclick="window.print()">
                        <i class="fas fa-print me-1"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Image and Description -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Deskripsi
                    </h5>
                </div>
                <div class="card-body">
                    @if($opt->gambar)
                    <div class="text-center mb-4">
                        <img src="{{ $opt->gambar_url }}" alt="{{ $opt->nama_penyakit }}" 
                             class="img-fluid rounded shadow" style="max-height: 300px;">
                    </div>
                    @endif
                    
                    @if($opt->deskripsi)
                    <div class="mb-4">
                        <h6>Deskripsi Umum</h6>
                        <p class="text-muted">{{ $opt->deskripsi }}</p>
                    </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Dasar</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>ID OPT:</strong></td>
                                    <td>{{ $opt->id_penyakit }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Jenis:</strong></td>
                                    <td>
                                        <span class="badge bg-success">
                                            {{ $opt->terjangkit }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Tanaman:</strong></td>
                                    <td>{{ $opt->jenis_tanaman }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Prioritas:</strong></td>
                                    <td>{{ $opt->priority }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Statistik</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Total Gejala:</strong></td>
                                    <td>{{ $opt->gejala->count() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Gejala Parah:</strong></td>
                                    <td>{{ $opt->gejala->where('severity_score', '>=', 6)->count() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Gejala Sering:</strong></td>
                                    <td>{{ $opt->gejala->where('frequency', '>=', 60)->count() }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Deteksi:</strong></td>
                                    <td>{{ $detectionStats }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gejala -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Gejala yang Terdeteksi
                    </h5>
                </div>
                <div class="card-body">
                    @if($opt->gejala->count() > 0)
                        @foreach($opt->gejala->groupBy('daerah') as $daerah => $gejalaDaerah)
                        <div class="mb-4">
                            <h6 class="text-success">
                                <i class="{{ $gejalaDaerah->first()->icon }} me-2"></i>
                                {{ $daerah }}
                            </h6>
                            <div class="row">
                                @foreach($gejalaDaerah as $gejala)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div class="flex-grow-1">
                                                    <p class="mb-1">{{ $gejala->gejala }}</p>
                                                    <small class="text-muted">
                                                        ID: {{ $gejala->id_gejala }}
                                                    </small>
                                                </div>
                                                <div class="text-end">
                                                    <span class="badge bg-success mb-1">
                                                        {{ $gejala->severity_level }}
                                                    </span>
                                                    <br>
                                                    <small class="text-muted">
                                                        Frekuensi: {{ $gejala->frequency }}%
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-circle fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Belum ada data gejala untuk OPT ini</p>
                    </div>
                    @endif
                </div>
            </div>

            
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Metode Pengendalian
                    </h5>
                </div>
                <div class="card-body">
                    @php
                        $pengendalian = $opt->getControlRecommendations();
                    @endphp
                    @if(count($pengendalian) > 0)
                        @foreach($pengendalian as $method)
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge bg-success me-2">{{ $method['priority'] }}</span>
                                    <h6 class="mb-0 text-capitalize">{{ str_replace('_', ' ', $method['type']) }}</h6>
                                </div>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0">{{ $method['method'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-info-circle fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">Belum ada data pengendalian untuk OPT ini</p>
                        </div>
                    @endif
                </div>
            </div>
            <!-- Related OPTs -->
            @if($relatedOpts->count() > 0)
            <div class="card shadow mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-link me-2"></i>
                        OPT Terkait
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($relatedOpts as $relatedOpt)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <h6 class="mb-1">
                                <a href="{{ route('opt.show', $relatedOpt->id) }}" class="text-decoration-none">
                                    {{ $relatedOpt->nama_penyakit }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                {{ $relatedOpt->gejala_count }} gejala sama
                            </small>
                        </div>
                        <span class="badge bg-success">
                            {{ $relatedOpt->terjangkit }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Gejala Modal -->
<div class="modal fade" id="gejalaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Semua Gejala {{ $opt->nama_penyakit }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="gejalaContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pengendalian Modal -->
<div class="modal fade" id="pengendalianModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Metode Pengendalian {{ $opt->nama_penyakit }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="pengendalianContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .btn, .card-header, .breadcrumb, .modal {
        display: none !important;
    }
    
    .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
    }
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
</style>
@endpush

@push('scripts')
<script>
function showGejalaModal() {
    fetch(`/api/opt/{{ $opt->id }}/gejala`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let content = '';
                Object.keys(data.data).forEach(daerah => {
                    content += `<h6 class="text-primary mb-3"><i class="fas fa-leaf me-2"></i>${daerah}</h6>`;
                    data.data[daerah].forEach(gejala => {
                        content += `
                            <div class="card mb-2">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="mb-1">${gejala.gejala}</p>
                                            <small class="text-muted">ID: ${gejala.id_gejala}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-info">${gejala.severity_score}/10</span>
                                            <br>
                                            <small class="text-muted">${gejala.frequency}%</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
                });
                document.getElementById('gejalaContent').innerHTML = content;
                new bootstrap.Modal(document.getElementById('gejalaModal')).show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data gejala');
        });
}

function showPengendalianModal() {
    fetch(`/api/opt/{{ $opt->id }}/pengendalian`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                let content = '';
                data.data.forEach((method, index) => {
                    content += `
                        <div class="mb-4">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-primary me-2">${method.priority}</span>
                                <h6 class="mb-0 text-capitalize">${method.type.replace('_', ' ')}</h6>
                            </div>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-0">${method.method}</p>
                            </div>
                        </div>
                    `;
                });
                document.getElementById('pengendalianContent').innerHTML = content;
                new bootstrap.Modal(document.getElementById('pengendalianModal')).show();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data pengendalian');
        });
}
</script>
@endpush
