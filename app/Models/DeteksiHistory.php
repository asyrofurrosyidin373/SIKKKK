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
        'detected_at',
        'session_id',
        'confidence_score',
        'detection_metadata',
        'is_verified'
    ];

    protected $casts = [
        'gejala_ids' => 'array',
        'results' => 'array',
        'detected_at' => 'datetime',
        'confidence_score' => 'decimal:2',
        'detection_metadata' => 'array',
        'is_verified' => 'boolean'
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

    // Scope untuk filter berdasarkan confidence score
    public function scopeByConfidence($query, $minConfidence = 0)
    {
        return $query->where('confidence_score', '>=', $minConfidence);
    }

    // Scope untuk filter berdasarkan verifikasi
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    // Scope untuk filter berdasarkan session
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    // Method untuk mendapatkan tingkat kepercayaan
    public function getConfidenceLevelAttribute(): string
    {
        return match(true) {
            $this->confidence_score >= 90 => 'Sangat Tinggi',
            $this->confidence_score >= 75 => 'Tinggi',
            $this->confidence_score >= 60 => 'Sedang',
            $this->confidence_score >= 40 => 'Rendah',
            default => 'Sangat Rendah'
        };
    }

    // Method untuk mendapatkan warna confidence
    public function getConfidenceColorAttribute(): string
    {
        return match(true) {
            $this->confidence_score >= 90 => 'text-success',
            $this->confidence_score >= 75 => 'text-primary',
            $this->confidence_score >= 60 => 'text-info',
            $this->confidence_score >= 40 => 'text-warning',
            default => 'text-danger'
        };
    }

    // Method untuk mendapatkan statistik deteksi
    public static function getDetectionStats($days = 30)
    {
        $startDate = Carbon::now()->subDays($days);
        
        return [
            'total_detections' => self::where('detected_at', '>=', $startDate)->count(),
            'high_confidence' => self::where('detected_at', '>=', $startDate)
                ->where('confidence_score', '>=', 75)->count(),
            'verified_detections' => self::where('detected_at', '>=', $startDate)
                ->where('is_verified', true)->count(),
            'avg_confidence' => self::where('detected_at', '>=', $startDate)
                ->avg('confidence_score'),
            'top_gejala' => self::where('detected_at', '>=', $startDate)
                ->get()
                ->pluck('gejala_ids')
                ->flatten()
                ->countBy()
                ->sortDesc()
                ->take(10)
        ];
    }
}