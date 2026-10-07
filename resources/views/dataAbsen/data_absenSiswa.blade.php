@extends($layout)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-7 d-flex align-items-center">
                <i class="fas fa-user-check text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
                <div class="d-flex flex-column justify-content-center">
                    <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Detail Absensi Siswa Kelas</h1>
                    <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Rincian kehadiran siswa per kelas untuk periode tertentu</p>
                </div>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                    @if(Auth::user()->role == 'admin')
                    <li class="breadcrumb-item"><a href="/admin/rekapAbsen">Rekap Kelas</a></li>
                    @endif
                    <li class="breadcrumb-item active">Detail Absensi Kelas</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Filter Card -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h3 class="card-title text-dark font-weight-bold mb-0">
                            <i class="fas fa-sliders-h text-primary mr-2"></i> Filter Periode Absensi
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="/admin/showRekapAbsen" method="GET">
                            <input type="hidden" name="id_kelas" value="{{ $kelasId }}">
                            <div class="row align-items-end">
                                <div class="form-group col-md-4 mb-3 mb-md-0">
                                    <label for="tanggal_awal" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Awal</label>
                                    <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" required value="{{ $tanggal_awal ?? '' }}">
                                </div>
                                <div class="form-group col-md-4 mb-3 mb-md-0">
                                    <label for="tanggal_akhir" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Akhir</label>
                                    <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required value="{{ $tanggal_akhir ?? '' }}">
                                </div>
                                <div class="form-group col-md-2 mb-0">
                                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Tampilkan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($siswa))
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                        <h3 class="card-title text-dark font-weight-bold mb-0">
                            <i class="fas fa-table text-primary mr-2"></i> Data Absensi Siswa
                        </h3>
                        <div class="d-flex align-items-center ml-auto mt-2 mt-md-0" style="gap: 8px;">
                            <a href="/admin/absensi/download?kelas={{ $kelasId }}&tanggal_awal={{ $tanggal_awal }}&tanggal_akhir={{ $tanggal_akhir }}" class="btn btn-success btn-sm shadow-sm font-weight-bold px-3" style="border-radius: 8px;">
                                <i class="fas fa-file-excel mr-1"></i> Unduh Excel
                            </a>
                            <a href="/admin/absensi/download-pdf?kelas={{ $kelasId }}&tanggal_awal={{ $tanggal_awal }}&tanggal_akhir={{ $tanggal_akhir }}" target="_blank" class="btn btn-danger btn-sm shadow-sm font-weight-bold px-3" style="border-radius: 8px;">
                                <i class="fas fa-file-pdf mr-1"></i> Unduh PDF
                            </a>
                        </div>
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
        @endif
    </div>
</div>
@endsection
