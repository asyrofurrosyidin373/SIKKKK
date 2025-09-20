<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penyakit_gejala', function (Blueprint $table) {
            $table->uuid('org_pen_tan_id');
            $table->uuid('gejala_id');
            $table->decimal('bobot_cf', 3, 2)->default(0.5); // Certainty Factor
            $table->timestamps(); // Tambahkan timestamps
            
            // Composite primary key
            $table->primary(['org_pen_tan_id', 'gejala_id']);

            // Foreign key constraints - PERBAIKI NAMA TABEL
            $table->foreign('org_pen_tan_id')->references('id')->on('org_pen_tan')->onDelete('cascade');
            $table->foreign('gejala_id')->references('id')->on('gejala')->onDelete('cascade'); // 'gejala' bukan 'gejalas'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyakit_gejala'); // Konsisten dengan nama tabel di up()
    }
};