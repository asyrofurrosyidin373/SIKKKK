<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TabKecamatan;
use App\Models\TabKabupaten;
use App\Models\TabProvinsi;

class KecamatanLengkapSeeder extends Seeder
{
    /**
     * Seeder lengkap dengan data dari PDF - disesuaikan dengan struktur tab_kecamatan
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting Kecamatan Lengkap seeder...');

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
            if (stripos($text, 'oktober') !== false || stripos($text, 'okt') !== false) $seasons[] = 'Oktober';
            
            return json_encode($seasons);
        };

        $parseOPT = function($text) {
            if (empty($text) || strtolower($text) === 'non' || $text === '-') return json_encode([]);
            
            $opts = [];
            if (stripos($text, 'ulat grayak') !== false) $opts[] = 'Ulat Grayak';
            if (stripos($text, 'kepik hijau') !== false) $opts[] = 'Kepik Hijau';
            if (stripos($text, 'penggerek polong') !== false) $opts[] = 'Penggerek Polong';
            if (stripos($text, 'pengisap polong') !== false) $opts[] = 'Pengisap Polong';
            if (stripos($text, 'tikus') !== false) $opts[] = 'Tikus';
            if (stripos($text, 'penggulung daun') !== false) $opts[] = 'Penggulung Daun';
            if (stripos($text, 'non endemik') !== false) $opts[] = 'Non Endemik';
            
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

        // Raw data from PDF - hanya yang produktif
        $rawData = [
            // PACITAN
            ['3276', '01', 'DONOROJO', 1.43, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro', -7.8945, 110.8542],
            ['3276', '02', 'PUNUNG', 1.42, 2.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro', -7.9156, 110.9745],
            ['3276', '04', 'PACITAN', 1.97, 3.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro', -8.2044, 111.0935],
            ['3276', '06', 'ARJOSARI', 1.85, 3.00, 'Ulat Grayak dan Kepik Hijau', 'November-Maret', 'Anjasmoro', -7.8967, 111.0123],
            
            // PONOROGO
            ['3277', '02', 'SLAHUNG', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo', -7.5678, 111.2456],
            ['3277', '10', 'SIMAN', 1.20, 1.00, 'Tikus, ulat grayak, penggerek polong', 'Nov-April', 'Anjasmoro, Gepak Kuning, Gepak Ijo, Dering2 dan Devon1', -7.7678, 111.3234],
            
            // TRENGGALEK
            ['3278', '01', 'PANGGUL', 1.50, 3.00, 'ulat grayak, penggerek polong', 'Jan-April dan Okt-Des', 'Lokal, Anjasmoro, Detap 1', -8.3456, 111.4567],
            ['3278', '07', 'KARANGAN', 1.66, 3.00, 'ulat grayak, penggerek polong', 'Jan-April dan Okt-Des', 'Anjasmoro, Detap 1', -8.0234, 111.7456],
            
            // TULUNGAGUNG
            ['3279', '01', 'BESUKI', 1.90, 3.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – April', 'Dering1, Detam2, Detam 4', -8.4567, 111.8567],
            ['3279', '06', 'KALIDAWIR', 2.00, 2.00, 'ulat grayak, penggerek polong, penggulung daun', 'Nov – Maret', 'Dering1, Detam2, Detam 4', -8.0567, 111.9123],
            
            // BLITAR
            ['3281', '06', 'SUTOJAYAN', 1.97, 3.00, 'Non Endemik', 'Oktober - April', 'Anjasmoro dan Ijen', -8.1234, 112.2890],
            ['3281', '10', 'SELOPURO', 1.97, 3.00, 'Non Endemik', 'Oktober - April', 'Anjasmoro dan Ijen', -8.3567, 112.4234],
            
            // KEDIRI
            ['3282', '01', 'MOJO', 1.35, 3.00, 'Non Endemik', 'Oktober - April', 'Dering dan Detap', -7.8567, 112.0234],
            ['3282', '14', 'PARE', 1.39, 3.00, 'Non Endemik', 'Oktober - April', 'Dering dan Detap', -7.7567, 112.1890],
            
            // MALANG
            ['3283', '01', 'DONOMULYO', 1.70, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', 'Nov-Maret', 'Anjasmoro dan Grobogan', -8.2567, 112.4567],
            ['3283', '03', 'PAGAK', 1.90, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', 'Nov-Maret', 'Anjasmoro dan Grobogan', -8.3234, 112.5890],
            ['3283', '09', 'PONCOKUSUMO', 1.90, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', 'Nov-Maret', 'Anjasmoro dan Grobogan', -7.9456, 112.6890],
            ['3283', '22', 'TUMPANG', 1.90, 3.00, 'Ulat grayak, pengisap polong, dan penggerek polong', 'Nov-Maret', 'Anjasmoro dan Grobogan', -7.9123, 112.7567],
        ];

        $created = 0;
        $updated = 0;

        // Process data
        foreach ($rawData as $row) {
            [$kabCode, $kecCode, $kecName, $productivity, $potential, $opt, $plantingSeason, $varieties, $latitude, $longitude] = $row;
            
            $kecamatanId = $kabCode . str_pad($kecCode, 2, '0', STR_PAD_LEFT);
            
            // Calculate production data
            $luasTanam = $productivity * 100;
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
                'nilai_potensi' => min(9.99, $potential * 2),
                'pot_peningkatan_judgement' => min(10, max(1, round($potential))),
                'varietas_id' => $parseVarieties($varieties),
                'opt_id' => $parseOPT($opt),
                'rekomendasi_waktu_tanam_kedelai' => $parsePlantingSeason($plantingSeason),
                'bulan_hujan' => json_encode(['Januari', 'Februari', 'Maret', 'November', 'Desember']),
                'bulan_kering' => json_encode(['Juni', 'Juli', 'Agustus', 'September']),
                'ip_lahan' => 2.5 + (rand(0, 15) / 10),
                'kdr_p' => 2.0 + (rand(0, 10) / 10),
                'kdr_c' => 1.8 + (rand(0, 8) / 10),
                'kdr_k' => 1.5 + (rand(0, 10) / 10),
                'ktk' => 2.2 + (rand(0, 15) / 10),
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

        $this->command->info('🎉 Kecamatan Lengkap seeder completed!');
        $this->command->info("📊 Created: {$created} kecamatan");
        $this->command->info("🔄 Updated: {$updated} kecamatan");
        
        $totalAll = TabKecamatan::count();
        $this->command->info("📍 Total kecamatan di database: {$totalAll}");
        
        // Show breakdown by kabupaten
        foreach ($kabupatenMapping as $code => $kabData) {
            $count = TabKecamatan::where('tab_kabupaten_id', $code)->count();
            if ($count > 0) {
                $this->command->info("   - {$kabData['name']}: {$count} kecamatan");
            }
        }
    }
}
