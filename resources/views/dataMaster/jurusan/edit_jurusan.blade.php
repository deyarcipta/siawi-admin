@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Tambah Jurusan</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Jurusan</a></li>
            <li class="breadcrumb-item active">Tambah Jurusan</li>
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
              <h3 class="card-title">Form Tambah Jurusan</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/jurusan/{{$edit->id  }}" method="POST">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-group">
                  <label for="kode_jurusan">Kode Jurusan</label>
                  <input type="text" class="form-control" id="kode_jurusan" placeholder="Enter Kode Jurusan" name="kode_jurusan" value="{{$edit->kode_jurusan}}">
                  @error('kode_jurusan')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="nama_jurusan">Nama Jurusan</label>
                  <input type="text" class="form-control" id="nama_jurusan" placeholder="Enter Nama Jurusan" name="nama_jurusan" value="{{$edit->nama_jurusan}}">
                  @error('nama_jurusan')
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