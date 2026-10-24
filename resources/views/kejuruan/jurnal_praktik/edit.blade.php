@extends($layout)
@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-edit"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Ubah Jurnal Praktik</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Perbarui catatan kegiatan praktik dan kelengkapan materi</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/kejuruan/jurnal-praktik" class="text-primary font-weight-500">Jurnal Praktik</a></li>
          <li class="breadcrumb-item active">Ubah Jurnal</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-exclamation-triangle mr-1"></i> {!! session('error') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Ubah Jurnal
            </h5>
          </div>

          <form action="/admin/kejuruan/jurnal-praktik/{{ $jurnal->id_jurnal_praktik }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card-body p-4 pt-2">
              <div class="p-3 bg-light rounded mb-3 border">
                <div class="row small">
                  <div class="col-sm-6 mb-1">
                    <strong>Kelas:</strong> {{ $jurnal->jadwalPraktik->kelas->nama_kelas ?? '-' }}
                  </div>
                  <div class="col-sm-6 mb-1">
                    <strong>Mata Pelajaran:</strong> {{ $jurnal->jadwalPraktik->mapel->nama_mapel ?? '-' }}
                  </div>
                  <div class="col-sm-6">
                    <strong>Laboratorium:</strong> {{ $jurnal->jadwalPraktik->lab->nama_lab ?? '-' }}
                  </div>
                  <div class="col-sm-6">
                    <strong>Tanggal:</strong> {{ $jurnal->tanggal->format('d/m/Y') }}
                  </div>
                </div>
              </div>

              <div class="form-group mb-3">
                <label for="materi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Materi Praktik / Topik Jobsheet <span class="text-danger">*</span>
                </label>
                <textarea class="form-control @error('materi') is-invalid @enderror" id="materi" name="materi" rows="3" required style="border-radius: 8px;">{{ old('materi', $jurnal->materi) }}</textarea>
                @error('materi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label for="jumlah_hadir" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Jumlah Siswa Hadir <span class="text-danger">*</span>
                  </label>
                  <div class="input-group">
                    <input type="number" class="form-control @error('jumlah_hadir') is-invalid @enderror" id="jumlah_hadir" name="jumlah_hadir" value="{{ old('jumlah_hadir', $jurnal->jumlah_hadir) }}" min="0" required style="border-radius: 8px 0 0 8px; height: 42px;">
                    <div class="input-group-append">
                      <span class="input-group-text bg-light text-muted" style="border-radius: 0 8px 8px 0;">Siswa</span>
                    </div>
                  </div>
                  @error('jumlah_hadir')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                  <label for="foto_praktik" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Ganti Foto Dokumentasi (Opsional)
                  </label>
                  <input type="file" class="form-control-file @error('foto_praktik') is-invalid @enderror" id="foto_praktik" name="foto_praktik" accept="image/*" style="font-size: 0.85rem;">
                  @error('foto_praktik')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <div class="form-group mb-3">
                <label for="alat_dipakai" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Alat & Software Laboratorium yang Digunakan
                </label>
                <input type="text" class="form-control @error('alat_dipakai') is-invalid @enderror" id="alat_dipakai" name="alat_dipakai" value="{{ old('alat_dipakai', $jurnal->alat_dipakai) }}" style="border-radius: 8px; height: 42px;">
                @error('alat_dipakai')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label for="kendala" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Kendala Praktik (Jika Ada)
                  </label>
                  <textarea class="form-control @error('kendala') is-invalid @enderror" id="kendala" name="kendala" rows="2" style="border-radius: 8px;">{{ old('kendala', $jurnal->kendala) }}</textarea>
                  @error('kendala')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                  <label for="catatan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Catatan Tambahan
                  </label>
                  <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="2" style="border-radius: 8px;">{{ old('catatan', $jurnal->catatan) }}</textarea>
                  @error('catatan')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>
            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/kejuruan/jurnal-praktik/{{ $jurnal->id_jurnal_praktik }}" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Batal
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
