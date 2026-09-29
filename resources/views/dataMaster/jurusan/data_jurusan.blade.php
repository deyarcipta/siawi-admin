@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-graduation-cap text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Kompetensi Keahlian (Jurusan)</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Daftar program keahlian kejuruan dan kode konsentrasi keahlian</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Data Master</a></li>
            <li class="breadcrumb-item active">Data Jurusan</li>
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
            <h3 class="card-title text-dark font-weight-bold mb-0">
              <i class="fas fa-table text-primary mr-2"></i> Data Jurusan
            </h3>
            <a href="/admin/jurusan/create" class="btn btn-success btn-sm ml-auto"><i class="fas fa-plus mr-1"></i> Tambah Jurusan</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Kode Jurusan</th>
                <th>Nama Jurusan</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($jurusan as $jur)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$jur->kode_jurusan}}</td>
                  <td>{{$jur->nama_jurusan}}</td>
                  <td>
                    <form action="/admin/jurusan/{{$jur->id_jurusan}}" method="POST">
                      <a href="/admin/jurusan/{{$jur->id_jurusan}}/edit" class="btn btn-success"><i class="fa fa-edit"></i></a>
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