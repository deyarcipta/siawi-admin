@extends($layout)

@section('content')
<style>
  /* === OPSI PENILAIAN PRAKTIK (YANG DISUKAI USER) === */
  .penilaian-mode-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 14px;
  }
  @media (max-width: 768px) {
    .penilaian-mode-grid {
      grid-template-columns: 1fr;
    }
  }
  .penilaian-mode-card {
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 18px;
    background: #ffffff;
    cursor: pointer;
    transition: all 0.2s ease-in-out;
    display: flex;
    align-items: center;
    position: relative;
    user-select: none;
    text-align: left;
  }
  .penilaian-mode-card:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
  }
  .penilaian-mode-card.active {
    border-color: #2563eb !important;
    background: #f0f7ff !important;
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
  }
  .penilaian-mode-card .mode-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    margin-right: 14px;
    flex-shrink: 0;
    transition: all 0.2s ease;
  }
  .penilaian-mode-card:not(.active) .mode-icon {
    background: #f1f5f9;
    color: #64748b;
  }
  .penilaian-mode-card.active .mode-icon {
    background: #2563eb;
    color: #ffffff;
  }
  .penilaian-mode-card .mode-title {
    font-size: 0.94rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 2px;
    line-height: 1.2;
  }
  .penilaian-mode-card.active .mode-title {
    color: #1d4ed8;
  }
  .penilaian-mode-card .mode-desc {
    font-size: 0.77rem;
    color: #64748b;
    margin-bottom: 0;
    line-height: 1.35;
  }
  .penilaian-mode-card .mode-check {
    position: absolute;
    top: 12px;
    right: 12px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    border: 2px solid #cbd5e1;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    color: transparent;
    transition: all 0.2s ease;
  }
  .penilaian-mode-card.active .mode-check {
    background: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
  }

  /* Select2 Multi-Select Styling untuk ATP */
  .select2-container--bootstrap4 .select2-selection--multiple {
    border: 1px solid #ced4da !important;
    border-radius: 8px !important;
    min-height: 42px !important;
    padding: 3px 6px !important;
  }
  .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice {
    background-color: #eff6ff !important;
    border: 1px solid #bfdbfe !important;
    color: #1d4ed8 !important;
    font-size: 0.82rem !important;
    font-weight: 600 !important;
    border-radius: 6px !important;
    padding: 3px 8px !important;
    margin: 3px !important;
  }
  .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove {
    color: #2563eb !important;
    margin-right: 5px !important;
  }
  .select2-container--bootstrap4 .select2-selection--multiple .select2-selection__choice__remove:hover {
    color: #dc2626 !important;
  }
</style>

<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-plus"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Mulai Sesi Penilaian Praktik</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Pilih kelas dan modul praktik atau langsung tentukan Tujuan Pembelajaran (ATP)</p>
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
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="border-radius: 10px;">
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
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1rem;">
              <i class="fas fa-clipboard-list text-primary mr-2"></i> Konfigurasi Sesi Penilaian Kelas
            </h5>
            <span class="badge badge-light border text-primary px-2 py-1 small font-weight-bold">
              Kurikulum Merdeka
            </span>
          </div>

          <form action="/admin/kejuruan/penilaian-praktik" method="POST" id="formPenilaianPraktik">
            @csrf
            @php
              $isAtpMode = old('metode_penilaian') === 'atp_langsung' || old('tanpa_modul') == '1';
            @endphp
            <input type="hidden" name="metode_penilaian" id="metode_penilaian" value="{{ $isAtpMode ? 'atp_langsung' : 'modul' }}">
            <input type="hidden" name="tanpa_modul" id="tanpa_modul" value="{{ $isAtpMode ? '1' : '0' }}">

            <div class="card-body p-4">

              <!-- PILIH KELAS SISWA -->
              <div class="form-group mb-3">
                <label for="id_kelas" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Pilih Kelas Siswa <span class="text-danger">*</span>
                </label>
                <select name="id_kelas" id="id_kelas" class="form-control @error('id_kelas') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                  <option value="">-- Pilih Kelas --</option>
                  @foreach($kelas as $kls)
                    <option value="{{ $kls->id_kelas }}" {{ (old('id_kelas', $selectedJadwal->id_kelas ?? null) == $kls->id_kelas) ? 'selected' : '' }}>
                      {{ $kls->nama_kelas }}
                    </option>
                  @endforeach
                </select>
                <small class="text-muted d-block mt-1">Seluruh siswa aktif di kelas yang dipilih akan otomatis dimasukkan ke tabel penilaian.</small>
                @error('id_kelas')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <!-- METODE PENILAIAN CARD TOGGLE -->
              <div class="form-group mb-4">
                <label class="font-weight-bold text-dark d-block mb-2" style="font-size: 0.85rem;">
                  Opsi Penilaian Praktik <span class="text-danger">*</span>
                </label>

                <div class="penilaian-mode-grid">
                  <!-- CARD 1: JALUR MODUL -->
                  <div class="penilaian-mode-card {{ !$isAtpMode ? 'active' : '' }}" id="card-mode-modul" onclick="switchMetode('modul')">
                    <div class="mode-icon">
                      <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                      <div class="mode-title">Jalur Modul Praktik</div>
                      <div class="mode-desc">Formal & terstruktur sesuai jobsheet dan rubrik modul terbitan.</div>
                    </div>
                    <div class="mode-check">
                      <i class="fas fa-check"></i>
                    </div>
                  </div>

                  <!-- CARD 2: JALUR CEPAT / TANPA MODUL -->
                  <div class="penilaian-mode-card {{ $isAtpMode ? 'active' : '' }}" id="card-mode-atp" onclick="switchMetode('atp_langsung')">
                    <div class="mode-icon">
                      <i class="fas fa-bolt"></i>
                    </div>
                    <div>
                      <div class="mode-title">Jalur Cepat (Tanpa Modul)</div>
                      <div class="mode-desc">Langsung pilih 1 atau beberapa ATP untuk asesmen dadakan/proyek.</div>
                    </div>
                    <div class="mode-check">
                      <i class="fas fa-check"></i>
                    </div>
                  </div>
                </div>
              </div>

              <!-- ======================================================= -->
              <!-- JALUR 1: DENGAN MODUL PRAKTIK                          -->
              <!-- ======================================================= -->
              <div id="section-modul" style="{{ $isAtpMode ? 'display: none;' : '' }}">
                <div class="form-group mb-3">
                  <label for="id_modul_praktik" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Pilih Modul Praktik (Bank Modul) <span class="text-danger">*</span>
                  </label>
                  <select name="id_modul_praktik" id="id_modul_praktik" class="form-control @error('id_modul_praktik') is-invalid @enderror" style="border-radius: 8px; height: 42px;">
                    <option value="">-- Pilih Modul Praktik Terbit --</option>
                    @foreach($moduls as $m)
                      @php
                        $rubrikList = $m->rubriks->map(fn($r) => $r->nama_indikator . ' (' . $r->bobot . '%)')->implode(', ');
                      @endphp
                      <option value="{{ $m->id_modul_praktik }}"
                              data-judul="{{ $m->judul }}"
                              data-kode-modul="{{ $m->kode_modul }}"
                              data-kompetensi-kode="{{ $m->kompetensi->kode_kompetensi ?? '-' }}"
                              data-kompetensi-nama="{{ $m->kompetensi->nama_kompetensi ?? '-' }}"
                              data-cp-kode="{{ $m->kompetensi->capaianPembelajaran->kode_elemen ?? '-' }}"
                              data-cp-nama="{{ $m->kompetensi->capaianPembelajaran->nama_elemen ?? 'Elemen Mandiri' }}"
                              data-cp-fase="{{ $m->kompetensi->capaianPembelajaran->fase_label ?? 'Fase ' . ($m->kompetensi->fase ?? 'F') }}"
                              data-kkm="{{ floatval($m->kompetensi->kkm ?? 75) }}"
                              data-rubriks="{{ $rubrikList ?: 'Standar Keterampilan (100%)' }}"
                              {{ old('id_modul_praktik') == $m->id_modul_praktik ? 'selected' : '' }}>
                        [{{ $m->kode_modul }}] {{ $m->judul }} (v{{ $m->versi }}) - {{ $m->kompetensi->nama_kompetensi ?? '' }} (KKM: {{ floatval($m->kompetensi->kkm ?? 75) }})
                      </option>
                    @endforeach
                  </select>
                  @error('id_modul_praktik')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <!-- Live Preview Card Modul -->
                <div id="preview-modul-card" class="card border-0 mb-3" style="display: none; background: #f8fafc; border-left: 4px solid #0284c7 !important; border-radius: 10px;">
                  <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <span class="badge badge-primary px-2 py-1 font-weight-bold" id="prev-modul-fase">-</span>
                      <span class="badge badge-light border text-primary font-weight-bold px-2 py-1" id="prev-modul-kkm">KKTP: 75</span>
                    </div>
                    <div class="mb-1">
                      <strong class="text-dark d-block" style="font-size: 0.85rem;"><i class="fas fa-layer-group text-primary mr-1"></i> Elemen CP:</strong>
                      <span class="text-secondary small" id="prev-modul-cp">-</span>
                    </div>
                    <div class="mb-2">
                      <strong class="text-dark d-block" style="font-size: 0.85rem;"><i class="fas fa-tasks text-success mr-1"></i> Alur TP (Kompetensi):</strong>
                      <span class="text-secondary small" id="prev-modul-tp">-</span>
                    </div>
                    <div class="pt-2 border-top">
                      <strong class="text-dark d-block" style="font-size: 0.82rem;"><i class="fas fa-list-check text-info mr-1"></i> Rubrik Asesmen Modul:</strong>
                      <span class="text-muted small" id="prev-modul-rubrik">-</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- ======================================================= -->
              <!-- JALUR 2: TANPA MODUL (BISA PILIH LEBIH DARI 1 ATP)     -->
              <!-- ======================================================= -->
              <div id="section-atp" style="{{ !$isAtpMode ? 'display: none;' : '' }}">
                <div class="form-group mb-3">
                  <div class="d-flex align-items-center justify-content-between mb-1">
                    <label for="id_kompetensi" class="font-weight-bold text-dark mb-0" style="font-size: 0.85rem;">
                      Pilih Tujuan Pembelajaran (ATP / TP) <span class="text-danger">*</span>
                    </label>
                    <span class="badge badge-info px-2 py-1 font-weight-normal small">
                      <i class="fas fa-layer-group mr-1"></i> Multi-pilih (Bisa pilih lebih dari 1)
                    </span>
                  </div>

                  @php
                    $selectedOldKompetensi = (array) old('id_kompetensi', []);
                  @endphp

                  <select name="id_kompetensi[]" id="id_kompetensi" class="form-control select2-atp @error('id_kompetensi') is-invalid @enderror" multiple="multiple" data-placeholder="-- Klik untuk memilih satu atau beberapa Tujuan Pembelajaran (ATP) --" style="width: 100%;">
                    @foreach($kompetensis->groupBy(function($item) {
                        if ($item->capaianPembelajaran) {
                            return '[' . $item->capaianPembelajaran->fase_label . '] ' . $item->capaianPembelajaran->kode_elemen . ' - ' . $item->capaianPembelajaran->nama_elemen . ' (' . ($item->jurusan->nama_singkat ?? '') . ')';
                        }
                        return 'Kompetensi Mandiri (Tanpa Elemen CP)';
                    }) as $groupElemen => $items)
                      <optgroup label="{{ $groupElemen }}">
                        @foreach($items as $k)
                          @php
                            $isSelected = in_array((string)$k->id_kompetensi, array_map('strval', $selectedOldKompetensi));
                            $faseLabel = $k->capaianPembelajaran ? $k->capaianPembelajaran->fase_label : 'Fase ' . $k->fase;
                          @endphp
                          <option value="{{ $k->id_kompetensi }}"
                                  data-kode="{{ $k->kode_kompetensi }}"
                                  data-nama="{{ $k->nama_kompetensi }}"
                                  data-cp-kode="{{ $k->capaianPembelajaran->kode_elemen ?? '-' }}"
                                  data-cp-nama="{{ $k->capaianPembelajaran->nama_elemen ?? 'Mandiri' }}"
                                  data-cp-fase="{{ $faseLabel }}"
                                  data-kkm="{{ floatval($k->kkm ?? 75) }}"
                                  data-materi="{{ $k->materi_pokok ?? '-' }}"
                                  {{ $isSelected ? 'selected' : '' }}>
                            [{{ $k->kode_kompetensi }}] {{ $k->nama_kompetensi }} (KKTP: {{ floatval($k->kkm ?? 75) }})
                          </option>
                        @endforeach
                      </optgroup>
                    @endforeach
                  </select>
                  <small class="text-muted d-block mt-1">
                    <i class="fas fa-info-circle text-info mr-1"></i> Anda dapat memilih beberapa butir ATP sekaligus jika proyek/tugas praktik mencakup lebih dari satu capaian.
                  </small>
                  @error('id_kompetensi')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <!-- Live Preview Card ATP (Mendukung Multi-ATP) -->
                <div id="preview-atp-card" class="card border-0 mb-3" style="display: none; background: #fdfaf4; border-left: 4px solid #f59e0b !important; border-radius: 10px;">
                  <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                      <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" id="prev-atp-count">
                        0 Butir ATP Terpilih
                      </span>
                      <span class="badge badge-light border text-primary font-weight-bold px-2 py-1" id="prev-atp-kkm">
                        Rata-Rata KKTP: 75
                      </span>
                    </div>

                    <div class="mb-2">
                      <strong class="text-dark d-block mb-1" style="font-size: 0.85rem;">
                        <i class="fas fa-tasks text-success mr-1"></i> Rincian ATP yang Dinilai:
                      </strong>
                      <div id="prev-atp-list" class="d-flex flex-column" style="gap: 4px;">
                        <!-- Dynamic items -->
                      </div>
                    </div>

                    <div class="pt-2 border-top">
                      <strong class="text-dark d-block" style="font-size: 0.82rem;">
                        <i class="fas fa-check-circle text-success mr-1"></i> Rubrik Asesmen Otomatis:
                      </strong>
                      <span class="text-muted small">Keterampilan & Hasil Praktik (Bobot 100%) berlaku simultan untuk seluruh butir ATP terpilih.</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- TANGGAL & JADWAL PRAKTIK -->
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
                    Tautkan ke Jadwal Praktik <span class="badge badge-light border text-secondary ml-1 font-weight-normal">Opsional</span>
                  </label>
                  <select name="id_jadwal_praktik" id="id_jadwal_praktik" class="form-control @error('id_jadwal_praktik') is-invalid @enderror" style="border-radius: 8px; height: 42px;">
                    <option value="">-- Tanpa Jadwal Spesifik (Opsional) --</option>
                    @foreach($jadwals as $j)
                      @php
                        $isSelected = (string)old('id_jadwal_praktik', $selectedJadwal->id_jadwal_praktik ?? '') === (string)$j->id_jadwal_praktik;
                        $jamMulai = $j->jam_mulai ? substr($j->jam_mulai, 0, 5) : '-';
                        $jamSelesai = $j->jam_selesai ? substr($j->jam_selesai, 0, 5) : '-';
                        $namaKelas = $j->kelas->nama_kelas ?? 'Semua Kelas';
                        $namaLab = $j->lab->nama_lab ?? 'Lab';
                        $namaMapel = $j->mapel->nama_mapel ?? 'Praktik';
                        $label = "{$j->hari} ({$jamMulai}-{$jamSelesai}) - {$namaKelas} - {$namaMapel} [{$namaLab}]";
                      @endphp
                      <option value="{{ $j->id_jadwal_praktik }}" data-kelas="{{ $j->id_kelas }}" {{ $isSelected ? 'selected' : '' }}>
                        {{ $label }}
                      </option>
                    @endforeach
                  </select>
                  <small class="text-muted d-block mt-1">Opsional: tautkan sesi penilaian ini dengan jadwal praktik lab.</small>
                  @error('id_jadwal_praktik')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <!-- CATATAN -->
              <div class="form-group mb-3">
                <label for="catatan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Catatan Sesi Penilaian <span class="badge badge-light border text-secondary ml-1 font-weight-normal">Opsional</span>
                </label>
                <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="3" placeholder="Contoh: Penilaian praktikum tugas mandiri pertemuan ke-4" style="border-radius: 8px;">{{ old('catatan') }}</textarea>
                <small class="text-muted d-block mt-1">Catatan tambahan mengenai kondisi asesmen atau petunjuk khusus guru.</small>
              </div>

            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center justify-content-between">
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

      <!-- SIDEBAR PANDUAN & KEUNTUNGAN -->
      <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; background-color: #f8fafc;">
          <div class="card-body p-4">
            <h6 class="font-weight-bold text-dark mb-3">
              <i class="fas fa-lightbulb text-warning mr-1"></i> Panduan Opsi Penilaian
            </h6>
            
            <div class="mb-3">
              <strong class="text-primary d-block font-weight-bold" style="font-size: 0.86rem;">
                <i class="fas fa-book-open mr-1"></i> 1. Jalur Modul Praktik
              </strong>
              <p class="text-muted small mb-0 mt-1" style="line-height: 1.5;">
                Cocok untuk praktikum terstruktur. Sistem otomatis mengaitkan Elemen CP, ATP, dan rubrik indikator modul secara lengkap.
              </p>
            </div>

            <div class="mb-3">
              <strong class="text-warning d-block font-weight-bold" style="font-size: 0.86rem;">
                <i class="fas fa-bolt mr-1"></i> 2. Jalur Cepat (Tanpa Modul)
              </strong>
              <p class="text-muted small mb-0 mt-1" style="line-height: 1.5;">
                Cocok untuk asesmen dadakan, kuis praktik, atau tugas terpadu. Anda dapat memilih <strong>satu atau lebih butir ATP</strong> sekaligus.
              </p>
            </div>

            <div class="border-top pt-3">
              <strong class="text-success d-block font-weight-bold" style="font-size: 0.86rem;">
                <i class="fas fa-check-double mr-1"></i> Integrasi Portofolio Otomatis
              </strong>
              <p class="text-muted small mb-0 mt-1" style="line-height: 1.5;">
                Saat sesi penilaian difinalkan, nilai siswa otomatis tercatat ke <strong>Buku Induk Capaian Siswa</strong> untuk setiap butir ATP yang dipilih.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
  // Fungsi Global untuk Klik Kartu Mode
  function switchMetode(mode) {
    if (mode === 'atp_langsung') {
      $('#metode_penilaian').val('atp_langsung');
      $('#tanpa_modul').val('1');
      $('#card-mode-modul').removeClass('active');
      $('#card-mode-atp').addClass('active');

      $('#section-modul').slideUp(200);
      $('#section-atp').slideDown(200);
      $('#id_modul_praktik').prop('required', false);
      $('#id_kompetensi').prop('required', true);
      triggerAtpPreview();
    } else {
      $('#metode_penilaian').val('modul');
      $('#tanpa_modul').val('0');
      $('#card-mode-atp').removeClass('active');
      $('#card-mode-modul').addClass('active');

      $('#section-atp').slideUp(200);
      $('#section-modul').slideDown(200);
      $('#id_kompetensi').prop('required', false);
      $('#id_modul_praktik').prop('required', true);
      triggerModulPreview();
    }
  }

  function triggerModulPreview() {
    var $selected = $('#id_modul_praktik').find(':selected');
    if ($selected.val()) {
      $('#prev-modul-fase').text($selected.data('cp-fase') || 'Fase F');
      $('#prev-modul-kkm').text('KKTP: ' + ($selected.data('kkm') || 75));
      $('#prev-modul-cp').text(($selected.data('cp-kode') ? '[' + $selected.data('cp-kode') + '] ' : '') + ($selected.data('cp-nama') || '-'));
      $('#prev-modul-tp').text(($selected.data('kompetensi-kode') ? '[' + $selected.data('kompetensi-kode') + '] ' : '') + ($selected.data('kompetensi-nama') || '-'));
      $('#prev-modul-rubrik').text($selected.data('rubriks') || 'Keterampilan & Hasil Praktik (100%)');
      $('#preview-modul-card').slideDown(150);
    } else {
      $('#preview-modul-card').slideUp(150);
    }
  }

  function triggerAtpPreview() {
    var $selectedOptions = $('#id_kompetensi').find(':selected');
    var count = $selectedOptions.length;

    if (count > 0) {
      $('#prev-atp-count').text(count + ' Butir ATP Terpilih');

      var totalKkm = 0;
      var listHtml = '';

      $selectedOptions.each(function() {
        var kode = $(this).data('kode') || '';
        var nama = $(this).data('nama') || '';
        var kkm = parseFloat($(this).data('kkm')) || 75;
        var cpKode = $(this).data('cp-kode') || '';
        var fase = $(this).data('cp-fase') || 'Fase F';
        totalKkm += kkm;

        listHtml += '<div class="p-2 rounded bg-white border small d-flex align-items-center justify-content-between">';
        listHtml += '  <div>';
        listHtml += '    <span class="badge badge-light border text-primary font-weight-bold mr-1">' + kode + '</span>';
        listHtml += '    <span class="font-weight-600 text-dark">' + nama + '</span>';
        if (cpKode && cpKode !== '-') {
          listHtml += '  <small class="text-muted d-block mt-1"><i class="fas fa-layer-group mr-1 text-secondary"></i>' + cpKode + ' (' + fase + ')</small>';
        }
        listHtml += '  </div>';
        listHtml += '  <span class="badge badge-light border font-weight-bold text-success ml-2">KKTP: ' + kkm + '</span>';
        listHtml += '</div>';
      });

      var avgKkm = Math.round((totalKkm / count) * 10) / 10;
      $('#prev-atp-kkm').text('Rata-Rata KKTP: ' + avgKkm);
      $('#prev-atp-list').html(listHtml);
      $('#preview-atp-card').slideDown(150);
    } else {
      $('#preview-atp-card').slideUp(150);
    }
  }

  $(document).ready(function() {
    var $kelasSelect = $('#id_kelas');
    var $jadwalSelect = $('#id_jadwal_praktik');
    var allOptions = [];

    // Inisialisasi Select2 KHUSUS untuk Multiple ATP saja (seperti di modul_praktik/create.blade.php)
    if ($.fn.select2) {
      $('#id_kompetensi').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: '-- Klik untuk memilih satu atau beberapa Tujuan Pembelajaran (ATP) --',
        closeOnSelect: false
      });
    }

    // Ambil daftar jadwal
    $jadwalSelect.find('option').each(function() {
      allOptions.push({
        value: $(this).val(),
        text: $(this).text(),
        kelas: $(this).data('kelas'),
        selected: $(this).is(':selected')
      });
    });

    function filterJadwal(selectedKelasId, preserveSelected) {
      var currentVal = preserveSelected ? $jadwalSelect.val() : '';
      $jadwalSelect.empty();

      $jadwalSelect.append($('<option>', {
        value: '',
        text: '-- Tanpa Jadwal Spesifik (Opsional) --'
      }));

      var matchedVal = '';
      allOptions.forEach(function(opt) {
        if (!opt.value) return;
        if (!selectedKelasId || !opt.kelas || opt.kelas == selectedKelasId) {
          var isSelected = (currentVal && currentVal == opt.value) || (!currentVal && opt.selected && (!selectedKelasId || opt.kelas == selectedKelasId));
          if (isSelected) matchedVal = opt.value;
          $jadwalSelect.append($('<option>', {
            value: opt.value,
            text: opt.text,
            'data-kelas': opt.kelas,
            selected: isSelected
          }));
        }
      });

      if (matchedVal) {
        $jadwalSelect.val(matchedVal);
      } else if (!preserveSelected) {
        $jadwalSelect.val('');
      }
    }

    $kelasSelect.on('change', function() {
      filterJadwal($(this).val(), false);
    });

    $jadwalSelect.on('change', function() {
      var selectedKelas = $(this).find(':selected').data('kelas');
      if (selectedKelas && !$kelasSelect.val()) {
        $kelasSelect.val(selectedKelas);
      }
    });

    if ($kelasSelect.val()) {
      filterJadwal($kelasSelect.val(), true);
    }

    // Event listeners dropdown
    $('#id_modul_praktik').on('change', function() {
      triggerModulPreview();
    });

    $('#id_kompetensi').on('change', function() {
      triggerAtpPreview();
    });

    // Initial triggers
    var initialMode = $('#tanpa_modul').val() === '1' ? 'atp_langsung' : 'modul';
    switchMetode(initialMode);
    triggerModulPreview();
    triggerAtpPreview();
  });
</script>
@endpush
