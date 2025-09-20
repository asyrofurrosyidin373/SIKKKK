<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KomKedelaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // PENTING: Anda harus memiliki data di tabel `org_pen_tan` dan `varietas_kedelai`
        // terlebih dahulu untuk mendapatkan ID yang valid.
        // Asumsi: Kita mengambil ID dari data yang sudah ada atau men-generate dummy ID.
        $dummyOptId1 = Str::uuid(); // Ganti dengan ID OPT yang valid
        $dummyOptId2 = Str::uuid(); // Ganti dengan ID OPT yang valid
        $dummyVarietasId1 = Str::uuid(); // Ganti dengan ID Varietas yang valid
        $dummyVarietasId2 = Str::uuid(); // Ganti dengan ID Varietas yang valid

        $komKedelai = [
            [
                'id' => Str::uuid(),
                'provitas' => 2.20,
                'opt_id' => json_encode([$dummyOptId1]),
                'varietas_kedelai_id' => json_encode([$dummyVarietasId1]),
                'pot_peningkatan_judgement' => 1,
                'nilai_potensi' => 2.50,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'provitas' => 2.40,
                'opt_id' => json_encode([$dummyOptId1, $dummyOptId2]),
                'varietas_kedelai_id' => json_encode([$dummyVarietasId2]),
                'pot_peningkatan_judgement' => 2,
                'nilai_potensi' => 2.75,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Anda bisa menambahkan data komoditas kedelai lainnya di sini
        ];

        DB::table('kom_kedelai')->insert($komKedelai);
    }
}
