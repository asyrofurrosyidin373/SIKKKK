<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VarietasKedelai;
use App\Models\VarietasKacangTanah;
use App\Models\VarietasKacangHijau;
use App\Models\OrgPenTan;
use App\Models\TabKecamatan;

class SearchController extends Controller
{
    public function globalSearch(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json(['error' => 'Query parameter required'], 400);
        }
        
        $results = [
            'varietas_kedelai' => VarietasKedelai::where('nama_varietas', 'LIKE', "%{$query}%")
                ->orWhere('galur', 'LIKE', "%{$query}%")
                ->limit(5)->get(),
                
            'varietas_kacang_tanah' => VarietasKacangTanah::where('nama_varietas', 'LIKE', "%{$query}%")
                ->orWhere('galur', 'LIKE', "%{$query}%")
                ->limit(5)->get(),
                
            'varietas_kacang_hijau' => VarietasKacangHijau::where('nama_varietas', 'LIKE', "%{$query}%")
                ->orWhere('galur', 'LIKE', "%{$query}%")
                ->limit(5)->get(),
                
            'opt' => OrgPenTan::where('nama_opt', 'LIKE', "%{$query}%")
                ->limit(5)->get(),
                
            'kecamatan' => TabKecamatan::with('kabupaten.provinsi')
                ->where('nama_kecamatan', 'LIKE', "%{$query}%")
                ->limit(5)->get()
        ];
        
        return response()->json($results);
    }
}