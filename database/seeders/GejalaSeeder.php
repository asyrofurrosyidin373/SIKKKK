<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GejalaSeeder extends Seeder
{
    public function run()
    {
        DB::table('gejalas')->insert([
            ['id_gejala' => 'G001', 'gejala' => 'Pembusukan di dekat akar', 'daerah' => 'Akar', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G002', 'gejala' => 'Batang layu', 'daerah' => 'Batang', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G003', 'gejala' => 'Terdapat lubang gerekan larva', 'daerah' => 'Batang', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G004', 'gejala' => 'Dua potong melingkar pada batang', 'daerah' => 'Batang', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G005', 'gejala' => 'Kerusakan batang', 'daerah' => 'Batang', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G006', 'gejala' => 'Mengalami pembusukan batang', 'daerah' => 'Batang', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G007', 'gejala' => 'Bercak warna coklat atau hitam pada batang', 'daerah' => 'Batang', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G008', 'gejala' => 'Bercak warna merah pada batang', 'daerah' => 'Batang', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G009', 'gejala' => 'Mengalami kerapuhan batang', 'daerah' => 'Batang', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G010', 'gejala' => 'Mengalami penyusutan batang', 'daerah' => 'Batang', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G011', 'gejala' => 'Bintik putih pada daun pertama atau kedua', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G012', 'gejala' => 'Bintik-bintik pada daun muda', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G013', 'gejala' => 'Seluruh helai daun layu', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G014', 'gejala' => 'Daun berwarna kekuningan', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G015', 'gejala' => 'Daun mengeriput', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G016', 'gejala' => 'Daun tampak hitam', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G017', 'gejala' => 'Urat daun menguning', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G018', 'gejala' => 'Urat daun cekung', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G019', 'gejala' => 'Daun mengkerut', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G020', 'gejala' => 'Daun berwarna keputihan', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G021', 'gejala' => 'Daun berwarna keperakan', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G022', 'gejala' => 'Daun mengalami pengeringan', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G023', 'gejala' => 'Daun habis tersisa tulang daun', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G024', 'gejala' => 'Daun menggulung', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
            ['id_gejala' => 'G025', 'gejala' => 'Kerusakan pada polong muda', 'daerah' => 'Daun', 'jenis_tanaman' => 'Kedelai'],
        ]);
    }
}
