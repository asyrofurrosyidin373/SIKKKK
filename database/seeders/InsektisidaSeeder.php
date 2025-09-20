<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InsektisidaSeeder extends Seeder
{
    public function run()
    {
        DB::table('insektisidas')->insert([
            [
                'id_insektisida' => 'IN001',
                'nama_insektisida' => 'Alphadine 6 GR',
                'bahan_aktif' => 'Dimehipo 6%',
                'hama_sasaran' => 'Lalat Kacang, Lalat Batang, Lalat Pucuk',
                'dosis' => 'Sesuai rekomendasi teknis',
                'cara_aplikasi' => 'Tabur pada tanah saat tanam',
            ],
            [
                'id_insektisida' => 'IN002',
                'nama_insektisida' => 'Basban 200 EC',
                'bahan_aktif' => 'Kloropirifos 200 g/l',
                'hama_sasaran' => 'Lalat Kacang, Lalat Batang, Lalat Pucuk',
                'dosis' => 'Sesuai rekomendasi teknis',
                'cara_aplikasi' => 'Penyemprotan foliar',
            ],
            [
                'id_insektisida' => 'IN003',
                'nama_insektisida' => 'Bassa 500 EC',
                'bahan_aktif' => 'BPMC 480 g/l',
                'hama_sasaran' => 'Lalat Kacang, Lalat Batang, Lalat Pucuk',
                'dosis' => 'Sesuai rekomendasi teknis',
                'cara_aplikasi' => 'Penyemprotan foliar',
            ],
            [
                'id_insektisida' => 'IN004',
                'nama_insektisida' => 'Cobra 15 EC',
                'bahan_aktif' => 'Alfametrin 15 g/l',
                'hama_sasaran' => 'Lalat Kacang, Lalat Batang, Lalat Pucuk',
                'dosis' => 'Sesuai rekomendasi teknis',
                'cara_aplikasi' => 'Penyemprotan saat ambang kendali',
            ],
            [
                'id_insektisida' => 'IN005',
                'nama_insektisida' => 'Confidor 70 WS',
                'bahan_aktif' => 'Imidakloporid',
                'hama_sasaran' => 'Lalat Kacang, Lalat Batang, Lalat Pucuk',
                'dosis' => 'Sesuai rekomendasi teknis',
                'cara_aplikasi' => 'Larutkan dan semprotkan pada daun',
            ],
            [
                'id_insektisida' => 'IN006',
                'nama_insektisida' => 'Cruiser 350 FS',
                'bahan_aktif' => 'Tiametoksam 350 g/l',
                'hama_sasaran' => 'Lalat Kacang, Lalat Batang, Lalat Pucuk',
                'dosis' => 'Sesuai rekomendasi teknis',
                'cara_aplikasi' => 'Perlakuan benih',
            ],
            [
                'id_insektisida' => 'IN007',
                'nama_insektisida' => 'Curaterr 3 GR',
                'bahan_aktif' => 'Klorpirifos 3%',
                'hama_sasaran' => 'Lalat Kacang, Lalat Batang, Lalat Pucuk',
                'dosis' => 'Sesuai rekomendasi teknis',
                'cara_aplikasi' => 'Tabur pada tanah',
            ],
        ]);
    }
}
