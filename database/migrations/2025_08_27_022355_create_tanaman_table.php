<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanaman', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_tanaman');
            $table->timestamps();
            $table->softDeletes(); // Tambah softDeletes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanaman'); // Fix typo
    }
};