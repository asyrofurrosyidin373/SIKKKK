<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\OrgPenTan;
use App\Models\VarietasKedelai;
use App\Models\VarietasKacangHijau;
use App\Models\VarietasKacangTanah;

class OptVarietasPivotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Asumsi data di tabel `org_pen_tan`, `varietas_kedelai`, `varietas_kacang_hijau`,
        // dan `varietas_kacang_tanah` sudah ada.
        
        $lalatKacangId = OrgPenTan::where('nama_opt', 'Lalat Kacang')->first()->id ?? Str::uuid();
        $penyakitHawarId = OrgPenTan::where('nama_opt', 'Penyakit Hawar')->first()->id ?? Str::uuid();
        $varietasKedelaiId = VarietasKedelai::first()->id ?? Str::uuid();
        $varietasKacangHijauId = VarietasKacangHijau::first()->id ?? Str::uuid();
        $varietasKacangTanahId = VarietasKacangTanah::first()->id ?? Str::uuid();

        // Contoh data untuk pivot table
        $pivotData = [
            [
                'org_pen_tan_id' => $lalatKacangId,
                'varietas_id' => $varietasKedelaiId,
                'tingkat_resistensi' => 8, // Sangat tahan
                'komoditas_type' => 'kedelai',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'org_pen_tan_id' => $penyakitHawarId,
                'varietas_id' => $varietasKacangHijauId,
                'tingkat_resistensi' => 9, // Sangat tahan
                'komoditas_type' => 'kacang_hijau',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'org_pen_tan_id' => $lalatKacangId,
                'varietas_id' => $varietasKacangTanahId,
                'tingkat_resistensi' => 5, // Cukup tahan
                'komoditas_type' => 'kacang_tanah',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Tambahkan data hubungan lainnya di sini
        ];

        DB::table('opt_varietas_pivot')->insert($pivotData);
    }
}
