@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Tambah Rapot</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Rapot</a></li>
            <li class="breadcrumb-item active">Tambah Rapot</li>
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
              <h3 class="card-title">Form Tambah Rapot</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/rapot" method="POST"  enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                <div class="row">
                  <div class="form-group col-4">
                    <label for="id_siswa">Siswa</label>
                    <select class="form-control" name="id_siswa" id="id_siswa">
                      <option value="{{old('id_siswa')}}">Pilih Siswa</option>
                      @foreach ($siswa as $data)
                        <option value="{{$data->id_siswa}}">{{$data->nama_siswa}}</option>
                      @endforeach
                    </select>
                    @error('id_siswa')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="semester">Semester</label>
                    <select class="form-control" name="semester" id="semester">
                      <option value="">Pilih Semester</option>
                      <option value="1">Semester 1</option>
                      <option value="2">Semester 2</option>
                      <option value="3">Semester 3</option>
                      <option value="4">Semester 4</option>
                      <option value="5">Semester 5</option>
                      <option value="6">Semester 6</option>
                    </select>
                    @error('semester')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-4">
                    <label for="rata_rata">Rata-Rata</label>
                    <input class="form-control"  type="text" name="rata_rata" id="rata_rata" value="{{old('rata_rata')}}" placeholder="Entar Rata-Rata Rapot">
                    @error('rata_rata')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="form-group">
                  <label for="file_rapot">Upload File Rapot</label>
                  <div class="input-group">
                    <div class="custom-file">
                      <input type="file" class="custom-file-input" name="file_rapot" id="file_rapot">
                      <label class="custom-file-label" id="file_rapot-label" for="file">Choose file</label>
                      <script>
                        document.getElementById('file_rapot').addEventListener('change', function(e) {
                            var fileName = e.target.files[0].name;
                            var label = document.getElementById('file_rapot-label');
                            label.textContent = fileName;
                        });
                    </script>
                    </div>
                  @error('file_rapot')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                <input type="text" name="id_kelas" id="id_kelas" value="{{$kelasId}}" hidden>
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </div>
</div>
@endsection