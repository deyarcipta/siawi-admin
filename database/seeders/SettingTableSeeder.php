<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('setting')->insert([
            'nama_app' => 'SIAWI APP',
            'nama_sekolah' => 'SMK Wisata Indonesia',
            'nama_kepsek' => 'H. Abdul Munir, HMA, M.Pd.',
            'nip_kepsek' => '-',
            'alamat' => 'JL. Raya Lenteng Agung / Jl. Langgar RT 009/03 No. 1 Kode Pos 12520',
            'kel' => 'Kebagusan',
            'kec' => 'Pasar Minggu',
            'prov' => 'DKI Jakarta',
            'kota' => 'Jakarta Selatan',
            'logo' => 'logo-wi.png',
        ]);
    }
}
