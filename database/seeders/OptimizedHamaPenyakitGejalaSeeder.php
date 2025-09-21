<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptimizedHamaPenyakitGejalaSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data (handle foreign key constraints)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('hama_penyakit_gejala')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Get all hama penyakit and gejala
        $hamaPenyakit = DB::table('hama_penyakits')->get()->keyBy('id');
        $gejala = DB::table('gejalas')->get()->keyBy('id');
        
        $relations = [];
        
        // Define relationships based on disease characteristics
        $diseaseSymptomMap = [
            'PH001' => ['G004', 'G005'], // Lalat Bibit Kacang
            'PH002' => ['G005', 'G017'], // Penyakit Rungkad
            'PH003' => ['G004', 'G005'], // Lalat Batang
            'PH004' => ['G010', 'G011'], // Lalat Pucuk
            'PH005' => ['G008', 'G018'], // Kutu Daun Aphis
            'PH006' => ['G012', 'G011'], // Kutu Bemisia
            'PH007' => ['G008', 'G011'], // Tungau Merah
            'PH008' => ['G010', 'G008'], // Kumbang Kedelai
            'PH009' => ['G015', 'G010'], // Ulat Grayak
            'PH010' => ['G010', 'G011'], // Ulat Jengkal
            'PH011' => ['G011', 'G010'], // Ulat Penggulung Daun
            'PH012' => ['G010', 'G008'], // Ulat Pemakan Polong
            'PH013' => ['G008', 'G017'], // Penghisap Polong
            'PH014' => ['G008', 'G017'], // Kepik Hijau
            'PH015' => ['G009', 'G016'], // Penyakit Karat
            'PH016' => ['G009', 'G008'], // Pustul Bakteri
            'PH017' => ['G006', 'G009'], // Antraknose
            'PH018' => ['G014', 'G008'], // Downy Mildew
            'PH019' => ['G009', 'G008'], // Target Spot
            'PH020' => ['G001', 'G005'], // Rebah Kecambah
            'PH021' => ['G005', 'G006'], // Hawar Batang
            'PH022' => ['G016', 'G009'], // Hawar Bercak Daun
            'PH023' => ['G013', 'G018'], // Virus Mosaik
        ];
        
        foreach ($diseaseSymptomMap as $diseaseId => $symptomIds) {
            $disease = $hamaPenyakit->firstWhere('id_penyakit', $diseaseId);
            if (!$disease) continue;
            
            foreach ($symptomIds as $symptomId) {
                $symptom = $gejala->firstWhere('id_gejala', $symptomId);
                if (!$symptom) continue;
                
                // Calculate bobot based on symptom severity and frequency
                $bobot = $this->calculateBobot($symptom, $disease);
                
                $relations[] = [
                    'hama_penyakit_id' => $disease->id,
                    'gejala_id' => $symptom->id,
                    'bobot' => $bobot,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }
        
        // Add some additional relationships for better detection
        $additionalRelations = [
            // High severity symptoms for major diseases
            ['PH009', 'G015', 0.9], // Ulat Grayak - Daun habis tersisa tulang
            ['PH015', 'G009', 0.8], // Penyakit Karat - Bercak coklat
            ['PH020', 'G001', 0.8], // Rebah Kecambah - Akar membusuk
            ['PH021', 'G005', 0.8], // Hawar Batang - Batang menguning
            ['PH023', 'G013', 0.9], // Virus Mosaik - Daun mosaik
            
            // Medium severity symptoms
            ['PH001', 'G005', 0.6], // Lalat Bibit - Batang menguning
            ['PH003', 'G004', 0.7], // Lalat Batang - Batang berlubang
            ['PH005', 'G008', 0.6], // Kutu Daun - Daun menguning
            ['PH007', 'G008', 0.6], // Tungau Merah - Daun menguning
            ['PH012', 'G010', 0.7], // Ulat Pemakan Polong - Daun berlubang
            ['PH013', 'G008', 0.6], // Penghisap Polong - Daun menguning
            ['PH014', 'G008', 0.6], // Kepik Hijau - Daun menguning
            ['PH016', 'G009', 0.7], // Pustul Bakteri - Bercak coklat
            ['PH017', 'G009', 0.7], // Antraknose - Bercak coklat
            ['PH018', 'G014', 0.7], // Downy Mildew - Daun putih
            ['PH019', 'G009', 0.6], // Target Spot - Bercak coklat
            ['PH022', 'G016', 0.7], // Hawar Bercak - Bercak ungu
        ];
        
        foreach ($additionalRelations as [$diseaseId, $symptomId, $bobot]) {
            $disease = $hamaPenyakit->firstWhere('id_penyakit', $diseaseId);
            $symptom = $gejala->firstWhere('id_gejala', $symptomId);
            
            if ($disease && $symptom) {
                // Check if relation already exists
                $exists = collect($relations)->contains(function($relation) use ($disease, $symptom) {
                    return $relation['hama_penyakit_id'] == $disease->id && 
                           $relation['gejala_id'] == $symptom->id;
                });
                
                if (!$exists) {
                    $relations[] = [
                        'hama_penyakit_id' => $disease->id,
                        'gejala_id' => $symptom->id,
                        'bobot' => $bobot,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }
        
        // Insert in batches
        $chunks = array_chunk($relations, 100);
        foreach ($chunks as $chunk) {
            DB::table('hama_penyakit_gejala')->insert($chunk);
        }
        
        $this->command->info('Optimized hama penyakit gejala relationships seeded successfully!');
    }
    
    /**
     * Calculate bobot based on symptom characteristics
     */
    private function calculateBobot($symptom, $disease): float
    {
        $baseBobot = 0.5;
        
        // Increase bobot based on symptom severity
        $severityMultiplier = $symptom->severity_score / 10;
        
        // Increase bobot based on symptom frequency
        $frequencyMultiplier = $symptom->frequency / 100;
        
        // Increase bobot for high priority diseases
        $priorityMultiplier = $disease->priority / 10;
        
        $bobot = $baseBobot + ($severityMultiplier * 0.3) + ($frequencyMultiplier * 0.2) + ($priorityMultiplier * 0.1);
        
        return min(1.0, max(0.1, $bobot));
    }
}
