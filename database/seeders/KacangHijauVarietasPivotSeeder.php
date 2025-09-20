<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\KomKacangHijau;
use App\Models\VarietasKacangHijau;

class KacangHijauVarietasPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID varietas kacang hijau secara dinamis
        $varietasKacangHijauIds = VarietasKacangHijau::pluck('id');
        
        // Mengambil ID dari entri pertama di tabel kom_kacang_hijau
        // Karena tabel ini spesifik untuk kacang hijau, kita mengasumsikan ada satu entri
        $komKacangHijauId = KomKacangHijau::first()->id ?? null;

        // Periksa apakah ID komoditas kacang hijau ditemukan
        if (!$komKacangHijauId) {
            echo "Warning: Tidak ada data di tabel kom_kacang_hijau. Pastikan KomKacangHijauSeeder telah dijalankan.\n";
            return;
        }

        $pivotData = [];

        // Hubungkan semua varietas dengan satu ID komoditas 'Kacang Hijau'
        foreach ($varietasKacangHijauIds as $idVarietas) {
            $pivotData[] = [
                'kom_kacang_hijau_id' => $komKacangHijauId,
                'varietas_kacang_hijau_id' => $idVarietas,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Periksa apakah ada data untuk dimasukkan
        if (!empty($pivotData)) {
            DB::table('kacang_hijau_varietas_pivot')->insert($pivotData);
            echo "Successfully seeded " . count($pivotData) . " entries into kacang_hijau_varietas_pivot.\n";
        } else {
            echo "Warning: Tidak ada varietas kacang hijau yang ditemukan untuk seeding pivot.\n";
        }
    }
}
