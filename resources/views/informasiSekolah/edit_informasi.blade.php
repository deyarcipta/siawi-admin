@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Informasi</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Informasi</a></li>
            <li class="breadcrumb-item active">Edit Informasi</li>
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
              <h3 class="card-title">Form Edit Informasi</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/informasi/{{$edit->id  }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-group">
                  <label for="informasi">Informasi</label>
                  <input type="text" class="form-control" id="informasi" placeholder="Enter Kode level" name="informasi" value="{{$edit->informasi}}">
                  @error('informasi')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="ket_informasi">Keterangan Informasi</label>
                  <input type="text" class="form-control" id="ket_informasi" placeholder="Enter Keterangan Informasi" name="ket_informasi" value="{{$edit->ket_informasi}}">
                  @error('ket_informasi')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <label for="tanggal_awal">Awal Tanggal Informasi</label>
                    <input type="date" class="form-control" id="tanggal_awal" placeholder="Enter Nama level" name="tanggal_awal" value="{{$edit->tanggal_awal}}">
                    @error('tanggal_awal')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="tanggal_akhir">Akhir Tanggal Informasi</label>
                    <input type="date" class="form-control" id="tanggal_akhir" placeholder="Enter Nama level" name="tanggal_akhir" value="{{$edit->tanggal_akhir}}">
                    @error('tanggal_akhir')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
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
                    @error('file')
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