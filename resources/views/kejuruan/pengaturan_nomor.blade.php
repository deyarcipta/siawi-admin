@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-barcode text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">
            @if($canAccessTkk && $canAccessSertifikat)
              Format Nomor Piagam & Sertifikat
            @elseif($canAccessTkk)
              Format Nomor Piagam TKK
            @else
              Format Nomor Sertifikat Kejuruan
            @endif
          </h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">
            @if($canAccessTkk && $canAccessSertifikat)
              Pengaturan mandiri format nomor seri Piagam TKK dan Sertifikat Uji Kompetensi Kejuruan (terpisah dari nomor surat sekolah)
            @elseif($canAccessTkk)
              Pengaturan mandiri format nomor seri Piagam Tanda Kecakapan Khusus (TKK) Kejuruan
            @else
              Pengaturan mandiri format nomor seri Sertifikat Uji Kompetensi Kejuruan & Portofolio Siswa
            @endif
          </p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          @if($canAccessTkk)
            <li class="breadcrumb-item"><a href="/admin/kejuruan/tkk" class="text-primary font-weight-500">Kejuruan</a></li>
          @else
            <li class="breadcrumb-item"><a href="/admin/kejuruan/portofolio" class="text-primary font-weight-500">Kejuruan</a></li>
          @endif
          <li class="breadcrumb-item active">Format Nomor Kejuruan</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="border-radius: 12px;">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="border-radius: 12px;">
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

    <!-- Information Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); color: #ffffff;">
      <div class="card-body p-4">
        <div class="d-flex align-items-start">
          <div class="rounded-circle d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px; background: rgba(255,255,255,0.18); font-size: 1.3rem; flex-shrink: 0;">
            <i class="fas fa-info text-white"></i>
          </div>
          <div>
            <h5 class="font-weight-bold mb-1">Standarisasi Penomoran Dokumen Kejuruan</h5>
            <p class="mb-2" style="font-size: 0.9rem; opacity: 0.92; line-height: 1.5;">
              Penomoran ini dikelola secara independen oleh Tim Kejuruan & Kaprog agar tidak bercampur dengan buku agenda surat umum sekolah. Gunakan token variabel seperti <code>{NO}</code>, <code>{JURUSAN}</code>@if($canAccessTkk), <code>{KLASTER}</code>@endif, <code>{ROMAWI}</code>, dan <code>{TAHUN}</code> agar nomor digenerate otomatis saat pengesahan siswa.
            </p>
            <div class="d-flex flex-wrap" style="gap: 6px;">
              <span class="badge badge-light text-primary font-weight-bold py-1 px-2">{NO} : Nomor Urut Berjalan</span>
              <span class="badge badge-light text-primary font-weight-bold py-1 px-2">{JURUSAN} : Kode Program Keahlian</span>
              @if($canAccessTkk)
                <span class="badge badge-light text-primary font-weight-bold py-1 px-2">{KLASTER} : Nomor Klaster (KL1, KL2)</span>
                <span class="badge badge-light text-primary font-weight-bold py-1 px-2">{KODE_TKK} : Kode Khusus TKK</span>
              @endif
              <span class="badge badge-light text-primary font-weight-bold py-1 px-2">{ROMAWI} : Bulan Romawi Saat Ini</span>
              <span class="badge badge-light text-primary font-weight-bold py-1 px-2">{BULAN} : 2 Digit Bulan (01-12)</span>
              <span class="badge badge-light text-primary font-weight-bold py-1 px-2">{TAHUN} : 4 Digit Tahun ({{ date('Y') }})</span>
              <span class="badge badge-light text-primary font-weight-bold py-1 px-2">{TA} : Tahun Ajaran Aktif</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <form action="{{ url('/admin/kejuruan/pengaturan-nomor') }}" method="POST">
      @csrf

      @php
        $cardColClass = ($canAccessTkk && $canAccessSertifikat) ? 'col-lg-6 mb-4' : 'col-lg-8 mx-auto mb-4';
      @endphp

      <div class="row">
        @if($canAccessTkk)
        <!-- 1. Format Nomor Piagam TKK -->
        <div class="{{ $cardColClass }}">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                <div class="rounded d-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px; background: #eff6ff; color: #2563eb;">
                  <i class="fas fa-medal"></i>
                </div>
                <div>
                  <h6 class="font-weight-bold text-dark mb-0">{{ ($canAccessTkk && $canAccessSertifikat) ? '1. Piagam Tanda Kecakapan Khusus (TKK)' : 'Piagam Tanda Kecakapan Khusus (TKK)' }}</h6>
                  <small class="text-muted">Diterbitkan pada penganugerahan lencana keahlian siswa</small>
                </div>
              </div>
              <span class="badge badge-primary px-2 py-1">TKK</span>
            </div>
            <div class="card-body p-4">
              <!-- Live Preview Box -->
              <div class="p-3 mb-3 rounded" style="background: #f8fafc; border: 1.5px dashed #cbd5e1;">
                <div class="small font-weight-bold text-muted text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                  Pratinjau Nomor TKK Berikutnya:
                </div>
                <div class="font-weight-bold text-primary" id="preview_tkk" style="font-size: 1.15rem; font-family: 'Courier New', Courier, monospace; word-break: break-all;">
                  TKK/{{ $sampleJurusan }}/{{ $sampleKlaster }}/{{ \App\Models\Setting::getRomanMonth() }}/{{ date('Y') }}/0001
                </div>
                <small class="text-muted mt-1 d-block" style="font-size: 0.76rem;">Simulasi menggunakan contoh Jurusan {{ $sampleJurusan }} dan Klaster 1.</small>
              </div>

              <!-- Input Template Format -->
              <div class="form-group mb-3">
                <label class="font-weight-bold text-dark small mb-1">
                  Pola Format Nomor TKK <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <input type="text" name="tkk_format" id="input_tkk_format" class="form-control font-weight-500 font-monospace" value="{{ old('tkk_format', $nomorSettings['tkk']['format']) }}" required placeholder="TKK/{JURUSAN}/{KLASTER}/{ROMAWI}/{TAHUN}/{NO}">
                </div>
                <small class="text-muted d-block mt-1">Klik token di bawah untuk menyisipkan ke format:</small>
                <div class="d-flex flex-wrap mt-1" style="gap: 5px;">
                  <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 token-btn" data-target="input_tkk_format" data-token="{NO}">+{NO}</button>
                  <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 token-btn" data-target="input_tkk_format" data-token="{JURUSAN}">+{JURUSAN}</button>
                  <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 token-btn" data-target="input_tkk_format" data-token="{KLASTER}">+{KLASTER}</button>
                  <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 token-btn" data-target="input_tkk_format" data-token="{KODE_TKK}">+{KODE_TKK}</button>
                  <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 token-btn" data-target="input_tkk_format" data-token="{ROMAWI}">+{ROMAWI}</button>
                  <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 token-btn" data-target="input_tkk_format" data-token="{BULAN}">+{BULAN}</button>
                  <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 token-btn" data-target="input_tkk_format" data-token="{TAHUN}">+{TAHUN}</button>
                  <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 token-btn" data-target="input_tkk_format" data-token="{TA}">+{TA}</button>
                </div>
              </div>

              <div class="row">
                <!-- Digit Padding -->
                <div class="col-sm-6 form-group mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Digit Nomor Urut {NO}</label>
                  <select name="tkk_padding" id="input_tkk_padding" class="form-control">
                    <option value="3" {{ old('tkk_padding', $nomorSettings['tkk']['padding']) == 3 ? 'selected' : '' }}>3 Digit (Contoh: 001)</option>
                    <option value="4" {{ old('tkk_padding', $nomorSettings['tkk']['padding']) == 4 ? 'selected' : '' }}>4 Digit (Contoh: 0001)</option>
                    <option value="5" {{ old('tkk_padding', $nomorSettings['tkk']['padding']) == 5 ? 'selected' : '' }}>5 Digit (Contoh: 00001)</option>
                  </select>
                </div>

                <!-- Periode Reset -->
                <div class="col-sm-6 form-group mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Siklus Reset Nomor Urut</label>
                  <select name="tkk_reset_periode" class="form-control">
                    <option value="tahun" {{ old('tkk_reset_periode', $nomorSettings['tkk']['reset_periode']) === 'tahun' ? 'selected' : '' }}>Setiap Ganti Tahun (1 Jan)</option>
                    <option value="tahun_ajaran" {{ old('tkk_reset_periode', $nomorSettings['tkk']['reset_periode']) === 'tahun_ajaran' ? 'selected' : '' }}>Setiap Tahun Ajaran Baru</option>
                    <option value="tidak_pernah" {{ old('tkk_reset_periode', $nomorSettings['tkk']['reset_periode']) === 'tidak_pernah' ? 'selected' : '' }}>Terus Menerus (Tidak Reset)</option>
                  </select>
                </div>
              </div>

              <!-- Counter Saat Ini -->
              <div class="form-group mb-0 pt-2 border-top">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                  <div>
                    <label class="font-weight-bold text-dark small mb-0">Nomor Urut Terakhir Saat Ini</label>
                    <small class="text-muted d-block">Nomor berikutnya akan bernilai: [Nomor Terakhir + 1]</small>
                  </div>
                  <div class="d-flex align-items-center mt-2 mt-sm-0" style="gap: 8px;">
                    <input type="number" name="tkk_counter" id="input_tkk_counter" class="form-control text-center font-weight-bold" style="width: 90px;" min="0" value="{{ old('tkk_counter', $nomorSettings['tkk']['counter']) }}" required>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="openResetModal('tkk')" title="Reset Counter TKK">
                      <i class="fas fa-undo"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endif

        @if($canAccessSertifikat)
        <!-- 2. Format Nomor Sertifikat UKK & Portofolio -->
        <div class="{{ $cardColClass }}">
          <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
              <div class="d-flex align-items-center">
                <div class="rounded d-flex align-items-center justify-content-center mr-2" style="width: 32px; height: 32px; background: #ecfdf5; color: #059669;">
                  <i class="fas fa-certificate"></i>
                </div>
                <div>
                  <h6 class="font-weight-bold text-dark mb-0">{{ ($canAccessTkk && $canAccessSertifikat) ? '2. Sertifikat UKK & Portofolio Siswa' : 'Sertifikat UKK & Portofolio Siswa' }}</h6>
                  <small class="text-muted">Diterbitkan pada lembar sertifikat kompetensi keahlian</small>
                </div>
              </div>
              <span class="badge badge-success px-2 py-1">Sertifikat</span>
            </div>
            <div class="card-body p-4">
              <!-- Live Preview Box -->
              <div class="p-3 mb-3 rounded" style="background: #f8fafc; border: 1.5px dashed #cbd5e1;">
                <div class="small font-weight-bold text-muted text-uppercase mb-1" style="font-size: 0.72rem; letter-spacing: 0.5px;">
                  Pratinjau Nomor Sertifikat Berikutnya:
                </div>
                <div class="font-weight-bold text-success" id="preview_sertifikat" style="font-size: 1.15rem; font-family: 'Courier New', Courier, monospace; word-break: break-all;">
                  SERT-UKK/{{ $sampleJurusan }}/{{ \App\Models\Setting::getRomanMonth() }}/{{ date('Y') }}/0001
                </div>
                <small class="text-muted mt-1 d-block" style="font-size: 0.76rem;">Simulasi menggunakan contoh Jurusan {{ $sampleJurusan }}.</small>
              </div>

              <!-- Input Template Format -->
              <div class="form-group mb-3">
                <label class="font-weight-bold text-dark small mb-1">
                  Pola Format Nomor Sertifikat <span class="text-danger">*</span>
                </label>
                <div class="input-group">
                  <input type="text" name="sertifikat_format" id="input_sertifikat_format" class="form-control font-weight-500 font-monospace" value="{{ old('sertifikat_format', $nomorSettings['sertifikat']['format']) }}" required placeholder="SERT-UKK/{JURUSAN}/{ROMAWI}/{TAHUN}/{NO}">
                </div>
                <small class="text-muted d-block mt-1">Klik token di bawah untuk menyisipkan ke format:</small>
                <div class="d-flex flex-wrap mt-1" style="gap: 5px;">
                  <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 token-btn" data-target="input_sertifikat_format" data-token="{NO}">+{NO}</button>
                  <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 token-btn" data-target="input_sertifikat_format" data-token="{JURUSAN}">+{JURUSAN}</button>
                  <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 token-btn" data-target="input_sertifikat_format" data-token="{ROMAWI}">+{ROMAWI}</button>
                  <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 token-btn" data-target="input_sertifikat_format" data-token="{BULAN}">+{BULAN}</button>
                  <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 token-btn" data-target="input_sertifikat_format" data-token="{TAHUN}">+{TAHUN}</button>
                  <button type="button" class="btn btn-xs btn-outline-secondary py-0 px-2 token-btn" data-target="input_sertifikat_format" data-token="{TA}">+{TA}</button>
                </div>
              </div>

              <div class="row">
                <!-- Digit Padding -->
                <div class="col-sm-6 form-group mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Digit Nomor Urut {NO}</label>
                  <select name="sertifikat_padding" id="input_sertifikat_padding" class="form-control">
                    <option value="3" {{ old('sertifikat_padding', $nomorSettings['sertifikat']['padding']) == 3 ? 'selected' : '' }}>3 Digit (Contoh: 001)</option>
                    <option value="4" {{ old('sertifikat_padding', $nomorSettings['sertifikat']['padding']) == 4 ? 'selected' : '' }}>4 Digit (Contoh: 0001)</option>
                    <option value="5" {{ old('sertifikat_padding', $nomorSettings['sertifikat']['padding']) == 5 ? 'selected' : '' }}>5 Digit (Contoh: 00001)</option>
                  </select>
                </div>

                <!-- Periode Reset -->
                <div class="col-sm-6 form-group mb-3">
                  <label class="font-weight-bold text-dark small mb-1">Siklus Reset Nomor Urut</label>
                  <select name="sertifikat_reset_periode" class="form-control">
                    <option value="tahun" {{ old('sertifikat_reset_periode', $nomorSettings['sertifikat']['reset_periode']) === 'tahun' ? 'selected' : '' }}>Setiap Ganti Tahun (1 Jan)</option>
                    <option value="tahun_ajaran" {{ old('sertifikat_reset_periode', $nomorSettings['sertifikat']['reset_periode']) === 'tahun_ajaran' ? 'selected' : '' }}>Setiap Tahun Ajaran Baru</option>
                    <option value="tidak_pernah" {{ old('sertifikat_reset_periode', $nomorSettings['sertifikat']['reset_periode']) === 'tidak_pernah' ? 'selected' : '' }}>Terus Menerus (Tidak Reset)</option>
                  </select>
                </div>
              </div>

              <!-- Counter Saat Ini -->
              <div class="form-group mb-0 pt-2 border-top">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                  <div>
                    <label class="font-weight-bold text-dark small mb-0">Nomor Urut Terakhir Saat Ini</label>
                    <small class="text-muted d-block">Nomor berikutnya akan bernilai: [Nomor Terakhir + 1]</small>
                  </div>
                  <div class="d-flex align-items-center mt-2 mt-sm-0" style="gap: 8px;">
                    <input type="number" name="sertifikat_counter" id="input_sertifikat_counter" class="form-control text-center font-weight-bold" style="width: 90px;" min="0" value="{{ old('sertifikat_counter', $nomorSettings['sertifikat']['counter']) }}" required>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="openResetModal('sertifikat')" title="Reset Counter Sertifikat">
                      <i class="fas fa-undo"></i>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        @endif
      </div>

      <!-- Action Buttons -->
      <div class="d-flex align-items-center justify-content-between mb-5">
        @if($canAccessTkk)
          <a href="/admin/kejuruan/tkk" class="btn btn-outline-secondary px-4 font-weight-bold" style="border-radius: 8px;">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Master TKK
          </a>
        @else
          <a href="/admin/kejuruan/portofolio" class="btn btn-outline-secondary px-4 font-weight-bold" style="border-radius: 8px;">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Portofolio & Sertifikat
          </a>
        @endif
        <button type="submit" class="btn btn-primary px-4 shadow-sm font-weight-bold" style="border-radius: 8px;">
          <i class="fas fa-save mr-1"></i> Simpan Format Nomor Kejuruan
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Reset Counter -->
<div class="modal fade" id="modalResetCounter" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
      <form action="{{ url('/admin/kejuruan/pengaturan-nomor/reset') }}" method="POST">
        @csrf
        <input type="hidden" name="jenis" id="reset_jenis_input" value="{{ $canAccessTkk ? 'tkk' : 'sertifikat' }}">
        <div class="modal-header bg-danger text-white py-3">
          <h5 class="modal-title font-weight-bold">
            <i class="fas fa-exclamation-triangle mr-2"></i> Konfirmasi Reset Counter Nomor
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">
          <p class="mb-3 text-dark">
            Anda akan mereset nomor urut terakhir untuk <strong id="reset_target_title">{{ $canAccessTkk ? 'Piagam TKK' : 'Sertifikat UKK' }}</strong>.
            Siswa berikutnya yang disahkan akan mendapatkan nomor urut mulai dari angka yang Anda tentukan di bawah.
          </p>
          <div class="form-group mb-0">
            <label class="font-weight-bold text-dark small mb-1">Setel Nilai Counter Terakhir Ke:</label>
            <input type="number" name="target_counter" class="form-control font-weight-bold" value="0" min="0" required>
            <small class="text-muted d-block mt-1">Masukkan 0 jika ingin nomor berikutnya dimulai kembali dari 1.</small>
          </div>
        </div>
        <div class="modal-footer bg-light py-2 px-3">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger btn-sm px-3 font-weight-bold">
            <i class="fas fa-redo mr-1"></i> Ya, Reset Counter Sekarang
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  const sampleJurusan = "{{ $sampleJurusan }}";
  const sampleKlaster = "{{ $sampleKlaster }}";
  const sampleKodeTkk = "{{ $sampleKodeTkk }}";
  const sampleBulan = "{{ date('m') }}";
  const sampleRomawi = "{{ \App\Models\Setting::getRomanMonth() }}";
  const sampleTahun = "{{ date('Y') }}";
  const sampleTa = "{{ str_replace('/', '-', $setting->tahun_ajaran ?? date('Y')) }}";

  function padZero(num, size) {
    let s = num + "";
    while (s.length < size) s = "0" + s;
    return s;
  }

  function renderTkkPreview() {
    const inputFormat = document.getElementById('input_tkk_format');
    const previewEl = document.getElementById('preview_tkk');
    if (!inputFormat || !previewEl) return;

    const format = inputFormat.value || '';
    const padding = parseInt(document.getElementById('input_tkk_padding').value) || 4;
    const currentCounter = parseInt(document.getElementById('input_tkk_counter').value) || 0;
    const nextNo = padZero(currentCounter + 1, padding);

    let res = format
      .replace(/{NO}/g, nextNo)
      .replace(/{JURUSAN}/g, sampleJurusan)
      .replace(/{KLASTER}/g, sampleKlaster)
      .replace(/{KODE_TKK}/g, sampleKodeTkk)
      .replace(/{ROMAWI}/g, sampleRomawi)
      .replace(/{BULAN}/g, sampleBulan)
      .replace(/{TAHUN}/g, sampleTahun)
      .replace(/{TA}/g, sampleTa);

    previewEl.textContent = res || '-';
  }

  function renderSertifikatPreview() {
    const inputFormat = document.getElementById('input_sertifikat_format');
    const previewEl = document.getElementById('preview_sertifikat');
    if (!inputFormat || !previewEl) return;

    const format = inputFormat.value || '';
    const padding = parseInt(document.getElementById('input_sertifikat_padding').value) || 4;
    const currentCounter = parseInt(document.getElementById('input_sertifikat_counter').value) || 0;
    const nextNo = padZero(currentCounter + 1, padding);

    let res = format
      .replace(/{NO}/g, nextNo)
      .replace(/{JURUSAN}/g, sampleJurusan)
      .replace(/{ROMAWI}/g, sampleRomawi)
      .replace(/{BULAN}/g, sampleBulan)
      .replace(/{TAHUN}/g, sampleTahun)
      .replace(/{TA}/g, sampleTa);

    previewEl.textContent = res || '-';
  }

  function openResetModal(jenis) {
    const modalInput = document.getElementById('reset_jenis_input');
    const titleEl = document.getElementById('reset_target_title');
    if (modalInput) modalInput.value = jenis;
    if (titleEl) titleEl.textContent = jenis === 'tkk' ? 'Piagam TKK' : 'Sertifikat UKK';
    $('#modalResetCounter').modal('show');
  }

  document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('input_tkk_format')) {
      ['input_tkk_format', 'input_tkk_padding', 'input_tkk_counter'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
          el.addEventListener('input', renderTkkPreview);
          el.addEventListener('change', renderTkkPreview);
        }
      });
      renderTkkPreview();
    }

    if (document.getElementById('input_sertifikat_format')) {
      ['input_sertifikat_format', 'input_sertifikat_padding', 'input_sertifikat_counter'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
          el.addEventListener('input', renderSertifikatPreview);
          el.addEventListener('change', renderSertifikatPreview);
        }
      });
      renderSertifikatPreview();
    }

    // Token insertion buttons
    document.querySelectorAll('.token-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const targetId = this.getAttribute('data-target');
        const token = this.getAttribute('data-token');
        const input = document.getElementById(targetId);
        if (input) {
          const start = input.selectionStart || input.value.length;
          const end = input.selectionEnd || input.value.length;
          const text = input.value;
          input.value = text.substring(0, start) + token + text.substring(end);
          input.focus();
          input.setSelectionRange(start + token.length, start + token.length);
          if (targetId === 'input_tkk_format') renderTkkPreview();
          if (targetId === 'input_sertifikat_format') renderSertifikatPreview();
        }
      });
    });
  });
</script>
@endsection
