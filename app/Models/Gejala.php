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
        'jenis_tanaman',
        'is_active',
        'frequency',
        'severity_score'
    ];

    protected $casts = [
        'daerah' => 'string',
        'is_active' => 'boolean',
        'frequency' => 'integer',
        'severity_score' => 'decimal:2',
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

    // Scope untuk filter aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk filter berdasarkan frekuensi
    public function scopeByFrequency($query, $minFrequency = 0)
    {
        return $query->where('frequency', '>=', $minFrequency);
    }

    // Scope untuk filter berdasarkan severity
    public function scopeBySeverity($query, $minSeverity = 0)
    {
        return $query->where('severity_score', '>=', $minSeverity);
    }

    // Method untuk mendapatkan tingkat keparahan
    public function getSeverityLevelAttribute(): string
    {
        return match(true) {
            $this->severity_score >= 8 => 'Sangat Parah',
            $this->severity_score >= 6 => 'Parah',
            $this->severity_score >= 4 => 'Sedang',
            $this->severity_score >= 2 => 'Ringan',
            default => 'Sangat Ringan'
        };
    }

    // Method untuk mendapatkan warna severity
    public function getSeverityColorAttribute(): string
    {
        return match(true) {
            $this->severity_score >= 8 => 'text-danger',
            $this->severity_score >= 6 => 'text-warning',
            $this->severity_score >= 4 => 'text-info',
            $this->severity_score >= 2 => 'text-primary',
            default => 'text-success'
        };
    }
}
