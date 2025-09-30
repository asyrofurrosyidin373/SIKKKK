<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanTulungagungSeeder extends Seeder
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

        // Data Kecamatan Tulungagung
        $tulungagungData = [
            ['3279', '01', 'BESUKI', 1.90, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4', -8.4567, 111.8567],
            ['3279', '02', 'BANDUNG', 1.80, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4', -8.3890, 111.9234],
            ['3279', '03', 'PAKEL', 1.94, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4', -8.2567, 111.9890],
            ['3279', '04', 'CAMPUR DARAT', 0.00, 0.00, '', '', '', -8.1234, 111.9567],
            ['3279', '05', 'TANGGUNG GUNUNG', 0.00, 0.00, '', '', '', -8.0890, 112.0123],
            ['3279', '06', 'KALIDAWIR', 2.00, 2.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – Maret', 'Dering1, Detam2, Detam 4', -8.0567, 111.9123],
            ['3279', '07', 'PUCANG LABAN', 0.00, 0.00, '', '', '', -7.9890, 111.8567],
            ['3279', '08', 'REJOTANGAN', 1.48, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4', -8.0234, 111.8234],
            ['3279', '09', 'NGUNUT', 1.36, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4', -7.9567, 111.8890],
            ['3279', '10', 'SUMBERGEMPOL', 0.00, 0.00, '', '', '', -7.8890, 111.9456],
            ['3279', '11', 'BOYOLANGU', 1.38, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4', -8.1567, 111.8567],
            ['3279', '12', 'TULUNGAGUNG', 0.00, 0.00, '', '', '', -8.0658, 111.9026],
            ['3279', '13', 'KEDUNGWARU', 0.00, 0.00, '', '', '', -8.0456, 111.9567],
            ['3279', '14', 'NGANTRU', 0.00, 0.00, '', '', '', -8.0123, 111.9234],
            ['3279', '15', 'KARANGREJO', 0.00, 0.00, '', '', '', -8.0890, 111.8890],
            ['3279', '16', 'KAUMAN', 0.00, 0.00, '', '', '', -7.9567, 111.8234],
            ['3279', '17', 'GONDANG', 1.88, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4', -8.2890, 111.7567],
            ['3279', '18', 'PAGER WOJO', 0.00, 0.00, '', '', '', -8.3567, 111.8123],
            ['3279', '19', 'SENDANG', 0.00, 0.00, '', '', '', -8.4234, 111.7890],
        ];

        $kecamatanData = [];

        // Process data untuk Tulungagung
        foreach ($tulungagungData as $row) {
            // Skip data dengan produktivitas 0.00 dan data kosong
            if ($row[3] == 0.00 && $row[4] == 0.00) {
                continue;
            }

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
        if (!empty($kecamatanData)) {
            DB::table('tab_kecamatan')->insert($kecamatanData);
        }
        
        $this->command->info('Data kecamatan Tulungagung berhasil diinsert: ' . count($kecamatanData) . ' records');
    }
}
