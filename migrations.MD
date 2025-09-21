<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('tab_provinsi', function (Blueprint $table) {
    $table->string('id', 2)->primary(); // kode provinsi
    $table->string('nama_provinsi', 64);
    $table->decimal('latitude', 10, 8)->nullable();
    $table->decimal('longitude', 11, 8)->nullable();
    $table->timestamps();
    $table->softDeletes();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('tab_provinsi');
    }
};

<?php

// 2. create_tab_kabupaten_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('tab_kabupaten', function (Blueprint $table) {
    $table->string('id', 4)->primary(); // kode kabupaten
    $table->string('tab_provinsi_id', 2); // FK ke provinsi
    $table->string('nama_kabupaten', 64);
    $table->decimal('latitude', 10, 8)->nullable();
    $table->decimal('longitude', 11, 8)->nullable();
    $table->timestamps();
    $table->softDeletes();

    $table->foreign('tab_provinsi_id')
        ->references('id')
        ->on('tab_provinsi')
        ->onDelete('cascade');
});

    }

    public function down(): void
    {
        Schema::dropIfExists('tab_kabupaten');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tab_kecamatan', function (Blueprint $table) {
            $table->string('id', 6)->primary();
            $table->string('tab_kabupaten_id', 4);
            $table->string('nama_kecamatan', 64);
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('ip_lahan', 4, 2)->nullable();
            $table->decimal('kdr_p', 4, 2)->nullable();
            $table->decimal('kdr_c', 4, 2)->nullable();
            $table->decimal('kdr_k', 4, 2)->nullable();
            $table->decimal('ktk', 4, 2)->nullable();
            $table->uuid('kom_kedelai_id')->nullable();
            $table->uuid('kom_kacang_tanah_id')->nullable();
            $table->uuid('kom_kacang_hijau_id')->nullable();
            
            $table->json('rekomendasi_waktu_tanam_kedelai')->nullable();
            $table->json('rekomendasi_waktu_tanam_kacang_tanah')->nullable();
            $table->json('rekomendasi_waktu_tanam_kacang_hijau')->nullable();
            $table->json('bulan_hujan')->nullable();
            $table->json('bulan_kering')->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tab_kabupaten_id')
                ->references('id')
                ->on('tab_kabupaten')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tab_kecamatan');
    }
};
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
<?php

// 8. create_kom_kedelai_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kom_kedelai', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('provitas', 3, 2)->nullable();
            $table->json('opt_id')->nullable();
            $table->json('varietas_kedelai_id')->nullable();
            $table->tinyInteger('pot_peningkatan_judgement')->nullable();
            $table->decimal('nilai_potensi', 3, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kom_kedelai');
    }
};
<?php

// 4. create_tab_bulan_table.php (Diperbaiki)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tab_bulan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bulan');
            $table->integer('angka_bulan');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tab_bulan');
    }
};
<?php

// 10. create_kom_kacang_hijau_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kom_kacang_hijau', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('provitas', 3, 2)->nullable();
            $table->json('opt_id')->nullable();
            $table->json('varietas_kacang_hijau_id')->nullable();
            $table->tinyInteger('pot_peningkatan_judgement')->nullable();
            $table->decimal('nilai_potensi', 3, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kom_kacang_hijau');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_pen_tan', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_opt');
            $table->string('jenis');
            $table->string('gambar')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_pen_tan');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('varietas_kedelai', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_varietas');
            $table->year('tahun')->nullable();
            $table->string('sk')->nullable();
            $table->string('galur')->nullable();
            $table->string('asal')->nullable();
            $table->decimal('potensi_hasil', 4, 2)->nullable();
            $table->decimal('rata_hasil', 4, 2)->nullable();
            $table->string('umur_berbunga')->nullable();
            $table->string('umur_masak')->nullable();
            $table->string('tinggi_tanaman')->nullable();
            $table->string('warna_biji')->nullable();
            $table->string('bobot')->nullable();
            $table->decimal('kadar_lemak', 4, 2)->nullable();
            $table->decimal('kadar_protein', 4, 2)->nullable();
            $table->string('inventor')->nullable();
            $table->string('pengenal')->nullable();
            $table->uuid('org_pen_tan_id')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('org_pen_tan_id')->references('id')->on('org_pen_tan')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('varietas_kedelai');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('varietas_kacang_hijau', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_varietas');
            $table->year('tahun')->nullable();
            $table->string('sk')->nullable();
            $table->string('galur')->nullable();
            $table->string('asal')->nullable();
            $table->decimal('potensi_hasil', 4, 2)->nullable();
            $table->decimal('rata_hasil', 4, 2)->nullable();
            $table->string('umur_berbunga')->nullable();
            $table->string('umur_masak')->nullable();
            $table->string('tinggi_tanaman')->nullable();
            $table->string('warna_biji')->nullable();
            $table->string('bobot')->nullable();
            $table->decimal('kadar_lemak', 4, 2)->nullable();
            $table->decimal('kadar_protein', 4, 2)->nullable();
            $table->string('inventor')->nullable();
            $table->string('pengenal')->nullable();
            $table->uuid('org_pen_tan_id')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('org_pen_tan_id')->references('id')->on('org_pen_tan')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('varietas_kacang_hijau');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('varietas_kacang_tanah', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_varietas');
            $table->year('tahun')->nullable();
            $table->string('sk')->nullable();
            $table->string('galur')->nullable();
            $table->string('asal')->nullable();
            $table->decimal('potensi_hasil', 4, 2)->nullable();
            $table->decimal('rata_hasil', 4, 2)->nullable();
            $table->string('umur_berbunga')->nullable();
            $table->string('umur_masak')->nullable();
            $table->string('tinggi_tanaman')->nullable();
            $table->string('warna_biji')->nullable();
            $table->string('bobot')->nullable();
            $table->decimal('kadar_lemak', 4, 2)->nullable();
            $table->decimal('kadar_protein', 4, 2)->nullable();
            $table->string('inventor')->nullable();
            $table->string('pengenal')->nullable();
            $table->uuid('org_pen_tan_id')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('org_pen_tan_id')->references('id')->on('org_pen_tan')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('varietas_kacang_tanah');
    }
};
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

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kacang_tanah_opt_pivot', function (Blueprint $table) {
            $table->id();
            $table->uuid('kom_kacang_tanah_id');
            $table->uuid('org_pen_tan_id');
            $table->timestamps();
            
            $table->foreign('kom_kacang_tanah_id')->references('id')->on('kom_kacang_tanah')->onDelete('cascade');
            $table->foreign('org_pen_tan_id')->references('id')->on('org_pen_tan')->onDelete('cascade');
            
            $table->unique(['kom_kacang_tanah_id', 'org_pen_tan_id'], 'kt_opt_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('kacang_tanah_opt_pivot');
    }
};
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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tanaman', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_tanaman');
            $table->timestamps();
            $table->softDeletes(); // Tambah softDeletes
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tanaman'); // Fix typo
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
Schema::create('penyakits', function (Blueprint $table) {
    $table->uuid('id')->primary(); // harus sama-sama uuid dengan pivot
    $table->string('nama');
    $table->text('deskripsi')->nullable();
    $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::dropIfExists('penyakits');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
Schema::create('gejala', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('bagian_tanaman');
    $table->text('deskripsi');
    $table->timestamps();
    $table->softDeletes(); // 👈 tambahkan ini
});

    }

    public function down()
    {
        Schema::dropIfExists('gejala');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengendalian', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('jenis', ['Kultur teknis', 'Mekanis', 'Kimiawi', 'Hayati']);
            $table->text('deskripsi');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengendalian');
    }
};

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

<?php
// database/migrations/2024_01_01_000004_create_insektisidas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('insektisidas', function (Blueprint $table) {
            // tetap gunakan id auto increment biar konsisten
            $table->id();

            // dari yang lama (uuid -> ganti ke string unik kalau masih dipakai di app)
            $table->uuid('uuid')->nullable()->unique(); 

            // id_insektisida (kode unik tambahan, kalau memang mau dipakai)
            $table->string('id_insektisida')->unique()->nullable();

            // gabungan nama insektisida
            $table->string('nama_insektisida'); // dari field lama `nama`

            $table->string('bahan_aktif');

            // lama: `sasaran`, baru: `hama_sasaran`
            $table->text('hama_sasaran')->nullable();

            $table->text('dosis')->nullable();
            $table->text('cara_aplikasi')->nullable();

            $table->softDeletes(); // dari tabel lama
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('insektisidas');
    }
};

<?php

// 6. create_laporan_deteksi_table.php (Diperbaiki foreign key)
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('laporan_deteksi', function (Blueprint $table) {
            $table->uuid('id')->primary(); 
            $table->text('deskripsi');
            $table->string('lokasi');
            $table->string('foto_path')->nullable();
            $table->string('status')->default('pending');
            
            $table->unsignedBigInteger('user_id');
            $table->uuid('tanaman_id')->nullable();
            $table->uuid('varietas_id')->nullable();
            $table->uuid('org_pen_tan_id')->nullable();
            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tanaman_id')->references('id')->on('tanaman')->onDelete('set null');
            $table->foreign('varietas_id')->references('id')->on('varietas_kedelai')->onDelete('set null');
            $table->foreign('org_pen_tan_id')->references('id')->on('org_pen_tan')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_deteksi');
    }
};

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
        Schema::table('gejala', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gejala', function (Blueprint $table) {
            //
        });
    }
};

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
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable');
            $table->text('name');
            $table->string('token', 64)->unique();
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};

<?php
// database/migrations/2024_01_01_000001_create_hama_penyakits_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hama_penyakits', function (Blueprint $table) {
            $table->id();
            $table->string('id_penyakit')->unique();
            $table->string('nama_penyakit');
            $table->enum('terjangkit', ['Hama', 'Penyakit']);
            $table->string('jenis_tanaman')->default('Kedelai');
            $table->text('kultur_teknis')->nullable();
            $table->text('fisik_mekanis')->nullable();
            $table->text('hayati')->nullable();
            $table->text('kimiawi')->nullable();
            $table->string('gambar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();
            
            $table->index(['terjangkit']);
            $table->index(['jenis_tanaman']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('hama_penyakits');
    }
};

<?php
// database/migrations/2024_01_01_000002_create_gejalas_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gejalas', function (Blueprint $table) {
            $table->id();
            $table->string('id_gejala')->unique();
            $table->text('gejala');
            $table->enum('daerah', ['Akar', 'Batang', 'Daun']);
            $table->string('jenis_tanaman')->default('Kedelai');
            $table->timestamps();
            
            $table->index(['daerah']);
            $table->index(['jenis_tanaman']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('gejalas');
    }
};

<?php
// database/migrations/2024_01_01_000003_create_hama_penyakit_gejala_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('hama_penyakit_gejala', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hama_penyakit_id')->constrained()->onDelete('cascade');
            $table->foreignId('gejala_id')->constrained()->onDelete('cascade');
            $table->decimal('bobot', 5, 2)->default(1.0);
            $table->timestamps();
            
            $table->unique(['hama_penyakit_id', 'gejala_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('hama_penyakit_gejala');
    }
};

<?php
// database/migrations/2024_01_01_000006_create_deteksi_histories_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('deteksi_histories', function (Blueprint $table) {
            $table->id();
            $table->json('gejala_ids');
            $table->json('results');
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamp('detected_at');
            $table->timestamps();
            
            $table->index(['detected_at']);
            $table->index(['ip_address']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('deteksi_histories');
    }
};
<?php
// database/migrations/2024_01_01_000005_create_hama_penyakit_insektisida_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
Schema::create('hama_penyakit_insektisida', function (Blueprint $table) {
    $table->id();

    $table->foreignId('hama_penyakit_id')
          ->constrained('hama_penyakits')
          ->cascadeOnDelete();

    $table->foreignId('insektisida_id')
          ->constrained('insektisidas')
          ->cascadeOnDelete();

    $table->timestamps();

    $table->unique(['hama_penyakit_id', 'insektisida_id']);
});

    }

    public function down()
    {
        Schema::dropIfExists('hama_penyakit_insektisida');
    }
};

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
        Schema::create('pengendalian_insektisida_pivot', function (Blueprint $table) {
            $table->id();
            $table->uuid('pengendalian_id');
            $table->unsignedBigInteger('insektisida_id');
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('pengendalian_id')->references('id')->on('pengendalian')->onDelete('cascade');
            $table->foreign('insektisida_id')->references('id')->on('insektisidas')->onDelete('cascade');
            
            // Composite unique untuk mencegah duplikat
            $table->unique(['pengendalian_id', 'insektisida_id'], 'pengendalian_insektisida_unique');
            
            // Index untuk performa
            $table->index('pengendalian_id');
            $table->index('insektisida_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengendalian_insektisida_pivot');
    }
};
