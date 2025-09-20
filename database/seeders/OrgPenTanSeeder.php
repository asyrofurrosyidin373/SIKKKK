<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrgPenTanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $orgPenTan = [
            [
                'id' => Str::uuid(),
                'nama_opt' => 'Lalat Kacang',
                'jenis' => 'Hama',
                'gambar' => 'placeholder_image_url_lalat_kacang.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'nama_opt' => 'Penyakit Hawar',
                'jenis' => 'Penyakit',
                'gambar' => 'placeholder_image_url_penyakit_hawar.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'nama_opt' => 'Gulma Daun Lebar',
                'jenis' => 'Gulma',
                'gambar' => 'placeholder_image_url_gulma.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'nama_opt' => 'Tikus Sawah',
                'jenis' => 'Vertebrata',
                'gambar' => 'placeholder_image_url_tikus_sawah.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Anda bisa menambahkan data OPT lainnya di sini
        ];

        DB::table('org_pen_tan')->insert($orgPenTan);
    }
}
