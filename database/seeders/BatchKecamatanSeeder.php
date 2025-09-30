<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabKecamatan;
use App\Models\TabKabupaten;
use App\Models\TabProvinsi;

class BatchKecamatanSeeder extends Seeder
{
    /**
     * Seeder batch untuk menambah data kecamatan secara bertahap
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting Batch Kecamatan seeder...');

        // Pastikan provinsi Jawa Timur ada
        $jatim = TabProvinsi::firstOrCreate(
            ['nama_provinsi' => 'Jawa Timur'],
            ['id' => 35, 'nama_provinsi' => 'Jawa Timur']
        );

        // Buat kabupaten Ponorogo
        $ponorogo = TabKabupaten::firstOrCreate(
            ['id' => 3277],
            [
                'id' => 3277,
                'nama_kabupaten' => 'Ponorogo',
                'tab_provinsi_id' => $jatim->id
            ]
        );

        // Data kecamatan Ponorogo (batch pertama)
        $kecamatanData = [
            [
                'id' => '327702',
                'tab_kabupaten_id' => '3277',
                'nama_kecamatan' => 'SLAHUNG',
                'latitude' => -7.5678,
                'longitude' => 111.2456,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 120.00,
                'produktivitas' => 1.20,
                'total_produksi' => 144.00,
                'provitas' => 1.00,
                'nilai_potensi' => 2.00,
                'pot_peningkatan_judgement' => 1,
                'varietas_id' => json_encode(['Anjasmoro', 'Gepak Kuning', 'Gepak Ijo']),
                'opt_id' => json_encode(['Tikus', 'Ulat Grayak', 'Penggerek Polong']),
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['November', 'Desember', 'Januari', 'Februari', 'Maret', 'April']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 2.8,
                'kdr_p' => 2.3,
                'kdr_c' => 2.0,
                'kdr_k' => 1.7,
                'ktk' => 2.9,
            ],
            [
                'id' => '327710',
                'tab_kabupaten_id' => '3277',
                'nama_kecamatan' => 'SIMAN',
                'latitude' => -7.7678,
                'longitude' => 111.3234,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 120.00,
                'produktivitas' => 1.20,
                'total_produksi' => 144.00,
                'provitas' => 1.00,
                'nilai_potensi' => 2.00,
                'pot_peningkatan_judgement' => 1,
                'varietas_id' => json_encode(['Anjasmoro', 'Gepak Kuning', 'Gepak Ijo', 'Dering2', 'Devon1']),
                'opt_id' => json_encode(['Tikus', 'Ulat Grayak', 'Penggerek Polong']),
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['November', 'Desember', 'Januari', 'Februari', 'Maret', 'April']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 2.9,
                'kdr_p' => 2.4,
                'kdr_c' => 2.1,
                'kdr_k' => 1.8,
                'ktk' => 3.0,
            ],
            [
                'id' => '327704',
                'tab_kabupaten_id' => '3277',
                'nama_kecamatan' => 'SAMBIT',
                'latitude' => -7.6234,
                'longitude' => 111.3567,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 120.00,
                'produktivitas' => 1.20,
                'total_produksi' => 144.00,
                'provitas' => 1.00,
                'nilai_potensi' => 2.00,
                'pot_peningkatan_judgement' => 1,
                'varietas_id' => json_encode(['Anjasmoro', 'Gepak Kuning', 'Gepak Ijo']),
                'opt_id' => json_encode(['Tikus', 'Ulat Grayak', 'Penggerek Polong']),
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['November', 'Desember', 'Januari', 'Februari', 'Maret', 'April']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 2.7,
                'kdr_p' => 2.2,
                'kdr_c' => 1.9,
                'kdr_k' => 1.6,
                'ktk' => 2.8,
            ]
        ];

        $created = 0;
        $updated = 0;

        foreach ($kecamatanData as $data) {
            $existing = TabKecamatan::find($data['id']);
            
            if ($existing) {
                $existing->update($data);
                $updated++;
                $this->command->info("✅ Updated: {$data['nama_kecamatan']} ({$data['id']})");
            } else {
                TabKecamatan::create($data);
                $created++;
                $this->command->info("✅ Created: {$data['nama_kecamatan']} ({$data['id']})");
            }
        }

        $this->command->info('🎉 Batch Kecamatan seeder completed!');
        $this->command->info("📊 Created: {$created} kecamatan");
        $this->command->info("🔄 Updated: {$updated} kecamatan");
        
        $totalPonorogo = TabKecamatan::where('tab_kabupaten_id', '3277')->count();
        $totalAll = TabKecamatan::count();
        $this->command->info("📍 Total kecamatan di Ponorogo: {$totalPonorogo}");
        $this->command->info("📍 Total kecamatan di database: {$totalAll}");
    }
}
