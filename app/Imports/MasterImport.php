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
        // Simpan data ke tabel jurusan
        $jurusan = Jurusan::updateOrCreate(
            ['kode_jurusan' => $row['kode_jurusan']], // Kondisi pencarian
            [
                'kode_jurusan' => $row['nama_jurusan'],
                'nama_jurusan' => $row['nama_jurusan']
            ]
        );

        // Simpan data ke tabel level
        $level = Level::updateOrCreate(
            ['kode_level' => $row['kode_level']],
            [
                'nama_level' => $row['kode_level'],
                'kode_level' => $row['kode_level']
            ]
            
        );

        // Simpan data ke tabel kelas
        $kelas = Kelas::updateOrCreate(
            ['id_kelas' => $row['id_kelas']], // Kondisi pencarian
            [
                'kode_kelas' => $row['nama_kelas'],
                'kode_level' => $row['kode_level'],
                'kode_jurusan' => $row['nama_jurusan'],
                'nama_kelas' => $row['nama_kelas']
            ] // Data yang diperbarui atau dibuat
        );

        // Simpan data ke tabel siswa
        Siswa::updateOrCreate(
            ['nis' => $row['nis']],
            [
                'nisn' => $row['nisn'],
                'nama_siswa' => $row['nama'],
                'id_kelas' => $row['id_kelas'],
                'id_kelas' => $row['id_kelas'],
                'id_jurusan' => $row['id_jurusan'],
                'id_level' => $row['id_level'],
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
