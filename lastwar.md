<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\GeographicController;
use App\Http\Controllers\Api\KomoditasController;
use App\Http\Controllers\Api\VarietasController;
use App\Http\Controllers\Api\DiagnosisController;
use App\Http\Controllers\Api\GeneralDataController;
use App\Http\Controllers\Api\TestController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Test route
Route::get('test', [TestController::class, 'test']);

// API v1 routes
Route::prefix('v1')->group(function () {
    
    // Geographic data routes
    Route::prefix('geographic')->group(function () {
        Route::get('provinces', [GeographicController::class, 'getProvinces']);
        Route::get('kabupatens', [GeographicController::class, 'getKabupatens']);
        Route::get('kecamatans', [GeographicController::class, 'getKecamatans']);
        Route::get('kecamatans/{id}', [GeographicController::class, 'getKecamatanDetail']);
        Route::get('months', [GeographicController::class, 'getMonths']);
        Route::get('search', [GeographicController::class, 'searchLocations']);
    });

    // Commodity data routes
    Route::prefix('commodities')->group(function () {
        Route::get('kedelai', [KomoditasController::class, 'getKedelaiCommodities']);
        Route::get('kacang-tanah', [KomoditasController::class, 'getKacangTanahCommodities']);
        Route::get('kacang-hijau', [KomoditasController::class, 'getKacangHijauCommodities']);
        Route::get('recommendations/{kecamatan_id}', [KomoditasController::class, 'getCommodityRecommendations']);
    });

    // Variety data routes
    Route::prefix('varieties')->group(function () {
        Route::get('kedelai', [VarietasController::class, 'getKedelaiVarieties']);
        Route::get('kacang-tanah', [VarietasController::class, 'getKacangTanahVarieties']);
        Route::get('kacang-hijau', [VarietasController::class, 'getKacangHijauVarieties']);
        Route::get('{type}/{id}', [VarietasController::class, 'getVarietyDetail']);
        Route::post('compare', [VarietasController::class, 'compareVarieties']);
    });

    // Diagnosis routes
    Route::prefix('diagnosis')->group(function () {
        Route::get('organisms', [DiagnosisController::class, 'getOrganisms']);
        Route::get('organisms/{id}', [DiagnosisController::class, 'getOrganismDetail']);
        Route::get('symptoms', [DiagnosisController::class, 'getSymptoms']);
        Route::post('diagnose', [DiagnosisController::class, 'diagnoseBySymptoms']);
        Route::get('control-methods', [DiagnosisController::class, 'getControlMethods']);
        Route::get('insecticides', [DiagnosisController::class, 'getInsecticides']);
        
        // Protected routes (require authentication)
        Route::middleware('auth:sanctum')->group(function () {
            Route::post('reports', [DiagnosisController::class, 'submitDetectionReport']);
            Route::get('my-reports', [DiagnosisController::class, 'getUserReports']);
        });
    });

    // General data routes
    Route::prefix('data')->group(function () {
        Route::get('plants', [GeneralDataController::class, 'getPlants']);
        Route::get('dashboard-stats', [GeneralDataController::class, 'getDashboardStats']);
        Route::get('recent-activities', [GeneralDataController::class, 'getRecentActivities']);
    });
});    

controller : 

<?php

// 1. GeographicController.php - For geographic data
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TabProvinsi;
use App\Models\TabKabupaten;
use App\Models\TabKecamatan;
use App\Models\TabBulan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GeographicController extends Controller
{
    /**
     * Get all provinces with kabupaten count
     */
    public function getProvinces(): JsonResponse
    {
        $provinces = TabProvinsi::withCount('kabupaten')
            ->orderBy('nama_provinsi')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $provinces
        ]);
    }

    /**
     * Get kabupatens by province
     */
    public function getKabupatens(Request $request): JsonResponse
    {
        $query = TabKabupaten::with('provinsi')->withCount('kecamatan');
        
        if ($request->has('provinsi_id')) {
            $query->where('tab_provinsi_id', $request->provinsi_id);
        }
        
        $kabupatens = $query->orderBy('nama_kabupaten')->get();

        return response()->json([
            'status' => 'success',
            'data' => $kabupatens
        ]);
    }

    /**
     * Get kecamatans with full details
     */
    public function getKecamatans(Request $request): JsonResponse
    {
        $query = TabKecamatan::with([
            'kabupaten.provinsi',
            'komoditasKedelai',
            'komoditasKacangTanah',
            'komoditasKacangHijau'
        ]);
        
        if ($request->has('kabupaten_id')) {
            $query->where('tab_kabupaten_id', $request->kabupaten_id);
        }
        
        if ($request->has('provinsi_id')) {
            $query->whereHas('kabupaten', function($q) use ($request) {
                $q->where('tab_provinsi_id', $request->provinsi_id);
            });
        }

        $kecamatans = $query->orderBy('nama_kecamatan')->get();

        // Add formatted month names
        $kecamatans->each(function ($kecamatan) {
            $kecamatan->bulan_hujan_nama = $kecamatan->bulan_hujan_nama;
            $kecamatan->waktu_tanam_kedelai_nama = $kecamatan->waktu_tanam_kedelai_nama;
            $kecamatan->waktu_tanam_kacang_tanah_nama = $kecamatan->waktu_tanam_kacang_tanah_nama;
            $kecamatan->waktu_tanam_kacang_hijau_nama = $kecamatan->waktu_tanam_kacang_hijau_nama;
        });

        return response()->json([
            'status' => 'success',
            'data' => $kecamatans
        ]);
    }

    /**
     * Get specific kecamatan details
     */
    public function getKecamatanDetail(string $id): JsonResponse
    {
        $kecamatan = TabKecamatan::with([
            'kabupaten.provinsi',
            'komoditasKedelai.organisme',
            'komoditasKedelai.varietas',
            'komoditasKacangTanah.organisme',
            'komoditasKacangTanah.varietas',
            'komoditasKacangHijau.organisme',
            'komoditasKacangHijau.varietas',
            'bulans'
        ])->find($id);

        if (!$kecamatan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Kecamatan not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $kecamatan
        ]);
    }

    /**
     * Get months data
     */
    public function getMonths(): JsonResponse
    {
        $months = TabBulan::orderBy('angka_bulan')->get();

        return response()->json([
            'status' => 'success',
            'data' => $months
        ]);
    }

    /**
     * Search locations
     */
    public function searchLocations(Request $request): JsonResponse
    {
        $search = $request->get('q', '');
        
        if (strlen($search) < 2) {
            return response()->json([
                'status' => 'success',
                'data' => []
            ]);
        }

        // Search provinces
        $provinces = TabProvinsi::where('nama_provinsi', 'LIKE', "%{$search}%")
            ->select('id', 'nama_provinsi as name')
            ->addSelect(\DB::raw("'province' as type"))
            ->limit(5)
            ->get();

        // Search kabupatens
        $kabupatens = TabKabupaten::with('provinsi')
            ->where('nama_kabupaten', 'LIKE', "%{$search}%")
            ->select('id', 'nama_kabupaten as name', 'tab_provinsi_id')
            ->addSelect(\DB::raw("'kabupaten' as type"))
            ->limit(5)
            ->get();

        // Search kecamatans
        $kecamatans = TabKecamatan::with('kabupaten.provinsi')
            ->where('nama_kecamatan', 'LIKE', "%{$search}%")
            ->select('id', 'nama_kecamatan as name', 'tab_kabupaten_id')
            ->addSelect(\DB::raw("'kecamatan' as type"))
            ->limit(10)
            ->get();

        $results = collect()
            ->merge($provinces)
            ->merge($kabupatens)
            ->merge($kecamatans);

        return response()->json([
            'status' => 'success',
            'data' => $results
        ]);
    }
}


<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VarietasKedelai;
use App\Models\VarietasKacangTanah;
use App\Models\VarietasKacangHijau;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VarietasController extends Controller
{
    /**
     * Get kedelai varieties
     */
    public function getKedelaiVarieties(Request $request): JsonResponse
    {
        $query = VarietasKedelai::with(['organisme', 'komoditas']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_varietas', 'LIKE', "%{$search}%");
        }

        if ($request->has('year_from')) {
            $query->where('tahun', '>=', $request->year_from);
        }

        if ($request->has('year_to')) {
            $query->where('tahun', '<=', $request->year_to);
        }

        $varieties = $query->orderBy('nama_varietas')->get();

        return response()->json([
            'status' => 'success',
            'data' => $varieties
        ]);
    }

    /**
     * Get kacang tanah varieties
     */
    public function getKacangTanahVarieties(Request $request): JsonResponse
    {
        $query = VarietasKacangTanah::with(['organisme', 'komoditas']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_varietas', 'LIKE', "%{$search}%");
        }

        $varieties = $query->orderBy('nama_varietas')->get();

        return response()->json([
            'status' => 'success',
            'data' => $varieties
        ]);
    }

    /**
     * Get kacang hijau varieties
     */
    public function getKacangHijauVarieties(Request $request): JsonResponse
    {
        $query = VarietasKacangHijau::with(['organisme', 'komoditas']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('nama_varietas', 'LIKE', "%{$search}%");
        }

        $varieties = $query->orderBy('nama_varietas')->get();

        return response()->json([
            'status' => 'success',
            'data' => $varieties
        ]);
    }

    /**
     * Get variety details
     */
    public function getVarietyDetail(string $type, string $id): JsonResponse
    {
        $model = match($type) {
            'kedelai' => VarietasKedelai::class,
            'kacang-tanah' => VarietasKacangTanah::class,
            'kacang-hijau' => VarietasKacangHijau::class,
            default => null
        };

        if (!$model) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid variety type'
            ], 400);
        }

        $variety = $model::with(['organisme', 'komoditas'])->find($id);

        if (!$variety) {
            return response()->json([
                'status' => 'error',
                'message' => 'Variety not found'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $variety
        ]);
    }

    /**
     * Compare varieties
     */
    public function compareVarieties(Request $request): JsonResponse
    {
        $request->validate([
            'varieties' => 'required|array|min:2|max:5',
            'varieties.*' => 'required|array',
            'varieties.*.type' => 'required|string|in:kedelai,kacang-tanah,kacang-hijau',
            'varieties.*.id' => 'required|string'
        ]);

        $comparisons = [];

        foreach ($request->varieties as $varietyData) {
            $model = match($varietyData['type']) {
                'kedelai' => VarietasKedelai::class,
                'kacang-tanah' => VarietasKacangTanah::class,
                'kacang-hijau' => VarietasKacangHijau::class,
            };

            $variety = $model::with(['organisme'])->find($varietyData['id']);
            if ($variety) {
                $comparisons[] = [
                    'type' => $varietyData['type'],
                    'data' => $variety
                ];
            }
        }

        return response()->json([
            'status' => 'success',
            'data' => $comparisons
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KomKedelai;
use App\Models\KomKacangTanah;
use App\Models\KomKacangHijau;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class KomoditasController extends Controller
{
    /**
     * Get all kedelai commodities
     */
    public function getKedelaiCommodities(Request $request): JsonResponse
    {
        $query = KomKedelai::with([
            'kecamatan.kabupaten.provinsi',
            'organisme',
            'varietas'
        ]);

        if ($request->has('kecamatan_id')) {
            $query->whereHas('kecamatan', function($q) use ($request) {
                $q->where('id', $request->kecamatan_id);
            });
        }

        $commodities = $query->withCount('kecamatan')->get();

        return response()->json([
            'status' => 'success',
            'data' => $commodities
        ]);
    }

    /**
     * Get kacang tanah commodities
     */
    public function getKacangTanahCommodities(Request $request): JsonResponse
    {
        $query = KomKacangTanah::with([
            'kecamatan.kabupaten.provinsi',
            'organisme',
            'varietas'
        ]);

        if ($request->has('kecamatan_id')) {
            $query->whereHas('kecamatan', function($q) use ($request) {
                $q->where('id', $request->kecamatan_id);
            });
        }

        $commodities = $query->withCount('kecamatan')->get();

        return response()->json([
            'status' => 'success',
            'data' => $commodities
        ]);
    }

    /**
     * Get kacang hijau commodities
     */
    public function getKacangHijauCommodities(Request $request): JsonResponse
    {
        $query = KomKacangHijau::with([
            'kecamatan.kabupaten.provinsi',
            'organisme',
            'varietas'
        ]);

        if ($request->has('kecamatan_id')) {
            $query->whereHas('kecamatan', function($q) use ($request) {
                $q->where('id', $request->kecamatan_id);
            });
        }

        $commodities = $query->withCount('kecamatan')->get();

        return response()->json([
            'status' => 'success',
            'data' => $commodities
        ]);
    }

    /**
     * Get commodity recommendations by location
     */
    public function getCommodityRecommendations(string $kecamatan_id): JsonResponse
    {
        $kecamatan = \App\Models\TabKecamatan::with([
            'komoditasKedelai.organisme',
            'komoditasKedelai.varietas',
            'komoditasKacangTanah.organisme',
            'komoditasKacangTanah.varietas',
            'komoditasKacangHijau.organisme',
            'komoditasKacangHijau.varietas'
        ])->find($kecamatan_id);

        if (!$kecamatan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Location not found'
            ], 404);
        }

        $recommendations = [
            'kedelai' => $kecamatan->komoditasKedelai,
            'kacang_tanah' => $kecamatan->komoditasKacangTanah,
            'kacang_hijau' => $kecamatan->komoditasKacangHijau,
            'planting_schedule' => [
                'kedelai' => $kecamatan->waktu_tanam_kedelai_nama,
                'kacang_tanah' => $kecamatan->waktu_tanam_kacang_tanah_nama,
                'kacang_hijau' => $kecamatan->waktu_tanam_kacang_hijau_nama
            ],
            'weather_pattern' => [
                'rainy_months' => $kecamatan->bulan_hujan_nama,
                'dry_months' => $kecamatan->bulan_kering ?? []
            ],
            'soil_condition' => [
                'ip_lahan' => $kecamatan->ip_lahan,
                'kdr_p' => $kecamatan->kdr_p,
                'kdr_c' => $kecamatan->kdr_c,
                'kdr_k' => $kecamatan->kdr_k,
                'ktk' => $kecamatan->ktk
            ]
        ];

        return response()->json([
            'status' => 'success',
            'data' => $recommendations
        ]);
    }
}


<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tanaman;
use App\Models\TabBulan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GeneralDataController extends Controller
{
    /**
     * Get all plants
     */
    public function getPlants(): JsonResponse
    {
        $plants = Tanaman::withCount('laporanDeteksi')
            ->orderBy('nama_tanaman')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $plants
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): JsonResponse
    {
        $stats = [
            'total_provinces' => \App\Models\TabProvinsi::count(),
            'total_kabupatens' => \App\Models\TabKabupaten::count(),
            'total_kecamatans' => \App\Models\TabKecamatan::count(),
            'total_varieties' => [
                'kedelai' => \App\Models\VarietasKedelai::count(),
                'kacang_tanah' => \App\Models\VarietasKacangTanah::count(),
                'kacang_hijau' => \App\Models\VarietasKacangHijau::count(),
            ],
            'total_organisms' => \App\Models\OrgPenTan::count(),
            'total_reports' => \App\Models\LaporanDeteksi::count(),
            'pending_reports' => \App\Models\LaporanDeteksi::where('status', 'pending')->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities(): JsonResponse
    {
        $recentReports = LaporanDeteksi::with(['user', 'tanaman', 'organisme'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $recentReports
        ]);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tanaman;
use App\Models\TabBulan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GeneralDataController extends Controller
{
    /**
     * Get all plants
     */
    public function getPlants(): JsonResponse
    {
        $plants = Tanaman::withCount('laporanDeteksi')
            ->orderBy('nama_tanaman')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $plants
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function getDashboardStats(): JsonResponse
    {
        $stats = [
            'total_provinces' => \App\Models\TabProvinsi::count(),
            'total_kabupatens' => \App\Models\TabKabupaten::count(),
            'total_kecamatans' => \App\Models\TabKecamatan::count(),
            'total_varieties' => [
                'kedelai' => \App\Models\VarietasKedelai::count(),
                'kacang_tanah' => \App\Models\VarietasKacangTanah::count(),
                'kacang_hijau' => \App\Models\VarietasKacangHijau::count(),
            ],
            'total_organisms' => \App\Models\OrgPenTan::count(),
            'total_reports' => \App\Models\LaporanDeteksi::count(),
            'pending_reports' => \App\Models\LaporanDeteksi::where('status', 'pending')->count(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    /**
     * Get recent activities
     */
    public function getRecentActivities(): JsonResponse
    {
        $recentReports = LaporanDeteksi::with(['user', 'tanaman', 'organisme'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $recentReports
        ]);
    }
}

frontend: 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Manajemen Pertanian</title>
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://unpkg.com/vue-router@4"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .app-container {
            min-height: 100vh;
            display: flex;
        }

        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            padding: 20px;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4a5568;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .collapsed .logo-text {
            display: none;
        }

        .nav-menu {
            padding: 20px 0;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #4a5568;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border-left: 4px solid transparent;
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
            border-left-color: #667eea;
        }

        .nav-icon {
            width: 24px;
            margin-right: 12px;
            text-align: center;
        }

        .collapsed .nav-text {
            display: none;
        }

        .main-content {
            flex: 1;
            overflow-y: auto;
        }

        .top-bar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .toggle-btn {
            background: none;
            border: none;
            font-size: 20px;
            color: #4a5568;
            cursor: pointer;
            padding: 8px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .toggle-btn:hover {
            background: rgba(102, 126, 234, 0.1);
            color: #667eea;
        }

        .page-title {
            font-size: 28px;
            font-weight: 700;
            color: #2d3748;
        }

        .content-area {
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin-bottom: 24px;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 24px;
            border-radius: 16px;
            text-align: center;
            box-shadow: 0 8px 32px rgba(102, 126, 234, 0.3);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            opacity: 0.9;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #2d3748;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-secondary {
            background: #f7fafc;
            color: #4a5568;
            border: 2px solid #e2e8f0;
        }

        .btn-secondary:hover {
            background: #edf2f7;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .table th,
        .table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .table th {
            background: #f7fafc;
            font-weight: 600;
            color: #2d3748;
        }

        .table tbody tr:hover {
            background: rgba(102, 126, 234, 0.05);
        }

        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success {
            background: rgba(72, 187, 120, 0.1);
            color: #38a169;
        }

        .badge-warning {
            background: rgba(237, 137, 54, 0.1);
            color: #dd6b20;
        }

        .badge-danger {
            background: rgba(245, 101, 101, 0.1);
            color: #e53e3e;
        }

        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .search-box {
            position: relative;
            margin-bottom: 24px;
        }

        .search-input {
            width: 100%;
            padding: 12px 16px 12px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 16px;
            background: rgba(255, 255, 255, 0.8);
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background: white;
            border-radius: 16px;
            padding: 24px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .filters {
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .alert {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .alert-success {
            background: rgba(72, 187, 120, 0.1);
            color: #38a169;
            border: 1px solid rgba(72, 187, 120, 0.2);
        }

        .alert-error {
            background: rgba(245, 101, 101, 0.1);
            color: #e53e3e;
            border: 1px solid rgba(245, 101, 101, 0.2);
        }

        .grid {
            display: grid;
            gap: 20px;
        }

        .grid-2 { grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); }
        .grid-3 { grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); }

        .variety-card {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .variety-card:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.15);
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: fixed;
                left: -100%;
                z-index: 999;
            }
            
            .sidebar.open {
                left: 0;
            }
            
            .main-content {
                width: 100%;
            }
            
            .content-area {
                padding: 15px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="app-container">
            <!-- Sidebar -->
            <nav class="sidebar" :class="{ collapsed: sidebarCollapsed, open: sidebarOpen }">
                <div class="sidebar-header">
                    <div class="logo">
                        <i class="fas fa-seedling"></i>
                        <span class="logo-text">AgriSystem</span>
                    </div>
                </div>
                <div class="nav-menu">
                    <router-link to="/" class="nav-item" exact-active-class="active">
                        <i class="nav-icon fas fa-home"></i>
                        <span class="nav-text">Dashboard</span>
                    </router-link>
                    <router-link to="/locations" class="nav-item" active-class="active">
                        <i class="nav-icon fas fa-map-marker-alt"></i>
                        <span class="nav-text">Lokasi</span>
                    </router-link>
                    <router-link to="/varieties" class="nav-item" active-class="active">
                        <i class="nav-icon fas fa-leaf"></i>
                        <span class="nav-text">Varietas</span>
                    </router-link>
                    <router-link to="/diagnosis" class="nav-item" active-class="active">
                        <i class="nav-icon fas fa-stethoscope"></i>
                        <span class="nav-text">Diagnosis</span>
                    </router-link>
                    <router-link to="/recommendations" class="nav-item" active-class="active">
                        <i class="nav-icon fas fa-lightbulb"></i>
                        <span class="nav-text">Rekomendasi</span>
                    </router-link>
                    <router-link to="/reports" class="nav-item" active-class="active">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <span class="nav-text">Laporan</span>
                    </router-link>
                </div>
            </nav>

            <!-- Main Content -->
            <div class="main-content">
                <div class="top-bar">
                    <button class="toggle-btn" @click="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="page-title">{{ currentPageTitle }}</h1>
                    <div></div>
                </div>

                <div class="content-area">
                    <router-view></router-view>
                </div>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div v-if="loading" class="modal-overlay">
            <div class="loading">
                <div class="spinner"></div>
            </div>
        </div>
    </div>

    <script>
        // API Configuration
        const API_BASE = 'http://localhost:8000/api/v1';
        
        const apiClient = axios.create({
            baseURL: API_BASE,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        });

        // API Services
        const api = {
            geographic: {
                getProvinces: () => apiClient.get('/geographic/provinces'),
                getKabupatens: (provinsiId = null) => apiClient.get('/geographic/kabupatens', { 
                    params: provinsiId ? { provinsi_id: provinsiId } : {} 
                }),
                getKecamatans: (params = {}) => apiClient.get('/geographic/kecamatans', { params }),
                getKecamatanDetail: (id) => apiClient.get(`/geographic/kecamatans/${id}`),
                searchLocations: (query) => apiClient.get('/geographic/search', { 
                    params: { q: query } 
                })
            },
            commodity: {
                getRecommendations: (kecamatanId) => apiClient.get(`/commodities/recommendations/${kecamatanId}`)
            },
            variety: {
                getKedelaiVarieties: (params = {}) => apiClient.get('/varieties/kedelai', { params }),
                getKacangTanahVarieties: (params = {}) => apiClient.get('/varieties/kacang-tanah', { params }),
                getKacangHijauVarieties: (params = {}) => apiClient.get('/varieties/kacang-hijau', { params }),
                getVarietyDetail: (type, id) => apiClient.get(`/varieties/${type}/${id}`),
                compareVarieties: (varieties) => apiClient.post('/varieties/compare', { varieties })
            },
            diagnosis: {
                getOrganisms: (params = {}) => apiClient.get('/diagnosis/organisms', { params }),
                getOrganismDetail: (id) => apiClient.get(`/diagnosis/organisms/${id}`),
                getSymptoms: (params = {}) => apiClient.get('/diagnosis/symptoms', { params }),
                diagnose: (symptoms) => apiClient.post('/diagnosis/diagnose', { symptoms }),
                getControlMethods: (params = {}) => apiClient.get('/diagnosis/control-methods', { params })
            },
            general: {
                getDashboardStats: () => apiClient.get('/data/dashboard-stats'),
                getRecentActivities: () => apiClient.get('/data/recent-activities')
            }
        };

        // Dashboard Component
        const Dashboard = {
            template: `
                <div>
                    <div v-if="alert" :class="'alert alert-' + alert.type">
                        {{ alert.message }}
                    </div>

                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="stat-number">{{ stats.total_provinces || 0 }}</div>
                            <div class="stat-label">Total Provinsi</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ stats.total_kecamatans || 0 }}</div>
                            <div class="stat-label">Total Kecamatan</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ totalVarieties }}</div>
                            <div class="stat-label">Total Varietas</div>
                        </div>
                        <div class="stat-card">
                            <div class="stat-number">{{ stats.total_organisms || 0 }}</div>
                            <div class="stat-label">Total OPT</div>
                        </div>
                    </div>

                    <div class="grid grid-2">
                        <div class="card">
                            <h3 style="margin-bottom: 20px;">Statistik Varietas</h3>
                            <div class="variety-stats">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                    <span>Kedelai:</span>
                                    <strong>{{ stats.total_varieties?.kedelai || 0 }}</strong>
                                </div>
                                <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                                    <span>Kacang Tanah:</span>
                                    <strong>{{ stats.total_varieties?.kacang_tanah || 0 }}</strong>
                                </div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span>Kacang Hijau:</span>
                                    <strong>{{ stats.total_varieties?.kacang_hijau || 0 }}</strong>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <h3 style="margin-bottom: 20px;">Aktivitas Terkini</h3>
                            <div v-if="recentActivities.length">
                                <div v-for="activity in recentActivities.slice(0, 5)" :key="activity.id" 
                                     style="padding: 12px 0; border-bottom: 1px solid #e2e8f0; last:border-bottom: none;">
                                    <div style="font-weight: 600; color: #2d3748;">
                                        {{ activity.user?.name }}
                                    </div>
                                    <div style="font-size: 14px; color: #718096; margin: 4px 0;">
                                        {{ activity.deskripsi.substring(0, 100) }}...
                                    </div>
                                    <div style="font-size: 12px; color: #a0aec0;">
                                        {{ formatDate(activity.created_at) }}
                                    </div>
                                </div>
                            </div>
                            <div v-else style="text-align: center; color: #718096; padding: 20px;">
                                Belum ada aktivitas
                            </div>
                        </div>
                    </div>
                </div>
            `,
            data() {
                return {
                    stats: {},
                    recentActivities: [],
                    alert: null
                };
            },
            computed: {
                totalVarieties() {
                    const varieties = this.stats.total_varieties || {};
                    return (varieties.kedelai || 0) + (varieties.kacang_tanah || 0) + (varieties.kacang_hijau || 0);
                }
            },
            async mounted() {
                try {
                    const [statsResponse, activitiesResponse] = await Promise.all([
                        api.general.getDashboardStats(),
                        api.general.getRecentActivities()
                    ]);
                    
                    this.stats = statsResponse.data.data;
                    this.recentActivities = activitiesResponse.data.data;
                } catch (error) {
                    this.alert = { type: 'error', message: 'Gagal memuat data dashboard' };
                }
            },
            methods: {
                formatDate(dateString) {
                    return new Date(dateString).toLocaleDateString('id-ID', {
                        year: 'numeric',
                        month: 'short',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            }
        };

        // Locations Component
        const Locations = {
            template: `
                <div>
                    <div class="search-box">
                        <i class="search-icon fas fa-search"></i>
                        <input 
                            type="text" 
                            class="search-input"
                            placeholder="Cari provinsi, kabupaten, atau kecamatan..."
                            v-model="searchQuery"
                            @input="searchLocations"
                        >
                    </div>

                    <div class="filters">
                        <select v-model="selectedProvinsi" @change="loadKabupatens" class="form-control" style="width: auto;">
                            <option value="">Semua Provinsi</option>
                            <option v-for="provinsi in provinces" :key="provinsi.id" :value="provinsi.id">
                                {{ provinsi.nama_provinsi }}
                            </option>
                        </select>
                        
                        <select v-model="selectedKabupaten" @change="loadKecamatans" class="form-control" style="width: auto;" :disabled="!selectedProvinsi">
                            <option value="">Semua Kabupaten</option>
                            <option v-for="kabupaten in kabupatens" :key="kabupaten.id" :value="kabupaten.id">
                                {{ kabupaten.nama_kabupaten }}
                            </option>
                        </select>
                    </div>

                    <div class="card">
                        <h3 style="margin-bottom: 20px;">Data Kecamatan</h3>
                        
                        <div v-if="loading" class="loading">
                            <div class="spinner"></div>
                        </div>
                        
                        <div v-else class="table-container">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Kecamatan</th>
                                        <th>Kabupaten</th>
                                        <th>Provinsi</th>
                                        <th>Komoditas Unggulan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="kecamatan in kecamatans" :key="kecamatan.id">
                                        <td>{{ kecamatan.nama_kecamatan }}</td>
                                        <td>{{ kecamatan.kabupaten?.nama_kabupaten }}</td>
                                        <td>{{ kecamatan.kabupaten?.provinsi?.nama_provinsi }}</td>
                                        <td>
                                            <div style="font-size: 12px;">
                                                <div v-if="kecamatan.komoditas_kedelai">Kedelai: {{ kecamatan.komoditas_kedelai.provitas }}</div>
                                                <div v-if="kecamatan.komoditas_kacang_tanah">K. Tanah: {{ kecamatan.komoditas_kacang_tanah.provitas }}</div>
                                                <div v-if="kecamatan.komoditas_kacang_hijau">K. Hijau: {{ kecamatan.komoditas_kacang_hijau.provitas }}</div>
                                            </div>
                                        </td>
                                        <td>
                                            <button @click="viewKecamatanDetail(kecamatan.id)" class="btn btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Detail Modal -->
                    <div v-if="showDetailModal" class="modal-overlay" @click="closeDetailModal">
                        <div class="modal-content" @click.stop>
                            <h3 style="margin-bottom: 20px;">Detail Kecamatan</h3>
                            <div v-if="selectedKecamatan">
                                <div class="grid grid-2">
                                    <div>
                                        <h4>Informasi Lokasi</h4>
                                        <p><strong>Nama:</strong> {{ selectedKecamatan.nama_kecamatan }}</p>
                                        <p><strong>Kabupaten:</strong> {{ selectedKecamatan.kabupaten?.nama_kabupaten }}</p>
                                        <p><strong>Provinsi:</strong> {{ selectedKecamatan.kabupaten?.provinsi?.nama_provinsi }}</p>
                                        <p><strong>Koordinat:</strong> {{ selectedKecamatan.latitude }}, {{ selectedKecamatan.longitude }}</p>
                                    </div>
                                    <div>
                                        <h4>Kondisi Tanah</h4>
                                        <p><strong>IP Lahan:</strong> {{ selectedKecamatan.ip_lahan }}%</p>
                                        <p><strong>Kadar P:</strong> {{ selectedKecamatan.kdr_p }} ppm</p>
                                        <p><strong>Kadar C:</strong> {{ selectedKecamatan.kdr_c }}%</p>
                                        <p><strong>Kadar K:</strong> {{ selectedKecamatan.kdr_k }} me/100g</p>
                                        <p><strong>KTK:</strong> {{ selectedKecamatan.ktk }} me/100g</p>
                                    </div>
                                </div>
                                
                                <div style="margin-top: 20px;">
                                    <button @click="closeDetailModal" class="btn btn-secondary">Tutup</button>
                                    <router-link :to="'/recommendations?kecamatan=' + selectedKecamatan.id" class="btn btn-primary" style="margin-left: 10px;">
                                        Lihat Rekomendasi
                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            data() {
                return {
                    searchQuery: '',
                    provinces: [],
                    kabupatens: [],
                    kecamatans: [],
                    selectedProvinsi: '',
                    selectedKabupaten: '',
                    selectedKecamatan: null,
                    showDetailModal: false,
                    loading: false
                };
            },
            async mounted() {
                await this.loadProvinces();
                await this.loadKecamatans();
            },
            methods: {
                async loadProvinces() {
                    try {
                        const response = await api.geographic.getProvinces();
                        this.provinces = response.data.data;
                    } catch (error) {
                        console.error('Failed to load provinces:', error);
                    }
                },
                
                async loadKabupatens() {
                    if (!this.selectedProvinsi) {
                        this.kabupatens = [];
                        this.selectedKabupaten = '';
                        await this.loadKecamatans();
                        return;
                    }
                    
                    try {
                        const response = await api.geographic.getKabupatens(this.selectedProvinsi);
                        this.kabupatens = response.data.data;
                        this.selectedKabupaten = '';
                        await this.loadKecamatans();
                    } catch (error) {
                        console.error('Failed to load kabupatens:', error);
                    }
                },
                
                async loadKecamatans() {
                    this.loading = true;
                    try {
                        const params = {};
                        if (this.selectedProvinsi) params.provinsi_id = this.selectedProvinsi;
                        if (this.selectedKabupaten) params.kabupaten_id = this.selectedKabupaten;
                        
                        const response = await api.geographic.getKecamatans(params);
                        this.kecamatans = response.data.data;
                    } catch (error) {
                        console.error('Failed to load kecamatans:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                async searchLocations() {
                    if (this.searchQuery.length < 2) {
                        await this.loadKecamatans();
                        return;
                    }
                    
                    this.loading = true;
                    try {
                        const response = await api.geographic.searchLocations(this.searchQuery);
                        // Filter to show only kecamatans in search results
                        const kecamatanResults = response.data.data.filter(item => item.type === 'kecamatan');
                        if (kecamatanResults.length > 0) {
                            // Get full kecamatan details
                            const kecamatanIds = kecamatanResults.map(item => item.id);
                            this.kecamatans = this.kecamatans.filter(k => kecamatanIds.includes(k.id));
                        }
                    } catch (error) {
                        console.error('Failed to search locations:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                async viewKecamatanDetail(id) {
                    try {
                        const response = await api.geographic.getKecamatanDetail(id);
                        this.selectedKecamatan = response.data.data;
                        this.showDetailModal = true;
                    } catch (error) {
                        console.error('Failed to load kecamatan detail:', error);
                    }
                },
                
                closeDetailModal() {
                    this.showDetailModal = false;
                    this.selectedKecamatan = null;
                }
            }
        };

        // Varieties Component
        const Varieties = {
            template: `
                <div>
                    <div class="filters">
                        <select v-model="selectedType" @change="loadVarieties" class="form-control" style="width: auto;">
                            <option value="kedelai">Kedelai</option>
                            <option value="kacang-tanah">Kacang Tanah</option>
                            <option value="kacang-hijau">Kacang Hijau</option>
                        </select>
                        
                        <input 
                            type="text" 
                            v-model="searchQuery"
                            @input="searchVarieties"
                            placeholder="Cari varietas..."
                            class="form-control"
                            style="width: 300px;"
                        >
                        
                        <button @click="showCompareModal = true" class="btn btn-primary" :disabled="selectedVarieties.length < 2">
                            <i class="fas fa-balance-scale"></i> Bandingkan ({{ selectedVarieties.length }})
                        </button>
                    </div>

                    <div class="card">
                        <h3 style="margin-bottom: 20px;">Varietas {{ selectedType.charAt(0).toUpperCase() + selectedType.slice(1) }}</h3>
                        
                        <div v-if="loading" class="loading">
                            <div class="spinner"></div>
                        </div>
                        
                        <div v-else class="grid grid-3">
                            <div v-for="variety in varieties" :key="variety.id" class="variety-card" @click="viewVarietyDetail(variety)">
                                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                                    <h4 style="margin: 0; color: #2d3748;">{{ variety.nama_varietas }}</h4>
                                    <input 
                                        type="checkbox" 
                                        :value="variety.id"
                                        v-model="selectedVarieties"
                                        @click.stop
                                        style="transform: scale(1.2);"
                                    >
                                </div>
                                
                                <div class="variety-info">
                                    <p><strong>Tahun:</strong> {{ variety.tahun }}</p>
                                    <p><strong>Potensi Hasil:</strong> {{ variety.potensi_hasil }} ton/ha</p>
                                    <p><strong>Rata-rata Hasil:</strong> {{ variety.rata_hasil }} ton/ha</p>
                                    <p><strong>Umur Masak:</strong> {{ variety.umur_masak }}</p>
                                    <div v-if="variety.organisme" style="margin-top: 8px;">
                                        <span class="badge badge-success">Tahan: {{ variety.organisme.nama_opt }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Modal -->
                    <div v-if="showDetailModal" class="modal-overlay" @click="closeDetailModal">
                        <div class="modal-content" @click.stop style="max-width: 800px;">
                            <h3 style="margin-bottom: 20px;">{{ selectedVariety?.nama_varietas }}</h3>
                            <div v-if="selectedVariety" class="grid grid-2">
                                <div>
                                    <h4>Informasi Dasar</h4>
                                    <p><strong>Tahun Pelepasan:</strong> {{ selectedVariety.tahun }}</p>
                                    <p><strong>SK:</strong> {{ selectedVariety.sk }}</p>
                                    <p><strong>Galur:</strong> {{ selectedVariety.galur }}</p>
                                    <p><strong>Asal:</strong> {{ selectedVariety.asal }}</p>
                                    <p><strong>Inventor:</strong> {{ selectedVariety.inventor }}</p>
                                </div>
                                <div>
                                    <h4>Karakteristik</h4>
                                    <p><strong>Potensi Hasil:</strong> {{ selectedVariety.potensi_hasil }} ton/ha</p>
                                    <p><strong>Rata-rata Hasil:</strong> {{ selectedVariety.rata_hasil }} ton/ha</p>
                                    <p><strong>Umur Berbunga:</strong> {{ selectedVariety.umur_berbunga }}</p>
                                    <p><strong>Umur Masak:</strong> {{ selectedVariety.umur_masak }}</p>
                                    <p><strong>Tinggi Tanaman:</strong> {{ selectedVariety.tinggi_tanaman }}</p>
                                </div>
                                <div>
                                    <h4>Kandungan Nutrisi</h4>
                                    <p><strong>Kadar Lemak:</strong> {{ selectedVariety.kadar_lemak }}%</p>
                                    <p><strong>Kadar Protein:</strong> {{ selectedVariety.kadar_protein }}%</p>
                                    <p><strong>Warna Biji:</strong> {{ selectedVariety.warna_biji }}</p>
                                    <p><strong>Bobot 100 Biji:</strong> {{ selectedVariety.bobot }}</p>
                                </div>
                                <div>
                                    <h4>Ketahanan OPT</h4>
                                    <p v-if="selectedVariety.organisme">
                                        <span class="badge badge-success">{{ selectedVariety.organisme.nama_opt }}</span>
                                    </p>
                                    <p v-else style="color: #718096;">Tidak ada data ketahanan</p>
                                </div>
                            </div>
                            
                            <div style="margin-top: 20px;">
                                <button @click="closeDetailModal" class="btn btn-secondary">Tutup</button>
                            </div>
                        </div>
                    </div>

                    <!-- Compare Modal -->
                    <div v-if="showCompareModal" class="modal-overlay" @click="closeCompareModal">
                        <div class="modal-content" @click.stop style="max-width: 1000px;">
                            <h3 style="margin-bottom: 20px;">Perbandingan Varietas</h3>
                            <div v-if="compareResults.length" class="table-container">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Karakteristik</th>
                                            <th v-for="result in compareResults" :key="result.data.id">{{ result.data.nama_varietas }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Tahun</strong></td>
                                            <td v-for="result in compareResults" :key="result.data.id">{{ result.data.tahun }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Potensi Hasil (ton/ha)</strong></td>
                                            <td v-for="result in compareResults" :key="result.data.id">{{ result.data.potensi_hasil }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Rata-rata Hasil (ton/ha)</strong></td>
                                            <td v-for="result in compareResults" :key="result.data.id">{{ result.data.rata_hasil }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Umur Masak</strong></td>
                                            <td v-for="result in compareResults" :key="result.data.id">{{ result.data.umur_masak }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kadar Protein (%)</strong></td>
                                            <td v-for="result in compareResults" :key="result.data.id">{{ result.data.kadar_protein }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Kadar Lemak (%)</strong></td>
                                            <td v-for="result in compareResults" :key="result.data.id">{{ result.data.kadar_lemak }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div style="margin-top: 20px;">
                                <button @click="closeCompareModal" class="btn btn-secondary">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            data() {
                return {
                    selectedType: 'kedelai',
                    varieties: [],
                    searchQuery: '',
                    selectedVarieties: [],
                    selectedVariety: null,
                    showDetailModal: false,
                    showCompareModal: false,
                    compareResults: [],
                    loading: false
                };
            },
            async mounted() {
                await this.loadVarieties();
            },
            methods: {
                async loadVarieties() {
                    this.loading = true;
                    try {
                        const apiMethod = {
                            'kedelai': api.variety.getKedelaiVarieties,
                            'kacang-tanah': api.variety.getKacangTanahVarieties,
                            'kacang-hijau': api.variety.getKacangHijauVarieties
                        }[this.selectedType];
                        
                        const response = await apiMethod({ search: this.searchQuery });
                        this.varieties = response.data.data;
                    } catch (error) {
                        console.error('Failed to load varieties:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                async searchVarieties() {
                    await this.loadVarieties();
                },
                
                viewVarietyDetail(variety) {
                    this.selectedVariety = variety;
                    this.showDetailModal = true;
                },
                
                closeDetailModal() {
                    this.showDetailModal = false;
                    this.selectedVariety = null;
                },
                
                async compareVarieties() {
                    if (this.selectedVarieties.length < 2) return;
                    
                    try {
                        const varieties = this.selectedVarieties.map(id => ({
                            type: this.selectedType,
                            id: id
                        }));
                        
                        const response = await api.variety.compareVarieties(varieties);
                        this.compareResults = response.data.data;
                        this.showCompareModal = true;
                    } catch (error) {
                        console.error('Failed to compare varieties:', error);
                    }
                },
                
                closeCompareModal() {
                    this.showCompareModal = false;
                    this.compareResults = [];
                }
            },
            watch: {
                selectedVarieties() {
                    if (this.selectedVarieties.length >= 2 && this.selectedVarieties.length <= 5) {
                        this.compareVarieties();
                    }
                }
            }
        };

        // Diagnosis Component
        const Diagnosis = {
            template: `
                <div>
                    <div class="grid grid-2">
                        <!-- Symptom Selection -->
                        <div class="card">
                            <h3 style="margin-bottom: 20px;">Pilih Gejala</h3>
                            <div v-for="(symptoms, part) in groupedSymptoms" :key="part" style="margin-bottom: 20px;">
                                <h4 style="color: #4a5568; margin-bottom: 12px; text-transform: capitalize;">{{ part }}</h4>
                                <div v-for="symptom in symptoms" :key="symptom.id" style="margin-bottom: 8px;">
                                    <label style="display: flex; align-items: start; cursor: pointer;">
                                        <input 
                                            type="checkbox" 
                                            :value="symptom.id" 
                                            v-model="selectedSymptoms"
                                            style="margin-right: 8px; margin-top: 4px;"
                                        >
                                        <span style="font-size: 14px;">{{ symptom.deskripsi }}</span>
                                    </label>
                                </div>
                            </div>
                            
                            <button 
                                @click="performDiagnosis" 
                                :disabled="selectedSymptoms.length === 0 || diagnosing"
                                class="btn btn-primary"
                                style="width: 100%; margin-top: 20px;"
                            >
                                <i class="fas fa-stethoscope"></i>
                                {{ diagnosing ? 'Mendiagnosis...' : 'Lakukan Diagnosis' }}
                            </button>
                        </div>

                        <!-- Diagnosis Results -->
                        <div class="card">
                            <h3 style="margin-bottom: 20px;">Hasil Diagnosis</h3>
                            
                            <div v-if="!diagnosisResults.length && selectedSymptoms.length === 0" style="text-align: center; color: #718096; padding: 40px;">
                                Pilih gejala yang diamati untuk melakukan diagnosis
                            </div>
                            
                            <div v-else-if="diagnosisResults.length">
                                <p style="margin-bottom: 20px; color: #4a5568;">
                                    Berdasarkan {{ totalSymptoms }} gejala yang dipilih:
                                </p>
                                
                                <div v-for="(result, index) in diagnosisResults" :key="index" 
                                     style="border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px; margin-bottom: 16px;">
                                    <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 12px;">
                                        <h4 style="color: #2d3748; margin: 0;">{{ result.organism.nama_opt }}</h4>
                                        <span class="badge badge-success">{{ result.score }}/{{ totalSymptoms }} cocok</span>
                                    </div>
                                    
                                    <p style="margin-bottom: 12px; color: #4a5568;">
                                        <strong>Jenis:</strong> {{ result.organism.jenis }}
                                    </p>
                                    
                                    <div style="margin-bottom: 12px;">
                                        <strong>Gejala yang cocok:</strong>
                                        <ul style="margin-top: 4px; padding-left: 20px;">
                                            <li v-for="symptom in result.matched_symptoms" :key="symptom.id" style="font-size: 14px; margin-bottom: 4px;">
                                                {{ symptom.deskripsi }}
                                            </li>
                                        </ul>
                                    </div>
                                    
                                    <button @click="viewOrganismDetail(result.organism.id)" class="btn btn-primary">
                                        <i class="fas fa-info-circle"></i> Detail & Pengendalian
                                    </button>
                                </div>
                            </div>
                            
                            <div v-else-if="selectedSymptoms.length && !diagnosing" style="text-align: center; color: #718096; padding: 40px;">
                                Tidak ditemukan OPT yang cocok dengan gejala yang dipilih
                            </div>
                        </div>
                    </div>

                    <!-- Organism Detail Modal -->
                    <div v-if="showOrganismModal" class="modal-overlay" @click="closeOrganismModal">
                        <div class="modal-content" @click.stop style="max-width: 800px;">
                            <h3 style="margin-bottom: 20px;">{{ selectedOrganism?.nama_opt }}</h3>
                            <div v-if="selectedOrganism">
                                <div class="grid grid-2">
                                    <div>
                                        <h4>Informasi OPT</h4>
                                        <p><strong>Jenis:</strong> {{ selectedOrganism.jenis }}</p>
                                        
                                        <h4 style="margin-top: 20px;">Gejala</h4>
                                        <ul v-if="selectedOrganism.gejala?.length">
                                            <li v-for="gejala in selectedOrganism.gejala" :key="gejala.id" style="margin-bottom: 8px;">
                                                <strong>{{ gejala.bagian_tanaman }}:</strong> {{ gejala.deskripsi }}
                                            </li>
                                        </ul>
                                        <p v-else style="color: #718096;">Tidak ada data gejala</p>
                                    </div>
                                    
                                    <div>
                                        <h4>Metode Pengendalian</h4>
                                        <div v-if="selectedOrganism.pengendalian?.length">
                                            <div v-for="pengendalian in selectedOrganism.pengendalian" :key="pengendalian.id" 
                                                 style="margin-bottom: 16px; padding: 12px; background: #f7fafc; border-radius: 8px;">
                                                <div style="display: flex; justify-content: between; align-items: center; margin-bottom: 8px;">
                                                    <span :class="'badge badge-' + getBadgeColor(pengendalian.jenis)">{{ pengendalian.jenis }}</span>
                                                </div>
                                                <p style="font-size: 14px; color: #4a5568;">{{ pengendalian.deskripsi }}</p>
                                                
                                                <div v-if="pengendalian.insektisida?.length" style="margin-top: 8px;">
                                                    <strong style="font-size: 12px;">Insektisida yang direkomendasikan:</strong>
                                                    <ul style="margin-top: 4px;">
                                                        <li v-for="insektisida in pengendalian.insektisida" :key="insektisida.id" 
                                                            style="font-size: 12px; margin-bottom: 2px;">
                                                            {{ insektisida.nama }} ({{ insektisida.bahan_aktif }})
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <p v-else style="color: #718096;">Tidak ada data pengendalian</p>
                                    </div>
                                </div>
                                
                                <div style="margin-top: 20px;">
                                    <button @click="closeOrganismModal" class="btn btn-secondary">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            data() {
                return {
                    symptoms: [],
                    selectedSymptoms: [],
                    diagnosisResults: [],
                    totalSymptoms: 0,
                    diagnosing: false,
                    selectedOrganism: null,
                    showOrganismModal: false
                };
            },
            computed: {
                groupedSymptoms() {
                    const grouped = {};
                    this.symptoms.forEach(symptom => {
                        const part = symptom.bagian_tanaman || 'lainnya';
                        if (!grouped[part]) grouped[part] = [];
                        grouped[part].push(symptom);
                    });
                    return grouped;
                }
            },
            async mounted() {
                await this.loadSymptoms();
            },
            methods: {
                async loadSymptoms() {
                    try {
                        const response = await api.diagnosis.getSymptoms();
                        this.symptoms = response.data.data.flat ? response.data.data : Object.values(response.data.data).flat();
                    } catch (error) {
                        console.error('Failed to load symptoms:', error);
                    }
                },
                
                async performDiagnosis() {
                    if (this.selectedSymptoms.length === 0) return;
                    
                    this.diagnosing = true;
                    try {
                        const response = await api.diagnosis.diagnose(this.selectedSymptoms);
                        this.totalSymptoms = response.data.data.total_symptoms;
                        this.diagnosisResults = Object.values(response.data.data.diagnosis_results);
                    } catch (error) {
                        console.error('Failed to perform diagnosis:', error);
                    } finally {
                        this.diagnosing = false;
                    }
                },
                
                async viewOrganismDetail(id) {
                    try {
                        const response = await api.diagnosis.getOrganismDetail(id);
                        this.selectedOrganism = response.data.data;
                        this.showOrganismModal = true;
                    } catch (error) {
                        console.error('Failed to load organism detail:', error);
                    }
                },
                
                closeOrganismModal() {
                    this.showOrganismModal = false;
                    this.selectedOrganism = null;
                },
                
                getBadgeColor(jenis) {
                    const colors = {
                        'Kultur teknis': 'success',
                        'Mekanis': 'warning',
                        'Kimiawi': 'danger',
                        'Hayati': 'success'
                    };
                    return colors[jenis] || 'secondary';
                }
            },
            watch: {
                selectedSymptoms() {
                    if (this.selectedSymptoms.length > 0) {
                        this.performDiagnosis();
                    } else {
                        this.diagnosisResults = [];
                    }
                }
            }
        };

        // Recommendations Component
        const Recommendations = {
            template: `
                <div>
                    <div class="card">
                        <h3 style="margin-bottom: 20px;">Rekomendasi Berdasarkan Lokasi</h3>
                        
                        <div class="search-box">
                            <i class="search-icon fas fa-map-marker-alt"></i>
                            <input 
                                type="text" 
                                class="search-input"
                                placeholder="Cari kecamatan..."
                                v-model="locationQuery"
                                @input="searchLocations"
                            >
                        </div>
                        
                        <div v-if="searchResults.length" style="max-height: 200px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
                            <div v-for="location in searchResults" :key="location.id" 
                                 @click="selectLocation(location)"
                                 style="padding: 12px; cursor: pointer; border-bottom: 1px solid #f7fafc;"
                                 :style="{ background: selectedKecamatan?.id === location.id ? '#f0f4f8' : '' }">
                                <strong>{{ location.name }}</strong>
                                <span style="color: #718096; font-size: 12px; margin-left: 8px;">({{ location.type }})</span>
                            </div>
                        </div>
                    </div>

                    <div v-if="recommendations && selectedKecamatan" class="grid grid-2">
                        <!-- Commodity Recommendations -->
                        <div class="card">
                            <h3 style="margin-bottom: 20px;">Komoditas Unggulan</h3>
                            
                            <div v-if="recommendations.kedelai" style="margin-bottom: 20px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                <h4 style="color: #2d3748; margin-bottom: 12px;">🫘 Kedelai</h4>
                                <p><strong>Produktivitas:</strong> {{ recommendations.kedelai.provitas }}</p>
                                <p><strong>Potensi Peningkatan:</strong> {{ recommendations.kedelai.pot_peningkatan_judgement }}/10</p>
                                <p><strong>Nilai Potensi:</strong> {{ recommendations.kedelai.nilai_potensi }}</p>
                            </div>
                            
                            <div v-if="recommendations.kacang_tanah" style="margin-bottom: 20px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                <h4 style="color: #2d3748; margin-bottom: 12px;">🥜 Kacang Tanah</h4>
                                <p><strong>Produktivitas:</strong> {{ recommendations.kacang_tanah.provitas }}</p>
                                <p><strong>Potensi Peningkatan:</strong> {{ recommendations.kacang_tanah.pot_peningkatan_judgement }}/10</p>
                                <p><strong>Nilai Potensi:</strong> {{ recommendations.kacang_tanah.nilai_potensi }}</p>
                            </div>
                            
                            <div v-if="recommendations.kacang_hijau" style="margin-bottom: 20px; padding: 16px; border: 1px solid #e2e8f0; border-radius: 8px;">
                                <h4 style="color: #2d3748; margin-bottom: 12px;">🫛 Kacang Hijau</h4>
                                <p><strong>Produktivitas:</strong> {{ recommendations.kacang_hijau.provitas }}</p>
                                <p><strong>Potensi Peningkatan:</strong> {{ recommendations.kacang_hijau.pot_peningkatan_judgement }}/10</p>
                                <p><strong>Nilai Potensi:</strong> {{ recommendations.kacang_hijau.nilai_potensi }}</p>
                            </div>
                        </div>

                        <!-- Planting Schedule & Conditions -->
                        <div class="card">
                            <h3 style="margin-bottom: 20px;">Jadwal Tanam & Kondisi</h3>
                            
                            <div style="margin-bottom: 24px;">
                                <h4 style="color: #2d3748; margin-bottom: 12px;">📅 Waktu Tanam Optimal</h4>
                                <div v-if="recommendations.planting_schedule">
                                    <p><strong>Kedelai:</strong> {{ formatMonths(recommendations.planting_schedule.kedelai) }}</p>
                                    <p><strong>Kacang Tanah:</strong> {{ formatMonths(recommendations.planting_schedule.kacang_tanah) }}</p>
                                    <p><strong>Kacang Hijau:</strong> {{ formatMonths(recommendations.planting_schedule.kacang_hijau) }}</p>
                                </div>
                            </div>
                            
                            <div style="margin-bottom: 24px;">
                                <h4 style="color: #2d3748; margin-bottom: 12px;">🌧️ Pola Cuaca</h4>
                                <div v-if="recommendations.weather_pattern">
                                    <p><strong>Bulan Hujan:</strong> {{ formatMonths(recommendations.weather_pattern.rainy_months) }}</p>
                                    <p><strong>Bulan Kering:</strong> {{ formatMonths(recommendations.weather_pattern.dry_months) }}</p>
                                </div>
                            </div>
                            
                            <div>
                                <h4 style="color: #2d3748; margin-bottom: 12px;">🌱 Kondisi Tanah</h4>
                                <div v-if="recommendations.soil_condition">
                                    <p><strong>IP Lahan:</strong> {{ recommendations.soil_condition.ip_lahan }}%</p>
                                    <p><strong>Kadar P:</strong> {{ recommendations.soil_condition.kdr_p }} ppm</p>
                                    <p><strong>Kadar C:</strong> {{ recommendations.soil_condition.kdr_c }}%</p>
                                    <p><strong>Kadar K:</strong> {{ recommendations.soil_condition.kdr_k }} me/100g</p>
                                    <p><strong>KTK:</strong> {{ recommendations.soil_condition.ktk }} me/100g</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-else-if="!selectedKecamatan" class="card" style="text-align: center; color: #718096; padding: 40px;">
                        <i class="fas fa-map-marker-alt" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                        <h3>Pilih Lokasi untuk Mendapatkan Rekomendasi</h3>
                        <p>Cari dan pilih kecamatan untuk melihat rekomendasi komoditas dan jadwal tanam</p>
                    </div>
                </div>
            `,
            data() {
                return {
                    locationQuery: '',
                    searchResults: [],
                    selectedKecamatan: null,
                    recommendations: null,
                    loading: false
                };
            },
            mounted() {
                // Check if kecamatan ID is provided in query params
                const kecamatanId = this.$route.query.kecamatan;
                if (kecamatanId) {
                    this.loadRecommendations(kecamatanId);
                }
            },
            methods: {
                async searchLocations() {
                    if (this.locationQuery.length < 2) {
                        this.searchResults = [];
                        return;
                    }
                    
                    try {
                        const response = await api.geographic.searchLocations(this.locationQuery);
                        this.searchResults = response.data.data.filter(item => item.type === 'kecamatan');
                    } catch (error) {
                        console.error('Failed to search locations:', error);
                    }
                },
                
                async selectLocation(location) {
                    this.selectedKecamatan = location;
                    this.locationQuery = location.name;
                    this.searchResults = [];
                    await this.loadRecommendations(location.id);
                },
                
                async loadRecommendations(kecamatanId) {
                    this.loading = true;
                    try {
                        const response = await api.commodity.getRecommendations(kecamatanId);
                        this.recommendations = response.data.data;
                    } catch (error) {
                        console.error('Failed to load recommendations:', error);
                    } finally {
                        this.loading = false;
                    }
                },
                
                formatMonths(months) {
                    if (!months || !Array.isArray(months) || months.length === 0) {
                        return 'Tidak ada data';
                    }
                    return months.join(', ');
                }
            }
        };

        // Reports Component (simplified version)
        const Reports = {
            template: `
                <div>
                    <div class="card">
                        <h3 style="margin-bottom: 20px;">Laporan Deteksi</h3>
                        <div style="text-align: center; color: #718096; padding: 40px;">
                            <i class="fas fa-file-alt" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                            <h3>Fitur Laporan</h3>
                            <p>Fitur ini memerlukan autentikasi pengguna. Silakan login untuk mengakses laporan deteksi Anda.</p>
                            <button class="btn btn-primary" style="margin-top: 20px;">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </button>
                        </div>
                    </div>
                </div>
            `,
            data() {
                return {};
            }
        };

        // Router Configuration
        const { createRouter, createWebHashHistory } = VueRouter;

        const routes = [
            { path: '/', component: Dashboard, name: 'dashboard' },
            { path: '/locations', component: Locations, name: 'locations' },
            { path: '/varieties', component: Varieties, name: 'varieties' },
            { path: '/diagnosis', component: Diagnosis, name: 'diagnosis' },
            { path: '/recommendations', component: Recommendations, name: 'recommendations' },
            { path: '/reports', component: Reports, name: 'reports' }
        ];

        const router = createRouter({
            history: createWebHashHistory(),
            routes
        });

        // Main Vue Application
        const { createApp } = Vue;

        const app = createApp({
            data() {
                return {
                    sidebarCollapsed: false,
                    sidebarOpen: false,
                    loading: false
                };
            },
            computed: {
                currentPageTitle() {
                    const routeTitles = {
                        'dashboard': 'Dashboard',
                        'locations': 'Data Lokasi',
                        'varieties': 'Varietas Tanaman',
                        'diagnosis': 'Diagnosis OPT',
                        'recommendations': 'Rekomendasi',
                        'reports': 'Laporan Deteksi'
                    };
                    return routeTitles[this.$route.name] || 'Sistem Manajemen Pertanian';
                }
            },
            methods: {
                toggleSidebar() {
                    if (window.innerWidth <= 768) {
                        this.sidebarOpen = !this.sidebarOpen;
                    } else {
                        this.sidebarCollapsed = !this.sidebarCollapsed;
                    }
                }
            },
            mounted() {
                // Handle responsive sidebar
                const handleResize = () => {
                    if (window.innerWidth > 768) {
                        this.sidebarOpen = false;
                    }
                };
                window.addEventListener('resize', handleResize);
                
                // Close mobile sidebar when clicking outside
                document.addEventListener('click', (e) => {
                    const sidebar = document.querySelector('.sidebar');
                    const toggleBtn = document.querySelector('.toggle-btn');
                    if (this.sidebarOpen && sidebar && !sidebar.contains(e.target) && !toggleBtn.contains(e.target)) {
                        this.sidebarOpen = false;
                    }
                });
            }
        });

        app.use(router);
        app.mount('#app');
    </script>
</body>
</html>