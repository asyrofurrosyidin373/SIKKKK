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
        // Add production fields to kom_kedelai table
        Schema::table('kom_kedelai', function (Blueprint $table) {
            $table->decimal('luas_tanam', 8, 2)->nullable()->after('provitas');
            $table->decimal('produktivitas', 8, 2)->nullable()->after('luas_tanam');
            $table->decimal('total_produksi', 10, 2)->nullable()->after('produktivitas');
        });

        // Add production fields to kom_kacang_tanah table
        Schema::table('kom_kacang_tanah', function (Blueprint $table) {
            $table->decimal('luas_tanam', 8, 2)->nullable()->after('provitas');
            $table->decimal('produktivitas', 8, 2)->nullable()->after('luas_tanam');
            $table->decimal('total_produksi', 10, 2)->nullable()->after('produktivitas');
        });

        // Add production fields to kom_kacang_hijau table
        Schema::table('kom_kacang_hijau', function (Blueprint $table) {
            $table->decimal('luas_tanam', 8, 2)->nullable()->after('provitas');
            $table->decimal('produktivitas', 8, 2)->nullable()->after('luas_tanam');
            $table->decimal('total_produksi', 10, 2)->nullable()->after('produktivitas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kom_kedelai', function (Blueprint $table) {
            $table->dropColumn(['luas_tanam', 'produktivitas', 'total_produksi']);
        });

        Schema::table('kom_kacang_tanah', function (Blueprint $table) {
            $table->dropColumn(['luas_tanam', 'produktivitas', 'total_produksi']);
        });

        Schema::table('kom_kacang_hijau', function (Blueprint $table) {
            $table->dropColumn(['luas_tanam', 'produktivitas', 'total_produksi']);
        });
    }
};
