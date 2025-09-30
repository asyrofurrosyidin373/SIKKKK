<?php

// app/Http/Controllers/RegionController.php
namespace App\Http\Controllers;

use App\Models\TabKabupaten;
use App\Models\TabKecamatan;
use App\Models\TabProvinsi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RegionController extends Controller
{
    /**
     * Get kabupaten by provinsi ID
     */
    public function getKabupaten($provinsiId)
    {
        try {
            $cacheKey = "kabupaten_provinsi_{$provinsiId}";
            
            $kabupaten = Cache::remember($cacheKey, 3600, function() use ($provinsiId) {
                return TabKabupaten::where('tab_provinsi_id', $provinsiId)
                    ->orderBy('nama_kabupaten')
                    ->get(['id', 'nama_kabupaten']);
            });

            return response()->json($kabupaten);
        } catch (\Exception $e) {
            Log::error('Error in getKabupaten: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal mengambil data kabupaten',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get kecamatan by kabupaten ID
     */
    public function getKecamatan($kabupatenId)
    {
        try {
            $cacheKey = "kecamatan_kabupaten_{$kabupatenId}";
            
            $kecamatan = Cache::remember($cacheKey, 3600, function() use ($kabupatenId) {
                return TabKecamatan::where('tab_kabupaten_id', $kabupatenId)
                    ->orderBy('nama_kecamatan')
                    ->get(['id', 'nama_kecamatan']);
            });

            return response()->json($kecamatan);
        } catch (\Exception $e) {
            Log::error('Error in getKecamatan: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal mengambil data kecamatan',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get detailed information about a specific kecamatan
     */
    public function getKecamatanDetail($id)
    {
        try {
            $cacheKey = "kecamatan_detail_{$id}";
            
            $kecamatan = Cache::remember($cacheKey, 1800, function() use ($id) {
                return TabKecamatan::with([
                    'kabupaten:id,nama_kabupaten,tab_provinsi_id',
                    'kabupaten.provinsi:id,nama_provinsi'
                ])->findOrFail($id);
            });

            // Add computed attributes dengan struktur baru
            $kecamatan->bulan_hujan_nama = $kecamatan->bulan_hujan ?? [];
            $kecamatan->bulan_kering_nama = $kecamatan->bulan_kering ?? [];
            
            // Rekomendasi waktu tanam berdasarkan jenis komoditas
            $kecamatan->rekomendasi_waktu_tanam = $kecamatan->rekomendasi_waktu_tanam;
            
            // Add komoditas information
            $kecamatan->nama_komoditas = $kecamatan->nama_komoditas;
            $kecamatan->nama_varietas = $kecamatan->nama_varietas;
            $kecamatan->detail_varietas = $kecamatan->detail_varietas;

            // Add komoditas flags for frontend dengan struktur baru
            $kecamatan->komoditas_kedelai = $kecamatan->jenis_komoditas === 'kedelai';
            $kecamatan->komoditas_kacang_tanah = $kecamatan->jenis_komoditas === 'kacang_tanah';
            $kecamatan->komoditas_kacang_hijau = $kecamatan->jenis_komoditas === 'kacang_hijau';

            // Production summary langsung dari field
            $kecamatan->total_luas_tanam = $kecamatan->luas_tanam ?? 0;
            $kecamatan->total_produksi = $kecamatan->total_produksi ?? 0;

            return response()->json($kecamatan);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Kecamatan not found: ' . $id);
            return response()->json([
                'error' => 'Kecamatan tidak ditemukan',
                'message' => 'Data kecamatan dengan ID tersebut tidak ada'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error in getKecamatanDetail: ' . $e->getMessage());
            Log::error('Kecamatan ID: ' . $id);
            return response()->json([
                'error' => 'Gagal mengambil detail kecamatan',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get all provinsi for dropdown
     */
    public function getProvinsi()
    {
        try {
            $cacheKey = 'all_provinsi';
            
            $provinsi = Cache::remember($cacheKey, 7200, function() {
                return TabProvinsi::select('id', 'nama_provinsi')
                    ->orderBy('nama_provinsi')
                    ->get();
            });

            return response()->json($provinsi);
        } catch (\Exception $e) {
            Log::error('Error in getProvinsi: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal mengambil data provinsi',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get region statistics
     */
    public function getRegionStats()
    {
        try {
            $cacheKey = 'region_stats';
            
            $stats = Cache::remember($cacheKey, 3600, function() {
                return [
                    'total_provinsi' => TabProvinsi::count(),
                    'total_kabupaten' => TabKabupaten::count(),
                    'total_kecamatan' => TabKecamatan::count(),
                    'kecamatan_with_coordinates' => TabKecamatan::whereNotNull('latitude')
                        ->whereNotNull('longitude')->count(),
                    'kecamatan_with_komoditas' => TabKecamatan::whereNotNull('jenis_komoditas')->count(),
                    'kedelai_count' => TabKecamatan::where('jenis_komoditas', 'kedelai')->count(),
                    'kacang_tanah_count' => TabKecamatan::where('jenis_komoditas', 'kacang_tanah')->count(),
                    'kacang_hijau_count' => TabKecamatan::where('jenis_komoditas', 'kacang_hijau')->count()
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getRegionStats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Gagal mengambil statistik wilayah',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get all kecamatan with coordinates for map
     */
    public function getAllKecamatanForMap()
    {
        try {
            $cacheKey = 'all_kecamatan_map';
            
            $kecamatan = Cache::remember($cacheKey, 1800, function() {
                return TabKecamatan::select(
                    'id', 'nama_kecamatan', 'latitude', 'longitude', 'tab_kabupaten_id'
                )
                ->with([
                    'kabupaten:id,nama_kabupaten,tab_provinsi_id',
                    'kabupaten.provinsi:id,nama_provinsi'
                ])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama_kecamatan' => $item->nama_kecamatan,
                        'latitude' => $item->latitude,
                        'longitude' => $item->longitude,
                        'kabupaten' => $item->kabupaten->nama_kabupaten ?? '',
                        'provinsi' => $item->kabupaten->provinsi->nama_provinsi ?? ''
                    ];
                });
            });

            return response()->json([
                'success' => true,
                'data' => $kecamatan
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getAllKecamatanForMap: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Gagal mengambil data kecamatan untuk peta',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Search kecamatan by name
     */
    public function searchKecamatan($query)
    {
        try {
            $results = TabKecamatan::select('id', 'nama_kecamatan', 'tab_kabupaten_id')
                ->with([
                    'kabupaten:id,nama_kabupaten,tab_provinsi_id',
                    'kabupaten.provinsi:id,nama_provinsi'
                ])
                ->where('nama_kecamatan', 'LIKE', "%{$query}%")
                ->limit(10)
                ->get()
                ->map(function($item) {
                    return [
                        'id' => $item->id,
                        'nama_kecamatan' => $item->nama_kecamatan,
                        'kabupaten' => $item->kabupaten->nama_kabupaten ?? '',
                        'provinsi' => $item->kabupaten->provinsi->nama_provinsi ?? '',
                        'full_name' => $item->nama_kecamatan . ', ' . 
                                     ($item->kabupaten->nama_kabupaten ?? '') . ', ' .
                                     ($item->kabupaten->provinsi->nama_provinsi ?? '')
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $results
            ]);
        } catch (\Exception $e) {
            Log::error('Error in searchKecamatan: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Gagal mencari kecamatan',
                'message' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}