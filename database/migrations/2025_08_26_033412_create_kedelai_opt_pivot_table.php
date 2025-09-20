<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kedelai_opt_pivot', function (Blueprint $table) {
            $table->id();
            $table->uuid('kom_kedelai_id');
            $table->uuid('org_pen_tan_id');
            $table->timestamps();
            
            $table->foreign('kom_kedelai_id')->references('id')->on('kom_kedelai')->onDelete('cascade');
            $table->foreign('org_pen_tan_id')->references('id')->on('org_pen_tan')->onDelete('cascade');
            
            $table->unique(['kom_kedelai_id', 'org_pen_tan_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kedelai_opt_pivot');
    }
};
