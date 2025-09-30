<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanTrenggalekSeeder extends Seeder
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
            if (stripos($text, 'ulat grayak') !== false || stripos($text, 'grayak') !== false) $opts[] = 'Ulat Grayak';
            if (stripos($text, 'kepik hijau') !== false) $opts[] = 'Kepik Hijau';
            if (stripos($text, 'kepik coklat') !== false) $opts[] = 'Kepik Coklat';
            if (stripos($text, 'penggerek polong') !== false || stripos($text, 'penggerek') !== false) $opts[] = 'Penggerek Polong';
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

        // Data Kecamatan Trenggalek
        $trenggalekData = [
            ['3278', '01', 'PANGGUL', 1.50, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1', -8.3456, 111.4567],
            ['3278', '02', 'MUNJUNGAN', 1.46, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1', -8.2789, 111.5234],
            ['3278', '03', 'WATULIMO', 0.00, 3.00, '', 'Jan-April dan Okt-Des', '', -8.2123, 111.4890],
            ['3278', '04', 'KAMPAK', 1.49, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1', -8.1890, 111.5567],
            ['3278', '05', 'DONGKO', 1.62, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1', -8.0567, 111.6123],
            ['3278', '06', 'PULE', 0.00, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1', -8.1234, 111.6890],
            ['3278', '07', 'KARANGAN', 1.66, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Anjasmoro, Detap 1', -8.0234, 111.7456],
            ['3278', '08', 'SURUH', 0.00, 3.00, '', 'Jan-April dan Okt-Des', '', -7.9567, 111.6567],
            ['3278', '09', 'GANDUSARI', 1.66, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1', -8.0890, 111.7123],
            ['3278', '10', 'DURENAN', 1.56, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1', -8.1567, 111.7890],
            ['3278', '11', 'POGALAN', 1.59, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1', -8.2234, 111.8456],
            ['3278', '12', 'TRENGGALEK', 1.59, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1', -8.0567, 111.7567],
            ['3278', '13', 'TUGU', 1.40, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1', -8.1890, 111.8123],
            ['3278', '14', 'BENDUNGAN', 0.00, 2.00, '', 'Jan-April dan Okt-Des', '', -7.9890, 111.8890],
        ];

        $kecamatanData = [];

        // Process data untuk Trenggalek
        foreach ($trenggalekData as $row) {
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
        
        $this->command->info('Data kecamatan Trenggalek berhasil diinsert: ' . count($kecamatanData) . ' records');
    }
}
