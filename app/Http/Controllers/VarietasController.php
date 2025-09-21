<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VarietasKedelai;
use App\Models\VarietasKacangTanah;
use App\Models\VarietasKacangHijau;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class VarietasController extends Controller
{
    public function index()
    {
        try {
            $cacheKey = 'varietas_overview';
            
            $data = Cache::remember($cacheKey, 3600, function() {
                return [
                    'stats' => [
                        'kedelai' => [
                            'total' => VarietasKedelai::count(),
                            'avg_yield' => round(VarietasKedelai::avg('potensi_hasil') ?? 0, 2),
                            'avg_maturity' => round(VarietasKedelai::avg('umur_masak') ?? 0, 1),
                            'recent' => VarietasKedelai::where('tahun', '>=', now()->year - 5)->count()
                        ],
                        'kacang_tanah' => [
                            'total' => VarietasKacangTanah::count(),
                            'avg_yield' => round(VarietasKacangTanah::avg('potensi_hasil') ?? 0, 2),
                            'avg_maturity' => round(VarietasKacangTanah::avg('umur_masak') ?? 0, 1),
                            'recent' => VarietasKacangTanah::where('tahun', '>=', now()->year - 5)->count()
                        ],
                        'kacang_hijau' => [
                            'total' => VarietasKacangHijau::count(),
                            'avg_yield' => round(VarietasKacangHijau::avg('potensi_hasil') ?? 0, 2),
                            'avg_maturity' => round(VarietasKacangHijau::avg('umur_masak') ?? 0, 1),
                            'recent' => VarietasKacangHijau::where('tahun', '>=', now()->year - 5)->count()
                        ]
                    ],
                    'top_varieties' => [
                        'kedelai' => VarietasKedelai::orderBy('potensi_hasil', 'desc')->limit(3)->get(),
                        'kacang_tanah' => VarietasKacangTanah::orderBy('potensi_hasil', 'desc')->limit(3)->get(),
                        'kacang_hijau' => VarietasKacangHijau::orderBy('potensi_hasil', 'desc')->limit(3)->get()
                    ]
                ];
            });
            
            return view('varietas.index', $data);
        } catch (\Exception $e) {
            Log::error('Error in VarietasController@index: ' . $e->getMessage());
            return view('varietas.index', [
                'stats' => [],
                'top_varieties' => []
            ]);
        }
    }

    public function kedelai(Request $request)
    {
        try {
            $cacheKey = 'varietas_kedelai_' . md5(serialize($request->all()));
            
            $data = Cache::remember($cacheKey, 1800, function() use ($request) {
                $query = VarietasKedelai::with(['organisme', 'komoditas'])
                    ->where('is_active', true);

                // Filter
                if ($request->tahun) {
                    $query->where('tahun', $request->tahun);
                }
                if ($request->min_hasil) {
                    $query->where('potensi_hasil', '>=', $request->min_hasil);
                }
                if ($request->max_hasil) {
                    $query->where('potensi_hasil', '<=', $request->max_hasil);
                }
                if ($request->max_umur) {
                    $query->where('umur_masak', '<=', $request->max_umur);
                }
                if ($request->search) {
                    $search = $request->search;
                    $query->where(function($q) use ($search) {
                        $q->where('nama_varietas', 'like', "%{$search}%")
                          ->orWhere('asal', 'like', "%{$search}%")
                          ->orWhere('inventor', 'like', "%{$search}%");
                    });
                }

                $varietas = $query->orderBy('potensi_hasil', 'desc')
                                ->orderBy('tahun', 'desc')
                                ->paginate(12);
                
                $tahunList = VarietasKedelai::distinct()->pluck('tahun')->sort();
                
                return [
                    'varietas' => $varietas,
                    'tahunList' => $tahunList,
                    'stats' => [
                        'total' => VarietasKedelai::count(),
                        'avg_yield' => round(VarietasKedelai::avg('potensi_hasil') ?? 0, 2),
                        'max_yield' => VarietasKedelai::max('potensi_hasil'),
                        'min_yield' => VarietasKedelai::min('potensi_hasil')
                    ]
                ];
            });

            return view('varietas.kedelai', [
                'varietas' => $data['varietas'],
                'tahunList' => $data['tahunList'],
                'stats' => $data['stats'],
                'type' => 'kedelai',
            ]);
        } catch (\Exception $e) {
            Log::error('Error in VarietasController@kedelai: ' . $e->getMessage());
            return view('varietas.kedelai', [
                'varietas' => VarietasKedelai::paginate(12),
                'tahunList' => collect(),
                'stats' => [],
                'type' => 'kedelai',
            ]);
        }
    }

    public function kacangTanah(Request $request)
    {
        $query = VarietasKacangTanah::with('organisme');

        // Bisa tambahkan filter serupa dengan kedelai kalau dibutuhkan
        $varietas = $query->paginate(12);
        $tahunList = VarietasKacangTanah::distinct()->pluck('tahun')->sort();

        return view('varietas.kacang-tanah', [
            'varietas'   => $varietas,
            'tahunList'  => $tahunList,
            'type'       => 'kacang-tanah', // ✅ penting
        ]);
    }

    public function kacangHijau(Request $request)
    {
        $query = VarietasKacangHijau::with('organisme');

        // Bisa tambahkan filter serupa dengan kedelai kalau dibutuhkan
        $varietas = $query->paginate(12);
        $tahunList = VarietasKacangHijau::distinct()->pluck('tahun')->sort();

        return view('varietas.kacang-hijau', [
            'varietas'   => $varietas,
            'tahunList'  => $tahunList,
            'type'       => 'kacang-hijau', // ✅ penting
        ]);
    }

    public function show($type, $id)
    {
        $modelClass = match ($type) {
            'kedelai'       => VarietasKedelai::class,
            'kacang-tanah'  => VarietasKacangTanah::class,
            'kacang-hijau'  => VarietasKacangHijau::class,
            default         => abort(404)
        };

        $varietas = $modelClass::with([
            'organisme',
            'komoditas.kecamatan.kabupaten.provinsi'
        ])->findOrFail($id);

        return view('varietas.detail', [
            'varietas' => $varietas,
            'type'     => $type, // ✅ dipakai di detail
        ]);
    }

    /**
     * Get variety recommendations based on criteria
     */
    public function getRecommendations(Request $request)
    {
        try {
            $request->validate([
                'plant_type' => 'required|in:kedelai,kacang-tanah,kacang-hijau',
                'min_yield' => 'nullable|numeric|min:0',
                'max_maturity' => 'nullable|numeric|min:0',
                'region_id' => 'nullable|exists:tab_kecamatan,id'
            ]);

            $plantType = $request->plant_type;
            $minYield = $request->min_yield ?? 0;
            $maxMaturity = $request->max_maturity ?? 999;
            $regionId = $request->region_id;

            $modelClass = match($plantType) {
                'kedelai' => VarietasKedelai::class,
                'kacang-tanah' => VarietasKacangTanah::class,
                'kacang-hijau' => VarietasKacangHijau::class,
            };

            $query = $modelClass::with(['organisme', 'komoditas'])
                ->where('is_active', true)
                ->where('potensi_hasil', '>=', $minYield)
                ->where('umur_masak', '<=', $maxMaturity);

            if ($regionId) {
                $query->whereHas('komoditas', function($q) use ($regionId) {
                    $q->where('tab_kecamatan_id', $regionId);
                });
            }

            $recommendations = $query->orderBy('potensi_hasil', 'desc')
                                   ->orderBy('umur_masak', 'asc')
                                   ->limit(10)
                                   ->get();

            return response()->json([
                'success' => true,
                'data' => $recommendations,
                'metadata' => [
                    'plant_type' => $plantType,
                    'criteria' => [
                        'min_yield' => $minYield,
                        'max_maturity' => $maxMaturity,
                        'region_id' => $regionId
                    ],
                    'total_found' => $recommendations->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getRecommendations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mendapatkan rekomendasi varietas'
            ], 500);
        }
    }

    /**
     * Compare varieties
     */
    public function compare(Request $request)
    {
        try {
            $request->validate([
                'varieties' => 'required|array|min:2|max:4',
                'varieties.*' => 'required|string'
            ]);

            $varieties = [];
            foreach ($request->varieties as $varietyId) {
                // Try to find in each model
                $variety = VarietasKedelai::find($varietyId) 
                    ?? VarietasKacangTanah::find($varietyId)
                    ?? VarietasKacangHijau::find($varietyId);
                
                if ($variety) {
                    $varieties[] = $variety;
                }
            }

            if (count($varieties) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Minimal 2 varietas harus ditemukan untuk perbandingan'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => $varieties,
                'metadata' => [
                    'total_compared' => count($varieties)
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in compare: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat membandingkan varietas'
            ], 500);
        }
    }
}
