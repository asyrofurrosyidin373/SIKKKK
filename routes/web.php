<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VarietasController;
use App\Http\Controllers\OptController;
use App\Http\Controllers\DeteksiController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HamaPenyakitController;
use App\Http\Controllers\GejalaController;
use App\Http\Controllers\PengendalianController; // Tambah import

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home/Dashboard
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Peta Routes
Route::get('/peta', [HomeController::class, 'peta'])->name('peta');
Route::get('/peta/data', [HomeController::class, 'getMapData'])->name('peta.data');
Route::get('/map', [HomeController::class, 'peta'])->name('map');
Route::get('/map/data', [HomeController::class, 'getMapData'])->name('map.data');

// Hasil Routes - TAMBAHAN BARU
Route::get('/hasil', [HomeController::class, 'hasil'])->name('hasil');
Route::get('/hasil/export', [HomeController::class, 'exportKecamatan'])->name('hasil.export');

// Region Routes
Route::get('/kabupaten/{provinsi}', [RegionController::class, 'getKabupaten']);
Route::get('/kecamatan/{kabupaten}', [RegionController::class, 'getKecamatan']);

// Varietas Routes
Route::prefix('varietas')->name('varietas.')->group(function () {
    Route::get('/', [VarietasController::class, 'index'])->name('index');
    Route::get('/kedelai', [VarietasController::class, 'kedelai'])->name('kedelai');
    Route::get('/kacang-tanah', [VarietasController::class, 'kacangTanah'])->name('kacang-tanah');
    Route::get('/kacang-hijau', [VarietasController::class, 'kacangHijau'])->name('kacang-hijau');
    Route::get('/{type}/{id}', [VarietasController::class, 'show'])->name('show');
    Route::get('/varietas/kedelai/{varietas}/detail', [VarietasController::class, 'showKedelaiDetail'])
        ->name('varietas.kedelai.detail');
    
    // API routes for varietas
    Route::get('/api/recommendations', [VarietasController::class, 'getRecommendations'])->name('api.recommendations');
    Route::post('/api/compare', [VarietasController::class, 'compare'])->name('api.compare');
});

// OPT Routes
Route::prefix('opt')->name('opt.')->group(function () {
    Route::get('/', [OptController::class, 'index'])->name('index');
    Route::get('/{id}', [OptController::class, 'show'])->name('show');
    Route::get('/api/data', [OptController::class, 'getOptData'])->name('api.data');
    Route::get('/api/search', [OptController::class, 'search'])->name('api.search');
});

// Hama & Penyakit Routes
Route::prefix('hama-penyakit')->name('hama-penyakit.')->group(function () {
    Route::get('/', [HamaPenyakitController::class, 'index'])->name('index');
    Route::get('/{hamaPenyakit}', [HamaPenyakitController::class, 'show'])->name('show');
});

// Alternative routes for backward compatibility
Route::prefix('hama')->name('hama.')->group(function () {
    Route::get('/', [HamaPenyakitController::class, 'index'])->name('index');
    Route::get('/{hamaPenyakit}', [HamaPenyakitController::class, 'show'])->name('show');
});

Route::prefix('penyakit')->name('penyakit.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('hama-penyakit.index', ['jenis' => 'penyakit']);
    })->name('index');
});

// Gejala Routes
Route::prefix('gejala')->name('gejala.')->group(function () {
    Route::get('/', [GejalaController::class, 'index'])->name('index');
    Route::get('/{gejala}', [GejalaController::class, 'show'])->name('show');
});

// Pengendalian Routes - Update dengan controller yang proper
Route::prefix('pengendalian')->name('pengendalian.')->group(function () {
    Route::get('/', [PengendalianController::class, 'index'])->name('index');
    Route::get('/{id}', [PengendalianController::class, 'show'])->name('show');
});

// Deteksi Routes
Route::prefix('deteksi')->name('deteksi.')->group(function () {
    Route::get('/', [DeteksiController::class, 'index'])->name('index');
    Route::post('/gejala', [DeteksiController::class, 'deteksiGejala'])->name('gejala');
    Route::post('/upload', [DeteksiController::class, 'uploadFoto'])->name('upload');
    Route::post('/diagnose', [DeteksiController::class, 'diagnose'])->name('diagnose');
    Route::get('/hasil', [DeteksiController::class, 'hasil'])->name('hasil');
});

// API Routes for AJAX calls
Route::prefix('api')->name('api.')->group(function () {
    Route::post('/deteksi/diagnose', [DeteksiController::class, 'diagnose'])->name('deteksi.diagnose');
    Route::get('/gejala/search', [GejalaController::class, 'search'])->name('gejala.search');
    Route::get('/hama-penyakit/search', [HamaPenyakitController::class, 'search'])->name('hama-penyakit.search');
    Route::get('/pengendalian/search', [PengendalianController::class, 'search'])->name('pengendalian.search'); // Tambah API search
    
    // API Routes untuk Region Detail - TAMBAHAN BARU
    Route::prefix('regions')->name('regions.')->group(function () {
        Route::get('/kecamatan/{id}', [RegionController::class, 'getKecamatanDetail'])->name('kecamatan.detail');
    });
});


// Database Test Route (for development)
Route::get('/test-db', function() {
    try {
        $stats = [
            'connection' => 'OK',
            'provinsi' => \App\Models\TabProvinsi::count(),
            'kabupaten' => \App\Models\TabKabupaten::count(),
            'kecamatan' => \App\Models\TabKecamatan::count(),
            'kecamatan_with_coords' => \App\Models\TabKecamatan::whereNotNull('latitude')->whereNotNull('longitude')->count(),
            'kedelai' => \App\Models\KomKedelai::count(),
            'kacang_tanah' => \App\Models\KomKacangTanah::count(),
            'kacang_hijau' => \App\Models\KomKacangHijau::count(),
            'kecamatan_with_kedelai' => \App\Models\TabKecamatan::where('jenis_komoditas', 'kedelai')->count(),
            'kecamatan_with_kacang_tanah' => \App\Models\TabKecamatan::where('jenis_komoditas', 'kacang_tanah')->count(),
            'kecamatan_with_kacang_hijau' => \App\Models\TabKecamatan::where('jenis_komoditas', 'kacang_hijau')->count(),
        ];
        
        return response()->json([
            'status' => 'success',
            'message' => 'Database connection successful',
            'data' => $stats
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => 'Database connection failed',
            'error' => $e->getMessage()
        ], 500);
    }
})->name('test.db');

// Debug Routes (for development)
Route::get('/debug/provinsi', function() {
    try {
        $provinsi = \App\Models\TabProvinsi::select('id', 'nama_provinsi')->get();
        return response()->json([
            'status' => 'success',
            'count' => $provinsi->count(),
            'data' => $provinsi
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/debug/kabupaten/{provinsiId}', function($provinsiId) {
    try {
        $kabupaten = \App\Models\TabKabupaten::where('tab_provinsi_id', $provinsiId)
            ->select('id', 'nama_kabupaten', 'tab_provinsi_id')
            ->get();
        return response()->json([
            'status' => 'success',
            'provinsi_id' => $provinsiId,
            'count' => $kabupaten->count(),
            'data' => $kabupaten
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/debug/kecamatan/{kabupatenId}', function($kabupatenId) {
    try {
        $kecamatan = \App\Models\TabKecamatan::where('tab_kabupaten_id', $kabupatenId)
            ->select('id', 'nama_kecamatan', 'tab_kabupaten_id', 'latitude', 'longitude')
            ->get();
        return response()->json([
            'status' => 'success',
            'kabupaten_id' => $kabupatenId,
            'count' => $kecamatan->count(),
            'data' => $kecamatan
        ]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// Debug page
Route::get('/debug', function() {
    return view('debug');
})->name('debug');

// Debug routes
Route::get('/debug/map-data', [\App\Http\Controllers\DebugController::class, 'testMapData']);
Route::get('/debug/simple-map', [\App\Http\Controllers\DebugController::class, 'simpleMapData']);

// Temporary fix for peta data
Route::get('/peta/data-simple', [\App\Http\Controllers\DebugController::class, 'simpleMapData']);

// Quick kecamatan test
Route::get('/test-kecamatan', function() {
    $kecamatan = \App\Models\TabKecamatan::first();
    return response()->json([
        'total_kecamatan' => \App\Models\TabKecamatan::count(),
        'sample_data' => $kecamatan ? [
            'id' => $kecamatan->id,
            'nama_kecamatan' => $kecamatan->nama_kecamatan,
            'jenis_komoditas' => $kecamatan->jenis_komoditas,
            'nama_komoditas' => $kecamatan->nama_komoditas,
            'nama_varietas' => $kecamatan->nama_varietas,
            'luas_tanam' => $kecamatan->luas_tanam,
            'total_produksi' => $kecamatan->total_produksi,
        ] : null
    ]);
});

// Main API endpoint for kecamatan data from database
Route::get('/api/kecamatan-data', function() {
    try {
        // Log the attempt
        Log::info('API kecamatan-data called with params: ' . json_encode(request()->all()));
        
        // Test database connection first
        $dbTest = DB::connection()->getPdo();
        Log::info('Database connection: OK');
        
        // Count total records
        $totalCount = \App\Models\TabKecamatan::count();
        Log::info('Total kecamatan records: ' . $totalCount);
        
        if ($totalCount == 0) {
            return response()->json([
                'success' => false,
                'message' => 'Database kosong - tidak ada data kecamatan',
                'data' => [],
                'count' => 0,
                'debug' => 'No records in tab_kecamatan table'
            ]);
        }
        
        // Simple query without complex relations first
        $query = \App\Models\TabKecamatan::query();
        
        // Apply basic filters
        if (request('provinsi')) {
            $query->whereHas('kabupaten', function($q) {
                $q->where('tab_provinsi_id', request('provinsi'));
            });
        }
        
        if (request('kabupaten')) {
            $query->where('tab_kabupaten_id', request('kabupaten'));
        }
        
        if (request('kecamatan')) {
            $query->where('id', request('kecamatan'));
        }
        
        // Get data with relations
        $kecamatans = $query->with(['kabupaten.provinsi'])->get();
        Log::info('Query result count: ' . $kecamatans->count());
        
        $data = $kecamatans->map(function($item) {
            // Resolve OPT names from opt_id array if available
            $optNames = [];
            try {
                $optIds = [];
                if (!empty($item->opt_id)) {
                    $optIds = is_array($item->opt_id) ? $item->opt_id : json_decode($item->opt_id, true);
                    if (!is_array($optIds)) { $optIds = []; }
                }
                if (!empty($optIds)) {
                    $optNames = \App\Models\HamaPenyakit::whereIn('id', $optIds)->pluck('nama_penyakit')->toArray();
                }
            } catch (\Exception $e) {
                // ignore, keep empty
            }

            return [
                'id' => $item->id,
                'nama_kecamatan' => $item->nama_kecamatan,
                'latitude' => (float) ($item->latitude ?? 0),
                'longitude' => (float) ($item->longitude ?? 0),
                'kabupaten_nama' => $item->kabupaten->nama_kabupaten ?? 'N/A',
                'provinsi_nama' => $item->kabupaten->provinsi->nama_provinsi ?? 'N/A',
                
                // Data Tanah
                'ip_lahan' => $item->ip_lahan ?? null,
                'kdr_p' => $item->kdr_p ?? null,
                'kdr_c' => $item->kdr_c ?? null,
                'kdr_k' => $item->kdr_k ?? null,
                'ktk' => $item->ktk ?? null,
                
                // Komoditas dengan struktur baru
                'jenis_komoditas' => $item->jenis_komoditas,
                'nama_komoditas' => $item->nama_komoditas,
                'nama_varietas' => $item->nama_varietas,
                'luas_tanam' => $item->luas_tanam,
                'produktivitas' => $item->produktivitas,
                'total_produksi' => $item->total_produksi,
                'komoditas_kedelai' => $item->jenis_komoditas === 'kedelai',
                'komoditas_kacang_tanah' => $item->jenis_komoditas === 'kacang_tanah',
                'komoditas_kacang_hijau' => $item->jenis_komoditas === 'kacang_hijau',
                
                // Rekomendasi Waktu Tanam
                'rekomendasi_waktu_tanam_kedelai' => $item->rekomendasi_waktu_tanam_kedelai ?? [],
                'rekomendasi_waktu_tanam_kacang_tanah' => $item->rekomendasi_waktu_tanam_kacang_tanah ?? [],
                'rekomendasi_waktu_tanam_kacang_hijau' => $item->rekomendasi_waktu_tanam_kacang_hijau ?? [],
                
                // Data Iklim
                'bulan_hujan' => $item->bulan_hujan ?? [],
                'bulan_kering' => $item->bulan_kering ?? [],

                // OPT resolved names
                'opt' => $optNames,
            ];
        });
        
        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $data->count(),
            'message' => 'Data berhasil dimuat dari database MySQL',
            'source' => 'MySQL Database',
            'total_in_db' => $totalCount
        ]);
        
    } catch (\Exception $e) {
        Log::error('API kecamatan-data error: ' . $e->getMessage());
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Error database: ' . $e->getMessage(),
            'data' => [],
            'count' => 0,
            'error_type' => get_class($e),
            'debug' => config('app.debug') ? $e->getTraceAsString() : 'Enable debug mode for details'
        ], 200); // Return 200 to avoid AJAX errors
    }
});

// Backup simple endpoint
Route::get('/api/kecamatan-simple', function() {
    try {
        $data = \App\Models\TabKecamatan::select('id', 'nama_kecamatan', 'latitude', 'longitude', 'tab_kabupaten_id')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama_kecamatan' => $item->nama_kecamatan,
                    'latitude' => (float) ($item->latitude ?? 0),
                    'longitude' => (float) ($item->longitude ?? 0),
                    'kabupaten_nama' => 'Sample Kabupaten',
                    'provinsi_nama' => 'Sample Provinsi',
                    'ip_lahan' => 3.2,
                    'kdr_p' => 2.5,
                    'kdr_c' => 2.1,
                    'kdr_k' => 1.8,
                    'ktk' => 2.9,
                    'kom_kedelai_id' => null,
                    'kom_kacang_tanah_id' => null,
                    'kom_kacang_hijau_id' => null,
                    'komoditas_kedelai' => false,
                    'komoditas_kacang_tanah' => false,
                    'komoditas_kacang_hijau' => false,
                    'rekomendasi_waktu_tanam_kedelai' => ['Maret', 'April'],
                    'rekomendasi_waktu_tanam_kacang_tanah' => ['April', 'Mei'],
                    'rekomendasi_waktu_tanam_kacang_hijau' => ['Mei', 'Juni'],
                    'bulan_hujan' => ['Januari', 'Februari', 'Maret'],
                    'bulan_kering' => ['Juli', 'Agustus', 'September']
                ];
            });
            
        return response()->json([
            'success' => true,
            'data' => $data,
            'count' => $data->count(),
            'message' => 'Simple data loaded'
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
            'data' => [],
            'count' => 0
        ]);
    }
});

// Test route to check if original peta data works
Route::get('/test-original-peta', function() {
    try {
        $controller = new \App\Http\Controllers\HomeController();
        $request = request();
        return $controller->getMapData($request);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});