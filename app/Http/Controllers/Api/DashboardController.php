<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HamaPenyakit;
use App\Models\Gejala;
use App\Models\DeteksiHistory;
use App\Models\VarietasKedelai;
use App\Models\VarietasKacangTanah;
use App\Models\VarietasKacangHijau;
use App\Models\TabKecamatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function getStats(Request $request)
    {
        try {
            $cacheKey = 'dashboard_stats_' . ($request->get('period', '30'));
            $period = $request->get('period', 30);
            
            $stats = Cache::remember($cacheKey, 1800, function() use ($period) {
                return [
                    'overview' => $this->getOverviewStats(),
                    'detection' => $this->getDetectionStats($period),
                    'diseases' => $this->getDiseaseStats(),
                    'varieties' => $this->getVarietyStats(),
                    'regions' => $this->getRegionStats(),
                    'recent_activity' => $this->getRecentActivity($period)
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $stats,
                'metadata' => [
                    'period_days' => $period,
                    'generated_at' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getStats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil statistik dashboard'
            ], 500);
        }
    }

    /**
     * Get overview statistics
     */
    private function getOverviewStats(): array
    {
        return [
            'total_diseases' => HamaPenyakit::active()->count(),
            'total_symptoms' => Gejala::active()->count(),
            'total_varieties' => VarietasKedelai::count() + VarietasKacangTanah::count() + VarietasKacangHijau::count(),
            'total_regions' => TabKecamatan::count(),
            'active_diseases' => HamaPenyakit::active()->count(),
            'high_priority_diseases' => HamaPenyakit::active()->where('priority', '>=', 8)->count(),
            'severe_symptoms' => Gejala::active()->where('severity_score', '>=', 8)->count(),
            'frequent_symptoms' => Gejala::active()->where('frequency', '>=', 80)->count()
        ];
    }

    /**
     * Get detection statistics
     */
    private function getDetectionStats(int $period): array
    {
        $startDate = now()->subDays($period);
        
        return [
            'total_detections' => DeteksiHistory::where('detected_at', '>=', $startDate)->count(),
            'high_confidence_detections' => DeteksiHistory::where('detected_at', '>=', $startDate)
                ->where('confidence_score', '>=', 80)->count(),
            'verified_detections' => DeteksiHistory::where('detected_at', '>=', $startDate)
                ->where('is_verified', true)->count(),
            'avg_confidence' => DeteksiHistory::where('detected_at', '>=', $startDate)
                ->avg('confidence_score'),
            'detections_today' => DeteksiHistory::today()->count(),
            'detections_this_week' => DeteksiHistory::thisWeek()->count(),
            'detections_this_month' => DeteksiHistory::thisMonth()->count()
        ];
    }

    /**
     * Get disease statistics
     */
    private function getDiseaseStats(): array
    {
        return [
            'by_type' => [
                'hama' => HamaPenyakit::active()->hama()->count(),
                'penyakit' => HamaPenyakit::active()->penyakit()->count()
            ],
            'by_plant' => [
                'kedelai' => HamaPenyakit::active()->kedelai()->count(),
                'kacang_tanah' => HamaPenyakit::active()->where('jenis_tanaman', 'Kacang Tanah')->count(),
                'kacang_hijau' => HamaPenyakit::active()->where('jenis_tanaman', 'Kacang Hijau')->count()
            ],
            'by_priority' => [
                'high' => HamaPenyakit::active()->where('priority', '>=', 8)->count(),
                'medium' => HamaPenyakit::active()->whereBetween('priority', [5, 7])->count(),
                'low' => HamaPenyakit::active()->where('priority', '<', 5)->count()
            ],
            'with_control_methods' => HamaPenyakit::active()->where(function($query) {
                $query->whereNotNull('kultur_teknis')
                      ->orWhereNotNull('fisik_mekanis')
                      ->orWhereNotNull('hayati')
                      ->orWhereNotNull('kimiawi');
            })->count()
        ];
    }

    /**
     * Get variety statistics
     */
    private function getVarietyStats(): array
    {
        return [
            'kedelai' => [
                'total' => VarietasKedelai::count(),
                'avg_yield' => VarietasKedelai::avg('potensi_hasil'),
                'avg_maturity' => VarietasKedelai::avg('umur_masak')
            ],
            'kacang_tanah' => [
                'total' => VarietasKacangTanah::count(),
                'avg_yield' => VarietasKacangTanah::avg('potensi_hasil'),
                'avg_maturity' => VarietasKacangTanah::avg('umur_masak')
            ],
            'kacang_hijau' => [
                'total' => VarietasKacangHijau::count(),
                'avg_yield' => VarietasKacangHijau::avg('potensi_hasil'),
                'avg_maturity' => VarietasKacangHijau::avg('umur_masak')
            ]
        ];
    }

    /**
     * Get region statistics
     */
    private function getRegionStats(): array
    {
        return [
            'total_kecamatan' => TabKecamatan::count(),
            'with_coordinates' => TabKecamatan::whereNotNull('latitude')
                ->whereNotNull('longitude')->count(),
            'with_komoditas' => TabKecamatan::whereHas('komoditasKedelai')
                ->orWhereHas('komoditasKacangTanah')
                ->orWhereHas('komoditasKacangHijau')
                ->count()
        ];
    }

    /**
     * Get recent activity
     */
    private function getRecentActivity(int $period): array
    {
        $startDate = now()->subDays($period);
        
        return [
            'recent_detections' => DeteksiHistory::where('detected_at', '>=', $startDate)
                ->orderBy('detected_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($detection) {
                    return [
                        'id' => $detection->id,
                        'detected_at' => $detection->detected_at,
                        'confidence_score' => $detection->confidence_score,
                        'symptoms_count' => count($detection->gejala_ids ?? [])
                    ];
                }),
            'top_diseases' => DeteksiHistory::where('detected_at', '>=', $startDate)
                ->get()
                ->pluck('results')
                ->flatten(1)
                ->pluck('detection_results')
                ->flatten(1)
                ->pluck('id')
                ->countBy()
                ->sortDesc()
                ->take(5)
                ->map(function($count, $diseaseId) {
                    $disease = HamaPenyakit::find($diseaseId);
                    return [
                        'id' => $diseaseId,
                        'name' => $disease?->nama_penyakit ?? 'Unknown',
                        'count' => $count
                    ];
                })
                ->values(),
            'top_symptoms' => DeteksiHistory::where('detected_at', '>=', $startDate)
                ->get()
                ->pluck('gejala_ids')
                ->flatten()
                ->countBy()
                ->sortDesc()
                ->take(5)
                ->map(function($count, $symptomId) {
                    $symptom = Gejala::find($symptomId);
                    return [
                        'id' => $symptomId,
                        'name' => $symptom?->gejala ?? 'Unknown',
                        'count' => $count
                    ];
                })
                ->values()
        ];
    }

    /**
     * Get chart data for dashboard
     */
    public function getChartData(Request $request)
    {
        try {
            $type = $request->get('type', 'detections');
            $period = $request->get('period', 30);
            
            $cacheKey = "chart_data_{$type}_{$period}";
            
            $data = Cache::remember($cacheKey, 1800, function() use ($type, $period) {
                return match($type) {
                    'detections' => $this->getDetectionChartData($period),
                    'diseases' => $this->getDiseaseChartData(),
                    'symptoms' => $this->getSymptomChartData(),
                    'varieties' => $this->getVarietyChartData(),
                    default => []
                };
            });

            return response()->json([
                'success' => true,
                'data' => $data,
                'metadata' => [
                    'type' => $type,
                    'period' => $period
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getChartData: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data chart'
            ], 500);
        }
    }

    /**
     * Get detection chart data
     */
    private function getDetectionChartData(int $period): array
    {
        $data = [];
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'detections' => DeteksiHistory::whereDate('detected_at', $date)->count(),
                'high_confidence' => DeteksiHistory::whereDate('detected_at', $date)
                    ->where('confidence_score', '>=', 80)->count()
            ];
        }
        return $data;
    }

    /**
     * Get disease chart data
     */
    private function getDiseaseChartData(): array
    {
        return [
            'by_type' => [
                'labels' => ['Hama', 'Penyakit'],
                'data' => [
                    HamaPenyakit::active()->hama()->count(),
                    HamaPenyakit::active()->penyakit()->count()
                ]
            ],
            'by_priority' => [
                'labels' => ['Tinggi (8-10)', 'Sedang (5-7)', 'Rendah (1-4)'],
                'data' => [
                    HamaPenyakit::active()->where('priority', '>=', 8)->count(),
                    HamaPenyakit::active()->whereBetween('priority', [5, 7])->count(),
                    HamaPenyakit::active()->where('priority', '<', 5)->count()
                ]
            ]
        ];
    }

    /**
     * Get symptom chart data
     */
    private function getSymptomChartData(): array
    {
        return [
            'by_region' => [
                'labels' => ['Akar', 'Batang', 'Daun'],
                'data' => [
                    Gejala::active()->where('daerah', 'Akar')->count(),
                    Gejala::active()->where('daerah', 'Batang')->count(),
                    Gejala::active()->where('daerah', 'Daun')->count()
                ]
            ],
            'by_severity' => [
                'labels' => ['Sangat Parah (8-10)', 'Parah (6-7)', 'Sedang (4-5)', 'Ringan (1-3)'],
                'data' => [
                    Gejala::active()->where('severity_score', '>=', 8)->count(),
                    Gejala::active()->whereBetween('severity_score', [6, 7])->count(),
                    Gejala::active()->whereBetween('severity_score', [4, 5])->count(),
                    Gejala::active()->where('severity_score', '<', 4)->count()
                ]
            ]
        ];
    }

    /**
     * Get variety chart data
     */
    private function getVarietyChartData(): array
    {
        return [
            'by_plant' => [
                'labels' => ['Kedelai', 'Kacang Tanah', 'Kacang Hijau'],
                'data' => [
                    VarietasKedelai::count(),
                    VarietasKacangTanah::count(),
                    VarietasKacangHijau::count()
                ]
            ],
            'yield_comparison' => [
                'labels' => ['Kedelai', 'Kacang Tanah', 'Kacang Hijau'],
                'data' => [
                    round(VarietasKedelai::avg('potensi_hasil') ?? 0, 2),
                    round(VarietasKacangTanah::avg('potensi_hasil') ?? 0, 2),
                    round(VarietasKacangHijau::avg('potensi_hasil') ?? 0, 2)
                ]
            ]
        ];
    }
}
