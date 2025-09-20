<?php
// app/Http/Controllers/DeteksiController.php

namespace App\Http\Controllers;

use App\Models\Gejala;
use App\Models\HamaPenyakit;
use App\Models\DeteksiHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class DeteksiController extends Controller
{
    public function index()
    {
        $gejala = Gejala::kedelai()
            ->orderBy('daerah')
            ->orderBy('gejala')
            ->get();
        
        $totalHama = HamaPenyakit::kedelai()->count();
        
        return view('deteksi.index', compact('gejala', 'totalHama'));
    }

    public function diagnose(Request $request)
    {
        $request->validate([
            'gejala' => 'required|array|min:1',
            'gejala.*' => 'exists:gejalas,id_gejala'
        ]);

        $gejalaIds = $request->gejala;
        
        $results = HamaPenyakit::kedelai()
            ->with(['gejala', 'insektisida'])
            ->get()
            ->map(function ($hamaPenyakit) use ($gejalaIds) {
                $matchedSymptoms = $hamaPenyakit->getMatchedSymptoms($gejalaIds);
                
                if ($matchedSymptoms->isEmpty()) {
                    return null; 
                }
                
                $confidenceScore = $this->calculateConfidenceScore($hamaPenyakit, $gejalaIds);
                
                return [
                    'id' => $hamaPenyakit->id,
                    'id_penyakit' => $hamaPenyakit->id_penyakit,
                    'nama_penyakit' => $hamaPenyakit->nama_penyakit,
                    'terjangkit' => $hamaPenyakit->terjangkit,
                    'jenis_tanaman' => $hamaPenyakit->jenis_tanaman,
                    'confidence_score' => $confidenceScore,
                    'matched_symptoms' => $matchedSymptoms->map(function ($gejala) {
                        return [
                            'id_gejala' => $gejala->id_gejala,
                            'gejala' => $gejala->gejala,
                            'daerah' => $gejala->daerah
                        ];
                    }),
                    'kultur_teknis' => $hamaPenyakit->kultur_teknis,
                    'fisik_mekanis' => $hamaPenyakit->fisik_mekanis,
                    'hayati' => $hamaPenyakit->hayati,
                    'kimiawi' => $hamaPenyakit->kimiawi,
                    'gambar' => $hamaPenyakit->gambar,
                    'insektisida' => $hamaPenyakit->insektisida->map(function ($insektisida) {
                        return [
                            'nama_insektisida' => $insektisida->nama_insektisida,
                            'bahan_aktif' => $insektisida->bahan_aktif,
                            'hama_sasaran' => $insektisida->hama_sasaran
                        ];
                    })
                ];
            })
            ->filter() 
            ->sortByDesc('confidence_score') 
            ->take(10) 
            ->values();

        // Save detection history
        $this->saveDetectionHistory($gejalaIds, $results->toArray(), $request);

        return response()->json($results);
    }

    public function hasil(Request $request)
    {
        $gejalaIds = $request->get('gejala', []);
        
        if (empty($gejalaIds)) {
            return redirect()->route('deteksi.index')
                           ->with('error', 'Tidak ada gejala yang dipilih');
        }

        if (is_string($gejalaIds)) {
            $gejalaIds = json_decode($gejalaIds, true) ?? [];
        }

        $results = collect();
        
        if (!empty($gejalaIds)) {
            $results = HamaPenyakit::kedelai()
                ->with(['gejala', 'insektisida'])
                ->get()
                ->map(function ($hamaPenyakit) use ($gejalaIds) {
                    $matchedSymptoms = $hamaPenyakit->getMatchedSymptoms($gejalaIds);
                    
                    if ($matchedSymptoms->isEmpty()) {
                        return null;
                    }
                    
                    $confidenceScore = $this->calculateConfidenceScore($hamaPenyakit, $gejalaIds);
                    
                    $hamaPenyakit->confidence_score = $confidenceScore;
                    $hamaPenyakit->matched_symptoms = $matchedSymptoms;
                    
                    return $hamaPenyakit;
                })
                ->filter()
                ->sortByDesc('confidence_score')
                ->take(10)
                ->values();
        }

        return view('deteksi.hasil', compact('results', 'gejalaIds'));
    }

    private function calculateConfidenceScore(HamaPenyakit $hamaPenyakit, array $gejalaIds): float
    {
        $allGejalaCount = $hamaPenyakit->gejala()->count();
        $matchedGejala = $hamaPenyakit->gejala()->whereIn('gejalas.id_gejala', $gejalaIds)->get();
        $matchedCount = $matchedGejala->count();
        $inputCount = count($gejalaIds);
        
        if ($allGejalaCount == 0 || $matchedCount == 0) {
            return 0;
        }
        
        $diseaseMatchPercentage = ($matchedCount / $allGejalaCount) * 60;
        $inputMatchPercentage = ($matchedCount / $inputCount) * 40;
        
        return round($diseaseMatchPercentage + $inputMatchPercentage, 2);
    }

    private function saveDetectionHistory(array $gejalaIds, array $results, Request $request): void
    {
        DeteksiHistory::create([
            'gejala_ids' => $gejalaIds,
            'results' => $results,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'detected_at' => now()
        ]);
    }
}