@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Data Kelas</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Data Master</a></li>
            <li class="breadcrumb-item active">Data Kelas</li>
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
            <h3 class="card-title">Data Kelas</h3>
            <a href="/admin/kelas/create" class="btn btn-success ml-auto">Tambah Kelas</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Kode Kelas</th>
                <th>Kode Level</th>
                <th>Nama Kelas</th>
                <th>Kode Jurusan</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($kelas as $kls)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$kls->kode_kelas}}</td>
                  <td>{{$kls->kode_level}}</td>
                  <td>{{$kls->nama_kelas}}</td>
                  <td>{{$kls->kode_jurusan}}</td>
                  <td>
                    <form action="/admin/kelas/{{$kls->id_kelas}}" method="POST">
                      <a href="/admin/kelas/{{$kls->id_kelas}}/edit" class="btn btn-success"><i class="fa fa-edit"></i></a>
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