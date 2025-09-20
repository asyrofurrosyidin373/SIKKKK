<?php
// database/migrations/2024_01_01_000006_create_deteksi_histories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('deteksi_histories', function (Blueprint $table) {
            $table->id();
            $table->json('gejala_ids');
            $table->json('results');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('detected_at');
            $table->timestamps();
            
            $table->index(['detected_at']);
            $table->index(['ip_address']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('deteksi_histories');
    }
};