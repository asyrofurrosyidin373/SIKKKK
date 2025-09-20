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