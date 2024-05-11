@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Data Berita</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Data Berita</a></li>
            <li class="breadcrumb-item active">Data Berita</li>
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
            <h3 class="card-title">Data Berita</h3>
            <a href="/admin/berita/create" class="btn btn-success ml-auto">Tambah Berita</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Judul Berita</th>
                <th>Isi Berita</th>
                <th>Pembuat</th>
                <th>Tanggal</th>
                <th>Gambar</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($berita as $data)
                <tr>
                  <td>{{$loop->iteration}}</td>
                  <td>{{$data->judul_berita}}</td>
                  <td>{{$data->isi_berita}}</td>
                  <td>{{$data->guru->nama_guru}}</td>
                  <td>{{$data->tanggal}}</td>
                  <td>
                    <img src="{{ asset("storage/berita/$data->cover") }}" alt="File Berita" width="100">
                  </td>
                  <td>
                    <form action="berita/{{$data->id_berita}}" method="POST">
                      <a href="{{route('admin.berita.edit', $data->id_berita)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
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