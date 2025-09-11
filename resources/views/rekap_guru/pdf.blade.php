<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Kehadiran Mengajar Guru</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background: #ddd; }
        h3 { margin: 0; text-align: center; }
    </style>
</head>
<body>
    <h3>Rekap Kehadiran Guru</h3>
    <p style="text-align:center;">
        Periode: {{ $tanggalAwal }} s/d {{ $tanggalAkhir }}
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Guru</th>
                <th>Mata Pelajaran</th>
                <th>Kelas</th>
                <th>Tanggal</th>
                <th>Tanggal Isi Jurnal</th>
                <th>Hari</th>
                {{-- <th>Jam Jadwal</th> --}}
                <th>Jam Absen</th>
                <th>Jam Jurnal</th>
                <th>Materi Jurnal</th>
                <th>Status Absensi</th>
                <th>Status Jurnal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td>{{ $row['no'] }}</td>
                <td>{{ $row['nama_guru'] }}</td>
                <td>{{ $row['mapel'] }}</td>
                <td>{{ $row['kelas'] }}</td>
                <td>{{ $row['tanggal'] }}</td>
                <td>
                    {{-- ✅ Format created_at --}}
                    {{ \Carbon\Carbon::parse($row['created_at'])->format('d-m-Y H:i') }}
                </td>
                <td>{{ $row['hari'] }}</td>
                {{-- <td>{{ $row['jam_jadwal'] }}</td> --}}
                <td>{{ $row['jam_absen'] }}</td>
                <td>{{ $row['jam_jurnal'] }}</td>
                <td>{{ $row['jurnal'] }}</td>
                <td>{{ $row['status_absensi'] }}</td>
                <td>{{ $row['status_jurnal'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
