<?php

// TanamanSeeder.php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tanaman;
use Illuminate\Support\Str;

class TanamanSeeder extends Seeder
{
    public function run()
    {
        $tanamans = [
            ['id' => Str::uuid(), 'nama_tanaman' => 'Kedelai'],
            ['id' => Str::uuid(), 'nama_tanaman' => 'Kacang Tanah'],
            ['id' => Str::uuid(), 'nama_tanaman' => 'Kacang Hijau'],
        ];

        foreach ($tanamans as $tanaman) {
            Tanaman::create($tanaman);
        }
    }
}