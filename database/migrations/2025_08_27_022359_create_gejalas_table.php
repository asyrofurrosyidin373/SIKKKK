<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
Schema::create('gejala', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('bagian_tanaman');
    $table->text('deskripsi');
    $table->timestamps();
    $table->softDeletes(); // 👈 tambahkan ini
});

    }

    public function down()
    {
        Schema::dropIfExists('gejala');
    }
};