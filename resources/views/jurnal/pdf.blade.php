<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Jurnal Mengajar</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Laporan Jurnal Mengajar</h2>
    <p>Periode: {{ $tanggalAwal }} s/d {{ $tanggalAkhir }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Guru</th>
                <th>Mata Pelajaran</th>
                <th>Jam</th>
                <th>Kelas</th>
                <th>Materi</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jurnal as $i => $j)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $j->tanggal }}</td>
                <td>{{ $j->guru->nama_guru ?? '-' }}</td>
                <td>{{ $j->jadwal->mapel->nama_mapel ?? '-' }}</td>
                <td>{{ $j->jam_awal ?? '-' }} s/d {{ $j->jam_akhir ?? '-'}}</td>
                <td>{{ $j->kelas->nama_kelas ?? '-' }}</td>
                <td>{{ $j->materi }}</td>
                <td>
                  @if($j->foto_kelas && file_exists(public_path('storage/' . str_replace('storage/', '', $j->foto_kelas))))
                      <img src="{{ public_path('storage/' . $j->foto_kelas) }}"
                        alt="Foto {{ $j->materi }}"
                        class="img-thumbnail"
                        style="max-height:80px;">
                  @else
                      <span class="text-muted">Tidak ada foto</span>
                  @endif
              </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
