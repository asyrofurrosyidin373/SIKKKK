<?php

// 4. create_tab_bulan_table.php (Diperbaiki)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tab_bulan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bulan');
            $table->integer('angka_bulan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tab_bulan');
    }
};