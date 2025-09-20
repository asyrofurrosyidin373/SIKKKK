<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tab_kecamatan', function (Blueprint $table) {
            $table->string('id', 6)->primary();
            $table->string('tab_kabupaten_id', 4);
            $table->string('nama_kecamatan', 64);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('ip_lahan', 4, 2)->nullable();
            $table->decimal('kdr_p', 4, 2)->nullable();
            $table->decimal('kdr_c', 4, 2)->nullable();
            $table->decimal('kdr_k', 4, 2)->nullable();
            $table->decimal('ktk', 4, 2)->nullable();
            $table->uuid('kom_kedelai_id')->nullable();
            $table->uuid('kom_kacang_tanah_id')->nullable();
            $table->uuid('kom_kacang_hijau_id')->nullable();
            
            $table->json('rekomendasi_waktu_tanam_kedelai')->nullable();
            $table->json('rekomendasi_waktu_tanam_kacang_tanah')->nullable();
            $table->json('rekomendasi_waktu_tanam_kacang_hijau')->nullable();
            $table->json('bulan_hujan')->nullable();
            $table->json('bulan_kering')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tab_kabupaten_id')
                ->references('id')
                ->on('tab_kabupaten')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tab_kecamatan');
    }
};