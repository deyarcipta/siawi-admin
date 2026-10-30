@extends($layout)
@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-award"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Ubah Standar Kompetensi</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Perbarui data standar kompetensi kejuruan dan batas ambang ketuntasan</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/kompetensi" class="text-primary font-weight-500">Data Kompetensi</a></li>
          <li class="breadcrumb-item active">Ubah Kompetensi</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="border-radius: 12px;">
        <div class="d-flex align-items-center">
          <i class="fas fa-exclamation-triangle mr-2 text-danger" style="font-size: 1.2rem;"></i>
          <strong>Terdapat kesalahan pengisian data:</strong>
        </div>
        <ul class="mb-0 mt-2 pl-3 small">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="row">
      <!-- Kolom Formulir Utama (Kiri) -->
      <div class="col-12 col-lg-7 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Ubah Kompetensi
            </h5>
            <span class="badge badge-info px-2 py-1 font-weight-normal small">
              {{ $kompetensi->kode_kompetensi }}
            </span>
          </div>

          <form action="/admin/kompetensi/{{ $kompetensi->id_kompetensi }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body p-4 pt-3">
              <!-- Program Keahlian / Jurusan -->
              <div class="form-group mb-3">
                <label for="id_jurusan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Program Keahlian / Jurusan <span class="text-danger">*</span>
                </label>
                <select name="id_jurusan" id="id_jurusan" class="form-control select2bs4 @error('id_jurusan') is-invalid @enderror" required style="border-radius: 8px;">
                  <option value="">Pilih Program Keahlian</option>
                  @foreach($jurusans as $j)
                    <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan', $kompetensi->id_jurusan) == $j->id_jurusan ? 'selected' : '' }}>
                      {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                    </option>
                  @endforeach
                </select>
                @error('id_jurusan')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <!-- Kode Kompetensi -->
              <div class="form-group mb-3">
                <label for="kode_kompetensi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Kode Kompetensi / Unit Keahlian <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-barcode text-muted"></i></span>
                  </div>
                  <input type="text" class="form-control border-left-0 @error('kode_kompetensi') is-invalid @enderror" id="kode_kompetensi" placeholder="Contoh: TKJ-KD-01" name="kode_kompetensi" value="{{ old('kode_kompetensi', $kompetensi->kode_kompetensi) }}" required style="border-radius: 0 8px 8px 0; font-weight: 600;">
                </div>
                @error('kode_kompetensi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <!-- Nama Capaian / Kompetensi -->
              <div class="form-group mb-3">
                <label for="nama_kompetensi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Nama Capaian / Standar Kompetensi <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('nama_kompetensi') is-invalid @enderror" id="nama_kompetensi" placeholder="Contoh: Mengkonfigurasi Routing Dinamis BGP" name="nama_kompetensi" value="{{ old('nama_kompetensi', $kompetensi->nama_kompetensi) }}" required style="border-radius: 8px;">
                @error('nama_kompetensi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <!-- Baris Tingkat, Semester, KKM -->
              <div class="row">
                <div class="col-md-4 form-group mb-3">
                  <label for="tingkat" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Tingkat Kelas <span class="text-danger">*</span>
                  </label>
                  <select name="tingkat" id="tingkat" class="form-control @error('tingkat') is-invalid @enderror" required style="border-radius: 8px;">
                    <option value="">Pilih Tingkat</option>
                    <option value="X" {{ old('tingkat', $kompetensi->tingkat) == 'X' ? 'selected' : '' }}>Kelas X</option>
                    <option value="XI" {{ old('tingkat', $kompetensi->tingkat) == 'XI' ? 'selected' : '' }}>Kelas XI</option>
                    <option value="XII" {{ old('tingkat', $kompetensi->tingkat) == 'XII' ? 'selected' : '' }}>Kelas XII</option>
                  </select>
                  @error('tingkat')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-4 form-group mb-3">
                  <label for="semester" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Semester <span class="text-danger">*</span>
                  </label>
                  <select name="semester" id="semester" class="form-control @error('semester') is-invalid @enderror" required style="border-radius: 8px;">
                    <option value="">Pilih Semester</option>
                    <option value="1" {{ old('semester', $kompetensi->semester) == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                    <option value="2" {{ old('semester', $kompetensi->semester) == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                  </select>
                  @error('semester')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-4 form-group mb-3">
                  <label for="kkm" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Batas KKM <span class="text-muted">(Min Kelulusan)</span>
                  </label>
                  <div class="input-group">
                    <input type="number" step="0.1" min="0" max="100" class="form-control @error('kkm') is-invalid @enderror" id="kkm" name="kkm" value="{{ old('kkm', $kompetensi->kkm ?? 75.00) }}" style="border-radius: 8px 0 0 8px; font-weight: bold;">
                    <div class="input-group-append">
                      <span class="input-group-text bg-light text-muted small" style="border-radius: 0 8px 8px 0;">Poin</span>
                    </div>
                  </div>
                  @error('kkm')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>
            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center justify-content-end" style="gap: 10px;">
              <a href="/admin/kompetensi" class="btn btn-outline-secondary px-3 mr-2" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Batal
              </a>
              <button type="submit" class="btn btn-primary px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Kolom Informasi & Petunjuk Standar (Kanan) -->
      <div class="col-12 col-lg-5 mb-4">
        <!-- Kartu 1: Pedoman Penamaan Kode -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 14px;">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-info text-white mr-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
              <i class="fas fa-info"></i>
            </div>
            <h6 class="font-weight-bold text-dark mb-0">Petunjuk Kodefikasi Kompetensi</h6>
          </div>
          <div class="card-body p-3 small text-secondary">
            <p class="mb-2">
              Perubahan pada nama atau kode kompetensi akan tersinkronisasi ke:
            </p>
            <ul class="pl-3 mb-2">
              <li class="mb-1">Seluruh <strong>Bank Modul Praktik</strong> yang menggunakan kompetensi ini.</li>
              <li class="mb-1">Rekam data <strong>Penilaian Praktik</strong> pada rombel kelas.</li>
              <li>Riwayat <strong>Transkrip Portofolio Siswa</strong> yang telah divalidasi.</li>
            </ul>
            <div class="text-muted" style="font-size: 0.8rem;">
              <i class="fas fa-lightbulb text-warning mr-1"></i> Pastikan kode kompetensi tetap unik dalam 1 program keahlian.
            </div>
          </div>
        </div>

        <!-- Kartu 2: Integrasi Sistem Kejuruan -->
        <div class="card border-0 shadow-sm" style="border-radius: 14px; background: linear-gradient(to bottom, #ffffff, #f8fafc);">
          <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-success text-white mr-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
              <i class="fas fa-network-wired"></i>
            </div>
            <h6 class="font-weight-bold text-dark mb-0">Status Keterkaitan Data</h6>
          </div>
          <div class="card-body p-3 small">
            <div class="d-flex align-items-start mb-3">
              <div class="text-primary font-weight-bold mr-2 mt-1"><i class="fas fa-layer-group"></i></div>
              <div>
                <strong class="text-dark d-block">Jumlah Modul Praktik Terhubung:</strong>
                <span class="text-muted">{{ $kompetensi->modulPraktiks()->count() }} Modul Praktik</span>
              </div>
            </div>
            <div class="d-flex align-items-start">
              <div class="text-primary font-weight-bold mr-2 mt-1"><i class="fas fa-user-graduate"></i></div>
              <div>
                <strong class="text-dark d-block">Capaian Siswa Terkoneksi:</strong>
                <span class="text-muted">{{ $kompetensi->capaianSiswas()->count() }} Rekor Nilai Siswa</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
