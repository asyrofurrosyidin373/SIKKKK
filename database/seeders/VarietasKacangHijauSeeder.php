<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VarietasKacangHijauSeeder extends Seeder
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
            echo "Warning: Seeder OrgPenTan belum dijalankan atau data 'Balitkabi' tidak ditemukan. Melewatkan seeding VarietasKacangHijau.\n";
            return;
        }

        $varietasKacangHijau = [
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Vima 1',
                'tahun' => '2005',
                'sk' => 'SK Mentan No. 128/Kpts/SR.120/1/2005',
                'galur' => 'Vima 1/L',
                'asal' => 'Balitkabi',
                'potensi_hasil' => 1.7,
                'rata_hasil' => 1.2,
                'umur_berbunga' => '25 hari',
                'umur_masak' => '58-60 hari',
                'tinggi_tanaman' => '65 cm',
                'warna_biji' => 'Hijau kusam',
                'bobot' => '6.5-7.5 g/100 biji',
                'kadar_protein' => 24,
                'kadar_lemak' => 1.3,
                'pengenal' => 'Vigoritas tanaman baik',
                'inventor' => 'Tim Pemulia Balitkabi',
                'gambar' => 'placeholder_image_url_vima1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Vima 2',
                'tahun' => '2005',
                'sk' => 'SK Mentan No. 128/Kpts/SR.120/1/2005',
                'galur' => 'Vima 2/L',
                'asal' => 'Balitkabi',
                'potensi_hasil' => 1.8,
                'rata_hasil' => 1.3,
                'umur_berbunga' => '26 hari',
                'umur_masak' => '60-62 hari',
                'tinggi_tanaman' => '70 cm',
                'warna_biji' => 'Hijau mengkilap',
                'bobot' => '7.0-8.0 g/100 biji',
                'kadar_protein' => 25,
                'kadar_lemak' => 1.5,
                'pengenal' => 'Toleran penyakit layu bakteri',
                'inventor' => 'Tim Pemulia Balitkabi',
                'gambar' => 'placeholder_image_url_vima2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Perkutut',
                'tahun' => '1990',
                'sk' => 'SK Mentan No. 128/Kpts/SR.120/1/1990',
                'galur' => 'Perkutut/L',
                'asal' => 'Balitkabi',
                'potensi_hasil' => 1.5,
                'rata_hasil' => 1.0,
                'umur_berbunga' => '23 hari',
                'umur_masak' => '55-57 hari',
                'tinggi_tanaman' => '60 cm',
                'warna_biji' => 'Hijau mengkilap',
                'bobot' => '6.0-7.0 g/100 biji',
                'kadar_protein' => 23,
                'kadar_lemak' => 1.2,
                'pengenal' => 'Genjah',
                'inventor' => 'Tim Pemulia Balitkabi',
                'gambar' => 'placeholder_image_url_perkutut.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('varietas_kacang_hijau')->insert($varietasKacangHijau);
    }
}
