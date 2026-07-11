@extends($layout)

@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Dokumen Siswa</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dokumen">Dokumen Siswa</a></li>
            <li class="breadcrumb-item"><a href="/admin/dokumen/{{ $dokumen->id_siswa }}">Review Dokumen</a></li>
            <li class="breadcrumb-item active">Edit Dokumen</li>
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
          <div class="card card-primary card-outline shadow-sm">
            <div class="card-header">
              <h3 class="card-title"><i class="fa fa-edit mr-2"></i>Form Edit Dokumen Siswa</h3>
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
                      <a href="{{ asset('storage/file_dokumen/' . $dokumen->file_dokumen) }}" target="_blank" class="btn btn-sm btn-info inline-flex items-center">
                        <i class="fa fa-file-pdf-o mr-1"></i> Lihat Dokumen ({{ $dokumen->file_dokumen }})
                      </a>
                    @else
                      <span class="text-muted">Tidak ada file terunggah</span>
                    @endif
                  </div>
                </div>

                <!-- Upload Dokumen Baru -->
                <div class="form-group">
                  <label for="file_dokumen">Upload Dokumen Baru <span class="text-muted text-sm">(Format PDF, opsional)</span></label>
                  <input type="file" name="file_dokumen" id="file_dokumen" class="form-control-file @error('file_dokumen') is-invalid @enderror">
                  <small class="form-text text-muted">Biarkan kosong jika Anda tidak ingin mengganti dokumen.</small>
                  @error('file_dokumen')
                    <span class="invalid-feedback d-block" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>

              </div>
              <!-- /.card-body -->

              <div class="card-footer bg-light">
                <button type="submit" class="btn btn-primary px-4"><i class="fa fa-save mr-1"></i> Simpan Perubahan</button>
                <a href="/admin/dokumen/{{ $dokumen->id_siswa }}" class="btn btn-secondary px-4"><i class="fa fa-arrow-left mr-1"></i> Kembali</a>
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </div>
@endsection
