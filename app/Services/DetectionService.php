<?php

namespace App\Services;

use App\Models\HamaPenyakit;
use App\Models\Gejala;
use App\Models\DeteksiHistory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DetectionService
{
    private $confidenceThreshold = 60;
    private $cacheTimeout = 3600; // 1 hour

    /**
     * Deteksi hama/penyakit berdasarkan gejala
     */
    public function detectBySymptoms(array $gejalaIds, array $options = []): array
    {
        $sessionId = $options['session_id'] ?? session()->getId();
        $ipAddress = $options['ip_address'] ?? request()->ip();
        $userAgent = $options['user_agent'] ?? request()->userAgent();

        // Validasi input
        if (empty($gejalaIds)) {
            throw new \InvalidArgumentException('Gejala tidak boleh kosong');
        }

        // Cache key untuk hasil deteksi
        $cacheKey = 'detection_' . md5(implode(',', $gejalaIds));
        
        // Cek cache terlebih dahulu
        if (Cache::has($cacheKey)) {
            $cachedResult = Cache::get($cacheKey);
            $this->saveDetectionHistory($gejalaIds, $cachedResult, $sessionId, $ipAddress, $userAgent);
            return $cachedResult;
        }

        // Ambil gejala yang aktif
        $gejala = Gejala::active()->whereIn('id', $gejalaIds)->get();
        
        if ($gejala->isEmpty()) {
            throw new \InvalidArgumentException('Gejala tidak ditemukan atau tidak aktif');
        }

        // Deteksi dengan algoritma yang lebih canggih
        $results = $this->performAdvancedDetection($gejalaIds, $gejala);

        // Simpan ke cache
        Cache::put($cacheKey, $results, $this->cacheTimeout);

        // Simpan ke history
        $this->saveDetectionHistory($gejalaIds, $results, $sessionId, $ipAddress, $userAgent);

        return $results;
    }

    /**
     * Algoritma deteksi yang lebih canggih
     */
    private function performAdvancedDetection(array $gejalaIds, $gejala): array
    {
        // Ambil semua hama/penyakit yang memiliki gejala yang dipilih
        $hamaPenyakit = HamaPenyakit::active()
            ->with(['gejala' => function($query) use ($gejalaIds) {
                $query->whereIn('gejala.id', $gejalaIds);
            }])
            ->whereHas('gejala', function($query) use ($gejalaIds) {
                $query->whereIn('gejala.id', $gejalaIds);
            })
            ->get();

        $results = [];

        foreach ($hamaPenyakit as $hp) {
            $confidenceScore = $this->calculateAdvancedConfidence($hp, $gejalaIds, $gejala);
            
            if ($confidenceScore >= $this->confidenceThreshold) {
                $results[] = [
                    'id' => $hp->id,
                    'nama_penyakit' => $hp->nama_penyakit,
                    'terjangkit' => $hp->terjangkit,
                    'jenis_tanaman' => $hp->jenis_tanaman,
                    'confidence_score' => round($confidenceScore, 2),
                    'matched_symptoms' => $hp->gejala->pluck('gejala')->toArray(),
                    'control_recommendations' => $hp->getControlRecommendations(),
                    'severity_level' => $this->calculateSeverityLevel($hp, $gejalaIds),
                    'risk_factors' => $this->identifyRiskFactors($hp, $gejalaIds)
                ];
            }
        }

        // Urutkan berdasarkan confidence score
        usort($results, function($a, $b) {
            return $b['confidence_score'] <=> $a['confidence_score'];
        });

        return [
            'detection_results' => $results,
            'total_matches' => count($results),
            'high_confidence_matches' => count(array_filter($results, fn($r) => $r['confidence_score'] >= 80)),
            'detection_metadata' => [
                'algorithm_version' => '2.0',
                'confidence_threshold' => $this->confidenceThreshold,
                'symptoms_analyzed' => count($gejalaIds),
                'timestamp' => now()->toISOString()
            ]
        ];
    }

    /**
     * Hitung confidence score yang lebih akurat
     */
    private function calculateAdvancedConfidence($hamaPenyakit, array $gejalaIds, $gejala): float
    {
        $matchedGejala = $hamaPenyakit->gejala;
        $totalBobotMatched = $matchedGejala->sum('pivot.bobot');
        $totalBobotPenyakit = $hamaPenyakit->gejala()->sum('bobot');
        
        if ($totalBobotPenyakit == 0) {
            return 0;
        }

        // Base score dari bobot gejala
        $baseScore = ($totalBobotMatched / $totalBobotPenyakit) * 100;

        // Faktor bonus untuk gejala yang sering muncul
        $frequencyBonus = $matchedGejala->avg('frequency') / 100;

        // Faktor severity
        $severityFactor = $matchedGejala->avg('severity_score') / 10;

        // Faktor spesifisitas (gejala yang unik untuk penyakit ini)
        $specificityFactor = $this->calculateSpecificityFactor($hamaPenyakit, $gejalaIds);

        // Faktor komprehensif (semakin banyak gejala yang cocok, semakin tinggi skor)
        $comprehensivenessFactor = (count($matchedGejala) / count($gejalaIds)) * 10;

        $adjustedScore = $baseScore + $frequencyBonus + $severityFactor + $specificityFactor + $comprehensivenessFactor;

        return min(100, max(0, $adjustedScore));
    }

    /**
     * Hitung faktor spesifisitas
     */
    private function calculateSpecificityFactor($hamaPenyakit, array $gejalaIds): float
    {
        // Hitung berapa banyak penyakit lain yang memiliki gejala yang sama
        $otherDiseases = HamaPenyakit::where('id', '!=', $hamaPenyakit->id)
            ->whereHas('gejala', function($query) use ($gejalaIds) {
                $query->whereIn('gejala.id', $gejalaIds);
            })
            ->count();

        // Semakin sedikit penyakit lain yang memiliki gejala yang sama, semakin spesifik
        return max(0, 10 - ($otherDiseases * 0.5));
    }

    /**
     * Hitung tingkat keparahan
     */
    private function calculateSeverityLevel($hamaPenyakit, array $gejalaIds): string
    {
        $matchedGejala = $hamaPenyakit->gejala;
        $avgSeverity = $matchedGejala->avg('severity_score');

        return match(true) {
            $avgSeverity >= 8 => 'Sangat Parah',
            $avgSeverity >= 6 => 'Parah',
            $avgSeverity >= 4 => 'Sedang',
            $avgSeverity >= 2 => 'Ringan',
            default => 'Sangat Ringan'
        };
    }

    /**
     * Identifikasi faktor risiko
     */
    private function identifyRiskFactors($hamaPenyakit, array $gejalaIds): array
    {
        $riskFactors = [];

        $matchedGejala = $hamaPenyakit->gejala;
        
        // Cek gejala dengan severity tinggi
        $highSeveritySymptoms = $matchedGejala->where('severity_score', '>=', 7);
        if ($highSeveritySymptoms->count() > 0) {
            $riskFactors[] = 'Gejala dengan tingkat keparahan tinggi terdeteksi';
        }

        // Cek gejala yang sering muncul
        $frequentSymptoms = $matchedGejala->where('frequency', '>=', 80);
        if ($frequentSymptoms->count() > 0) {
            $riskFactors[] = 'Gejala yang sering muncul terdeteksi';
        }

        // Cek kombinasi gejala yang berbahaya
        if ($matchedGejala->count() >= 5) {
            $riskFactors[] = 'Banyak gejala yang terdeteksi secara bersamaan';
        }

        return $riskFactors;
    }

    /**
     * Simpan history deteksi
     */
    private function saveDetectionHistory(array $gejalaIds, array $results, string $sessionId, string $ipAddress, string $userAgent): void
    {
        try {
            DeteksiHistory::create([
                'gejala_ids' => $gejalaIds,
                'results' => $results,
                'session_id' => $sessionId,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
                'confidence_score' => $results['detection_results'][0]['confidence_score'] ?? 0,
                'detection_metadata' => $results['detection_metadata'] ?? [],
                'detected_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save detection history: ' . $e->getMessage());
        }
    }

    /**
     * Dapatkan statistik deteksi
     */
    public function getDetectionStats(int $days = 30): array
    {
        return DeteksiHistory::getDetectionStats($days);
    }

    /**
     * Dapatkan rekomendasi berdasarkan hasil deteksi
     */
    public function getRecommendations(array $detectionResults): array
    {
        $recommendations = [];

        foreach ($detectionResults as $result) {
            if ($result['confidence_score'] >= 80) {
                $recommendations[] = [
                    'type' => 'immediate_action',
                    'message' => "Segera lakukan tindakan pengendalian untuk {$result['nama_penyakit']}",
                    'priority' => 'high',
                    'actions' => $result['control_recommendations']
                ];
            } elseif ($result['confidence_score'] >= 60) {
                $recommendations[] = [
                    'type' => 'monitoring',
                    'message' => "Pantau perkembangan {$result['nama_penyakit']} dan siapkan tindakan pengendalian",
                    'priority' => 'medium',
                    'actions' => $result['control_recommendations']
                ];
            }
        }

        return $recommendations;
    }
}
