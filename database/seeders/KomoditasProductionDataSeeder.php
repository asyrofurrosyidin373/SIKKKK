<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KomKedelai;
use App\Models\KomKacangTanah;
use App\Models\KomKacangHijau;
use Illuminate\Support\Facades\DB;

class KomoditasProductionDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update existing KomKedelai records with production data
        $kedelaiRecords = KomKedelai::all();
        foreach ($kedelaiRecords as $record) {
            $luasTanam = rand(50, 500) / 10; // 5.0 - 50.0 hectares
            $produktivitas = rand(15, 35) / 10; // 1.5 - 3.5 ton/ha
            $totalProduksi = $luasTanam * $produktivitas;
            
            $record->update([
                'luas_tanam' => $luasTanam,
                'produktivitas' => $produktivitas,
                'total_produksi' => round($totalProduksi, 2)
            ]);
        }

        // Update existing KomKacangTanah records with production data
        $kacangTanahRecords = KomKacangTanah::all();
        foreach ($kacangTanahRecords as $record) {
            $luasTanam = rand(30, 300) / 10; // 3.0 - 30.0 hectares
            $produktivitas = rand(10, 25) / 10; // 1.0 - 2.5 ton/ha
            $totalProduksi = $luasTanam * $produktivitas;
            
            $record->update([
                'luas_tanam' => $luasTanam,
                'produktivitas' => $produktivitas,
                'total_produksi' => round($totalProduksi, 2)
            ]);
        }

        // Update existing KomKacangHijau records with production data
        $kacangHijauRecords = KomKacangHijau::all();
        foreach ($kacangHijauRecords as $record) {
            $luasTanam = rand(20, 200) / 10; // 2.0 - 20.0 hectares
            $produktivitas = rand(8, 20) / 10; // 0.8 - 2.0 ton/ha
            $totalProduksi = $luasTanam * $produktivitas;
            
            $record->update([
                'luas_tanam' => $luasTanam,
                'produktivitas' => $produktivitas,
                'total_produksi' => round($totalProduksi, 2)
            ]);
        }

        $this->command->info('Production data seeded for all komoditas records.');
    }
}
