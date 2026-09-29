<?php

namespace App\Exports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AlumniExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function collection()
    {
        $alumni = Alumni::with(['jurusan'])->orderBy('tahun_lulus', 'desc')->orderBy('nama', 'asc')->get();

        return $alumni->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'NIS' => $item->nis ?? '-',
                'NISN' => $item->nisn ?? '-',
                'Nama' => $item->nama ?? '-',
                'Jenis Kelamin' => $item->jenis_kelamin == 'L' ? 'Laki-laki' : ($item->jenis_kelamin == 'P' ? 'Perempuan' : ($item->jenis_kelamin ?? '-')),
                'Jurusan' => $item->jurusan->nama_jurusan ?? '-',
                'Tahun Lulus' => $item->tahun_lulus ?? '-',
                'Status' => $item->status ?? '-',
                'Tempat Lahir' => $item->tempat_lahir ?? '-',
                'Tanggal Lahir' => $item->tanggal_lahir ?? '-',
                'No HP' => $item->no_hp ?? '-',
                'Email' => $item->email ?? '-',
                'Alamat' => $item->alamat ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'NIS',
            'NISN',
            'Nama Alumni',
            'Jenis Kelamin',
            'Jurusan',
            'Tahun Lulus',
            'Status Saat Ini',
            'Tempat Lahir',
            'Tanggal Lahir',
            'No HP / WhatsApp',
            'Email',
            'Alamat',
        ];
    }
}
