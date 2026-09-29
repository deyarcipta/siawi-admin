@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Edit Konsentrasi Keahlian / Jurusan</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Perbarui data kompetensi keahlian dan jurusan</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/jurusan" class="text-primary font-weight-500">Data Jurusan</a></li>
          <li class="breadcrumb-item active">Edit Jurusan</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Edit Jurusan
            </h5>
          </div>

          <form action="/admin/jurusan/{{ $edit->id }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body p-4 pt-2">
              <div class="form-group mb-3">
                <label for="kode_jurusan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Kode Jurusan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('kode_jurusan') is-invalid @enderror" id="kode_jurusan" placeholder="Contoh: TJKT" name="kode_jurusan" value="{{ old('kode_jurusan', $edit->kode_jurusan) }}" required style="border-radius: 8px; height: 42px;">
                @error('kode_jurusan')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>
              <div class="form-group mb-2">
                <label for="nama_jurusan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Nama Konsentrasi Keahlian / Jurusan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama_jurusan') is-invalid @enderror" id="nama_jurusan" placeholder="Contoh: Teknik Jaringan Komputer dan Telekomunikasi" name="nama_jurusan" value="{{ old('nama_jurusan', $edit->nama_jurusan) }}" required style="border-radius: 8px; height: 42px;">
                @error('nama_jurusan')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>
            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/jurusan" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
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