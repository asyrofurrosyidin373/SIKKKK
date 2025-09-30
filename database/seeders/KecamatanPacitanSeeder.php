<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanPacitanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Helper function to parse planting season from text
        $parsePlantingSeason = function($text) {
            if (empty($text)) return json_encode([]);
            
            $seasons = [];
            if (stripos($text, 'november') !== false || stripos($text, 'nov') !== false) $seasons[] = 'November';
            if (stripos($text, 'december') !== false || stripos($text, 'des') !== false) $seasons[] = 'Desember';
            if (stripos($text, 'januari') !== false || stripos($text, 'jan') !== false) $seasons[] = 'Januari';
            if (stripos($text, 'februari') !== false || stripos($text, 'feb') !== false) $seasons[] = 'Februari';
            if (stripos($text, 'maret') !== false || stripos($text, 'mar') !== false) $seasons[] = 'Maret';
            if (stripos($text, 'april') !== false || stripos($text, 'apr') !== false) $seasons[] = 'April';
            if (stripos($text, 'mei') !== false) $seasons[] = 'Mei';
            if (stripos($text, 'juni') !== false || stripos($text, 'jun') !== false) $seasons[] = 'Juni';
            if (stripos($text, 'juli') !== false || stripos($text, 'jul') !== false) $seasons[] = 'Juli';
            if (stripos($text, 'agustus') !== false || stripos($text, 'agt') !== false) $seasons[] = 'Agustus';
            if (stripos($text, 'september') !== false || stripos($text, 'sep') !== false) $seasons[] = 'September';
            if (stripos($text, 'oktober') !== false || stripos($text, 'okt') !== false) $seasons[] = 'Oktober';
            
            return json_encode($seasons);
        };

        // Helper function to parse OPT
        $parseOPT = function($text) {
            if (empty($text) || strtolower($text) === 'non' || $text === '-') return json_encode([]);
            
            $opts = [];
            if (stripos($text, 'ulat grayak') !== false) $opts[] = 'Ulat Grayak';
            if (stripos($text, 'kepik hijau') !== false) $opts[] = 'Kepik Hijau';
            if (stripos($text, 'kepik coklat') !== false) $opts[] = 'Kepik Coklat';
            if (stripos($text, 'penggerek polong') !== false) $opts[] = 'Penggerek Polong';
            if (stripos($text, 'pengisap polong') !== false) $opts[] = 'Pengisap Polong';
            if (stripos($text, 'kutu kebul') !== false) $opts[] = 'Kutu Kebul';
            if (stripos($text, 'tikus') !== false) $opts[] = 'Tikus';
            if (stripos($text, 'tungau merah') !== false) $opts[] = 'Tungau Merah';
            if (stripos($text, 'penggulung daun') !== false) $opts[] = 'Penggulung Daun';
            if (stripos($text, 'lalat bibit') !== false) $opts[] = 'Lalat Bibit';
            if (stripos($text, 'walang sangit') !== false) $opts[] = 'Walang Sangit';
            if (stripos($text, 'karat daun') !== false) $opts[] = 'Karat Daun';
            if (stripos($text, 'belalang') !== false) $opts[] = 'Belalang';
            if (stripos($text, 'ulat jengkal') !== false) $opts[] = 'Ulat Jengkal';
            if (stripos($text, 'wereng') !== false) $opts[] = 'Wereng';
            if (stripos($text, 'jamur') !== false) $opts[] = 'Jamur';
            if (stripos($text, 'bercak daun') !== false) $opts[] = 'Bercak Daun';
            if (stripos($text, 'cercospora') !== false) $opts[] = 'Cercospora';
            
            return json_encode($opts);
        };

        // Helper function to parse varieties
        $parseVarieties = function($text) {
            if (empty($text) || $text === '-') return json_encode([]);
            
            $varieties = [];
            $text = str_replace([',', ' dan ', ' & '], '|', $text);
            $parts = explode('|', $text);
            
            foreach ($parts as $part) {
                $part = trim($part);
                if (!empty($part)) {
                    $varieties[] = $part;
                }
            }
            
            return json_encode($varieties);
        };

        // Data Kecamatan Pacitan
        $pacitanData = [
            ['3276', '01', 'DONOROJO', 1.43, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro', -7.8945, 110.8542],
            ['3276', '02', 'PUNUNG', 1.42, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro', -7.9156, 110.9745],
            ['3276', '03', 'PRINGKUKU', 1.42, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro', -8.0234, 110.9123],
            ['3276', '04', 'PACITAN', 1.97, 3.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro', -8.2044, 111.0935],
            ['3276', '05', 'KEBONAGUNG', 0.00, 3.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro', -8.1234, 111.0567],
            ['3276', '06', 'ARJOSARI', 1.85, 3.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro', -7.8967, 111.0123],
            ['3276', '07', 'NAWANGAN', 0.00, 3.00, '-', 'November-Maret', 'Lokal', -7.8123, 111.1234],
            ['3276', '08', 'BANDAR', 1.42, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro', -7.9567, 111.1567],
            ['3276', '09', 'TEGALOMBO', 0.00, 1.00, '-', 'November-Maret', 'Lokal', -7.8789, 111.2123],
            ['3276', '10', 'TULAKAN', 1.45, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro', -8.0567, 111.1890],
            ['3276', '11', 'NGADIROJO', 1.61, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro', -7.7890, 111.0890],
            ['3276', '12', 'SUDIMORO', 1.46, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro', -8.1567, 111.1456],
        ];

        $kecamatanData = [];

        // Process data untuk Pacitan
        foreach ($pacitanData as $row) {
            $kecamatanData[] = [
                'id' => $row[0] . $row[1], // Gabungan kode kabupaten + kode kecamatan
                'tab_kabupaten_id' => $row[0],
                'nama_kecamatan' => $row[2],
                'jenis_komoditas' => 'kedelai',
                'produktivitas' => $row[3],
                'provitas' => $row[4],
                'opt_id' => $parseOPT($row[5]),
                'rekomendasi_waktu_tanam_kedelai' => $parsePlantingSeason($row[6]),
                'varietas_id' => $parseVarieties($row[7]),
                'latitude' => $row[8],
                'longitude' => $row[9],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert data ke database
        DB::table('tab_kecamatan')->insert($kecamatanData);
        
        $this->command->info('Data kecamatan Pacitan berhasil diinsert: ' . count($kecamatanData) . ' records');
    }
}
