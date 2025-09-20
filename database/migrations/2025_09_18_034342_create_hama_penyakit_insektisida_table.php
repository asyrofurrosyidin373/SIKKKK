<?php
// database/migrations/2024_01_01_000005_create_hama_penyakit_insektisida_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
Schema::create('hama_penyakit_insektisida', function (Blueprint $table) {
    $table->id();

    $table->foreignId('hama_penyakit_id')
          ->constrained('hama_penyakits')
          ->cascadeOnDelete();

    $table->foreignId('insektisida_id')
          ->constrained('insektisidas')
          ->cascadeOnDelete();

    $table->timestamps();

    $table->unique(['hama_penyakit_id', 'insektisida_id']);
});

    }

    public function down()
    {
        Schema::dropIfExists('hama_penyakit_insektisida');
    }
};