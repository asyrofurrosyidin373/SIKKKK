<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hama_penyakits', function (Blueprint $table) {
            // Tambah indeks komposit untuk query yang lebih efisien
            $table->index(['terjangkit', 'jenis_tanaman'], 'idx_terjangkit_jenis');
            $table->index(['nama_penyakit'], 'idx_nama_penyakit');
            
            // Tambah kolom untuk optimasi
            $table->boolean('is_active')->default(true)->after('deskripsi');
            $table->integer('priority')->default(0)->after('is_active');
            $table->json('metadata')->nullable()->after('priority');
            
            // Tambah indeks untuk kolom baru
            $table->index(['is_active'], 'idx_is_active');
            $table->index(['priority'], 'idx_priority');
        });
    }

    public function down()
    {
        Schema::table('hama_penyakits', function (Blueprint $table) {
            $table->dropIndex('idx_terjangkit_jenis');
            $table->dropIndex('idx_nama_penyakit');
            $table->dropIndex('idx_is_active');
            $table->dropIndex('idx_priority');
            $table->dropColumn(['is_active', 'priority', 'metadata']);
        });
    }
};
