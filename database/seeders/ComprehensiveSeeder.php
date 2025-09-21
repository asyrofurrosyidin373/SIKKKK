<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ComprehensiveSeeder extends Seeder
{
    public function run()
    {
        $this->command->info('Starting comprehensive seeding...');

        // 1. Clear existing data with foreign key handling
        $this->clearData();

        // 2. Seed provinces, districts, and sub-districts
        $this->seedRegions();

        // 3. Seed organizations
        $this->seedOrganizations();

        // 4. Seed diseases and symptoms
        $this->seedDiseasesAndSymptoms();

        // 5. Seed varieties
        $this->seedVarieties();

        // 6. Seed relationships
        $this->seedRelationships();

        $this->command->info('Comprehensive seeding completed successfully!');
    }

    private function clearData()
    {
        $this->command->info('Clearing existing data...');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        $tables = [
            'hama_penyakit_gejala',
            'hama_penyakit_insektisida',
            'deteksi_histories',
            'gejalas',
            'hama_penyakits',
            'varietas_kedelai',
            'varietas_kacang_tanah',
            'varietas_kacang_hijau',
            'org_pen_tan',
            'tab_kecamatan',
            'tab_kabupaten',
            'tab_provinsi'
        ];

        foreach ($tables as $table) {
            DB::table($table)->delete();
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function seedRegions()
    {
        $this->command->info('Seeding regions...');

        // Provinces
        $provinces = [
            ['id' => '11', 'nama_provinsi' => 'Aceh'],
            ['id' => '12', 'nama_provinsi' => 'Sumatera Utara'],
            ['id' => '13', 'nama_provinsi' => 'Sumatera Barat'],
            ['id' => '14', 'nama_provinsi' => 'Riau'],
            ['id' => '15', 'nama_provinsi' => 'Jambi'],
            ['id' => '16', 'nama_provinsi' => 'Sumatera Selatan'],
            ['id' => '17', 'nama_provinsi' => 'Bengkulu'],
            ['id' => '18', 'nama_provinsi' => 'Lampung'],
            ['id' => '19', 'nama_provinsi' => 'Kepulauan Bangka Belitung'],
            ['id' => '21', 'nama_provinsi' => 'Kepulauan Riau'],
            ['id' => '31', 'nama_provinsi' => 'DKI Jakarta'],
            ['id' => '32', 'nama_provinsi' => 'Jawa Barat'],
            ['id' => '33', 'nama_provinsi' => 'Jawa Tengah'],
            ['id' => '34', 'nama_provinsi' => 'DI Yogyakarta'],
            ['id' => '35', 'nama_provinsi' => 'Jawa Timur'],
            ['id' => '36', 'nama_provinsi' => 'Banten'],
            ['id' => '51', 'nama_provinsi' => 'Bali'],
            ['id' => '52', 'nama_provinsi' => 'Nusa Tenggara Barat'],
            ['id' => '53', 'nama_provinsi' => 'Nusa Tenggara Timur'],
            ['id' => '61', 'nama_provinsi' => 'Kalimantan Barat'],
            ['id' => '62', 'nama_provinsi' => 'Kalimantan Tengah'],
            ['id' => '63', 'nama_provinsi' => 'Kalimantan Selatan'],
            ['id' => '64', 'nama_provinsi' => 'Kalimantan Timur'],
            ['id' => '65', 'nama_provinsi' => 'Kalimantan Utara'],
            ['id' => '71', 'nama_provinsi' => 'Sulawesi Utara'],
            ['id' => '72', 'nama_provinsi' => 'Sulawesi Tengah'],
            ['id' => '73', 'nama_provinsi' => 'Sulawesi Selatan'],
            ['id' => '74', 'nama_provinsi' => 'Sulawesi Tenggara'],
            ['id' => '75', 'nama_provinsi' => 'Gorontalo'],
            ['id' => '76', 'nama_provinsi' => 'Sulawesi Barat'],
            ['id' => '81', 'nama_provinsi' => 'Maluku'],
            ['id' => '82', 'nama_provinsi' => 'Maluku Utara'],
            ['id' => '91', 'nama_provinsi' => 'Papua Barat'],
            ['id' => '94', 'nama_provinsi' => 'Papua']
        ];

        foreach ($provinces as $province) {
            $province['created_at'] = now();
            $province['updated_at'] = now();
        }
        DB::table('tab_provinsi')->insert($provinces);

        // Sample districts for key provinces
        $districts = [
            // Jawa Barat
            ['id' => '3201', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Bogor'],
            ['id' => '3202', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Sukabumi'],
            ['id' => '3203', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Cianjur'],
            ['id' => '3204', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Bandung'],
            ['id' => '3205', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Garut'],
            ['id' => '3206', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Tasikmalaya'],
            ['id' => '3207', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Ciamis'],
            ['id' => '3208', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Kuningan'],
            ['id' => '3209', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Cirebon'],
            ['id' => '3210', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Majalengka'],
            ['id' => '3211', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Sumedang'],
            ['id' => '3212', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Indramayu'],
            ['id' => '3213', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Subang'],
            ['id' => '3214', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Purwakarta'],
            ['id' => '3215', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Karawang'],
            ['id' => '3216', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Bekasi'],
            ['id' => '3217', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Bandung Barat'],
            ['id' => '3218', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Pangandaran'],
            
            // Jawa Tengah
            ['id' => '3301', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Cilacap'],
            ['id' => '3302', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Banyumas'],
            ['id' => '3303', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Purbalingga'],
            ['id' => '3304', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Banjarnegara'],
            ['id' => '3305', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Kebumen'],
            ['id' => '3306', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Purworejo'],
            ['id' => '3307', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Wonosobo'],
            ['id' => '3308', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Magelang'],
            ['id' => '3309', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Boyolali'],
            ['id' => '3310', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Klaten'],
            ['id' => '3311', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Sukoharjo'],
            ['id' => '3312', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Wonogiri'],
            ['id' => '3313', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Karanganyar'],
            ['id' => '3314', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Sragen'],
            ['id' => '3315', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Grobogan'],
            ['id' => '3316', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Blora'],
            ['id' => '3317', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Rembang'],
            ['id' => '3318', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Pati'],
            ['id' => '3319', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Kudus'],
            ['id' => '3320', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Jepara'],
            ['id' => '3321', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Demak'],
            ['id' => '3322', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Semarang'],
            ['id' => '3323', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Temanggung'],
            ['id' => '3324', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Kendal'],
            ['id' => '3325', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Batang'],
            ['id' => '3326', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Pekalongan'],
            ['id' => '3327', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Pemalang'],
            ['id' => '3328', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Tegal'],
            ['id' => '3329', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Brebes'],
            
            // Jawa Timur
            ['id' => '3501', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Pacitan'],
            ['id' => '3502', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Ponorogo'],
            ['id' => '3503', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Trenggalek'],
            ['id' => '3504', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Tulungagung'],
            ['id' => '3505', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Blitar'],
            ['id' => '3506', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Kediri'],
            ['id' => '3507', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Malang'],
            ['id' => '3508', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Lumajang'],
            ['id' => '3509', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Jember'],
            ['id' => '3510', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Banyuwangi'],
            ['id' => '3511', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Bondowoso'],
            ['id' => '3512', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Situbondo'],
            ['id' => '3513', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Probolinggo'],
            ['id' => '3514', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Pasuruan'],
            ['id' => '3515', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Sidoarjo'],
            ['id' => '3516', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Mojokerto'],
            ['id' => '3517', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Jombang'],
            ['id' => '3518', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Nganjuk'],
            ['id' => '3519', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Madiun'],
            ['id' => '3520', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Magetan'],
            ['id' => '3521', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Ngawi'],
            ['id' => '3522', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Bojonegoro'],
            ['id' => '3523', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Tuban'],
            ['id' => '3524', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Lamongan'],
            ['id' => '3525', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Gresik'],
            ['id' => '3526', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Bangkalan'],
            ['id' => '3527', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Sampang'],
            ['id' => '3528', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Pamekasan'],
            ['id' => '3529', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Sumenep']
        ];

        foreach ($districts as $district) {
            $district['created_at'] = now();
            $district['updated_at'] = now();
        }
        DB::table('tab_kabupaten')->insert($districts);

        // Sample sub-districts with coordinates
        $subDistricts = [];
        foreach ($districts as $district) {
            for ($i = 1; $i <= 5; $i++) {
                // Generate random coordinates within reasonable bounds for Indonesia
                $baseLat = -6.0 + (rand(-200, 200) / 100); // Around Java
                $baseLng = 106.0 + (rand(-200, 200) / 100); // Around Java
                
                $subDistricts[] = [
                    'id' => $district['id'] . sprintf('%02d', $i),
                    'tab_kabupaten_id' => $district['id'],
                    'nama_kecamatan' => 'Kecamatan ' . $i . ' ' . $district['nama_kabupaten'],
                    'latitude' => $baseLat,
                    'longitude' => $baseLng,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        DB::table('tab_kecamatan')->insert($subDistricts);
    }

    private function seedOrganizations()
    {
        $this->command->info('Seeding organizations...');

        $organizations = [
            [
                'id' => Str::uuid(),
                'nama_opt' => 'Balitkabi',
                'jenis' => 'Organisasi',
                'gambar' => 'balitkabi.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid(),
                'nama_opt' => 'Balitbangtan',
                'jenis' => 'Organisasi',
                'gambar' => 'balitbangtan.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid(),
                'nama_opt' => 'IPB University',
                'jenis' => 'Universitas',
                'gambar' => 'ipb.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('org_pen_tan')->insert($organizations);
    }

    private function seedDiseasesAndSymptoms()
    {
        $this->command->info('Seeding diseases and symptoms...');

        // Seed symptoms first
        $symptoms = [
            // Gejala Akar
            ['id_gejala' => 'G001', 'gejala' => 'Akar membusuk dan berwarna coklat kehitaman', 'daerah' => 'Akar', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 75, 'severity_score' => 8.5],
            ['id_gejala' => 'G002', 'gejala' => 'Akar berwarna kuning dan mudah patah', 'daerah' => 'Akar', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 60, 'severity_score' => 7.0],
            ['id_gejala' => 'G003', 'gejala' => 'Bintil akar berkurang atau tidak ada', 'daerah' => 'Akar', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 45, 'severity_score' => 6.5],
            
            // Gejala Batang
            ['id_gejala' => 'G004', 'gejala' => 'Batang berlubang dan berwarna coklat', 'daerah' => 'Batang', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 80, 'severity_score' => 8.0],
            ['id_gejala' => 'G005', 'gejala' => 'Batang menguning dan layu', 'daerah' => 'Batang', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 70, 'severity_score' => 7.5],
            ['id_gejala' => 'G006', 'gejala' => 'Batang bengkak dan berwarna kehitaman', 'daerah' => 'Batang', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 55, 'severity_score' => 8.5],
            ['id_gejala' => 'G007', 'gejala' => 'Batang retak dan mengeluarkan lendir', 'daerah' => 'Batang', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 40, 'severity_score' => 7.0],
            
            // Gejala Daun
            ['id_gejala' => 'G008', 'gejala' => 'Daun menguning dan keriput', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 85, 'severity_score' => 6.5],
            ['id_gejala' => 'G009', 'gejala' => 'Bercak coklat pada daun', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 90, 'severity_score' => 7.5],
            ['id_gejala' => 'G010', 'gejala' => 'Daun berlubang dan tidak beraturan', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 75, 'severity_score' => 8.0],
            ['id_gejala' => 'G011', 'gejala' => 'Daun menggulung dan mengering', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 65, 'severity_score' => 7.0],
            ['id_gejala' => 'G012', 'gejala' => 'Daun berwarna keperakan', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 50, 'severity_score' => 6.0],
            ['id_gejala' => 'G013', 'gejala' => 'Daun mosaik hijau-kuning', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 45, 'severity_score' => 8.5],
            ['id_gejala' => 'G014', 'gejala' => 'Daun berwarna putih seperti tepung', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 60, 'severity_score' => 7.5],
            ['id_gejala' => 'G015', 'gejala' => 'Daun habis tersisa tulang daun', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 70, 'severity_score' => 9.0],
            ['id_gejala' => 'G016', 'gejala' => 'Bercak ungu pada daun', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 55, 'severity_score' => 7.0],
            ['id_gejala' => 'G017', 'gejala' => 'Daun layu dan menggantung', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 80, 'severity_score' => 8.0],
            ['id_gejala' => 'G018', 'gejala' => 'Daun kerdil dan tidak berkembang', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai', 'is_active' => true, 'frequency' => 40, 'severity_score' => 6.5],
        ];

        foreach ($symptoms as $symptom) {
            $symptom['created_at'] = now();
            $symptom['updated_at'] = now();
        }
        DB::table('gejalas')->insert($symptoms);

        // Seed diseases
        $diseases = [
            [
                'id_penyakit' => 'PH001',
                'nama_penyakit' => 'Lalat Bibit Kacang (Ophiomyia Phaseoli)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Rotasi tanaman, sanitasi lahan, penggunaan benih sehat',
                'fisik_mekanis' => 'Pemasangan perangkap kuning, pengumpulan dan pemusnahan tanaman terserang',
                'hayati' => 'Penggunaan parasitoid dan predator alami',
                'kimiawi' => 'Aplikasi insektisida sistemik pada benih',
                'gambar' => 'lalat_bibit_kacang.jpg',
                'deskripsi' => 'Hama yang menyerang benih dan bibit kedelai, menyebabkan kerusakan parah pada fase awal pertumbuhan',
                'is_active' => true,
                'priority' => 9,
                'metadata' => json_encode(['severity_level' => 'high', 'affected_parts' => ['root', 'stem'], 'control_methods' => ['cultural', 'biological', 'chemical']])
            ],
            [
                'id_penyakit' => 'PH002',
                'nama_penyakit' => 'Ulat Grayak (Spodoptera Litura)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Rotasi tanaman, sanitasi lahan, penanaman varietas tahan',
                'fisik_mekanis' => 'Pengumpulan dan pemusnahan telur dan larva',
                'hayati' => 'Penggunaan parasitoid Trichogramma sp.',
                'kimiawi' => 'Aplikasi insektisida kontak dan sistemik',
                'gambar' => 'ulat_grayak.jpg',
                'deskripsi' => 'Hama yang menyerang daun kedelai, menyebabkan kerusakan parah pada fase vegetatif',
                'is_active' => true,
                'priority' => 8,
                'metadata' => json_encode(['severity_level' => 'high', 'affected_parts' => ['leaf'], 'control_methods' => ['cultural', 'biological', 'chemical']])
            ],
            [
                'id_penyakit' => 'PH003',
                'nama_penyakit' => 'Kutu Daun (Aphis Glycines)',
                'terjangkit' => 'Hama',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Rotasi tanaman, sanitasi lahan, penanaman varietas tahan',
                'fisik_mekanis' => 'Penyemprotan air bertekanan tinggi',
                'hayati' => 'Penggunaan predator Coccinellidae',
                'kimiawi' => 'Aplikasi insektisida sistemik',
                'gambar' => 'kutu_daun.jpg',
                'deskripsi' => 'Hama yang menyerang daun dan batang muda, menyebabkan daun menguning dan keriput',
                'is_active' => true,
                'priority' => 7,
                'metadata' => json_encode(['severity_level' => 'medium', 'affected_parts' => ['leaf', 'stem'], 'control_methods' => ['cultural', 'biological', 'chemical']])
            ],
            [
                'id_penyakit' => 'PH004',
                'nama_penyakit' => 'Penyakit Hawar Daun (Cercospora Sojina)',
                'terjangkit' => 'Penyakit',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Rotasi tanaman, sanitasi lahan, penanaman varietas tahan',
                'fisik_mekanis' => 'Pemangkasan daun terserang',
                'hayati' => 'Penggunaan agens antagonis',
                'kimiawi' => 'Aplikasi fungisida sistemik',
                'gambar' => 'hawar_daun.jpg',
                'deskripsi' => 'Penyakit yang menyerang daun kedelai, menyebabkan bercak coklat dan penurunan hasil',
                'is_active' => true,
                'priority' => 8,
                'metadata' => json_encode(['severity_level' => 'high', 'affected_parts' => ['leaf'], 'control_methods' => ['cultural', 'biological', 'chemical']])
            ],
            [
                'id_penyakit' => 'PH005',
                'nama_penyakit' => 'Penyakit Busuk Akar (Phytophthora Sojae)',
                'terjangkit' => 'Penyakit',
                'jenis_tanaman' => 'Kedelai',
                'kultur_teknis' => 'Drainase yang baik, rotasi tanaman, penanaman varietas tahan',
                'fisik_mekanis' => 'Pencabutan tanaman terserang',
                'hayati' => 'Penggunaan agens antagonis Trichoderma sp.',
                'kimiawi' => 'Aplikasi fungisida sistemik',
                'gambar' => 'busuk_akar.jpg',
                'deskripsi' => 'Penyakit yang menyerang akar kedelai, menyebabkan akar membusuk dan tanaman layu',
                'is_active' => true,
                'priority' => 9,
                'metadata' => json_encode(['severity_level' => 'high', 'affected_parts' => ['root'], 'control_methods' => ['cultural', 'biological', 'chemical']])
            ]
        ];

        foreach ($diseases as $disease) {
            $disease['created_at'] = now();
            $disease['updated_at'] = now();
        }
        DB::table('hama_penyakits')->insert($diseases);
    }

    private function seedVarieties()
    {
        $this->command->info('Seeding varieties...');

        $balitkabiId = DB::table('org_pen_tan')->where('nama_opt', 'Balitkabi')->value('id');

        $varieties = [
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Argomulyo',
                'tahun' => '2003',
                'potensi_hasil' => 3.5,
                'umur_masak' => 85,
                'asal' => 'Malang',
                'inventor' => 'Balitkabi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Anjasmoro',
                'tahun' => '2001',
                'potensi_hasil' => 3.2,
                'umur_masak' => 80,
                'asal' => 'Malang',
                'inventor' => 'Balitkabi',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => Str::uuid(),
                'org_pen_tan_id' => $balitkabiId,
                'nama_varietas' => 'Grobogan',
                'tahun' => '2002',
                'potensi_hasil' => 3.0,
                'umur_masak' => 75,
                'asal' => 'Grobogan',
                'inventor' => 'Balitkabi',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('varietas_kedelai')->insert($varieties);
    }

    private function seedRelationships()
    {
        $this->command->info('Seeding relationships...');

        // Get diseases and symptoms
        $diseases = DB::table('hama_penyakits')->get();
        $symptoms = DB::table('gejalas')->get();

        $relationships = [];

        // Create relationships between diseases and symptoms
        foreach ($diseases as $disease) {
            $diseaseSymptoms = $symptoms->random(rand(3, 6));
            
            foreach ($diseaseSymptoms as $symptom) {
                $relationships[] = [
                    'hama_penyakit_id' => $disease->id,
                    'gejala_id' => $symptom->id,
                    'bobot' => rand(1, 10),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        DB::table('hama_penyakit_gejala')->insert($relationships);
    }
}
