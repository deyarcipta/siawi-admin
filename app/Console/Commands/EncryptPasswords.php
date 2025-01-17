<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EncryptPasswords extends Command
{
    protected $signature = 'password:encrypt';
    protected $description = 'Encrypt existing passwords in the database';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Ambil semua pengguna dari tabel users (atau tabel lain yang sesuai)
        $siswa = DB::table('siswa')->get();

        foreach ($siswa as $user) {
            // Cek apakah password sudah terenkripsi
            if (!Hash::needsRehash($user->password)) {
                continue;
            }

            // Enkripsi password dan update di database
            $encryptedPassword = Hash::make($user->password);

            DB::table('siswa')
                ->where('id_siswa', $user->id_siswa)
                ->update(['password' => $encryptedPassword]);

            $this->info("Password for user id_siswa {$user->id_siswa} has been encrypted.");
        }

        $this->info('All passwords have been encrypted.');
    }
}
