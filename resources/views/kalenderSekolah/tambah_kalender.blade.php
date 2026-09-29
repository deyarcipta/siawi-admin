@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-calendar-week"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Tambah Kegiatan Kalender</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Tambahkan agenda dan jadwal baru pada kalender akademik sekolah</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/kalender" class="text-primary font-weight-500">Kalender Sekolah</a></li>
          <li class="breadcrumb-item active">Tambah Kalender</li>
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
              <i class="fas fa-plus-circle text-primary mr-2"></i> Formulir Tambah Kegiatan Kalender
            </h5>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form action="/admin/kalender" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
              <div class="form-group">
                <label for="kegiatan">Nama Kegiatan</label>
                <input type="text" class="form-control" id="kegiatan" placeholder="Masukkan Nama Kegiatan" name="kegiatan" value="{{old('kegiatan')}}">
                @error('kegiatan')
                  <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="tgl_mulai">Tanggal Mulai</label>
                  <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" value="{{old('tgl_mulai')}}">
                  @error('tgl_mulai')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group col-md-6 mb-0">
                  <label for="tgl_akhir">Tanggal Selesai</label>
                  <input type="date" class="form-control" id="tgl_akhir" name="tgl_akhir" value="{{old('tgl_akhir')}}">
                  @error('tgl_akhir')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/kalender" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Tambah Kegiatan
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