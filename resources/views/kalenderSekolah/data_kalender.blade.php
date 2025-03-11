@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Kalender Sekolah</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Kalender Sekolah</li>
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
            <h3 class="card-title">Data Kalender</h3>
            <a href="/admin/kalender/create" class="btn btn-success ml-auto">Tambah Kalender</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Kegiatan</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($kalender as $data)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$data->kegiatan}}</td>
                <td>{{$data->tgl_mulai}}</td>
                <td>{{$data->tgl_akhir}}</td>
                <td>
                  {{-- <form action="#" method="POST"> --}}
                  <form action="/admin/kalender/{{$data->id}}" method="POST">
                    {{-- <a href="kalender/{{$data->id}}" class="btn btn-success"><i class="fa fa-eye"></i></a> --}}
                    <a href="/admin/kalender/{{$data->id}}/edit" class="btn btn-warning"><i class="fa fa-edit" style="color: white"></i></a>
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