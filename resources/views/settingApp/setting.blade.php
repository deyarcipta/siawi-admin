@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Setting</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Setting</a></li>
            <li class="breadcrumb-item active">Setting</li>
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
              <h3 class="card-title">Form Setting</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/setting/{{$setting->id}}" method="POST" enctype="multipart/form-data">
            {{-- <form action="/setting" method="POST"> --}}
              @method('PUT')
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="nama_app">Nama Aplikasi</label>
                  <input type="text" class="form-control" id="nama_app" placeholder="Enter Nama Aplikasi" name="nama_app" value="{{$setting->nama_app}}">
                  @error('nama_app')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="nama_sekolah">Nama Sekolah</label>
                  <input type="text" class="form-control" id="nama_sekolah" placeholder="Enter Nama Sekolah" name="nama_sekolah" value="{{$setting->nama_sekolah}}">
                  @error('nama_sekolah')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <label for="nama_kepsek">Nama Kepsek</label>
                    <input type="text" class="form-control" id="nama_kepsek" placeholder="Enter Nama Kepsek" name="nama_kepsek" value="{{$setting->nama_kepsek}}">
                    @error('nama_kepsek')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="nip_kepsek">Nip Kepsek</label>
                    <input type="text" class="form-control" id="nip_kepsek" placeholder="Enter Nip Kepsek" name="nip_kepsek" value="{{$setting->nip_kepsek}}">
                    @error('nip_kepsek')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="form-group">
                  <label for="alamat">Alamat Sekolah</label>
                  <textarea type="text" class="form-control" id="alamat" placeholder="Enter Alamat Sekolah" name="alamat" value="{{$setting->alamat}}">{{$setting->alamat}}</textarea>
                  @error('alamat')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <label for="kel">Kelurahaan</label>
                    <input type="text" class="form-control" id="kel" placeholder="Enter Kelurahaan" name="kel" value="{{$setting->kel}}">
                    @error('kel')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="kec">Kecamatan</label>
                    <input type="text" class="form-control" id="kec" placeholder="Enter Kecamatan" name="kec" value="{{$setting->kec}}">
                    @error('kec')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <label for="kota">Kota</label>
                    <input type="text" class="form-control" id="kota" placeholder="Enter Kota" name="kota" value="{{$setting->kota}}">
                    @error('kota')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="prov">Provinsi</label>
                    <input type="text" class="form-control" id="prov" placeholder="Enter Provinsi" name="prov" value="{{$setting->prov}}">
                    @error('prov')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <label for="logo">Logo</label>
                    <div class="input-group">
                      <div class="custom-file">
                        <input type="file" class="custom-file-input" name="logo" id="logo">
                        <label class="custom-file-label" id="logo-label" for="logo">Choose file</label>
                        <script>
                          document.getElementById('logo').addEventListener('change', function(e) {
                              var fileName = e.target.files[0].name;
                              var label = document.getElementById('logo-label');
                              label.textContent = fileName;
                          });
                      </script>
                      </div>
                    @error('logo')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="col-6 form-group">
                  <img src="{{ asset("storage/gambar/$setting->logo") }}" alt="Logo Sekolah" width="90px">
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