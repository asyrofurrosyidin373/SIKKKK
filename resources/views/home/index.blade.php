@extends('layouts.app')

@section('title', 'Sistem Deteksi Hama dan Penyakit Tanaman')

@section('content')
<div class="container-fluid">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="hero-section bg-gradient-primary text-white rounded-3 p-5">
                <div class="row align-items-center">
                    <div class="col-lg-8">
                        <h1 class="display-4 fw-bold mb-3">
                            <i class="fas fa-seedling me-3"></i>
                            Sistem Deteksi Hama & Penyakit Tanaman
                        </h1>
                        <p class="lead mb-4">
                            Platform canggih untuk mendeteksi, mengidentifikasi, dan mengendalikan hama serta penyakit pada tanaman kedelai, kacang tanah, dan kacang hijau dengan teknologi AI.
                        </p>
                        <div class="d-flex gap-3">
                            <a href="{{ route('deteksi.index') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-search me-2"></i> Deteksi Sekarang
                            </a>
                            <a href="{{ route('opt.index') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-bug me-2"></i> Lihat Database OPT
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-4 text-center">
                        <div class="hero-stats">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="stat-card bg-white bg-opacity-10 rounded-3 p-3">
                                        <h3 class="mb-1">{{ $stats['total_diseases'] ?? 0 }}</h3>
                                        <small>Hama & Penyakit</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card bg-white bg-opacity-10 rounded-3 p-3">
                                        <h3 class="mb-1">{{ $stats['total_symptoms'] ?? 0 }}</h3>
                                        <small>Gejala Terdaftar</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card bg-white bg-opacity-10 rounded-3 p-3">
                                        <h3 class="mb-1">{{ $stats['total_varieties'] ?? 0 }}</h3>
                                        <small>Varietas Tanaman</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card bg-white bg-opacity-10 rounded-3 p-3">
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
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-5">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Wilayah
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_kecamatan'] ?? 0 }} Kecamatan
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-map-marker-alt fa-2x text-gray-300"></i>
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
                                Prioritas Tinggi
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['high_priority_diseases'] ?? 0 }} OPT
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                                Gejala Parah
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['severe_symptoms'] ?? 0 }} Gejala
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-heartbeat fa-2x text-gray-300"></i>
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
                                Akurasi Sistem
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">95%+</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-bolt me-2"></i>
                        Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-3">
                        <a href="{{ route('deteksi.index') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-search me-2"></i>
                            Deteksi Hama & Penyakit
                        </a>
                        <a href="{{ route('opt.index') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-bug me-2"></i>
                            Database OPT
                        </a>
                        <a href="{{ route('varietas.index') }}" class="btn btn-outline-success btn-lg">
                            <i class="fas fa-seedling me-2"></i>
                            Varietas Tanaman
                        </a>
                        <a href="{{ route('peta') }}" class="btn btn-outline-info btn-lg">
                            <i class="fas fa-map me-2"></i>
                            Peta Wilayah
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Aktivitas Terbaru
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentDetections && $recentDetections->count() > 0)
                        <div class="activity-feed">
                            @foreach($recentDetections as $detection)
                            <div class="activity-item d-flex align-items-center mb-3">
                                <div class="activity-icon bg-primary text-white rounded-circle me-3">
                                    <i class="fas fa-search"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="activity-text">
                                        <strong>Deteksi Baru</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $detection->detected_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                                <div class="activity-badge">
                                    <span class="badge bg-{{ $detection->confidence_color }}">
                                        {{ $detection->confidence_score }}%
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('deteksi.index') }}" class="btn btn-sm btn-outline-info">
                                Lihat Semua Deteksi
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-search fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Belum ada aktivitas deteksi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Diseases -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        OPT Prioritas Tinggi
                    </h5>
                </div>
                <div class="card-body">
                    @if($topDiseases && $topDiseases->count() > 0)
                        <div class="disease-list">
                            @foreach($topDiseases as $disease)
                            <div class="disease-item d-flex align-items-center mb-3">
                                <div class="disease-icon me-3">
                                    <i class="fas fa-{{ $disease->terjangkit == 'Hama' ? 'bug' : 'virus' }} 
                                        text-{{ $disease->terjangkit == 'Hama' ? 'danger' : 'warning' }}"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="disease-name">
                                        <strong>{{ Str::limit($disease->nama_penyakit, 30) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $disease->jenis_tanaman }}</small>
                                    </div>
                                </div>
                                <div class="disease-priority">
                                    <span class="badge bg-{{ $disease->priority >= 8 ? 'danger' : ($disease->priority >= 6 ? 'warning' : 'info') }}">
                                        {{ $disease->priority }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('opt.index') }}" class="btn btn-sm btn-outline-danger">
                                Lihat Semua OPT
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-bug fa-2x text-muted mb-2"></i>
                            <p class="text-muted">Belum ada data OPT</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-white">
                    <h4 class="mb-0 text-center">
                        <i class="fas fa-star me-2 text-warning"></i>
                        Fitur Unggulan Sistem
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-4">
                            <div class="feature-icon bg-primary text-white rounded-circle mx-auto mb-3">
                                <i class="fas fa-brain fa-2x"></i>
                            </div>
                            <h5>AI Detection</h5>
                            <p class="text-muted">Sistem deteksi berbasis AI dengan akurasi tinggi untuk identifikasi hama dan penyakit</p>
                        </div>
                        <div class="col-md-3 text-center mb-4">
                            <div class="feature-icon bg-success text-white rounded-circle mx-auto mb-3">
                                <i class="fas fa-database fa-2x"></i>
                            </div>
                            <h5>Database Lengkap</h5>
                            <p class="text-muted">Database komprehensif hama, penyakit, gejala, dan metode pengendalian</p>
                        </div>
                        <div class="col-md-3 text-center mb-4">
                            <div class="feature-icon bg-info text-white rounded-circle mx-auto mb-3">
                                <i class="fas fa-map-marked-alt fa-2x"></i>
                            </div>
                            <h5>Peta Interaktif</h5>
                            <p class="text-muted">Visualisasi data geografis dan distribusi varietas tanaman</p>
                        </div>
                        <div class="col-md-3 text-center mb-4">
                            <div class="feature-icon bg-warning text-white rounded-circle mx-auto mb-3">
                                <i class="fas fa-mobile-alt fa-2x"></i>
                            </div>
                            <h5>Responsive Design</h5>
                            <p class="text-muted">Akses mudah dari berbagai perangkat dengan tampilan yang optimal</p>
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
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 300px;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
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