@extends($layout)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Point Siswa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Point Siswa</li>
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
                        <form action="/admin/pointSiswa" method="GET">
                            @csrf
                            <div class="row">
                                <div class="form-group col-6">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" required value="{{ $tanggal ?? '' }}">
                                </div>
                                <div class="form-group col-6">
                                    <label for="kelas">Kelas</label>
                                    <select class="form-control" id="kelas" name="kelas" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelas as $kls)
                                        <option value="{{ $kls->id_kelas }}" {{ $kelasId == $kls->id_kelas ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Tampilkan Data</button>
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
                        </table>
                            <table id="" class="table table-bordered table-hover mt-2">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Nama Siswa</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <input type="hidden" name="siswa[{{ $data->id_siswa }}][id_siswa]" value="{{ $data->id_siswa }}">{{ $data->nama_siswa }}
                                        </td>
                                        <td>
                                            <a href="/admin/pointSiswa/proses/{{$data->id_siswa}}/{{$tanggal}}" class="btn btn-danger ml-auto">Proses</a>
                                            {{-- <input class="form-control" type="text" name="siswa[{{ $data->id_siswa }}][keterangan]" value="{{ $absensiSiswa[$data->id_siswa]->keterangan ?? '' }}"> --}}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- <input type="hidden" name="tanggal" value="{{ $tanggal }}"> --}}
                            <input type="hidden" name="kelas_id" value="{{ $dataKelas->id_kelas }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
