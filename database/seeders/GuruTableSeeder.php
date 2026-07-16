<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('guru')->insert([
            'username' => 'deyarcipta',
            'password' => Hash::make('admin123'),
            'nama_guru' => 'Deyar Cipta Rizky',
            'role' => 'admin',
        ]);
    }
}
