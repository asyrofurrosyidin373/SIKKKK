<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabKecamatan;
use App\Models\TabKabupaten;
use App\Models\TabProvinsi;

class TestKecamatanSeeder extends Seeder
{
    /**
     * Test seeder dengan data dari PDF - sample kecil
     */
    public function run()
    {
        $this->command->info('🌱 Starting Test Kecamatan seeder...');

        // Pastikan provinsi Jawa Timur ada
        $jatim = TabProvinsi::firstOrCreate(
            ['nama_provinsi' => 'Jawa Timur'],
            ['id' => 35, 'nama_provinsi' => 'Jawa Timur']
        );

        // Pastikan kabupaten Pacitan ada
        $pacitan = TabKabupaten::firstOrCreate(
            ['id' => 3276],
            [
                'id' => 3276,
                'nama_kabupaten' => 'Pacitan',
                'tab_provinsi_id' => $jatim->id
            ]
        );

        // Data sample dari PDF
        $testData = [
            [
                'id' => '327601',
                'tab_kabupaten_id' => '3276',
                'nama_kecamatan' => 'DONOROJO',
                'latitude' => -7.559,
                'longitude' => 111.557,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 143.00,
                'produktivitas' => 1.43,
                'total_produksi' => 204.49,
                'provitas' => 2.00,
                'nilai_potensi' => 2.00,
                'pot_peningkatan_judgement' => 2,
                'varietas_id' => json_encode(['Anjasmoro']),
                'opt_id' => json_encode(['Ulat Grayak', 'Kepik Hijau']),
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['November', 'Desember', 'Januari', 'Februari', 'Maret']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 2.5,
                'kdr_p' => 2.7,
                'kdr_c' => 2.4,
                'kdr_k' => 2.1,
                'ktk' => 3.3,
            ],
            [
                'id' => '327602',
                'tab_kabupaten_id' => '3276',
                'nama_kecamatan' => 'PUNUNG',
                'latitude' => -7.542,
                'longitude' => 111.634,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 142.00,
                'produktivitas' => 1.42,
                'total_produksi' => 201.64,
                'provitas' => 2.00,
                'nilai_potensi' => 2.00,
                'pot_peningkatan_judgement' => 2,
                'varietas_id' => json_encode(['Anjasmoro']),
                'opt_id' => json_encode(['Ulat Grayak', 'Kepik Hijau']),
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['November', 'Desember', 'Januari', 'Februari', 'Maret']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 2.6,
                'kdr_p' => 2.5,
                'kdr_c' => 2.2,
                'kdr_k' => 1.9,
                'ktk' => 3.1,
            ],
            [
                'id' => '327604',
                'tab_kabupaten_id' => '3276',
                'nama_kecamatan' => 'PACITAN',
                'latitude' => -8.2007,
                'longitude' => 111.0943,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 197.00,
                'produktivitas' => 1.97,
                'total_produksi' => 388.09,
                'provitas' => 3.00,
                'nilai_potensi' => 3.00,
                'pot_peningkatan_judgement' => 3,
                'varietas_id' => json_encode(['Anjasmoro']),
                'opt_id' => json_encode(['Ulat Grayak', 'Kepik Hijau']),
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['November', 'Desember', 'Januari', 'Februari', 'Maret']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 3.2,
                'kdr_p' => 2.9,
                'kdr_c' => 2.6,
                'kdr_k' => 2.3,
                'ktk' => 3.5,
            ]
        ];

        $created = 0;
        $updated = 0;

        foreach ($testData as $data) {
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

        $this->command->info('🎉 Test seeder completed!');
        $this->command->info("📊 Created: {$created} kecamatan");
        $this->command->info("🔄 Updated: {$updated} kecamatan");
        
        $totalPacitan = TabKecamatan::where('tab_kabupaten_id', '3276')->count();
        $this->command->info("📍 Total kecamatan di Pacitan: {$totalPacitan}");
    }
}
