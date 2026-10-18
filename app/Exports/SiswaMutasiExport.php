<?php

namespace App\Exports;

use App\Models\SiswaMutasi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SiswaMutasiExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = SiswaMutasi::with(['kelas', 'jurusan']);

        if (!empty($this->filters['jenis_mutasi'])) {
            $query->where('jenis_mutasi', $this->filters['jenis_mutasi']);
        }

        if (!empty($this->filters['id_jurusan'])) {
            $query->where('id_jurusan', $this->filters['id_jurusan']);
        }

        if (!empty($this->filters['tahun'])) {
            $query->whereYear('tanggal_mutasi', $this->filters['tahun']);
        }

        $mutasi = $query->orderBy('tanggal_mutasi', 'desc')->get();

        return $mutasi->map(function ($item, $index) {
            $namaOrtu = $item->nama_ayah ?: ($item->nama_ibu ?: ($item->nama_wali ?: '-'));
            $hpOrtu = $item->no_hp_ibu ?: ($item->no_hp_ayah ?: ($item->no_hp_wali ?: '-'));

            return [
                'No' => $index + 1,
                'Tanggal Mutasi' => $item->tanggal_mutasi ? $item->tanggal_mutasi->format('d/m/Y') : '-',
                'NIS' => $item->nis ?? '-',
                'NISN' => $item->nisn ?? '-',
                'Nama Siswa' => $item->nama_siswa ?? '-',
                'Jenis Kelamin' => $item->jenis_kelamin == 'L' ? 'Laki-laki' : ($item->jenis_kelamin == 'P' ? 'Perempuan' : ($item->jenis_kelamin ?? '-')),
                'Kelas Terakhir' => $item->kelas_terakhir ?? ($item->kelas->nama_kelas ?? '-'),
                'Jurusan' => $item->jurusan_terakhir ?? ($item->jurusan->nama_jurusan ?? '-'),
                'Jenis Mutasi' => $item->jenis_mutasi ?? '-',
                'Sekolah Tujuan' => $item->sekolah_tujuan ?? '-',
                'Alasan / Keterangan' => $item->alasan_mutasi ?? '-',
                'Dicatat Oleh' => $item->dicatat_oleh ?? '-',
                'No HP Siswa' => $item->no_hp ?? '-',
                'Nama Orang Tua / Wali' => $namaOrtu,
                'No HP Orang Tua / Wali' => $hpOrtu,
                'Alamat' => $item->alamat ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Mutasi',
            'NIS',
            'NISN',
            'Nama Siswa',
            'Jenis Kelamin',
            'Kelas Terakhir',
            'Jurusan',
            'Jenis Mutasi',
            'Sekolah Tujuan',
            'Alasan / Keterangan',
            'Dicatat Oleh',
            'No HP Siswa',
            'Nama Orang Tua / Wali',
            'No HP Orang Tua / Wali',
            'Alamat',
        ];
    }
}
