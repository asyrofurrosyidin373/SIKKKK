<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Master data wilayah & waktu
            ProvinsiSeeder::class,
            KabupatenSeeder::class,
            KecamatanSeeder::class,
            BulanSeeder::class,

            // Data dasar tanaman & varietas
            TanamanSeeder::class,
            VarietasKedelaiSeeder::class,
            VarietasKacangHijauSeeder::class,
            VarietasKacangTanahSeeder::class,

            // Data komoditas
            KomKedelaiSeeder::class,
            KomKacangHijauSeeder::class,
            KomKacangTanahSeeder::class,

            // Data organisme pengganggu & pengendalian
            OrgPenTanSeeder::class,
            HamaPenyakitSeeder::class,
            PenyakitSeeder::class,
            GejalaSeeder::class,
            InsektisidaSeeder::class,
            PengendalianSeeder::class,

            // Pivot varietas
            KedelaiVarietasPivotSeeder::class,
            KacangHijauVarietasPivotSeeder::class,
            KacangTanahVarietasPivotSeeder::class,
            OptVarietasPivotSeeder::class,

            // Pivot OPT (organisme pengganggu tanaman)
            KedelaiOptPivotSeeder::class,
            KacangHijauOptPivotSeeder::class,
            KacangTanahOptPivotSeeder::class,

            // Pivot gejala & pengendalian
            PenyakitGejalaSeeder::class,
            PenyakitPengendalianSeeder::class,
            PengendalianInsektisidaPivotSeeder::class,

            // Pivot kecamatan/bulan
            KecamatanBulanPivotSeeder::class,

            // Laporan & testing
            LaporanDeteksiSeeder::class,
            ApiTestDataSeeder::class,
            PivotSeeder::class, // custom pivot tambahan
        ]);
    }
}
