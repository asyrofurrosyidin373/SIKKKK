<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\KomKedelai;
use App\Models\OrgPenTan;

class KedelaiOptPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID dari OrgPenTan secara dinamis
        $orgPenTanIds = OrgPenTan::pluck('id');
        
        // Mengambil ID dari entri pertama di tabel kom_kedelai
        // Asumsi bahwa ada satu entri yang relevan untuk komoditas 'Kedelai'
        $komKedelaiId = KomKedelai::first()->id ?? null;

        // Periksa apakah ID komoditas kedelai ditemukan
        if (!$komKedelaiId) {
            echo "Warning: Tidak ada data di tabel kom_kedelai. Pastikan KomKedelaiSeeder telah dijalankan.\n";
            return;
        }

        $pivotData = [];

        // Hubungkan semua OrgPenTan dengan satu ID komoditas 'Kedelai'
        foreach ($orgPenTanIds as $idOpt) {
            $pivotData[] = [
                'kom_kedelai_id' => $komKedelaiId,
                'org_pen_tan_id' => $idOpt,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Periksa apakah ada data untuk dimasukkan
        if (!empty($pivotData)) {
            DB::table('kedelai_opt_pivot')->insert($pivotData);
            echo "Successfully seeded " . count($pivotData) . " entries into kedelai_opt_pivot.\n";
        } else {
            echo "Warning: Tidak ada OrgPenTan yang ditemukan untuk seeding pivot.\n";
        }
    }
}
