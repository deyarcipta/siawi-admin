@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Tambah Informasi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Informasi</a></li>
            <li class="breadcrumb-item active">Tambah Informasi</li>
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
              <h3 class="card-title">Form Tambah Informasi</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/informasi" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="informasi">Informasi</label>
                  <input type="text" class="form-control" id="informasi" placeholder="Enter Kode level" name="informasi" value="{{old('informasi')}}">
                  @error('informasi')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="tanggal">Tanggal Informasi</label>
                  <input type="date" class="form-control" id="tanggal" placeholder="Enter Nama level" name="tanggal" value="{{old('tanggal')}}">
                  @error('tanggal')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="file">Upload Surat Edaran</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="file" id="file">
                        <label class="custom-file-label" id="file-label" for="file">Choose file</label>
                        <script>
                          document.getElementById('file').addEventListener('change', function(e) {
                              var fileName = e.target.files[0].name;
                              var label = document.getElementById('file-label');
                              label.textContent = fileName;
                          });
                      </script>
                      </div>
                    @error('logo')
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