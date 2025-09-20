<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PenyakitGejala extends Pivot
{
    protected $table = 'penyakit_gejala';

    protected $fillable = [
        'org_pen_tan_id',
        'gejala_id',
    ];
}
