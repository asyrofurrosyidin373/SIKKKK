<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TabProvinsi;
use App\Models\TabKabupaten;
use App\Models\TabKecamatan;
use App\Models\KomKedelai;
use App\Models\KomKacangTanah;
use App\Models\KomKacangHijau;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DebugController extends Controller
{
    public function testMapData(Request $request)
    {
        try {
            $debug = [];
            
            // Test basic data
            $debug['database_counts'] = [
                'provinsi' => TabProvinsi::count(),
                'kabupaten' => TabKabupaten::count(),
                'kecamatan' => TabKecamatan::count(),
                'kecamatan_with_coords' => TabKecamatan::whereNotNull('latitude')->whereNotNull('longitude')->count(),
                'kedelai' => KomKedelai::count(),
                'kacang_tanah' => KomKacangTanah::count(),
                'kacang_hijau' => KomKacangHijau::count(),
            ];
            
            // Test request parameters
            $debug['request_params'] = $request->all();
            
            // Test simple query without relations
            $simpleQuery = TabKecamatan::select('id', 'nama_kecamatan', 'latitude', 'longitude', 'tab_kabupaten_id')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->limit(5);
            
            $provinsiId = $request->input('provinsi');
            $kabupatenId = $request->input('kabupaten');
            $kecamatanId = $request->input('kecamatan');
            
            if ($provinsiId) {
                $simpleQuery->whereHas('kabupaten', function($q) use ($provinsiId) {
                    $q->where('tab_provinsi_id', $provinsiId);
                });
            }
            
            if ($kabupatenId) {
                $simpleQuery->where('tab_kabupaten_id', $kabupatenId);
            }
            
            if ($kecamatanId) {
                $simpleQuery->where('id', $kecamatanId);
            }
            
            $debug['simple_query_sql'] = $simpleQuery->toSql();
            $debug['simple_query_bindings'] = $simpleQuery->getBindings();
            
            $simpleResults = $simpleQuery->get();
            $debug['simple_results_count'] = $simpleResults->count();
            $debug['simple_results_sample'] = $simpleResults->take(3)->toArray();
            
            // Test with relations
            try {
                $complexQuery = TabKecamatan::select('id', 'nama_kecamatan', 'latitude', 'longitude', 'tab_kabupaten_id')
                    ->with([
                        'kabupaten:id,nama_kabupaten,tab_provinsi_id',
                        'kabupaten.provinsi:id,nama_provinsi'
                    ])
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->limit(3);
                
                if ($provinsiId) {
                    $complexQuery->whereHas('kabupaten', function($q) use ($provinsiId) {
                        $q->where('tab_provinsi_id', $provinsiId);
                    });
                }
                
                if ($kabupatenId) {
                    $complexQuery->where('tab_kabupaten_id', $kabupatenId);
                }
                
                $complexResults = $complexQuery->get();
                $debug['complex_results_count'] = $complexResults->count();
                $debug['complex_results_sample'] = $complexResults->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama_kecamatan' => $item->nama_kecamatan,
                        'latitude' => $item->latitude,
                        'longitude' => $item->longitude,
                        'kabupaten_nama' => $item->kabupaten->nama_kabupaten ?? 'N/A',
                        'provinsi_nama' => $item->kabupaten->provinsi->nama_provinsi ?? 'N/A',
                    ];
                })->toArray();
                
            } catch (\Exception $e) {
                $debug['complex_query_error'] = $e->getMessage();
            }
            
            // Test specific data for Malang
            if ($kabupatenId === '3507' || !$kabupatenId) {
                $malangData = TabKecamatan::where('tab_kabupaten_id', '3507')
                    ->with('kabupaten')
                    ->get();
                    
                $debug['malang_data'] = [
                    'count' => $malangData->count(),
                    'kecamatan' => $malangData->map(function($item) {
                        return [
                            'id' => $item->id,
                            'nama' => $item->nama_kecamatan,
                            'coords' => [$item->latitude, $item->longitude],
                            'kabupaten' => $item->kabupaten->nama_kabupaten ?? 'N/A'
                        ];
                    })->toArray()
                ];
            }
            
            return response()->json([
                'success' => true,
                'debug' => $debug
            ]);
            
        } catch (\Exception $e) {
            Log::error('Debug test error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null
            ], 500);
        }
    }
    
    public function simpleMapData(Request $request)
    {
        try {
            $provinsiId = $request->input('provinsi');
            $kabupatenId = $request->input('kabupaten');
            $kecamatanId = $request->input('kecamatan');
            
            $query = TabKecamatan::select(
                'id', 'nama_kecamatan', 'latitude', 'longitude', 
                'tab_kabupaten_id', 'ip_lahan', 'kdr_p', 'kdr_c', 'kdr_k', 'ktk',
                'jenis_komoditas', 'provitas', 'luas_tanam', 'produktivitas', 'total_produksi',
                'opt_id', 'varietas_id', 'pot_peningkatan_judgement', 'nilai_potensi',
                'rekomendasi_waktu_tanam_kedelai', 'rekomendasi_waktu_tanam_kacang_tanah', 'rekomendasi_waktu_tanam_kacang_hijau',
                'bulan_hujan', 'bulan_kering'
            )
            ->with([
                'kabupaten:id,nama_kabupaten,tab_provinsi_id',
                'kabupaten.provinsi:id,nama_provinsi'
            ])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');
            
            // Apply filters
            if ($provinsiId) {
                $query->whereHas('kabupaten', function($q) use ($provinsiId) {
                    $q->where('tab_provinsi_id', $provinsiId);
                });
            }
            
            if ($kabupatenId) {
                $query->where('tab_kabupaten_id', $kabupatenId);
            }
            
            if ($kecamatanId) {
                $query->where('id', $kecamatanId);
            }
            
            $data = $query->get()->map(function($item) {
                return [
                    'id' => $item->id,
                    'nama_kecamatan' => $item->nama_kecamatan,
                    'latitude' => (float) $item->latitude,
                    'longitude' => (float) $item->longitude,
                    'kabupaten_nama' => $item->kabupaten->nama_kabupaten ?? 'N/A',
                    'provinsi_nama' => $item->kabupaten->provinsi->nama_provinsi ?? 'N/A',
                    
                    // Data Tanah
                    'ip_lahan' => $item->ip_lahan,
                    'kdr_p' => $item->kdr_p,
                    'kdr_c' => $item->kdr_c,
                    'kdr_k' => $item->kdr_k,
                    'ktk' => $item->ktk,
                    
                    // Komoditas dengan struktur baru
                    'jenis_komoditas' => $item->jenis_komoditas,
                    'nama_komoditas' => $item->nama_komoditas,
                    'nama_varietas' => $item->nama_varietas,
                    'luas_tanam' => $item->luas_tanam,
                    'produktivitas' => $item->produktivitas,
                    'total_produksi' => $item->total_produksi,
                    'provitas' => $item->provitas,
                    'nilai_potensi' => $item->nilai_potensi,
                    
                    // Backward compatibility
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
                    
                    'has_coordinates' => true,
                ];
            });
            
            $metadata = [
                'total' => $data->count(),
                'with_coordinates' => $data->count(), // All have coordinates in this query
                'filters_applied' => compact('provinsiId', 'kabupatenId', 'kecamatanId')
            ];
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'metadata' => $metadata
            ]);
            
        } catch (\Exception $e) {
            Log::error('Simple map data error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data peta: ' . $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
