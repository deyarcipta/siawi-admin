@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-plus-circle text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Buat Kegiatan Uji Kompetensi (UKK)</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Atur unit kompetensi yang diuji, tim penguji internal/eksternal, dan peserta ujian</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/uji-kompetensi" class="text-primary font-weight-500">Uji Kompetensi</a></li>
          <li class="breadcrumb-item active">Buat Kegiatan</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
        <ul class="mb-0">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <form action="/admin/kejuruan/uji-kompetensi" method="POST">
      @csrf

      <!-- Card 1: Informasi Dasar Kegiatan -->
      <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
        <div class="card-header bg-white py-3 border-bottom">
          <h5 class="card-title text-dark font-weight-bold mb-0">
            <i class="fas fa-info-circle text-primary mr-2"></i> 1. Informasi Kegiatan Ujian
          </h5>
        </div>
        <div class="card-body p-4">
          <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label class="font-weight-bold text-dark small">Nama Kegiatan UKK <span class="text-danger">*</span></label>
              <input type="text" name="nama_kegiatan" class="form-control" placeholder="Contoh: Ujian Sertifikasi Keahlian TKJ 2026/2027" value="{{ old('nama_kegiatan') }}" required>
            </div>

            <div class="col-md-6 form-group mb-3">
              <label class="font-weight-bold text-dark small">Program Keahlian (Jurusan) <span class="text-danger">*</span></label>
              <select name="id_jurusan" class="form-control select2bs4" required>
                @foreach($jurusans as $j)
                  <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                    {{ $j->nama_jurusan }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 form-group mb-3">
              <label class="font-weight-bold text-dark small">Laboratorium / Ruang Praktik</label>
              <select name="id_lab" class="form-control select2bs4">
                <option value="">-- Pilih Laboratorium (Opsional) --</option>
                @foreach($labs as $l)
                  <option value="{{ $l->id_lab }}" {{ old('id_lab') == $l->id_lab ? 'selected' : '' }}>
                    {{ $l->nama_lab }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-4 form-group mb-3">
              <label class="font-weight-bold text-dark small">Tanggal Mulai <span class="text-danger">*</span></label>
              <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', date('Y-m-d')) }}" required>
            </div>

            <div class="col-md-4 form-group mb-3">
              <label class="font-weight-bold text-dark small">Tanggal Selesai <span class="text-danger">*</span></label>
              <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', date('Y-m-d')) }}" required>
            </div>

            <div class="col-md-4 form-group mb-3">
              <label class="font-weight-bold text-dark small">Tahun Ajaran</label>
              <input type="text" name="tahun_ajaran" class="form-control" value="{{ old('tahun_ajaran', '2026/2027') }}" placeholder="Contoh: 2026/2027">
            </div>

            <div class="col-md-4 form-group mb-3">
              <label class="font-weight-bold text-dark small">Nomor SK Kegiatan / Penugasan</label>
              <input type="text" name="nomor_sk" class="form-control" value="{{ old('nomor_sk') }}" placeholder="Contoh: 421.5/120/SMK-UKK/2026">
            </div>

            <div class="col-md-4 form-group mb-3">
              <label class="font-weight-bold text-dark small">Status Kegiatan</label>
              <select name="status" class="form-control">
                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Persiapan)</option>
                <option value="berlangsung" {{ old('status') === 'berlangsung' ? 'selected' : '' }}>Sedang Berlangsung</option>
                <option value="selesai" {{ old('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
              </select>
            </div>

            <div class="col-12 form-group mb-0">
              <label class="font-weight-bold text-dark small">Deskripsi / Catatan Pelaksanaan</label>
              <textarea name="deskripsi" class="form-control" rows="2" placeholder="Catatan panduan atau standar teknis pengujian">{{ old('deskripsi') }}</textarea>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 2: Unit Kompetensi yang Diujikan -->
      <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
        <div class="card-header bg-white py-3 border-bottom">
          <h5 class="card-title text-dark font-weight-bold mb-0">
            <i class="fas fa-tasks text-primary mr-2"></i> 2. Unit Kompetensi yang Diujikan <span class="text-danger">*</span>
          </h5>
          <p class="text-muted small mb-0 mt-1">Centang seluruh unit kompetensi keahlian yang menjadi materi asesmen pada kegiatan ini.</p>
        </div>
        <div class="card-body p-4">
          <div class="row">
            @forelse($kompetensis as $k)
              <div class="col-md-6 mb-2">
                <div class="custom-control custom-checkbox p-2 rounded bg-light border">
                  <input type="checkbox" class="custom-control-input" id="komp_{{ $k->id_kompetensi }}" name="unit_kompetensi[]" value="{{ $k->id_kompetensi }}" {{ is_array(old('unit_kompetensi')) && in_array($k->id_kompetensi, old('unit_kompetensi')) ? 'checked' : '' }}>
                  <label class="custom-control-label font-weight-500 text-dark d-block" for="komp_{{ $k->id_kompetensi }}" style="cursor: pointer;">
                    <span class="badge badge-primary px-2 mr-1">{{ $k->kode_kompetensi }}</span>
                    {{ $k->nama_kompetensi }}
                    <small class="text-muted d-block mt-1">
                      Kelas {{ $k->tingkat }} - KKM: {{ floatval($k->kkm) }}
                    </small>
                  </label>
                </div>
              </div>
            @empty
              <div class="col-12 text-muted">Belum ada standar kompetensi terdaftar. Silakan atur pada menu Standar Kompetensi terlebih dahulu.</div>
            @endforelse
          </div>
        </div>
      </div>

      <!-- Card 3: Tim Penguji (Internal & Eksternal) -->
      <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
        <div class="card-header bg-white py-3 border-bottom">
          <h5 class="card-title text-dark font-weight-bold mb-0">
            <i class="fas fa-user-check text-primary mr-2"></i> 3. Tim Asesor / Penguji
          </h5>
          <p class="text-muted small mb-0 mt-1">Pilih guru penguji internal sekolah dan input nama penguji eksternal dari DU-DI atau Asosiasi Profesi.</p>
        </div>
        <div class="card-body p-4">
          <div class="row">
            <!-- Penguji Internal -->
            <div class="col-md-6 mb-3">
              <h6 class="font-weight-bold text-dark border-bottom pb-2">
                <i class="fas fa-chalkboard-teacher text-success mr-1"></i> Penguji Internal (Guru Sekolah)
              </h6>
              <div class="form-group mb-2">
                <label class="small text-muted">Pilih Penguji Internal 1</label>
                <select name="penguji_internal[]" class="form-control select2bs4">
                  <option value="">-- Pilih Guru Penguji 1 --</option>
                  @foreach($gurus as $g)
                    <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group mb-0">
                <label class="small text-muted">Pilih Penguji Internal 2 (Opsional)</label>
                <select name="penguji_internal[]" class="form-control select2bs4">
                  <option value="">-- Pilih Guru Penguji 2 --</option>
                  @foreach($gurus as $g)
                    <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Penguji Eksternal -->
            <div class="col-md-6 mb-3">
              <h6 class="font-weight-bold text-dark border-bottom pb-2">
                <i class="fas fa-building text-warning mr-1"></i> Penguji Eksternal (DU-DI / LSP / Asosiasi)
              </h6>
              <div class="p-3 bg-light rounded border mb-2">
                <div class="form-group mb-2">
                  <label class="small font-weight-bold text-dark mb-1">Nama Asesor Eksternal</label>
                  <input type="text" name="penguji_eksternal_nama[]" class="form-control form-control-sm" placeholder="Contoh: Bpk. Hendra Gunawan, S.T.">
                </div>
                <div class="form-group mb-0">
                  <label class="small font-weight-bold text-dark mb-1">Nama Instansi / Perusahaan</label>
                  <input type="text" name="penguji_eksternal_instansi[]" class="form-control form-control-sm" placeholder="Contoh: PT Telkom Akses / LSP Informatika">
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Card 4: Daftarkan Peserta Ujian -->
      <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
        <div class="card-header bg-white py-3 border-bottom">
          <h5 class="card-title text-dark font-weight-bold mb-0">
            <i class="fas fa-user-graduate text-primary mr-2"></i> 4. Peserta Ujian (Pilih Kelas)
          </h5>
          <p class="text-muted small mb-0 mt-1">Seluruh siswa aktif di kelas yang Anda centang akan otomatis didaftarkan sebagai peserta kegiatan UKK ini.</p>
        </div>
        <div class="card-body p-4">
          <div class="row">
            @forelse($kelas as $k)
              <div class="col-md-4 col-sm-6 mb-2">
                <div class="custom-control custom-checkbox p-2 rounded bg-light border">
                  <input type="checkbox" class="custom-control-input" id="kls_{{ $k->id_kelas }}" name="id_kelas[]" value="{{ $k->id_kelas }}">
                  <label class="custom-control-label font-weight-bold text-dark d-block" for="kls_{{ $k->id_kelas }}" style="cursor: pointer;">
                    {{ $k->nama_kelas }}
                    <small class="text-muted d-block font-weight-normal">
                      {{ $k->siswa_count ?? '' }} Siswa
                    </small>
                  </label>
                </div>
              </div>
            @empty
              <div class="col-12 text-muted">Belum ada kelas yang terdaftar.</div>
            @endforelse
          </div>
        </div>
      </div>

      <!-- Footer Tombol Simpan -->
      <div class="d-flex align-items-center justify-content-between mb-5">
        <a href="/admin/kejuruan/uji-kompetensi" class="btn btn-outline-secondary px-4 font-weight-bold" style="border-radius: 8px;">
          <i class="fas fa-arrow-left mr-1"></i> Batal
        </a>
        <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold" style="border-radius: 8px;">
          <i class="fas fa-save mr-1"></i> Simpan & Buat Kegiatan UKK
        </button>
      </div>

    </form>
  </div>
</div>
@endsection
