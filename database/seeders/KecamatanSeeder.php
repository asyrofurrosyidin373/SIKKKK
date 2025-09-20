<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil ID kabupaten secara dinamis dari database
        $kabupatenMalangId = DB::table('tab_kabupaten')->where('nama_kabupaten', 'Malang')->value('id');
        $kabupatenJemberId = DB::table('tab_kabupaten')->where('nama_kabupaten', 'Jember')->value('id');
        $kabupatenBanyuwangiId = DB::table('tab_kabupaten')->where('nama_kabupaten', 'Banyuwangi')->value('id');

        // Pastikan ID kabupaten ditemukan sebelum menyisipkan data
        if (!$kabupatenMalangId || !$kabupatenJemberId || !$kabupatenBanyuwangiId) {
            echo "Warning: Seeder kabupaten belum dijalankan atau data kabupaten tidak ditemukan. Melewatkan seeding kecamatan.\n";
            return;
        }

        $kecamatan = [
            // Contoh data untuk Kabupaten Malang
            [
                'id' => Str::uuid(),
                'tab_kabupaten_id' => $kabupatenMalangId,
                'nama_kecamatan' => 'Dau',
                'latitude' => -7.9404,
                'longitude' => 112.5937,
                'ip_lahan' => 1.50,
                'kdr_p' => 2.00,
                'kdr_c' => 2.50,
                'kdr_k' => 3.00,
                'ktk' => 3.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'tab_kabupaten_id' => $kabupatenMalangId,
                'nama_kecamatan' => 'Tumpang',
                'latitude' => -7.9715,
                'longitude' => 112.7237,
                'ip_lahan' => 1.60,
                'kdr_p' => 2.10,
                'kdr_c' => 2.60,
                'kdr_k' => 3.10,
                'ktk' => 3.60,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'tab_kabupaten_id' => $kabupatenMalangId,
                'nama_kecamatan' => 'Wajak',
                'latitude' => -8.0863,
                'longitude' => 112.7226,
                'ip_lahan' => 1.55,
                'kdr_p' => 2.05,
                'kdr_c' => 2.55,
                'kdr_k' => 3.05,
                'ktk' => 3.55,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Contoh data untuk Kabupaten Jember
            [
                'id' => Str::uuid(),
                'tab_kabupaten_id' => $kabupatenJemberId,
                'nama_kecamatan' => 'Ajung',
                'latitude' => -8.2120,
                'longitude' => 113.6823,
                'ip_lahan' => 1.70,
                'kdr_p' => 2.20,
                'kdr_c' => 2.70,
                'kdr_k' => 3.20,
                'ktk' => 3.70,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'tab_kabupaten_id' => $kabupatenJemberId,
                'nama_kecamatan' => 'Kaliwates',
                'latitude' => -8.1704,
                'longitude' => 113.7001,
                'ip_lahan' => 1.75,
                'kdr_p' => 2.25,
                'kdr_c' => 2.75,
                'kdr_k' => 3.25,
                'ktk' => 3.75,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            // Contoh data untuk Kabupaten Banyuwangi
            [
                'id' => Str::uuid(),
                'tab_kabupaten_id' => $kabupatenBanyuwangiId,
                'nama_kecamatan' => 'Glagah',
                'latitude' => -8.1718,
                'longitude' => 114.3314,
                'ip_lahan' => 1.80,
                'kdr_p' => 2.30,
                'kdr_c' => 2.80,
                'kdr_k' => 3.30,
                'ktk' => 3.80,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('tab_kecamatan')->insert($kecamatan);
    }
}
