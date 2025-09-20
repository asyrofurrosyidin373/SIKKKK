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
        Schema::create('pengendalian_insektisida_pivot', function (Blueprint $table) {
            $table->id();
            $table->uuid('pengendalian_id');
            $table->unsignedBigInteger('insektisida_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('pengendalian_id')->references('id')->on('pengendalian')->onDelete('cascade');
            $table->foreign('insektisida_id')->references('id')->on('insektisidas')->onDelete('cascade');
            
            // Composite unique untuk mencegah duplikat
            $table->unique(['pengendalian_id', 'insektisida_id'], 'pengendalian_insektisida_unique');
            
            // Index untuk performa
            $table->index('pengendalian_id');
            $table->index('insektisida_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengendalian_insektisida_pivot');
    }
};