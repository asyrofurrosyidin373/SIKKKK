<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('opt_varietas_pivot', function (Blueprint $table) {
            $table->id();
            $table->uuid('org_pen_tan_id');
            $table->uuid('varietas_id'); // Bisa varietas_kedelai_id, dll., tapi general untuk simplitas
            $table->tinyInteger('tingkat_resistensi')->nullable(); // 1-10 misal
            $table->string('komoditas_type')->nullable(); // 'kedelai', 'kacang_tanah', dll. untuk polymorph
            $table->timestamps();

            $table->foreign('org_pen_tan_id')->references('id')->on('org_pen_tan')->onDelete('cascade');
            // Foreign ke varietas bisa polymorph, tapi skip untuk sekarang
            $table->unique(['org_pen_tan_id', 'varietas_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('opt_varietas_pivot');
    }
};