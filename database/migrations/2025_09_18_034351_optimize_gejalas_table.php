<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('gejalas', function (Blueprint $table) {
            // Tambah indeks komposit untuk query yang lebih efisien
            $table->index(['daerah', 'jenis_tanaman'], 'idx_daerah_jenis');
            $table->index(['id_gejala'], 'idx_id_gejala');
            
            // Tambah kolom untuk optimasi
            $table->boolean('is_active')->default(true)->after('jenis_tanaman');
            $table->integer('frequency')->default(0)->after('is_active');
            $table->decimal('severity_score', 3, 2)->default(0.00)->after('frequency');
            
            // Tambah indeks untuk kolom baru
            $table->index(['is_active'], 'idx_gejala_active');
            $table->index(['frequency'], 'idx_frequency');
            $table->index(['severity_score'], 'idx_severity');
        });
    }

    public function down()
    {
        Schema::table('gejalas', function (Blueprint $table) {
            $table->dropIndex('idx_daerah_jenis');
            $table->dropIndex('idx_id_gejala');
            $table->dropIndex('idx_gejala_active');
            $table->dropIndex('idx_frequency');
            $table->dropIndex('idx_severity');
            $table->dropColumn(['is_active', 'frequency', 'severity_score']);
        });
    }
};
