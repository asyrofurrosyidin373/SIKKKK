<?php
// database/migrations/2024_01_01_000001_create_hama_penyakits_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hama_penyakits', function (Blueprint $table) {
            $table->id();
            $table->string('id_penyakit')->unique();
            $table->string('nama_penyakit');
            $table->enum('terjangkit', ['Hama', 'Penyakit']);
            $table->string('jenis_tanaman')->default('Kedelai');
            $table->text('kultur_teknis')->nullable();
            $table->text('fisik_mekanis')->nullable();
            $table->text('hayati')->nullable();
            $table->text('kimiawi')->nullable();
            $table->string('gambar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            
            $table->index(['terjangkit']);
            $table->index(['jenis_tanaman']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('hama_penyakits');
    }
};