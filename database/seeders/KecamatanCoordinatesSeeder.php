<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabKecamatan;
use Illuminate\Support\Facades\DB;

class KecamatanCoordinatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample coordinates for Indonesian regions (approximate)
        $indonesiaRegions = [
            // Java coordinates
            ['lat_min' => -8.5, 'lat_max' => -5.5, 'lng_min' => 105.0, 'lng_max' => 115.0],
            // Sumatra coordinates  
            ['lat_min' => -6.0, 'lat_max' => 6.0, 'lng_min' => 95.0, 'lng_max' => 106.0],
            // Kalimantan coordinates
            ['lat_min' => -4.0, 'lat_max' => 7.0, 'lng_min' => 108.0, 'lng_max' => 119.0],
            // Sulawesi coordinates
            ['lat_min' => -6.0, 'lat_max' => 2.0, 'lng_min' => 118.0, 'lng_max' => 125.0],
            // Papua coordinates
            ['lat_min' => -9.0, 'lat_max' => -1.0, 'lng_min' => 130.0, 'lng_max' => 141.0],
        ];

        // Get kecamatan without coordinates
        $kecamatanWithoutCoords = TabKecamatan::whereNull('latitude')
            ->orWhereNull('longitude')
            ->get();

        $this->command->info("Found {$kecamatanWithoutCoords->count()} kecamatan without coordinates");

        foreach ($kecamatanWithoutCoords as $kecamatan) {
            // Randomly select a region
            $region = $indonesiaRegions[array_rand($indonesiaRegions)];
            
            // Generate random coordinates within the region
            $latitude = $this->randomFloat($region['lat_min'], $region['lat_max'], 6);
            $longitude = $this->randomFloat($region['lng_min'], $region['lng_max'], 6);
            
            $kecamatan->update([
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);
        }

        $this->command->info('Coordinates added to all kecamatan records.');
    }

    /**
     * Generate random float between min and max with specified precision
     */
    private function randomFloat($min, $max, $precision = 6): float
    {
        $multiplier = pow(10, $precision);
        return mt_rand($min * $multiplier, $max * $multiplier) / $multiplier;
    }
}
