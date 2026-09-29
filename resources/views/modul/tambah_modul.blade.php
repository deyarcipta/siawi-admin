@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-file-pdf"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Tambah Modul Pembelajaran</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Unggah modul dan materi pembelajaran baru untuk siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/modul" class="text-primary font-weight-500">Modul Siswa</a></li>
          <li class="breadcrumb-item active">Tambah Modul</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- general form elements -->
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-plus-circle text-primary mr-2"></i> Formulir Tambah Modul Pembelajaran
            </h5>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form action="/admin/modul" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="nama_modul">Nama Modul</label>
                <input type="text" class="form-control" id="nama_modul" placeholder="Masukkan Nama Modul" name="nama_modul" value="{{old('nama_modul')}}">
                @error('nama_modul')
                  <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
              </div>
              <div class="form-group">
                <label for="id_mapel">Mata Pelajaran</label>
                <select class="form-control" name="id_mapel" id="id_mapel">
                  <option value="">Pilih Mapel</option>
                  @foreach ($mapel as $data)
                    <option value="{{$data->id_mapel}}" {{ old('id_mapel') == $data->id_mapel ? 'selected' : '' }}>{{$data->nama_mapel}}</option>
                  @endforeach
                </select>
                @error('id_mapel')
                  <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="id_guru">Nama Guru</label>
                  <select class="form-control" name="id_guru" id="id_guru">
                    <option value="">Pilih Guru</option>
                    @foreach ($guru as $data)
                      <option value="{{$data->id_guru}}" {{ old('id_guru') == $data->id_guru ? 'selected' : '' }}>{{$data->nama_guru}}</option>
                    @endforeach
                  </select>
                  @error('id_guru')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group col-md-6">
                  <label for="id_level">Nama Level</label>
                  <select class="form-control" name="id_level" id="id_level">
                    <option value="">Pilih Level</option>
                    @foreach ($level as $data)
                      <option value="{{$data->id_level}}" {{ old('id_level') == $data->id_level ? 'selected' : '' }}>{{$data->nama_level}}</option>
                    @endforeach
                  </select>
                  @error('id_level')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="id_jurusan">Jurusan</label>
                  <select class="form-control" name="id_jurusan" id="id_jurusan">
                    <option value="">Pilih Jurusan</option>
                    @foreach ($jurusan as $data)
                      <option value="{{$data->id_jurusan}}" {{ old('id_jurusan') == $data->id_jurusan ? 'selected' : '' }}>{{$data->nama_jurusan}}</option>
                    @endforeach
                  </select>
                  @error('id_jurusan')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group col-md-6 mb-0">
                  <label for="file_modul">File Modul</label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" name="file_modul" id="file_modul">
                    <label class="custom-file-label" id="file_modul-label" for="file_modul">Pilih berkas modul...</label>
                    <script>
                      document.getElementById('file_modul').addEventListener('change', function(e) {
                          var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih berkas modul...';
                          var label = document.getElementById('file_modul-label');
                          label.textContent = fileName;
                      });
                    </script>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/modul" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Tambah Modul
              </button>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</div>
@endsection