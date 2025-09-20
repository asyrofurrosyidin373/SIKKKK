@extends('layouts.app')

@section('title', 'Deteksi')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h1 class="fw-bold text-success">Deteksi Hama & Penyakit</h1>
            <p class="lead text-muted">Identifikasi masalah pada tanaman Anda dengan dua cara berbeda</p>
        </div>
    </div>
    
    <!-- Method Selection -->
    <div class="row mb-5">
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm method-card" data-method="gejala">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-list-check fa-4x text-success"></i>
                    </div>
                    <h4 class="card-title">Deteksi Berdasarkan Gejala</h4>
                    <p class="card-text text-muted">Pilih gejala yang terlihat pada tanaman untuk mendapatkan diagnosis</p>
                    <button class="btn btn-primary btn-lg" onclick="showGejalMethod()">
                        Mulai Deteksi
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm method-card" data-method="foto">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-camera fa-4x text-success"></i>
                    </div>
                    <h4 class="card-title">Deteksi Berdasarkan Foto</h4>
                    <p class="card-text text-muted">Upload foto tanaman yang bermasalah untuk analisis AI</p>
                    <button class="btn btn-success btn-lg" onclick="showFotoMethod()">
                        Upload Foto
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gejala Detection Method -->
    <div id="gejalaMethod" style="display: none;">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-list-check me-2"></i>Deteksi Berdasarkan Gejala
                    <button type="button" class="btn-close btn-close-white float-end" onclick="hideAllMethods()"></button>
                </h5>
            </div>
            <div class="card-body">
                <form id="gejalaForm" method="POST" action="{{ route('deteksi.gejala') }}">
                    @csrf
                    <div class="mb-4">
                        <p class="text-muted">Pilih gejala yang Anda amati pada tanaman (bisa memilih lebih dari satu):</p>
                    </div>
                    
                    @foreach($gejalas as $bagian => $gejalaList)
                        <div class="mb-4">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-leaf me-2"></i>{{ ucfirst($bagian) }}
                            </h6>
                            <div class="row">
                                @foreach($gejalaList as $gejala)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="gejala_{{ $gejala->id }}" name="gejala[]" value="{{ $gejala->id }}">
                                            <label class="form-check-label" for="gejala_{{ $gejala->id }}">
                                                {{ $gejala->deskripsi }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-lg">
                            Deteksi Sekarang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Foto Detection Method -->
    <div id="fotoMethod" style="display: none;">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">
                    <i class="fas fa-camera me-2"></i>Deteksi Berdasarkan Foto
                    <button type="button" class="btn-close btn-close-white float-end" onclick="hideAllMethods()"></button>
                </h5>
            </div>
            <div class="card-body">
                <form id="fotoForm" method="POST" action="{{ route('deteksi.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pilih Jenis Tanaman</label>
                                <select class="form-select" name="tanaman_id" required>
                                    <option value="">-- Pilih Tanaman --</option>
                                    @foreach($tanamans as $tanaman)
                                        <option value="{{ $tanaman->id }}">{{ $tanaman->nama_tanaman }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Upload Foto</label>
                                <input type="file" class="form-control" name="foto" accept="image/*" required>
                                <div class="form-text">Format: JPG, PNG, maksimal 2MB</div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Lokasi (Opsional)</label>
                                <input type="text" class="form-control" name="lokasi" placeholder="Contoh: Kebun A, Desa Sukamaju">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Deskripsi Tambahan (Opsional)</label>
                                <textarea class="form-control" name="deskripsi" rows="3" 
                                          placeholder="Jelaskan kondisi tanaman atau gejala yang Anda amati..."></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="preview-area border rounded p-4 text-center h-100 d-flex align-items-center justify-content-center">
                                <div>
                                    <i class="fas fa-image fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Preview foto akan muncul di sini</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-upload me-2"></i>Upload & Analisis
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Tips Section -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light border-0">
                <div class="card-body">
                    <h5 class="mb-3"><i class="fas fa-lightbulb me-2"></i>Tips Deteksi</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Deteksi Berdasarkan Gejala</h6>
                            <ul class="text-muted">
                                <li>Amati tanaman dengan cermat</li>
                                <li>Pilih semua gejala yang terlihat</li>
                                <li>Perhatikan bagian tanaman yang terkena</li>
                                <li>Semakin banyak gejala yang dipilih, semakin akurat hasil deteksi</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Deteksi Berdasarkan Foto</h6>
                            <ul class="text-muted">
                                <li>Gunakan foto dengan pencahayaan yang baik</li>
                                <li>Fokus pada bagian tanaman yang bermasalah</li>
                                <li>Pastikan foto tidak blur atau kabur</li>
                                <li>Sertakan deskripsi untuk hasil yang lebih akurat</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function showGejalMethod() {
    hideAllMethods();
    document.getElementById('gejalaMethod').style.display = 'block';
    
    // Smooth scroll
    document.getElementById('gejalaMethod').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

function showFotoMethod() {
    hideAllMethods();
    document.getElementById('fotoMethod').style.display = 'block';
    
    // Smooth scroll
    document.getElementById('fotoMethod').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    });
}

function hideAllMethods() {
    document.getElementById('gejalaMethod').style.display = 'none';
    document.getElementById('fotoMethod').style.display = 'none';
}

// File preview
document.querySelector('input[name="foto"]').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const previewArea = document.querySelector('.preview-area');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            previewArea.innerHTML = `<img src="${e.target.result}" class="img-fluid rounded" style="max-height: 300px;">`;
        };
        reader.readAsDataURL(file);
    }
});

// Form validation
document.getElementById('gejalaForm').addEventListener('submit', function(e) {
    const checkboxes = document.querySelectorAll('input[name="gejala[]"]:checked');
    if (checkboxes.length === 0) {
        e.preventDefault();
        alert('Pilih minimal satu gejala!');
    }
});
</script>
@endpush