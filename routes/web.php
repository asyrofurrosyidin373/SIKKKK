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
use App\Http\Controllers\PengendalianController; // Tambah import

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

// Pengendalian Routes - Update dengan controller yang proper
Route::prefix('pengendalian')->name('pengendalian.')->group(function () {
    Route::get('/', [PengendalianController::class, 'index'])->name('index');
    Route::get('/{id}', [PengendalianController::class, 'show'])->name('show');
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
    Route::get('/pengendalian/search', [PengendalianController::class, 'search'])->name('pengendalian.search'); // Tambah API search
});

// Search
Route::get('/search', [HomeController::class, 'search'])->name('search');