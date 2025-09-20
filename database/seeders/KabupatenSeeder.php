<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class KabupatenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $data = [
            // Jawa
            [
                'provinsi_id' => '31', 'provinsi_nama' => 'DKI Jakarta',
                'kabupatens' => [
                    ['kode' => '71', 'nama' => 'Kota Jakarta Pusat', 'lat' => -6.1754, 'lng' => 106.8272],
                    ['kode' => '72', 'nama' => 'Kota Jakarta Utara', 'lat' => -6.1214, 'lng' => 106.9012],
                    ['kode' => '73', 'nama' => 'Kota Jakarta Barat', 'lat' => -6.1672, 'lng' => 106.7646],
                    ['kode' => '74', 'nama' => 'Kota Jakarta Selatan', 'lat' => -6.2297, 'lng' => 106.8272],
                    ['kode' => '75', 'nama' => 'Kota Jakarta Timur', 'lat' => -6.2253, 'lng' => 106.8732],
                    ['kode' => '01', 'nama' => 'Kabupaten Kepulauan Seribu', 'lat' => -5.6946, 'lng' => 106.5683]
                ]
            ],
            [
                'provinsi_id' => '32', 'provinsi_nama' => 'Jawa Barat',
                'kabupatens' => [
                    ['kode' => '01', 'nama' => 'Kabupaten Bogor', 'lat' => -6.5950, 'lng' => 106.8166],
                    ['kode' => '02', 'nama' => 'Kabupaten Sukabumi', 'lat' => -6.9175, 'lng' => 107.0375],
                    ['kode' => '03', 'nama' => 'Kabupaten Cianjur', 'lat' => -6.8167, 'lng' => 107.1333],
                    ['kode' => '04', 'nama' => 'Kabupaten Bandung', 'lat' => -7.0000, 'lng' => 107.5000],
                    ['kode' => '05', 'nama' => 'Kabupaten Garut', 'lat' => -7.2000, 'lng' => 107.9000],
                    ['kode' => '06', 'nama' => 'Kabupaten Tasikmalaya', 'lat' => -7.3167, 'lng' => 108.0667],
                    ['kode' => '07', 'nama' => 'Kabupaten Ciamis', 'lat' => -7.3333, 'lng' => 108.3167],
                    ['kode' => '08', 'nama' => 'Kabupaten Kuningan', 'lat' => -6.9833, 'lng' => 108.4833],
                    ['kode' => '09', 'nama' => 'Kabupaten Cirebon', 'lat' => -6.7167, 'lng' => 108.5667],
                    ['kode' => '10', 'nama' => 'Kabupaten Majalengka', 'lat' => -6.8333, 'lng' => 108.2333],
                    ['kode' => '11', 'nama' => 'Kabupaten Sumedang', 'lat' => -6.8500, 'lng' => 107.9333],
                    ['kode' => '12', 'nama' => 'Kabupaten Indramayu', 'lat' => -6.3333, 'lng' => 108.3167],
                    ['kode' => '13', 'nama' => 'Kabupaten Subang', 'lat' => -6.5667, 'lng' => 107.7500],
                    ['kode' => '14', 'nama' => 'Kabupaten Purwakarta', 'lat' => -6.5500, 'lng' => 107.4500],
                    ['kode' => '15', 'nama' => 'Kabupaten Karawang', 'lat' => -6.3167, 'lng' => 107.3167],
                    ['kode' => '16', 'nama' => 'Kabupaten Bekasi', 'lat' => -6.2259, 'lng' => 106.9942],
                    ['kode' => '17', 'nama' => 'Kabupaten Bandung Barat', 'lat' => -6.8333, 'lng' => 107.4167],
                    ['kode' => '18', 'nama' => 'Kabupaten Pangandaran', 'lat' => -7.6833, 'lng' => 108.5833],
                    ['kode' => '71', 'nama' => 'Kota Bogor', 'lat' => -6.5950, 'lng' => 106.8166],
                    ['kode' => '72', 'nama' => 'Kota Sukabumi', 'lat' => -6.9200, 'lng' => 106.9200],
                    ['kode' => '73', 'nama' => 'Kota Bandung', 'lat' => -6.9175, 'lng' => 107.6191],
                    ['kode' => '74', 'nama' => 'Kota Cirebon', 'lat' => -6.7167, 'lng' => 108.5667],
                    ['kode' => '75', 'nama' => 'Kota Bekasi', 'lat' => -6.2343, 'lng' => 106.9959],
                    ['kode' => '76', 'nama' => 'Kota Depok', 'lat' => -6.3900, 'lng' => 106.8100],
                    ['kode' => '77', 'nama' => 'Kota Cimahi', 'lat' => -6.8833, 'lng' => 107.5500],
                    ['kode' => '78', 'nama' => 'Kota Tasikmalaya', 'lat' => -7.3200, 'lng' => 108.2200],
                    ['kode' => '79', 'nama' => 'Kota Banjar', 'lat' => -7.3667, 'lng' => 108.5333]
                ]
            ],
            [
                'provinsi_id' => '33', 'provinsi_nama' => 'Jawa Tengah',
                'kabupatens' => [
                    ['kode' => '01', 'nama' => 'Kabupaten Cilacap', 'lat' => -7.6970, 'lng' => 108.9828],
                    ['kode' => '02', 'nama' => 'Kabupaten Banyumas', 'lat' => -7.4200, 'lng' => 109.2300],
                    ['kode' => '03', 'nama' => 'Kabupaten Purbalingga', 'lat' => -7.3833, 'lng' => 109.3500],
                    ['kode' => '04', 'nama' => 'Kabupaten Banjarnegara', 'lat' => -7.4167, 'lng' => 109.7333],
                    ['kode' => '05', 'nama' => 'Kabupaten Kebumen', 'lat' => -7.6667, 'lng' => 109.6833],
                    ['kode' => '06', 'nama' => 'Kabupaten Purworejo', 'lat' => -7.7167, 'lng' => 109.9167],
                    ['kode' => '07', 'nama' => 'Kabupaten Wonosobo', 'lat' => -7.3833, 'lng' => 109.9333],
                    ['kode' => '08', 'nama' => 'Kabupaten Magelang', 'lat' => -7.4667, 'lng' => 110.1500],
                    ['kode' => '09', 'nama' => 'Kabupaten Boyolali', 'lat' => -7.5333, 'lng' => 110.6000],
                    ['kode' => '10', 'nama' => 'Kabupaten Klaten', 'lat' => -7.7000, 'lng' => 110.6000],
                    ['kode' => '11', 'nama' => 'Kabupaten Sukoharjo', 'lat' => -7.6833, 'lng' => 110.8500],
                    ['kode' => '12', 'nama' => 'Kabupaten Wonogiri', 'lat' => -7.9000, 'lng' => 110.9000],
                    ['kode' => '13', 'nama' => 'Kabupaten Karanganyar', 'lat' => -7.6000, 'lng' => 111.0000],
                    ['kode' => '14', 'nama' => 'Kabupaten Sragen', 'lat' => -7.4000, 'lng' => 111.0167],
                    ['kode' => '15', 'nama' => 'Kabupaten Grobogan', 'lat' => -7.1000, 'lng' => 110.8667],
                    ['kode' => '16', 'nama' => 'Kabupaten Blora', 'lat' => -6.9833, 'lng' => 111.4167],
                    ['kode' => '17', 'nama' => 'Kabupaten Rembang', 'lat' => -6.7000, 'lng' => 111.3333],
                    ['kode' => '18', 'nama' => 'Kabupaten Pati', 'lat' => -6.7667, 'lng' => 111.0333],
                    ['kode' => '19', 'nama' => 'Kabupaten Kudus', 'lat' => -6.8000, 'lng' => 110.8333],
                    ['kode' => '20', 'nama' => 'Kabupaten Jepara', 'lat' => -6.6000, 'lng' => 110.7000],
                    ['kode' => '21', 'nama' => 'Kabupaten Demak', 'lat' => -6.8500, 'lng' => 110.6333],
                    ['kode' => '22', 'nama' => 'Kabupaten Semarang', 'lat' => -7.1000, 'lng' => 110.4000],
                    ['kode' => '23', 'nama' => 'Kabupaten Temanggung', 'lat' => -7.3167, 'lng' => 110.2000],
                    ['kode' => '24', 'nama' => 'Kabupaten Kendal', 'lat' => -6.9333, 'lng' => 110.1500],
                    ['kode' => '25', 'nama' => 'Kabupaten Batang', 'lat' => -6.9333, 'lng' => 109.8167],
                    ['kode' => '26', 'nama' => 'Kabupaten Pekalongan', 'lat' => -7.0000, 'lng' => 109.5833],
                    ['kode' => '27', 'nama' => 'Kabupaten Pemalang', 'lat' => -6.9000, 'lng' => 109.4000],
                    ['kode' => '28', 'nama' => 'Kabupaten Tegal', 'lat' => -6.9000, 'lng' => 109.1000],
                    ['kode' => '29', 'nama' => 'Kabupaten Brebes', 'lat' => -6.8333, 'lng' => 108.8333],
                    ['kode' => '71', 'nama' => 'Kota Magelang', 'lat' => -7.4764, 'lng' => 110.2178],
                    ['kode' => '72', 'nama' => 'Kota Surakarta', 'lat' => -7.5500, 'lng' => 110.8333],
                    ['kode' => '73', 'nama' => 'Kota Salatiga', 'lat' => -7.3333, 'lng' => 110.5000],
                    ['kode' => '74', 'nama' => 'Kota Semarang', 'lat' => -6.9667, 'lng' => 110.4333],
                    ['kode' => '75', 'nama' => 'Kota Pekalongan', 'lat' => -6.8833, 'lng' => 109.6667],
                    ['kode' => '76', 'nama' => 'Kota Tegal', 'lat' => -6.8833, 'lng' => 109.1333]
                ]
            ],
            [
                'provinsi_id' => '34', 'provinsi_nama' => 'DI Yogyakarta',
                'kabupatens' => [
                    ['kode' => '01', 'nama' => 'Kabupaten Kulon Progo', 'lat' => -7.8942, 'lng' => 110.1589],
                    ['kode' => '02', 'nama' => 'Kabupaten Bantul', 'lat' => -7.8833, 'lng' => 110.3500],
                    ['kode' => '03', 'nama' => 'Kabupaten Gunungkidul', 'lat' => -7.9833, 'lng' => 110.6667],
                    ['kode' => '04', 'nama' => 'Kabupaten Sleman', 'lat' => -7.7000, 'lng' => 110.4000],
                    ['kode' => '71', 'nama' => 'Kota Yogyakarta', 'lat' => -7.7956, 'lng' => 110.3695]
                ]
            ],
            [
                'provinsi_id' => '35', 'provinsi_nama' => 'Jawa Timur',
                'kabupatens' => [
                    ['kode' => '01', 'nama' => 'Kabupaten Pacitan', 'lat' => -8.1833, 'lng' => 111.1000],
                    ['kode' => '02', 'nama' => 'Kabupaten Ponorogo', 'lat' => -7.9000, 'lng' => 111.4500],
                    ['kode' => '03', 'nama' => 'Kabupaten Trenggalek', 'lat' => -8.0833, 'lng' => 111.6667],
                    ['kode' => '04', 'nama' => 'Kabupaten Tulungagung', 'lat' => -8.0667, 'lng' => 111.9000],
                    ['kode' => '05', 'nama' => 'Kabupaten Blitar', 'lat' => -8.1000, 'lng' => 112.2000],
                    ['kode' => '06', 'nama' => 'Kabupaten Kediri', 'lat' => -7.8500, 'lng' => 112.0500],
                    ['kode' => '07', 'nama' => 'Kabupaten Malang', 'lat' => -8.0000, 'lng' => 112.6000],
                    ['kode' => '08', 'nama' => 'Kabupaten Lumajang', 'lat' => -8.1333, 'lng' => 113.1167],
                    ['kode' => '09', 'nama' => 'Kabupaten Jember', 'lat' => -8.1500, 'lng' => 113.6833],
                    ['kode' => '10', 'nama' => 'Kabupaten Banyuwangi', 'lat' => -8.2167, 'lng' => 114.3667],
                    ['kode' => '11', 'nama' => 'Kabupaten Bondowoso', 'lat' => -7.9167, 'lng' => 113.9167],
                    ['kode' => '12', 'nama' => 'Kabupaten Situbondo', 'lat' => -7.7167, 'lng' => 114.0000],
                    ['kode' => '13', 'nama' => 'Kabupaten Probolinggo', 'lat' => -7.7500, 'lng' => 113.3167],
                    ['kode' => '14', 'nama' => 'Kabupaten Pasuruan', 'lat' => -7.7000, 'lng' => 112.9000],
                    ['kode' => '15', 'nama' => 'Kabupaten Sidoarjo', 'lat' => -7.4500, 'lng' => 112.7167],
                    ['kode' => '16', 'nama' => 'Kabupaten Mojokerto', 'lat' => -7.5000, 'lng' => 112.4333],
                    ['kode' => '17', 'nama' => 'Kabupaten Jombang', 'lat' => -7.5500, 'lng' => 112.2333],
                    ['kode' => '18', 'nama' => 'Kabupaten Nganjuk', 'lat' => -7.6000, 'lng' => 111.9000],
                    ['kode' => '19', 'nama' => 'Kabupaten Madiun', 'lat' => -7.6333, 'lng' => 111.5667],
                    ['kode' => '20', 'nama' => 'Kabupaten Magetan', 'lat' => -7.6500, 'lng' => 111.3667],
                    ['kode' => '21', 'nama' => 'Kabupaten Ngawi', 'lat' => -7.4000, 'lng' => 111.3667],
                    ['kode' => '22', 'nama' => 'Kabupaten Bojonegoro', 'lat' => -7.1500, 'lng' => 111.8833],
                    ['kode' => '23', 'nama' => 'Kabupaten Tuban', 'lat' => -6.9000, 'lng' => 112.0667],
                    ['kode' => '24', 'nama' => 'Kabupaten Lamongan', 'lat' => -7.1167, 'lng' => 112.4167],
                    ['kode' => '25', 'nama' => 'Kabupaten Gresik', 'lat' => -7.1500, 'lng' => 112.6000],
                    ['kode' => '26', 'nama' => 'Kabupaten Bangkalan', 'lat' => -7.0333, 'lng' => 112.8000],
                    ['kode' => '27', 'nama' => 'Kabupaten Sampang', 'lat' => -7.1833, 'lng' => 113.2500],
                    ['kode' => '28', 'nama' => 'Kabupaten Pamekasan', 'lat' => -7.1667, 'lng' => 113.4667],
                    ['kode' => '29', 'nama' => 'Kabupaten Sumenep', 'lat' => -7.0000, 'lng' => 113.8000],
                    ['kode' => '71', 'nama' => 'Kota Kediri', 'lat' => -7.8286, 'lng' => 112.0163],
                    ['kode' => '72', 'nama' => 'Kota Blitar', 'lat' => -8.1000, 'lng' => 112.1667],
                    ['kode' => '73', 'nama' => 'Kota Malang', 'lat' => -7.9833, 'lng' => 112.6333],
                    ['kode' => '74', 'nama' => 'Kota Probolinggo', 'lat' => -7.7500, 'lng' => 113.2167],
                    ['kode' => '75', 'nama' => 'Kota Pasuruan', 'lat' => -7.6333, 'lng' => 112.9000],
                    ['kode' => '76', 'nama' => 'Kota Mojokerto', 'lat' => -7.4667, 'lng' => 112.4333],
                    ['kode' => '77', 'nama' => 'Kota Madiun', 'lat' => -7.6250, 'lng' => 111.5333],
                    ['kode' => '78', 'nama' => 'Kota Surabaya', 'lat' => -7.2500, 'lng' => 112.7500],
                    ['kode' => '79', 'nama' => 'Kota Batu', 'lat' => -7.8767, 'lng' => 112.5297]
                ]
            ],
            [
                'provinsi_id' => '36', 'provinsi_nama' => 'Banten',
                'kabupatens' => [
                    ['kode' => '01', 'nama' => 'Kabupaten Pandeglang', 'lat' => -6.3167, 'lng' => 105.8167],
                    ['kode' => '02', 'nama' => 'Kabupaten Lebak', 'lat' => -6.6667, 'lng' => 106.2833],
                    ['kode' => '03', 'nama' => 'Kabupaten Tangerang', 'lat' => -6.1833, 'lng' => 106.6167],
                    ['kode' => '04', 'nama' => 'Kabupaten Serang', 'lat' => -6.0667, 'lng' => 106.1000],
                    ['kode' => '71', 'nama' => 'Kota Tangerang', 'lat' => -6.1754, 'lng' => 106.6455],
                    ['kode' => '72', 'nama' => 'Kota Cilegon', 'lat' => -6.0000, 'lng' => 106.0167],
                    ['kode' => '73', 'nama' => 'Kota Serang', 'lat' => -6.1200, 'lng' => 106.1500],
                    ['kode' => '74', 'nama' => 'Kota Tangerang Selatan', 'lat' => -6.2900, 'lng' => 106.7000]
                ]
            ],
            // Bali
            [
                'provinsi_id' => '51', 'provinsi_nama' => 'Bali',
                'kabupatens' => [
                    ['kode' => '01', 'nama' => 'Kabupaten Jembrana', 'lat' => -8.3333, 'lng' => 114.6167],
                    ['kode' => '02', 'nama' => 'Kabupaten Tabanan', 'lat' => -8.4500, 'lng' => 115.0000],
                    ['kode' => '03', 'nama' => 'Kabupaten Badung', 'lat' => -8.6500, 'lng' => 115.1667],
                    ['kode' => '04', 'nama' => 'Kabupaten Gianyar', 'lat' => -8.5500, 'lng' => 115.3333],
                    ['kode' => '05', 'nama' => 'Kabupaten Klungkung', 'lat' => -8.5333, 'lng' => 115.4167],
                    ['kode' => '06', 'nama' => 'Kabupaten Bangli', 'lat' => -8.3333, 'lng' => 115.3500],
                    ['kode' => '07', 'nama' => 'Kabupaten Karangasem', 'lat' => -8.4000, 'lng' => 115.5833],
                    ['kode' => '08', 'nama' => 'Kabupaten Buleleng', 'lat' => -8.1167, 'lng' => 115.0833],
                    ['kode' => '71', 'nama' => 'Kota Denpasar', 'lat' => -8.6500, 'lng' => 115.2167]
                ]
            ],
            // Kalimantan
            [
                'provinsi_id' => '61', 'provinsi_nama' => 'Kalimantan Barat',
                'kabupatens' => [
                    ['kode' => '01', 'nama' => 'Kabupaten Sambas', 'lat' => 1.3667, 'lng' => 109.3000],
                    ['kode' => '02', 'nama' => 'Kabupaten Mempawah', 'lat' => -0.3167, 'lng' => 109.1167],
                    ['kode' => '03', 'nama' => 'Kabupaten Sanggau', 'lat' => 0.1667, 'lng' => 110.5833],
                    ['kode' => '04', 'nama' => 'Kabupaten Ketapang', 'lat' => -1.8333, 'lng' => 110.1667],
                    ['kode' => '05', 'nama' => 'Kabupaten Sintang', 'lat' => 0.0833, 'lng' => 111.4833],
                    ['kode' => '06', 'nama' => 'Kabupaten Kapuas Hulu', 'lat' => 0.8333, 'lng' => 112.5000],
                    ['kode' => '07', 'nama' => 'Kabupaten Bengkayang', 'lat' => 0.8333, 'lng' => 109.8333],
                    ['kode' => '08', 'nama' => 'Kabupaten Landak', 'lat' => 0.3333, 'lng' => 109.7000],
                    ['kode' => '09', 'nama' => 'Kabupaten Sekadau', 'lat' => -0.0667, 'lng' => 110.9667],
                    ['kode' => '10', 'nama' => 'Kabupaten Melawi', 'lat' => 0.0000, 'lng' => 111.5000],
                    ['kode' => '11', 'nama' => 'Kabupaten Kayong Utara', 'lat' => -1.3333, 'lng' => 109.9167],
                    ['kode' => '12', 'nama' => 'Kabupaten Kubu Raya', 'lat' => -0.1667, 'lng' => 109.3333],
                    ['kode' => '71', 'nama' => 'Kota Pontianak', 'lat' => -0.0235, 'lng' => 109.3411],
                    ['kode' => '72', 'nama' => 'Kota Singkawang', 'lat' => 0.9000, 'lng' => 108.9833]
                ]
            ],
            [
                'provinsi_id' => '62', 'provinsi_nama' => 'Kalimantan Tengah',
                'kabupatens' => [
                    ['kode' => '01', 'nama' => 'Kabupaten Kotawaringin Barat', 'lat' => -2.5167, 'lng' => 111.6667],
                    ['kode' => '02', 'nama' => 'Kabupaten Kotawaringin Timur', 'lat' => -2.2500, 'lng' => 112.9000],
                    ['kode' => '03', 'nama' => 'Kabupaten Kapuas', 'lat' => -3.0000, 'lng' => 114.4167],
                    ['kode' => '04', 'nama' => 'Kabupaten Barito Selatan', 'lat' => -2.0000, 'lng' => 114.8333],
                    ['kode' => '05', 'nama' => 'Kabupaten Barito Utara', 'lat' => -0.7500, 'lng' => 114.9167],
                    ['kode' => '06', 'nama' => 'Kabupaten Sukamara', 'lat' => -2.4333, 'lng' => 111.3333],
                    ['kode' => '07', 'nama' => 'Kabupaten Lamandau', 'lat' => -2.5167, 'lng' => 111.5833],
                    ['kode' => '08', 'nama' => 'Kabupaten Seruyan', 'lat' => -2.3333, 'lng' => 112.5000],
                    ['kode' => '09', 'nama' => 'Kabupaten Katingan', 'lat' => -1.7500, 'lng' => 113.5000],
                    ['kode' => '10', 'nama' => 'Kabupaten Pulang Pisau', 'lat' => -2.5000, 'lng' => 113.8000],
                    ['kode' => '11', 'nama' => 'Kabupaten Gunung Mas', 'lat' => -0.8333, 'lng' => 113.8333],
                    ['kode' => '12', 'nama' => 'Kabupaten Barito Timur', 'lat' => -1.7500, 'lng' => 115.0000],
                    ['kode' => '13', 'nama' => 'Kabupaten Murung Raya', 'lat' => -0.7500, 'lng' => 114.5000],
                    ['kode' => '71', 'nama' => 'Kota Palangka Raya', 'lat' => -2.2167, 'lng' => 113.9167]
                ]
            ],
            [
                'provinsi_id' => '63', 'provinsi_nama' => 'Kalimantan Selatan',
                'kabupatens' => [
                    ['kode' => '01', 'nama' => 'Kabupaten Tanah Laut', 'lat' => -3.8000, 'lng' => 114.7333],
                    ['kode' => '02', 'nama' => 'Kabupaten Kotabaru', 'lat' => -3.2000, 'lng' => 116.2000],
                    ['kode' => '03', 'nama' => 'Kabupaten Banjar', 'lat' => -3.5000, 'lng' => 115.0000],
                    ['kode' => '04', 'nama' => 'Kabupaten Barito Kuala', 'lat' => -3.2500, 'lng' => 114.5000],
                    ['kode' => '05', 'nama' => 'Kabupaten Tapin', 'lat' => -2.9000, 'lng' => 115.0000],
                    ['kode' => '06', 'nama' => 'Kabupaten Hulu Sungai Selatan', 'lat' => -2.9167, 'lng' => 115.2000],
                    ['kode' => '07', 'nama' => 'Kabupaten Hulu Sungai Tengah', 'lat' => -2.7500, 'lng' => 115.3500],
                    ['kode' => '08', 'nama' => 'Kabupaten Hulu Sungai Utara', 'lat' => -2.5000, 'lng' => 115.2000],
                    ['kode' => '09', 'nama' => 'Kabupaten Tabalong', 'lat' => -2.2500, 'lng' => 115.3333],
                    ['kode' => '10', 'nama' => 'Kabupaten Tanah Bumbu', 'lat' => -3.3333, 'lng' => 115.8333],
                    ['kode' => '11', 'nama' => 'Kabupaten Balangan', 'lat' => -2.4167, 'lng' => 115.4167],
                    ['kode' => '71', 'nama' => 'Kota Banjarmasin', 'lat' => -3.3167, 'lng' => 114.5917],
                    ['kode' => '72', 'nama' => 'Kota Banjarbaru', 'lat' => -3.4500, 'lng' => 114.8333]
                ]
            ],
            [
                'provinsi_id' => '64', 'provinsi_nama' => 'Kalimantan Timur',
                'kabupatens' => [
                    ['kode' => '01', 'nama' => 'Kabupaten Paser', 'lat' => -1.7500, 'lng' => 116.2500],
                    ['kode' => '02', 'nama' => 'Kabupaten Kutai Kartanegara', 'lat' => -0.5000, 'lng' => 116.9000],
                    ['kode' => '03', 'nama' => 'Kabupaten Berau', 'lat' => 2.0000, 'lng' => 117.5000],
                    ['kode' => '04', 'nama' => 'Kabupaten Kutai Barat', 'lat' => 0.0000, 'lng' => 115.7500],
                    ['kode' => '05', 'nama' => 'Kabupaten Kutai Timur', 'lat' => 0.5000, 'lng' => 117.0000],
                    ['kode' => '07', 'nama' => 'Kabupaten Penajam Paser Utara', 'lat' => -1.2500, 'lng' => 116.7500],
                    ['kode' => '08', 'nama' => 'Kabupaten Mahakam Ulu', 'lat' => 0.2500, 'lng' => 115.5000],
                    ['kode' => '71', 'nama' => 'Kota Balikpapan', 'lat' => -1.2370, 'lng' => 116.8529],
                    ['kode' => '72', 'nama' => 'Kota Samarinda', 'lat' => -0.4915, 'lng' => 117.1517],
                    ['kode' => '74', 'nama' => 'Kota Bontang', 'lat' => 0.1333, 'lng' => 117.5000]
                ]
            ],
            [
                'provinsi_id' => '65', 'provinsi_nama' => 'Kalimantan Utara',
                'kabupatens' => [
                    ['kode' => '01', 'nama' => 'Kabupaten Malinau', 'lat' => 3.4667, 'lng' => 116.3000],
                    ['kode' => '02', 'nama' => 'Kabupaten Bulungan', 'lat' => 2.8000, 'lng' => 117.3333],
                    ['kode' => '03', 'nama' => 'Kabupaten Tana Tidung', 'lat' => 3.5000, 'lng' => 116.5000],
                    ['kode' => '04', 'nama' => 'Kabupaten Nunukan', 'lat' => 4.0833, 'lng' => 117.6667],
                    ['kode' => '71', 'nama' => 'Kota Tarakan', 'lat' => 3.3000, 'lng' => 117.6167]
                ]
            ],
        ];

        foreach ($data as $provinsi) {
            foreach ($provinsi['kabupatens'] as $kabupaten) {
                DB::table('tab_kabupaten')->insert([
                    'id' => $provinsi['provinsi_id'] . $kabupaten['kode'],
                    'tab_provinsi_id' => $provinsi['provinsi_id'],
                    'nama_kabupaten' => $kabupaten['nama'],
                    'latitude' => $kabupaten['lat'],
                    'longitude' => $kabupaten['lng'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
