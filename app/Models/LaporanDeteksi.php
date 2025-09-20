<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporanDeteksi extends Model
{
    use HasFactory;

    protected $table = 'laporan_deteksi';

    protected $fillable = [
        'user_id',
        'tanaman_id',
        'org_pen_tan_id',
        'varietas_id',
        'foto_path',
        'deskripsi',
        'status',
        'lokasi',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tanaman(): BelongsTo
    {
        return $this->belongsTo(Tanaman::class);
    }

    public function organisme(): BelongsTo
    {
        return $this->belongsTo(OrgPenTan::class, 'org_pen_tan_id');
    }

    // Tambah: BelongsTo varietas (asumsi VarietasKedelai sebagai example, gunakan polymorph jika varietas beda tabel)
    public function varietas(): BelongsTo
    {
        return $this->belongsTo(VarietasKedelai::class, 'varietas_id'); // Adjust ke model varietas spesifik
    }
}