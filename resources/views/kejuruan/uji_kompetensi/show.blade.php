@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-clipboard-check text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">{{ $kegiatan->nama_kegiatan }}</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">
            {{ $kegiatan->jurusan->nama_jurusan ?? '-' }} | Lab: {{ $kegiatan->lab->nama_lab ?? 'Lab Praktik' }} | Periode: {{ $kegiatan->tanggal_mulai->format('d/m/Y') }} - {{ $kegiatan->tanggal_selesai->format('d/m/Y') }}
          </p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/uji-kompetensi" class="text-primary font-weight-500">Uji Kompetensi</a></li>
          <li class="breadcrumb-item active">Detail UKK</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
        <i class="fas fa-check-circle mr-2"></i> {!! session('success') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @php
      $persenKompeten = $totalPeserta > 0 ? round(($totalKompeten / $totalPeserta) * 100) : 0;
    @endphp

    <!-- Summary Stat Cards -->
    <div class="row mb-3">
      <!-- Total Peserta -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Peserta</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalPeserta }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-user-graduate text-primary mr-1"></i> Terdaftar</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-users"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Sudah Dinilai -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Progress Penilaian</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $sudahDinilai }} / {{ $totalPeserta }}</div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-clock text-warning mr-1"></i> Telah Dinilai Penguji</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fffbeb; color: #f59e0b; flex-shrink: 0;">
              <i class="fas fa-clipboard-list"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Kompeten (Lulus) -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Kompeten (Lulus)</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalKompeten }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-check-circle text-success mr-1"></i> {{ $persenKompeten }}% Kelulusan</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-check-double"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Belum Kompeten / Remedial -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #dc2626 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Belum Kompeten</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalBelum }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-exclamation-circle text-danger mr-1"></i> Perlu Ujian Remedial</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fee2e2; color: #dc2626; flex-shrink: 0;">
              <i class="fas fa-user-times"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Toolbar Card -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap: 8px;">
          <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
            <a href="/admin/kejuruan/uji-kompetensi/{{ $kegiatan->id_ukk_kegiatan }}/berita-acara" target="_blank" class="btn btn-danger btn-sm shadow-sm font-weight-bold">
              <i class="fas fa-file-pdf mr-1"></i> Cetak Berita Acara (PDF)
            </a>
            <a href="/admin/kejuruan/uji-kompetensi/{{ $kegiatan->id_ukk_kegiatan }}/rekap-pdf" target="_blank" class="btn btn-success btn-sm shadow-sm font-weight-bold">
              <i class="fas fa-file-excel mr-1"></i> Cetak Rekap Nilai (PDF)
            </a>
            <a href="/admin/kejuruan/capaian-siswa" class="btn btn-outline-secondary btn-sm shadow-sm">
              <i class="fas fa-medal mr-1"></i> Buku Induk Capaian
            </a>
          </div>

          <div>
            <a href="/admin/kejuruan/uji-kompetensi" class="btn btn-link text-muted btn-sm p-0">
              <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Kegiatan
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Nav Tabs & Content -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
      <div class="card-header bg-white py-2 px-3 border-bottom">
        <ul class="nav nav-pills" id="ukk-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link py-1 px-3 active font-weight-500" id="peserta-tab" data-toggle="pill" href="#tab-peserta" role="tab" style="border-radius: 6px;">
              <i class="fas fa-user-graduate mr-1"></i> Peserta & Penilaian ({{ $totalPeserta }})
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link py-1 px-3 font-weight-500" id="penguji-tab" data-toggle="pill" href="#tab-penguji" role="tab" style="border-radius: 6px;">
              <i class="fas fa-user-check mr-1"></i> Tim Penguji ({{ $kegiatan->pengujis->count() }})
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link py-1 px-3 font-weight-500" id="unit-tab" data-toggle="pill" href="#tab-unit" role="tab" style="border-radius: 6px;">
              <i class="fas fa-tasks mr-1"></i> Unit Kompetensi Uji ({{ $kegiatan->units->count() }})
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link py-1 px-3 font-weight-500 text-danger" id="remedial-tab" data-toggle="pill" href="#tab-remedial" role="tab" style="border-radius: 6px;">
              <i class="fas fa-calendar-times mr-1"></i> Jadwal Remedial ({{ $pesertaRemedial->count() }})
            </a>
          </li>
        </ul>
      </div>

      <div class="card-body p-0">
        <div class="tab-content" id="ukk-tabContent">

          <!-- TAB 1: PESERTA & PENILAIAN -->
          <div class="tab-pane fade show active" id="tab-peserta" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                  <tr>
                    <th style="width: 50px" class="text-center">No</th>
                    <th style="width: 110px">No. Peserta</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th style="width: 110px" class="text-center">Nilai Akhir</th>
                    <th style="width: 140px" class="text-center">Status Kelulusan</th>
                    <th style="width: 140px" class="text-center">Aksi Penilaian</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($kegiatan->pesertas as $p)
                    <tr>
                      <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                      <td class="align-middle">
                        <span class="badge badge-light border text-primary font-weight-bold px-2 py-1">
                          {{ $p->nomor_peserta ?: '-' }}
                        </span>
                      </td>
                      <td class="align-middle font-weight-600 text-dark">
                        {{ $p->siswa->nama_siswa ?? 'Siswa' }}
                        <small class="text-muted d-block font-weight-normal">NIS: {{ $p->siswa->nis ?? '-' }}</small>
                      </td>
                      <td class="align-middle">
                        <span class="badge badge-info px-2 py-1">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</span>
                      </td>
                      <td class="text-center align-middle font-weight-bold" style="font-size: 1rem;">
                        @if($p->status_kelulusan === 'belum_dinilai')
                          <span class="text-muted">-</span>
                        @else
                          <span class="{{ $p->status_kelulusan === 'kompeten' ? 'text-success' : 'text-danger' }}">
                            {{ floatval($p->nilai_akhir) }}
                          </span>
                        @endif
                      </td>
                      <td class="text-center align-middle">
                        @if($p->status_kelulusan === 'kompeten')
                          <span class="badge badge-success px-2 py-1 font-weight-bold">
                            <i class="fas fa-check-circle mr-1"></i> Kompeten
                          </span>
                        @elseif($p->status_kelulusan === 'belum_kompeten')
                          <span class="badge badge-danger px-2 py-1 font-weight-bold">
                            <i class="fas fa-times-circle mr-1"></i> Belum Kompeten
                          </span>
                        @else
                          <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold">
                            <i class="fas fa-clock mr-1"></i> Belum Dinilai
                          </span>
                        @endif
                      </td>
                      <td class="text-center align-middle">
                        <a href="/admin/kejuruan/uji-kompetensi/{{ $kegiatan->id_ukk_kegiatan }}/input/{{ $p->id_ukk_peserta }}" class="btn btn-sm btn-primary px-3 shadow-sm font-weight-bold" style="border-radius: 6px;">
                          <i class="fas fa-edit mr-1"></i> Input Nilai
                        </a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="7" class="text-center py-4 text-muted">Belum ada peserta terdaftar dalam kegiatan UKK ini.</td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

          <!-- TAB 2: TIM PENGUJI -->
          <div class="tab-pane fade p-3" id="tab-penguji" role="tabpanel">
            <div class="row">
              @forelse($kegiatan->pengujis as $png)
                <div class="col-md-6 mb-3">
                  <div class="card border shadow-sm h-100" style="border-radius: 10px;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                      <div>
                        <span class="badge {{ $png->jenis === 'internal' ? 'badge-primary' : 'badge-warning text-dark' }} px-2 py-1 mb-1 font-weight-bold">
                          {{ strtoupper($png->jenis) }}
                        </span>
                        <h6 class="font-weight-bold text-dark mb-0 mt-1">
                          {{ $png->nama_tampil }}
                        </h6>
                        <small class="text-muted d-block mt-1">
                          {{ $png->jabatan_penguji }} | Instansi: {{ $png->instansi ?: 'SMK Internal' }}
                        </small>
                      </div>
                      <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px; background: #f8fafc; color: #0284c7;">
                        <i class="fas fa-user-tie"></i>
                      </div>
                    </div>
                  </div>
                </div>
              @empty
                <div class="col-12 text-muted">Belum ada tim penguji yang ditugaskan.</div>
              @endforelse
            </div>
          </div>

          <!-- TAB 3: UNIT KOMPETENSI UJI -->
          <div class="tab-pane fade" id="tab-unit" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem;">
                  <tr>
                    <th style="width: 50px" class="text-center">No</th>
                    <th style="width: 140px">Kode Unit</th>
                    <th>Judul Unit Kompetensi</th>
                    <th style="width: 100px" class="text-center">KKM</th>
                    <th style="width: 100px" class="text-center">Bobot</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($kegiatan->units as $u)
                    <tr>
                      <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                      <td class="align-middle">
                        <span class="badge badge-light border text-primary font-weight-bold px-2 py-1">
                          {{ $u->kompetensi->kode_kompetensi ?? '-' }}
                        </span>
                      </td>
                      <td class="align-middle font-weight-600 text-dark">
                        {{ $u->kompetensi->nama_kompetensi ?? '-' }}
                      </td>
                      <td class="text-center align-middle font-weight-bold text-dark">
                        {{ floatval($u->kkm) }}
                      </td>
                      <td class="text-center align-middle font-weight-bold text-primary">
                        {{ floatval($u->bobot) }}%
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>

          <!-- TAB 4: JADWAL REMEDIAL -->
          <div class="tab-pane fade" id="tab-remedial" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem;">
                  <tr>
                    <th style="width: 50px" class="text-center">No</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Unit Belum Kompeten</th>
                    <th>Jadwal Ujian Remedial</th>
                    <th style="width: 140px" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($pesertaRemedial as $pr)
                    <tr>
                      <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                      <td class="align-middle font-weight-bold text-dark">
                        {{ $pr->siswa->nama_siswa ?? 'Siswa' }}
                      </td>
                      <td class="align-middle">
                        <span class="badge badge-info px-2 py-1">{{ $pr->siswa->kelas->nama_kelas ?? '-' }}</span>
                      </td>
                      <td class="align-middle">
                        @foreach($pr->nilais->where('status', 'belum_kompeten') as $nl)
                          <div class="mb-1">
                            <span class="badge badge-danger px-2 mr-1">Skor: {{ floatval($nl->nilai) }}</span>
                            <span class="text-dark small font-weight-500">{{ $nl->unit->kompetensi->nama_kompetensi ?? '-' }}</span>
                          </div>
                        @endforeach
                      </td>
                      <td class="align-middle">
                        @php
                          $tglRem = $pr->nilais->where('status', 'belum_kompeten')->pluck('jadwal_remedial')->filter()->first();
                        @endphp
                        @if($tglRem)
                          <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold">
                            <i class="fas fa-calendar-day mr-1"></i> {{ \Carbon\Carbon::parse($tglRem)->format('d/m/Y') }}
                          </span>
                        @else
                          <span class="text-muted font-italic small">Belum dijadwalkan</span>
                        @endif
                      </td>
                      <td class="text-center align-middle">
                        <a href="/admin/kejuruan/uji-kompetensi/{{ $kegiatan->id_ukk_kegiatan }}/input/{{ $pr->id_ukk_peserta }}" class="btn btn-sm btn-outline-danger font-weight-bold px-3">
                          <i class="fas fa-redo mr-1"></i> Nilai Ulang
                        </a>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="6" class="text-center py-4 text-success font-weight-500">
                        <i class="fas fa-check-circle mr-1"></i> Tidak ada peserta yang memerlukan remedial pada kegiatan ini.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
