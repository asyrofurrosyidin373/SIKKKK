<?php

namespace App\Http\Controllers;

use App\Models\TabKabupaten;
use App\Models\TabKecamatan;

class RegionController extends Controller
{
    public function getKabupaten($provinsiId)
    {
        $kabupaten = TabKabupaten::where('tab_provinsi_id', $provinsiId)
            ->orderBy('nama_kabupaten')
            ->get(['id', 'nama_kabupaten']);

        return response()->json($kabupaten);
    }

    public function getKecamatan($kabupatenId)
    {
        $kecamatan = TabKecamatan::where('tab_kabupaten_id', $kabupatenId)
            ->orderBy('nama_kecamatan')
            ->get(['id', 'nama_kecamatan']);

        return response()->json($kecamatan);
    }
}
