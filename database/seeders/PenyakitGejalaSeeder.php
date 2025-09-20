<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyakitGejalaSeeder extends Seeder
{
    public function run()
    {
        // sesuaikan nama tabel penyakit yang ada
        $table = DB::getSchemaBuilder()->hasTable('hama_penyakits') ? 'hama_penyakits' : 'org_pen_tan';
        $penyakit = DB::table($table)->where('id_penyakit', 'PH001')->first();

        // sesuaikan kolom di tabel gejala (id / kode / lainnya)
        $gejalas = DB::table('gejala')
            ->whereIn('id', [1, 2, 3]) // kalau pakai PK integer
            // ->whereIn('kode', ['G001','G002','G003']) // kalau pakai kode string
            ->get();

        if ($penyakit) {
            foreach ($gejalas as $gejala) {
                DB::table('penyakit_gejala')->insert([
                    'org_pen_tan_id' => $penyakit->id,
                    'gejala_id'      => $gejala->id,
                ]);
            }
        }
    }
}
