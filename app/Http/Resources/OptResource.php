<?php

// app/Http/Resources/OptResource.php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OptResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_opt' => $this->nama_opt,
            'jenis' => $this->jenis,
            'gambar' => $this->gambar ? asset('storage/' . $this->gambar) : null,
            'gejala' => $this->whenLoaded('gejala', function() {
                return $this->gejala->map(function($gejala) {
                    return [
                        'id' => $gejala->id,
                        'deskripsi' => $gejala->deskripsi,
                        'bagian_tanaman' => $gejala->bagian_tanaman,
                    ];
                });
            }),
            'pengendalian' => $this->whenLoaded('pengendalian', function() {
                return $this->pengendalian->map(function($pengendalian) {
                    return [
                        'id' => $pengendalian->id,
                        'jenis' => $pengendalian->jenis,
                        'deskripsi' => $pengendalian->deskripsi,
                        'insektisida' => $pengendalian->insektisida ?? []
                    ];
                });
            }),
            'varietas_tahan' => [
                'kedelai' => $this->whenLoaded('varietasKedelai', function() {
                    return $this->varietasKedelai->map(function($varietas) {
                        return [
                            'id' => $varietas->id,
                            'nama_varietas' => $varietas->nama_varietas,
                            'tahun' => $varietas->tahun,
                        ];
                    });
                }),
                'kacang_tanah' => $this->whenLoaded('varietasKacangTanah', function() {
                    return $this->varietasKacangTanah->map(function($varietas) {
                        return [
                            'id' => $varietas->id,
                            'nama_varietas' => $varietas->nama_varietas,
                            'tahun' => $varietas->tahun,
                        ];
                    });
                }),
                'kacang_hijau' => $this->whenLoaded('varietasKacangHijau', function() {
                    return $this->varietasKacangHijau->map(function($varietas) {
                        return [
                            'id' => $varietas->id,
                            'nama_varietas' => $varietas->nama_varietas,
                            'tahun' => $varietas->tahun,
                        ];
                    });
                })
            ],
            'affected_regions' => $this->whenLoaded('komoditasKedelai', function() {
                $regions = collect();
                
                if ($this->komoditasKedelai) {
                    $regions = $regions->concat($this->komoditasKedelai->flatMap(function($kom) {
                        return $kom->kecamatan ?? collect();
                    }));
                }
                if ($this->komoditasKacangTanah) {
                    $regions = $regions->concat($this->komoditasKacangTanah->flatMap(function($kom) {
                        return $kom->kecamatan ?? collect();
                    }));
                }
                if ($this->komoditasKacangHijau) {
                    $regions = $regions->concat($this->komoditasKacangHijau->flatMap(function($kom) {
                        return $kom->kecamatan ?? collect();
                    }));
                }

                return $regions->unique('id')->map(function($kecamatan) {
                    return [
                        'kecamatan' => $kecamatan->nama_kecamatan,
                        'kabupaten' => $kecamatan->kabupaten->nama_kabupaten ?? '',
                        'provinsi' => $kecamatan->kabupaten->provinsi->nama_provinsi ?? '',
                    ];
                });
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
