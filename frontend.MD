<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TabProvinsi;
use App\Models\TabKabupaten;
use App\Models\TabKecamatan;
use App\Models\VarietasKedelai;
use App\Models\VarietasKacangTanah;
use App\Models\VarietasKacangHijau;
use App\Models\LaporanDeteksi;
use App\Models\KomKedelai;
use App\Models\KomKacangTanah;
use App\Models\KomKacangHijau;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'provinsi_count' => TabProvinsi::count(),
            'kecamatan_count' => TabKecamatan::count(),
            'varietas_kedelai_count' => VarietasKedelai::count(),
            'varietas_kacang_tanah_count' => VarietasKacangTanah::count(),
            'varietas_kacang_hijau_count' => VarietasKacangHijau::count(),
            'laporan_count' => LaporanDeteksi::count(),
        ];

        $provinsi = TabProvinsi::all();
        return view('home', compact('stats', 'provinsi'));
    }

    public function getProvinsi()
    {
        $provinsi = TabProvinsi::all();
        return response()->json($provinsi);
    }

    public function getKabupaten($provinsiId)
    {
        $kabupaten = TabKabupaten::where('tab_provinsi_id', $provinsiId)->get();
        return response()->json($kabupaten);
    }

    public function getKecamatan($kabupatenId)
    {
        $kecamatan = TabKecamatan::where('tab_kabupaten_id', $kabupatenId)
            ->with(['komKedelai', 'komKacangTanah', 'komKacangHijau'])
            ->get();
        return response()->json($kecamatan);
    }

    public function getKomoditasData($type, $kecamatanId)
    {
        $data = null;
        switch ($type) {
            case 'kedelai':
                $data = KomKedelai::where('tab_kecamatan_id', $kecamatanId)->first();
                break;
            case 'kacang_tanah':
                $data = KomKacangTanah::where('tab_kecamatan_id', $kecamatanId)->first();
                break;
            case 'kacang_hijau':
                $data = KomKacangHijau::where('tab_kecamatan_id', $kecamatanId)->first();
                break;
        }
        
        return response()->json($data);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $jenis = $request->get('jenis', 'all');

        $results = [];

        if ($jenis == 'all' || $jenis == 'kedelai') {
            $kedelai = KomKedelai::with('kecamatan')
                ->whereHas('kecamatan', function($q) use ($query) {
                    $q->where('nama', 'like', "%{$query}%");
                })->get();
            $results['kedelai'] = $kedelai;
        }

        if ($jenis == 'all' || $jenis == 'kacang_tanah') {
            $kacangTanah = KomKacangTanah::with('kecamatan')
                ->whereHas('kecamatan', function($q) use ($query) {
                    $q->where('nama', 'like', "%{$query}%");
                })->get();
            $results['kacang_tanah'] = $kacangTanah;
        }

        if ($jenis == 'all' || $jenis == 'kacang_hijau') {
            $kacangHijau = KomKacangHijau::with('kecamatan')
                ->whereHas('kecamatan', function($q) use ($query) {
                    $q->where('nama', 'like', "%{$query}%");
                })->get();
            $results['kacang_hijau'] = $kacangHijau;
        }

        return response()->json($results);
    }

    public function getKecamatanDetail($id)
    {
        $kecamatan = TabKecamatan::with([
            'komKedelai',
            'komKacangTanah',
            'komKacangHijau'
        ])->findOrFail($id);

        return response()->json($kecamatan);
    }
}
<br>
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OrgPenTan;
use App\Models\Gejala;
use App\Models\Pengendalian;
use App\Models\Tanaman;
use App\Models\LaporanDeteksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeteksiController extends Controller
{
    public function index()
    {
        // Get all gejala and group them by bagian_tanaman
        $gejala = Gejala::select('id', 'deskripsi', 'bagian_tanaman')
            ->orderBy('bagian_tanaman')
            ->orderBy('deskripsi')
            ->get();
            
        return view('deteksi.index', compact('gejala'));
    }

    public function getGejala(Request $request)
    {
        $bagian = $request->get('bagian_tanaman');
        
        $query = Gejala::with('organisme');
        
        if ($bagian) {
            $query->where('bagian_tanaman', $bagian);
        }
        
        $gejala = $query->get();
        
        return response()->json([
            'success' => true,
            'data' => $gejala
        ]);
    }

    public function diagnosa(Request $request)
    {
        $gejalaIds = $request->get('gejala_ids', []);
        
        if (empty($gejalaIds)) {
            return response()->json(['error' => 'Pilih minimal satu gejala'], 400);
        }

        // Find organisms that have the selected symptoms
        $organisme = OrgPenTan::whereHas('gejala', function($query) use ($gejalaIds) {
            $query->whereIn('gejala.id', $gejalaIds);
        })->with(['gejala', 'pengendalian.insektisida'])
          ->get();

        // Calculate match percentage for each organism
        $results = $organisme->map(function($org) use ($gejalaIds) {
            $matchingSymptoms = $org->gejala->whereIn('id', $gejalaIds)->count();
            $totalSymptoms = $org->gejala->count();
            $matchPercentage = $totalSymptoms > 0 ? ($matchingSymptoms / $totalSymptoms) * 100 : 0;
            
            return [
                'organisme' => $org,
                'match_percentage' => round($matchPercentage, 2),
                'matching_symptoms' => $matchingSymptoms,
                'total_symptoms' => $totalSymptoms
            ];
        })->sortByDesc('match_percentage');

        return response()->json([
            'success' => true,
            'data' => $results->values()
        ]);
    }

    public function submitLaporan(Request $request)
    {
        $request->validate([
            'tanaman_id' => 'required|exists:tanaman,id',
            'org_pen_tan_id' => 'nullable|exists:org_pen_tan,id',
            'varietas_id' => 'nullable|string',
            'foto' => 'nullable|image|max:5120',
            'deskripsi' => 'required|string',
            'lokasi' => 'required|string'
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('laporan-deteksi', 'public');
        }

        $laporan = LaporanDeteksi::create([
            'user_id' => Auth::id(),
            'tanaman_id' => $request->tanaman_id,
            'org_pen_tan_id' => $request->org_pen_tan_id,
            'varietas_id' => $request->varietas_id,
            'foto_path' => $fotoPath,
            'deskripsi' => $request->deskripsi,
            'lokasi' => $request->lokasi,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil dikirim',
            'data' => $laporan
        ]);
    }

    public function getTanaman()
    {
        $tanaman = Tanaman::all();
        
        return response()->json([
            'success' => true,
            'data' => $tanaman
        ]);
    }
}

<br>
<?php

// app/Http/Controllers/MapController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TabProvinsi;
use App\Models\TabKabupaten;
use App\Models\TabKecamatan;
use App\Models\TabBulan;
use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        return view('map.index');
    }

    public function searchLocation(Request $request)
    {
        $query = $request->get('query');
        $type = $request->get('type', 'kecamatan'); // provinsi, kabupaten, kecamatan
        
        switch ($type) {
            case 'provinsi':
                $results = TabProvinsi::where('nama_provinsi', 'LIKE', "%{$query}%")
                    ->with('kabupaten')
                    ->get();
                break;
                
            case 'kabupaten':
                $results = TabKabupaten::where('nama_kabupaten', 'LIKE', "%{$query}%")
                    ->with(['provinsi', 'kecamatan'])
                    ->get();
                break;
                
            default:
                $results = TabKecamatan::where('nama_kecamatan', 'LIKE', "%{$query}%")
                    ->with([
                        'kabupaten.provinsi',
                        'komoditasKedelai.varietas',
                        'komoditasKacangTanah.varietas',
                        'komoditasKacangHijau.varietas'
                    ])
                    ->get();
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $results,
            'type' => $type
        ]);
    }

    public function getKecamatanDetail($id)
    {
        $kecamatan = TabKecamatan::with([
            'kabupaten.provinsi',
            'komoditasKedelai.varietas',
            'komoditasKacangTanah.varietas',
            'komoditasKacangHijau.varietas'
        ])->findOrFail($id);

        // Get bulan names
        $bulanHujan = [];
        $bulanKering = [];
        $waktuTanamKedelai = [];
        $waktuTanamKacangTanah = [];
        $waktuTanamKacangHijau = [];

        if ($kecamatan->bulan_hujan) {
            $bulanHujan = TabBulan::whereIn('id', $kecamatan->bulan_hujan)
                ->pluck('nama_bulan')->toArray();
        }
        
        if ($kecamatan->bulan_kering) {
            $bulanKering = TabBulan::whereIn('id', $kecamatan->bulan_kering)
                ->pluck('nama_bulan')->toArray();
        }

        if ($kecamatan->rekomendasi_waktu_tanam_kedelai) {
            $waktuTanamKedelai = TabBulan::whereIn('id', $kecamatan->rekomendasi_waktu_tanam_kedelai)
                ->pluck('nama_bulan')->toArray();
        }

        if ($kecamatan->rekomendasi_waktu_tanam_kacang_tanah) {
            $waktuTanamKacangTanah = TabBulan::whereIn('id', $kecamatan->rekomendasi_waktu_tanam_kacang_tanah)
                ->pluck('nama_bulan')->toArray();
        }

        if ($kecamatan->rekomendasi_waktu_tanam_kacang_hijau) {
            $waktuTanamKacangHijau = TabBulan::whereIn('id', $kecamatan->rekomendasi_waktu_tanam_kacang_hijau)
                ->pluck('nama_bulan')->toArray();
        }

        return response()->json([
            'success' => true,
            'data' => array_merge($kecamatan->toArray(), [
                'bulan_hujan_nama' => $bulanHujan,
                'bulan_kering_nama' => $bulanKering,
                'waktu_tanam_kedelai_nama' => $waktuTanamKedelai,
                'waktu_tanam_kacang_tanah_nama' => $waktuTanamKacangTanah,
                'waktu_tanam_kacang_hijau_nama' => $waktuTanamKacangHijau,
            ])
        ]);
    }
}

<br>
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TabProvinsi;
use App\Models\TabKabupaten;
use App\Models\TabKecamatan;
use App\Models\VarietasKedelai;
use App\Models\VarietasKacangTanah;
use App\Models\VarietasKacangHijau;
use App\Models\LaporanDeteksi;
use App\Models\KomKedelai;
use App\Models\KomKacangTanah;
use App\Models\KomKacangHijau;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'provinsi_count' => TabProvinsi::count(),
            'kecamatan_count' => TabKecamatan::count(),
            'varietas_kedelai_count' => VarietasKedelai::count(),
            'varietas_kacang_tanah_count' => VarietasKacangTanah::count(),
            'varietas_kacang_hijau_count' => VarietasKacangHijau::count(),
            'laporan_count' => LaporanDeteksi::count(),
        ];

        $provinsi = TabProvinsi::all();
        return view('home', compact('stats', 'provinsi'));
    }

    public function getProvinsi()
    {
        $provinsi = TabProvinsi::all();
        return response()->json($provinsi);
    }

    public function getKabupaten($provinsiId)
    {
        $kabupaten = TabKabupaten::where('tab_provinsi_id', $provinsiId)->get();
        return response()->json($kabupaten);
    }

    public function getKecamatan($kabupatenId)
    {
        $kecamatan = TabKecamatan::where('tab_kabupaten_id', $kabupatenId)
            ->with(['komKedelai', 'komKacangTanah', 'komKacangHijau'])
            ->get();
        return response()->json($kecamatan);
    }

    public function getKomoditasData($type, $kecamatanId)
    {
        $data = null;
        switch ($type) {
            case 'kedelai':
                $data = KomKedelai::where('tab_kecamatan_id', $kecamatanId)->first();
                break;
            case 'kacang_tanah':
                $data = KomKacangTanah::where('tab_kecamatan_id', $kecamatanId)->first();
                break;
            case 'kacang_hijau':
                $data = KomKacangHijau::where('tab_kecamatan_id', $kecamatanId)->first();
                break;
        }
        
        return response()->json($data);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $jenis = $request->get('jenis', 'all');

        $results = [];

        if ($jenis == 'all' || $jenis == 'kedelai') {
            $kedelai = KomKedelai::with('kecamatan')
                ->whereHas('kecamatan', function($q) use ($query) {
                    $q->where('nama', 'like', "%{$query}%");
                })->get();
            $results['kedelai'] = $kedelai;
        }

        if ($jenis == 'all' || $jenis == 'kacang_tanah') {
            $kacangTanah = KomKacangTanah::with('kecamatan')
                ->whereHas('kecamatan', function($q) use ($query) {
                    $q->where('nama', 'like', "%{$query}%");
                })->get();
            $results['kacang_tanah'] = $kacangTanah;
        }

        if ($jenis == 'all' || $jenis == 'kacang_hijau') {
            $kacangHijau = KomKacangHijau::with('kecamatan')
                ->whereHas('kecamatan', function($q) use ($query) {
                    $q->where('nama', 'like', "%{$query}%");
                })->get();
            $results['kacang_hijau'] = $kacangHijau;
        }

        return response()->json($results);
    }

    public function getKecamatanDetail($id)
    {
        $kecamatan = TabKecamatan::with([
            'komKedelai',
            'komKacangTanah',
            'komKacangHijau'
        ])->findOrFail($id);

        return response()->json($kecamatan);
    }
}


<br>

DETEKSI : 
@extends('layouts.app')

@section('styles')
<style>
    .symptoms-section {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .results-section {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .plant-part {
        margin-bottom: 20px;
    }
    .symptom-item {
        padding: 10px;
        margin: 5px 0;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        background: white;
    }
    .result-item {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
    }
    .similarity-badge {
        font-size: 1.2em;
        padding: 5px 10px;
        border-radius: 15px;
        margin-bottom: 10px;
        display: inline-block;
    }
    .control-section {
        background: #f8f9fa;
        border-radius: 5px;
        padding: 10px;
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Deteksi Hama dan Penyakit</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="symptoms-section">
                <h4 class="mb-4">Pilih Gejala yang Diamati</h4>
                <form id="detectionForm">
                    @php
                        $groupedGejala = $gejala->groupBy('bagian_tanaman');
                    @endphp

                    @forelse($groupedGejala as $bagian => $gejalaList)
                        <div class="plant-part">
                            <h5>{{ ucfirst($bagian ?: 'Umum') }}</h5>
                            @foreach($gejalaList as $g)
                                <div class="symptom-item">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="gejala[]" value="{{ $g->id }}" 
                                               id="gejala{{ $g->id }}">
                                        <label class="form-check-label" for="gejala{{ $g->id }}">
                                            {{ $g->deskripsi }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <div class="alert alert-info">
                            Belum ada data gejala yang tersedia.
                        </div>
                    @endforelse

                    <button type="submit" class="btn btn-primary w-100 mt-3">Diagnosa</button>
                </form>
            </div>
        </div>

        <div class="col-md-7">
            <div class="results-section">
                <h4>Hasil Diagnosa</h4>
                <div id="detectionResults" class="mt-3">
                    Pilih gejala yang teramati untuk memulai diagnosa
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const detectionForm = document.getElementById('detectionForm');
    
    detectionForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(detectionForm);
        
        fetch('/deteksi/diagnose', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                gejala: Array.from(formData.getAll('gejala[]'))
            })
        })
        .then(response => response.json())
        .then(data => displayResults(data.results));
    });
});

function displayResults(results) {
    const container = document.getElementById('detectionResults');
    if (!results.length) {
        container.innerHTML = '<p>Tidak ditemukan hama atau penyakit yang sesuai dengan gejala yang dipilih</p>';
        return;
    }

    let html = '';
    results.forEach(result => {
        const opt = result.opt;
        const similarityClass = getSimilarityClass(result.similarity);
        
        html += `
            <div class="result-item">
                <div class="similarity-badge ${similarityClass}">
                    Kemiripan: ${Math.round(result.similarity)}%
                </div>
                <h5>${opt.nama_opt}</h5>
                <p><strong>Jenis:</strong> ${opt.jenis}</p>
                <p><strong>Gejala yang cocok:</strong> ${result.matching_gejala} dari ${result.total_gejala} gejala</p>
                
                ${opt.pengendalian.length ? `
                    <div class="control-section">
                        <h6>Cara Pengendalian:</h6>
                        <ul>
                            ${opt.pengendalian.map(p => `
                                <li>
                                    <strong>${p.jenis}:</strong> ${p.deskripsi}
                                </li>
                            `).join('')}
                        </ul>
                    </div>
                ` : ''}
            </div>
        `;
    });

    container.innerHTML = html;
}

function getSimilarityClass(similarity) {
    if (similarity >= 80) return 'bg-success text-white';
    if (similarity >= 60) return 'bg-warning';
    return 'bg-danger text-white';
}
</script>
@endsection
<BR>

VARIETAS : 
@extends('layouts.app')

@section('styles')
<style>
    .filter-card {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .results-card {
        background: white;
        border-radius: 8px;
        padding: 20px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .range-input {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    .varietas-item {
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Pencarian Varietas</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="filter-card">
                <form id="filterForm">
                    <div class="mb-3">
                        <label class="form-label">Pilih Komoditas</label>
                        <select class="form-select" id="commodityType" name="type" required>
                            <option value="">Pilih Komoditas</option>
                            <option value="kedelai">Kedelai</option>
                            <option value="kacang_tanah">Kacang Tanah</option>
                            <option value="kacang_hijau">Kacang Hijau</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tahun</label>
                        <input type="number" class="form-control" name="tahun" min="1900" max="2100">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Potensi Hasil (ton/ha)</label>
                        <div class="range-input">
                            <input type="number" class="form-control" name="potensi_hasil[min]" placeholder="Min" step="0.01">
                            <span>-</span>
                            <input type="number" class="form-control" name="potensi_hasil[max]" placeholder="Max" step="0.01">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rata-rata Hasil (ton/ha)</label>
                        <div class="range-input">
                            <input type="number" class="form-control" name="rata_hasil[min]" placeholder="Min" step="0.01">
                            <span>-</span>
                            <input type="number" class="form-control" name="rata_hasil[max]" placeholder="Max" step="0.01">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Umur Berbunga (hari)</label>
                        <div class="range-input">
                            <input type="number" class="form-control" name="umur_berbunga[min]" placeholder="Min">
                            <span>-</span>
                            <input type="number" class="form-control" name="umur_berbunga[max]" placeholder="Max">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Umur Masak (hari)</label>
                        <div class="range-input">
                            <input type="number" class="form-control" name="umur_masak[min]" placeholder="Min">
                            <span>-</span>
                            <input type="number" class="form-control" name="umur_masak[max]" placeholder="Max">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tinggi Tanaman (cm)</label>
                        <div class="range-input">
                            <input type="number" class="form-control" name="tinggi_tanaman[min]" placeholder="Min">
                            <span>-</span>
                            <input type="number" class="form-control" name="tinggi_tanaman[max]" placeholder="Max">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bobot (g/100 biji)</label>
                        <div class="range-input">
                            <input type="number" class="form-control" name="bobot[min]" placeholder="Min" step="0.01">
                            <span>-</span>
                            <input type="number" class="form-control" name="bobot[max]" placeholder="Max" step="0.01">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kadar Lemak (%)</label>
                        <div class="range-input">
                            <input type="number" class="form-control" name="kadar_lemak[min]" placeholder="Min" step="0.01">
                            <span>-</span>
                            <input type="number" class="form-control" name="kadar_lemak[max]" placeholder="Max" step="0.01">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Cari Varietas</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="results-card">
                <h4>Hasil Pencarian</h4>
                <div id="searchResults" class="mt-3">
                    Pilih komoditas dan atur filter untuk mencari varietas
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Varietas Details -->
<div class="modal fade" id="varietasDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Varietas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="varietasDetailContent">
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filterForm');
    
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(filterForm);
        const searchParams = new URLSearchParams();
        
        for (const [key, value] of formData.entries()) {
            searchParams.append(key, value);
        }
        
        fetch(`/varietas/search?${searchParams.toString()}`)
            .then(response => response.json())
            .then(data => displayResults(data));
    });
});

function displayResults(results) {
    const container = document.getElementById('searchResults');
    if (!results.length) {
        container.innerHTML = '<p>Tidak ada varietas yang sesuai dengan kriteria</p>';
        return;
    }

    let html = '<div class="row">';
    results.forEach(varietas => {
        html += `
            <div class="col-md-6 mb-3">
                <div class="varietas-item">
                    <h5>${varietas.nama_varietas}</h5>
                    <p>Tahun: ${varietas.tahun || '-'}</p>
                    <p>Potensi Hasil: ${varietas.potensi_hasil || '-'} ton/ha</p>
                    <button class="btn btn-sm btn-primary" onclick="showVarietasDetail('${varietas.id}')">
                        Lihat Detail
                    </button>
                </div>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
}

function showVarietasDetail(id) {
    const type = document.getElementById('commodityType').value;
    fetch(`/varietas/detail/${id}?type=${type}`)
        .then(response => response.json())
        .then(data => {
            const modal = new bootstrap.Modal(document.getElementById('varietasDetailModal'));
            document.getElementById('varietasDetailContent').innerHTML = createVarietasDetailContent(data);
            modal.show();
        });
}

function createVarietasDetailContent(varietas) {
    return `
        <table class="table">
            <tr><th>Nama Varietas</th><td>${varietas.nama_varietas}</td></tr>
            <tr><th>Tahun</th><td>${varietas.tahun || '-'}</td></tr>
            <tr><th>SK</th><td>${varietas.sk || '-'}</td></tr>
            <tr><th>Galur</th><td>${varietas.galur || '-'}</td></tr>
            <tr><th>Asal</th><td>${varietas.asal || '-'}</td></tr>
            <tr><th>Potensi Hasil</th><td>${varietas.potensi_hasil || '-'} ton/ha</td></tr>
            <tr><th>Rata-rata Hasil</th><td>${varietas.rata_hasil || '-'} ton/ha</td></tr>
            <tr><th>Umur Berbunga</th><td>${varietas.umur_berbunga || '-'} hari</td></tr>
            <tr><th>Umur Masak</th><td>${varietas.umur_masak || '-'} hari</td></tr>
            <tr><th>Tinggi Tanaman</th><td>${varietas.tinggi_tanaman || '-'} cm</td></tr>
            <tr><th>Bobot</th><td>${varietas.bobot || '-'} g/100 biji</td></tr>
            <tr><th>Kadar Lemak</th><td>${varietas.kadar_lemak || '-'}%</td></tr>
        </table>
    `;
}
</script>
@endsection


<BR>

@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Search Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-search me-2"></i>Filter Pencarian</h5>
                </div>
                <div class="card-body">
                    <form id="searchForm">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Provinsi</label>
                                <select class="form-select" id="provinsi" name="provinsi">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kabupaten</label>
                                <select class="form-select" id="kabupaten" name="kabupaten" disabled>
                                    <option value="">Pilih Kabupaten</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kecamatan</label>
                                <select class="form-select" id="kecamatan" name="kecamatan" disabled>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-search me-2"></i>Cari
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Map -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-map me-2"></i>Peta Tematik</h5>
                </div>
                <div class="card-body">
                    <div id="map" style="height: 500px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>Data Wilayah</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Wilayah</th>
                                    <th>Luas Tanam (Ha)</th>
                                    <th>Produktivitas (Ton/Ha)</th>
                                    <th>Total Produksi (Ton)</th>
                                </tr>
                            </thead>
                            <tbody id="resultTable">
                                <tr>
                                    <td colspan="4" class="text-center">Pilih wilayah untuk melihat data</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('map').setView([-7.5360639, 112.2384017], 8);
    
    // Add tile layer
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);
});
</script>
@endsection
  
  <br>
  @extends('layouts.app')

@section('content')
<div class="container">
    <div class="jumbotron bg-primary text-white text-center py-5 rounded mb-4">
        <div class="container">
            <h1 class="display-4"><i class="fas fa-leaf"></i> Sistem Informasi Geografis Pertanian</h1>
            <p class="lead">Platform terpadu untuk informasi peta tematik, database varietas tanaman, dan deteksi hama penyakit</p>
            <hr class="my-4 bg-white">
            <p>Temukan informasi lengkap tentang komoditas unggulan di berbagai wilayah Indonesia</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-map fa-4x text-primary mb-3"></i>
                    <h5 class="card-title">Peta Tematik</h5>
                    <p class="card-text">Jelajahi peta interaktif untuk melihat sebaran komoditas unggulan di berbagai wilayah Indonesia dengan informasi detail tentang kondisi tanah dan rekomendasi waktu tanam.</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('map.index') }}" class="btn btn-primary w-100">
                        <i class="fas fa-map"></i> Buka Peta
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-seedling fa-4x text-success mb-3"></i>
                    <h5 class="card-title">Database Varietas</h5>
                    <p class="card-text">Cari dan bandingkan berbagai varietas kedelai, kacang tanah, dan kacang hijau berdasarkan kriteria yang Anda inginkan seperti potensi hasil, kandungan nutrisi, dan ketahanan hama.</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('varietas.index') }}" class="btn btn-success w-100">
                        <i class="fas fa-seedling"></i> Cari Varietas
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <i class="fas fa-search fa-4x text-warning mb-3"></i>
                    <h5 class="card-title">Deteksi Hama & Penyakit</h5>
                    <p class="card-text">Sistem cerdas untuk mendiagnosa hama dan penyakit tanaman berdasarkan gejala yang terlihat, lengkap dengan rekomendasi metode pengendalian yang tepat.</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('deteksi.index') }}" class="btn btn-warning w-100">
                        <i class="fas fa-search"></i> Mulai Deteksi
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Features Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h2 class="text-center mb-4">Fitur Unggulan</h2>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="d-flex">
                <div class="me-3 text-primary">
                    <i class="fas fa-map-marked-alt fa-2x"></i>
                </div>
                <div>
                    <h5 class="mt-0">Peta Interaktif</h5>
                    <p>Visualisasi data geografis dengan teknologi Leaflet.js yang memungkinkan eksplorasi detail setiap wilayah dengan informasi koordinat yang akurat.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="d-flex">
                <div class="me-3 text-success">
                    <i class="fas fa-filter fa-2x"></i>
                </div>
                <div>
                    <h5 class="mt-0">Filter Canggih</h5>
                    <p>Sistem pencarian dan filtering yang fleksibel untuk menemukan varietas tanaman berdasarkan berbagai kriteria spesifik sesuai kebutuhan.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="d-flex">
                <div class="me-3 text-info">
                    <i class="fas fa-chart-line fa-2x"></i>
                </div>
                <div>
                    <h5 class="mt-0">Data Komprehensif</h5>
                    <p>Database lengkap mencakup informasi tanah, cuaca, komoditas unggulan, dan karakteristik detail setiap varietas tanaman.</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-6 mb-4">
            <div class="d-flex">
                <div class="me-3 text-warning">
                    <i class="fas fa-stethoscope fa-2x"></i>
                </div>
                <div>
                    <h5 class="mt-0">Diagnosa Cerdas</h5>
                    <p>Sistem deteksi hama dan penyakit berbasis gejala dengan tingkat akurasi tinggi dan rekomendasi pengendalian yang tepat sasaran.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
