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