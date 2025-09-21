<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VarietasKedelai;
use App\Models\VarietasKacangTanah;
use App\Models\VarietasKacangHijau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class VarietasController extends Controller
{
    public function getAllVarietas(Request $request)
    {
        try {
            $cacheKey = 'all_varietas_' . md5(serialize($request->all()));
            
            $data = Cache::remember($cacheKey, 3600, function() use ($request) {
                $query = VarietasKedelai::select('id', 'nama_varietas', 'tahun', 'potensi_hasil', 'rata_hasil', 'umur_masak', 'tinggi_tanaman');
                    
                if ($request->has('min_yield')) {
                    $query->where('potensi_hasil', '>=', $request->min_yield);
                }
                
                if ($request->has('max_age')) {
                    $query->where('umur_masak', '<=', $request->max_age);
                }
                
                $kedelai = $query->get()->map(function($item) {
                    $item->type = 'kedelai';
                    $item->tanaman = 'Kedelai';
                    return $item;
                });
                
                $kacangTanah = VarietasKacangTanah::select('id', 'nama_varietas', 'tahun', 'potensi_hasil', 'rata_hasil', 'umur_masak', 'tinggi_tanaman')
                    ->get()->map(function($item) {
                        $item->type = 'kacang-tanah';
                        $item->tanaman = 'Kacang Tanah';
                        return $item;
                    });
                    
                $kacangHijau = VarietasKacangHijau::select('id', 'nama_varietas', 'tahun', 'potensi_hasil', 'rata_hasil', 'umur_masak', 'tinggi_tanaman')
                    ->get()->map(function($item) {
                        $item->type = 'kacang-hijau';
                        $item->tanaman = 'Kacang Hijau';
                        return $item;
                    });
                    
                return $kedelai->concat($kacangTanah)->concat($kacangHijau);
            });
            
            return response()->json([
                'success' => true,
                'data' => $data,
                'metadata' => [
                    'total' => $data->count(),
                    'by_type' => [
                        'kedelai' => $data->where('type', 'kedelai')->count(),
                        'kacang_tanah' => $data->where('type', 'kacang-tanah')->count(),
                        'kacang_hijau' => $data->where('type', 'kacang-hijau')->count()
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getAllVarietas: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data varietas'
            ], 500);
        }
    }
    
    public function getVarietasKedelai(Request $request)
    {
        try {
            $query = VarietasKedelai::with(['organisme', 'komoditas']);

            // Filter berdasarkan tahun
            if ($request->has('tahun')) {
                $query->where('tahun', $request->tahun);
            }

            // Filter berdasarkan potensi hasil
            if ($request->has('min_potensi')) {
                $query->where('potensi_hasil', '>=', $request->min_potensi);
            }

            // Filter berdasarkan umur masak
            if ($request->has('max_umur')) {
                $query->where('umur_masak', '<=', $request->max_umur);
            }

            // Search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_varietas', 'like', "%{$search}%")
                      ->orWhere('asal', 'like', "%{$search}%")
                      ->orWhere('inventor', 'like', "%{$search}%");
                });
            }

            $varietas = $query->orderBy('potensi_hasil', 'desc')
                            ->orderBy('tahun', 'desc')
                            ->paginate($request->get('per_page', 20));

            return response()->json([
                'success' => true,
                'data' => $varietas,
                'metadata' => [
                    'filters_applied' => $request->only(['tahun', 'min_potensi', 'max_umur', 'search'])
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getVarietasKedelai: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data varietas kedelai'
            ], 500);
        }
    }
    
    public function getVarietasKacangTanah(Request $request)
    {
        try {
            $query = VarietasKacangTanah::with(['organisme', 'komoditas']);

            if ($request->has('tahun')) {
                $query->where('tahun', $request->tahun);
            }

            if ($request->has('min_potensi')) {
                $query->where('potensi_hasil', '>=', $request->min_potensi);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_varietas', 'like', "%{$search}%")
                      ->orWhere('asal', 'like', "%{$search}%");
                });
            }

            $varietas = $query->orderBy('potensi_hasil', 'desc')
                            ->orderBy('tahun', 'desc')
                            ->paginate($request->get('per_page', 20));

            return response()->json([
                'success' => true,
                'data' => $varietas
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getVarietasKacangTanah: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data varietas kacang tanah'
            ], 500);
        }
    }
    
    public function getVarietasKacangHijau(Request $request)
    {
        try {
            $query = VarietasKacangHijau::with(['organisme', 'komoditas']);

            if ($request->has('tahun')) {
                $query->where('tahun', $request->tahun);
            }

            if ($request->has('min_potensi')) {
                $query->where('potensi_hasil', '>=', $request->min_potensi);
            }

            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_varietas', 'like', "%{$search}%")
                      ->orWhere('asal', 'like', "%{$search}%");
                });
            }

            $varietas = $query->orderBy('potensi_hasil', 'desc')
                            ->orderBy('tahun', 'desc')
                            ->paginate($request->get('per_page', 20));

            return response()->json([
                'success' => true,
                'data' => $varietas
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getVarietasKacangHijau: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data varietas kacang hijau'
            ], 500);
        }
    }
    
    public function getVarietasDetail($type, $id)
    {
        $modelClass = match($type) {
            'kedelai' => VarietasKedelai::class,
            'kacang-tanah' => VarietasKacangTanah::class,
            'kacang-hijau' => VarietasKacangHijau::class,
            default => abort(404)
        };
        
        $varietas = $modelClass::with([
            'organisme',
            'komoditas.kecamatan.kabupaten.provinsi'
        ])->findOrFail($id);
        
        return response()->json($varietas);
    }
}
