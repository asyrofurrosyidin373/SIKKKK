<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrgPenTan extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'org_pen_tan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_opt',
        'jenis',
        'gambar',
    ];

    public function varietasKedelai(): HasMany
    {
        return $this->hasMany(VarietasKedelai::class);
    }

    public function varietasKacangTanah(): HasMany
    {
        return $this->hasMany(VarietasKacangTanah::class);
    }

    public function varietasKacangHijau(): HasMany
    {
        return $this->hasMany(VarietasKacangHijau::class);
    }

    public function laporanDeteksi(): HasMany
    {
        return $this->hasMany(LaporanDeteksi::class);
    }

    public function komoditasKedelai(): BelongsToMany
    {
        return $this->belongsToMany(KomKedelai::class, 'kedelai_opt_pivot', 'org_pen_tan_id', 'kom_kedelai_id');
    }

    public function komoditasKacangTanah(): BelongsToMany
    {
        return $this->belongsToMany(KomKacangTanah::class, 'kacang_tanah_opt_pivot', 'org_pen_tan_id', 'kom_kacang_tanah_id');
    }

    public function komoditasKacangHijau(): BelongsToMany
    {
        return $this->belongsToMany(KomKacangHijau::class, 'kacang_hijau_opt_pivot', 'org_pen_tan_id', 'kom_kacang_hijau_id');
    }

    public function gejala(): BelongsToMany
    {
        return $this->belongsToMany(Gejala::class, 'penyakit_gejala', 'org_pen_tan_id', 'gejala_id');
    }

    public function pengendalian(): BelongsToMany
    {
        return $this->belongsToMany(Pengendalian::class, 'penyakit_pengendalian', 'org_pen_tan_id', 'pengendalian_id');
    }

    // Tambah untuk kompleksitas deteksi: OPT resisted by varietas (many-to-many)
    public function varietasResistensi(): BelongsToMany
    {
        return $this->belongsToMany(VarietasKedelai::class, 'opt_varietas_pivot', 'org_pen_tan_id', 'varietas_id') // Asumsi general varietas, adjust per komoditas if needed
                    ->withPivot('tingkat_resistensi');
    }
}





<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pengendalian extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'pengendalian';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'jenis',
        'deskripsi',
    ];

    public function organisme(): BelongsToMany
    {
        return $this->belongsToMany(OrgPenTan::class, 'penyakit_pengendalian', 'pengendalian_id', 'org_pen_tan_id');
    }

public function insektisida()
{
    return $this->belongsToMany(Insektisida::class, 'pengendalian_insektisida_pivot', 'pengendalian_id', 'insektisida_id');
}

}





<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PenyakitPengendalian extends Pivot
{
    protected $table = 'penyakit_pengendalian';

    protected $fillable = [
        'org_pen_tan_id',
        'pengendalian_id',
    ];
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PenyakitGejala extends Pivot
{
    protected $table = 'penyakit_gejala';

    protected $fillable = [
        'org_pen_tan_id',
        'gejala_id',
    ];
}




<?php
// app/Models/Insektisida.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Insektisida extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_insektisida',
        'nama_insektisida',
        'bahan_aktif',
        'hama_sasaran',
        'dosis',
        'cara_aplikasi'
    ];

    public function hamaPenyakit(): BelongsToMany
    {
        return $this->belongsToMany(HamaPenyakit::class, 'hama_penyakit_insektisida')
                    ->withTimestamps();
    }

    // Method untuk format nama lengkap
    public function getNamaLengkapAttribute(): string
    {
        return "{$this->nama_insektisida} ({$this->bahan_aktif})";
    }

    // Scope untuk pencarian
    public function scopeForHama($query, $hamaNama)
    {
        return $query->where('hama_sasaran', 'like', "%{$hamaNama}%");
    }
}



<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyakit extends Model
{
    /** @use HasFactory<\Database\Factories\PenyakitFactory> */
    use HasFactory;
}



<?php
// app/Models/Gejala.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Gejala extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_gejala',
        'gejala',
        'daerah',
        'jenis_tanaman'
    ];

    protected $casts = [
        'daerah' => 'string',
    ];

    public function hamaPenyakit(): BelongsToMany
    {
        return $this->belongsToMany(HamaPenyakit::class, 'hama_penyakit_gejala')
                    ->withPivot('bobot')
                    ->withTimestamps();
    }

    // Scope untuk filter berdasarkan daerah
    public function scopeAkar($query)
    {
        return $query->where('daerah', 'Akar');
    }

    public function scopeBatang($query)
    {
        return $query->where('daerah', 'Batang');
    }

    public function scopeDaun($query)
    {
        return $query->where('daerah', 'Daun');
    }

    public function scopeKedelai($query)
    {
        return $query->where('jenis_tanaman', 'Kedelai');
    }

    // Method untuk mendapatkan icon berdasarkan daerah
    public function getIconAttribute(): string
    {
        return match($this->daerah) {
            'Akar' => 'fas fa-seedling',
            'Batang' => 'fas fa-tree',
            'Daun' => 'fas fa-leaf',
            default => 'fas fa-question-circle'
        };
    }

    // Method untuk mendapatkan warna berdasarkan daerah
    public function getColorAttribute(): string
    {
        return match($this->daerah) {
            'Akar' => 'text-brown',
            'Batang' => 'text-success',
            'Daun' => 'text-primary',
            default => 'text-muted'
        };
    }
}



ini code halamannya, tadi error pengendalian tidak terddefinisi
@extends('layout.app')

@section('content')
    <div class="container">
        <a href="{{ route('pengendalian.index') }}" class="btn-back">Kembali ke Daftar</a>

        <h1>Detail Pengendalian</h1>

        <div class="detail-item">
            <strong>Jenis:</strong>
            <p>{{ $pengendalian->jenis }}</p>
        </div>

        <div class="detail-item">
            <strong>Deskripsi:</strong>
            <p>{{ $pengendalian->deskripsi }}</p>
        </div>

        @if ($pengendalian->organisme->isNotEmpty())
            <h2>Organisme Terkait</h2>
            <ul class="list-items">
                @foreach ($pengendalian->organisme as $organisme)
                    <li>{{ $organisme->nama }}</li>
                @endforeach
            </ul>
        @endif

        @if ($pengendalian->insektisida->isNotEmpty())
            <h2>Insektisida yang Dianjurkan</h2>
            <ul class="list-items">
                @foreach ($pengendalian->insektisida as $insektisida)
                    <li>{{ $insektisida->nama_dagang }}</li>
                @endforeach
            </ul>
        @endif

    </div>
@endsection


routes:
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\VarietasController;
use App\Http\Controllers\OptController;
use App\Http\Controllers\DeteksiController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HamaPenyakitController;
use App\Http\Controllers\GejalaController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home/Dashboard
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Peta Routes
Route::get('/peta', [HomeController::class, 'peta'])->name('peta');
Route::get('/peta/data', [HomeController::class, 'getMapData'])->name('peta.data');

// Region Routes
Route::get('/kabupaten/{provinsi}', [RegionController::class, 'getKabupaten']);
Route::get('/kecamatan/{kabupaten}', [RegionController::class, 'getKecamatan']);

// Varietas Routes
Route::prefix('varietas')->name('varietas.')->group(function () {
    Route::get('/', [VarietasController::class, 'index'])->name('index');
    Route::get('/kedelai', [VarietasController::class, 'kedelai'])->name('kedelai');
    Route::get('/kacang-tanah', [VarietasController::class, 'kacangTanah'])->name('kacang-tanah');
    Route::get('/kacang-hijau', [VarietasController::class, 'kacangHijau'])->name('kacang-hijau');
    Route::get('/{type}/{id}', [VarietasController::class, 'show'])->name('show');
    Route::get('/varietas/kedelai/{varietas}/detail', [VarietasController::class, 'showKedelaiDetail'])
        ->name('varietas.kedelai.detail');
});

// OPT Routes
Route::prefix('opt')->name('opt.')->group(function () {
    Route::get('/', [OptController::class, 'index'])->name('index');
    Route::get('/{id}', [OptController::class, 'show'])->name('show');
});

// Hama & Penyakit Routes
Route::prefix('hama-penyakit')->name('hama-penyakit.')->group(function () {
    Route::get('/', [HamaPenyakitController::class, 'index'])->name('index');
    Route::get('/{hamaPenyakit}', [HamaPenyakitController::class, 'show'])->name('show');
});

// Alternative routes for backward compatibility
Route::prefix('hama')->name('hama.')->group(function () {
    Route::get('/', [HamaPenyakitController::class, 'index'])->name('index');
    Route::get('/{hamaPenyakit}', [HamaPenyakitController::class, 'show'])->name('show');
});

Route::prefix('penyakit')->name('penyakit.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('hama-penyakit.index', ['jenis' => 'penyakit']);
    })->name('index');
});

// Gejala Routes
Route::prefix('gejala')->name('gejala.')->group(function () {
    Route::get('/', [GejalaController::class, 'index'])->name('index');
    Route::get('/{gejala}', [GejalaController::class, 'show'])->name('show');
});

// Deteksi Routes
Route::prefix('deteksi')->name('deteksi.')->group(function () {
    Route::get('/', [DeteksiController::class, 'index'])->name('index');
    Route::post('/gejala', [DeteksiController::class, 'deteksiGejala'])->name('gejala');
    Route::post('/upload', [DeteksiController::class, 'uploadFoto'])->name('upload');
    Route::post('/diagnose', [DeteksiController::class, 'diagnose'])->name('diagnose');
    Route::get('/hasil', [DeteksiController::class, 'hasil'])->name('hasil');
});

// API Routes for AJAX calls
Route::prefix('api')->name('api.')->group(function () {
    Route::post('/deteksi/diagnose', [DeteksiController::class, 'diagnose'])->name('deteksi.diagnose');
    Route::get('/gejala/search', [GejalaController::class, 'search'])->name('gejala.search');
    Route::get('/hama-penyakit/search', [HamaPenyakitController::class, 'search'])->name('hama-penyakit.search');
});

// Additional utility routes
Route::prefix('pengendalian')->name('pengendalian.')->group(function () {
    Route::get('/', function () {
        return view('pengendalian.index');
    })->name('index');
});

// Search
Route::get('/search', [HomeController::class, 'search'])->name('search');