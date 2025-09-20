<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinsiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $provinces = [
            ['id' => '11', 'nama_provinsi' => 'Aceh', 'latitude' => 5.5583, 'longitude' => 95.3195],
            ['id' => '12', 'nama_provinsi' => 'Sumatera Utara', 'latitude' => 3.5952, 'longitude' => 98.6775],
            ['id' => '13', 'nama_provinsi' => 'Sumatera Barat', 'latitude' => -0.9458, 'longitude' => 100.4140],
            ['id' => '14', 'nama_provinsi' => 'Riau', 'latitude' => 0.5104, 'longitude' => 101.4468],
            ['id' => '15', 'nama_provinsi' => 'Jambi', 'latitude' => -1.6116, 'longitude' => 103.6131],
            ['id' => '16', 'nama_provinsi' => 'Sumatera Selatan', 'latitude' => -2.9909, 'longitude' => 104.7566],
            ['id' => '17', 'nama_provinsi' => 'Bengkulu', 'latitude' => -3.7925, 'longitude' => 102.2608],
            ['id' => '18', 'nama_provinsi' => 'Lampung', 'latitude' => -5.4500, 'longitude' => 105.2667],
            ['id' => '19', 'nama_provinsi' => 'Kepulauan Bangka Belitung', 'latitude' => -2.0341, 'longitude' => 106.1054],
            ['id' => '21', 'nama_provinsi' => 'Kepulauan Riau', 'latitude' => 1.0500, 'longitude' => 104.0333],
            ['id' => '31', 'nama_provinsi' => 'DKI Jakarta', 'latitude' => -6.1754, 'longitude' => 106.8272],
            ['id' => '32', 'nama_provinsi' => 'Jawa Barat', 'latitude' => -6.9175, 'longitude' => 107.6191],
            ['id' => '33', 'nama_provinsi' => 'Jawa Tengah', 'latitude' => -6.9667, 'longitude' => 110.4167],
            ['id' => '34', 'nama_provinsi' => 'DI Yogyakarta', 'latitude' => -7.7956, 'longitude' => 110.3695],
            ['id' => '35', 'nama_provinsi' => 'Jawa Timur', 'latitude' => -7.2500, 'longitude' => 112.7500],
            ['id' => '36', 'nama_provinsi' => 'Banten', 'latitude' => -6.1200, 'longitude' => 106.1300],
            ['id' => '51', 'nama_provinsi' => 'Bali', 'latitude' => -8.6500, 'longitude' => 115.2167],
            ['id' => '52', 'nama_provinsi' => 'Nusa Tenggara Barat', 'latitude' => -8.5833, 'longitude' => 116.1167],
            ['id' => '53', 'nama_provinsi' => 'Nusa Tenggara Timur', 'latitude' => -10.1788, 'longitude' => 123.5989],
            ['id' => '61', 'nama_provinsi' => 'Kalimantan Barat', 'latitude' => -0.0249, 'longitude' => 109.3406],
            ['id' => '62', 'nama_provinsi' => 'Kalimantan Tengah', 'latitude' => -1.4500, 'longitude' => 113.8167],
            ['id' => '63', 'nama_provinsi' => 'Kalimantan Selatan', 'latitude' => -3.3167, 'longitude' => 114.5936],
            ['id' => '64', 'nama_provinsi' => 'Kalimantan Timur', 'latitude' => -0.5000, 'longitude' => 117.1500],
            ['id' => '65', 'nama_provinsi' => 'Kalimantan Utara', 'latitude' => 2.8333, 'longitude' => 117.6333],
            ['id' => '71', 'nama_provinsi' => 'Sulawesi Utara', 'latitude' => 1.4748, 'longitude' => 124.8421],
            ['id' => '72', 'nama_provinsi' => 'Sulawesi Tengah', 'latitude' => -0.8906, 'longitude' => 119.8707],
            ['id' => '73', 'nama_provinsi' => 'Sulawesi Selatan', 'latitude' => -5.1477, 'longitude' => 119.4327],
            ['id' => '74', 'nama_provinsi' => 'Sulawesi Tenggara', 'latitude' => -3.9912, 'longitude' => 122.5186],
            ['id' => '75', 'nama_provinsi' => 'Gorontalo', 'latitude' => 0.5400, 'longitude' => 123.0500],
            ['id' => '76', 'nama_provinsi' => 'Sulawesi Barat', 'latitude' => -2.5500, 'longitude' => 118.9167],
            ['id' => '81', 'nama_provinsi' => 'Maluku', 'latitude' => -3.6500, 'longitude' => 128.1750],
            ['id' => '82', 'nama_provinsi' => 'Maluku Utara', 'latitude' => 0.7833, 'longitude' => 127.3833],
            ['id' => '91', 'nama_provinsi' => 'Papua', 'latitude' => -2.5333, 'longitude' => 140.7000],
            ['id' => '92', 'nama_provinsi' => 'Papua Barat', 'latitude' => -0.8870, 'longitude' => 134.0792],
            ['id' => '93', 'nama_provinsi' => 'Papua Selatan', 'latitude' => -8.4116, 'longitude' => 140.4079],
            ['id' => '94', 'nama_provinsi' => 'Papua Tengah', 'latitude' => -4.0175, 'longitude' => 136.2155],
            ['id' => '95', 'nama_provinsi' => 'Papua Pegunungan', 'latitude' => -4.5422, 'longitude' => 138.6475],
            ['id' => '96', 'nama_provinsi' => 'Papua Barat Daya', 'latitude' => -1.2500, 'longitude' => 131.0000],
        ];

        DB::table('tab_provinsi')->insert($provinces);
    }
}
