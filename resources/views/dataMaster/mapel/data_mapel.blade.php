@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-book-open text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Mata Pelajaran</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Kelola daftar kurikulum mata pelajaran dan kode mapel</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Data Master</a></li>
            <li class="breadcrumb-item active">Data Mata Pelajaran</li>
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
              <i class="fas fa-table text-primary mr-2"></i> Data Mata Pelajaran
            </h3>
            <a href="/admin/mapel/create" class="btn btn-success btn-sm ml-auto"><i class="fas fa-plus mr-1"></i> Tambah Mata Pelajaran</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Kode Mata Pelajaran</th>
                <th>Nama Mata Pelajaran</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($mapel as $mpl)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$mpl->kode_mapel}}</td>
                  <td>{{$mpl->nama_mapel}}</td>
                  <td>
                    <form action="/admin/mapel/{{$mpl->id_mapel}}" method="POST">
                      <a href="/admin/mapel/{{$mpl->id_mapel}}/edit" class="btn btn-success"><i class="fa fa-edit"></i></a>
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