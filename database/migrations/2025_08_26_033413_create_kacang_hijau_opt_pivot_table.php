<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kacang_hijau_opt_pivot', function (Blueprint $table) {
            $table->id();
            $table->uuid('kom_kacang_hijau_id');
            $table->uuid('org_pen_tan_id');
            $table->timestamps();
            
            $table->foreign('kom_kacang_hijau_id')->references('id')->on('kom_kacang_hijau')->onDelete('cascade');
            $table->foreign('org_pen_tan_id')->references('id')->on('org_pen_tan')->onDelete('cascade');
            
            $table->unique(['kom_kacang_hijau_id', 'org_pen_tan_id'], 'kh_opt_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kacang_hijau_opt_pivot');
    }
};