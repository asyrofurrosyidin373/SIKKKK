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
