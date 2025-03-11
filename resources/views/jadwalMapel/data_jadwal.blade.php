@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Data Jadwal Mata Pelajaran</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Jadwal</a></li>
            <li class="breadcrumb-item active">Data Jadwal Mata Pelajaran</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title">Data Jadwal Mata Pelajaran</h3>
            <a href="/admin/jadwal/create" class="btn btn-success ml-auto">Tambah Jadwal</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Nama Mapel</th>
                <th>Nama Guru</th>
                <th>Kelas</th>
                <th>Hari</th>
                <th>Jam</th>
                <th>Waktu</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($jadwal as $jdwl)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$jdwl->mapel->nama_mapel}}</td>
                  <td>{{$jdwl->guru->nama_guru}}</td>
                  <td>{{$jdwl->kelas}}</td>
                  <td>{{$jdwl->hari}}</td>
                  <td>{{$jdwl->jam_awal}} s/d {{$jdwl->jam_akhir}}</td>
                  <td>{{$jdwl->waktu_awal}} s/d {{$jdwl->waktu_akhir}}</td>
                  <td>
                    <form action="/admin/jadwal/{{$jdwl->id_jadwal}}" method="POST">
                      <a href="/admin/jadwal/{{$jdwl->id_jadwal}}/edit" class="btn btn-success"><i class="fa fa-edit"></i></a>
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                    </form>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card --> 
        </div>
      </div>
    </div>
  </div>
  @endsection