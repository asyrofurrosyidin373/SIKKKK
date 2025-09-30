<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanBlitarKediriMalangSeeder extends Seeder
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
            if (empty($text) || strtolower($text) === 'non' || $text === '-' || stripos($text, 'non endemik') !== false) return json_encode([]);
            
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

        // Data gabungan Blitar, Kediri, dan Malang (hanya yang memiliki data produktivitas)
        $allData = [
            // BLITAR - hanya yang memiliki data produktivitas
            ['3281', '03', 'PANGGUNGREJO', 1.42, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen', -8.1890, 112.1890],
            ['3281', '04', 'WATES', 1.42, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen', -8.2567, 112.2567],
            ['3281', '05', 'BINANGUN', 1.42, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen', -8.3234, 112.3234],
            ['3281', '06', 'SUTOJAYAN', 1.97, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen', -8.1234, 112.2890],
            ['3281', '09', 'TALUN', 1.60, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen', -8.2890, 112.3567],
            ['3281', '10', 'SELOPURO', 1.97, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen', -8.3567, 112.4234],
            ['3281', '17', 'NGLEGOK', 1.70, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen', -8.2123, 112.6123],
            ['3281', '21', 'WONODADI', 1.97, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen', -8.1890, 112.3456],

            // KEDIRI - hanya yang memiliki data produktivitas
            ['3282', '01', 'MOJO', 1.35, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap', -7.8567, 112.0234],
            ['3282', '08', 'NGANCAR', 1.39, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap', -7.9234, 112.1234],
            ['3282', '14', 'PARE', 1.39, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap', -7.7567, 112.1890],
            ['3282', '16', 'KUNJANG', 1.39, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap', -7.6123, 112.3123],
            ['3282', '17', 'PLEMAHAN', 1.27, 0.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap', -7.7456, 112.3789],
            ['3282', '19', 'PAPAR', 1.25, 0.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap', -7.8789, 112.5123],
            ['3282', '21', 'KAYEN KIDUL', 1.39, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap', -7.7123, 112.4890],
            ['3282', '22', 'GAMPENGREJO', 1.39, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap', -7.6456, 112.5456],
            ['3282', '23', 'NGASEM', 1.00, 0.00, '', 'Oktober - April (7 Bulan)', 'Dering dan Detap', -7.5789, 112.6123],
            ['3282', '24', 'BANYAKAN', 1.39, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap', -7.8456, 112.6789],
            ['3282', '25', 'GROGOL', 1.39, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap', -7.9123, 112.7456],
            ['3282', '26', 'TAROKAN', 1.38, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap', -7.7890, 112.8123],

            // MALANG - sample data yang memiliki produktivitas
            ['3283', '01', 'DONOMULYO', 1.70, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '', 'Anjasmoro dan Grobogan', -8.2567, 112.4567],
            ['3283', '02', 'KALIPARE', 1.40, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '', 'Anjasmoro dan Grobogan', -8.1890, 112.5234],
            ['3283', '03', 'PAGAK', 1.90, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan', -8.3234, 112.5890],
            ['3283', '04', 'BANTUR', 1.40, 2.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan', -8.2890, 112.6567],
            ['3283', '05', 'GEDANGAN', 1.40, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan', -8.3567, 112.7234],
            ['3283', '09', 'PONCOKUSUMO', 1.90, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan', -7.9456, 112.6890],
            ['3283', '13', 'PAGELARAN', 1.40, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan', -8.1234, 112.7890],
            ['3283', '15', 'SUMBER PUCUNG', 1.90, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan', -8.0567, 112.8567],
            ['3283', '16', 'KROMENGAN', 1.00, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan', -8.2123, 112.9234],
            ['3283', '17', 'NGAJUM', 1.40, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan', -8.1567, 112.9890],
            ['3283', '18', 'WONOSARI', 1.40, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan', -8.0890, 113.0567],
            ['3283', '21', 'TAJINAN', 1.40, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan', -8.3890, 112.8234],
            ['3283', '22', 'TUMPANG', 1.90, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan', -7.9123, 112.7567],
            ['3283', '27', 'KARANGPLOSO', 1.40, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan', -7.8456, 112.5890],
        ];

        $kecamatanData = [];

        // Process data
        foreach ($allData as $row) {
            // Skip data dengan produktivitas 0.00 dan provitas 0.00
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
        
        $this->command->info('Data kecamatan Blitar, Kediri, dan Malang berhasil diinsert: ' . count($kecamatanData) . ' records');
    }
}
