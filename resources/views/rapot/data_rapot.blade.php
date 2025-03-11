@extends($layout)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Rapot</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Data Rapot</li>
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
                    <div class="card-body ">
                        <form action="/admin/rapot" method="GET">
                            @csrf
                                <div class="form-group">
                                    <label for="kelas">Kelas</label>
                                    <select class="form-control" id="kelas" name="kelas" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelas as $kls)
                                        <option value="{{ $kls->id_kelas }}" {{ $kelasId == $kls->id_kelas ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                        @endforeach
                                    </select>
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
                        <a href="/admin/rapot/create/{{$kelasId}}" class="btn btn-success ml-auto">Tambah Rapot</a>
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
                                        <td>{{ $data->nama_siswa }}</td>
                                        <td>
                                            <form action="point/{{$data->id_point}}" method="POST">
                                                <a href="/admin/rapot/{{$data->id_siswa}}" class="btn btn-success"><i class="fa fa-eye"></i></a>
                                                @csrf
                                              </form>
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
