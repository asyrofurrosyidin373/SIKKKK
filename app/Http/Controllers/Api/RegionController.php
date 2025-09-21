<?php

// app/Http/Controllers/Api/RegionController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TabProvinsi;
use App\Models\TabKabupaten;
use App\Models\TabKecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class RegionController extends Controller
{
    public function getProvinsi(Request $request)
    {
        try {
            $cacheKey = 'provinsi_list';
            
            $provinsi = Cache::remember($cacheKey, 7200, function() {
                return TabProvinsi::select('id', 'nama_provinsi', 'kode_provinsi')
                    ->orderBy('nama_provinsi')
                    ->get();
            });

            return response()->json([
                'success' => true,
                'data' => $provinsi,
                'metadata' => [
                    'total' => $provinsi->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getProvinsi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data provinsi'
            ], 500);
        }
    }
    
    public function getKabupatenByProvinsi($provinsiId, Request $request)
    {
        try {
            $cacheKey = "kabupaten_provinsi_{$provinsiId}";
            
            $kabupaten = Cache::remember($cacheKey, 7200, function() use ($provinsiId) {
                return TabKabupaten::select('id', 'nama_kabupaten', 'kode_kabupaten', 'tab_provinsi_id')
                    ->where('tab_provinsi_id', $provinsiId)
                    ->orderBy('nama_kabupaten')
                    ->get();
            });

            return response()->json([
                'success' => true,
                'data' => $kabupaten,
                'metadata' => [
                    'provinsi_id' => $provinsiId,
                    'total' => $kabupaten->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getKabupatenByProvinsi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kabupaten'
            ], 500);
        }
    }
    
    public function getKecamatanByKabupaten($kabupatenId, Request $request)
    {
        try {
            $cacheKey = "kecamatan_kabupaten_{$kabupatenId}";
            
            $kecamatan = Cache::remember($cacheKey, 7200, function() use ($kabupatenId) {
                return TabKecamatan::select('id', 'nama_kecamatan', 'kode_kecamatan', 'tab_kabupaten_id', 'latitude', 'longitude')
                    ->where('tab_kabupaten_id', $kabupatenId)
                    ->orderBy('nama_kecamatan')
                    ->get();
            });

            return response()->json([
                'success' => true,
                'data' => $kecamatan,
                'metadata' => [
                    'kabupaten_id' => $kabupatenId,
                    'total' => $kecamatan->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getKecamatanByKabupaten: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data kecamatan'
            ], 500);
        }
    }
    
    public function getKecamatanDetail($id)
    {
        $kecamatan = TabKecamatan::with([
            'kabupaten.provinsi',
            'komoditasKedelai.varietas',
            'komoditasKacangTanah.varietas',
            'komoditasKacangHijau.varietas'
        ])->findOrFail($id);
        
        // Add processed month names
        $kecamatan->bulan_hujan_nama = $kecamatan->getBulanHujanNamaAttribute();
        $kecamatan->waktu_tanam_kedelai_nama = $kecamatan->getWaktuTanamKedelaiNamaAttribute();
        $kecamatan->waktu_tanam_kacang_tanah_nama = $kecamatan->getWaktuTanamKacangTanahNamaAttribute();
        $kecamatan->waktu_tanam_kacang_hijau_nama = $kecamatan->getWaktuTanamKacangHijauNamaAttribute();
        
        return response()->json($kecamatan);
    }
    
    public function getAllKecamatanForMap(Request $request)
    {
        try {
            $cacheKey = 'kecamatan_map_data';
            
            $kecamatan = Cache::remember($cacheKey, 3600, function() {
                return TabKecamatan::select(
                    'id', 'nama_kecamatan', 'latitude', 'longitude', 
                    'tab_kabupaten_id', 'bulan_hujan', 'waktu_tanam_kedelai',
                    'waktu_tanam_kacang_tanah', 'waktu_tanam_kacang_hijau'
                )
                ->with([
                    'kabupaten:id,nama_kabupaten,tab_provinsi_id',
                    'kabupaten.provinsi:id,nama_provinsi'
                ])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->get()
                ->map(function($item) {
                    // Add computed properties
                    $item->bulan_hujan_nama = $item->getBulanHujanNamaAttribute();
                    $item->waktu_tanam_kedelai_nama = $item->getWaktuTanamKedelaiNamaAttribute();
                    $item->waktu_tanam_kacang_tanah_nama = $item->getWaktuTanamKacangTanahNamaAttribute();
                    $item->waktu_tanam_kacang_hijau_nama = $item->getWaktuTanamKacangHijauNamaAttribute();
                    return $item;
                });
            });

            return response()->json([
                'success' => true,
                'data' => $kecamatan,
                'metadata' => [
                    'total' => $kecamatan->count(),
                    'with_coordinates' => $kecamatan->whereNotNull('latitude')->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getAllKecamatanForMap: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data peta'
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
                    'kecamatan_with_komoditas' => TabKecamatan::whereHas('komoditasKedelai')
                        ->orWhereHas('komoditasKacangTanah')
                        ->orWhereHas('komoditasKacangHijau')
                        ->count()
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
                'message' => 'Terjadi kesalahan saat mengambil statistik wilayah'
            ], 500);
        }
    }
}