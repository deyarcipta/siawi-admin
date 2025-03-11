@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Tambah Modul</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Modul</a></li>
            <li class="breadcrumb-item active">Tambah Modul</li>
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
              <h3 class="card-title">Form Tambah Modul</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/modul" method="POST"  enctype="multipart/form-data">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="nama_modul">Nama Modul</label>
                  <input type="text" class="form-control" id="nama_modul" placeholder="Enter Nama Modul" name="nama_modul" value="{{old('nama_modul')}}">
                  @error('nama_modul')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="id_mapel">Mata Pelajaran</label>
                  <select class="form-control" name="id_mapel" id="id_mapel">
                    <option value="{{old('id_mapel')}}">Pilih Mapel</option>
                    @foreach ($mapel as $data)
                      <option value="{{$data->id_mapel}}">{{$data->nama_mapel}}</option>
                    @endforeach
                  </select>
                  @error('id_mapel')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <label for="id_guru">Nama Guru</label>
                    <select class="form-control" name="id_guru" id="id_guru">
                      <option value="{{old('id_guru')}}">Pilih Guru</option>
                      @foreach ($guru as $data)
                        <option value="{{$data->id_guru}}">{{$data->nama_guru}}</option>
                      @endforeach
                    </select>
                    @error('id_guru')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="id_level">Nama Level</label>
                    <select class="form-control" name="id_level" id="id_level">
                      <option value="{{old('id_level')}}">Pilih Level</option>
                      @foreach ($level as $data)
                        <option value="{{$data->id_level}}">{{$data->nama_level}}</option>
                      @endforeach
                    </select>
                    @error('id_level')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <label for="id_jurusan">Jurusan</label>
                    <select class="form-control" name="id_jurusan" id="id_jurusan">
                      <option value="{{old('id_jurusan')}}">Pilih Jurusan</option>
                      @foreach ($jurusan as $data)
                        <option value="{{$data->id_jurusan}}">{{$data->nama_jurusan}}</option>
                      @endforeach
                    </select>
                    @error('id_jurusan')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="file_modul">File Modul</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="file_modul" id="file_modul">
                        <label class="custom-file-label" id="file_modul-label" for="file">Choose file</label>
                        <script>
                          document.getElementById('file_modul').addEventListener('change', function(e) {
                              var fileName = e.target.files[0].name;
                              var label = document.getElementById('file_modul-label');
                              label.textContent = fileName;
                          });
                      </script>
                      </div>
                    @error('logo')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
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
</div>
@endsection