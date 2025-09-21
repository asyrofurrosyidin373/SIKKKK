<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gejala;
use App\Models\Tanaman;
use App\Models\LaporanDeteksi;
use App\Models\OrgPenTan;
use App\Models\HamaPenyakit;
use App\Services\DetectionService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class DeteksiController extends Controller
{
    protected $detectionService;

    public function __construct(DetectionService $detectionService)
    {
        $this->detectionService = $detectionService;
    }

    public function getGejala(Request $request)
    {
        try {
            $query = Gejala::active()->with(['hamaPenyakit' => function($q) {
                $q->select('id', 'nama_penyakit', 'terjangkit');
            }]);

            // Filter berdasarkan jenis tanaman jika ada
            if ($request->has('jenis_tanaman')) {
                $query->where('jenis_tanaman', $request->jenis_tanaman);
            }

            // Filter berdasarkan daerah jika ada
            if ($request->has('daerah')) {
                $query->where('daerah', $request->daerah);
            }

            // Filter berdasarkan severity jika ada
            if ($request->has('min_severity')) {
                $query->where('severity_score', '>=', $request->min_severity);
            }

            $gejalas = $query->orderBy('frequency', 'desc')
                           ->orderBy('severity_score', 'desc')
                           ->get()
                           ->groupBy('daerah');

            return response()->json([
                'success' => true,
                'data' => $gejalas,
                'metadata' => [
                    'total_gejala' => $gejalas->flatten()->count(),
                    'daerah_count' => $gejalas->count(),
                    'filters_applied' => $request->only(['jenis_tanaman', 'daerah', 'min_severity'])
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting gejala: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data gejala'
            ], 500);
        }
    }
    
    public function deteksiBerdasarkanGejala(Request $request)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'gejala' => 'required|array|min:1',
                'gejala.*' => 'required|integer|exists:gejalas,id',
                'jenis_tanaman' => 'nullable|string|in:Kedelai,Kacang Tanah,Kacang Hijau',
                'session_id' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak valid',
                    'errors' => $validator->errors()
                ], 422);
            }

            $gejalaIds = $request->input('gejala');
            $options = [
                'session_id' => $request->input('session_id', session()->getId()),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'jenis_tanaman' => $request->input('jenis_tanaman')
            ];

            // Gunakan DetectionService untuk deteksi yang lebih canggih
            $results = $this->detectionService->detectBySymptoms($gejalaIds, $options);

            // Dapatkan rekomendasi
            $recommendations = $this->detectionService->getRecommendations($results['detection_results']);

            return response()->json([
                'success' => true,
                'data' => $results,
                'recommendations' => $recommendations,
                'selected_gejala' => $gejalaIds,
                'metadata' => [
                    'detection_time' => now()->toISOString(),
                    'algorithm_version' => '2.0',
                    'total_symptoms_analyzed' => count($gejalaIds)
                ]
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            Log::error('Error in deteksiBerdasarkanGejala: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat melakukan deteksi'
            ], 500);
        }
    }
    
    public function getTanaman()
    {
        $tanamans = Tanaman::all();
        return response()->json($tanamans);
    }
    
    public function submitLaporan(Request $request)
    {
        $request->validate([
            'tanaman_id' => 'required|exists:tanaman,id',
            'foto_path' => 'required|string',
            'deskripsi' => 'nullable|string|max:500',
            'lokasi' => 'nullable|string|max:255'
        ]);
        
        $laporan = LaporanDeteksi::create([
            'user_id' => auth()->id(),
            'tanaman_id' => $request->tanaman_id,
            'foto_path' => $request->foto_path,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending',
            'lokasi' => $request->lokasi
        ]);
        
        return response()->json($laporan, 201);
    }
    
    public function detectWithAI(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'tanaman_id' => 'required|exists:tanaman,id'
        ]);
        
        // Save uploaded file
        $fotoPath = $request->file('foto')->store('deteksi', 'public');
        
        // Here you would integrate with AI service
        // For demo purposes, return mock results
        $mockResults = [
            'detected_opt' => [
                'id' => 1,
                'nama_opt' => 'Penggerek Polong',
                'confidence' => 85.5,
                'jenis' => 'hama'
            ],
            'alternative_opts' => [
                [
                    'id' => 2,
                    'nama_opt' => 'Ulat Grayak',
                    'confidence' => 72.3,
                    'jenis' => 'hama'
                ]
            ],
            'foto_path' => $fotoPath
        ];
        
        // Save to database
        $laporan = LaporanDeteksi::create([
            'user_id' => auth()->id(),
            'tanaman_id' => $request->tanaman_id,
            'org_pen_tan_id' => $mockResults['detected_opt']['id'] ?? null,
            'foto_path' => $fotoPath,
            'deskripsi' => $request->deskripsi,
            'status' => 'completed',
            'lokasi' => $request->lokasi
        ]);
        
        return response()->json([
            'laporan' => $laporan,
            'ai_results' => $mockResults
        ]);
    }

    /**
     * Dapatkan statistik deteksi
     */
    public function getDetectionStats(Request $request)
    {
        try {
            $days = $request->input('days', 30);
            $stats = $this->detectionService->getDetectionStats($days);

            return response()->json([
                'success' => true,
                'data' => $stats,
                'metadata' => [
                    'period_days' => $days,
                    'generated_at' => now()->toISOString()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting detection stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil statistik'
            ], 500);
        }
    }

    /**
     * Dapatkan history deteksi untuk user
     */
    public function getDetectionHistory(Request $request)
    {
        try {
            $sessionId = $request->input('session_id', session()->getId());
            $limit = $request->input('limit', 10);

            $history = \App\Models\DeteksiHistory::bySession($sessionId)
                ->orderBy('detected_at', 'desc')
                ->limit($limit)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $history,
                'metadata' => [
                    'session_id' => $sessionId,
                    'total_records' => $history->count()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting detection history: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil history'
            ], 500);
        }
    }
}