<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\KomKacangHijau;
use App\Models\OrgPenTan;

class KacangHijauOptPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID dari OrgPenTan secara dinamis
        $orgPenTanIds = OrgPenTan::pluck('id');
        
        // Mengambil ID dari entri pertama di tabel kom_kacang_hijau
        // Asumsi bahwa ada satu entri yang relevan untuk komoditas 'Kacang Hijau'
        $komKacangHijauId = KomKacangHijau::first()->id ?? null;

        // Periksa apakah ID komoditas kacang hijau ditemukan
        if (!$komKacangHijauId) {
            echo "Warning: Tidak ada data di tabel kom_kacang_hijau. Pastikan KomKacangHijauSeeder telah dijalankan.\n";
            return;
        }

        $pivotData = [];

        // Hubungkan semua OrgPenTan dengan satu ID komoditas 'Kacang Hijau'
        foreach ($orgPenTanIds as $idOpt) {
            $pivotData[] = [
                'kom_kacang_hijau_id' => $komKacangHijauId,
                'org_pen_tan_id' => $idOpt,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Periksa apakah ada data untuk dimasukkan
        if (!empty($pivotData)) {
            DB::table('kacang_hijau_opt_pivot')->insert($pivotData);
            echo "Successfully seeded " . count($pivotData) . " entries into kacang_hijau_opt_pivot.\n";
        } else {
            echo "Warning: Tidak ada OrgPenTan yang ditemukan untuk seeding pivot.\n";
        }
    }
}
