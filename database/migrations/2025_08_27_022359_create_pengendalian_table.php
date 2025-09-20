<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengendalian', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('jenis', ['Kultur teknis', 'Mekanis', 'Kimiawi', 'Hayati']);
            $table->text('deskripsi');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengendalian');
    }
};