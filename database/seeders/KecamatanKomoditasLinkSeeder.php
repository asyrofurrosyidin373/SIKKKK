<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabKecamatan;
use App\Models\VarietasKedelai;
use App\Models\VarietasKacangTanah;
use App\Models\VarietasKacangHijau;
use Illuminate\Support\Facades\DB;

class KecamatanKomoditasLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all kecamatan
        $allKecamatan = TabKecamatan::all();
        
        // Get all varietas
        $varietasKedelai = VarietasKedelai::all();
        $varietasKacangTanah = VarietasKacangTanah::all();
        $varietasKacangHijau = VarietasKacangHijau::all();

        $this->command->info("Populating kecamatan with komoditas data using new structure...");

        $komoditasTypes = ['kedelai', 'kacang_tanah', 'kacang_hijau'];
        $seededCount = 0;

        foreach ($allKecamatan as $kecamatan) {
            // Skip if already has komoditas data
            if ($kecamatan->jenis_komoditas) {
                continue;
            }

            // Randomly select a komoditas type
            $jenisKomoditas = $komoditasTypes[array_rand($komoditasTypes)];
            
            // Get random varietas based on komoditas type
            $varietasIds = [];
            $varietasCollection = null;
            
            switch ($jenisKomoditas) {
                case 'kedelai':
                    $varietasCollection = $varietasKedelai;
                    break;
                case 'kacang_tanah':
                    $varietasCollection = $varietasKacangTanah;
                    break;
                case 'kacang_hijau':
                    $varietasCollection = $varietasKacangHijau;
                    break;
            }

            // Select 1-3 random varietas
            if ($varietasCollection && $varietasCollection->count() > 0) {
                $numVarietas = rand(1, min(3, $varietasCollection->count()));
                $selectedVarietas = $varietasCollection->random($numVarietas);
                $varietasIds = $selectedVarietas->pluck('id')->toArray();
            }

            // Generate random production data
            $luasTanam = rand(50, 500) + (rand(0, 99) / 100); // 50.00 - 500.99
            $produktivitas = rand(15, 35) / 10; // 1.5 - 3.5 ton/ha
            $totalProduksi = $luasTanam * $produktivitas;
            
            $updateData = [
                'jenis_komoditas' => $jenisKomoditas,
                'varietas_id' => json_encode($varietasIds),
                'provitas' => rand(20, 40) / 10, // 2.0 - 4.0
                'luas_tanam' => round($luasTanam, 2),
                'produktivitas' => round($produktivitas, 2),
                'total_produksi' => round($totalProduksi, 2),
                'pot_peningkatan_judgement' => rand(5, 10),
                'nilai_potensi' => rand(25, 45) / 10, // 2.5 - 4.5
            ];

            $kecamatan->update($updateData);
            $seededCount++;
        }

        // Update statistics
        $kecamatanWithKedelai = TabKecamatan::where('jenis_komoditas', 'kedelai')->count();
        $kecamatanWithKacangTanah = TabKecamatan::where('jenis_komoditas', 'kacang_tanah')->count();
        $kecamatanWithKacangHijau = TabKecamatan::where('jenis_komoditas', 'kacang_hijau')->count();

        $this->command->info("Successfully populated {$seededCount} kecamatan with komoditas data:");
        $this->command->info("- Kedelai: {$kecamatanWithKedelai} kecamatan");
        $this->command->info("- Kacang Tanah: {$kecamatanWithKacangTanah} kecamatan");
        $this->command->info("- Kacang Hijau: {$kecamatanWithKacangHijau} kecamatan");
    }
}
