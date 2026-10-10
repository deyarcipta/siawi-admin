<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\Jurusan;
use App\Models\Level;
use App\Models\OrangTua;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class MasterImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. Resolve or Create Jurusan
        $kodeJurusan = isset($row['kode_jurusan']) ? trim((string)$row['kode_jurusan']) : null;
        $namaJurusan = isset($row['nama_jurusan']) ? trim((string)$row['nama_jurusan']) : null;
        $jurusanKey = $kodeJurusan ?: $namaJurusan;

        if (!$jurusanKey) {
            return null; // Skip jika tidak ada informasi jurusan
        }

        $jurusan = Jurusan::updateOrCreate(
            ['kode_jurusan' => $jurusanKey],
            [
                'kode_jurusan' => $jurusanKey,
                'nama_jurusan' => $namaJurusan ?: $jurusanKey
            ]
        );

        // 2. Resolve or Create Level (Tingkat/Kelas: X, XI, XII)
        $kodeLevel = isset($row['kode_level']) ? trim((string)$row['kode_level']) : null;
        if (!$kodeLevel) {
            return null; // Skip jika tidak ada informasi tingkat
        }

        $level = Level::updateOrCreate(
            ['kode_level' => $kodeLevel],
            [
                'kode_level' => $kodeLevel,
                'nama_level' => $kodeLevel
            ]
        );

        // 3. Resolve or Create Kelas
        $namaKelas = isset($row['nama_kelas']) ? trim((string)$row['nama_kelas']) : null;
        $idKelas = isset($row['id_kelas']) ? $row['id_kelas'] : null;

        if (!$namaKelas && !$idKelas) {
            return null; // Skip jika tidak ada kelas
        }

        $kelasSearch = $idKelas ? ['id_kelas' => $idKelas] : ['nama_kelas' => $namaKelas];

        $kelas = Kelas::updateOrCreate(
            $kelasSearch,
            [
                'kode_kelas' => isset($row['kode_kelas']) && !empty($row['kode_kelas']) ? trim((string)$row['kode_kelas']) : ($namaKelas ?: 'KLS'),
                'kode_level' => $level->kode_level,
                'kode_jurusan' => $jurusan->kode_jurusan,
                'nama_kelas' => $namaKelas ?: ($row['kode_kelas'] ?? 'Kelas')
            ]
        );

        // 4. Periksa NIS Siswa (Kunci Utama)
        $nis = isset($row['nis']) ? trim((string)$row['nis']) : null;
        if (empty($nis)) {
            return null; // Skip jika NIS kosong
        }

        // Cek data siswa yang sudah ada jika ada
        $existingSiswa = Siswa::where('nis', $nis)->first();

        $foto = ($existingSiswa && !empty($existingSiswa->foto)) ? $existingSiswa->foto : 'avatar.jpg';
        $password = ($existingSiswa && !empty($existingSiswa->password)) ? $existingSiswa->password : 'siswa123';

        // Format No. HP
        $noHpSiswa = $this->formatPhone($row['no_hp'] ?? $row['no_hp_siswa'] ?? null);
        $noHpIbu   = $this->formatPhone($row['no_hp_ibu'] ?? null);
        $noHpAyah  = $this->formatPhone($row['no_hp_ayah'] ?? null);
        $noHpWali  = $this->formatPhone($row['no_hp_wali'] ?? null);

        // Format Tanggal Lahir
        $tglLahir = $this->transformDate($row['tgl_lahir'] ?? null);

        // Jenis Kelamin normalisasi (L / P)
        $jkRaw = strtoupper(trim((string)($row['jenis_kelamin'] ?? $row['jk'] ?? '')));
        $jenisKelamin = '-';
        if (str_starts_with($jkRaw, 'L') || $jkRaw === 'LAKI-LAKI') {
            $jenisKelamin = 'L';
        } elseif (str_starts_with($jkRaw, 'P') || $jkRaw === 'PEREMPUAN') {
            $jenisKelamin = 'P';
        }

        $dataSiswa = [
            'nisn' => isset($row['nisn']) && !empty($row['nisn']) ? trim((string)$row['nisn']) : ($existingSiswa->nisn ?? '-'),
            'nama_siswa' => isset($row['nama']) && !empty($row['nama']) 
                ? trim((string)$row['nama']) 
                : (isset($row['nama_siswa']) && !empty($row['nama_siswa']) ? trim((string)$row['nama_siswa']) : ($existingSiswa->nama_siswa ?? '-')),
            'id_kelas' => $kelas->id_kelas,
            'id_jurusan' => $jurusan->id_jurusan,
            'id_level' => $level->id_level,
            'foto' => $foto,
            'password' => $password,
            'tmpt_lahir' => $this->valOrDefault($row['tmpt_lahir'] ?? null, $existingSiswa->tmpt_lahir ?? '-'),
            'tgl_lahir' => $tglLahir ?: ($existingSiswa->tgl_lahir ?? '-'),
            'agama' => $this->valOrDefault($row['agama'] ?? null, $existingSiswa->agama ?? '-'),
            'jenis_kelamin' => $jenisKelamin !== '-' ? $jenisKelamin : ($existingSiswa->jenis_kelamin ?? '-'),
            'no_hp' => $noHpSiswa ?: ($existingSiswa->no_hp ?? '-'),
            'no_tlpn' => $this->valOrDefault($row['no_tlpn'] ?? null, $existingSiswa->no_tlpn ?? '-'),
            'email' => $this->valOrDefault($row['email'] ?? null, $existingSiswa->email ?? '-'),
            'alamat' => $this->valOrDefault($row['alamat'] ?? null, $existingSiswa->alamat ?? '-'),
            'rt' => $this->valOrDefault($row['rt'] ?? null, $existingSiswa->rt ?? '-'),
            'rw' => $this->valOrDefault($row['rw'] ?? null, $existingSiswa->rw ?? '-'),
            'no_rumah' => $this->valOrDefault($row['no_rumah'] ?? null, $existingSiswa->no_rumah ?? '-'),
            'kel' => $this->valOrDefault($row['kel'] ?? $row['kelurahan'] ?? null, $existingSiswa->kel ?? '-'),
            'kec' => $this->valOrDefault($row['kec'] ?? $row['kecamatan'] ?? null, $existingSiswa->kec ?? '-'),
            'kota' => $this->valOrDefault($row['kota'] ?? null, $existingSiswa->kota ?? '-'),
            'prov' => $this->valOrDefault($row['prov'] ?? $row['provinsi'] ?? null, $existingSiswa->prov ?? '-'),

            // Data Ibu
            'nik_ibu' => $this->valOrDefault($row['nik_ibu'] ?? null, $existingSiswa->nik_ibu ?? '-'),
            'nama_ibu' => $this->valOrDefault($row['nama_ibu'] ?? null, $existingSiswa->nama_ibu ?? '-'),
            'no_hp_ibu' => $noHpIbu ?: ($existingSiswa->no_hp_ibu ?? null),
            'tmpt_lahir_ibu' => $this->valOrDefault($row['tmpt_lahir_ibu'] ?? null, $existingSiswa->tmpt_lahir_ibu ?? '-'),
            'tgl_lahir_ibu' => $this->transformDate($row['tgl_lahir_ibu'] ?? null) ?: ($existingSiswa->tgl_lahir_ibu ?? '-'),
            'pendidikan_ibu' => $this->valOrDefault($row['pendidikan_ibu'] ?? null, $existingSiswa->pendidikan_ibu ?? '-'),
            'pekerjaan_ibu' => $this->valOrDefault($row['pekerjaan_ibu'] ?? null, $existingSiswa->pekerjaan_ibu ?? '-'),
            'penghasilan_ibu' => $this->valOrDefault($row['penghasilan_ibu'] ?? null, $existingSiswa->penghasilan_ibu ?? '-'),

            // Data Ayah
            'nik_ayah' => $this->valOrDefault($row['nik_ayah'] ?? null, $existingSiswa->nik_ayah ?? '-'),
            'nama_ayah' => $this->valOrDefault($row['nama_ayah'] ?? null, $existingSiswa->nama_ayah ?? '-'),
            'no_hp_ayah' => $noHpAyah ?: ($existingSiswa->no_hp_ayah ?? null),
            'tmpt_lahir_ayah' => $this->valOrDefault($row['tmpt_lahir_ayah'] ?? null, $existingSiswa->tmpt_lahir_ayah ?? '-'),
            'tgl_lahir_ayah' => $this->transformDate($row['tgl_lahir_ayah'] ?? null) ?: ($existingSiswa->tgl_lahir_ayah ?? '-'),
            'pendidikan_ayah' => $this->valOrDefault($row['pendidikan_ayah'] ?? null, $existingSiswa->pendidikan_ayah ?? '-'),
            'pekerjaan_ayah' => $this->valOrDefault($row['pekerjaan_ayah'] ?? null, $existingSiswa->pekerjaan_ayah ?? '-'),
            'penghasilan_ayah' => $this->valOrDefault($row['penghasilan_ayah'] ?? null, $existingSiswa->penghasilan_ayah ?? '-'),

            // Data Wali
            'nik_wali' => $this->valOrDefault($row['nik_wali'] ?? null, $existingSiswa->nik_wali ?? '-'),
            'nama_wali' => $this->valOrDefault($row['nama_wali'] ?? null, $existingSiswa->nama_wali ?? '-'),
            'no_hp_wali' => $noHpWali ?: ($existingSiswa->no_hp_wali ?? null),
            'tmpt_lahir_wali' => $this->valOrDefault($row['tmpt_lahir_wali'] ?? null, $existingSiswa->tmpt_lahir_wali ?? '-'),
            'tgl_lahir_wali' => $this->transformDate($row['tgl_lahir_wali'] ?? null) ?: ($existingSiswa->tgl_lahir_wali ?? '-'),
            'pendidikan_wali' => $this->valOrDefault($row['pendidikan_wali'] ?? null, $existingSiswa->pendidikan_wali ?? '-'),
            'pekerjaan_wali' => $this->valOrDefault($row['pekerjaan_wali'] ?? null, $existingSiswa->pekerjaan_wali ?? '-'),
            'penghasilan_wali' => $this->valOrDefault($row['penghasilan_wali'] ?? null, $existingSiswa->penghasilan_wali ?? '-'),
        ];

        if ($existingSiswa) {
            $existingSiswa->update($dataSiswa);
            $siswa = $existingSiswa;
        } else {
            $siswa = Siswa::create(array_merge(['nis' => $nis], $dataSiswa));
        }

        // Buat atau sinkronkan akun login orang tua secara otomatis (ortu_{nis} dan multi-anak)
        try {
            OrangTua::createOrLinkForSiswa($siswa);
        } catch (\Throwable $e) {
            // Jangan gagalkan seluruh proses impor jika penautan akun ortu menemui kendala minor
        }

        return $siswa;
    }

    /**
     * Helper untuk mengambil nilai string atau fallback ke default
     */
    private function valOrDefault($value, $default = '-')
    {
        if ($value === null) {
            return $default;
        }
        $str = trim((string)$value);
        return $str !== '' ? $str : $default;
    }

    /**
     * Helper normalisasi nomor HP ke format standar Indonesia (08...)
     */
    private function formatPhone($value): ?string
    {
        if ($value === null || $value === '' || $value === '-') {
            return null;
        }

        $clean = preg_replace('/[^0-9]/', '', (string)$value);
        if (empty($clean)) {
            return null;
        }

        if (str_starts_with($clean, '628')) {
            $clean = '0' . substr($clean, 2);
        } elseif (str_starts_with($clean, '8')) {
            $clean = '0' . $clean;
        }

        return strlen($clean) >= 9 ? $clean : null;
    }

    /**
     * Helper mengubah format tanggal Excel (serial atau string) ke format Y-m-d
     */
    private function transformDate($value): ?string
    {
        if ($value === null || $value === '' || $value === '-') {
            return null;
        }

        if (is_numeric($value)) {
            try {
                return ExcelDate::excelToDateTimeObject($value)->format('Y-m-d');
            } catch (\Exception $e) {
                // Lewati jika bukan serial excel yang valid
            }
        }

        $clean = trim((string)$value);
        $timestamp = strtotime($clean);
        if ($timestamp !== false && $timestamp > 0) {
            return date('Y-m-d', $timestamp);
        }

        return $clean;
    }
}
