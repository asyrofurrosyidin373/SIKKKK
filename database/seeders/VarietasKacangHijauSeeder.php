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
                'nama_varietas' => 'Vima-1',
                'tahun' => '2008',
                'sk' => '322/Kpts/SR.120/8/2008',
                'galur' => 'Vima-1',
                'asal' => 'Persilangan (Vima × Mungbean)',
                'potensi_hasil' => 1.80,
                'rata_hasil' => 1.20,
                'umur_berbunga' => '±30 HST',
                'umur_masak' => '±60 hari',
                'tinggi_tanaman' => '60–70 cm',
                'warna_biji' => 'hijau',
                'bobot' => '5.5 g/100 biji',
                'kadar_protein' => 24.00,
                'kadar_lemak' => 1.00,
                'pengenal' => null,
                'inventor' => 'Balitkabi',
                'gambar' => 'placeholder_image_url_vima1.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Vima-2',
                'tahun' => '2008',
                'sk' => '322/Kpts/SR.120/8/2008',
                'galur' => 'Vima-2',
                'asal' => 'Persilangan (Vima × Mungbean)',
                'potensi_hasil' => 2.00,
                'rata_hasil' => 1.50,
                'umur_berbunga' => '±30 HST',
                'umur_masak' => '±60 hari',
                'tinggi_tanaman' => '65–75 cm',
                'warna_biji' => 'hijau',
                'bobot' => '6.0 g/100 biji',
                'kadar_protein' => 24.50,
                'kadar_lemak' => 1.00,
                'pengenal' => null,
                'inventor' => 'Balitkabi',
                'gambar' => 'placeholder_image_url_vima2.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Vima-3',
                'tahun' => '2008',
                'sk' => '322/Kpts/SR.120/8/2008',
                'galur' => 'Vima-3',
                'asal' => 'Persilangan (Vima × Mungbean)',
                'potensi_hasil' => 2.20,
                'rata_hasil' => 1.70,
                'umur_berbunga' => '±30 HST',
                'umur_masak' => '±60 hari',
                'tinggi_tanaman' => '70–80 cm',
                'warna_biji' => 'hijau',
                'bobot' => '6.5 g/100 biji',
                'kadar_protein' => 25.00,
                'kadar_lemak' => 1.00,
                'pengenal' => null,
                'inventor' => 'Balitkabi',
                'gambar' => 'placeholder_image_url_vima3.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Murai',
                'tahun' => '2016',
                'sk' => '211/Kpts/SR.120/D.2.7/2016',
                'galur' => 'Murai',
                'asal' => 'Persilangan (Malabar × S 398)',
                'potensi_hasil' => 2.50,
                'rata_hasil' => 1.80,
                'umur_berbunga' => '±30 HST',
                'umur_masak' => '±55 hari',
                'tinggi_tanaman' => '60–70 cm',
                'warna_biji' => 'hijau',
                'bobot' => '5.0 g/100 biji',
                'kadar_protein' => 23.00,
                'kadar_lemak' => 1.00,
                'pengenal' => null,
                'inventor' => 'Balitkabi',
                'gambar' => 'placeholder_image_url_murai.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Kutilang',
                'tahun' => '2016',
                'sk' => '211/Kpts/SR.120/D.2.7/2016',
                'galur' => 'Kutilang',
                'asal' => 'Persilangan (Malabar × S 398)',
                'potensi_hasil' => 2.60,
                'rata_hasil' => 1.90,
                'umur_berbunga' => '±30 HST',
                'umur_masak' => '±58 hari',
                'tinggi_tanaman' => '65–75 cm',
                'warna_biji' => 'hijau',
                'bobot' => '5.5 g/100 biji',
                'kadar_protein' => 23.50,
                'kadar_lemak' => 1.00,
                'pengenal' => null,
                'inventor' => 'Balitkabi',
                'gambar' => 'placeholder_image_url_kutilang.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('varietas_kacang_hijau')->insert($varietasKacangHijau);
    }
}