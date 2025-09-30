<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabKecamatan;
use App\Models\TabKabupaten;
use App\Models\TabProvinsi;

class KecamatanJawaTimurSeeder extends Seeder
{
    /**
     * Seeder untuk data kecamatan Jawa Timur berdasarkan data PDF
     * Kabupaten: Pacitan, Ponorogo, Trenggalek, Tulungagung, Blitar, Kediri, Malang
     */
    public function run()
    {
        $this->command->info('🌱 Starting Kecamatan Jawa Timur seeder...');

        // Helper functions
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

        $parseOPT = function($text) {
            if (empty($text) || strtolower($text) === 'non' || $text === '-') return json_encode([]);
            
            $opts = [];
            if (stripos($text, 'ulat grayak') !== false) $opts[] = 'Ulat Grayak';
            if (stripos($text, 'kepik hijau') !== false) $opts[] = 'Kepik Hijau';
            if (stripos($text, 'kepik coklat') !== false) $opts[] = 'Kepik Coklat';
            if (stripos($text, 'penggerek polong') !== false) $opts[] = 'Penggerek Polong';
            if (stripos($text, 'pengisap polong') !== false) $opts[] = 'Pengisap Polong';
            if (stripos($text, 'tikus') !== false) $opts[] = 'Tikus';
            if (stripos($text, 'penggulung daun') !== false) $opts[] = 'Penggulung Daun';
            
            return json_encode($opts);
        };

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

        // Pastikan provinsi Jawa Timur ada
        $jatim = TabProvinsi::firstOrCreate(
            ['nama_provinsi' => 'Jawa Timur'],
            ['id' => 35, 'nama_provinsi' => 'Jawa Timur']
        );

        // Mapping kabupaten
        $kabupatenMapping = [
            '3276' => ['name' => 'Pacitan', 'id' => 3276],
            '3277' => ['name' => 'Ponorogo', 'id' => 3277],
            '3278' => ['name' => 'Trenggalek', 'id' => 3278],
            '3279' => ['name' => 'Tulungagung', 'id' => 3279],
            '3281' => ['name' => 'Blitar', 'id' => 3281],
            '3282' => ['name' => 'Kediri', 'id' => 3282],
            '3283' => ['name' => 'Malang', 'id' => 3283],
        ];

        // Buat kabupaten jika belum ada
        foreach ($kabupatenMapping as $code => $kabData) {
            TabKabupaten::firstOrCreate(
                ['id' => $kabData['id']],
                [
                    'id' => $kabData['id'],
                    'nama_kabupaten' => $kabData['name'],
                    'tab_provinsi_id' => $jatim->id
                ]
            );
        }

        // Raw data dari PDF
        $rawData = [
            // PACITAN
            ['3276', '01', 'DONOROJO', 1.43, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro'],
            ['3276', '02', 'PUNUNG', 1.42, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro'],
            ['3276', '03', 'PRINGKUKU', 1.42, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro'],
            ['3276', '04', 'PACITAN', 1.97, 3.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro'],
            ['3276', '05', 'KEBONAGUNG', 0.00, 3.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro'],
            ['3276', '06', 'ARJOSARI', 1.85, 3.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro'],
            ['3276', '07', 'NAWANGAN', 0.00, 3.00, '-', 'November-Maret', 'Lokal'],
            ['3276', '08', 'BANDAR', 1.42, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro'],
            ['3276', '09', 'TEGALOMBO', 0.00, 1.00, '-', 'November-Maret', 'Lokal'],
            ['3276', '10', 'TULAKAN', 1.45, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro'],
            ['3276', '11', 'NGADIROJO', 1.61, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro'],
            ['3276', '12', 'SUDIMORO', 1.46, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro'],

            // PONOROGO
            ['3277', '02', 'SLAHUNG', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo'],
            ['3277', '04', 'SAMBIT', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo'],
            ['3277', '05', 'SAWOO', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo'],
            ['3277', '08', 'PULUNG', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo'],
            ['3277', '09', 'MLARAK', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo'],
            ['3277', '10', 'SIMAN', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo, Dering2 dan Devon1'],
            ['3277', '11', 'JETIS', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo'],
            ['3277', '12', 'BALONG', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo'],
            ['3277', '13', 'KAUMAN', 1.20, 1.00, 'Ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo'],
            ['3277', '14', 'JAMBON', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo'],
            ['3277', '15', 'BADEGAN', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo'],
            ['3277', '16', 'SAMPUNG', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo'],
            ['3277', '20', 'JENANGAN', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo'],

            // TRENGGALEK
            ['3278', '01', 'PANGGUL', 1.50, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1'],
            ['3278', '02', 'MUNJUNGAN', 1.46, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1'],
            ['3278', '04', 'KAMPAK', 1.49, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1'],
            ['3278', '05', 'DONGKO', 1.62, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1'],
            ['3278', '07', 'KARANGAN', 1.66, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Anjasmoro, Detap 1'],
            ['3278', '09', 'GANDUSARI', 1.66, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1'],
            ['3278', '10', 'DURENAN', 1.56, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1'],
            ['3278', '11', 'POGALAN', 1.59, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1'],
            ['3278', '12', 'TRENGGALEK', 1.59, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1'],
            ['3278', '13', 'TUGU', 1.40, 3.00, 'non (grayak, penggerek)', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1'],

            // TULUNGAGUNG
            ['3279', '01', 'BESUKI', 1.90, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4'],
            ['3279', '02', 'BANDUNG', 1.80, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4'],
            ['3279', '03', 'PAKEL', 1.94, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4'],
            ['3279', '06', 'KALIDAWIR', 2.00, 2.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – Maret', 'Dering1, Detam2, Detam 4'],
            ['3279', '08', 'REJOTANGAN', 1.48, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4'],
            ['3279', '09', 'NGUNUT', 1.36, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4'],
            ['3279', '11', 'BOYOLANGU', 1.38, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4'],
            ['3279', '17', 'GONDANG', 1.88, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4'],

            // BLITAR
            ['3281', '03', 'PANGGUNGREJO', 1.42, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen'],
            ['3281', '04', 'WATES', 1.42, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen'],
            ['3281', '05', 'BINANGUN', 1.42, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen'],
            ['3281', '06', 'SUTOJAYAN', 1.97, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen'],
            ['3281', '09', 'TALUN', 1.60, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen'],
            ['3281', '10', 'SELOPURO', 1.97, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen'],
            ['3281', '17', 'NGLEGOK', 1.70, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen'],
            ['3281', '21', 'WONODADI', 1.97, 3.00, 'Non Endemik', 'Oktober - April (7 bulan)', 'Anjasmoro dan Ijen'],

            // KEDIRI
            ['3282', '01', 'MOJO', 1.35, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap'],
            ['3282', '08', 'NGANCAR', 1.39, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap'],
            ['3282', '14', 'PARE', 1.39, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap'],
            ['3282', '16', 'KUNJANG', 1.39, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap'],
            ['3282', '17', 'PLEMAHAN', 1.27, 0.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap'],
            ['3282', '19', 'PAPAR', 1.25, 0.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap'],
            ['3282', '21', 'KAYEN KIDUL', 1.39, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap'],
            ['3282', '22', 'GAMPENGREJO', 1.39, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap'],
            ['3282', '23', 'NGASEM', 1.00, 0.00, '', 'Oktober - April (7 Bulan)', 'Dering dan Detap'],
            ['3282', '24', 'BANYAKAN', 1.39, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap'],
            ['3282', '25', 'GROGOL', 1.39, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap'],
            ['3282', '26', 'TAROKAN', 1.38, 3.00, 'Non Endemik', 'Oktober - April (7 Bulan)', 'Dering dan Detap'],

            // MALANG
            ['3283', '01', 'DONOMULYO', 1.70, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan'],
            ['3283', '02', 'KALIPARE', 1.40, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan'],
            ['3283', '03', 'PAGAK', 1.90, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan'],
            ['3283', '04', 'BANTUR', 1.40, 2.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan'],
            ['3283', '05', 'GEDANGAN', 1.40, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan'],
            ['3283', '09', 'PONCOKUSUMO', 1.90, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan'],
            ['3283', '13', 'PAGELARAN', 1.40, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan'],
            ['3283', '15', 'SUMBER PUCUNG', 1.90, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan'],
            ['3283', '16', 'KROMENGAN', 1.00, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan'],
            ['3283', '17', 'NGAJUM', 1.40, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan'],
            ['3283', '18', 'WONOSARI', 1.40, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan'],
            ['3283', '21', 'TAJINAN', 1.40, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan'],
            ['3283', '22', 'TUMPANG', 1.90, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan'],
            ['3283', '27', 'KARANGPLOSO', 1.40, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', '5 bulan (Nov-Maret)', 'Anjasmoro dan Grobogan'],
        ];

        $created = 0;
        $updated = 0;

        // Process data
        foreach ($rawData as $row) {
            [$kabCode, $kecCode, $kecName, $productivity, $potential, $opt, $plantingSeason, $varieties] = $row;
            
            // Skip data dengan produktivitas 0
            if ($productivity == 0.00) continue;
            
            $kecamatanId = $kabCode . str_pad($kecCode, 2, '0', STR_PAD_LEFT);
            
            // Generate sample coordinates (akan diganti dengan koordinat real)
            $baseLatitude = -7.5; // Base latitude untuk Jawa Timur
            $baseLongitude = 111.5; // Base longitude untuk Jawa Timur
            $latitude = $baseLatitude + (rand(-100, 100) / 1000);
            $longitude = $baseLongitude + (rand(-100, 100) / 1000);
            
            // Calculate production data
            $luasTanam = $productivity * 100; // Estimasi luas tanam
            $totalProduksi = $luasTanam * $productivity;
            
            $kecamatanData = [
                'id' => $kecamatanId,
                'tab_kabupaten_id' => $kabCode,
                'nama_kecamatan' => $kecName,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'jenis_komoditas' => 'kedelai',
                'luas_tanam' => $luasTanam,
                'produktivitas' => $productivity,
                'total_produksi' => $totalProduksi,
                'provitas' => $potential,
                'nilai_potensi' => min(9.99, $potential * 2), // Estimasi nilai potensi (max 9.99)
                'pot_peningkatan_judgement' => min(10, max(1, round($potential))),
                'varietas_id' => $parseVarieties($varieties),
                'opt_id' => $parseOPT($opt),
                'rekomendasi_waktu_tanam_kedelai' => $parsePlantingSeason($plantingSeason),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 2.5 + (rand(0, 15) / 10), // Random IP lahan 2.5-4.0
                'kdr_p' => 2.0 + (rand(0, 10) / 10), // Random kadar P 2.0-3.0
                'kdr_c' => 1.8 + (rand(0, 8) / 10), // Random kadar C 1.8-2.6
                'kdr_k' => 1.5 + (rand(0, 10) / 10), // Random kadar K 1.5-2.5
                'ktk' => 2.2 + (rand(0, 15) / 10), // Random KTK 2.2-3.7
            ];

            $existing = TabKecamatan::find($kecamatanId);
            
            if ($existing) {
                $existing->update($kecamatanData);
                $updated++;
                $this->command->info("✅ Updated: {$kecName} ({$kecamatanId})");
            } else {
                TabKecamatan::create($kecamatanData);
                $created++;
                $this->command->info("✅ Created: {$kecName} ({$kecamatanId})");
            }
        }

        $this->command->info('🎉 Kecamatan Jawa Timur seeder completed!');
        $this->command->info("📊 Created: {$created} kecamatan");
        $this->command->info("🔄 Updated: {$updated} kecamatan");
        $this->command->info("📍 Total: " . ($created + $updated) . " kecamatan dengan data produksi kedelai");
        
        // Show summary by kabupaten
        foreach ($kabupatenMapping as $code => $kabData) {
            $count = TabKecamatan::where('tab_kabupaten_id', $code)->count();
            $this->command->info("   - {$kabData['name']}: {$count} kecamatan");
        }
    }
}
