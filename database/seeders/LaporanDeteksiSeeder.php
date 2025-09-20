<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Tanaman;
use App\Models\OrgPenTan; // Pastikan modelnya OrgPenTan (bukan OrgPenTans)
use App\Models\VarietasKedelai;

class LaporanDeteksiSeeder extends Seeder
{
    public function run()
    {
        // Buat data referensi jika belum ada
        $user = User::firstOrCreate(
            ['email' => 'demo@example.com'],
            [
                'name' => 'Demo User', 
                'password' => Hash::make('password'),
                'email_verified_at' => now()
            ]
        );

        $tanaman = Tanaman::firstOrCreate(
            ['nama_tanaman' => 'Kedelai'],
            ['nama_tanaman' => 'Kedelai']
        );

        $varietas = VarietasKedelai::firstOrCreate(
            ['nama_varietas' => 'Varietas Demo'],
            ['nama_varietas' => 'Varietas Demo']
        );

        // PERBAIKI: Gunakan 'nama_opt' bukan 'nama_organisasi'
        $orgPenTan = OrgPenTan::firstOrCreate(
            ['nama_opt' => 'Organisasi Demo'], // Kolom yang benar: nama_opt
            [
                'nama_opt' => 'Organisasi Demo',
                'jenis' => 'Hama' // Tambahkan field wajib sesuai migration
            ]
        );

        $laporanDeteksi = [
            [
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'tanaman_id' => $tanaman->id,
                'org_pen_tan_id' => null,
                'varietas_id' => $varietas->id,
                'foto_path' => 'storage/app/public/laporan/foto_pending_1.jpg',
                'deskripsi' => 'Daun menguning dan layu pada tanaman kedelai',
                'status' => 'pending',
                'lokasi' => 'Malang, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'tanaman_id' => $tanaman->id,
                'org_pen_tan_id' => $orgPenTan->id,
                'varietas_id' => $varietas->id,
                'foto_path' => 'storage/app/public/laporan/foto_confirmed_1.jpg',
                'deskripsi' => 'Terdapat bercak coklat pada daun akibat penyakit hawar',
                'status' => 'confirmed',
                'lokasi' => 'Malang, Jawa Timur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('laporan_deteksi')->insert($laporanDeteksi);
    }
}