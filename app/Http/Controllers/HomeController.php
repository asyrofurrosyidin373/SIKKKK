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
            // Load fresh data to avoid stale cache issues for dropdowns
            $provinsi = TabProvinsi::select('id', 'nama_provinsi')
                ->orderBy('nama_provinsi')
                ->get();

            $stats = [
                'total_kecamatan' => TabKecamatan::count(),
                'with_coordinates' => TabKecamatan::withCoordinates()->count(),
                'with_komoditas' => TabKecamatan::whereNotNull('jenis_komoditas')->count(),
                'with_production' => TabKecamatan::withProduction()->count()
            ];
            
            return view('home.peta', compact('provinsi', 'stats'));
        } catch (\Exception $e) {
            Log::error('Error in HomeController@peta: ' . $e->getMessage());
            return view('home.peta', [
                'provinsi' => collect(),
                'stats' => []
            ]);
        }
    }

    public function hasil(Request $request)
    {
        try {
            // Load provinces for filter dropdown
            $provinsi = TabProvinsi::select('id', 'nama_provinsi')
                ->orderBy('nama_provinsi')
                ->get();

            // Get filter parameters from request
            $filters = [
                'provinsi' => $request->input('provinsi'),
                'kabupaten' => $request->input('kabupaten'),
                'kecamatan' => $request->input('kecamatan')
            ];

            // Load kabupaten if provinsi is selected
            $kabupaten = collect();
            if ($filters['provinsi']) {
                $kabupaten = TabKabupaten::where('tab_provinsi_id', $filters['provinsi'])
                    ->select('id', 'nama_kabupaten')
                    ->orderBy('nama_kabupaten')
                    ->get();
            }

            // Load kecamatan if kabupaten is selected
            $kecamatan = collect();
            if ($filters['kabupaten']) {
                $kecamatan = TabKecamatan::where('tab_kabupaten_id', $filters['kabupaten'])
                    ->select('id', 'nama_kecamatan')
                    ->orderBy('nama_kecamatan')
                    ->get();
            }

            // Get basic statistics dengan struktur baru
            $stats = [
                'total_kecamatan' => TabKecamatan::count(),
                'with_coordinates' => TabKecamatan::whereNotNull('latitude')
                    ->whereNotNull('longitude')->count(),
                'with_production' => TabKecamatan::whereNotNull('jenis_komoditas')
                    ->whereNotNull('luas_tanam')
                    ->whereNotNull('total_produksi')->count(),
                'provinces_count' => TabProvinsi::count(),
                'regencies_count' => TabKabupaten::count(),
                'kedelai_count' => TabKecamatan::where('jenis_komoditas', 'kedelai')->count(),
                'kacang_tanah_count' => TabKecamatan::where('jenis_komoditas', 'kacang_tanah')->count(),
                'kacang_hijau_count' => TabKecamatan::where('jenis_komoditas', 'kacang_hijau')->count()
            ];
            
            return view('home.hasil', compact('provinsi', 'kabupaten', 'kecamatan', 'stats', 'filters'));
        } catch (\Exception $e) {
            Log::error('Error in HomeController@hasil: ' . $e->getMessage());
            return view('home.hasil', [
                'provinsi' => collect(),
                'stats' => []
            ])->with('error', 'Terjadi kesalahan saat memuat halaman');
        }
    }
    
    public function getMapData(Request $request)
    {
        try {
            $cacheKey = 'map_data_' . md5(serialize($request->all()));
            
            $data = Cache::remember($cacheKey, 3600, function() use ($request) {
                // Resolve incoming params from either *_id or bare names
                $provinsiId = $request->input('provinsi') ?? $request->input('provinsi_id');
                $kabupatenId = $request->input('kabupaten') ?? $request->input('kabupaten_id');
                $kecamatanId = $request->input('kecamatan') ?? $request->input('kecamatan_id');

                $query = TabKecamatan::select(
                    'id', 'nama_kecamatan', 'latitude', 'longitude', 
                    'tab_kabupaten_id', 'ip_lahan', 'kdr_p', 'kdr_c', 'kdr_k', 'ktk',
                    'jenis_komoditas', 'provitas', 'luas_tanam', 'produktivitas', 'total_produksi',
                    'opt_id', 'varietas_id', 'pot_peningkatan_judgement', 'nilai_potensi',
                    'rekomendasi_waktu_tanam_kedelai', 'rekomendasi_waktu_tanam_kacang_tanah', 
                    'rekomendasi_waktu_tanam_kacang_hijau', 'bulan_hujan', 'bulan_kering'
                )
                ->with([
                    'kabupaten:id,nama_kabupaten,tab_provinsi_id',
                    'kabupaten.provinsi:id,nama_provinsi'
                ]);
                
                // Filter by provinsi
                if ($provinsiId) {
                    $query->whereHas('kabupaten.provinsi', function($q) use ($provinsiId) {
                        $q->where('id', $provinsiId);
                    });
                }
                
                // Filter by kabupaten
                if ($kabupatenId) {
                    $query->where('tab_kabupaten_id', $kabupatenId);
                }
                
                // Filter by kecamatan
                if ($kecamatanId) {
                    $query->where('id', $kecamatanId);
                }
                
                // Filter by coordinates
                if ($request->has_coordinates) {
                    $query->withCoordinates();
                }
                
                // Filter by production data
                if ($request->has_production) {
                    $query->withProduction();
                }
                
                // Filter by komoditas
                $komoditas = $request->get('komoditas', []);
                if (!empty($komoditas)) {
                    $query->whereIn('jenis_komoditas', $komoditas);
                }
                
                // Filter by luas tanam
                if ($request->luas_min) {
                    $query->where('luas_tanam', '>=', $request->luas_min);
                }
                if ($request->luas_max) {
                    $query->where('luas_tanam', '<=', $request->luas_max);
                }
                
                // Filter by produktivitas
                if ($request->produktivitas_min) {
                    $query->where('produktivitas', '>=', $request->produktivitas_min);
                }
                if ($request->produktivitas_max) {
                    $query->where('produktivitas', '<=', $request->produktivitas_max);
                }
                
                return $query->get()->map(function($item) {
                    // Add basic computed properties
                    $item->has_coordinates = !is_null($item->latitude) && !is_null($item->longitude);
                    $item->provinsi_nama = $item->kabupaten->provinsi->nama_provinsi ?? null;
                    $item->kabupaten_nama = $item->kabupaten->nama_kabupaten ?? null;
                    
                    // Add komoditas data dengan struktur baru
                    $item->nama_komoditas = $item->nama_komoditas;
                    $item->nama_varietas = $item->nama_varietas;
                    $item->detail_varietas = $item->detail_varietas;
                    $item->rekomendasi_waktu_tanam = $item->rekomendasi_waktu_tanam;
                    
                    // Add komoditas flags untuk backward compatibility
                    $item->komoditas_kedelai = $item->jenis_komoditas === 'kedelai';
                    $item->komoditas_kacang_tanah = $item->jenis_komoditas === 'kacang_tanah';
                    $item->komoditas_kacang_hijau = $item->jenis_komoditas === 'kacang_hijau';
                    
                    // Total production langsung dari field
                    $item->total_production = $item->total_produksi ?? 0;
                    
                    // Add backward compatibility fields
                    if ($item->jenis_komoditas === 'kedelai') {
                        $item->komKedelai = (object) [
                            'luas_tanam' => $item->luas_tanam,
                            'produktivitas' => $item->produktivitas,
                            'total_produksi' => $item->total_produksi
                        ];
                        $item->komKacangTanah = null;
                        $item->komKacangHijau = null;
                    } elseif ($item->jenis_komoditas === 'kacang_tanah') {
                        $item->komKacangTanah = (object) [
                            'luas_tanam' => $item->luas_tanam,
                            'produktivitas' => $item->produktivitas,
                            'total_produksi' => $item->total_produksi
                        ];
                        $item->komKedelai = null;
                        $item->komKacangHijau = null;
                    } elseif ($item->jenis_komoditas === 'kacang_hijau') {
                        $item->komKacangHijau = (object) [
                            'luas_tanam' => $item->luas_tanam,
                            'produktivitas' => $item->produktivitas,
                            'total_produksi' => $item->total_produksi
                        ];
                        $item->komKedelai = null;
                        $item->komKacangTanah = null;
                    }
                    
                    return $item;
                });
            });

            // Calculate metadata
            $metadata = [
                'total' => $data->count(),
                'with_coordinates' => $data->where('has_coordinates', true)->count(),
                'with_production' => $data->where('total_production', '>', 0)->count(),
                'filters_applied' => $request->only([
                    'provinsi', 'kabupaten', 'kecamatan', 'has_coordinates', 
                    'has_production', 'komoditas', 'luas_min', 'luas_max', 
                    'produktivitas_min', 'produktivitas_max'
                ])
            ];

            return response()->json([
                'success' => true,
                'data' => $data,
                'metadata' => $metadata
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getMapData: ' . $e->getMessage());
            Log::error('Request params: ' . json_encode($request->all()));
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data peta',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    public function exportKecamatan(Request $request)
    {
        try {
            // Get filtered data using the same logic as getMapData
            $cacheKey = 'export_data_' . md5(serialize($request->all()));
            
            $data = Cache::remember($cacheKey, 300, function() use ($request) {
                $provinsiId = $request->input('provinsi') ?? $request->input('provinsi_id');
                $kabupatenId = $request->input('kabupaten') ?? $request->input('kabupaten_id');
                $kecamatanId = $request->input('kecamatan') ?? $request->input('kecamatan_id');

                $query = TabKecamatan::select(
                    'id', 'nama_kecamatan', 'latitude', 'longitude', 
                    'tab_kabupaten_id', 'ip_lahan', 'kdr_p', 'kdr_c', 'kdr_k', 'ktk',
                    'jenis_komoditas', 'provitas', 'luas_tanam', 'produktivitas', 'total_produksi',
                    'opt_id', 'varietas_id', 'pot_peningkatan_judgement', 'nilai_potensi'
                )
                ->with([
                    'kabupaten:id,nama_kabupaten,tab_provinsi_id',
                    'kabupaten.provinsi:id,nama_provinsi'
                ]);
                
                // Apply same filters as getMapData
                if ($provinsiId) {
                    $query->whereHas('kabupaten.provinsi', function($q) use ($provinsiId) {
                        $q->where('id', $provinsiId);
                    });
                }
                
                if ($kabupatenId) {
                    $query->where('tab_kabupaten_id', $kabupatenId);
                }
                
                if ($kecamatanId) {
                    $query->where('id', $kecamatanId);
                }

                // Filter by coordinates if requested
                if ($request->has_coordinates) {
                    $query->withCoordinates();
                }
                
                // Filter by production data if requested
                if ($request->has_production) {
                    $query->withProduction();
                }

                // Filter by specific komoditas
                $komoditas = $request->get('komoditas', []);
                if (!empty($komoditas)) {
                    $query->whereIn('jenis_komoditas', $komoditas);
                }
                
                return $query->get();
            });

            // Prepare data for CSV export
            $exportData = [];
            $exportData[] = [
                'Kecamatan',
                'Kabupaten', 
                'Provinsi',
                'Latitude',
                'Longitude',
                'IP Lahan',
                'Kadar P',
                'Kadar C', 
                'Kadar K',
                'KTK',
                'Jenis Komoditas',
                'Nama Komoditas',
                'Nama Varietas',
                'Luas Tanam (ha)',
                'Produktivitas (ton/ha)',
                'Total Produksi (ton)',
                'Provitas',
                'Nilai Potensi'
            ];

            foreach ($data as $item) {
                $exportData[] = [
                    $item->nama_kecamatan,
                    $item->kabupaten->nama_kabupaten ?? '',
                    $item->kabupaten->provinsi->nama_provinsi ?? '',
                    $item->latitude ?? '',
                    $item->longitude ?? '',
                    $item->ip_lahan ?? '',
                    $item->kdr_p ?? '',
                    $item->kdr_c ?? '',
                    $item->kdr_k ?? '',
                    $item->ktk ?? '',
                    $item->jenis_komoditas ?? '',
                    $item->nama_komoditas ?? '',
                    $item->nama_varietas ?? '',
                    $item->luas_tanam ?? 0,
                    $item->produktivitas ?? 0,
                    $item->total_produksi ?? 0,
                    $item->provitas ?? 0,
                    $item->nilai_potensi ?? 0,
                ];
            }

            $filename = 'data_kecamatan_' . date('Y-m-d_H-i-s') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Pragma' => 'no-cache',
                'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
                'Expires' => '0'
            ];

            $callback = function() use ($exportData) {
                $file = fopen('php://output', 'w');
                
                // Add BOM for UTF-8
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                
                foreach ($exportData as $row) {
                    fputcsv($file, $row, ';'); // Use semicolon for better Excel compatibility
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        } catch (\Exception $e) {
            Log::error('Error in exportKecamatan: ' . $e->getMessage());
            Log::error('Request params: ' . json_encode($request->all()));
            return response()->json([
                'error' => 'Gagal mengexport data',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
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