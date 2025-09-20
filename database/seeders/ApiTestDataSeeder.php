<?php

// database/seeders/ApiTestDataSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabProvinsi;
use App\Models\TabKabupaten;
use App\Models\TabKecamatan;
use App\Models\TabBulan;
use App\Models\KomKedelai;
use App\Models\OrgPenTan;
use App\Models\VarietasKedelai;
use App\Models\Gejala;
use App\Models\Pengendalian;
use App\Models\Tanaman;

class ApiTestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create sample data for API testing
        
        // Bulan data
        if (TabBulan::count() === 0) {
            $bulan = [
                ['nama_bulan' => 'Januari', 'angka_bulan' => 1],
                ['nama_bulan' => 'Februari', 'angka_bulan' => 2],
                ['nama_bulan' => 'Maret', 'angka_bulan' => 3],
                ['nama_bulan' => 'April', 'angka_bulan' => 4],
                ['nama_bulan' => 'Mei', 'angka_bulan' => 5],
                ['nama_bulan' => 'Juni', 'angka_bulan' => 6],
                ['nama_bulan' => 'Juli', 'angka_bulan' => 7],
                ['nama_bulan' => 'Agustus', 'angka_bulan' => 8],
                ['nama_bulan' => 'September', 'angka_bulan' => 9],
                ['nama_bulan' => 'Oktober', 'angka_bulan' => 10],
                ['nama_bulan' => 'November', 'angka_bulan' => 11],
                ['nama_bulan' => 'Desember', 'angka_bulan' => 12],
            ];
            
            foreach ($bulan as $b) {
                TabBulan::create($b);
            }
        }

        // Tanaman data
        if (Tanaman::count() === 0) {
            $tanaman = ['Kedelai', 'Kacang Tanah', 'Kacang Hijau'];
            foreach ($tanaman as $t) {
                Tanaman::create(['nama_tanaman' => $t]);
            }
        }

        // Sample Provinsi
        if (TabProvinsi::count() === 0) {
            TabProvinsi::create([
                'id' => '35',
                'nama_provinsi' => 'Jawa Timur',
                'latitude' => -7.5360639,
                'longitude' => 112.2384017
            ]);
        }

        // Sample Kabupaten  
        if (TabKabupaten::count() === 0) {
            TabKabupaten::create([
                'id' => '3510',
                'tab_provinsi_id' => '35',
                'nama_kabupaten' => 'Jombang',
                'latitude' => -7.5467,
                'longitude' => 112.2384
            ]);
        }

        // Sample OPT
        if (OrgPenTan::count() === 0) {
            OrgPenTan::create([
                'nama_opt' => 'Aphid (Kutu Daun)',
                'jenis' => 'Hama',
                'gambar' => null
            ]);

            OrgPenTan::create([
                'nama_opt' => 'Bercak Daun Coklat',
                'jenis' => 'Penyakit',
                'gambar' => null
            ]);
        }

        // Sample Gejala
        if (Gejala::count() === 0) {
            Gejala::create([
                'deskripsi' => 'Daun menguning dan mengkerut',
                'bagian_tanaman' => 'daun'
            ]);

            Gejala::create([
                'deskripsi' => 'Bercak coklat pada permukaan daun',
                'bagian_tanaman' => 'daun'
            ]);
        }

        // Sample Pengendalian
        if (Pengendalian::count() === 0) {
            Pengendalian::create([
                'jenis' => 'Kimiawi',
                'deskripsi' => 'Semprot dengan insektisida berbahan aktif imidakloprid'
            ]);

            Pengendalian::create([
                'jenis' => 'Kultur teknis',
                'deskripsi' => 'Lakukan rotasi tanaman dan sanitasi lahan'
            ]);
        }
    }
}

