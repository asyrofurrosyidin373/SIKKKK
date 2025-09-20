<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengendalian_insektisida', function (Blueprint $table) {
            $table->id();
            
            // pengendalian pakai UUID
            $table->char('pengendalian_id', 36);
            
            // insektisida pakai unsignedBigInteger
            $table->unsignedBigInteger('insektisida_id');
            
            $table->timestamps();

            // Foreign keys
            $table->foreign('pengendalian_id')
                  ->references('id')
                  ->on('pengendalian')
                  ->onDelete('cascade');

            $table->foreign('insektisida_id')
                  ->references('id')
                  ->on('insektisidas')
                  ->onDelete('cascade');

            $table->unique(['pengendalian_id', 'insektisida_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengendalian_insektisida');
    }
};
