<?php
// database/migrations/2024_01_01_000004_create_insektisidas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('insektisidas', function (Blueprint $table) {
            // tetap gunakan id auto increment biar konsisten
            $table->id();

            // dari yang lama (uuid -> ganti ke string unik kalau masih dipakai di app)
            $table->uuid('uuid')->nullable()->unique(); 

            // id_insektisida (kode unik tambahan, kalau memang mau dipakai)
            $table->string('id_insektisida')->unique()->nullable();

            // gabungan nama insektisida
            $table->string('nama_insektisida'); // dari field lama `nama`

            $table->string('bahan_aktif');

            // lama: `sasaran`, baru: `hama_sasaran`
            $table->text('hama_sasaran')->nullable();

            $table->text('dosis')->nullable();
            $table->text('cara_aplikasi')->nullable();

            $table->softDeletes(); // dari tabel lama
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('insektisidas');
    }
};
