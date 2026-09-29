@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Edit Data Poin Pelanggaran</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Perbarui kategori nama pelanggaran dan bobot poin siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/point" class="text-primary font-weight-500">Master Poin</a></li>
          <li class="breadcrumb-item active">Edit Poin</li>
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
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Edit Master Poin
            </h5>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form action="/admin/point/{{$edit->id_point}}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
              <div class="form-group">
                <label for="nama_point">Nama Poin / Pelanggaran</label>
                <input type="text" class="form-control" id="nama_point" placeholder="Masukkan Nama Point" name="nama_point" value="{{$edit->nama_point}}">
                @error('nama_point')
                  <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="jenis_point">Jenis Poin</label>
                  <input type="text" class="form-control" id="jenis_point" placeholder="Masukkan Jenis point" name="jenis_point" value="{{$edit->jenis_point}}">
                  @error('jenis_point')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group col-md-6 mb-0">
                  <label for="skor_point">Skor / Bobot Poin</label>
                  <input type="number" class="form-control" id="skor_point" placeholder="Masukkan Skor point" name="skor_point" value="{{$edit->skor_point}}">
                  @error('skor_point')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/point" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
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