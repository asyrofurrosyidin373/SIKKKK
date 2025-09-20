<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('tab_provinsi', function (Blueprint $table) {
    $table->string('id', 2)->primary(); // kode provinsi
    $table->string('nama_provinsi', 64);
    $table->decimal('latitude', 10, 8)->nullable();
    $table->decimal('longitude', 11, 8)->nullable();
    $table->timestamps();
    $table->softDeletes();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('tab_provinsi');
    }
};