<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabProvinsi;
use App\Models\TabKabupaten;
use App\Models\TabKecamatan;
use App\Models\KomKedelai;
use App\Models\KomKacangTanah;
use App\Models\KomKacangHijau;
use Illuminate\Support\Facades\DB;

class MinimalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating minimal data for testing...');

        // Create sample provinces if none exist
        if (TabProvinsi::count() == 0) {
            $provinces = [
                ['id' => '11', 'nama_provinsi' => 'Aceh', 'kode_provinsi' => '11'],
                ['id' => '12', 'nama_provinsi' => 'Sumatera Utara', 'kode_provinsi' => '12'],
                ['id' => '32', 'nama_provinsi' => 'Jawa Barat', 'kode_provinsi' => '32'],
                ['id' => '33', 'nama_provinsi' => 'Jawa Tengah', 'kode_provinsi' => '33'],
                ['id' => '35', 'nama_provinsi' => 'Jawa Timur', 'kode_provinsi' => '35'],
            ];

            foreach ($provinces as $province) {
                TabProvinsi::create($province);
            }
            $this->command->info('Created ' . count($provinces) . ' provinces');
        }

        // Create sample kabupaten if none exist
        if (TabKabupaten::count() == 0) {
            $regencies = [
                ['id' => '1101', 'tab_provinsi_id' => '11', 'nama_kabupaten' => 'Simeulue', 'kode_kabupaten' => '1101'],
                ['id' => '1102', 'tab_provinsi_id' => '11', 'nama_kabupaten' => 'Aceh Singkil', 'kode_kabupaten' => '1102'],
                ['id' => '1201', 'tab_provinsi_id' => '12', 'nama_kabupaten' => 'Nias', 'kode_kabupaten' => '1201'],
                ['id' => '1202', 'tab_provinsi_id' => '12', 'nama_kabupaten' => 'Mandailing Natal', 'kode_kabupaten' => '1202'],
                ['id' => '3201', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Bogor', 'kode_kabupaten' => '3201'],
                ['id' => '3202', 'tab_provinsi_id' => '32', 'nama_kabupaten' => 'Sukabumi', 'kode_kabupaten' => '3202'],
                ['id' => '3301', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Cilacap', 'kode_kabupaten' => '3301'],
                ['id' => '3302', 'tab_provinsi_id' => '33', 'nama_kabupaten' => 'Banyumas', 'kode_kabupaten' => '3302'],
                ['id' => '3501', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Pacitan', 'kode_kabupaten' => '3501'],
                ['id' => '3502', 'tab_provinsi_id' => '35', 'nama_kabupaten' => 'Ponorogo', 'kode_kabupaten' => '3502'],
            ];

            foreach ($regencies as $regency) {
                TabKabupaten::create($regency);
            }
            $this->command->info('Created ' . count($regencies) . ' kabupaten');
        }

        // Create sample kecamatan if none exist
        if (TabKecamatan::count() == 0) {
            $districts = [
                // Aceh - Simeulue
                ['id' => '110101', 'tab_kabupaten_id' => '1101', 'nama_kecamatan' => 'Teupah Selatan', 'latitude' => 2.6190, 'longitude' => 96.0907],
                ['id' => '110102', 'tab_kabupaten_id' => '1101', 'nama_kecamatan' => 'Simeulue Timur', 'latitude' => 2.6500, 'longitude' => 96.1200],
                
                // Aceh - Aceh Singkil
                ['id' => '110201', 'tab_kabupaten_id' => '1102', 'nama_kecamatan' => 'Pulau Banyak', 'latitude' => 2.1500, 'longitude' => 97.2000],
                ['id' => '110202', 'tab_kabupaten_id' => '1102', 'nama_kecamatan' => 'Singkil', 'latitude' => 2.3000, 'longitude' => 97.7900],
                
                // Sumatera Utara - Nias
                ['id' => '120101', 'tab_kabupaten_id' => '1201', 'nama_kecamatan' => 'Idano Gawo', 'latitude' => 1.0500, 'longitude' => 97.5000],
                ['id' => '120102', 'tab_kabupaten_id' => '1201', 'nama_kecamatan' => 'Botomuzoi', 'latitude' => 1.1000, 'longitude' => 97.5500],
                
                // Jawa Barat - Bogor
                ['id' => '320101', 'tab_kabupaten_id' => '3201', 'nama_kecamatan' => 'Nanggung', 'latitude' => -6.5500, 'longitude' => 106.7000],
                ['id' => '320102', 'tab_kabupaten_id' => '3201', 'nama_kecamatan' => 'Leuwiliang', 'latitude' => -6.5800, 'longitude' => 106.6500],
                
                // Jawa Barat - Sukabumi
                ['id' => '320201', 'tab_kabupaten_id' => '3202', 'nama_kecamatan' => 'Palabuhanratu', 'latitude' => -7.0200, 'longitude' => 106.5500],
                ['id' => '320202', 'tab_kabupaten_id' => '3202', 'nama_kecamatan' => 'Simpenan', 'latitude' => -7.0500, 'longitude' => 106.6000],
                
                // Jawa Tengah - Cilacap
                ['id' => '330101', 'tab_kabupaten_id' => '3301', 'nama_kecamatan' => 'Dayeuhluhur', 'latitude' => -7.3200, 'longitude' => 108.6200],
                ['id' => '330102', 'tab_kabupaten_id' => '3301', 'nama_kecamatan' => 'Wanareja', 'latitude' => -7.3500, 'longitude' => 108.7000],
                
                // Jawa Timur - Pacitan
                ['id' => '350101', 'tab_kabupaten_id' => '3501', 'nama_kecamatan' => 'Donorojo', 'latitude' => -8.1500, 'longitude' => 111.0500],
                ['id' => '350102', 'tab_kabupaten_id' => '3501', 'nama_kecamatan' => 'Punung', 'latitude' => -8.1200, 'longitude' => 111.1000],
            ];

            foreach ($districts as $district) {
                TabKecamatan::create($district);
            }
            $this->command->info('Created ' . count($districts) . ' kecamatan');
        }

        // Create sample komoditas if none exist
        if (KomKedelai::count() == 0) {
            for ($i = 1; $i <= 5; $i++) {
                KomKedelai::create([
                    'provitas' => rand(15, 35) / 10,
                    'luas_tanam' => rand(50, 500) / 10,
                    'produktivitas' => rand(15, 35) / 10,
                    'total_produksi' => rand(100, 1000) / 10,
                    'nilai_potensi' => rand(70, 95) / 10,
                ]);
            }
            $this->command->info('Created 5 KomKedelai records');
        }

        if (KomKacangTanah::count() == 0) {
            for ($i = 1; $i <= 5; $i++) {
                KomKacangTanah::create([
                    'provitas' => rand(10, 25) / 10,
                    'luas_tanam' => rand(30, 300) / 10,
                    'produktivitas' => rand(10, 25) / 10,
                    'total_produksi' => rand(50, 500) / 10,
                    'nilai_potensi' => rand(60, 85) / 10,
                ]);
            }
            $this->command->info('Created 5 KomKacangTanah records');
        }

        if (KomKacangHijau::count() == 0) {
            for ($i = 1; $i <= 5; $i++) {
                KomKacangHijau::create([
                    'provitas' => rand(8, 20) / 10,
                    'luas_tanam' => rand(20, 200) / 10,
                    'produktivitas' => rand(8, 20) / 10,
                    'total_produksi' => rand(30, 300) / 10,
                    'nilai_potensi' => rand(50, 80) / 10,
                ]);
            }
            $this->command->info('Created 5 KomKacangHijau records');
        }

        // Link some kecamatan to komoditas
        $kecamatanList = TabKecamatan::all();
        $kedelaiList = KomKedelai::all();
        $kacangTanahList = KomKacangTanah::all();
        $kacangHijauList = KomKacangHijau::all();

        foreach ($kecamatanList as $kecamatan) {
            $updates = [];
            
            // 70% chance to have kedelai
            if (rand(1, 100) <= 70 && $kedelaiList->count() > 0) {
                $updates['kom_kedelai_id'] = $kedelaiList->random()->id;
            }
            
            // 60% chance to have kacang tanah
            if (rand(1, 100) <= 60 && $kacangTanahList->count() > 0) {
                $updates['kom_kacang_tanah_id'] = $kacangTanahList->random()->id;
            }
            
            // 50% chance to have kacang hijau
            if (rand(1, 100) <= 50 && $kacangHijauList->count() > 0) {
                $updates['kom_kacang_hijau_id'] = $kacangHijauList->random()->id;
            }

            if (!empty($updates)) {
                $kecamatan->update($updates);
            }
        }

        $this->command->info('Linked kecamatan to komoditas');
        $this->command->info('✅ Minimal data seeding completed!');
    }
}
