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