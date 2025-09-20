<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PenyakitPengendalian extends Pivot
{
    protected $table = 'penyakit_pengendalian';

    protected $fillable = [
        'org_pen_tan_id',
        'pengendalian_id',
    ];
}