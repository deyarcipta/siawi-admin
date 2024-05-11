@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Data Point</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Point</a></li>
            <li class="breadcrumb-item active">Data Point</li>
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
            <h3 class="card-title">Data Point</h3>
            <a href="/admin/point/create" class="btn btn-success ml-auto">Tambah Point</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Nama Point</th>
                <th>Jenis Point</th>
                <th>Skor Point</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($point as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$data->nama_point}}</td>
                  <td>{{$data->jenis_point}}</td>
                  <td>{{$data->skor_point}}</td>
                  <td>
                    <form action="/admin/point/{{$data->id_point}}" method="POST">
                      <a href="/admin/point/{{$data->id_point}}/edit" class="btn btn-success"><i class="fa fa-edit"></i></a>
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