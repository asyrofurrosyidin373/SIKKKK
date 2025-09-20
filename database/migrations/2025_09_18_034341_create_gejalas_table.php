<?php
// database/migrations/2024_01_01_000002_create_gejalas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gejalas', function (Blueprint $table) {
            $table->id();
            $table->string('id_gejala')->unique();
            $table->text('gejala');
            $table->enum('daerah', ['Akar', 'Batang', 'Daun']);
            $table->string('jenis_tanaman')->default('Kedelai');
            $table->timestamps();
            
            $table->index(['daerah']);
            $table->index(['jenis_tanaman']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('gejalas');
    }
};