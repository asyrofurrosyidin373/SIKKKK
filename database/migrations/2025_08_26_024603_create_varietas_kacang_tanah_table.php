<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('varietas_kacang_tanah', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_varietas');
            $table->year('tahun')->nullable();
            $table->string('sk')->nullable();
            $table->string('galur')->nullable();
            $table->string('asal')->nullable();
            $table->decimal('potensi_hasil', 4, 2)->nullable();
            $table->decimal('rata_hasil', 4, 2)->nullable();
            $table->string('umur_berbunga')->nullable();
            $table->string('umur_masak')->nullable();
            $table->string('tinggi_tanaman')->nullable();
            $table->string('warna_biji')->nullable();
            $table->string('bobot')->nullable();
            $table->decimal('kadar_lemak', 4, 2)->nullable();
            $table->decimal('kadar_protein', 4, 2)->nullable();
            $table->string('inventor')->nullable();
            $table->string('pengenal')->nullable();
            $table->uuid('org_pen_tan_id')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('org_pen_tan_id')->references('id')->on('org_pen_tan')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('varietas_kacang_tanah');
    }
};