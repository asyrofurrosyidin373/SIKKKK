<?php

// 9. create_kom_kacang_tanah_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kom_kacang_tanah', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('provitas', 3, 2)->nullable();
            $table->json('opt_id')->nullable();
            $table->json('varietas_kacang_tanah_id')->nullable();
            $table->tinyInteger('pot_peningkatan_judgement')->nullable();
            $table->decimal('nilai_potensi', 3, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kom_kacang_tanah');
    }
};