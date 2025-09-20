<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\KomKacangTanah;
use App\Models\OrgPenTan;

class KacangTanahOptPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID dari OrgPenTan secara dinamis
        $orgPenTanIds = OrgPenTan::pluck('id');
        
        // Mengambil ID dari entri pertama di tabel kom_kacang_tanah
        // Asumsi bahwa ada satu entri yang relevan untuk komoditas 'Kacang Tanah'
        $komKacangTanahId = KomKacangTanah::first()->id ?? null;

        // Periksa apakah ID komoditas kacang tanah ditemukan
        if (!$komKacangTanahId) {
            echo "Warning: Tidak ada data di tabel kom_kacang_tanah. Pastikan KomKacangTanahSeeder telah dijalankan.\n";
            return;
        }

        $pivotData = [];

        // Hubungkan semua OrgPenTan dengan satu ID komoditas 'Kacang Tanah'
        foreach ($orgPenTanIds as $idOpt) {
            $pivotData[] = [
                'kom_kacang_tanah_id' => $komKacangTanahId,
                'org_pen_tan_id' => $idOpt,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Periksa apakah ada data untuk dimasukkan
        if (!empty($pivotData)) {
            DB::table('kacang_tanah_opt_pivot')->insert($pivotData);
            echo "Successfully seeded " . count($pivotData) . " entries into kacang_tanah_opt_pivot.\n";
        } else {
            echo "Warning: Tidak ada OrgPenTan yang ditemukan untuk seeding pivot.\n";
        }
    }
}
