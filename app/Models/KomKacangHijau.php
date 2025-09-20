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