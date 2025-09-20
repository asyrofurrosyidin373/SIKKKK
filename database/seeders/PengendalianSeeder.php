<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PengendalianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pengendalian = [
            [
                'id' => Str::uuid(),
                'jenis' => 'Kultur teknis',
                'deskripsi' => 'Pengolahan tanah yang baik, rotasi tanaman, penanaman varietas tahan.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jenis' => 'Mekanis',
                'deskripsi' => 'Mengumpulkan dan memusnahkan hama atau bagian tanaman yang terserang.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jenis' => 'Kimiawi',
                'deskripsi' => 'Penggunaan insektisida, fungisida, atau herbisida.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'jenis' => 'Hayati',
                'deskripsi' => 'Pemanfaatan musuh alami, seperti predator, parasitoid, atau patogen.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Anda bisa menambahkan data pengendalian lainnya di sini
        ];

        DB::table('pengendalian')->insert($pengendalian);
    }
}
