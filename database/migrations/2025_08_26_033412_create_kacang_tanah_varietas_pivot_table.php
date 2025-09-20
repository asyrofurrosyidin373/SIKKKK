<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kacang_tanah_varietas_pivot', function (Blueprint $table) {
            $table->id();
            $table->uuid('kom_kacang_tanah_id');
            $table->uuid('varietas_kacang_tanah_id');
            $table->timestamps();
            
            $table->foreign('kom_kacang_tanah_id')->references('id')->on('kom_kacang_tanah')->onDelete('cascade');
            $table->foreign('varietas_kacang_tanah_id')->references('id')->on('varietas_kacang_tanah')->onDelete('cascade');
            
            $table->unique(['kom_kacang_tanah_id', 'varietas_kacang_tanah_id'], 'kt_var_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kacang_tanah_varietas_pivot');
    }
};
