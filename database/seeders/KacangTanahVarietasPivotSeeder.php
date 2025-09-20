<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\KomKacangTanah;
use App\Models\VarietasKacangTanah;

class KacangTanahVarietasPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID varietas kacang tanah secara dinamis
        $varietasKacangTanahIds = VarietasKacangTanah::pluck('id');
        
        // Mengambil ID dari entri pertama di tabel kom_kacang_tanah
        // Karena tabel ini spesifik untuk kacang tanah, kita mengasumsikan ada satu entri
        $komKacangTanahId = KomKacangTanah::first()->id ?? null;

        // Periksa apakah ID komoditas kacang tanah ditemukan
        if (!$komKacangTanahId) {
            echo "Warning: Tidak ada data di tabel kom_kacang_tanah. Pastikan KomKacangTanahSeeder telah dijalankan.\n";
            return;
        }

        $pivotData = [];

        // Hubungkan semua varietas dengan satu ID komoditas 'Kacang Tanah'
        foreach ($varietasKacangTanahIds as $idVarietas) {
            $pivotData[] = [
                'kom_kacang_tanah_id' => $komKacangTanahId,
                'varietas_kacang_tanah_id' => $idVarietas,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Periksa apakah ada data untuk dimasukkan
        if (!empty($pivotData)) {
            DB::table('kacang_tanah_varietas_pivot')->insert($pivotData);
            echo "Successfully seeded " . count($pivotData) . " entries into kacang_tanah_varietas_pivot.\n";
        } else {
            echo "Warning: Tidak ada varietas kacang tanah yang ditemukan untuk seeding pivot.\n";
        }
    }
}
