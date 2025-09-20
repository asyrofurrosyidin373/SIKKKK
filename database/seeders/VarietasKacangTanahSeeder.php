<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VarietasKacangTanahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID dari tabel org_pen_tan secara dinamis
        $balitkabiId = DB::table('org_pen_tan')->where('nama_opt', 'Balitkabi')->value('id');

        // Pastikan ID ditemukan sebelum menyisipkan data
        if (!$balitkabiId) {
            echo "Warning: Seeder OrgPenTan belum dijalankan atau data 'Balitkabi' tidak ditemukan. Melewatkan seeding VarietasKacangTanah.\n";
            return;
        }

        $varietasKacangTanah = [
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Kancil',
                'tahun' => '2000',
                'sk' => 'SK Mentan No. 257/Kpts/TP.240/1/2000',
                'galur' => 'Wilis/L',
                'asal' => 'Balitkabi',
                'potensi_hasil' => 2.5,
                'rata_hasil' => 1.8,
                'umur_berbunga' => '25 hari',
                'umur_masak' => '85 hari',
                'tinggi_tanaman' => '40 cm',
                'warna_biji' => 'Kuning',
                'bobot' => '40 g/100 biji',
                'kadar_protein' => 25,
                'kadar_lemak' => 45,
                'pengenal' => 'Genjah',
                'inventor' => 'Tim Pemulia Balitkabi',
                'gambar' => 'placeholder_image_url_kancil.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Talam',
                'tahun' => '2005',
                'sk' => 'SK Mentan No. 128/Kpts/SR.120/1/2005',
                'galur' => 'Talam/L',
                'asal' => 'Balitkabi',
                'potensi_hasil' => 3.0,
                'rata_hasil' => 2.0,
                'umur_berbunga' => '28 hari',
                'umur_masak' => '90 hari',
                'tinggi_tanaman' => '50 cm',
                'warna_biji' => 'Coklat',
                'bobot' => '50 g/100 biji',
                'kadar_protein' => 28,
                'kadar_lemak' => 48,
                'pengenal' => 'Genjah',
                'inventor' => 'Tim Pemulia Balitkabi',
                'gambar' => 'placeholder_image_url_talam.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('varietas_kacang_tanah')->insert($varietasKacangTanah);
    }
}
