<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabKecamatan;
use App\Models\TabKabupaten;
use App\Models\TabProvinsi;
use Illuminate\Support\Facades\DB;

class RealKecamatanDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Seeding real kecamatan data...');

        // First, ensure we have the required provinsi and kabupaten
        $this->ensureProvinsiKabupaten();

        // Real kecamatan data for Kabupaten Malang (3507) with new structure
        $kecamatanData = [
            [
                'id' => '350701',
                'tab_kabupaten_id' => '3507',
                'nama_kecamatan' => 'Donomulyo',
                'latitude' => -8.2435,
                'longitude' => 112.4419,
                'ip_lahan' => 3.2,
                'kdr_p' => 2.5,
                'kdr_c' => 2.1,
                'kdr_k' => 1.8,
                'ktk' => 2.9,
                'jenis_komoditas' => 'kedelai',
                'varietas_id' => json_encode([]),
                'provitas' => 3.2,
                'luas_tanam' => 150.50,
                'produktivitas' => 2.5,
                'total_produksi' => 376.25,
                'pot_peningkatan_judgement' => 7,
                'nilai_potensi' => 3.5,
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['Maret', 'April']),
                'rekomendasi_waktu_tanam_kacang_tanah' => json_encode(['April', 'Mei']),
                'rekomendasi_waktu_tanam_kacang_hijau' => json_encode(['Mei', 'Juni']),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret']),
                'bulan_kering' => json_encode(['Juli', 'Agustus', 'September']),
            ],
            [
                'id' => '350702',
                'tab_kabupaten_id' => '3507',
                'nama_kecamatan' => 'Pagak',
                'latitude' => -8.1991,
                'longitude' => 112.4828,
                'ip_lahan' => 3.1,
                'kdr_p' => 2.4,
                'kdr_c' => 2.0,
                'kdr_k' => 1.7,
                'ktk' => 2.8,
                'jenis_komoditas' => 'kacang_tanah',
                'varietas_id' => json_encode([]),
                'provitas' => 2.8,
                'luas_tanam' => 120.75,
                'produktivitas' => 1.8,
                'total_produksi' => 217.35,
                'pot_peningkatan_judgement' => 6,
                'nilai_potensi' => 3.1,
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['Februari', 'Maret']),
                'rekomendasi_waktu_tanam_kacang_tanah' => json_encode(['Maret', 'April']),
                'rekomendasi_waktu_tanam_kacang_hijau' => json_encode(['April', 'Mei']),
                'bulan_hujan' => json_encode(['Desember', 'Januari', 'Februari']),
                'bulan_kering' => json_encode(['Agustus', 'September', 'Oktober']),
            ],
            [
                'id' => '350703',
                'tab_kabupaten_id' => '3507',
                'nama_kecamatan' => 'Bantur',
                'latitude' => -8.1950,
                'longitude' => 112.5604,
                'ip_lahan' => 3.5,
                'kdr_p' => 2.8,
                'kdr_c' => 2.4,
                'kdr_k' => 2.0,
                'ktk' => 3.2,
                'jenis_komoditas' => 'kacang_hijau',
                'varietas_id' => json_encode([]),
                'provitas' => 3.5,
                'luas_tanam' => 95.25,
                'produktivitas' => 1.4,
                'total_produksi' => 133.35,
                'pot_peningkatan_judgement' => 8,
                'nilai_potensi' => 3.8,
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['April', 'Mei']),
                'rekomendasi_waktu_tanam_kacang_tanah' => json_encode(['Mei', 'Juni']),
                'rekomendasi_waktu_tanam_kacang_hijau' => json_encode(['Juni', 'Juli']),
                'bulan_hujan' => json_encode(['Februari', 'Maret', 'April']),
                'bulan_kering' => json_encode(['September', 'Oktober', 'November']),
            ],
            [
                'id' => '350704',
                'tab_kabupaten_id' => '3507',
                'nama_kecamatan' => 'Gedangan',
                'latitude' => -8.2435,
                'longitude' => 112.6074,
                'ip_lahan' => 3.0,
                'kdr_p' => 2.2,
                'kdr_c' => 1.9,
                'kdr_k' => 1.6,
                'ktk' => 2.7,
                'jenis_komoditas' => 'kedelai',
                'varietas_id' => json_encode([]),
                'provitas' => 3.0,
                'luas_tanam' => 180.00,
                'produktivitas' => 2.2,
                'total_produksi' => 396.00,
                'pot_peningkatan_judgement' => 5,
                'nilai_potensi' => 2.9,
                'rekomendasi_waktu_tanam_kedelai' => json_encode(['Januari', 'Februari']),
                'rekomendasi_waktu_tanam_kacang_tanah' => json_encode(['Maret', 'April']),
                'rekomendasi_waktu_tanam_kacang_hijau' => json_encode(['Mei', 'Juni']),
                'bulan_hujan' => json_encode(['Desember', 'Januari', 'Februari']),
                'bulan_kering' => json_encode(['Juli', 'Agustus', 'September']),
            ],
        ];

        // Insert or update kecamatan data
        foreach ($kecamatanData as $data) {
            $existing = TabKecamatan::find($data['id']);
            
            if ($existing) {
                $existing->update($data);
                $this->command->info("✅ Updated: {$data['nama_kecamatan']} ({$data['id']})");
            } else {
                TabKecamatan::create($data);
                $this->command->info("✅ Created: {$data['nama_kecamatan']} ({$data['id']})");
            }
        }

        // Data komoditas sudah terintegrasi dalam struktur tabel baru

        $this->command->info('🎉 Real kecamatan data seeding completed!');
        
        // Show summary
        $totalKecamatan = TabKecamatan::where('tab_kabupaten_id', '3507')->count();
        $this->command->info("📊 Kabupaten Malang now has {$totalKecamatan} kecamatan");
    }

    private function ensureProvinsiKabupaten(): void
    {
        // Ensure Jawa Timur exists
        $provinsi = TabProvinsi::firstOrCreate(
            ['id' => '35'],
            [
                'nama_provinsi' => 'Jawa Timur',
                'kode_provinsi' => '35'
            ]
        );

        // Ensure Kabupaten Malang exists
        $kabupaten = TabKabupaten::firstOrCreate(
            ['id' => '3507'],
            [
                'tab_provinsi_id' => '35',
                'nama_kabupaten' => 'Malang',
                'kode_kabupaten' => '3507'
            ]
        );

        $this->command->info("✅ Ensured Provinsi: {$provinsi->nama_provinsi}");
        $this->command->info("✅ Ensured Kabupaten: {$kabupaten->nama_kabupaten}");
    }

}
