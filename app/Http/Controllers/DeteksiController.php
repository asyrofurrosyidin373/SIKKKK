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
    public function index(Request $request)
    {
        // Get filter parameters
        $plantType = $request->get('plant_type', 'Kedelai');
        $attackType = $request->get('attack_type', '');
        $plantPart = $request->get('plant_part', '');
        
        // Build gejala query
        $gejalaQuery = Gejala::query();
        
        // Filter by plant type
        if ($plantType) {
            $gejalaQuery->where('jenis_tanaman', $plantType);
        }
        
        // Filter by plant part
        if ($plantPart) {
            $gejalaQuery->where('daerah', $plantPart);
        }
        
        $gejala = $gejalaQuery->orderBy('daerah')
            ->orderBy('gejala')
            ->get();
        
        // Get statistics
        $stats = [
            'total_hama' => HamaPenyakit::where('jenis_tanaman', $plantType)->where('terjangkit', 'Hama')->count(),
            'total_penyakit' => HamaPenyakit::where('jenis_tanaman', $plantType)->where('terjangkit', 'Penyakit')->count(),
            'total_gejala' => $gejala->count(),
            'gejala_by_part' => $gejala->groupBy('daerah')->map->count()
        ];
        
        return view('deteksi.index', compact('gejala', 'stats', 'plantType'));
    }

    public function diagnose(Request $request)
    {
        $request->validate([
            'gejala' => 'required|array|min:1',
            'gejala.*' => 'exists:gejalas,id_gejala'
        ]);

        $gejalaIds = $request->gejala; // array of string codes (id_gejala)
        $gejalaNumericIds = Gejala::whereIn('id_gejala', (array)$gejalaIds)->pluck('id')->all();
        $plantType = $request->get('plant_type', 'Kedelai');
        
        // Get gejala data to determine plant types
        $gejalaData = Gejala::whereIn('id_gejala', $gejalaIds)->get();
        $plantTypes = $gejalaData->pluck('jenis_tanaman')->unique();
        
        // Build query for multiple plant types
        $hamaPenyakitQuery = HamaPenyakit::query();
        
        if ($plantTypes->isNotEmpty()) {
            $hamaPenyakitQuery->whereIn('jenis_tanaman', $plantTypes);
        } else {
            $hamaPenyakitQuery->where('jenis_tanaman', $plantType);
        }
        
        $results = $hamaPenyakitQuery->with(['gejala', 'insektisida'])
            ->get()
            ->map(function ($hamaPenyakit) use ($gejalaIds, $gejalaNumericIds) {
                $matchedSymptoms = $hamaPenyakit->getMatchedSymptoms($gejalaIds);
                
                if ($matchedSymptoms->isEmpty()) {
                    return null; 
                }
                
                $confidenceScore = $this->calculateConfidenceScore($hamaPenyakit, $gejalaNumericIds);
                
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
                            'daerah' => $gejala->daerah,
                            'jenis_tanaman' => $gejala->jenis_tanaman
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
            // Resolve provided string codes (id_gejala) to numeric primary keys
            $gejalaNumericIds = Gejala::whereIn('id_gejala', (array)$gejalaIds)->pluck('id');

            $results = HamaPenyakit::with(['gejala', 'insektisida'])
                ->get()
                ->map(function ($hamaPenyakit) use ($gejalaNumericIds) {
                    // Match by numeric gejala IDs to align with pivot (gejala_id)
                    $matchedSymptoms = $hamaPenyakit->gejala()->whereIn('gejalas.id', $gejalaNumericIds)->get();

                    if ($matchedSymptoms->isEmpty()) {
                        return null; // only include if there is a match
                    }

                    $confidenceScore = $this->calculateConfidenceScore($hamaPenyakit, $gejalaNumericIds->all());

                    // Attach computed fields for the view
                    $hamaPenyakit->confidence_score = $confidenceScore;
                    $hamaPenyakit->matched_symptoms = $matchedSymptoms;

                    return $hamaPenyakit;
                })
                ->filter()
                ->sortByDesc('confidence_score')
                ->values();
        }

        return view('deteksi.hasil', compact('results', 'gejalaIds'));
    }

    private function calculateConfidenceScore(HamaPenyakit $hamaPenyakit, array $gejalaIds): float
    {
        $allGejalaCount = $hamaPenyakit->gejala()->count();
        // Treat provided IDs as numeric IDs from gejalas.id
        $matchedGejala = $hamaPenyakit->gejala()->whereIn('gejalas.id', $gejalaIds)->get();
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