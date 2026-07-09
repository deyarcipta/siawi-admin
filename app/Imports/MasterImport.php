<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Level;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. Resolve or Create Jurusan
        $kodeJurusan = $row['kode_jurusan'] ?? $row['nama_jurusan'] ?? null;
        if (!$kodeJurusan) {
            return null; // Skip if no jurusan info
        }

        $jurusan = Jurusan::updateOrCreate(
            ['kode_jurusan' => $kodeJurusan],
            [
                'kode_jurusan' => $kodeJurusan,
                'nama_jurusan' => $row['nama_jurusan'] ?? $kodeJurusan
            ]
        );

        // 2. Resolve or Create Level
        $kodeLevel = $row['kode_level'] ?? null;
        if (!$kodeLevel) {
            return null; // Skip if no level info
        }

        $level = Level::updateOrCreate(
            ['kode_level' => $kodeLevel],
            [
                'kode_level' => $kodeLevel,
                'nama_level' => $kodeLevel
            ]
        );

        // 3. Resolve or Create Kelas
        $idKelas = $row['id_kelas'] ?? null;
        $namaKelas = $row['nama_kelas'] ?? null;
        if (!$namaKelas && !$idKelas) {
            return null; // Skip if no class info
        }

        $kelasSearch = [];
        if ($idKelas) {
            $kelasSearch = ['id_kelas' => $idKelas];
        } else {
            $kelasSearch = ['nama_kelas' => $namaKelas];
        }

        $kelas = Kelas::updateOrCreate(
            $kelasSearch,
            [
                'kode_kelas' => $row['kode_kelas'] ?? $namaKelas,
                'kode_level' => $level->kode_level,
                'kode_jurusan' => $jurusan->kode_jurusan,
                'nama_kelas' => $namaKelas
            ]
        );

        // 4. Update or Create Siswa
        if (empty($row['nis'])) {
            return null; // Skip if no NIS
        }

        return Siswa::updateOrCreate(
            ['nis' => $row['nis']],
            [
                'nisn' => $row['nisn'] ?? '-',
                'nama_siswa' => $row['nama'] ?? '-',
                'id_kelas' => $kelas->id_kelas,
                'id_jurusan' => $jurusan->id_jurusan,
                'id_level' => $level->id_level,
                'foto' => 'avatar.jpg',
                'tmpt_lahir' => '-',
                'tgl_lahir' => '-',
                'agama' => '-',
                'jenis_kelamin' => '-',
                'no_hp' => '-',
                'no_tlpn' => '-',
                'email' => '-',
                'alamat' => '-',
                'rt' => '-',
                'rw' => '-',
                'no_rumah' => '-',
                'kel' => '-',
                'kec' => '-',
                'prov' => '-',
                'kota' => '-',
                'password' => 'siswa123',// Atur default password
                'nik_ayah' => '-',
                'nama_ayah' => '-',
                'tmpt_lahir_ayah' => '-',
                'tgl_lahir_ayah' => '-',
                'pendidikan_ayah' => '-',
                'pekerjaan_ayah' => '-',
                'penghasilan_ayah' => '-',
                'nik_ibu' => '-',
                'nama_ibu' => '-',
                'tmpt_lahir_ibu' => '-',
                'tgl_lahir_ibu' => '-',
                'pendidikan_ibu' => '-',
                'pekerjaan_ibu' => '-',
                'penghasilan_ibu' => '-',
                'nik_wali' => '-',
                'nama_wali' => '-',
                'tmpt_lahir_wali' => '-',
                'tgl_lahir_wali' => '-',
                'pendidikan_wali' => '-',
                'pekerjaan_wali' => '-',
                'penghasilan_wali' => '-',
            ]
        );
    }
}
