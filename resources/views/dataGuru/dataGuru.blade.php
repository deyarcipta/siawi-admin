@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Data Guru</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Data Guru</li>
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
            <h3 class="card-title">Data Guru</h3>
            <a href="/admin/guru/create" class="btn btn-success ml-auto">Tambah Guru</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Nama Guru</th>
                <th>Username</th>
                {{-- <th>Password</th> --}}
                <th>Role</th>
                <th style="width: 160px">Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($guru as $gru)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$gru->nama_guru}}</td>
                <td>{{$gru->username}}</td>
                {{-- <td>{{ $gru->password }}</td> --}}
                <td>{{$gru->role}}</td>
                <td>
                  <form action="guru/{{$gru->id_guru}}" method="POST">
                    <a href="{{route('admin.guru.reset', $gru->id_guru)}}" class="btn btn-primary"><i class="fa fa-key" style="color: white"></i></a>
                    <a href="{{route('admin.guru.edit', $gru->id_guru)}}" class="btn btn-warning"><i class="fa fa-edit" style="color: white"></i></a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash" style="color: white"></i></button>
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