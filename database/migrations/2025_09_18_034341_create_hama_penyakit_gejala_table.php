<?php
// database/migrations/2024_01_01_000003_create_hama_penyakit_gejala_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hama_penyakit_gejala', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hama_penyakit_id')->constrained()->onDelete('cascade');
            $table->foreignId('gejala_id')->constrained()->onDelete('cascade');
            $table->decimal('bobot', 5, 2)->default(1.0);
            $table->timestamps();
            
            $table->unique(['hama_penyakit_id', 'gejala_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('hama_penyakit_gejala');
    }
};