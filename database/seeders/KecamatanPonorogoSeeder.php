<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KecamatanPonorogoSeeder extends Seeder
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

        // Data Kecamatan Ponorogo
        $ponorogoData = [
            ['3277', '02', 'SLAHUNG', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo', -7.5678, 111.2456],
            ['3277', '04', 'SAMBIT', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo', -7.6234, 111.3567],
            ['3277', '05', 'SAWOO', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo', -7.7345, 111.4123],
            ['3277', '08', 'PULUNG', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo', -7.8456, 111.2890],
            ['3277', '09', 'MLARAK', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo', -7.9123, 111.4567],
            ['3277', '10', 'SIMAN', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo, Dering2 dan Devon1', -7.7678, 111.3234],
            ['3277', '11', 'JETIS', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo', -7.6789, 111.5123],
            ['3277', '12', 'BALONG', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo', -7.8234, 111.3890],
            ['3277', '13', 'KAUMAN', 1.20, 1.00, 'Ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo', -7.8567, 111.4234],
            ['3277', '14', 'JAMBON', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo', -7.7234, 111.5567],
            ['3277', '15', 'BADEGAN', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo', -7.8890, 111.4890],
            ['3277', '16', 'SAMPUNG', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo', -7.9567, 111.3567],
            ['3277', '20', 'JENANGAN', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo', -7.8123, 111.6123],
        ];

        $kecamatanData = [];

        // Process data untuk Ponorogo
        foreach ($ponorogoData as $row) {
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
        
        $this->command->info('Data kecamatan Ponorogo berhasil diinsert: ' . count($kecamatanData) . ' records');
    }
}
