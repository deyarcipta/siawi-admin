<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Siswa;
use App\Models\OrangTua;
use Illuminate\Support\Facades\Hash;

class GenerateAkunOrangTuaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'siawi:generate-akun-ortu {--default-password=123456 : Password default untuk akun orang tua}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatisasi pembuatan dan sinkronisasi akun orang tua dari data siswa yang ada (Mendukung Multi-Anak)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai sinkronisasi & pembuatan akun orang tua...');

        $defaultPassword = $this->option('default-password') ?: '123456';
        $hashedPassword = Hash::make($defaultPassword);

        $siswaList = Siswa::whereNull('id_orang_tua')->orderBy('id_siswa', 'asc')->get();
        $this->info("Ditemukan {$siswaList->count()} siswa yang belum memiliki tautan akun orang tua.");

        $createdCount = 0;
        $linkedCount = 0;
        $multiChildCount = 0;

        foreach ($siswaList as $siswa) {
            // 1. Ekstrak nomor HP orang tua
            $rawPhone = trim($siswa->no_hp ?? $siswa->no_tlpn ?? '');
            $cleanPhone = preg_replace('/[^0-9]/', '', $rawPhone);

            // Validasi kelayakan nomor telepon (minimal 9 digit)
            $hasValidPhone = strlen($cleanPhone) >= 9 && !in_array($cleanPhone, ['000000000', '123456789']);

            $username = 'ortu_' . trim($siswa->nis);

            // 2. Tentukan nama orang tua
            $namaOrtu = null;
            if (!empty($siswa->nama_ayah) && trim($siswa->nama_ayah) !== '-') {
                $namaOrtu = trim($siswa->nama_ayah);
            } elseif (!empty($siswa->nama_ibu) && trim($siswa->nama_ibu) !== '-') {
                $namaOrtu = trim($siswa->nama_ibu);
            } elseif (!empty($siswa->nama_wali) && trim($siswa->nama_wali) !== '-') {
                $namaOrtu = trim($siswa->nama_wali);
            } else {
                $namaOrtu = 'Wali dari ' . $siswa->nama_siswa;
            }

            // 3. Cek apakah akun Orang Tua sudah ada (Deteksi Multi-Anak via No. HP atau Username)
            $ortu = null;
            if ($hasValidPhone) {
                $ortu = OrangTua::where('no_hp', $cleanPhone)->first();
            }

            if (!$ortu) {
                $ortu = OrangTua::where('username', $username)->first();
            }

            // Sinkronkan juga nomor kontak ke no_hp_ibu dan no_hp_ayah jika masih kosong
            if ($hasValidPhone) {
                if (empty($siswa->no_hp_ibu)) {
                    $siswa->no_hp_ibu = $cleanPhone;
                }
                if (empty($siswa->no_hp_ayah)) {
                    $siswa->no_hp_ayah = $cleanPhone;
                }
            }

            if ($ortu) {
                // Hubungkan anak kedua / saudara kandung ke akun orang tua yang sama
                $siswa->id_orang_tua = $ortu->id_orang_tua;
                $siswa->save();
                $linkedCount++;
                $multiChildCount++;
            } else {
                // Buat akun orang tua baru dengan username terstandarisasi ortu_{nis}
                $ortu = OrangTua::create([
                    'username' => $username,
                    'password' => $defaultPassword,
                    'nama_lengkap' => $namaOrtu,
                    'no_hp' => $hasValidPhone ? $cleanPhone : null,
                    'alamat' => $siswa->alamat ?? null,
                    'status_aktif' => true,
                ]);

                $siswa->id_orang_tua = $ortu->id_orang_tua;
                $siswa->save();

                $createdCount++;
                $linkedCount++;
            }
        }

        $this->info("=================================================");
        $this->info("SINKRONISASI AKUN ORANG TUA SELESAI:");
        $this->info("- Akun Orang Tua baru dibuat : {$createdCount}");
        $this->info("- Siswa berhasil dihubungkan : {$linkedCount}");
        $this->info("- Deteksi Siswa Bersaudara   : {$multiChildCount} anak");
        $this->info("- Password default           : {$defaultPassword}");
        $this->info("=================================================");

        return Command::SUCCESS;
    }
}
