<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\OrgPenTan;
use App\Models\Pengendalian;

class PenyakitPengendalianSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil semua ID dari tabel OrgPenTan dan Pengendalian
        $orgPenTanIds = OrgPenTan::pluck('id')->all();
        $pengendalianIds = Pengendalian::pluck('id')->all();

        // Periksa apakah kedua tabel memiliki data
        if (empty($orgPenTanIds)) {
            echo "Warning: No data found in OrgPenTan table. Skipping seeding.\n";
            return;
        }

        if (empty($pengendalianIds)) {
            echo "Warning: No data found in Pengendalian table. Skipping seeding.\n";
            return;
        }

        $pivotData = [];

        // Buat data hubungan "many-to-many"
        foreach ($orgPenTanIds as $orgPenTanId) {
            foreach ($pengendalianIds as $pengendalianId) {
                $pivotData[] = [
                    'org_pen_tan_id' => $orgPenTanId,
                    'pengendalian_id' => $pengendalianId,
                    // Baris created_at dan updated_at dihilangkan
                ];
            }
        }

        // Masukkan data ke dalam pivot table
        DB::table('penyakit_pengendalian')->insert($pivotData);

        echo "Successfully seeded " . count($pivotData) . " entries into penyakit_pengendalian.\n";
    }
}
