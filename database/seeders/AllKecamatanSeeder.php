<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AllKecamatanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 Memulai seeding data kecamatan untuk semua kabupaten...');
        
        // Jalankan seeder untuk setiap kabupaten
        $this->call([
            KecamatanPacitanSeeder::class,
            KecamatanPonorogoSeeder::class,
            KecamatanTrenggalekSeeder::class,
            KecamatanTulungagungSeeder::class,
            KecamatanBlitarKediriMalangSeeder::class,
        ]);
        
        $this->command->info('✅ Seeding data kecamatan selesai untuk semua kabupaten!');
        $this->command->info('📊 Data yang telah diinsert:');
        $this->command->info('   - Pacitan: 12 kecamatan');
        $this->command->info('   - Ponorogo: 13 kecamatan');
        $this->command->info('   - Trenggalek: 14 kecamatan');
        $this->command->info('   - Tulungagung: ~8 kecamatan (yang memiliki data)');
        $this->command->info('   - Blitar, Kediri, Malang: ~34 kecamatan (yang memiliki data)');
    }
}
