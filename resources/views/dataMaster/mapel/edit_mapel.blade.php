@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Tambah Mata Pelajaran</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Mata Pelajaran</a></li>
            <li class="breadcrumb-item active">Tambah Mata Pelajaran</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Form Tambah Mata Pelajaran</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/mapel/{{$edit->id  }}" method="POST">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-group">
                  <label for="kode_mapel">Kode Mata Pelajaran</label>
                  <input type="text" class="form-control" id="kode_mapel" placeholder="Enter Kode Mapel" name="kode_mapel" value="{{$edit->kode_mapel}}">
                  @error('kode_mapel')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="nama_mapel">Nama Mata Pelajaran</label>
                  <input type="text" class="form-control" id="nama_mapel" placeholder="Enter Nama Mapel" name="nama_mapel" value="{{$edit->nama_mapel}}">
                  @error('nama_mapel')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </div>
@endsection