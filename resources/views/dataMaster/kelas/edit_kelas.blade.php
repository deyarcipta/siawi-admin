@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-chalkboard"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Edit Data Kelas</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Perbarui data nama kelas, tingkat level, keahlian jurusan, dan wali kelas</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/kelas" class="text-primary font-weight-500">Data Kelas</a></li>
          <li class="breadcrumb-item active">Edit Kelas</li>
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
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Edit Data Kelas
            </h5>
          </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/kelas/{{$edit->id_kelas  }}" method="POST">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-group">
                  <label for="kode_kelas">Kode Kelas</label>
                  <input type="text" class="form-control" id="kode_kelas" placeholder="Enter Kode kelas" name="kode_kelas" value="{{$edit->kode_kelas}}">
                  @error('kode_kelas')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="kode_level">Pilih Level</label>
                  <select class="form-control" name="kode_level" id="kode_level">
                    @foreach ($level as $lvl)
                      <option value="{{$lvl->kode_level}}" {{ (old('kode_level', $edit->kode_level) == $lvl->kode_level) ? 'selected' : '' }}>{{$lvl->kode_level}}</option>
                    @endforeach
                  </select>
                  @error('kode_level')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="nama_kelas">Nama Kelas</label>
                  <input type="text" class="form-control" id="nama_kelas" placeholder="Enter Nama kelas" name="nama_kelas" value="{{$edit->nama_kelas}}">
                  @error('nama_kelas')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="kode_jurusan">Pilih Jurusan</label>
                  <select name="kode_jurusan" id="kode_jurusan" class="form-control">
                    @foreach ($jurusan as $jur)
                      <option value="{{ $jur->kode_jurusan }}" {{ (old('kode_jurusan', $edit->kode_jurusan) == $jur->kode_jurusan) ? 'selected' : '' }}> {{ $jur->kode_jurusan }}</option>
                    @endforeach
                  </select>
                  @error('kode_jurusan')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="id_guru">Pilih Wali Kelas (Guru)</label>
                  <select class="form-control" name="id_guru" id="id_guru">
                    <option value="">-- Pilih Wali Kelas (Opsional) --</option>
                    @foreach ($guru as $g)
                      <option value="{{ $g->id_guru }}" {{ (old('id_guru', $edit->id_guru) == $g->id_guru) ? 'selected' : '' }}>{{ $g->nama_guru }}</option>
                    @endforeach
                  </select>
                  @error('id_guru')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/kelas" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection