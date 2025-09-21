<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('deteksi_histories', function (Blueprint $table) {
            // Tambah indeks komposit untuk query yang lebih efisien
            $table->index(['detected_at', 'ip_address'], 'idx_detected_ip');
            $table->index(['detected_at'], 'idx_detected_at');
            
            // Tambah kolom untuk optimasi
            $table->string('session_id')->nullable()->after('user_agent');
            $table->decimal('confidence_score', 5, 2)->nullable()->after('session_id');
            $table->json('detection_metadata')->nullable()->after('confidence_score');
            $table->boolean('is_verified')->default(false)->after('detection_metadata');
            
            // Tambah indeks untuk kolom baru
            $table->index(['session_id'], 'idx_session_id');
            $table->index(['confidence_score'], 'idx_confidence');
            $table->index(['is_verified'], 'idx_verified');
        });
    }

    public function down()
    {
        Schema::table('deteksi_histories', function (Blueprint $table) {
            $table->dropIndex('idx_detected_ip');
            $table->dropIndex('idx_detected_at');
            $table->dropIndex('idx_session_id');
            $table->dropIndex('idx_confidence');
            $table->dropIndex('idx_verified');
            $table->dropColumn(['session_id', 'confidence_score', 'detection_metadata', 'is_verified']);
        });
    }
};
