<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabKecamatan;
use App\Models\TabKabupaten;
use App\Models\TabProvinsi;

class MoreKecamatanSeeder extends Seeder
{
    /**
     * Seeder untuk menambah lebih banyak kecamatan dari data PDF
     */
    public function run()
    {
        $this->command->info('🌱 Starting More Kecamatan seeder...');

        // Pastikan provinsi Jawa Timur ada
        $jatim = TabProvinsi::firstOrCreate(
            ['nama_provinsi' => 'Jawa Timur'],
            ['id' => 35, 'nama_provinsi' => 'Jawa Timur']
        );

        // Buat kabupaten-kabupaten baru
        $kabupatenData = [
            ['id' => 3277, 'name' => 'Ponorogo'],
            ['id' => 3278, 'name' => 'Trenggalek'],
            ['id' => 3279, 'name' => 'Tulungagung'],
            ['id' => 3281, 'name' => 'Blitar'],
            ['id' => 3282, 'name' => 'Kediri'],
        ];

        foreach ($kabupatenData as $kab) {
            TabKabupaten::firstOrCreate(
                ['id' => $kab['id']],
                [
                    'id' => $kab['id'],
                    'nama_kabupaten' => $kab['name'],
                    'tab_provinsi_id' => $jatim->id
                ]
            );
        }

        // Data kecamatan dari PDF (sample yang paling produktif)
        $kecamatanData = [
            // PONOROGO
            [
                'id' => '327702',
                'tab_kabupaten_id' => '3277',
                'nama_kecamatan' => 'SLAHUNG',
                'latitude' => -7.7234,
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
                'latitude' => -7.6789,
                'longitude' => 111.3456,
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

            // TRENGGALEK
            [
                'id' => '327801',
                'tab_kabupaten_id' => '3278',
                'nama_kecamatan' => 'PANGGUL',
                'latitude' => -8.0123,
                'longitude' => 111.6789,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 150.00,
                'produktivitas' => 1.50,
                'total_produksi' => 225.00,
                'provitas' => 3.00,
                'nilai_potensi' => 6.00,
                'pot_peningkatan_judgement' => 3,
                'varietas_id' => json_encode(['Lokal', 'Anjasmoro', 'Detap 1']),
                'opt_id' => json_encode(['Ulat Grayak', 'Penggerek Polong']),
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['Januari', 'Februari', 'Maret', 'April', 'Oktober', 'November', 'Desember']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 3.2,
                'kdr_p' => 2.7,
                'kdr_c' => 2.3,
                'kdr_k' => 2.0,
                'ktk' => 3.4,
            ],
            [
                'id' => '327807',
                'tab_kabupaten_id' => '3278',
                'nama_kecamatan' => 'KARANGAN',
                'latitude' => -8.1234,
                'longitude' => 111.7890,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 166.00,
                'produktivitas' => 1.66,
                'total_produksi' => 275.56,
                'provitas' => 3.00,
                'nilai_potensi' => 6.00,
                'pot_peningkatan_judgement' => 3,
                'varietas_id' => json_encode(['Anjasmoro', 'Detap 1']),
                'opt_id' => json_encode(['Ulat Grayak', 'Penggerek Polong']),
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['Januari', 'Februari', 'Maret', 'April', 'Oktober', 'November', 'Desember']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 3.3,
                'kdr_p' => 2.8,
                'kdr_c' => 2.4,
                'kdr_k' => 2.1,
                'ktk' => 3.5,
            ],

            // TULUNGAGUNG
            [
                'id' => '327901',
                'tab_kabupaten_id' => '3279',
                'nama_kecamatan' => 'BESUKI',
                'latitude' => -8.2345,
                'longitude' => 111.8901,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 190.00,
                'produktivitas' => 1.90,
                'total_produksi' => 361.00,
                'provitas' => 3.00,
                'nilai_potensi' => 6.00,
                'pot_peningkatan_judgement' => 3,
                'varietas_id' => json_encode(['Dering1', 'Detam2', 'Detam 4']),
                'opt_id' => json_encode(['Ulat Grayak', 'Penggerek Polong', 'Penggulung Daun']),
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['November', 'Desember', 'Januari', 'Februari', 'Maret', 'April']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 3.4,
                'kdr_p' => 2.9,
                'kdr_c' => 2.5,
                'kdr_k' => 2.2,
                'ktk' => 3.6,
            ],
            [
                'id' => '327906',
                'tab_kabupaten_id' => '3279',
                'nama_kecamatan' => 'KALIDAWIR',
                'latitude' => -8.1456,
                'longitude' => 111.9012,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 200.00,
                'produktivitas' => 2.00,
                'total_produksi' => 400.00,
                'provitas' => 2.00,
                'nilai_potensi' => 4.00,
                'pot_peningkatan_judgement' => 2,
                'varietas_id' => json_encode(['Dering1', 'Detam2', 'Detam 4']),
                'opt_id' => json_encode(['Ulat Grayak', 'Penggerek Polong', 'Penggulung Daun']),
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['November', 'Desember', 'Januari', 'Februari', 'Maret']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 3.5,
                'kdr_p' => 3.0,
                'kdr_c' => 2.6,
                'kdr_k' => 2.3,
                'ktk' => 3.7,
            ],

            // BLITAR
            [
                'id' => '328106',
                'tab_kabupaten_id' => '3281',
                'nama_kecamatan' => 'SUTOJAYAN',
                'latitude' => -8.0567,
                'longitude' => 112.1234,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 197.00,
                'produktivitas' => 1.97,
                'total_produksi' => 388.09,
                'provitas' => 3.00,
                'nilai_potensi' => 6.00,
                'pot_peningkatan_judgement' => 3,
                'varietas_id' => json_encode(['Anjasmoro', 'Ijen']),
                'opt_id' => json_encode(['Non Endemik']),
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 3.6,
                'kdr_p' => 3.1,
                'kdr_c' => 2.7,
                'kdr_k' => 2.4,
                'ktk' => 3.8,
            ],
            [
                'id' => '328110',
                'tab_kabupaten_id' => '3281',
                'nama_kecamatan' => 'SELOPURO',
                'latitude' => -8.1678,
                'longitude' => 112.2345,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 197.00,
                'produktivitas' => 1.97,
                'total_produksi' => 388.09,
                'provitas' => 3.00,
                'nilai_potensi' => 6.00,
                'pot_peningkatan_judgement' => 3,
                'varietas_id' => json_encode(['Anjasmoro', 'Ijen']),
                'opt_id' => json_encode(['Non Endemik']),
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 3.7,
                'kdr_p' => 3.2,
                'kdr_c' => 2.8,
                'kdr_k' => 2.5,
                'ktk' => 3.9,
            ],

            // KEDIRI
            [
                'id' => '328201',
                'tab_kabupaten_id' => '3282',
                'nama_kecamatan' => 'MOJO',
                'latitude' => -7.8789,
                'longitude' => 112.3456,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 135.00,
                'produktivitas' => 1.35,
                'total_produksi' => 182.25,
                'provitas' => 3.00,
                'nilai_potensi' => 6.00,
                'pot_peningkatan_judgement' => 3,
                'varietas_id' => json_encode(['Dering', 'Detap']),
                'opt_id' => json_encode(['Non Endemik']),
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 3.0,
                'kdr_p' => 2.5,
                'kdr_c' => 2.2,
                'kdr_k' => 1.9,
                'ktk' => 3.2,
            ],
            [
                'id' => '328214',
                'tab_kabupaten_id' => '3282',
                'nama_kecamatan' => 'PARE',
                'latitude' => -7.7890,
                'longitude' => 112.4567,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 139.00,
                'produktivitas' => 1.39,
                'total_produksi' => 193.21,
                'provitas' => 3.00,
                'nilai_potensi' => 6.00,
                'pot_peningkatan_judgement' => 3,
                'varietas_id' => json_encode(['Dering', 'Detap']),
                'opt_id' => json_encode(['Non Endemik']),
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['Oktober', 'November', 'Desember', 'Januari', 'Februari', 'Maret', 'April']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 3.1,
                'kdr_p' => 2.6,
                'kdr_c' => 2.3,
                'kdr_k' => 2.0,
                'ktk' => 3.3,
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

        $this->command->info('🎉 More Kecamatan seeder completed!');
        $this->command->info("📊 Created: {$created} kecamatan");
        $this->command->info("🔄 Updated: {$updated} kecamatan");
        
        $totalAll = TabKecamatan::count();
        $this->command->info("📍 Total kecamatan di database: {$totalAll}");
        
        // Show breakdown by kabupaten
        $breakdown = TabKecamatan::with('kabupaten')->get()->groupBy('tab_kabupaten_id');
        foreach ($breakdown as $kabId => $kecs) {
            $kabName = $kecs->first()->kabupaten->nama_kabupaten ?? 'Unknown';
            $count = $kecs->count();
            $this->command->info("   - {$kabName}: {$count} kecamatan");
        }
    }
}
