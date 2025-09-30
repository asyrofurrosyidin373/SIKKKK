<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabKabupaten;
use App\Models\TabKecamatan;
use App\Models\KomKedelai;
use App\Models\KomKacangTanah;
use App\Models\KomKacangHijau;
use Illuminate\Support\Facades\DB;

class FixKecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🔧 Fixing kecamatan data...');

        // Get all existing kabupaten
        $allKabupaten = TabKabupaten::all();
        
        if ($allKabupaten->count() == 0) {
            $this->command->error('❌ No kabupaten found! Please run MinimalDataSeeder first.');
            return;
        }

        $this->command->info("Found {$allKabupaten->count()} kabupaten");

        // Create kecamatan for each kabupaten
        foreach ($allKabupaten as $kabupaten) {
            $existingKecamatan = TabKecamatan::where('tab_kabupaten_id', $kabupaten->id)->count();
            
            if ($existingKecamatan == 0) {
                $this->command->info("Creating kecamatan for {$kabupaten->nama_kabupaten}...");
                
                // Create 2-3 kecamatan per kabupaten
                $kecamatanCount = rand(2, 3);
                
                for ($i = 1; $i <= $kecamatanCount; $i++) {
                    $kecamatanId = $kabupaten->id . str_pad($i, 2, '0', STR_PAD_LEFT);
                    
                    // Generate random coordinates based on Indonesia regions
                    $coordinates = $this->generateCoordinates();
                    
                    $kecamatan = TabKecamatan::create([
                        'id' => $kecamatanId,
                        'tab_kabupaten_id' => $kabupaten->id,
                        'nama_kecamatan' => $this->generateKecamatanName($i),
                        'latitude' => $coordinates['lat'],
                        'longitude' => $coordinates['lng'],
                    ]);

                    $this->command->info("  ✅ Created: {$kecamatan->nama_kecamatan} ({$coordinates['lat']}, {$coordinates['lng']})");
                }
            } else {
                $this->command->info("Kabupaten {$kabupaten->nama_kabupaten} already has {$existingKecamatan} kecamatan");
                
                // Update existing kecamatan to ensure they have coordinates
                $kecamatanWithoutCoords = TabKecamatan::where('tab_kabupaten_id', $kabupaten->id)
                    ->where(function($query) {
                        $query->whereNull('latitude')->orWhereNull('longitude');
                    })->get();
                
                foreach ($kecamatanWithoutCoords as $kec) {
                    $coordinates = $this->generateCoordinates();
                    $kec->update([
                        'latitude' => $coordinates['lat'],
                        'longitude' => $coordinates['lng']
                    ]);
                    $this->command->info("  🔄 Updated coordinates for: {$kec->nama_kecamatan}");
                }
            }
        }

        // Ensure we have some komoditas data
        $this->ensureKomoditasData();
        
        // Link kecamatan to komoditas
        $this->linkKecamatanToKomoditas();

        $totalKecamatan = TabKecamatan::count();
        $kecamatanWithCoords = TabKecamatan::whereNotNull('latitude')->whereNotNull('longitude')->count();
        
        $this->command->info("✅ Kecamatan fix completed!");
        $this->command->info("📊 Total kecamatan: {$totalKecamatan}");
        $this->command->info("📍 With coordinates: {$kecamatanWithCoords}");
    }

    private function generateCoordinates(): array
    {
        // Indonesia coordinate ranges
        $regions = [
            // Java
            ['lat_min' => -8.5, 'lat_max' => -5.5, 'lng_min' => 105.0, 'lng_max' => 115.0],
            // Sumatra
            ['lat_min' => -6.0, 'lat_max' => 6.0, 'lng_min' => 95.0, 'lng_max' => 106.0],
            // Kalimantan
            ['lat_min' => -4.0, 'lat_max' => 7.0, 'lng_min' => 108.0, 'lng_max' => 119.0],
        ];

        $region = $regions[array_rand($regions)];
        
        return [
            'lat' => $this->randomFloat($region['lat_min'], $region['lat_max'], 6),
            'lng' => $this->randomFloat($region['lng_min'], $region['lng_max'], 6)
        ];
    }

    private function randomFloat($min, $max, $precision = 6): float
    {
        $multiplier = pow(10, $precision);
        return mt_rand($min * $multiplier, $max * $multiplier) / $multiplier;
    }

    private function generateKecamatanName($index): string
    {
        $names = [
            'Kecamatan Utara', 'Kecamatan Selatan', 'Kecamatan Timur', 'Kecamatan Barat',
            'Kecamatan Tengah', 'Kecamatan Indah', 'Kecamatan Makmur', 'Kecamatan Sejahtera',
            'Kecamatan Maju', 'Kecamatan Damai'
        ];
        
        return $names[($index - 1) % count($names)];
    }

    private function ensureKomoditasData(): void
    {
        // Create komoditas if they don't exist
        if (KomKedelai::count() == 0) {
            for ($i = 1; $i <= 3; $i++) {
                KomKedelai::create([
                    'provitas' => rand(15, 35) / 10,
                    'luas_tanam' => rand(50, 500) / 10,
                    'produktivitas' => rand(15, 35) / 10,
                    'total_produksi' => rand(100, 1000) / 10,
                    'nilai_potensi' => rand(70, 95) / 10,
                ]);
            }
            $this->command->info('✅ Created KomKedelai data');
        }

        if (KomKacangTanah::count() == 0) {
            for ($i = 1; $i <= 3; $i++) {
                KomKacangTanah::create([
                    'provitas' => rand(10, 25) / 10,
                    'luas_tanam' => rand(30, 300) / 10,
                    'produktivitas' => rand(10, 25) / 10,
                    'total_produksi' => rand(50, 500) / 10,
                    'nilai_potensi' => rand(60, 85) / 10,
                ]);
            }
            $this->command->info('✅ Created KomKacangTanah data');
        }

        if (KomKacangHijau::count() == 0) {
            for ($i = 1; $i <= 3; $i++) {
                KomKacangHijau::create([
                    'provitas' => rand(8, 20) / 10,
                    'luas_tanam' => rand(20, 200) / 10,
                    'produktivitas' => rand(8, 20) / 10,
                    'total_produksi' => rand(30, 300) / 10,
                    'nilai_potensi' => rand(50, 80) / 10,
                ]);
            }
            $this->command->info('✅ Created KomKacangHijau data');
        }
    }

    private function linkKecamatanToKomoditas(): void
    {
        $kecamatanList = TabKecamatan::whereNull('kom_kedelai_id')
            ->whereNull('kom_kacang_tanah_id')
            ->whereNull('kom_kacang_hijau_id')
            ->get();

        if ($kecamatanList->count() == 0) {
            $this->command->info('All kecamatan already linked to komoditas');
            return;
        }

        $kedelaiList = KomKedelai::all();
        $kacangTanahList = KomKacangTanah::all();
        $kacangHijauList = KomKacangHijau::all();

        foreach ($kecamatanList as $kecamatan) {
            $updates = [];
            
            // 70% chance to have kedelai
            if (rand(1, 100) <= 70 && $kedelaiList->count() > 0) {
                $updates['kom_kedelai_id'] = $kedelaiList->random()->id;
            }
            
            // 60% chance to have kacang tanah
            if (rand(1, 100) <= 60 && $kacangTanahList->count() > 0) {
                $updates['kom_kacang_tanah_id'] = $kacangTanahList->random()->id;
            }
            
            // 50% chance to have kacang hijau
            if (rand(1, 100) <= 50 && $kacangHijauList->count() > 0) {
                $updates['kom_kacang_hijau_id'] = $kacangHijauList->random()->id;
            }

            if (!empty($updates)) {
                $kecamatan->update($updates);
            }
        }

        $this->command->info('✅ Linked kecamatan to komoditas');
    }
}
