<?php

// app/Http/Resources/KecamatanResource.php
namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KecamatanResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama_kecamatan' => $this->nama_kecamatan,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'ip_lahan' => $this->ip_lahan,
            'kdr_p' => $this->kdr_p,
            'kdr_c' => $this->kdr_c,
            'kdr_k' => $this->kdr_k,
            'ktk' => $this->ktk,
            'kabupaten' => [
                'id' => $this->kabupaten->id,
                'nama_kabupaten' => $this->kabupaten->nama_kabupaten,
                'provinsi' => [
                    'id' => $this->kabupaten->provinsi->id,
                    'nama_provinsi' => $this->kabupaten->provinsi->nama_provinsi,
                ]
            ],
            'komoditas' => [
                'kedelai' => $this->whenLoaded('komoditasKedelai', function() {
                    return [
                        'id' => $this->komoditasKedelai->id,
                        'provitas' => $this->komoditasKedelai->provitas,
                        'nilai_potensi' => $this->komoditasKedelai->nilai_potensi,
                        'varietas' => $this->komoditasKedelai->varietas ?? null
                    ];
                }),
                'kacang_tanah' => $this->whenLoaded('komoditasKacangTanah', function() {
                    return [
                        'id' => $this->komoditasKacangTanah->id,
                        'provitas' => $this->komoditasKacangTanah->provitas,
                        'nilai_potensi' => $this->komoditasKacangTanah->nilai_potensi,
                        'varietas' => $this->komoditasKacangTanah->varietas ?? null
                    ];
                }),
                'kacang_hijau' => $this->whenLoaded('komoditasKacangHijau', function() {
                    return [
                        'id' => $this->komoditasKacangHijau->id,
                        'provitas' => $this->komoditasKacangHijau->provitas,
                        'nilai_potensi' => $this->komoditasKacangHijau->nilai_potensi,
                        'varietas' => $this->komoditasKacangHijau->varietas ?? null
                    ];
                })
            ],
            'cuaca' => [
                'bulan_hujan' => $this->bulan_hujan_nama,
                'bulan_kering' => $this->bulan_kering_nama,
            ],
            'waktu_tanam' => [
                'kedelai' => $this->waktu_tanam_kedelai_nama,
                'kacang_tanah' => $this->waktu_tanam_kacang_tanah_nama,
                'kacang_hijau' => $this->waktu_tanam_kacang_hijau_nama,
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
