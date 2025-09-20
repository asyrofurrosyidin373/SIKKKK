<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KecamatanBulanPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil ID dari tabel tab_kecamatan dan tab_bulan yang sudah ada
        $kecamatanDauId = DB::table('tab_kecamatan')->where('nama_kecamatan', 'Dau')->value('id');
        $kecamatanTumpangId = DB::table('tab_kecamatan')->where('nama_kecamatan', 'Tumpang')->value('id');
        $kecamatanWajakId = DB::table('tab_kecamatan')->where('nama_kecamatan', 'Wajak')->value('id');

        $bulanJanuariId = DB::table('tab_bulan')->where('nama_bulan', 'Januari')->value('id');
        $bulanFebruariId = DB::table('tab_bulan')->where('nama_bulan', 'Februari')->value('id');
        $bulanMaretId = DB::table('tab_bulan')->where('nama_bulan', 'Maret')->value('id');
        
        // Periksa apakah ID ditemukan sebelum menyisipkan data
        if (!$kecamatanDauId || !$kecamatanTumpangId || !$kecamatanWajakId || !$bulanJanuariId || !$bulanFebruariId || !$bulanMaretId) {
            echo "Warning: Seeder kecamatan atau bulan belum dijalankan atau data tidak ditemukan. Melewatkan seeding pivot.\n";
            return;
        }

        $pivotData = [
            // Contoh data untuk Kecamatan Dau
            [
                'tab_kecamatan_id' => $kecamatanDauId,
                'tab_bulan_id' => $bulanJanuariId,
                'tipe' => 'bulan_hujan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tab_kecamatan_id' => $kecamatanDauId,
                'tab_bulan_id' => $bulanFebruariId,
                'tipe' => 'bulan_hujan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Contoh data untuk Kecamatan Tumpang
            [
                'tab_kecamatan_id' => $kecamatanTumpangId,
                'tab_bulan_id' => $bulanFebruariId,
                'tipe' => 'bulan_hujan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Contoh data untuk Kecamatan Wajak
            [
                'tab_kecamatan_id' => $kecamatanWajakId,
                'tab_bulan_id' => $bulanMaretId,
                'tipe' => 'bulan_hujan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('kecamatan_bulan_pivot')->insert($pivotData);
    }
}
