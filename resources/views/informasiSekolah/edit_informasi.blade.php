@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-bullhorn"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Edit Pengumuman / Informasi Sekolah</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Perbarui konten informasi publik, sasaran pembaca, dan lampiran</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/informasi" class="text-primary font-weight-500">Informasi Sekolah</a></li>
          <li class="breadcrumb-item active">Edit Informasi</li>
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
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Edit Informasi
            </h5>
          </div>

          <form action="/admin/informasi/{{ $edit->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body p-4 pt-2">
              <div class="form-group mb-3">
                <label for="informasi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Judul Informasi <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('informasi') is-invalid @enderror" id="informasi" placeholder="Masukkan Judul Informasi" name="informasi" value="{{ old('informasi', $edit->informasi) }}" required style="border-radius: 8px; height: 42px;">
                @error('informasi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="ket_informasi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Keterangan / Isi Informasi <span class="text-danger">*</span></label>
                <textarea class="form-control @error('ket_informasi') is-invalid @enderror" id="ket_informasi" placeholder="Masukkan Keterangan Informasi" name="ket_informasi" rows="4" required style="border-radius: 8px;">{{ old('ket_informasi', $edit->ket_informasi) }}</textarea>
                @error('ket_informasi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="tanggal_awal" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Tanggal Mulai Berlaku <span class="text-danger">*</span></label>
                  <input type="date" class="form-control @error('tanggal_awal') is-invalid @enderror" id="tanggal_awal" name="tanggal_awal" value="{{ old('tanggal_awal', $edit->tanggal_awal) }}" required style="border-radius: 8px; height: 42px;">
                  @error('tanggal_awal')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
                <div class="col-md-6 mb-3">
                  <label for="tanggal_akhir" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Tanggal Berakhir <span class="text-danger">*</span></label>
                  <input type="date" class="form-control @error('tanggal_akhir') is-invalid @enderror" id="tanggal_akhir" name="tanggal_akhir" value="{{ old('tanggal_akhir', $edit->tanggal_akhir) }}" required style="border-radius: 8px; height: 42px;">
                  @error('tanggal_akhir')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <div class="form-group mb-2">
                <label for="file" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Upload Surat Edaran / Dokumen Lampiran <small class="text-muted font-weight-normal">(Kosongkan jika tidak ada perubahan)</small></label>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" name="file" id="file" onchange="document.getElementById('file-label').textContent = this.files[0] ? this.files[0].name : 'Pilih file baru...'">
                  <label class="custom-file-label" id="file-label" for="file" style="border-radius: 8px; height: 42px; line-height: 28px;">{{ $edit->file ? $edit->file : 'Pilih file baru...' }}</label>
                </div>
                @error('file')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>
            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/informasi" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
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