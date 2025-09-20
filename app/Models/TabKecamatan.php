<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabKecamatan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tab_kecamatan';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id',
        'tab_kabupaten_id',
        'nama_kecamatan',
        'latitude',
        'longitude',
        'ip_lahan',
        'kdr_p',
        'kdr_c',
        'kdr_k',
        'ktk',
        'kom_kedelai_id',
        'kom_kacang_tanah_id',
        'kom_kacang_hijau_id',
        'rekomendasi_waktu_tanam_kedelai',
        'rekomendasi_waktu_tanam_kacang_tanah',
        'rekomendasi_waktu_tanam_kacang_hijau',
        'bulan_hujan',
        'bulan_kering',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'ip_lahan' => 'decimal:2',
        'kdr_p' => 'decimal:2',
        'kdr_c' => 'decimal:2',
        'kdr_k' => 'decimal:2',
        'ktk' => 'decimal:2',
        'rekomendasi_waktu_tanam_kedelai' => 'array',
        'rekomendasi_waktu_tanam_kacang_tanah' => 'array',
        'rekomendasi_waktu_tanam_kacang_hijau' => 'array',
        'bulan_hujan' => 'array',
        'bulan_kering' => 'array',
    ];

    // Relasi ke kabupaten
    public function kabupaten()
    {
        return $this->belongsTo(TabKabupaten::class, 'tab_kabupaten_id');
    }

    public function komoditasKedelai()
    {
        return $this->belongsTo(KomKedelai::class, 'kom_kedelai_id');
    }

    public function komoditasKacangTanah()
    {
        return $this->belongsTo(KomKacangTanah::class, 'kom_kacang_tanah_id');
    }

    public function komoditasKacangHijau()
    {
        return $this->belongsTo(KomKacangHijau::class, 'kom_kacang_hijau_id');
    }

    // Relasi many-to-many dengan bulan melalui pivot
    public function bulans()
    {
        return $this->belongsToMany(
            TabBulan::class,
            'kecamatan_bulan_pivot',
            'tab_kecamatan_id',
            'tab_bulan_id'
        )->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Relasi spesifik untuk bulan hujan
    public function bulanHujan()
    {
        return $this->belongsToMany(
            TabBulan::class,
            'kecamatan_bulan_pivot',
            'tab_kecamatan_id',
            'tab_bulan_id'
        )->wherePivot('tipe', 'hujan')
          ->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Relasi spesifik untuk bulan kering
    public function bulanKering()
    {
        return $this->belongsToMany(
            TabBulan::class,
            'kecamatan_bulan_pivot',
            'tab_kecamatan_id',
            'tab_bulan_id'
        )->wherePivot('tipe', 'kering')
          ->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Relasi untuk waktu tanam kedelai
    public function waktuTanamKedelai()
    {
        return $this->belongsToMany(
            TabBulan::class,
            'kecamatan_bulan_pivot',
            'tab_kecamatan_id',
            'tab_bulan_id'
        )->wherePivot('tipe', 'tanam_kedelai')
          ->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Relasi untuk waktu tanam kacang tanah
    public function waktuTanamKacangTanah()
    {
        return $this->belongsToMany(
            TabBulan::class,
            'kecamatan_bulan_pivot',
            'tab_kecamatan_id',
            'tab_bulan_id'
        )->wherePivot('tipe', 'tanam_kacang_tanah')
          ->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Relasi untuk waktu tanam kacang hijau
    public function waktuTanamKacangHijau()
    {
        return $this->belongsToMany(
            TabBulan::class,
            'kecamatan_bulan_pivot',
            'tab_kecamatan_id',
            'tab_bulan_id'
        )->wherePivot('tipe', 'tanam_kacang_hijau')
          ->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Accessor untuk mendapatkan nama bulan hujan dari JSON
    public function getBulanHujanNamaAttribute()
    {
        if (empty($this->bulan_hujan)) {
            return [];
        }
        
        return TabBulan::whereIn('id', $this->bulan_hujan)
            ->pluck('nama_bulan')
            ->toArray();
    }

    // Accessor untuk mendapatkan nama bulan tanam kedelai dari JSON
    public function getWaktuTanamKedelaiNamaAttribute()
    {
        if (empty($this->rekomendasi_waktu_tanam_kedelai)) {
            return [];
        }
        
        return TabBulan::whereIn('id', $this->rekomendasi_waktu_tanam_kedelai)
            ->pluck('nama_bulan')
            ->toArray();
    }

    // Accessor untuk mendapatkan nama bulan tanam kacang tanah dari JSON
    public function getWaktuTanamKacangTanahNamaAttribute()
    {
        if (empty($this->rekomendasi_waktu_tanam_kacang_tanah)) {
            return [];
        }
        
        return TabBulan::whereIn('id', $this->rekomendasi_waktu_tanam_kacang_tanah)
            ->pluck('nama_bulan')
            ->toArray();
    }

    // Accessor untuk mendapatkan nama bulan tanam kacang hijau dari JSON
    public function getWaktuTanamKacangHijauNamaAttribute()
    {
        if (empty($this->rekomendasi_waktu_tanam_kacang_hijau)) {
            return [];
        }
        
        return TabBulan::whereIn('id', $this->rekomendasi_waktu_tanam_kacang_hijau)
            ->pluck('nama_bulan')
            ->toArray();
    }

    // Method untuk sync bulan hujan via pivot table
    public function syncBulanHujan(array $bulanIds, array $keterangan = [])
    {
        $syncData = [];
        foreach ($bulanIds as $index => $bulanId) {
            $syncData[$bulanId] = [
                'tipe' => 'hujan',
                'keterangan' => $keterangan[$index] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        $this->bulans()->wherePivot('tipe', 'hujan')->detach();
        $this->bulans()->attach($syncData);
    }

    // Method untuk sync waktu tanam kedelai via pivot table
    public function syncWaktuTanamKedelai(array $bulanIds, array $keterangan = [])
    {
        $syncData = [];
        foreach ($bulanIds as $index => $bulanId) {
            $syncData[$bulanId] = [
                'tipe' => 'tanam_kedelai',
                'keterangan' => $keterangan[$index] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }
        
        $this->bulans()->wherePivot('tipe', 'tanam_kedelai')->detach();
        $this->bulans()->attach($syncData);
    }
}