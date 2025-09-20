<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gejala;
use App\Models\Tanaman;
use App\Models\LaporanDeteksi;
use App\Models\OrgPenTan;

class DeteksiController extends Controller
{
    public function getGejala()
    {
        $gejalas = Gejala::with('organisme')->get()->groupBy('bagian_tanaman');
        return response()->json($gejalas);
    }
    
    public function deteksiBerdasarkanGejala(Request $request)
    {
        $gejalaIds = $request->input('gejala', []);
        
        if (empty($gejalaIds)) {
            return response()->json(['error' => 'Pilih minimal satu gejala'], 400);
        }
        
        $opts = OrgPenTan::whereHas('gejala', function($query) use ($gejalaIds) {
            $query->whereIn('gejala.id', $gejalaIds);
        })
        ->with(['gejala', 'pengendalian'])
        ->withCount(['gejala' => function($query) use ($gejalaIds) {
            $query->whereIn('gejala.id', $gejalaIds);
        }])
        ->orderBy('gejala_count', 'desc')
        ->get();
        
        return response()->json([
            'opts' => $opts,
            'selected_gejala' => $gejalaIds,
            'confidence_scores' => $opts->map(function($opt) use ($gejalaIds) {
                return [
                    'opt_id' => $opt->id,
                    'confidence' => round(($opt->gejala_count / count($gejalaIds)) * 100)
                ];
            })
        ]);
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
}