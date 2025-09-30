<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabKecamatan;
use App\Models\TabKabupaten;
use App\Models\TabProvinsi;
use App\Models\VarietasKedelai;

class KedelaiPotensiProduksiSeeder extends Seeder
{
    /**
     * Seeder untuk data kecamatan dengan estimasi potensi produksi kedelai
     * Berdasarkan data BPSI (Balai Penelitian Serealia Indonesia)
     */
    public function run()
    {
        // Mapping untuk pot_peningkatan_judgement (string ke integer)
        $judgementMapping = [
            'Rendah' => 1,
            'Sedang' => 2,
            'Tinggi' => 3,
            'Sangat Tinggi' => 4
        ];
        
        // Data kecamatan dengan potensi produksi kedelai
        $kecamatanData = [
            // Jawa Timur - Kabupaten Malang
            [
                'nama_kecamatan' => 'Donomulyo',
                'kabupaten' => 'Malang',
                'provinsi' => 'Jawa Timur',
                'latitude' => -8.2435,
                'longitude' => 112.4419,
                'jenis_komoditas' => 'kedelai',
                'varietas_id' => [1, 2], // Grobogan, Anjasmoro
                'luas_tanam' => 150.50,
                'produktivitas' => 2.5,
                'total_produksi' => 376.25,
                'provitas' => 2.8,
                'ip_lahan' => 3.2,
                'kdr_p' => 2.5,
                'kdr_c' => 2.1,
                'kdr_k' => 1.8,
                'ktk' => 2.9,
                'pot_peningkatan_judgement' => 'Tinggi',
                'nilai_potensi' => 85.5,
                'rekomendasi_waktu_tanam_kedelai' => ['Maret', 'April', 'Mei'],
                'bulan_hujan' => ['Januari', 'Februari', 'Maret', 'November', 'Desember'],
                'bulan_kering' => ['Juni', 'Juli', 'Agustus', 'September']
            ],
            [
                'nama_kecamatan' => 'Bantur',
                'kabupaten' => 'Malang',
                'provinsi' => 'Jawa Timur',
                'latitude' => -8.3567,
                'longitude' => 112.5234,
                'jenis_komoditas' => 'kedelai',
                'varietas_id' => [1, 3], // Grobogan, Argomulyo
                'luas_tanam' => 200.75,
                'produktivitas' => 2.8,
                'total_produksi' => 562.10,
                'provitas' => 3.0,
                'ip_lahan' => 3.5,
                'kdr_p' => 2.8,
                'kdr_c' => 2.4,
                'kdr_k' => 2.0,
                'ktk' => 3.2,
                'pot_peningkatan_judgement' => 'Sangat Tinggi',
                'nilai_potensi' => 92.3,
                'rekomendasi_waktu_tanam_kedelai' => ['Maret', 'April'],
                'bulan_hujan' => ['Januari', 'Februari', 'Maret', 'November', 'Desember'],
                'bulan_kering' => ['Juni', 'Juli', 'Agustus', 'September']
            ],
            [
                'nama_kecamatan' => 'Gedangan',
                'kabupaten' => 'Malang',
                'provinsi' => 'Jawa Timur',
                'latitude' => -8.1234,
                'longitude' => 112.6789,
                'jenis_komoditas' => 'kedelai',
                'varietas_id' => [2, 4], // Anjasmoro, Demas 1
                'luas_tanam' => 175.25,
                'produktivitas' => 2.3,
                'total_produksi' => 403.08,
                'provitas' => 2.6,
                'ip_lahan' => 2.8,
                'kdr_p' => 2.2,
                'kdr_c' => 1.9,
                'kdr_k' => 1.6,
                'ktk' => 2.5,
                'pot_peningkatan_judgement' => 'Sedang',
                'nilai_potensi' => 78.2,
                'rekomendasi_waktu_tanam_kedelai' => ['April', 'Mei'],
                'bulan_hujan' => ['Januari', 'Februari', 'Maret', 'November', 'Desember'],
                'bulan_kering' => ['Juni', 'Juli', 'Agustus', 'September']
            ],

            // Jawa Tengah - Kabupaten Grobogan
            [
                'nama_kecamatan' => 'Purwodadi',
                'kabupaten' => 'Grobogan',
                'provinsi' => 'Jawa Tengah',
                'latitude' => -7.0854,
                'longitude' => 110.9015,
                'jenis_komoditas' => 'kedelai',
                'varietas_id' => [1, 5], // Grobogan, Burangrang
                'luas_tanam' => 320.80,
                'produktivitas' => 3.2,
                'total_produksi' => 1026.56,
                'provitas' => 3.5,
                'ip_lahan' => 4.1,
                'kdr_p' => 3.2,
                'kdr_c' => 2.8,
                'kdr_k' => 2.4,
                'ktk' => 3.8,
                'pot_peningkatan_judgement' => 'Sangat Tinggi',
                'nilai_potensi' => 95.7,
                'rekomendasi_waktu_tanam_kedelai' => ['Februari', 'Maret', 'April'],
                'bulan_hujan' => ['Januari', 'Februari', 'Maret', 'November', 'Desember'],
                'bulan_kering' => ['Juni', 'Juli', 'Agustus', 'September']
            ],
            [
                'nama_kecamatan' => 'Geyer',
                'kabupaten' => 'Grobogan',
                'provinsi' => 'Jawa Tengah',
                'latitude' => -7.2345,
                'longitude' => 110.8765,
                'jenis_komoditas' => 'kedelai',
                'varietas_id' => [1, 2, 3], // Grobogan, Anjasmoro, Argomulyo
                'luas_tanam' => 285.40,
                'produktivitas' => 3.0,
                'total_produksi' => 856.20,
                'provitas' => 3.3,
                'ip_lahan' => 3.9,
                'kdr_p' => 3.0,
                'kdr_c' => 2.6,
                'kdr_k' => 2.2,
                'ktk' => 3.5,
                'pot_peningkatan_judgement' => 'Tinggi',
                'nilai_potensi' => 88.4,
                'rekomendasi_waktu_tanam_kedelai' => ['Maret', 'April'],
                'bulan_hujan' => ['Januari', 'Februari', 'Maret', 'November', 'Desember'],
                'bulan_kering' => ['Juni', 'Juli', 'Agustus', 'September']
            ],

            // Jawa Barat - Kabupaten Garut
            [
                'nama_kecamatan' => 'Tarogong Kidul',
                'kabupaten' => 'Garut',
                'provinsi' => 'Jawa Barat',
                'latitude' => -7.2147,
                'longitude' => 107.9077,
                'jenis_komoditas' => 'kedelai',
                'varietas_id' => [3, 4], // Argomulyo, Demas 1
                'luas_tanam' => 165.30,
                'produktivitas' => 2.4,
                'total_produksi' => 396.72,
                'provitas' => 2.7,
                'ip_lahan' => 3.0,
                'kdr_p' => 2.4,
                'kdr_c' => 2.0,
                'kdr_k' => 1.7,
                'ktk' => 2.8,
                'pot_peningkatan_judgement' => 'Sedang',
                'nilai_potensi' => 75.8,
                'rekomendasi_waktu_tanam_kedelai' => ['Maret', 'April', 'Mei'],
                'bulan_hujan' => ['Januari', 'Februari', 'Maret', 'November', 'Desember'],
                'bulan_kering' => ['Juni', 'Juli', 'Agustus', 'September']
            ],
            [
                'nama_kecamatan' => 'Leles',
                'kabupaten' => 'Garut',
                'provinsi' => 'Jawa Barat',
                'latitude' => -7.1234,
                'longitude' => 107.8765,
                'jenis_komoditas' => 'kedelai',
                'varietas_id' => [2, 5], // Anjasmoro, Burangrang
                'luas_tanam' => 140.60,
                'produktivitas' => 2.2,
                'total_produksi' => 309.32,
                'provitas' => 2.5,
                'ip_lahan' => 2.7,
                'kdr_p' => 2.1,
                'kdr_c' => 1.8,
                'kdr_k' => 1.5,
                'ktk' => 2.4,
                'pot_peningkatan_judgement' => 'Sedang',
                'nilai_potensi' => 72.1,
                'rekomendasi_waktu_tanam_kedelai' => ['April', 'Mei'],
                'bulan_hujan' => ['Januari', 'Februari', 'Maret', 'November', 'Desember'],
                'bulan_kering' => ['Juni', 'Juli', 'Agustus', 'September']
            ],

            // Jawa Tengah - Kabupaten Wonogiri
            [
                'nama_kecamatan' => 'Wonogiri',
                'kabupaten' => 'Wonogiri',
                'provinsi' => 'Jawa Tengah',
                'latitude' => -7.8147,
                'longitude' => 110.9264,
                'jenis_komoditas' => 'kedelai',
                'varietas_id' => [1, 4, 5], // Grobogan, Demas 1, Burangrang
                'luas_tanam' => 195.75,
                'produktivitas' => 2.6,
                'total_produksi' => 508.95,
                'provitas' => 2.9,
                'ip_lahan' => 3.3,
                'kdr_p' => 2.6,
                'kdr_c' => 2.2,
                'kdr_k' => 1.9,
                'ktk' => 3.0,
                'pot_peningkatan_judgement' => 'Tinggi',
                'nilai_potensi' => 82.6,
                'rekomendasi_waktu_tanam_kedelai' => ['Maret', 'April'],
                'bulan_hujan' => ['Januari', 'Februari', 'Maret', 'November', 'Desember'],
                'bulan_kering' => ['Juni', 'Juli', 'Agustus', 'September']
            ],

            // Yogyakarta - Kabupaten Gunungkidul
            [
                'nama_kecamatan' => 'Wonosari',
                'kabupaten' => 'Gunungkidul',
                'provinsi' => 'DI Yogyakarta',
                'latitude' => -7.9647,
                'longitude' => 110.6022,
                'jenis_komoditas' => 'kedelai',
                'varietas_id' => [2, 3], // Anjasmoro, Argomulyo
                'luas_tanam' => 125.40,
                'produktivitas' => 2.1,
                'total_produksi' => 263.34,
                'provitas' => 2.4,
                'ip_lahan' => 2.5,
                'kdr_p' => 1.9,
                'kdr_c' => 1.6,
                'kdr_k' => 1.3,
                'ktk' => 2.2,
                'pot_peningkatan_judgement' => 'Rendah',
                'nilai_potensi' => 65.3,
                'rekomendasi_waktu_tanam_kedelai' => ['April', 'Mei', 'Juni'],
                'bulan_hujan' => ['Januari', 'Februari', 'Maret', 'November', 'Desember'],
                'bulan_kering' => ['Juni', 'Juli', 'Agustus', 'September']
            ],

            // Jawa Timur - Kabupaten Lamongan
            [
                'nama_kecamatan' => 'Lamongan',
                'kabupaten' => 'Lamongan',
                'provinsi' => 'Jawa Timur',
                'latitude' => -7.1167,
                'longitude' => 112.4167,
                'jenis_komoditas' => 'kedelai',
                'varietas_id' => [1, 2, 5], // Grobogan, Anjasmoro, Burangrang
                'luas_tanam' => 245.80,
                'produktivitas' => 2.9,
                'total_produksi' => 712.82,
                'provitas' => 3.2,
                'ip_lahan' => 3.7,
                'kdr_p' => 2.9,
                'kdr_c' => 2.5,
                'kdr_k' => 2.1,
                'ktk' => 3.4,
                'pot_peningkatan_judgement' => 'Tinggi',
                'nilai_potensi' => 87.9,
                'rekomendasi_waktu_tanam_kedelai' => ['Februari', 'Maret', 'April'],
                'bulan_hujan' => ['Januari', 'Februari', 'Maret', 'November', 'Desember'],
                'bulan_kering' => ['Juni', 'Juli', 'Agustus', 'September']
            ]
        ];

        foreach ($kecamatanData as $data) {
            // Cari provinsi
            $provinsi = TabProvinsi::where('nama_provinsi', $data['provinsi'])->first();
            if (!$provinsi) {
                $provinsi = TabProvinsi::create([
                    'nama_provinsi' => $data['provinsi']
                ]);
            }

            // Cari kabupaten
            $kabupaten = TabKabupaten::where('nama_kabupaten', $data['kabupaten'])
                ->where('tab_provinsi_id', $provinsi->id)
                ->first();
            if (!$kabupaten) {
                // Generate ID untuk kabupaten baru
                $lastKabupaten = TabKabupaten::orderBy('id', 'desc')->first();
                $newId = $lastKabupaten ? $lastKabupaten->id + 1 : 1001;
                
                $kabupaten = TabKabupaten::create([
                    'id' => $newId,
                    'nama_kabupaten' => $data['kabupaten'],
                    'tab_provinsi_id' => $provinsi->id
                ]);
            }

            // Cek apakah kecamatan sudah ada
            $existingKecamatan = TabKecamatan::where('nama_kecamatan', $data['nama_kecamatan'])
                ->where('tab_kabupaten_id', $kabupaten->id)
                ->first();

            if (!$existingKecamatan) {
                // Generate unique ID untuk kecamatan (6 digit string)
                $lastKecamatan = TabKecamatan::orderBy('id', 'desc')->first();
                $newKecamatanId = $lastKecamatan ? 
                    str_pad((int)$lastKecamatan->id + 1, 6, '0', STR_PAD_LEFT) : 
                    '100001';
                
                TabKecamatan::create([
                    'id' => $newKecamatanId,
                    'nama_kecamatan' => $data['nama_kecamatan'],
                    'tab_kabupaten_id' => $kabupaten->id,
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'jenis_komoditas' => $data['jenis_komoditas'],
                    'varietas_id' => json_encode($data['varietas_id']),
                    'luas_tanam' => $data['luas_tanam'],
                    'produktivitas' => $data['produktivitas'],
                    'total_produksi' => $data['total_produksi'],
                    'provitas' => $data['provitas'],
                    'ip_lahan' => $data['ip_lahan'],
                    'kdr_p' => $data['kdr_p'],
                    'kdr_c' => $data['kdr_c'],
                    'kdr_k' => $data['kdr_k'],
                    'ktk' => $data['ktk'],
                    'pot_peningkatan_judgement' => $judgementMapping[$data['pot_peningkatan_judgement']] ?? 2,
                    'nilai_potensi' => $data['nilai_potensi'],
                    'rekomendasi_waktu_tanam_kedelai' => json_encode($data['rekomendasi_waktu_tanam_kedelai']),
                    'bulan_hujan' => json_encode($data['bulan_hujan']),
                    'bulan_kering' => json_encode($data['bulan_kering']),
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                $this->command->info("✅ Created kecamatan: {$data['nama_kecamatan']}, {$data['kabupaten']}, {$data['provinsi']}");
            } else {
                // Update data yang sudah ada dengan struktur baru
                $existingKecamatan->update([
                    'latitude' => $data['latitude'],
                    'longitude' => $data['longitude'],
                    'jenis_komoditas' => $data['jenis_komoditas'],
                    'varietas_id' => json_encode($data['varietas_id']),
                    'luas_tanam' => $data['luas_tanam'],
                    'produktivitas' => $data['produktivitas'],
                    'total_produksi' => $data['total_produksi'],
                    'provitas' => $data['provitas'],
                    'ip_lahan' => $data['ip_lahan'],
                    'kdr_p' => $data['kdr_p'],
                    'kdr_c' => $data['kdr_c'],
                    'kdr_k' => $data['kdr_k'],
                    'ktk' => $data['ktk'],
                    'pot_peningkatan_judgement' => $judgementMapping[$data['pot_peningkatan_judgement']] ?? 2,
                    'nilai_potensi' => $data['nilai_potensi'],
                    'rekomendasi_waktu_tanam_kedelai' => json_encode($data['rekomendasi_waktu_tanam_kedelai']),
                    'bulan_hujan' => json_encode($data['bulan_hujan']),
                    'bulan_kering' => json_encode($data['bulan_kering']),
                    'updated_at' => now()
                ]);

                $this->command->info("🔄 Updated kecamatan: {$data['nama_kecamatan']}, {$data['kabupaten']}, {$data['provinsi']}");
            }
        }

        $this->command->info("🎉 Seeder KedelaiPotensiProduksiSeeder completed!");
        $this->command->info("📊 Total data: " . count($kecamatanData) . " kecamatan dengan data potensi produksi kedelai");
    }
}
