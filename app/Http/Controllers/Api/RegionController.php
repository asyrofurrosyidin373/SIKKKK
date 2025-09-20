<?php

// app/Http/Controllers/Api/RegionController.php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TabProvinsi;
use App\Models\TabKabupaten;
use App\Models\TabKecamatan;

class RegionController extends Controller
{
    public function getProvinsi()
    {
        $provinsi = TabProvinsi::orderBy('nama_provinsi')->get();
        return response()->json($provinsi);
    }
    
    public function getKabupatenByProvinsi($provinsiId)
    {
        $kabupaten = TabKabupaten::where('tab_provinsi_id', $provinsiId)
            ->orderBy('nama_kabupaten')
            ->get();
        return response()->json($kabupaten);
    }
    
    public function getKecamatanByKabupaten($kabupatenId)
    {
        $kecamatan = TabKecamatan::where('tab_kabupaten_id', $kabupatenId)
            ->orderBy('nama_kecamatan')
            ->get();
        return response()->json($kecamatan);
    }
    
    public function getKecamatanDetail($id)
    {
        $kecamatan = TabKecamatan::with([
            'kabupaten.provinsi',
            'komoditasKedelai.varietas',
            'komoditasKacangTanah.varietas',
            'komoditasKacangHijau.varietas'
        ])->findOrFail($id);
        
        // Add processed month names
        $kecamatan->bulan_hujan_nama = $kecamatan->getBulanHujanNamaAttribute();
        $kecamatan->waktu_tanam_kedelai_nama = $kecamatan->getWaktuTanamKedelaiNamaAttribute();
        $kecamatan->waktu_tanam_kacang_tanah_nama = $kecamatan->getWaktuTanamKacangTanahNamaAttribute();
        $kecamatan->waktu_tanam_kacang_hijau_nama = $kecamatan->getWaktuTanamKacangHijauNamaAttribute();
        
        return response()->json($kecamatan);
    }
    
    public function getAllKecamatanForMap()
    {
        $kecamatan = TabKecamatan::with([
            'kabupaten.provinsi',
            'komoditasKedelai',
            'komoditasKacangTanah',
            'komoditasKacangHijau'
        ])->whereNotNull('latitude')
          ->whereNotNull('longitude')
          ->get();
          
        return response()->json($kecamatan);
    }
}