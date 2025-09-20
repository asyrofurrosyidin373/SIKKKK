<?php
// database/seeders/PengendalianInsektisidaPivotSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\HamaPenyakit; // Ensure you have this model
use App\Models\Insektisida; // Ensure you have this model

class PengendalianInsektisidaPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hamaPenyakits = HamaPenyakit::all();
        $insektisidaIds = Insektisida::pluck('id')->toArray();

        // Loop through each HamaPenyakit and attach all Insektisida IDs
        foreach ($hamaPenyakits as $hamaPenyakit) {
            $data = [];
            foreach ($insektisidaIds as $insektisidaId) {
                $data[] = [
                    'hama_penyakit_id' => $hamaPenyakit->id,
                    'insektisida_id' => $insektisidaId,
                ];
            }
            DB::table('hama_penyakit_insektisida')->insert($data);
        }
    }
}