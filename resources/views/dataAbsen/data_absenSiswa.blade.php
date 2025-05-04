@extends($layout)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Absensi Siswa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Data Absensi Siswa</li>
                </ol>
            </div>
        </div>
    </div>
</div>

@if(isset($siswa))
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">
                        <h3 class="card-title">Data Absensi</h3>
                        <a href="/admin/absensi/download?kelas={{ $kelasId }}&tanggal_awal={{ $tanggal_awal }}&tanggal_akhir={{ $tanggal_akhir }}" class="btn btn-success ml-auto">Unduh Data</a>
                    </div>
                    <div class="card-body">
                        <table style="font-size: 18px;">
                          <tr>
                            <td style="font-weight:bold" width="80">Kelas</td>
                            <td width="10">:</td>
                            <td>{{ $dataKelas->nama_kelas }}</td>
                          </tr>
                          <tr>
                            <td style="font-weight:bold">Tanggal</td>
                            <td>:</td>
                            <td>{{ $tanggal_awal }} s/d {{ $tanggal_akhir }}</td>
                          </tr>
                        </table>

                        <table class="table table-bordered table-hover mt-3">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Total Absen</th>
                                    <th>Masuk</th>
                                    <th>S</th>
                                    <th>I</th>
                                    <th>A</th>
                                    <th>Total Tidak Hadir</th>
                                    <th>Presentase</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswa as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->nama_siswa }}</td>
                                    <td>{{ $absensiSiswa[$data->id_siswa] }}</td>
                                    <td>{{ $countMasuk[$data->id_siswa] }}</td>
                                    <td>{{ $countSakit[$data->id_siswa] }}</td>
                                    <td>{{ $countIzin[$data->id_siswa] }}</td>
                                    <td>{{ $countAlfa[$data->id_siswa] }}</td>
                                    <td>{{ $countAlfa[$data->id_siswa] + $countIzin[$data->id_siswa] + $countSakit[$data->id_siswa] }}</td>
                                    <td>
                                        @php
                                            $totalAbsen = $absensiSiswa[$data->id_siswa];
                                            $presentase = $totalAbsen > 0 ? ($countMasuk[$data->id_siswa] / $totalAbsen) * 100 : 0;
                                            $badgeClass = $presentase > 90 ? 'badge-success' : ($presentase >= 80 ? 'badge-warning' : 'badge-danger');
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ number_format($presentase, 2) }}%</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
