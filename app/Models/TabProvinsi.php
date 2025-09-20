<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\TabKabupaten;

class TabProvinsi extends Model
{
    use SoftDeletes;

    protected $table = 'tab_provinsi';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = ['id', 'nama_provinsi', 'latitude', 'longitude'];

    public function kabupaten()
    {
        return $this->hasMany(TabKabupaten::class, 'tab_provinsi_id', 'id');
    }
}
