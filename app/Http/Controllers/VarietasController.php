<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VarietasKedelai;
use App\Models\VarietasKacangTanah;
use App\Models\VarietasKacangHijau;

class VarietasController extends Controller
{
    public function index()
    {
        return view('varietas.index');
    }

    public function kedelai(Request $request)
    {
        $query = VarietasKedelai::with('organisme');

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

        $varietas = $query->paginate(12);
        $tahunList = VarietasKedelai::distinct()->pluck('tahun')->sort();

        return view('varietas.kedelai', [
            'varietas'   => $varietas,
            'tahunList'  => $tahunList,
            'type'       => 'kedelai', // ✅ penting
        ]);
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
}
