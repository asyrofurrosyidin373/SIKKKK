<?php

// app/Http/Controllers/HomeController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TabProvinsi;
use App\Models\TabKabupaten;
use App\Models\TabKecamatan;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'total_provinsi' => TabProvinsi::count(),
            'total_kabupaten' => TabKabupaten::count(),
            'total_kecamatan' => TabKecamatan::count(),
        ];
        
        return view('home.index', compact('stats'));
    }
    
    public function peta()
    {
        $provinsi = TabProvinsi::orderBy('nama_provinsi')->get();
        return view('home.peta', compact('provinsi'));
    }
    
    public function getMapData(Request $request)
    {
        $query = TabKecamatan::with([
            'kabupaten.provinsi',
            'komoditasKedelai.varietas',
            'komoditasKacangTanah.varietas',
            'komoditasKacangHijau.varietas'
        ]);
        
        if ($request->provinsi_id) {
            $query->whereHas('kabupaten.provinsi', function($q) use ($request) {
                $q->where('id', $request->provinsi_id);
            });
        }
        
        if ($request->kabupaten_id) {
            $query->where('tab_kabupaten_id', $request->kabupaten_id);
        }
        
        $kecamatan = $query->get();
        
        return response()->json($kecamatan);
    }
    
    public function search(Request $request)
    {
        // Global search implementation
        $query = $request->get('q');
        
        return view('search.results', compact('query'));
    }
}
