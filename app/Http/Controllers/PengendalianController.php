<?php

namespace App\Http\Controllers;

use App\Models\Pengendalian;
use Illuminate\Http\Request;

class PengendalianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Pengendalian::with(['organisme', 'insektisida']);
        
        // Filter berdasarkan jenis jika ada
        if ($request->has('jenis') && $request->jenis) {
            $query->where('jenis', $request->jenis);
        }
        
        // Search berdasarkan jenis atau deskripsi
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('jenis', 'like', "%{$search}%")
                  ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }
        
        $pengendalians = $query->paginate(12);
        
        return view('pengendalian.index', compact('pengendalians'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pengendalian = Pengendalian::with(['organisme', 'insektisida'])
            ->findOrFail($id);
            
        return view('pengendalian.show', compact('pengendalian'));
    }
    
    /**
     * Search pengendalian for API calls
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json([]);
        }
        
        $pengendalians = Pengendalian::where('jenis', 'like', "%{$query}%")
            ->orWhere('deskripsi', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'jenis', 'deskripsi']);
            
        return response()->json($pengendalians);
    }
}