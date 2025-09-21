<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HamaPenyakitSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data (handle foreign key constraints)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('hama_penyakits')->delete();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $hamaPenyakitData = [
            [
                'id_penyakit' => 'PH001',
                'nama_penyakit' => 'Lalat Bibit Kacang (Ophiomyia Phaseoli)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Mulsa Jerami',
                'fisik_mekanis' => 'Perlakuan Benih (Daerah Endemik)',
                'hayati' => null,
                'kimiawi' => 'Semprot insektisida saat umur 7 hari, 1 imago/50 rumpun',
                'deskripsi' => 'Serangan awal pada fase kecambah kedelai',
            ],
            [
                'id_penyakit' => 'PH002',
                'nama_penyakit' => 'Penyakit Rungkad',
                'terjangkit' => 'Penyakit',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Mulsa Jerami',
                'fisik_mekanis' => 'Perlakuan Benih (Daerah Endemik)',
                'hayati' => null,
                'kimiawi' => 'Semprot insektisida saat umur 12 hari, 1 imago/50 rumpun',
                'deskripsi' => 'Menyerang batang muda kedelai',
            ],
            [
                'id_penyakit' => 'PH003',
                'nama_penyakit' => 'Lalat Batang (Melanagromyza Sojae)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Mulsa Jerami',
                'fisik_mekanis' => 'Perlakuan Benih',
                'hayati' => null,
                'kimiawi' => 'Semprot insektisida umur 12 hari, ambang 1 imago/50 rumpun',
                'deskripsi' => 'Menyerang batang kedelai',
            ],
            [
                'id_penyakit' => 'PH004',
                'nama_penyakit' => 'Lalat Pucuk (Melanagromyza Dolicostigma)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Tanam serempak',
                'fisik_mekanis' => 'Pemantauan rutin',
                'hayati' => null,
                'kimiawi' => 'Semprot insektisida jika populasi tinggi',
                'deskripsi' => 'Merusak pucuk tanaman',
            ],
            [
                'id_penyakit' => 'PH005',
                'nama_penyakit' => 'Kutu Daun Aphis (Aphis Glycines)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Tanam serempak',
                'fisik_mekanis' => 'Pemantauan rutin',
                'hayati' => null,
                'kimiawi' => 'Semprot insektisida bila populasi tinggi',
                'deskripsi' => 'Daun menguning, keriput, kerdil',
            ],
            [
                'id_penyakit' => 'PH006',
                'nama_penyakit' => 'Kutu Bemisia (Bemisia Tabaci Gennadius)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Tanam serempak',
                'fisik_mekanis' => 'Pemantauan rutin',
                'hayati' => null,
                'kimiawi' => 'Semprot insektisida bila ambang kendali tercapai',
                'deskripsi' => 'Daun berwarna keperakan dan keriting',
            ],
            [
                'id_penyakit' => 'PH007',
                'nama_penyakit' => 'Tungau Merah (Tetranychus Cinnabarius Boisduval)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Pengelolaan gulma, rotasi tanam',
                'fisik_mekanis' => 'Pengumpulan manual, pemangkasan daun',
                'hayati' => null,
                'kimiawi' => 'Semprot insektisida',
                'deskripsi' => 'Daun menguning dan mengering',
            ],
            [
                'id_penyakit' => 'PH008',
                'nama_penyakit' => 'Kumbang Kedelai (Phaedonia Inclusa Stall)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Penanaman awal, pemupukan tepat',
                'fisik_mekanis' => 'Penyisiran manual, penggunaan jaring',
                'hayati' => null,
                'kimiawi' => 'Semprot insektisida bila ambang kendali',
                'deskripsi' => 'Merusak daun dan polong muda',
            ],
            [
                'id_penyakit' => 'PH009',
                'nama_penyakit' => 'Ulat Grayak',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Penjarangan tanaman, rotasi',
                'fisik_mekanis' => 'Pengumpulan manual, pemangkasan',
                'hayati' => 'Penggunaan predator alami, semprot HaNPV',
                'kimiawi' => 'Semprot insektisida bila kerusakan daun 12.5%',
                'deskripsi' => 'Daun habis tersisa tulang',
            ],
            [
                'id_penyakit' => 'PH010',
                'nama_penyakit' => 'Ulat Jengkal (Chrysodeixis Chalcites)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Pengaturan waktu tanam, sanitasi',
                'fisik_mekanis' => 'Penggulung daun dibuka, pemangkasan',
                'hayati' => null,
                'kimiawi' => 'Semprot insektisida',
                'deskripsi' => 'Merusak daun dengan potongan khas',
            ],
            [
                'id_penyakit' => 'PH011',
                'nama_penyakit' => 'Ulat Penggulung Daun (Lamprosema Haliotis)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Pengaturan waktu tanam',
                'fisik_mekanis' => 'Pembukaan gulungan daun',
                'hayati' => null,
                'kimiawi' => 'Semprot insektisida',
                'deskripsi' => 'Daun tergulung dan rusak',
            ],
            [
                'id_penyakit' => 'PH012',
                'nama_penyakit' => 'Ulat Pemakan Polong (Helicoverpa Haliotis)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Pengendalian gulma, perangkap feromon',
                'fisik_mekanis' => 'Pengumpulan manual, penyiangan telur',
                'hayati' => 'Predator alami, semprot HaNPV',
                'kimiawi' => 'Semprot insektisida bila ambang tercapai',
                'deskripsi' => 'Merusak polong kedelai',
            ],
            [
                'id_penyakit' => 'PH013',
                'nama_penyakit' => 'Penghisap Polong (Riptortus Linearis Fabricuis)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Rotasi tanam, pemilihan varietas tahan',
                'fisik_mekanis' => 'Penyemprotan air bertekanan, pengumpulan manual',
                'hayati' => 'Musuh alami atau mikroba patogen',
                'kimiawi' => 'Semprot insektisida',
                'deskripsi' => 'Merusak polong dengan menghisap cairan',
            ],
            [
                'id_penyakit' => 'PH014',
                'nama_penyakit' => 'Kepik Hijau (Nezara Viridula Linnaeus)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Mengatur jarak tanam, rotasi tanaman',
                'fisik_mekanis' => 'Penggunaan perangkap, penyapuan daun',
                'hayati' => null,
                'kimiawi' => 'Semprot insektisida',
                'deskripsi' => 'Merusak polong muda',
            ],
            [
                'id_penyakit' => 'PH015',
                'nama_penyakit' => 'Penyakit Karat (Phakopsora Pachyrhizi)',
                'terjangkit' => 'Penyakit',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Varietas tahan, rotasi',
                'fisik_mekanis' => 'Pengaturan jarak tanam',
                'hayati' => null,
                'kimiawi' => 'Fungisida mankoseb, triadimefon, difenokonazol',
                'deskripsi' => 'Bercak coklat pada daun',
            ],
            [
                'id_penyakit' => 'PH016',
                'nama_penyakit' => 'Penyakit Pustul Bakteri (Xanthomonas axonopodis pv glycines)',
                'terjangkit' => 'Penyakit',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Rotasi tanaman, sanitasi',
                'fisik_mekanis' => 'Penggunaan benih sehat',
                'hayati' => null,
                'kimiawi' => 'Fungisida bakteri',
                'deskripsi' => 'Pustul pada daun',
            ],
            [
                'id_penyakit' => 'PH017',
                'nama_penyakit' => 'Penyakit Antraknose (Collectrium Dematium var Truncatum)',
                'terjangkit' => 'Penyakit',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Hancurkan sisa tanaman, rotasi',
                'fisik_mekanis' => 'Benih sehat',
                'hayati' => 'Mikroba antagonis (Trichoderma, Pseudomonas)',
                'kimiawi' => 'Fungisida benomil, klorotalonil',
                'deskripsi' => 'Bercak hitam batang/daun',
            ],
            [
                'id_penyakit' => 'PH018',
                'nama_penyakit' => 'Penyakit Downy Mildew (Peronospora Manshurica)',
                'terjangkit' => 'Penyakit',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Benih sehat, rotasi',
                'fisik_mekanis' => 'Sanitasi lahan',
                'hayati' => null,
                'kimiawi' => 'Fungisida sistemik',
                'deskripsi' => 'Daun berwarna putih',
            ],
            [
                'id_penyakit' => 'PH019',
                'nama_penyakit' => 'Target Spot (Corynespora)',
                'terjangkit' => 'Penyakit',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Perbaikan drainase',
                'fisik_mekanis' => 'Pemangkasan tanaman sakit',
                'hayati' => null,
                'kimiawi' => 'Fungisida benomil, klorotalonil',
                'deskripsi' => 'Bercak pada daun',
            ],
            [
                'id_penyakit' => 'PH020',
                'nama_penyakit' => 'Rebah Kecambah, Busuk Daun & Polong (Rhizoctonia Solani)',
                'terjangkit' => 'Penyakit',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Perawatan benih, sanitasi',
                'fisik_mekanis' => 'Membenamkan tanaman terinfeksi',
                'hayati' => 'Cendawan antagonis',
                'kimiawi' => 'Fungisida sistemik',
                'deskripsi' => 'Busuk akar & polong',
            ],
            [
                'id_penyakit' => 'PH021',
                'nama_penyakit' => 'Penyakit Hawar Batang (Sclerotium Rolfsii)',
                'terjangkit' => 'Penyakit',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Rotasi, perbaikan drainase',
                'fisik_mekanis' => 'Membenamkan tanaman sakit',
                'hayati' => 'Cendawan antagonis',
                'kimiawi' => 'Fungisida sistemik',
                'deskripsi' => 'Hawar batang',
            ],
            [
                'id_penyakit' => 'PH022',
                'nama_penyakit' => 'Penyakit Hawar, Bercak Daun & Bercak Biji Ungu (Cercospora Kikuchii)',
                'terjangkit' => 'Penyakit',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Rotasi, varietas tahan',
                'fisik_mekanis' => 'Sanitasi lahan',
                'hayati' => 'Mikroba antagonis',
                'kimiawi' => 'Fungisida',
                'deskripsi' => 'Bercak ungu pada daun/biji',
            ],
            [
                'id_penyakit' => 'PH023',
                'nama_penyakit' => 'Penyakit Virus Mosaik Kedelai',
                'terjangkit' => 'Penyakit',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Benih sehat, pengendalian vektor',
                'fisik_mekanis' => 'Pemusnahan tanaman terinfeksi',
                'hayati' => null,
                'kimiawi' => null,
                'deskripsi' => 'Daun mosaik hijau-kuning',
                'is_active' => true,
                'priority' => 8,
                'metadata' => json_encode([
                    'severity_level' => 'high',
                    'common_season' => 'musim_hujan',
                    'affected_stages' => ['vegetative', 'reproductive']
                ])
            ],
        ];

        // Add optimized fields to all records
        foreach ($hamaPenyakitData as &$record) {
            $record['is_active'] = true;
            $record['priority'] = $this->calculatePriority($record);
            $record['metadata'] = json_encode($this->generateMetadata($record));
            $record['created_at'] = now();
            $record['updated_at'] = now();
        }

        // Insert in batches for better performance
        $chunks = array_chunk($hamaPenyakitData, 100);
        foreach ($chunks as $chunk) {
            DB::table('hama_penyakits')->insert($chunk);
        }
    }

    /**
     * Calculate priority based on disease characteristics
     */
    private function calculatePriority($record): int
    {
        $priority = 5; // Base priority
        
        // Increase priority for diseases with chemical control
        if (!empty($record['kimiawi'])) {
            $priority += 2;
        }
        
        // Increase priority for diseases with biological control
        if (!empty($record['hayati'])) {
            $priority += 1;
        }
        
        // Increase priority for diseases affecting multiple plant parts
        $controlMethods = array_filter([
            $record['kultur_teknis'],
            $record['fisik_mekanis'],
            $record['hayati'],
            $record['kimiawi']
        ]);
        
        $priority += count($controlMethods);
        
        return min(10, max(1, $priority));
    }

    /**
     * Generate metadata for the disease
     */
    private function generateMetadata($record): array
    {
        $metadata = [
            'control_methods_count' => count(array_filter([
                $record['kultur_teknis'],
                $record['fisik_mekanis'],
                $record['hayati'],
                $record['kimiawi']
            ])),
            'has_chemical_control' => !empty($record['kimiawi']),
            'has_biological_control' => !empty($record['hayati']),
            'has_cultural_control' => !empty($record['kultur_teknis']),
            'has_physical_control' => !empty($record['fisik_mekanis']),
        ];

        // Add severity level based on description
        $description = strtolower($record['deskripsi'] ?? '');
        if (str_contains($description, 'parah') || str_contains($description, 'habis') || str_contains($description, 'rusak')) {
            $metadata['severity_level'] = 'high';
        } elseif (str_contains($description, 'sedang') || str_contains($description, 'menguning')) {
            $metadata['severity_level'] = 'medium';
        } else {
            $metadata['severity_level'] = 'low';
        }

        // Add affected plant parts
        $affectedParts = [];
        if (str_contains($description, 'daun')) {
            $affectedParts[] = 'leaf';
        }
        if (str_contains($description, 'batang')) {
            $affectedParts[] = 'stem';
        }
        if (str_contains($description, 'polong') || str_contains($description, 'biji')) {
            $affectedParts[] = 'pod';
        }
        if (str_contains($description, 'akar')) {
            $affectedParts[] = 'root';
        }
        
        $metadata['affected_parts'] = $affectedParts;

        return $metadata;
    }
}
