<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuruTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('setting')->insert([
            'username' => 'deyarcipta',
            'password' => 'admin123',
            'nama_guru' => 'Deyar Cipta Rizky',
            'role' => 'admin',
        ]);
    }
}
