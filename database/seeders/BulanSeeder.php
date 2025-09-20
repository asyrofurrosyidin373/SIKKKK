<?php

// BulanSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabBulan;

class BulanSeeder extends Seeder
{
    public function run()
    {
        $bulans = [
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

        foreach ($bulans as $bulan) {
            TabBulan::create($bulan);
        }
    }
}