<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabBulan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tab_bulan';

    protected $fillable = [
        'nama_bulan',
        'angka_bulan',
    ];

    protected $casts = [
        'angka_bulan' => 'integer',
    ];

    // Relasi many-to-many dengan kecamatan melalui pivot
    public function kecamatans()
    {
        return $this->belongsToMany(
            TabKecamatan::class,
            'kecamatan_bulan_pivot',
            'tab_bulan_id',
            'tab_kecamatan_id'
        )->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Relasi spesifik untuk bulan hujan
    public function kecamatanBulanHujan()
    {
        return $this->belongsToMany(
            TabKecamatan::class,
            'kecamatan_bulan_pivot',
            'tab_bulan_id',
            'tab_kecamatan_id'
        )->wherePivot('tipe', 'hujan')
          ->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }

    // Relasi spesifik untuk waktu tanam kedelai
    public function kecamatanTanamKedelai()
    {
        return $this->belongsToMany(
            TabKecamatan::class,
            'kecamatan_bulan_pivot',
            'tab_bulan_id',
            'tab_kecamatan_id'
        )->wherePivot('tipe', 'tanam_kedelai')
          ->withPivot('tipe', 'keterangan')
          ->withTimestamps();
    }
}