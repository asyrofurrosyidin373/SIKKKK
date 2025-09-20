<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Clear existing data from the pivot tables before seeding
        // This ensures a clean slate and prevents "Duplicate entry" errors
        DB::table('hama_penyakit_gejala')->truncate();
        DB::table('hama_penyakit_insektisida')->truncate();

        // Ambil ID hama_penyakit
        $hp1 = DB::table('hama_penyakits')->where('id_penyakit', 'PH001')->value('id');
        $hp2 = DB::table('hama_penyakits')->where('id_penyakit', 'PH005')->value('id');

        // Ambil ID gejala
        $g1 = DB::table('gejalas')->where('id_gejala', 'G001')->value('id');
        $g2 = DB::table('gejalas')->where('id_gejala', 'G002')->value('id');
        $g14 = DB::table('gejalas')->where('id_gejala', 'G014')->value('id');

        // Relasi hama/penyakit -> gejala
        DB::table('hama_penyakit_gejala')->insert([
            ['hama_penyakit_id' => $hp1, 'gejala_id' => $g1, 'bobot' => 1.0],
            ['hama_penyakit_id' => $hp1, 'gejala_id' => $g2, 'bobot' => 0.9],
            ['hama_penyakit_id' => $hp2, 'gejala_id' => $g14, 'bobot' => 1.0],
        ]);

        // Ambil ID insektisida
        $in1 = DB::table('insektisidas')->where('id_insektisida', 'IN001')->value('id');
        $in2 = DB::table('insektisidas')->where('id_insektisida', 'IN002')->value('id');

        // Relasi hama/penyakit -> insektisidas
        DB::table('hama_penyakit_insektisida')->insert([
            ['hama_penyakit_id' => $hp1, 'insektisida_id' => $in1],
            ['hama_penyakit_id' => $hp1, 'insektisida_id' => $in2],
        ]);
    }
}