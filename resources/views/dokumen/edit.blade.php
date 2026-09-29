@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-file-signature"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Edit Dokumen Siswa</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Perbarui berkas dokumen kelengkapan data siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dokumen" class="text-primary font-weight-500">Dokumen Siswa</a></li>
          <li class="breadcrumb-item"><a href="/admin/dokumen/{{ $dokumen->id_siswa }}" class="text-primary font-weight-500">Review Dokumen</a></li>
          <li class="breadcrumb-item active">Edit Dokumen</li>
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
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Edit Dokumen Siswa
            </h5>
          </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/dokumen/{{ $dokumen->id_dokumen }}" method="POST" enctype="multipart/form-data">
              @csrf
              @method('PUT')
              <div class="card-body">
                
                <!-- Nama Siswa (Read-only) -->
                <div class="form-group">
                  <label for="nama_siswa">Nama Siswa</label>
                  <input type="text" class="form-control" id="nama_siswa" value="{{ $dokumen->siswa->nama_siswa }}" readonly disabled>
                </div>

                <!-- Jenis Dokumen -->
                <div class="form-group">
                  <label for="jenis_dokumen">Jenis Dokumen</label>
                  <input type="text" class="form-control @error('jenis_dokumen') is-invalid @enderror" id="jenis_dokumen" placeholder="Contoh: Kartu Pelajar" name="jenis_dokumen" value="{{ old('jenis_dokumen', $dokumen->jenis_dokumen) }}" required>
                  @error('jenis_dokumen')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>

                <!-- File Dokumen Saat Ini -->
                <div class="form-group">
                  <label>Dokumen Saat Ini</label>
                  <div class="mb-2">
                    @if($dokumen->file_dokumen)
                      <a href="{{ asset('storage/file_dokumen/' . $dokumen->file_dokumen) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                        <i class="fas fa-file-pdf mr-1"></i> Buka Berkas PDF ({{ $dokumen->file_dokumen }})
                      </a>
                    @else
                      <span class="text-muted">Tidak ada file terunggah</span>
                    @endif
                  </div>
                </div>

                <!-- Upload Dokumen Baru -->
                <div class="form-group mb-0">
                  <label for="file_dokumen" class="font-weight-bold">Upload Dokumen Baru <span class="text-muted text-sm">(Format PDF, opsional)</span></label>
                  <input type="file" name="file_dokumen" id="file_dokumen" class="form-control-file p-2 border @error('file_dokumen') is-invalid @enderror" accept=".pdf" style="border-radius: 8px; background: #f8fafc;">
                  <small class="form-text text-muted">Biarkan kosong jika Anda tidak ingin mengganti dokumen.</small>
                  @error('file_dokumen')
                    <span class="invalid-feedback d-block" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>

              </div>
              <!-- /.card-body -->

              <div class="card-footer bg-light py-3 d-flex align-items-center">
                <a href="/admin/dokumen/{{ $dokumen->id_siswa }}" class="btn btn-secondary px-3" style="border-radius: 8px;"><i class="fas fa-arrow-left mr-1"></i> Kembali</a>
                <button type="submit" class="btn btn-primary ml-auto px-4" style="border-radius: 8px;"><i class="fas fa-save mr-1"></i> Simpan Perubahan</button>
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </div>
@endsection
