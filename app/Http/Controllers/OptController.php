<?php
// app/Http/Controllers/OptController.php (atau controller yang sesuai)

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gejala;
use App\Models\Tanaman;

class OptController extends Controller
{
    public function index()
    {
        // Ambil data gejala dan grup berdasarkan bagian tanaman
        $gejalas = Gejala::all()->groupBy('bagian_tanaman');
        
        // Ambil semua data tanaman
        $tanamans = Tanaman::all();
        
        return view('opt.index', compact('gejalas', 'tanamans'));
    }
    
    public function deteksiGejala(Request $request)
    {
        // Logic untuk deteksi berdasarkan gejala
        $selectedGejala = $request->input('gejala', []);
        
        // Proses deteksi...
        
        return view('opt.result', compact('hasil'));
    }
    
    public function deteksiUpload(Request $request)
    {
        // Logic untuk deteksi berdasarkan foto
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'tanaman_id' => 'required|exists:tanamans,id'
        ]);
        
        // Proses upload dan analisis...
        
        return view('opt.result', compact('hasil'));
    }
}