<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tab_kecamatan', function (Blueprint $table) {
            // Hapus kolom relasi lama ke komoditas terpisah
            $table->dropColumn([
                'kom_kedelai_id', 
                'kom_kacang_tanah_id', 
                'kom_kacang_hijau_id'
            ]);
            
            // Tambah field komoditas langsung ke tabel kecamatan
            $table->enum('jenis_komoditas', ['kedelai', 'kacang_tanah', 'kacang_hijau'])->after('ktk');
            
            // Data Produksi
            $table->decimal('provitas', 3, 2)->nullable()->after('jenis_komoditas');
            $table->decimal('luas_tanam', 8, 2)->nullable()->after('provitas');
            $table->decimal('produktivitas', 8, 2)->nullable()->after('luas_tanam');
            $table->decimal('total_produksi', 10, 2)->nullable()->after('produktivitas');
            
            // Data OPT & Varietas
            $table->json('opt_id')->nullable()->after('total_produksi');
            $table->json('varietas_id')->nullable()->after('opt_id');
            $table->tinyInteger('pot_peningkatan_judgement')->nullable()->after('varietas_id');
            $table->decimal('nilai_potensi', 5, 2)->nullable()->after('pot_peningkatan_judgement');
            
            // Index untuk performance
            $table->index('jenis_komoditas', 'idx_jenis_komoditas');
            $table->index(['luas_tanam', 'produktivitas', 'total_produksi'], 'idx_produksi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tab_kecamatan', function (Blueprint $table) {
            // Hapus field komoditas
            $table->dropIndex('idx_jenis_komoditas');
            $table->dropIndex('idx_produksi');
            
            $table->dropColumn([
                'jenis_komoditas',
                'provitas',
                'luas_tanam',
                'produktivitas',
                'total_produksi',
                'opt_id',
                'varietas_id',
                'pot_peningkatan_judgement',
                'nilai_potensi'
            ]);
            
            // Kembalikan kolom relasi lama
            $table->uuid('kom_kedelai_id')->nullable();
            $table->uuid('kom_kacang_tanah_id')->nullable();
            $table->uuid('kom_kacang_hijau_id')->nullable();
        });
    }
};
