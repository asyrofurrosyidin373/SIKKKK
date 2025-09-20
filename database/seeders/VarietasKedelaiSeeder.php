<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VarietasKedelaiSeeder extends Seeder
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
            echo "Warning: Seeder OrgPenTan belum dijalankan atau data 'Balitkabi' tidak ditemukan. Melewatkan seeding VarietasKedelai.\n";
            return;
        }

        $varietasKedelai = [
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Argomulyo',
                'tahun' => '2003',
                'sk' => 'SK Mentan No. 2970/Kpts/SR.120/10/2003',
                'galur' => 'Tanggunan/L',
                'asal' => 'Balitkabi',
                'potensi_hasil' => 3.5,
                'rata_hasil' => 2.5,
                'umur_berbunga' => '28 hari',
                'umur_masak' => '80 hari',
                'tinggi_tanaman' => '70 cm',
                'warna_biji' => 'Kuning',
                'bobot' => '10-12 g/100 biji',
                'kadar_protein' => 40,
                'kadar_lemak' => 18,
                'pengenal' => 'Kedelai Tahan Kekeringan',
                'inventor' => 'Tim Pemulia Balitkabi',
                'gambar' => 'placeholder_image_url_argomulyo.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Grobogan',
                'tahun' => '2010',
                'sk' => 'SK Mentan No. 128/Kpts/SR.120/1/2010',
                'galur' => 'Ijo / L',
                'asal' => 'Balitkabi',
                'potensi_hasil' => 3.0,
                'rata_hasil' => 2.2,
                'umur_berbunga' => '30 hari',
                'umur_masak' => '76 hari',
                'tinggi_tanaman' => '65 cm',
                'warna_biji' => 'Kuning',
                'bobot' => '15-17 g/100 biji',
                'kadar_protein' => 42,
                'kadar_lemak' => 17.5,
                'pengenal' => 'Kedelai Genjah',
                'inventor' => 'Tim Pemulia Balitkabi',
                'gambar' => 'placeholder_image_url_grobogan.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Anjasmoro',
                'tahun' => '2000',
                'sk' => 'SK Mentan No. 257/Kpts/TP.240/1/2000',
                'galur' => 'Wilis/L',
                'asal' => 'Balitkabi',
                'potensi_hasil' => 3.2,
                'rata_hasil' => 2.3,
                'umur_berbunga' => '32 hari',
                'umur_masak' => '84 hari',
                'tinggi_tanaman' => '75 cm',
                'warna_biji' => 'Kuning',
                'bobot' => '13-15 g/100 biji',
                'kadar_protein' => 41.5,
                'kadar_lemak' => 18.5,
                'pengenal' => 'Toleran Naungan',
                'inventor' => 'Tim Pemulia Balitkabi',
                'gambar' => 'placeholder_image_url_anjasmoro.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('varietas_kedelai')->insert($varietasKedelai);
    }
}
