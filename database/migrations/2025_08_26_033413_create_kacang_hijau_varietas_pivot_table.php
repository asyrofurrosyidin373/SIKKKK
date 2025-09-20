<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kacang_hijau_varietas_pivot', function (Blueprint $table) {
            $table->id();
            $table->uuid('kom_kacang_hijau_id');
            $table->uuid('varietas_kacang_hijau_id');
            $table->timestamps();
            
            $table->foreign('kom_kacang_hijau_id')->references('id')->on('kom_kacang_hijau')->onDelete('cascade');
            $table->foreign('varietas_kacang_hijau_id')->references('id')->on('varietas_kacang_hijau')->onDelete('cascade');
            
            $table->unique(['kom_kacang_hijau_id', 'varietas_kacang_hijau_id'], 'kh_var_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kacang_hijau_varietas_pivot');
    }
};