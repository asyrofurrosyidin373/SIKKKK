<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TabKabupaten;

class TabProvinsi extends Model
{
    use SoftDeletes;

    protected $table = 'tab_provinsi';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'nama_provinsi', 'latitude', 'longitude'];

    public function kabupaten()
    {
        return $this->hasMany(TabKabupaten::class, 'tab_provinsi_id', 'id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TabProvinsi;
use App\Models\TabKecamatan;

class TabKabupaten extends Model
{
    use SoftDeletes;

    protected $table = 'tab_kabupaten';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'tab_provinsi_id', 'nama_kabupaten', 'latitude', 'longitude'];

    public function provinsi()
    {
        return $this->belongsTo(TabProvinsi::class, 'tab_provinsi_id', 'id');
    }

    public function kecamatan()
    {
        return $this->hasMany(TabKecamatan::class, 'tab_kabupaten_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabKecamatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tab_kecamatan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'tab_kabupaten_id',
        'nama_kecamatan',
        'latitude',
        'longitude',
        'ip_lahan',
        'kdr_p',
        'kdr_c',
        'kdr_k',
        'ktk',
        'kom_kedelai_id',
        'kom_kacang_tanah_id',
        'kom_kacang_hijau_id',
        'rekomendasi_waktu_tanam_kedelai',
        'rekomendasi_waktu_tanam_kacang_tanah',
        'rekomendasi_waktu_tanam_kacang_hijau',
        'bulan_hujan',
        'bulan_kering',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'ip_lahan' => 'decimal:2',
        'kdr_p' => 'decimal:2',
        'kdr_c' => 'decimal:2',
        'kdr_k' => 'decimal:2',
        'ktk' => 'decimal:2',
        'rekomendasi_waktu_tanam_kedelai' => 'array',
        'rekomendasi_waktu_tanam_kacang_tanah' => 'array',
        'rekomendasi_waktu_tanam_kacang_hijau' => 'array',
        'bulan_hujan' => 'array',
        'bulan_kering' => 'array',
    ];

    // Relasi ke kabupaten
    public function kabupaten()
    {
        return $this->belongsTo(TabKabupaten::class, 'tab_kabupaten_id');
    }

    public function komoditasKedelai()
    {
        return $this->belongsTo(KomKedelai::class, 'kom_kedelai_id');
    }

    public function komoditasKacangTanah()
    {
        return $this->belongsTo(KomKacangTanah::class, 'kom_kacang_tanah_id');
    }

    public function komoditasKacangHijau()
    {
        return $this->belongsTo(KomKacangHijau::class, 'kom_kacang_hijau_id');
    }

    // Relasi many-to-many dengan bulan melalui pivot
    public function bulans()
    {
        return $this->belongsToMany(
            TabBulan::class,
            'kecamatan_bulan_pivot',
            'tab_kecamatan_id',
            'tab_bulan_id'
        )->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Relasi spesifik untuk bulan hujan
    public function bulanHujan()
    {
        return $this->belongsToMany(
            TabBulan::class,
            'kecamatan_bulan_pivot',
            'tab_kecamatan_id',
            'tab_bulan_id'
        )->wherePivot('tipe', 'hujan')
          ->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Relasi spesifik untuk bulan kering
    public function bulanKering()
    {
        return $this->belongsToMany(
            TabBulan::class,
            'kecamatan_bulan_pivot',
            'tab_kecamatan_id',
            'tab_bulan_id'
        )->wherePivot('tipe', 'kering')
          ->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Relasi untuk waktu tanam kedelai
    public function waktuTanamKedelai()
    {
        return $this->belongsToMany(
            TabBulan::class,
            'kecamatan_bulan_pivot',
            'tab_kecamatan_id',
            'tab_bulan_id'
        )->wherePivot('tipe', 'tanam_kedelai')
          ->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Relasi untuk waktu tanam kacang tanah
    public function waktuTanamKacangTanah()
    {
        return $this->belongsToMany(
            TabBulan::class,
            'kecamatan_bulan_pivot',
            'tab_kecamatan_id',
            'tab_bulan_id'
        )->wherePivot('tipe', 'tanam_kacang_tanah')
          ->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Relasi untuk waktu tanam kacang hijau
    public function waktuTanamKacangHijau()
    {
        return $this->belongsToMany(
            TabBulan::class,
            'kecamatan_bulan_pivot',
            'tab_kecamatan_id',
            'tab_bulan_id'
        )->wherePivot('tipe', 'tanam_kacang_hijau')
          ->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Accessor untuk mendapatkan nama bulan hujan dari JSON
    public function getBulanHujanNamaAttribute()
    {
        if (empty($this->bulan_hujan)) {
            return [];
        }
        
        return TabBulan::whereIn('id', $this->bulan_hujan)
            ->pluck('nama_bulan')
            ->toArray();
    }

    // Accessor untuk mendapatkan nama bulan tanam kedelai dari JSON
    public function getWaktuTanamKedelaiNamaAttribute()
    {
        if (empty($this->rekomendasi_waktu_tanam_kedelai)) {
            return [];
        }
        
        return TabBulan::whereIn('id', $this->rekomendasi_waktu_tanam_kedelai)
            ->pluck('nama_bulan')
            ->toArray();
    }

    // Accessor untuk mendapatkan nama bulan tanam kacang tanah dari JSON
    public function getWaktuTanamKacangTanahNamaAttribute()
    {
        if (empty($this->rekomendasi_waktu_tanam_kacang_tanah)) {
            return [];
        }
        
        return TabBulan::whereIn('id', $this->rekomendasi_waktu_tanam_kacang_tanah)
            ->pluck('nama_bulan')
            ->toArray();
    }

    // Accessor untuk mendapatkan nama bulan tanam kacang hijau dari JSON
    public function getWaktuTanamKacangHijauNamaAttribute()
    {
        if (empty($this->rekomendasi_waktu_tanam_kacang_hijau)) {
            return [];
        }
        
        return TabBulan::whereIn('id', $this->rekomendasi_waktu_tanam_kacang_hijau)
            ->pluck('nama_bulan')
            ->toArray();
    }

    // Method untuk sync bulan hujan via pivot table
    public function syncBulanHujan(array $bulanIds, array $keterangan = [])
    {
        $syncData = [];
        foreach ($bulanIds as $index => $bulanId) {
            $syncData[$bulanId] = [
                'tipe' => 'hujan',
                'keterangan' => $keterangan[$index] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        $this->bulans()->wherePivot('tipe', 'hujan')->detach();
        $this->bulans()->attach($syncData);
    }

    // Method untuk sync waktu tanam kedelai via pivot table
    public function syncWaktuTanamKedelai(array $bulanIds, array $keterangan = [])
    {
        $syncData = [];
        foreach ($bulanIds as $index => $bulanId) {
            $syncData[$bulanId] = [
                'tipe' => 'tanam_kedelai',
                'keterangan' => $keterangan[$index] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        $this->bulans()->wherePivot('tipe', 'tanam_kedelai')->detach();
        $this->bulans()->attach($syncData);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabBulan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tab_bulan';

    protected $fillable = [
        'nama_bulan',
        'angka_bulan',
    ];

    protected $casts = [
        'angka_bulan' => 'integer',
    ];

    // Relasi many-to-many dengan kecamatan melalui pivot
    public function kecamatans()
    {
        return $this->belongsToMany(
            TabKecamatan::class,
            'kecamatan_bulan_pivot',
            'tab_bulan_id',
            'tab_kecamatan_id'
        )->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Relasi spesifik untuk bulan hujan
    public function kecamatanBulanHujan()
    {
        return $this->belongsToMany(
            TabKecamatan::class,
            'kecamatan_bulan_pivot',
            'tab_bulan_id',
            'tab_kecamatan_id'
        )->wherePivot('tipe', 'hujan')
          ->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Relasi spesifik untuk waktu tanam kedelai
    public function kecamatanTanamKedelai()
    {
        return $this->belongsToMany(
            TabKecamatan::class,
            'kecamatan_bulan_pivot',
            'tab_bulan_id',
            'tab_kecamatan_id'
        )->wherePivot('tipe', 'tanam_kedelai')
          ->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KomKacangHijau extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'kom_kacang_hijau';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'provitas',
        'opt_id',
        'varietas_kacang_hijau_id',
        'pot_peningkatan_judgement',
        'nilai_potensi',
    ];

    protected $casts = [
        'provitas' => 'decimal:2',
        'opt_id' => 'array',
        'varietas_kacang_hijau_id' => 'array',
        'pot_peningkatan_judgement' => 'integer',
        'nilai_potensi' => 'decimal:2',
    ];

    public function kecamatan(): HasMany
    {
        return $this->hasMany(TabKecamatan::class, 'kom_kacang_hijau_id');
    }

    public function organisme(): BelongsToMany
    {
        return $this->belongsToMany(OrgPenTan::class, 'kacang_hijau_opt_pivot', 'kom_kacang_hijau_id', 'org_pen_tan_id');
    }

    public function varietas(): BelongsToMany
    {
        return $this->belongsToMany(VarietasKacangHijau::class, 'kacang_hijau_varietas_pivot', 'kom_kacang_hijau_id', 'varietas_kacang_hijau_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KomKacangTanah extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'kom_kacang_tanah';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'provitas',
        'opt_id',
        'varietas_kacang_tanah_id',
        'pot_peningkatan_judgement',
        'nilai_potensi',
    ];

    protected $casts = [
        'provitas' => 'decimal:2',
        'opt_id' => 'array',
        'varietas_kacang_tanah_id' => 'array',
        'pot_peningkatan_judgement' => 'integer',
        'nilai_potensi' => 'decimal:2',
    ];

    public function kecamatan(): HasMany
    {
        return $this->hasMany(TabKecamatan::class, 'kom_kacang_tanah_id');
    }

    public function organisme(): BelongsToMany
    {
        return $this->belongsToMany(OrgPenTan::class, 'kacang_tanah_opt_pivot', 'kom_kacang_tanah_id', 'org_pen_tan_id');
    }

    public function varietas(): BelongsToMany
    {
        return $this->belongsToMany(VarietasKacangTanah::class, 'kacang_tanah_varietas_pivot', 'kom_kacang_tanah_id', 'varietas_kacang_tanah_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KomKedelai extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'kom_kedelai';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'provitas',
        'opt_id',
        'varietas_kedelai_id',
        'pot_peningkatan_judgement',
        'nilai_potensi',
    ];

    protected $casts = [
        'provitas' => 'decimal:2',
        'opt_id' => 'array',
        'varietas_kedelai_id' => 'array',
        'pot_peningkatan_judgement' => 'integer',
        'nilai_potensi' => 'decimal:2',
    ];

    public function kecamatan(): HasMany
    {
        return $this->hasMany(TabKecamatan::class, 'kom_kedelai_id');
    }

    public function organisme(): BelongsToMany
    {
        return $this->belongsToMany(OrgPenTan::class, 'kedelai_opt_pivot', 'kom_kedelai_id', 'org_pen_tan_id');
    }

    public function varietas(): BelongsToMany
    {
        return $this->belongsToMany(VarietasKedelai::class, 'kedelai_varietas_pivot', 'kom_kedelai_id', 'varietas_kedelai_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VarietasKacangHijau extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'varietas_kacang_hijau';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_varietas',
        'tahun',
        'sk',
        'galur',
        'asal',
        'potensi_hasil',
        'rata_hasil',
        'umur_berbunga',
        'umur_masak',
        'tinggi_tanaman',
        'warna_biji',
        'bobot',
        'kadar_lemak',
        'kadar_protein',
        'inventor',
        'pengenal',
        'org_pen_tan_id',
        'gambar',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'potensi_hasil' => 'decimal:2',
        'rata_hasil' => 'decimal:2',
        'kadar_lemak' => 'decimal:2',
        'kadar_protein' => 'decimal:2',
    ];

    public function organisme(): BelongsTo
    {
        return $this->belongsTo(OrgPenTan::class, 'org_pen_tan_id');
    }

    public function komoditas(): BelongsToMany
    {
        return $this->belongsToMany(KomKacangHijau::class, 'kacang_hijau_varietas_pivot', 'varietas_kacang_hijau_id', 'kom_kacang_hijau_id');
    }

    public function resistensiOpt(): BelongsToMany
    {
        return $this->belongsToMany(OrgPenTan::class, 'opt_varietas_pivot', 'varietas_id', 'org_pen_tan_id')
                    ->withPivot('tingkat_resistensi');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VarietasKacangTanah extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'varietas_kacang_tanah';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_varietas',
        'tahun',
        'sk',
        'galur',
        'asal',
        'potensi_hasil',
        'rata_hasil',
        'umur_berbunga',
        'umur_masak',
        'tinggi_tanaman',
        'warna_biji',
        'bobot',
        'kadar_lemak',
        'kadar_protein',
        'inventor',
        'pengenal',
        'org_pen_tan_id',
        'gambar',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'potensi_hasil' => 'decimal:2',
        'rata_hasil' => 'decimal:2',
        'kadar_lemak' => 'decimal:2',
        'kadar_protein' => 'decimal:2',
    ];

    public function organisme(): BelongsTo
    {
        return $this->belongsTo(OrgPenTan::class, 'org_pen_tan_id');
    }

    public function komoditas(): BelongsToMany
    {
        return $this->belongsToMany(KomKacangTanah::class, 'kacang_tanah_varietas_pivot', 'varietas_kacang_tanah_id', 'kom_kacang_tanah_id');
    }

    // Tambah inverse resistensi
    public function resistensiOpt(): BelongsToMany
    {
        return $this->belongsToMany(OrgPenTan::class, 'opt_varietas_pivot', 'varietas_id', 'org_pen_tan_id')
                    ->withPivot('tingkat_resistensi');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VarietasKedelai extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'varietas_kedelai';

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_varietas',
        'tahun',
        'sk',
        'galur',
        'asal',
        'potensi_hasil',
        'rata_hasil',
        'umur_berbunga',
        'umur_masak',
        'tinggi_tanaman',
        'warna_biji',
        'bobot',
        'kadar_lemak',
        'kadar_protein',
        'inventor',
        'pengenal',
        'org_pen_tan_id',
        'gambar',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'potensi_hasil' => 'decimal:2',
        'rata_hasil' => 'decimal:2',
        'kadar_lemak' => 'decimal:2',
        'kadar_protein' => 'decimal:2',
    ];

    public function organisme(): BelongsTo
    {
        return $this->belongsTo(OrgPenTan::class, 'org_pen_tan_id');
    }

    public function komoditas(): BelongsToMany
    {
        return $this->belongsToMany(KomKedelai::class, 'kedelai_varietas_pivot', 'varietas_kedelai_id', 'kom_kedelai_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tanaman extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tanaman';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_tanaman',
    ];

    public function laporanDeteksi(): HasMany
    {
        return $this->hasMany(LaporanDeteksi::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyakit extends Model
{
    /** @use HasFactory<\Database\Factories\PenyakitFactory> */
    use HasFactory;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PenyakitGejala extends Pivot
{
    protected $table = 'penyakit_gejala';

    protected $fillable = [
        'org_pen_tan_id',
        'gejala_id',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PenyakitPengendalian extends Pivot
{
    protected $table = 'penyakit_pengendalian';

    protected $fillable = [
        'org_pen_tan_id',
        'pengendalian_id',
    ];
}
<?php
// app/Models/Gejala.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Gejala extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_gejala',
        'gejala',
        'daerah',
        'jenis_tanaman'
    ];

    protected $casts = [
        'daerah' => 'string',
    ];

    public function hamaPenyakit(): BelongsToMany
    {
        return $this->belongsToMany(HamaPenyakit::class, 'hama_penyakit_gejala')
                    ->withPivot('bobot')
                    ->withTimestamps();
    }

    // Scope untuk filter berdasarkan daerah
    public function scopeAkar($query)
    {
        return $query->where('daerah', 'Akar');
    }

    public function scopeBatang($query)
    {
        return $query->where('daerah', 'Batang');
    }

    public function scopeDaun($query)
    {
        return $query->where('daerah', 'Daun');
    }

    public function scopeKedelai($query)
    {
        return $query->where('jenis_tanaman', 'Kedelai');
    }

    // Method untuk mendapatkan icon berdasarkan daerah
    public function getIconAttribute(): string
    {
        return match($this->daerah) {
            'Akar' => 'fas fa-seedling',
            'Batang' => 'fas fa-tree',
            'Daun' => 'fas fa-leaf',
            default => 'fas fa-question-circle'
        };
    }

    // Method untuk mendapatkan warna berdasarkan daerah
    public function getColorAttribute(): string
    {
        return match($this->daerah) {
            'Akar' => 'text-brown',
            'Batang' => 'text-success',
            'Daun' => 'text-primary',
            default => 'text-muted'
        };
    }
}

<?php
// app/Models/HamaPenyakit.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class HamaPenyakit extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_penyakit',
        'nama_penyakit',
        'terjangkit',
        'jenis_tanaman',
        'kultur_teknis',
        'fisik_mekanis',
        'hayati',
        'kimiawi',
        'gambar',
        'deskripsi'
    ];

    protected $casts = [
        'terjangkit' => 'string',
    ];

    public function gejala(): BelongsToMany
    {
        return $this->belongsToMany(Gejala::class, 'hama_penyakit_gejala')
                    ->withPivot('bobot')
                    ->withTimestamps();
    }

    public function insektisida(): BelongsToMany
    {
        return $this->belongsToMany(Insektisida::class, 'hama_penyakit_insektisida')
                    ->withTimestamps();
    }

    // Accessor untuk URL gambar
    protected function gambarUrl(): Attribute
    {
        return Attribute::make(
            get: fn ($value, $attributes) => 
                $attributes['gambar'] ? asset('storage/' . $attributes['gambar']) : null
        );
    }

    // Scope untuk filter berdasarkan jenis
    public function scopeHama($query)
    {
        return $query->where('terjangkit', 'Hama');
    }

    public function scopePenyakit($query)
    {
        return $query->where('terjangkit', 'Penyakit');
    }

    public function scopeKedelai($query)
    {
        return $query->where('jenis_tanaman', 'Kedelai');
    }

    // Method untuk mendapatkan confidence score berdasarkan gejala
    public function getConfidenceScore(array $gejalaIds): float
    {
        $matchedGejala = $this->gejala()->whereIn('gejala_id', $gejalaIds)->get();
        $totalBobotMatched = $matchedGejala->sum('pivot.bobot');
        $totalBobotPenyakit = $this->gejala()->sum('bobot');
        
        if ($totalBobotPenyakit == 0) {
            return 0;
        }
        
        return ($totalBobotMatched / $totalBobotPenyakit) * 100;
    }

    // Method untuk mendapatkan gejala yang cocok
    public function getMatchedSymptoms(array $gejalaIds)
    {
        return $this->gejala()->whereIn('gejala_id', $gejalaIds)->get();
    }

    // Method untuk check apakah memiliki metode pengendalian
    public function hasControlMethods(): bool
    {
        return !empty($this->kultur_teknis) || 
               !empty($this->fisik_mekanis) || 
               !empty($this->hayati) || 
               !empty($this->kimiawi);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Insektisida extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_insektisida',
        'nama_insektisida',
        'bahan_aktif',
        'hama_sasaran',
        'dosis',
        'cara_aplikasi'
    ];

    public function hamaPenyakit(): BelongsToMany
    {
        return $this->belongsToMany(HamaPenyakit::class, 'hama_penyakit_insektisida')
                    ->withTimestamps();
    }

    // Relasi dengan Pengendalian - tambah ini
    public function pengendalian(): BelongsToMany
    {
        return $this->belongsToMany(Pengendalian::class, 'pengendalian_insektisida_pivot', 'insektisida_id', 'pengendalian_id');
    }

    // Method untuk format nama lengkap
    public function getNamaLengkapAttribute(): string
    {
        return "{$this->nama_insektisida} ({$this->bahan_aktif})";
    }

    // Scope untuk pencarian
    public function scopeForHama($query, $hamaNama)
    {
        return $query->where('hama_sasaran', 'like', "%{$hamaNama}%");
    }
}
<?php
// app/Models/DeteksiHistory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DeteksiHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'gejala_ids',
        'results',
        'ip_address',
        'user_agent',
        'detected_at'
    ];

    protected $casts = [
        'gejala_ids' => 'array',
        'results' => 'array',
        'detected_at' => 'datetime'
    ];

    // Scope untuk filter berdasarkan tanggal
    public function scopeToday($query)
    {
        return $query->whereDate('detected_at', Carbon::today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('detected_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('detected_at', Carbon::now()->month)
                     ->whereYear('detected_at', Carbon::now()->year);
    }

    // Method untuk mendapatkan gejala yang digunakan
    public function getGejalaUsed()
    {
        return Gejala::whereIn('id', $this->gejala_ids)->get();
    }

    // Method untuk mendapatkan hasil deteksi
    public function getDetectionResults()
    {
        $hamaPenyakitIds = collect($this->results)->pluck('id')->filter();
        return HamaPenyakit::whereIn('id', $hamaPenyakitIds)->get();
    }
}