<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('penyakits', function (Blueprint $table) {
    $table->uuid('id')->primary(); // harus sama-sama uuid dengan pivot
    $table->string('nama');
    $table->text('deskripsi')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('penyakits');
    }
};
