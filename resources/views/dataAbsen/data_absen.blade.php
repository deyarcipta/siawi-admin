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

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h3 class="card-title">Pilih Kelas</h3>
                    </div>
                    <div class="card-body">
                        <form action="/admin/dataAbsen" method="GET">
                            @csrf
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="kelas">Kelas</label>
                                    <select class="form-control" id="kelas" name="kelas" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelas as $kls)
                                        <option value="{{ $kls->id_kelas }}" {{ $kelasId == $kls->id_kelas ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label> <!-- Label kosong untuk membuat tombol sejajar dengan input -->
                                        <button type="submit" class="btn btn-primary btn-block">Tampilkan Data</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
                    <div class="card-header d-flex align-items-center">
                        <h3 class="card-title">Data Absensi</h3>
                    </div>
                    <div class="card-body">
                        <table style="font-size: 18px;">
                          <tr>
                            <td style="font-weight:bold" width="80">Kelas</td>
                            <td width="10">:</td>
                            <td>{{ $dataKelas->nama_kelas }}</td>
                          </tr>
                        </table>
                        <form action="/admin/absensi/absen" method="POST">
                            @csrf
                            <table id="" class="table table-bordered table-hover mt-2">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Nama Siswa</th>
                                        <th>Total Absen</th>
                                        <th>Masuk</th>
                                        <th>S</th>
                                        <th>I</th>
                                        <th>A</th>
                                        <th>Total Tidak Hadir</th>
                                        <th>Presentae</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $data)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$data->nama_siswa}}</td>
                                        <td>{{$absensiSiswa[$data->id_siswa]}}</td> <!-- Menampilkan total absensi -->
                                        <td>{{ $countMasuk[$data->id_siswa]}}</td> <!-- Menampilkan jumlah masuk -->
                                        <td>{{$countSakit[$data->id_siswa]}}</td> <!-- Menampilkan jumlah sakit -->
                                        <td>{{$countIzin[$data->id_siswa]}}</td> <!-- Menampilkan jumlah izin -->
                                        <td>{{$countAlfa[$data->id_siswa]}}</td> <!-- Menampilkan jumlah alfa -->
                                        <td>{{$countAlfa[$data->id_siswa] + $countIzin[$data->id_siswa] + $countSakit[$data->id_siswa]}}</td>
                                        <td>
                                            @php
                                            $presentase = ($countMasuk[$data->id_siswa]/$absensiSiswa[$data->id_siswa])*100; // Misalkan presentase diambil dari variabel $presentase
                                            $badgeClass = '';

                                            if ($presentase > 90) {
                                                $badgeClass = 'badge-success';
                                            } elseif ($presentase >= 80 && $presentase <= 90) {
                                                $badgeClass = 'badge-warning';
                                            } else {
                                                $badgeClass = 'badge-danger';
                                            }
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $presentase }}%</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
