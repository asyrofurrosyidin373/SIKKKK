<?php
// app/Http/Controllers/HamaPenyakitController.php

namespace App\Http\Controllers;

use App\Models\HamaPenyakit;
use Illuminate\Http\Request;

class HamaPenyakitController extends Controller
{
    public function index(Request $request)
    {
        $query = HamaPenyakit::kedelai()->with(['gejala', 'insektisida']);
        
        // Filter by type
        if ($request->filled('jenis')) {
            if ($request->jenis === 'hama') {
                $query->hama();
            } elseif ($request->jenis === 'penyakit') {
                $query->penyakit();
            }
        }
        
        // Search by name
        if ($request->filled('search')) {
            $query->where('nama_penyakit', 'like', '%' . $request->search . '%');
        }
        
        $hamaPenyakits = $query->orderBy('terjangkit')
                              ->orderBy('nama_penyakit')
                              ->paginate(12);
        
        $jenisOptions = ['hama', 'penyakit'];
        
        return view('hama-penyakit.index', compact('hamaPenyakits', 'jenisOptions'));
    }

    public function show(HamaPenyakit $hamaPenyakit)
    {
        $hamaPenyakit->load(['gejala', 'insektisida']);
        
        // Get related hama/penyakit (same type)
        $related = HamaPenyakit::where('id', '!=', $hamaPenyakit->id)
                               ->where('terjangkit', $hamaPenyakit->terjangkit)
                               ->kedelai()
                               ->with(['gejala'])
                               ->take(4)
                               ->get();
        
        return view('hama-penyakit.show', compact('hamaPenyakit', 'related'));
    }
}
