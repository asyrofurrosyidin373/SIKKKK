<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\VarietasKedelai;
use App\Models\VarietasKacangTanah;
use App\Models\VarietasKacangHijau;

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
        'jenis_komoditas',
        'provitas',
        'luas_tanam',
        'produktivitas',
        'total_produksi',
        'opt_id',
        'varietas_id',
        'pot_peningkatan_judgement',
        'nilai_potensi',
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
        'provitas' => 'decimal:2',
        'luas_tanam' => 'decimal:2',
        'produktivitas' => 'decimal:2',
        'total_produksi' => 'decimal:2',
        'opt_id' => 'array',
        'varietas_id' => 'array',
        'nilai_potensi' => 'decimal:2',
        'rekomendasi_waktu_tanam_kedelai' => 'array',
        'rekomendasi_waktu_tanam_kacang_tanah' => 'array',
        'rekomendasi_waktu_tanam_kacang_hijau' => 'array',
        'bulan_hujan' => 'array',
        'bulan_kering' => 'array',
    ];

    // ===== RELATIONSHIPS =====
    
    /**
     * Relasi ke Kabupaten
     */
    public function kabupaten()
    {
        return $this->belongsTo(TabKabupaten::class, 'tab_kabupaten_id');
    }

    /**
     * Relasi ke Varietas Kedelai (Many-to-Many via JSON)
     */
    public function varietasKedelai()
    {
        if ($this->jenis_komoditas !== 'kedelai' || !$this->varietas_id) {
            return collect();
        }
        
        $varietasIds = is_array($this->varietas_id) ? $this->varietas_id : json_decode($this->varietas_id, true) ?? [];
        
        if (empty($varietasIds)) {
            return collect();
        }
        
        return VarietasKedelai::whereIn('id', $varietasIds)->get();
    }

    /**
     * Relasi ke Varietas Kacang Tanah (Many-to-Many via JSON)
     */
    public function varietasKacangTanah()
    {
        if ($this->jenis_komoditas !== 'kacang_tanah' || !$this->varietas_id) {
            return collect();
        }
        
        $varietasIds = is_array($this->varietas_id) ? $this->varietas_id : json_decode($this->varietas_id, true) ?? [];
        
        if (empty($varietasIds)) {
            return collect();
        }
        
        return VarietasKacangTanah::whereIn('id', $varietasIds)->get();
    }

    /**
     * Relasi ke Varietas Kacang Hijau (Many-to-Many via JSON)
     */
    public function varietasKacangHijau()
    {
        if ($this->jenis_komoditas !== 'kacang_hijau' || !$this->varietas_id) {
            return collect();
        }
        
        $varietasIds = is_array($this->varietas_id) ? $this->varietas_id : json_decode($this->varietas_id, true) ?? [];
        
        if (empty($varietasIds)) {
            return collect();
        }
        
        return VarietasKacangHijau::whereIn('id', $varietasIds)->get();
    }

    /**
     * Get varietas berdasarkan jenis komoditas
     */
    public function getVarietasAttribute()
    {
        switch ($this->jenis_komoditas) {
            case 'kedelai':
                return $this->varietasKedelai();
            case 'kacang_tanah':
                return $this->varietasKacangTanah();
            case 'kacang_hijau':
                return $this->varietasKacangHijau();
            default:
                return collect();
        }
    }

    // ===== SCOPES =====
    
    /**
     * Scope untuk komoditas kedelai
     */
    public function scopeKedelai($query)
    {
        return $query->where('jenis_komoditas', 'kedelai');
    }

    /**
     * Scope untuk komoditas kacang tanah
     */
    public function scopeKacangTanah($query)
    {
        return $query->where('jenis_komoditas', 'kacang_tanah');
    }

    /**
     * Scope untuk komoditas kacang hijau
     */
    public function scopeKacangHijau($query)
    {
        return $query->where('jenis_komoditas', 'kacang_hijau');
    }

    /**
     * Scope untuk filter berdasarkan kabupaten
     */
    public function scopeByKabupaten($query, $kabupatenId)
    {
        return $query->where('tab_kabupaten_id', $kabupatenId);
    }

    /**
     * Scope untuk data dengan koordinat
     */
    public function scopeWithCoordinates($query)
    {
        return $query->whereNotNull('latitude')->whereNotNull('longitude');
    }

    /**
     * Scope untuk data produksi
     */
    public function scopeWithProduction($query)
    {
        return $query->whereNotNull('luas_tanam')
                    ->whereNotNull('produktivitas')
                    ->whereNotNull('total_produksi');
    }

    /**
     * Scope untuk filter berdasarkan varietas
     */
    public function scopeByVarietas($query, $varietasId)
    {
        return $query->whereJsonContains('varietas_id', (string) $varietasId);
    }

    // ===== BACKWARD COMPATIBILITY RELATIONSHIPS =====
    
    /**
     * Backward compatibility untuk komoditasKedelai
     */
    public function komoditasKedelai()
    {
        if ($this->jenis_komoditas === 'kedelai') {
            return (object) [
                'luas_tanam' => $this->luas_tanam,
                'produktivitas' => $this->produktivitas,
                'total_produksi' => $this->total_produksi,
                'provitas' => $this->provitas
            ];
        }
        return null;
    }
    
    /**
     * Backward compatibility untuk komoditasKacangTanah
     */
    public function komoditasKacangTanah()
    {
        if ($this->jenis_komoditas === 'kacang_tanah') {
            return (object) [
                'luas_tanam' => $this->luas_tanam,
                'produktivitas' => $this->produktivitas,
                'total_produksi' => $this->total_produksi,
                'provitas' => $this->provitas
            ];
        }
        return null;
    }
    
    /**
     * Backward compatibility untuk komoditasKacangHijau
     */
    public function komoditasKacangHijau()
    {
        if ($this->jenis_komoditas === 'kacang_hijau') {
            return (object) [
                'luas_tanam' => $this->luas_tanam,
                'produktivitas' => $this->produktivitas,
                'total_produksi' => $this->total_produksi,
                'provitas' => $this->provitas
            ];
        }
        return null;
    }

    // ===== ACCESSORS & MUTATORS =====
    
    /**
     * Get nama komoditas yang lebih readable
     */
    public function getNamaKomoditasAttribute()
    {
        $names = [
            'kedelai' => 'Kedelai',
            'kacang_tanah' => 'Kacang Tanah',
            'kacang_hijau' => 'Kacang Hijau'
        ];
        
        return $names[$this->jenis_komoditas] ?? $this->jenis_komoditas;
    }

    /**
     * Get nama varietas sebagai string (comma separated)
     */
    public function getNamaVarietasAttribute()
    {
        try {
            $varietas = $this->varietas;
            
            if (!$varietas || $varietas->isEmpty()) {
                return 'Tidak ada varietas';
            }
            
            return $varietas->pluck('nama_varietas')->join(', ');
        } catch (\Exception $e) {
            return 'Tidak ada varietas';
        }
    }

    /**
     * Get nama varietas sebagai array
     */
    public function getNamaVarietasArrayAttribute()
    {
        try {
            $varietas = $this->varietas;
            
            if (!$varietas || $varietas->isEmpty()) {
                return [];
            }
            
            return $varietas->pluck('nama_varietas')->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Get detail varietas dengan informasi lengkap
     */
    public function getDetailVarietasAttribute()
    {
        try {
            $varietas = $this->varietas;
            
            if (!$varietas || $varietas->isEmpty()) {
                return [];
            }
            
            return $varietas->map(function ($v) {
                return [
                    'id' => $v->id,
                    'nama' => $v->nama_varietas ?? 'N/A',
                    'potensi_hasil' => $v->potensi_hasil ?? 'N/A',
                    'rata_hasil' => $v->rata_hasil ?? 'N/A',
                    'umur_masak' => $v->umur_masak ?? 'N/A',
                    'kadar_protein' => $v->kadar_protein ?? 'N/A',
                    'kadar_lemak' => $v->kadar_lemak ?? 'N/A',
                ];
            })->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Get formatted coordinates
     */
    public function getFormattedCoordinatesAttribute()
    {
        if ($this->latitude && $this->longitude) {
            return number_format($this->latitude, 6) . ', ' . number_format($this->longitude, 6);
        }
        return 'Tidak ada koordinat';
    }
    
    /**
     * Get rekomendasi waktu tanam berdasarkan jenis komoditas
     */
    public function getRekomendasiWaktuTanamAttribute()
    {
        switch ($this->jenis_komoditas) {
            case 'kedelai':
                return $this->rekomendasi_waktu_tanam_kedelai ?? [];
            case 'kacang_tanah':
                return $this->rekomendasi_waktu_tanam_kacang_tanah ?? [];
            case 'kacang_hijau':
                return $this->rekomendasi_waktu_tanam_kacang_hijau ?? [];
            default:
                return [];
        }
    }
}
