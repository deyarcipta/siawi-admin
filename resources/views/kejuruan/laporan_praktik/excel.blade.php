<table>
    <thead>
        <tr>
            <th colspan="8" style="font-size: 14px; font-weight: bold; text-align: center;">
                {{ strtoupper($setting->nama_sekolah ?? 'SMK PUSAT KEUNGGULAN') }}
            </th>
        </tr>
        <tr>
            <th colspan="8" style="font-size: 12px; font-weight: bold; text-align: center;">
                LAPORAN REALISASI PRAKTIK KEJURUAN & PENGGUNAAN LABORATORIUM
            </th>
        </tr>
        <tr>
            <th colspan="8" style="font-size: 10px; text-align: center;">
                Periode: {{ $periode }}
            </th>
        </tr>
        <tr><th colspan="8"></th></tr>
        <tr style="background-color: #e2e8f0; font-weight: bold;">
            <th style="width: 5px; text-align: center; border: 1px solid #000;">No</th>
            <th style="width: 15px; text-align: center; border: 1px solid #000;">Tanggal</th>
            <th style="width: 15px; border: 1px solid #000;">Kelas</th>
            <th style="width: 25px; border: 1px solid #000;">Mata Pelajaran</th>
            <th style="width: 20px; border: 1px solid #000;">Laboratorium</th>
            <th style="width: 25px; border: 1px solid #000;">Guru Instruktur</th>
            <th style="width: 35px; border: 1px solid #000;">Materi / Jobsheet</th>
            <th style="width: 12px; text-align: center; border: 1px solid #000;">Siswa Hadir</th>
            <th style="width: 15px; text-align: center; border: 1px solid #000;">Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($jurnals as $j)
            <tr>
                <td style="text-align: center; border: 1px solid #000;">{{ $loop->iteration }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ $j->tanggal->format('d/m/Y') }}</td>
                <td style="border: 1px solid #000;">{{ $j->jadwalPraktik->kelas->nama_kelas ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $j->jadwalPraktik->mapel->nama_mapel ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $j->jadwalPraktik->lab->nama_lab ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $j->jadwalPraktik->guru->nama_guru ?? '-' }}</td>
                <td style="border: 1px solid #000;">{{ $j->materi }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ $j->jumlah_hadir }}</td>
                <td style="text-align: center; border: 1px solid #000;">{{ ucfirst($j->status_approval) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
