<?php

// app/Http/Controllers/MapController.php
namespace App\Http\Controllers;

use App\Models\TabProvinsi;
use App\Models\TabKabupaten; 
use App\Models\TabKecamatan;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $provinces = TabProvinsi::all();
        return view('map.index', compact('provinces'));
    }

    public function getKabupaten($provinsiId)
    {
        $kabupatens = TabKabupaten::where('tab_provinsi_id', $provinsiId)->get();
        return response()->json($kabupatens);
    }

    public function getKecamatan($kabupatenId)
    {
        $kecamatans = TabKecamatan::where('tab_kabupaten_id', $kabupatenId)->get();
        return response()->json($kecamatans);
    }

    public function getKecamatanDetail($kecamatanId)
    {
        $kecamatan = TabKecamatan::with([
            'kabupaten.provinsi',
            'komoditasKedelai.varietas',
            'komoditasKacangTanah.varietas', 
            'komoditasKacangHijau.varietas'
        ])->find($kecamatanId);

        if (!$kecamatan) {
            return response()->json(['error' => 'Kecamatan not found'], 404);
        }

        return response()->json($kecamatan);
    }
}

// app/Http/Controllers/VarietasController.php
namespace App\Http\Controllers;

use App\Models\VarietasKedelai;
use App\Models\VarietasKacangTanah;
use App\Models\VarietasKacangHijau;
use Illuminate\Http\Request;

class VarietasController extends Controller
{
    public function index()
    {
        return view('varietas.index');
    }

    public function search(Request $request)
    {
        $komoditas = $request->get('komoditas');
        $filters = $request->only([
            'min_potensi_hasil', 'max_potensi_hasil',
            'min_umur_masak', 'max_umur_masak',
            'min_kadar_protein', 'max_kadar_protein',
            'min_kadar_lemak', 'max_kadar_lemak'
        ]);

        $results = [];

        // Search Kedelai
        if (!$komoditas || $komoditas === 'kedelai') {
            $query = VarietasKedelai::query();
            $this->applyFilters($query, $filters);
            $kedelai = $query->get()->map(function($item) {
                $item->komoditas_type = 'Kedelai';
                return $item;
            });
            $results = $results->merge($kedelai);
        }

        // Search Kacang Tanah
        if (!$komoditas || $komoditas === 'kacang_tanah') {
            $query = VarietasKacangTanah::query();
            $this->applyFilters($query, $filters);
            $kacangTanah = $query->get()->map(function($item) {
                $item->komoditas_type = 'Kacang Tanah';
                return $item;
            });
            $results = $results->merge($kacangTanah);
        }

        // Search Kacang Hijau
        if (!$komoditas || $komoditas === 'kacang_hijau') {
            $query = VarietasKacangHijau::query();
            $this->applyFilters($query, $filters);
            $kacangHijau = $query->get()->map(function($item) {
                $item->komoditas_type = 'Kacang Hijau';
                return $item;
            });
            $results = $results->merge($kacangHijau);
        }

        return response()->json($results);
    }

    private function applyFilters($query, $filters)
    {
        if (!empty($filters['min_potensi_hasil'])) {
            $query->where('potensi_hasil', '>=', $filters['min_potensi_hasil']);
        }
        if (!empty($filters['max_potensi_hasil'])) {
            $query->where('potensi_hasil', '<=', $filters['max_potensi_hasil']);
        }
        if (!empty($filters['min_umur_masak'])) {
            $query->where('umur_masak', '>=', $filters['min_umur_masak']);
        }
        if (!empty($filters['max_umur_masak'])) {
            $query->where('umur_masak', '<=', $filters['max_umur_masak']);
        }
        if (!empty($filters['min_kadar_protein'])) {
            $query->where('kadar_protein', '>=', $filters['min_kadar_protein']);
        }
        if (!empty($filters['max_kadar_protein'])) {
            $query->where('kadar_protein', '<=', $filters['max_kadar_protein']);
        }
        if (!empty($filters['min_kadar_lemak'])) {
            $query->where('kadar_lemak', '>=', $filters['min_kadar_lemak']);
        }
        if (!empty($filters['max_kadar_lemak'])) {
            $query->where('kadar_lemak', '<=', $filters['max_kadar_lemak']);
        }
    }

    public function show($type, $id)
    {
        $varietas = null;
        
        switch($type) {
            case 'kedelai':
                $varietas = VarietasKedelai::with('organisme')->find($id);
                break;
            case 'kacang_tanah':
                $varietas = VarietasKacangTanah::with('organisme')->find($id);
                break;
            case 'kacang_hijau':
                $varietas = VarietasKacangHijau::with('organisme')->find($id);
                break;
        }

        if (!$varietas) {
            return response()->json(['error' => 'Varietas not found'], 404);
        }

        return response()->json($varietas);
    }
}

// app/Http/Controllers/DeteksiHamaController.php
namespace App\Http\Controllers;

use App\Models\HamaPenyakit;
use App\Models\Gejala;
use App\Models\DeteksiHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DeteksiHamaController extends Controller
{
    public function index()
    {
        $gejalas = Gejala::orderBy('daerah')->get()->groupBy('daerah');
        return view('deteksi.index', compact('gejalas'));
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ]);

        $file = $request->file('image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('detections', $filename, 'public');

        // TODO: Send to AI service for processing
        // For now, return mock data
        return response()->json([
            'success' => true,
            'filename' => $filename,
            'path' => $path,
            'url' => Storage::url($path)
        ]);
    }

    public function detectBySymptoms(Request $request)
    {
        $request->validate([
            'gejala_ids' => 'required|array',
            'gejala_ids.*' => 'exists:gejalas,id'
        ]);

        $gejalaIds = $request->gejala_ids;
        $selectedGejalas = Gejala::whereIn('id', $gejalaIds)->get();

        // Get all pests/diseases
        $hamaPenyakits = HamaPenyakit::with('gejala')->get();
        
        $results = [];
        
        foreach ($hamaPenyakits as $hamaPenyakit) {
            $score = $hamaPenyakit->getConfidenceScore($gejalaIds);
            if ($score > 0) {
                $matchedSymptoms = $hamaPenyakit->getMatchedSymptoms($gejalaIds);
                
                $results[] = [
                    'id' => $hamaPenyakit->id,
                    'nama_penyakit' => $hamaPenyakit->nama_penyakit,
                    'terjangkit' => $hamaPenyakit->terjangkit,
                    'confidence_score' => $score,
                    'matched_symptoms' => $matchedSymptoms,
                    'gambar_url' => $hamaPenyakit->gambar_url,
                    'kultur_teknis' => $hamaPenyakit->kultur_teknis,
                    'fisik_mekanis' => $hamaPenyakit->fisik_mekanis,
                    'hayati' => $hamaPenyakit->hayati,
                    'kimiawi' => $hamaPenyakit->kimiawi,
                    'deskripsi' => $hamaPenyakit->deskripsi
                ];
            }
        }

        // Sort by confidence score descending
        usort($results, function($a, $b) {
            return $b['confidence_score'] <=> $a['confidence_score'];
        });

        // Take top 5 results
        $results = array_slice($results, 0, 5);

        // Save to history
        DeteksiHistory::create([
            'gejala_ids' => $gejalaIds,
            'results' => $results,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'detected_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'selected_symptoms' => $selectedGejalas,
            'results' => $results
        ]);
    }

    public function history()
    {
        $histories = DeteksiHistory::latest()
            ->take(50)
            ->get();
            
        return view('deteksi.history', compact('histories'));
    }

    public function hamaPenyakitDetail($id)
    {
        $hamaPenyakit = HamaPenyakit::with(['gejala', 'insektisida'])->find($id);
        
        if (!$hamaPenyakit) {
            return response()->json(['error' => 'Hama/Penyakit not found'], 404);
        }

        return response()->json($hamaPenyakit);
    }
}

// app/Http/Controllers/DashboardController.php  
namespace App\Http\Controllers;

use App\Models\TabKecamatan;
use App\Models\DeteksiHistory;
use App\Models\HamaPenyakit;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_kecamatan' => TabKecamatan::count(),
            'total_varietas' => $this->getTotalVarietas(),
            'deteksi_today' => DeteksiHistory::today()->count(),
            'deteksi_this_week' => DeteksiHistory::thisWeek()->count(),
            'total_hama_penyakit' => HamaPenyakit::count()
        ];

        $recentDetections = DeteksiHistory::latest()
            ->take(10)
            ->get();

        return view('dashboard.index', compact('stats', 'recentDetections'));
    }

    private function getTotalVarietas()
    {
        return \App\Models\VarietasKedelai::count() + 
               \App\Models\VarietasKacangTanah::count() + 
               \App\Models\VarietasKacangHijau::count();
    }
}