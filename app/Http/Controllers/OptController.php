<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrgPenTan;
use App\Models\HamaPenyakit;
use App\Models\Gejala;
use App\Models\Pengendalian;
use Illuminate\Support\Facades\Cache;

class OptController extends Controller
{
    /**
     * Tampilkan halaman utama OPT
     */
    public function index(Request $request)
    {
        try {
            $query = HamaPenyakit::active()->with(['gejala', 'insektisida']);

            // Filter berdasarkan jenis
            if ($request->has('jenis')) {
                $query->where('terjangkit', $request->jenis);
            }

            // Filter berdasarkan jenis tanaman
            if ($request->has('tanaman')) {
                $query->where('jenis_tanaman', $request->tanaman);
            }

            // Filter berdasarkan search
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nama_penyakit', 'like', "%{$search}%")
                      ->orWhere('deskripsi', 'like', "%{$search}%");
                });
            }

            $opts = $query->orderBy('priority', 'desc')
                         ->orderBy('nama_penyakit', 'asc')
                         ->paginate(12);

            // Statistik untuk dashboard
            $stats = Cache::remember('opt_stats', 3600, function() {
                return [
                    'total_hama' => HamaPenyakit::active()->hama()->count(),
                    'total_penyakit' => HamaPenyakit::active()->penyakit()->count(),
                    'total_gejala' => Gejala::active()->count(),
                    'total_pengendalian' => Pengendalian::count(),
                    'recent_detections' => \App\Models\DeteksiHistory::today()->count()
                ];
            });

            return view('opt.index', compact('opts', 'stats'));
        } catch (\Exception $e) {
            \Log::error('Error in OptController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data OPT');
        }
    }

    /**
     * Tampilkan detail OPT
     */
    public function show($id)
    {
        try {
            $opt = HamaPenyakit::active()
                ->with([
                    'gejala' => function($query) {
                        $query->orderBy('severity_score', 'desc')
                              ->orderBy('frequency', 'desc');
                    },
                    'insektisida'
                ])
                ->findOrFail($id);

            // OPT terkait berdasarkan gejala yang sama
            $relatedOpts = HamaPenyakit::active()
                ->where('id', '!=', $id)
                ->whereHas('gejala', function($query) use ($opt) {
                    $query->whereIn('gejala.id', $opt->gejala->pluck('id'));
                })
                ->withCount(['gejala' => function($query) use ($opt) {
                    $query->whereIn('gejala.id', $opt->gejala->pluck('id'));
                }])
                ->orderBy('gejala_count', 'desc')
                ->limit(5)
                ->get();

            // Statistik deteksi untuk OPT ini
            $detectionStats = \App\Models\DeteksiHistory::whereJsonContains('results', [
                'detection_results' => [
                    ['id' => $id]
                ]
            ])->count();

            return view('opt.show', compact('opt', 'relatedOpts', 'detectionStats'));
        } catch (\Exception $e) {
            \Log::error('Error in OptController@show: ' . $e->getMessage());
            return redirect()->route('opt.index')->with('error', 'OPT tidak ditemukan');
        }
    }

    /**
     * API untuk mendapatkan data OPT
     */
    public function getOptData(Request $request)
    {
        try {
            $query = HamaPenyakit::active()->with(['gejala', 'insektisida']);

            if ($request->has('jenis')) {
                $query->where('terjangkit', $request->jenis);
            }

            if ($request->has('tanaman')) {
                $query->where('jenis_tanaman', $request->tanaman);
            }

            $opts = $query->orderBy('priority', 'desc')
                         ->orderBy('nama_penyakit', 'asc')
                         ->get();

            return response()->json([
                'success' => true,
                'data' => $opts,
                'metadata' => [
                    'total' => $opts->count(),
                    'filters' => $request->only(['jenis', 'tanaman'])
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in OptController@getOptData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data OPT'
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan gejala OPT
     */
    public function getOptGejala($id)
    {
        try {
            $opt = HamaPenyakit::active()->findOrFail($id);
            $gejala = $opt->gejala()
                ->active()
                ->orderBy('severity_score', 'desc')
                ->orderBy('frequency', 'desc')
                ->get()
                ->groupBy('daerah');

            return response()->json([
                'success' => true,
                'data' => $gejala,
                'metadata' => [
                    'opt_name' => $opt->nama_penyakit,
                    'total_gejala' => $gejala->flatten()->count()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in OptController@getOptGejala: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil gejala OPT'
            ], 500);
        }
    }

    /**
     * API untuk mendapatkan pengendalian OPT
     */
    public function getOptPengendalian($id)
    {
        try {
            $opt = HamaPenyakit::active()->findOrFail($id);
            $pengendalian = $opt->getControlRecommendations();

            return response()->json([
                'success' => true,
                'data' => $pengendalian,
                'metadata' => [
                    'opt_name' => $opt->nama_penyakit,
                    'total_methods' => count($pengendalian)
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in OptController@getOptPengendalian: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil pengendalian OPT'
            ], 500);
        }
    }

    /**
     * API untuk search OPT
     */
    public function search(Request $request)
    {
        try {
            $search = $request->input('q', '');
            $limit = $request->input('limit', 10);

            if (strlen($search) < 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kata kunci minimal 2 karakter'
                ], 400);
            }

            $opts = HamaPenyakit::active()
                ->where(function($query) use ($search) {
                    $query->where('nama_penyakit', 'like', "%{$search}%")
                          ->orWhere('deskripsi', 'like', "%{$search}%")
                          ->orWhereHas('gejala', function($q) use ($search) {
                              $q->where('gejala', 'like', "%{$search}%");
                          });
                })
                ->with(['gejala' => function($query) {
                    $query->select('id', 'gejala', 'daerah');
                }])
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $opts,
                'metadata' => [
                    'search_term' => $search,
                    'total_found' => $opts->count()
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in OptController@search: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari OPT'
            ], 500);
        }
    }
}