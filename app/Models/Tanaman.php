<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tanaman extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'tanaman';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'nama_tanaman',
    ];

    public function laporanDeteksi(): HasMany
    {
        return $this->hasMany(LaporanDeteksi::class);
    }
}