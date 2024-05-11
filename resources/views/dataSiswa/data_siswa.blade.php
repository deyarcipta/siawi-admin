@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Data Siswa</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Data Siswa</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title">Data Siswa</h3>
            <a href="/admin/siswa/create" class="btn btn-success ml-auto">Tambah Siswa</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Nis</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Username</th>
                <th>Password</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($siswa as $data)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$data->nis}}</td>
                <td>{{$data->nama_siswa}}</td>
                <td>{{$data->kelas->nama_kelas}}</td>
                <td>{{$data->nis}}</td>
                <td>{{$data->password}}</td>
                <td>
                  <form action="/admin/siswa/{{$data->id_siswa}}" method="POST">
                    <a href="/admin/siswa/{{$data->id_siswa}}" class="btn btn-success"><i class="fa fa-eye"></i></a>
                    <a href="/admin/siswa/{{$data->id_siswa}}/edit" class="btn btn-warning"><i class="fa fa-edit" style="color: white"></i></a>
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