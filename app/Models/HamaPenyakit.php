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
        'deskripsi',
        'is_active',
        'priority',
        'metadata'
    ];

    protected $casts = [
        'terjangkit' => 'string',
        'is_active' => 'boolean',
        'priority' => 'integer',
        'metadata' => 'array',
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
        return $this->gejala()->whereIn('id_gejala', $gejalaIds)->get();
    }

    // Method untuk check apakah memiliki metode pengendalian
    public function hasControlMethods(): bool
    {
        return !empty($this->kultur_teknis) || 
               !empty($this->fisik_mekanis) || 
               !empty($this->hayati) || 
               !empty($this->kimiawi);
    }

    // Scope untuk filter aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope untuk filter berdasarkan prioritas
    public function scopeByPriority($query, $priority = null)
    {
        if ($priority !== null) {
            return $query->where('priority', $priority);
        }
        return $query->orderBy('priority', 'desc');
    }

    // Method untuk mendapatkan confidence score yang lebih akurat
    public function getAdvancedConfidenceScore(array $gejalaIds): float
    {
        $matchedGejala = $this->gejala()->whereIn('gejala_id', $gejalaIds)->get();
        $totalBobotMatched = $matchedGejala->sum('pivot.bobot');
        $totalBobotPenyakit = $this->gejala()->sum('bobot');
        
        if ($totalBobotPenyakit == 0) {
            return 0;
        }
        
        // Faktor bonus untuk gejala yang sering muncul
        $frequencyBonus = $matchedGejala->avg('frequency') / 100;
        
        // Faktor severity
        $severityFactor = $matchedGejala->avg('severity_score') / 10;
        
        $baseScore = ($totalBobotMatched / $totalBobotPenyakit) * 100;
        $adjustedScore = $baseScore + $frequencyBonus + $severityFactor;
        
        return min(100, max(0, $adjustedScore));
    }

    // Method untuk mendapatkan rekomendasi pengendalian berdasarkan prioritas
    public function getControlRecommendations(): array
    {
        $controls = [];
        
        if (!empty($this->kultur_teknis)) {
            $controls[] = ['type' => 'kultur_teknis', 'method' => $this->kultur_teknis, 'priority' => 1];
        }
        if (!empty($this->fisik_mekanis)) {
            $controls[] = ['type' => 'fisik_mekanis', 'method' => $this->fisik_mekanis, 'priority' => 2];
        }
        if (!empty($this->hayati)) {
            $controls[] = ['type' => 'hayati', 'method' => $this->hayati, 'priority' => 3];
        }
        if (!empty($this->kimiawi)) {
            $controls[] = ['type' => 'kimiawi', 'method' => $this->kimiawi, 'priority' => 4];
        }
        
        return collect($controls)->sortBy('priority')->values()->toArray();
    }
}