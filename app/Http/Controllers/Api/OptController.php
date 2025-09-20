<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrgPenTan;

class OptController extends Controller
{
    public function getAllOpt()
    {
        $opts = OrgPenTan::with(['gejala', 'pengendalian'])->paginate(20);
        return response()->json($opts);
    }
    
    public function getOptDetail($id)
    {
        $opt = OrgPenTan::with([
            'gejala',
            'pengendalian.insektisida',
            'varietasKedelai',
            'varietasKacangTanah',
            'varietasKacangHijau'
        ])->findOrFail($id);
        
        return response()->json($opt);
    }
    
    public function getGejalaByOpt($id)
    {
        $opt = OrgPenTan::with('gejala')->findOrFail($id);
        return response()->json($opt->gejala->groupBy('bagian_tanaman'));
    }
    
    public function getPengendalianByOpt($id)
    {
        $opt = OrgPenTan::with('pengendalian.insektisida')->findOrFail($id);
        return response()->json($opt->pengendalian->groupBy('jenis'));
    }
}