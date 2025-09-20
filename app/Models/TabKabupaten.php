<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TabProvinsi;
use App\Models\TabKecamatan;

class TabKabupaten extends Model
{
    use SoftDeletes;

    protected $table = 'tab_kabupaten';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'tab_provinsi_id', 'nama_kabupaten', 'latitude', 'longitude'];

    public function provinsi()
    {
        return $this->belongsTo(TabProvinsi::class, 'tab_provinsi_id', 'id');
    }

    public function kecamatan()
    {
        return $this->hasMany(TabKecamatan::class, 'tab_kabupaten_id', 'id');
    }
}
