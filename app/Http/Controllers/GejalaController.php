<?php
// app/Http/Controllers/GejalaController.php

namespace App\Http\Controllers;

use App\Models\Gejala;
use Illuminate\Http\Request;

class GejalaController extends Controller
{
    public function index(Request $request)
    {
        $query = Gejala::kedelai()->with(['hamaPenyakit']);
        
        // Filter by area
        if ($request->filled('daerah')) {
            $query->where('daerah', $request->daerah);
        }
        
        // Search by description
        if ($request->filled('search')) {
            $query->where('gejala', 'like', '%' . $request->search . '%');
        }
        
        $gejalas = $query->orderBy('daerah')
                         ->orderBy('gejala')
                         ->paginate(15);
        
        $daerahOptions = ['Akar', 'Batang', 'Daun'];
        
        return view('gejala.index', compact('gejalas', 'daerahOptions'));
    }

    public function show(Gejala $gejala)
    {
        $gejala->load(['hamaPenyakit']);
        
        return view('gejala.show', compact('gejala'));
    }
}