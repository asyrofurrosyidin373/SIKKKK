<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OptimizedGejalaSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data (handle foreign key constraints)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('gejalas')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $gejalaData = [
            // Gejala Akar
            [
                'id_gejala' => 'G001',
                'gejala' => 'Akar membusuk dan berwarna coklat kehitaman',
                'daerah' => 'Akar',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 75,
                'severity_score' => 8.5,
            ],
            [
                'id_gejala' => 'G002',
                'gejala' => 'Akar berwarna kuning dan mudah patah',
                'daerah' => 'Akar',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 60,
                'severity_score' => 7.0,
            ],
            [
                'id_gejala' => 'G003',
                'gejala' => 'Bintil akar berkurang atau tidak ada',
                'daerah' => 'Akar',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 45,
                'severity_score' => 6.5,
            ],
            
            // Gejala Batang
            [
                'id_gejala' => 'G004',
                'gejala' => 'Batang berlubang dan berwarna coklat',
                'daerah' => 'Batang',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 80,
                'severity_score' => 8.0,
            ],
            [
                'id_gejala' => 'G005',
                'gejala' => 'Batang menguning dan layu',
                'daerah' => 'Batang',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 70,
                'severity_score' => 7.5,
            ],
            [
                'id_gejala' => 'G006',
                'gejala' => 'Batang bengkak dan berwarna kehitaman',
                'daerah' => 'Batang',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 55,
                'severity_score' => 8.5,
            ],
            [
                'id_gejala' => 'G007',
                'gejala' => 'Batang retak dan mengeluarkan lendir',
                'daerah' => 'Batang',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 40,
                'severity_score' => 7.0,
            ],
            
            // Gejala Daun
            [
                'id_gejala' => 'G008',
                'gejala' => 'Daun menguning dan keriput',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 85,
                'severity_score' => 6.5,
            ],
            [
                'id_gejala' => 'G009',
                'gejala' => 'Bercak coklat pada daun',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 90,
                'severity_score' => 7.5,
            ],
            [
                'id_gejala' => 'G010',
                'gejala' => 'Daun berlubang dan tidak beraturan',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 75,
                'severity_score' => 8.0,
            ],
            [
                'id_gejala' => 'G011',
                'gejala' => 'Daun menggulung dan mengering',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 65,
                'severity_score' => 7.0,
            ],
            [
                'id_gejala' => 'G012',
                'gejala' => 'Daun berwarna keperakan',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 50,
                'severity_score' => 6.0,
            ],
            [
                'id_gejala' => 'G013',
                'gejala' => 'Daun mosaik hijau-kuning',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 45,
                'severity_score' => 8.5,
            ],
            [
                'id_gejala' => 'G014',
                'gejala' => 'Daun berwarna putih seperti tepung',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 60,
                'severity_score' => 7.5,
            ],
            [
                'id_gejala' => 'G015',
                'gejala' => 'Daun habis tersisa tulang daun',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 70,
                'severity_score' => 9.0,
            ],
            [
                'id_gejala' => 'G016',
                'gejala' => 'Bercak ungu pada daun',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 55,
                'severity_score' => 7.0,
            ],
            [
                'id_gejala' => 'G017',
                'gejala' => 'Daun layu dan menggantung',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 80,
                'severity_score' => 8.0,
            ],
            [
                'id_gejala' => 'G018',
                'gejala' => 'Daun kerdil dan tidak berkembang',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kedelai',
                'is_active' => true,
                'frequency' => 40,
                'severity_score' => 6.5,
            ],
            
            // Gejala Kacang Tanah
            [
                'id_gejala' => 'G019',
                'gejala' => 'Akar kacang tanah membusuk',
                'daerah' => 'Akar',
                'jenis_tanaman' => 'Kacang Tanah',
                'is_active' => true,
                'frequency' => 70,
                'severity_score' => 8.0,
            ],
            [
                'id_gejala' => 'G020',
                'gejala' => 'Daun kacang tanah menguning',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kacang Tanah',
                'is_active' => true,
                'frequency' => 75,
                'severity_score' => 7.0,
            ],
            [
                'id_gejala' => 'G021',
                'gejala' => 'Bercak hitam pada daun kacang tanah',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kacang Tanah',
                'is_active' => true,
                'frequency' => 65,
                'severity_score' => 7.5,
            ],
            
            // Gejala Kacang Hijau
            [
                'id_gejala' => 'G022',
                'gejala' => 'Akar kacang hijau membusuk',
                'daerah' => 'Akar',
                'jenis_tanaman' => 'Kacang Hijau',
                'is_active' => true,
                'frequency' => 60,
                'severity_score' => 7.5,
            ],
            [
                'id_gejala' => 'G023',
                'gejala' => 'Daun kacang hijau berlubang',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kacang Hijau',
                'is_active' => true,
                'frequency' => 70,
                'severity_score' => 8.0,
            ],
            [
                'id_gejala' => 'G024',
                'gejala' => 'Bercak kuning pada daun kacang hijau',
                'daerah' => 'Daun',
                'jenis_tanaman' => 'Kacang Hijau',
                'is_active' => true,
                'frequency' => 55,
                'severity_score' => 6.5,
            ],
        ];

        // Add timestamps and optimize data
        foreach ($gejalaData as &$record) {
            $record['created_at'] = now();
            $record['updated_at'] = now();
        }

        // Insert in batches for better performance
        $chunks = array_chunk($gejalaData, 50);
        foreach ($chunks as $chunk) {
            DB::table('gejalas')->insert($chunk);
        }

        $this->command->info('Optimized gejala data seeded successfully!');
    }
}
