@extends($layout)
@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-tasks"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Tambah Tujuan Pembelajaran (TP / ATP)</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Penyusunan Alur Tujuan Pembelajaran (ATP) dan unit kompetensi Kurikulum Merdeka Kejuruan</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kompetensi" class="text-primary font-weight-500">CP & ATP</a></li>
          <li class="breadcrumb-item active">Tambah TP</li>
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
      <div class="col-12 col-lg-8 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-plus-circle text-primary mr-2"></i> Formulir Tujuan Pembelajaran (TP)
            </h5>
            <span class="badge badge-light border text-primary px-2 py-1 font-weight-normal small">
              Kurikulum Merdeka
            </span>
          </div>

          <form action="/admin/kompetensi" method="POST">
            @csrf
            <div class="card-body p-4 pt-3">
              <!-- Baris Program Keahlian & Induk Elemen CP -->
              <div class="row">
                <div class="col-md-6 form-group mb-3">
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
                  @error('id_jurusan')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                  <label for="id_cp" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Induk Elemen Capaian Pembelajaran (CP)
                  </label>
                  <select name="id_cp" id="id_cp" class="form-control @error('id_cp') is-invalid @enderror" style="border-radius: 8px;">
                    <option value="">-- Tanpa Induk (TP Mandiri) --</option>
                    @foreach($capaianList as $cp)
                      <option value="{{ $cp->id_cp }}" 
                              data-jurusan="{{ $cp->id_jurusan }}" 
                              data-fase="{{ $cp->fase }}"
                              {{ (old('id_cp', $selectedCpId) == $cp->id_cp) ? 'selected' : '' }}>
                        [{{ $cp->fase_label }}] {{ $cp->kode_elemen }} - {{ $cp->nama_elemen }} ({{ $cp->jurusan->nama_singkat ?? '-' }})
                      </option>
                    @endforeach
                  </select>
                  <small class="form-text text-muted">Pilih Elemen CP induk agar TP masuk ke dalam Alur Tujuan Pembelajaran (ATP).</small>
                  @error('id_cp')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <!-- Baris Urutan Alur ATP & Alokasi JP -->
              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label for="urutan_atp" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Nomor Alur dalam CP (ATP #)
                  </label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-light text-muted"><i class="fas fa-sort-numeric-down"></i></span>
                    </div>
                    <input type="number" min="1" max="99" class="form-control @error('urutan_atp') is-invalid @enderror" id="urutan_atp" placeholder="Contoh: 1, 2, 3..." name="urutan_atp" value="{{ old('urutan_atp') }}" style="border-radius: 0 8px 8px 0;">
                  </div>
                  <small class="form-text text-muted">Urutan pembelajaran TP ini di dalam fase/elemen CP terkait.</small>
                  @error('urutan_atp')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                  <label for="alokasi_jp" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Alokasi Jam Pelajaran (JP)
                  </label>
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text bg-light text-muted"><i class="fas fa-clock"></i></span>
                    </div>
                    <input type="number" min="1" max="500" class="form-control @error('alokasi_jp') is-invalid @enderror" id="alokasi_jp" placeholder="Contoh: 18" name="alokasi_jp" value="{{ old('alokasi_jp') }}" style="border-radius: 0 8px 8px 0;">
                    <div class="input-group-append">
                      <span class="input-group-text bg-light text-muted small">JP</span>
                    </div>
                  </div>
                  <small class="form-text text-muted">Perkiraan durasi JP pembelajaran teori & praktikum.</small>
                  @error('alokasi_jp')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <!-- Kodefikasi TP -->
              <div class="form-group mb-3">
                <label for="kode_kompetensi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Kodefikasi Tujuan Pembelajaran (Kode TP) <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-light border-right-0"><i class="fas fa-barcode text-muted"></i></span>
                  </div>
                  <input type="text" class="form-control border-left-0 @error('kode_kompetensi') is-invalid @enderror" id="kode_kompetensi" placeholder="Contoh: TP-01, TP-TKJ-101, atau EL1-TP02" name="kode_kompetensi" value="{{ old('kode_kompetensi') }}" required style="border-radius: 0 8px 8px 0; font-weight: 600;">
                </div>
                <small class="form-text text-muted">Format kode identifikasi Tujuan Pembelajaran (TP) yang akan tampil di modul praktik dan penilaian.</small>
                @error('kode_kompetensi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <!-- Judul Rumusan Tujuan Pembelajaran -->
              <div class="form-group mb-3">
                <label for="nama_kompetensi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Rumusan Tujuan Pembelajaran (TP) <span class="text-danger">*</span>
                </label>
                <textarea rows="2" class="form-control @error('nama_kompetensi') is-invalid @enderror" id="nama_kompetensi" placeholder="Contoh: Siswa mampu merancang topologi jaringan lokal (LAN) berbasis mikrotik dengan teliti dan sesuai SOP" name="nama_kompetensi" required style="border-radius: 8px;">{{ old('nama_kompetensi') }}</textarea>
                <small class="form-text text-muted">Rumusan operasional kemampuan peserta didik (memuat kompetensi + konten materi).</small>
                @error('nama_kompetensi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <!-- Ruang Lingkup Materi Pokok -->
              <div class="form-group mb-3">
                <label for="materi_pokok" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Ruang Lingkup Materi Pokok
                </label>
                <input type="text" class="form-control @error('materi_pokok') is-invalid @enderror" id="materi_pokok" placeholder="Contoh: IP Subnetting, VLSM, Topologi Bintang, Kabel UTP Cat6" name="materi_pokok" value="{{ old('materi_pokok') }}" style="border-radius: 8px;">
                <small class="form-text text-muted">Ringkasan pokok bahasan modul ajar / job sheet praktikum.</small>
                @error('materi_pokok')
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
                <i class="fas fa-save mr-1"></i> Simpan Tujuan Pembelajaran
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Kolom Petunjuk & Standar Kurikulum Merdeka (Kanan) -->
      <div class="col-12 col-lg-4 mb-4">
        <!-- Kartu 1: Pedoman Hierarki CP-ATP -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 14px;">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-info text-white mr-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
              <i class="fas fa-sitemap"></i>
            </div>
            <h6 class="font-weight-bold text-dark mb-0">Hierarki CP & ATP SMK</h6>
          </div>
          <div class="card-body p-3 small text-secondary">
            <div class="mb-2">
              <strong class="text-dark d-block">1. Hubungan Induk & Anak:</strong>
              <p class="mb-1 text-muted">
                <strong>Elemen CP (Parent):</strong> Elemen standar capaian nasional (misal: Jaringan Komputer).
              </p>
              <p class="mb-1 text-muted">
                <strong>Tujuan Pembelajaran (Child):</strong> Alur langkah operasional pembelajaran per topik praktikum.
              </p>
            </div>
            <div class="mb-2">
              <strong class="text-dark d-block">2. Manfaat Pemisahan:</strong>
              <ul class="pl-3 mb-2 text-muted">
                <li>Menghindari pengetikan teks CP berulang-ulang di setiap modul.</li>
                <li>Modul & Job Sheet langsung memilih TP yang spesifik.</li>
                <li>Otomatis menghitung total jam pelajaran (JP) per elemen capaian.</li>
              </ul>
            </div>
          </div>
        </div>

        <!-- Kartu 2: Integrasi Otomatis Pembelajaran -->
        <div class="card border-0 shadow-sm" style="border-radius: 14px; background: linear-gradient(to bottom, #ffffff, #f8fafc);">
          <div class="card-header bg-transparent py-3 border-bottom d-flex align-items-center">
            <div class="rounded-circle d-flex align-items-center justify-content-center bg-success text-white mr-2" style="width: 32px; height: 32px; font-size: 0.9rem;">
              <i class="fas fa-network-wired"></i>
            </div>
            <h6 class="font-weight-bold text-dark mb-0">Integrasi Otomatis</h6>
          </div>
          <div class="card-body p-3 small">
            <div class="d-flex align-items-start mb-3">
              <div class="text-primary font-weight-bold mr-2 mt-1"><i class="fas fa-check-circle"></i></div>
              <div>
                <strong class="text-dark d-block">Bank Modul & Jobsheet:</strong>
                <span class="text-muted">Job sheet praktikum dikaitkan dengan TP ini sebagai rujukan materi.</span>
              </div>
            </div>
            <div class="d-flex align-items-start mb-3">
              <div class="text-primary font-weight-bold mr-2 mt-1"><i class="fas fa-check-circle"></i></div>
              <div>
                <strong class="text-dark d-block">Penilaian & KKTP:</strong>
                <span class="text-muted">Hasil nilai praktikum dinilai terhadap ambang batas KKTP TP ini.</span>
              </div>
            </div>
            <div class="d-flex align-items-start">
              <div class="text-primary font-weight-bold mr-2 mt-1"><i class="fas fa-check-circle"></i></div>
              <div>
                <strong class="text-dark d-block">Portofolio & UKK:</strong>
                <span class="text-muted">Kelulusan TP ini otomatis tercatat dalam portofolio keahlian siswa.</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
  // Sync Jurusan when Induk CP is selected
  $('#id_cp').on('change', function() {
    var selected = $(this).find(':selected');
    var jurusanId = selected.data('jurusan');
    var fase = selected.data('fase');
    
    if (jurusanId) {
      $('#id_jurusan').val(jurusanId).trigger('change');
    }
    if (fase === 'E') {
      $('#tingkat').val('X');
    } else if (fase === 'F') {
      if (!$('#tingkat').val() || $('#tingkat').val() === 'X') {
        $('#tingkat').val('XI');
      }
    }
  });
});
</script>
@endpush
@endsection
