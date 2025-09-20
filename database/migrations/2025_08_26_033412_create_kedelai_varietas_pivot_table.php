<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kedelai_varietas_pivot', function (Blueprint $table) {
            $table->id();
            $table->uuid('kom_kedelai_id');
            $table->uuid('varietas_kedelai_id');
            $table->timestamps();
            
            $table->foreign('kom_kedelai_id')->references('id')->on('kom_kedelai')->onDelete('cascade');
            $table->foreign('varietas_kedelai_id')->references('id')->on('varietas_kedelai')->onDelete('cascade');
            
            $table->unique(['kom_kedelai_id', 'varietas_kedelai_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kedelai_varietas_pivot');
    }
};

