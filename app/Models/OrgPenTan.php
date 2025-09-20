<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrgPenTan extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'org_pen_tan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_opt',
        'jenis',
        'gambar',
    ];

    public function varietasKedelai(): HasMany
    {
        return $this->hasMany(VarietasKedelai::class);
    }

    public function varietasKacangTanah(): HasMany
    {
        return $this->hasMany(VarietasKacangTanah::class);
    }

    public function varietasKacangHijau(): HasMany
    {
        return $this->hasMany(VarietasKacangHijau::class);
    }

    public function laporanDeteksi(): HasMany
    {
        return $this->hasMany(LaporanDeteksi::class);
    }

    public function komoditasKedelai(): BelongsToMany
    {
        return $this->belongsToMany(KomKedelai::class, 'kedelai_opt_pivot', 'org_pen_tan_id', 'kom_kedelai_id');
    }

    public function komoditasKacangTanah(): BelongsToMany
    {
        return $this->belongsToMany(KomKacangTanah::class, 'kacang_tanah_opt_pivot', 'org_pen_tan_id', 'kom_kacang_tanah_id');
    }

    public function komoditasKacangHijau(): BelongsToMany
    {
        return $this->belongsToMany(KomKacangHijau::class, 'kacang_hijau_opt_pivot', 'org_pen_tan_id', 'kom_kacang_hijau_id');
    }

    public function gejala(): BelongsToMany
    {
        return $this->belongsToMany(Gejala::class, 'penyakit_gejala', 'org_pen_tan_id', 'gejala_id');
    }

    public function pengendalian(): BelongsToMany
    {
        return $this->belongsToMany(Pengendalian::class, 'penyakit_pengendalian', 'org_pen_tan_id', 'pengendalian_id');
    }

    // Tambah untuk kompleksitas deteksi: OPT resisted by varietas (many-to-many)
    public function varietasResistensi(): BelongsToMany
    {
        return $this->belongsToMany(VarietasKedelai::class, 'opt_varietas_pivot', 'org_pen_tan_id', 'varietas_id') // Asumsi general varietas, adjust per komoditas if needed
                    ->withPivot('tingkat_resistensi');
    }
}