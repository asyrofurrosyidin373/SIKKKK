# Struktur Folder Views

```
resources/views/
├── layouts/
│   ├── app.blade.php                 # Layout utama dengan navbar, sidebar
│   └── components/
│       ├── navbar.blade.php          # Navigation bar
│       ├── sidebar.blade.php         # Sidebar menu
│       └── modal.blade.php           # Modal template
│
├── dashboard/
│   └── index.blade.php               # Halaman dashboard/beranda
│
├── map/                              # HALAMAN PETA PERSEBARAN
│   ├── index.blade.php               # Halaman utama peta
│   └── components/
│       ├── map-container.blade.php   # Container peta Leaflet
│       ├── location-filters.blade.php # Filter provinsi/kabupaten/kecamatan
│       ├── kecamatan-detail.blade.php # Panel detail kecamatan
│       └── modals/
│           ├── varietas-modal.blade.php  # Modal detail varietas
│           └── hama-modal.blade.php      # Modal detail hama/OPT
│
├── varietas/                         # HALAMAN VARIETAS
│   ├── index.blade.php               # Halaman utama varietas dengan filter
│   └── components/
│       ├── search-filters.blade.php  # Form filter pencarian
│       ├── varietas-grid.blade.php   # Grid hasil pencarian
│       ├── varietas-card.blade.php   # Card individual varietas
│       └── modals/
│           └── varietas-detail-modal.blade.php # Modal detail varietas
│
├── deteksi/                          # HALAMAN DETEKSI HAMA
│   ├── index.blade.php               # Halaman utama deteksi
│   ├── history.blade.php             # Riwayat deteksi
│   └── components/
│       ├── symptom-selector.blade.php    # Pemilih gejala
│       ├── image-upload.blade.php        # Upload gambar
│       ├── detection-results.blade.php   # Hasil deteksi
│       └── modals/
│           └── hama-detail-modal.blade.php # Modal detail hama
│
└── partials/                         # Komponen reusable
    ├── loading.blade.php             # Loading spinner
    ├── error.blade.php               # Error message
    └── pagination.blade.php          # Pagination component
```

## File Views Utama

### 1. Layout Utama (layouts/app.blade.php)
```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIG Aneka Kacang')</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    @stack('styles')
</head>
<body>
    @include('layouts.components.navbar')
    
    <div class="container-fluid">
        <div class="row">
            @include('layouts.components.sidebar')
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    @stack('scripts')
</body>
</html>
```

### 2. Halaman Peta (map/index.blade.php)
```html
@extends('layouts.app')

@section('title', 'Peta Persebaran - SIG Aneka Kacang')

@section('content')
<div class="row">
    <!-- Filter Panel -->
    <div class="col-md-3">
        @include('map.components.location-filters')
        @include('map.components.kecamatan-detail')
    </div>
    
    <!-- Map Container -->
    <div class="col-md-9">
        @include('map.components.map-container')
    </div>
</div>

<!-- Modals -->
@include('map.components.modals.varietas-modal')
@include('map.components.modals.hama-modal')
@endsection

@push('scripts')
<script src="{{ asset('js/map.js') }}"></script>
@endpush
```

### 3. Halaman Varietas (varietas/index.blade.php)
```html
@extends('layouts.app')

@section('title', 'Database Varietas - SIG Aneka Kacang')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h2">Database Varietas Aneka Kacang</h1>
            <p class="text-muted">Cari dan jelajahi berbagai varietas kedelai, kacang tanah, dan kacang hijau</p>
        </div>
    </div>
    
    <!-- Search Filters -->
    @include('varietas.components.search-filters')
    
    <!-- Results Grid -->
    <div class="row mt-4">
        <div class="col-12">
            @include('varietas.components.varietas-grid')
        </div>
    </div>
</div>

<!-- Modal -->
@include('varietas.components.modals.varietas-detail-modal')
@endsection

@push('scripts')
<script src="{{ asset('js/varietas.js') }}"></script>
@endpush
```

### 4. Halaman Deteksi (deteksi/index.blade.php)
```html
@extends('layouts.app')

@section('title', 'Deteksi Hama & Penyakit - SIG Aneka Kacang')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h2">Sistem Deteksi Hama & Penyakit</h1>
            <p class="text-muted">Identifikasi hama dan penyakit pada tanaman kacang-kacangan</p>
        </div>
    </div>
    
    <div class="row">
        <!-- Detection Methods -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Deteksi Berdasarkan Foto</h5>
                </div>
                <div class="card-body">
                    @include('deteksi.components.image-upload')
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header">
                    <h5>Deteksi Berdasarkan Gejala</h5>
                </div>
                <div class="card-body">
                    @include('deteksi.components.symptom-selector', ['gejalas' => $gejalas])
                </div>
            </div>
        </div>
    </div>
    
    <!-- Results -->
    <div class="row">
        <div class="col-12">
            @include('deteksi.components.detection-results')
        </div>
    </div>
</div>

<!-- Modal -->
@include('deteksi.components.modals.hama-detail-modal')
@endsection

@push('scripts')
<script src="{{ asset('js/deteksi.js') }}"></script>
@endpush
```

## Routes yang Dibutuhkan

```php
// routes/web.php
Route::get('/', [MapController::class, 'index'])->name('map.index');

// Map routes
Route::prefix('map')->name('map.')->group(function () {
    Route::get('/kabupaten/{provinsi}', [MapController::class, 'getKabupaten']);
    Route::get('/kecamatan/{kabupaten}', [MapController::class, 'getKecamatan']);  
    Route::get('/kecamatan-detail/{kecamatan}', [MapController::class, 'getKecamatanDetail']);
});

// Varietas routes
Route::prefix('varietas')->name('varietas.')->group(function () {
    Route::get('/', [VarietasController::class, 'index'])->name('index');
    Route::get('/search', [VarietasController::class, 'search'])->name('search');
    Route::get('/{type}/{id}', [VarietasController::class, 'show'])->name('show');
});

// Deteksi routes
Route::prefix('deteksi')->name('deteksi.')->group(function () {
    Route::get('/', [DeteksiHamaController::class, 'index'])->name('index');
    Route::post('/upload-image', [DeteksiHamaController::class, 'uploadImage'])->name('upload');
    Route::post('/detect-symptoms', [DeteksiHamaController::class, 'detectBySymptoms'])->name('symptoms');
    Route::get('/history', [DeteksiHamaController::class, 'history'])->name('history');
    Route::get('/hama/{id}', [DeteksiHamaController::class, 'hamaPenyakitDetail'])->name('hama.detail');
});

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
```

## JavaScript Files yang Dibutuhkan

```
public/js/
├── app.js                    # Core JavaScript functions
├── map.js                    # Map functionality dengan Leaflet
├── varietas.js               # Varietas search & filters
└── deteksi.js               # Detection system functionality
```

Struktur ini memberikan:
- Separation of concerns yang jelas
- Reusable components 
- Modal system untuk detail views
- AJAX-ready untuk interaksi dinamis
- Bootstrap-based responsive design
- Leaflet maps integration
- File upload capabilities
