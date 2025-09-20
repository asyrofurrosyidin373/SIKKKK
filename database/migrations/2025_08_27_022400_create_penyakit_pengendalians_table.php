<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penyakit_pengendalian', function (Blueprint $table) {
            $table->uuid('org_pen_tan_id');
            $table->uuid('pengendalian_id');
            $table->primary(['org_pen_tan_id', 'pengendalian_id']);

            $table->foreign('org_pen_tan_id')
                  ->references('id')
                  ->on('org_pen_tan')
                  ->onDelete('cascade');

            $table->foreign('pengendalian_id')
                  ->references('id')
                  ->on('pengendalian')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penyakit_pengendalian');
    }
};