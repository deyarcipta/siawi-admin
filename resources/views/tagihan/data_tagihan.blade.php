@extends($layout)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Tagihan Siswa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Tagihan Siswa</li>
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
                        <a href="/admin/tagihan/1/edit" class="btn btn-success ml-auto">Edit Link</a>
                    </div>
                    <div class="card-body">
                        <form action="/admin/tagihan" method="GET">
                            @csrf
                            <div class="row">
                                <div class="from-group col-2">
                                    <label for="kelas">Pilih Kelas</label>
                                </div>
                                <div class="form-group col-4">
                                    <select class="form-control" id="kelas" name="kelas" required>
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelas as $kls)
                                        <option value="{{ $kls->id_kelas }}" {{ $kelasId == $kls->id_kelas ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-2"> <!-- Menambahkan class text-right untuk menggeser ke kanan -->
                                    <button type="submit" class="btn btn-primary">Tampilkan Data</button>
                                </div>
                            </div>
                            {{-- <button type="submit" class="btn btn-primary">Tampilkan Data</button> --}}
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
                        <h3 class="card-title">Data Tagihan</h3>
                    </div>
                    <div class="card-body">
                        <form action="#" method="POST">
                            @csrf
                            <table id="" class="table table-bordered table-hover mt-2">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Nama Siswa</th>
                                        <th>Link tagihan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $data)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{ $data->nama_siswa }}</td>
                                        <td>
                                            <a href="{{$tagihan->link}}{{ $data->nis }}" target="_blank">{{$tagihan->link}}{{ $data->nis }}
                                            </a>
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
