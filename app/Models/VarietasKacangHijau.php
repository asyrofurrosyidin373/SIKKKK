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