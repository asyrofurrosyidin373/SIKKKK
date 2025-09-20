<?php

// 6. create_laporan_deteksi_table.php (Diperbaiki foreign key)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_deteksi', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->text('deskripsi');
            $table->string('lokasi');
            $table->string('foto_path')->nullable();
            $table->string('status')->default('pending');
            
            $table->unsignedBigInteger('user_id');
            $table->uuid('tanaman_id')->nullable();
            $table->uuid('varietas_id')->nullable();
            $table->uuid('org_pen_tan_id')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tanaman_id')->references('id')->on('tanaman')->onDelete('set null');
            $table->foreign('varietas_id')->references('id')->on('varietas_kedelai')->onDelete('set null');
            $table->foreign('org_pen_tan_id')->references('id')->on('org_pen_tan')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_deteksi');
    }
};
