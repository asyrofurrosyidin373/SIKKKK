<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabKecamatan;
use App\Models\TabKabupaten;
use App\Models\TabProvinsi;

class SimpleKecamatanSeeder extends Seeder
{
    /**
     * Simple seeder untuk test data kecamatan dengan struktur baru
     */
    public function run()
    {
        $this->command->info('🌱 Starting simple kecamatan seeder...');

        // Pastikan ada provinsi Jawa Timur
        $jatim = TabProvinsi::firstOrCreate(
            ['nama_provinsi' => 'Jawa Timur'],
            ['id' => 35, 'nama_provinsi' => 'Jawa Timur']
        );

        // Pastikan ada kabupaten Malang
        $malang = TabKabupaten::firstOrCreate(
            ['nama_kabupaten' => 'Malang', 'tab_provinsi_id' => $jatim->id],
            ['id' => 3507, 'nama_kabupaten' => 'Malang', 'tab_provinsi_id' => $jatim->id]
        );

        // Data kecamatan sederhana dengan ID yang benar (6 digit)
        $kecamatanData = [
            [
                'id' => '350701',
                'nama_kecamatan' => 'Donomulyo',
                'latitude' => -8.2435,
                'longitude' => 112.4419,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 150.50,
                'produktivitas' => 2.5,
                'total_produksi' => 376.25,
                'ip_lahan' => 3.2,
                'kdr_p' => 2.5,
                'kdr_c' => 2.1,
                'kdr_k' => 1.8,
                'ktk' => 2.9,
            ],
            [
                'id' => '350702',
                'nama_kecamatan' => 'Bantur',
                'latitude' => -8.3567,
                'longitude' => 112.5234,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => 200.75,
                'produktivitas' => 2.8,
                'total_produksi' => 562.10,
                'ip_lahan' => 3.5,
                'kdr_p' => 2.8,
                'kdr_c' => 2.4,
                'kdr_k' => 2.0,
                'ktk' => 3.2,
            ],
            [
                'id' => '350703',
                'nama_kecamatan' => 'Gedangan',
                'latitude' => -8.1234,
                'longitude' => 112.6789,
                'jenis_komoditas' => 'kacang_tanah',
                'luas_tanam' => 175.25,
                'produktivitas' => 2.3,
                'total_produksi' => 403.08,
                'ip_lahan' => 2.8,
                'kdr_p' => 2.2,
                'kdr_c' => 1.9,
                'kdr_k' => 1.6,
                'ktk' => 2.5,
            ]
        ];

        foreach ($kecamatanData as $data) {
            $data['tab_kabupaten_id'] = $malang->id;
            
            $existing = TabKecamatan::find($data['id']);

            if ($existing) {
                $existing->update($data);
                $this->command->info("✅ Updated: {$data['nama_kecamatan']}");
            } else {
                TabKecamatan::create($data);
                $this->command->info("✅ Created: {$data['nama_kecamatan']}");
            }
        }

        $total = TabKecamatan::where('tab_kabupaten_id', $malang->id)->count();
        $this->command->info("🎉 Simple seeder completed! Total kecamatan in Malang: {$total}");
    }
}
