@extends($layout)

@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-plus"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Mulai Sesi Penilaian Praktik</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Pilih kelas dan modul praktik untuk membuka formulir matriks penilaian satu kelas</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/penilaian-praktik" class="text-primary font-weight-500">Penilaian Praktik</a></li>
          <li class="breadcrumb-item active">Sesi Baru</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
        <h6 class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> Periksa Isian Formulir:</h6>
        <ul class="mb-0 pl-3">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="row">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
          <div class="card-header bg-white py-3 border-bottom">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1rem;">
              <i class="fas fa-clipboard-list text-primary mr-2"></i> Konfigurasi Sesi Penilaian Kelas
            </h5>
          </div>

          <form action="/admin/kejuruan/penilaian-praktik" method="POST">
            @csrf
            <div class="card-body p-4">

              <div class="form-group mb-3">
                <label for="id_kelas" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Pilih Kelas Siswa <span class="text-danger">*</span>
                </label>
                <select name="id_kelas" id="id_kelas" class="form-control select2bs4 @error('id_kelas') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                  <option value="">-- Pilih Kelas --</option>
                  @foreach($kelas as $kls)
                    <option value="{{ $kls->id_kelas }}" {{ (old('id_kelas', $selectedJadwal->id_kelas ?? null) == $kls->id_kelas) ? 'selected' : '' }}>
                      {{ $kls->nama_kelas }}
                    </option>
                  @endforeach
                </select>
                <small class="text-muted">Seluruh siswa aktif di kelas yang dipilih akan otomatis dimasukkan ke tabel penilaian.</small>
                @error('id_kelas')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="id_modul_praktik" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Pilih Modul Praktik (Bank Modul) <span class="text-danger">*</span>
                </label>
                <select name="id_modul_praktik" id="id_modul_praktik" class="form-control select2bs4 @error('id_modul_praktik') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                  <option value="">-- Pilih Modul Praktik Terbit --</option>
                  @foreach($moduls as $m)
                    <option value="{{ $m->id_modul_praktik }}" {{ old('id_modul_praktik') == $m->id_modul_praktik ? 'selected' : '' }}>
                      [{{ $m->kode_modul }}] {{ $m->judul }} (v{{ $m->versi }}) - {{ $m->kompetensi->nama_kompetensi ?? '' }} (KKM: {{ floatval($m->kompetensi->kkm ?? 75) }})
                    </option>
                  @endforeach
                </select>
                @error('id_modul_praktik')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label for="tanggal_penilaian" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Tanggal Penilaian <span class="text-danger">*</span>
                  </label>
                  <input type="date" class="form-control @error('tanggal_penilaian') is-invalid @enderror" id="tanggal_penilaian" name="tanggal_penilaian" value="{{ old('tanggal_penilaian', date('Y-m-d')) }}" required style="border-radius: 8px; height: 42px;">
                  @error('tanggal_penilaian')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                  <label for="id_jadwal_praktik" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Tautkan ke Jadwal Praktik <span class="badge badge-light border text-primary ml-1">Opsional</span>
                  </label>
                  <input type="text" class="form-control" readonly value="{{ $selectedJadwal ? ($selectedJadwal->hari . ' ' . substr($selectedJadwal->jam_mulai,0,5) . ' di ' . ($selectedJadwal->lab->nama_lab ?? 'Lab')) : 'Tanpa jadwal spesifik' }}" style="border-radius: 8px; height: 42px; background-color: #f8fafc;">
                  @if($selectedJadwal)
                    <input type="hidden" name="id_jadwal_praktik" value="{{ $selectedJadwal->id_jadwal_praktik }}">
                  @endif
                </div>
              </div>

              <div class="form-group mb-3">
                <label for="catatan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Catatan Sesi Penilaian (Opsional)
                </label>
                <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="3" placeholder="Contoh: Penilaian praktikum tugas mandiri pertemuan ke-4" style="border-radius: 8px;">{{ old('catatan') }}</textarea>
              </div>

            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/kejuruan/penilaian-praktik" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Batal
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-arrow-right mr-1"></i> Lanjut ke Input Nilai Kelas
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; background-color: #f8fafc;">
          <div class="card-body p-4">
            <h6 class="font-weight-bold text-dark mb-3">
              <i class="fas fa-lightbulb text-warning mr-1"></i> Panduan Alur Penilaian
            </h6>
            <ol class="text-muted small pl-3 mb-0" style="line-height: 1.7;">
              <li>Setelah sesi dibuat, sistem akan otomatis menyiapkan baris penilaian untuk setiap siswa di kelas tersebut.</li>
              <li>Guru dapat menginput nilai berdasarkan rubrik indikator modul secara bersamaan.</li>
              <li>Nilai akan tersimpan terlebih dahulu sebagai <strong>Draft</strong>.</li>
              <li>Siswa baru dapat melihat nilai setelah guru menekan tombol <strong>Finalkan Nilai</strong>.</li>
              <li>Nilai yang difinalkan otomatis tercatat pada portofolio <strong>Capaian Siswa</strong>.</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection
