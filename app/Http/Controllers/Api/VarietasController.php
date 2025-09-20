<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\VarietasKedelai;
use App\Models\VarietasKacangTanah;
use App\Models\VarietasKacangHijau;

class VarietasController extends Controller
{
    public function getAllVarietas()
    {
        $kedelai = VarietasKedelai::select('id', 'nama_varietas', 'tahun', 'potensi_hasil')
            ->get()->map(function($item) {
                $item->type = 'kedelai';
                return $item;
            });
            
        $kacangTanah = VarietasKacangTanah::select('id', 'nama_varietas', 'tahun', 'potensi_hasil')
            ->get()->map(function($item) {
                $item->type = 'kacang-tanah';
                return $item;
            });
            
        $kacangHijau = VarietasKacangHijau::select('id', 'nama_varietas', 'tahun', 'potensi_hasil')
            ->get()->map(function($item) {
                $item->type = 'kacang-hijau';
                return $item;
            });
            
        $allVarietas = $kedelai->concat($kacangTanah)->concat($kacangHijau);
        
        return response()->json($allVarietas);
    }
    
    public function getVarietasKedelai()
    {
        $varietas = VarietasKedelai::with('organisme')->paginate(20);
        return response()->json($varietas);
    }
    
    public function getVarietasKacangTanah()
    {
        $varietas = VarietasKacangTanah::with('organisme')->paginate(20);
        return response()->json($varietas);
    }
    
    public function getVarietasKacangHijau()
    {
        $varietas = VarietasKacangHijau::with('organisme')->paginate(20);
        return response()->json($varietas);
    }
    
    public function getVarietasDetail($type, $id)
    {
        $modelClass = match($type) {
            'kedelai' => VarietasKedelai::class,
            'kacang-tanah' => VarietasKacangTanah::class,
            'kacang-hijau' => VarietasKacangHijau::class,
            default => abort(404)
        };
        
        $varietas = $modelClass::with([
            'organisme',
            'komoditas.kecamatan.kabupaten.provinsi'
        ])->findOrFail($id);
        
        return response()->json($varietas);
    }
}
