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
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Tambah Capaian & Tujuan Pembelajaran (CP / TP)</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Penyusunan standar capaian kompetensi dan alur tujuan pembelajaran Kurikulum Merdeka Kejuruan</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/kompetensi" class="text-primary font-weight-500">Capaian & TP</a></li>
          <li class="breadcrumb-item active">Tambah CP/TP</li>
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
              <i class="fas fa-plus-circle text-primary mr-2"></i> Formulir Capaian & Tujuan Pembelajaran
            </h5>
            <span class="badge badge-light border text-primary px-2 py-1 font-weight-normal small">
              Kurikulum Merdeka
            </span>
          </div>

          <form action="/admin/kompetensi" method="POST">
            @csrf
            <div class="card-body p-4 pt-3">
              <!-- Program Keahlian / Jurusan -->
              <div class="form-group mb-3">
                <label for="id_jurusan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Program Keahlian / Jurusan <span class="text-danger">*</span>
                </label>
                <select name="id_jurusan" id="id_jurusan" class="form-control select2bs4 @error('id_jurusan') is-invalid @enderror" required style="border-radius: 8px;">
                  <option value="">Pilih Program Keahlian</option>
                  @foreach($jurusans as $j)
                    <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                      {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                    </option>
                  @endforeach
                </select>
                <small class="form-text text-muted">Kompetensi akan terikat pada kurikulum jurusan kejuruan yang dipilih.</small>
                @error('id_jurusan')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <!-- Kodefikasi CP / TP -->
              <div class="form-group mb-3">
                <label for="kode_kompetensi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Kodefikasi Capaian / TP <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-barcode text-muted"></i></span>
                  </div>
                  <input type="text" class="form-control border-left-0 @error('kode_kompetensi') is-invalid @enderror" id="kode_kompetensi" placeholder="Contoh: CP-TKJ-01, TP-01, atau EL-02" name="kode_kompetensi" value="{{ old('kode_kompetensi') }}" required style="border-radius: 0 8px 8px 0; font-weight: 600;">
                </div>
                <small class="form-text text-muted">Format kode identifikasi Capaian Pembelajaran (CP) atau butir Tujuan Pembelajaran (TP).</small>
                @error('kode_kompetensi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <!-- Judul Capaian / Tujuan Pembelajaran -->
              <div class="form-group mb-3">
                <label for="nama_kompetensi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Rumusan Capaian / Tujuan Pembelajaran <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('nama_kompetensi') is-invalid @enderror" id="nama_kompetensi" placeholder="Contoh: Mengkonfigurasi Routing Dinamis BGP pada Jaringan Enterprise" name="nama_kompetensi" value="{{ old('nama_kompetensi') }}" required style="border-radius: 8px;">
                <small class="form-text text-muted">Rumusan kompetensi operasional mencakup kompetensi keterampilan kerja dan materi ajar.</small>
                @error('nama_kompetensi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <!-- Baris Fase/Tingkat, Semester, KKTP -->
              <div class="row">
                <div class="col-md-5 form-group mb-3">
                  <label for="tingkat" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Fase / Tingkat Kelas <span class="text-danger">*</span>
                  </label>
                  <select name="tingkat" id="tingkat" class="form-control @error('tingkat') is-invalid @enderror" required style="border-radius: 8px;">
                    <option value="">Pilih Fase & Kelas</option>
                    <option value="X" {{ old('tingkat') == 'X' ? 'selected' : '' }}>Fase E — Kelas X (Dasar Kejuruan)</option>
                    <option value="XI" {{ old('tingkat') == 'XI' ? 'selected' : '' }}>Fase F — Kelas XI (Konsentrasi)</option>
                    <option value="XII" {{ old('tingkat') == 'XII' ? 'selected' : '' }}>Fase F — Kelas XII (Proyek & UKK)</option>
                  </select>
                  @error('tingkat')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-3 form-group mb-3">
                  <label for="semester" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Semester <span class="text-danger">*</span>
                  </label>
                  <select name="semester" id="semester" class="form-control @error('semester') is-invalid @enderror" required style="border-radius: 8px;">
                    <option value="">Pilih Semester</option>
                    <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                    <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                  </select>
                  @error('semester')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-4 form-group mb-3">
                  <label for="kkm" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Batas KKTP <span class="text-muted">(Min Kelulusan)</span>
                  </label>
                  <div class="input-group">
                    <input type="number" step="0.1" min="0" max="100" class="form-control @error('kkm') is-invalid @enderror" id="kkm" name="kkm" value="{{ old('kkm', 75.00) }}" style="border-radius: 8px 0 0 8px; font-weight: bold;">
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

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center justify-content-between">
              <a href="/admin/kompetensi" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Batal
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Capaian / TP
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Kolom Informasi & Petunjuk Standar (Kanan) -->
      <div class="col-12 col-lg-5 mb-4">
        <!-- Kartu 1: Pedoman Kurikulum Merdeka -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 14px;">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-info text-white mr-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
              <i class="fas fa-graduation-cap"></i>
            </div>
            <h6 class="font-weight-bold text-dark mb-0">Struktur Kurikulum Merdeka SMK</h6>
          </div>
          <div class="card-body p-3 small text-secondary">
            <div class="mb-2">
              <strong class="text-dark d-block">1. Pembagian Fase SMK:</strong>
              <ul class="pl-3 mb-2">
                <li class="mb-1"><strong>Fase E (Kelas X):</strong> Dasar-dasar Program Keahlian, pembentukan fondasi kerja, K3LH, dan etika profesi.</li>
                <li><strong>Fase F (Kelas XI & XII):</strong> Konsentrasi Keahlian, pembelajaran berbasis proyek (PjBL), PKL di industri, dan sertifikasi UKK.</li>
              </ul>
            </div>
            <div class="mb-2">
              <strong class="text-dark d-block">2. Hubungan CP & TP:</strong>
              <p class="mb-1 text-muted" style="font-size: 0.8rem;">
                <strong>Capaian Pembelajaran (CP)</strong> merupakan kompetensi makro per fase. Guru menurunkan CP menjadi butir-butir <strong>Tujuan Pembelajaran (TP)</strong> yang dievaluasi melalui modul praktik.
              </p>
            </div>
            <div class="p-2 rounded bg-light border font-monospace" style="font-size: 0.78rem;">
              <i class="fas fa-lightbulb text-warning mr-1"></i> Contoh penamaan: <code>CP-TKJ-01</code> (Capaian Elemen), <code>TP-ROUTING-01</code> (Tujuan Pembelajaran).
            </div>
          </div>
        </div>

        <!-- Kartu 2: Integrasi Sistem Kejuruan -->
        <div class="card border-0 shadow-sm" style="border-radius: 14px; background: linear-gradient(to bottom, #ffffff, #f8fafc);">
          <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-success text-white mr-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
              <i class="fas fa-network-wired"></i>
            </div>
            <h6 class="font-weight-bold text-dark mb-0">Integrasi Otomatis Pembelajaran</h6>
          </div>
          <div class="card-body p-3 small">
            <div class="d-flex align-items-start mb-3">
              <div class="text-primary font-weight-bold mr-2 mt-1"><i class="fas fa-check-circle"></i></div>
              <div>
                <strong class="text-dark d-block">Bank Modul & Jobsheet:</strong>
                <span class="text-muted">Setiap lembar kerja praktikum dikaitkan dengan CP/TP ini sebagai bukti materi ajar.</span>
              </div>
            </div>
            <div class="d-flex align-items-start mb-3">
              <div class="text-primary font-weight-bold mr-2 mt-1"><i class="fas fa-check-circle"></i></div>
              <div>
                <strong class="text-dark d-block">Penilaian & KKTP:</strong>
                <span class="text-muted">Hasil asesmen praktik siswa otomatis dievaluasi terhadap ambang batas KKTP yang ditetapkan.</span>
              </div>
            </div>
            <div class="d-flex align-items-start">
              <div class="text-primary font-weight-bold mr-2 mt-1"><i class="fas fa-check-circle"></i></div>
              <div>
                <strong class="text-dark d-block">Portofolio & Transkrip Siswa:</strong>
                <span class="text-muted">Kompetensi yang tuntas otomatis tercatat dalam rekam jejak portofolio keahlian siswa.</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
