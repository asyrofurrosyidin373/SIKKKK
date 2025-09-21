<?php

// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TabProvinsi;
use App\Models\TabKabupaten;
use App\Models\TabKecamatan;
use App\Models\HamaPenyakit;
use App\Models\Gejala;
use App\Models\DeteksiHistory;
use App\Models\VarietasKedelai;
use App\Models\VarietasKacangTanah;
use App\Models\VarietasKacangHijau;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $cacheKey = 'home_stats';
            
            $stats = Cache::remember($cacheKey, 3600, function() {
                return [
                    'total_provinsi' => TabProvinsi::count(),
                    'total_kabupaten' => TabKabupaten::count(),
                    'total_kecamatan' => TabKecamatan::count(),
                    'total_diseases' => HamaPenyakit::active()->count(),
                    'total_symptoms' => Gejala::active()->count(),
                    'total_varieties' => VarietasKedelai::count() + VarietasKacangTanah::count() + VarietasKacangHijau::count(),
                    'recent_detections' => DeteksiHistory::today()->count(),
                    'high_priority_diseases' => HamaPenyakit::active()->where('priority', '>=', 8)->count(),
                    'severe_symptoms' => Gejala::active()->where('severity_score', '>=', 8)->count(),
                ];
            });
            
            // Get recent detections for activity feed
            $recentDetections = Cache::remember('recent_detections', 1800, function() {
                return DeteksiHistory::with(['getDetectionResults'])
                    ->orderBy('detected_at', 'desc')
                    ->limit(5)
                    ->get();
            });
            
            // Get top diseases
            $topDiseases = Cache::remember('top_diseases', 3600, function() {
                return HamaPenyakit::active()
                    ->where('priority', '>=', 7)
                    ->orderBy('priority', 'desc')
                    ->limit(6)
                    ->get();
            });
            
            return view('home.index', compact('stats', 'recentDetections', 'topDiseases'));
        } catch (\Exception $e) {
            Log::error('Error in HomeController@index: ' . $e->getMessage());
            return view('home.index', [
                'stats' => [],
                'recentDetections' => collect(),
                'topDiseases' => collect()
            ]);
        }
    }
    
    public function peta()
    {
        try {
            $cacheKey = 'peta_data';
            
            $data = Cache::remember($cacheKey, 7200, function() {
                return [
                    'provinsi' => TabProvinsi::select('id', 'nama_provinsi', 'kode_provinsi')
                        ->orderBy('nama_provinsi')
                        ->get(),
                    'stats' => [
                        'total_kecamatan' => TabKecamatan::count(),
                        'with_coordinates' => TabKecamatan::whereNotNull('latitude')
                            ->whereNotNull('longitude')->count(),
                        'with_komoditas' => TabKecamatan::whereHas('komoditasKedelai')
                            ->orWhereHas('komoditasKacangTanah')
                            ->orWhereHas('komoditasKacangHijau')
                            ->count()
                    ]
                ];
            });
            
            return view('home.peta', $data);
        } catch (\Exception $e) {
            Log::error('Error in HomeController@peta: ' . $e->getMessage());
            return view('home.peta', [
                'provinsi' => collect(),
                'stats' => []
            ]);
        }
    }
    
    public function getMapData(Request $request)
    {
        try {
            $cacheKey = 'map_data_' . md5(serialize($request->all()));
            
            $data = Cache::remember($cacheKey, 3600, function() use ($request) {
                $query = TabKecamatan::select(
                    'id', 'nama_kecamatan', 'latitude', 'longitude', 
                    'tab_kabupaten_id'
                )
                ->with([
                    'kabupaten:id,nama_kabupaten,tab_provinsi_id',
                    'kabupaten.provinsi:id,nama_provinsi'
                ]);
                
                if ($request->provinsi_id) {
                    $query->whereHas('kabupaten.provinsi', function($q) use ($request) {
                        $q->where('id', $request->provinsi_id);
                    });
                }
                
                if ($request->kabupaten_id) {
                    $query->where('tab_kabupaten_id', $request->kabupaten_id);
                }
                
                if ($request->has_coordinates) {
                    $query->whereNotNull('latitude')->whereNotNull('longitude');
                }
                
                return $query->get()->map(function($item) {
                    // Add basic computed properties
                    $item->has_coordinates = !is_null($item->latitude) && !is_null($item->longitude);
                    $item->provinsi_nama = $item->kabupaten->provinsi->nama_provinsi ?? null;
                    $item->kabupaten_nama = $item->kabupaten->nama_kabupaten ?? null;
                    
                    // Add variety counts (simplified)
                    $item->variety_counts = [
                        'kedelai' => 0,
                        'kacang_tanah' => 0,
                        'kacang_hijau' => 0
                    ];
                    
                    return $item;
                });
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'metadata' => [
                    'total' => $data->count(),
                    'with_coordinates' => $data->whereNotNull('latitude')->count(),
                    'filters_applied' => $request->only(['provinsi_id', 'kabupaten_id', 'has_coordinates'])
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getMapData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data peta',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function search(Request $request)
    {
        // Global search implementation
        $query = $request->get('q');
        
        return view('search.results', compact('query'));
    }
}
