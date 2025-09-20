<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\KomKedelai;
use App\Models\VarietasKedelai;

class KedelaiVarietasPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID varietas kedelai secara dinamis
        $varietasKedelaiIds = VarietasKedelai::pluck('id');
        
        // Mengambil ID dari entri pertama di tabel kom_kedelai
        // Karena tabel ini spesifik untuk kedelai, kita mengasumsikan ada satu entri
        $komKedelaiId = KomKedelai::first()->id ?? null;

        // Periksa apakah ID komoditas kedelai ditemukan
        if (!$komKedelaiId) {
            echo "Warning: Tidak ada data di tabel kom_kedelai. Pastikan KomKedelaiSeeder telah dijalankan.\n";
            return;
        }

        $pivotData = [];

        // Hubungkan semua varietas dengan satu ID komoditas 'Kedelai'
        foreach ($varietasKedelaiIds as $idVarietas) {
            $pivotData[] = [
                'kom_kedelai_id' => $komKedelaiId,
                'varietas_kedelai_id' => $idVarietas,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Periksa apakah ada data untuk dimasukkan
        if (!empty($pivotData)) {
            DB::table('kedelai_varietas_pivot')->insert($pivotData);
            echo "Successfully seeded " . count($pivotData) . " entries into kedelai_varietas_pivot.\n";
        } else {
            echo "Warning: Tidak ada varietas kedelai yang ditemukan untuk seeding pivot.\n";
        }
    }
}
