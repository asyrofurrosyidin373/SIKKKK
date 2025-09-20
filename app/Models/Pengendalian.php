<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pengendalian extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'pengendalian';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'jenis',
        'deskripsi',
    ];

    public function organisme(): BelongsToMany
    {
        return $this->belongsToMany(OrgPenTan::class, 'penyakit_pengendalian', 'pengendalian_id', 'org_pen_tan_id');
    }

public function insektisida()
{
    return $this->belongsToMany(Insektisida::class, 'pengendalian_insektisida_pivot', 'pengendalian_id', 'insektisida_id');
}

}