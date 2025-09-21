<?php

// routes/api.php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RegionController;
use App\Http\Controllers\Api\VarietasController;
use App\Http\Controllers\Api\OptController;
use App\Http\Controllers\Api\DeteksiController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Region/Geographic Routes
Route::prefix('regions')->group(function () {
    Route::get('/provinsi', [RegionController::class, 'getProvinsi']);
    Route::get('/provinsi/{id}/kabupaten', [RegionController::class, 'getKabupatenByProvinsi']);
    Route::get('/kabupaten/{id}/kecamatan', [RegionController::class, 'getKecamatanByKabupaten']);
    Route::get('/kecamatan/{id}', [RegionController::class, 'getKecamatanDetail']);
    Route::get('/kecamatan-map', [RegionController::class, 'getAllKecamatanForMap']);
    Route::get('/stats', [RegionController::class, 'getRegionStats']);
});

// Varietas Routes
Route::prefix('varietas')->group(function () {
    Route::get('/all', [VarietasController::class, 'getAllVarietas']);
    Route::get('/kedelai', [VarietasController::class, 'getVarietasKedelai']);
    Route::get('/kacang-tanah', [VarietasController::class, 'getVarietasKacangTanah']);
    Route::get('/kacang-hijau', [VarietasController::class, 'getVarietasKacangHijau']);
    Route::get('/{type}/{id}', [VarietasController::class, 'getVarietasDetail']);
});

// OPT (Organisme Pengganggu Tanaman) Routes
Route::prefix('opt')->group(function () {
    Route::get('/', [OptController::class, 'getAllOpt']);
    Route::get('/search', [OptController::class, 'search']);
    Route::get('/stats', [OptController::class, 'getStats']);
    Route::get('/{id}', [OptController::class, 'getOptDetail']);
    Route::get('/{id}/gejala', [OptController::class, 'getGejalaByOpt']);
    Route::get('/{id}/pengendalian', [OptController::class, 'getPengendalianByOpt']);
    Route::get('/{id}/related', [OptController::class, 'getRelatedOpts']);
});

// Detection Routes
Route::prefix('deteksi')->group(function () {
    Route::get('/gejala', [DeteksiController::class, 'getGejala']);
    Route::post('/gejala', [DeteksiController::class, 'deteksiBerdasarkanGejala']);
    Route::get('/tanaman', [DeteksiController::class, 'getTanaman']);
    Route::post('/laporan', [DeteksiController::class, 'submitLaporan']);
    Route::post('/ai', [DeteksiController::class, 'detectWithAI']); // AI detection endpoint
    Route::get('/stats', [DeteksiController::class, 'getDetectionStats']);
    Route::get('/history', [DeteksiController::class, 'getDetectionHistory']);
});

// Dashboard Routes
Route::prefix('dashboard')->group(function () {
    Route::get('/stats', [DashboardController::class, 'getStats']);
    Route::get('/charts', [DashboardController::class, 'getChartData']);
});

// Search Routes
Route::get('/search', [SearchController::class, 'globalSearch']);

// Health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now(),
        'version' => '2.0.0'
    ]);
});
