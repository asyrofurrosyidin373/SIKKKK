<?php

// 2. create_tab_kabupaten_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('tab_kabupaten', function (Blueprint $table) {
    $table->string('id', 4)->primary(); // kode kabupaten
    $table->string('tab_provinsi_id', 2); // FK ke provinsi
    $table->string('nama_kabupaten', 64);
    $table->decimal('latitude', 10, 8)->nullable();
    $table->decimal('longitude', 11, 8)->nullable();
    $table->timestamps();
    $table->softDeletes();

    $table->foreign('tab_provinsi_id')
        ->references('id')
        ->on('tab_provinsi')
        ->onDelete('cascade');
});

    }

    public function down(): void
    {
        Schema::dropIfExists('tab_kabupaten');
    }
};