@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-medal text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Buku Induk Capaian Siswa</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Portofolio ketuntasan kompetensi siswa (Praktik Kejuruan, UKK, TKK, dan Sertifikasi)</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/capaian-siswa" class="text-primary font-weight-500">Kejuruan</a></li>
          <li class="breadcrumb-item active">Capaian Siswa</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">

    @php
      $persenKompeten = $totalCapaian > 0 ? round(($totalKompeten / $totalCapaian) * 100) : 0;
    @endphp

    <!-- Summary Stat Cards -->
    <div class="row mb-3">
      <!-- Total Rekor Capaian -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Rekor Capaian</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalCapaian }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Entri</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-tasks text-primary mr-1"></i> Riwayat Penilaian</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-tasks"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Kompeten -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Kompeten (Lulus)</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalKompeten }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-check-circle text-success mr-1"></i> Di Atas KKM</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-check-circle"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Belum Kompeten -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #dc2626 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Belum Kompeten</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalBelum }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Siswa</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-exclamation-circle text-danger mr-1"></i> Perlu Remedial</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fee2e2; color: #dc2626; flex-shrink: 0;">
              <i class="fas fa-times-circle"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Persentase Kelulusan -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #8b5cf6 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Tingkat Kelulusan</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $persenKompeten }}%</div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-chart-line text-purple mr-1"></i> Ketuntasan Portofolio</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f5f3ff; color: #8b5cf6; flex-shrink: 0;">
              <i class="fas fa-award"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Toolbar Card -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <form method="GET" action="/admin/kejuruan/capaian-siswa" class="row align-items-end">
          <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Cari Siswa</label>
            <input type="text" name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Nama / NIS...">
          </div>

          <div class="col-md-2 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Kelas</label>
            <select name="kelas_id" class="form-control form-control-sm select2bs4">
              <option value="">-- Semua Kelas --</option>
              @foreach($kelas as $kls)
                <option value="{{ $kls->id_kelas }}" {{ request('kelas_id') == $kls->id_kelas ? 'selected' : '' }}>
                  {{ $kls->nama_kelas }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Kompetensi</label>
            <select name="kompetensi_id" class="form-control form-control-sm select2bs4">
              <option value="">-- Semua Kompetensi --</option>
              @foreach($kompetensis as $k)
                <option value="{{ $k->id_kompetensi }}" {{ request('kompetensi_id') == $k->id_kompetensi ? 'selected' : '' }}>
                  [{{ $k->kode_kompetensi }}] {{ $k->nama_kompetensi }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Jenis Capaian</label>
            <div class="d-flex align-items-center" style="gap: 8px;">
              <select name="jenis_capaian" class="form-control form-control-sm" style="flex: 1;">
                <option value="">-- Semua Jenis --</option>
                <option value="praktik" {{ request('jenis_capaian') === 'praktik' ? 'selected' : '' }}>Praktik</option>
                <option value="ukk" {{ request('jenis_capaian') === 'ukk' ? 'selected' : '' }}>UKK</option>
                <option value="tkk" {{ request('jenis_capaian') === 'tkk' ? 'selected' : '' }}>TKK</option>
                <option value="sertifikat" {{ request('jenis_capaian') === 'sertifikat' ? 'selected' : '' }}>Sertifikat</option>
              </select>
              <button type="submit" class="btn btn-primary btn-sm px-3 d-flex align-items-center justify-content-center shadow-sm" style="height: 31px; flex-shrink: 0;" title="Terapkan Filter">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form>

        <!-- Lower Action Toolbar -->
        <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top flex-wrap" style="gap: 8px;">
          <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
            <a href="/admin/kejuruan/penilaian-praktik" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
              <i class="fas fa-clipboard-check mr-1"></i> Penilaian Praktik
            </a>
          </div>

          <div>
            @if(request()->anyFilled(['q', 'kelas_id', 'kompetensi_id', 'jenis_capaian', 'status']))
              <a href="/admin/kejuruan/capaian-siswa" class="btn btn-link text-muted btn-sm p-0">
                <i class="fas fa-undo mr-1"></i> Reset Filter
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel Buku Induk Capaian -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
      <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
        <div>
          <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
            <i class="fas fa-list-ul text-primary mr-2"></i> Rekapitulasi Portofolio Capaian Siswa
          </h3>
          <span class="badge badge-light border text-secondary ml-2 px-2 py-1" style="font-size: 0.75rem;">
            Total: {{ $capaians->count() }} Rekor
          </span>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th style="width: 45px; text-align: center;">No</th>
                <th>Siswa</th>
                <th>Kelas</th>
                <th>Kompetensi Kejuruan</th>
                <th style="width: 110px; text-align: center;">Jenis</th>
                <th style="width: 90px; text-align: center;">Nilai</th>
                <th style="width: 130px; text-align: center;">Status</th>
                <th style="width: 110px;">Tanggal</th>
                <th>Penilai</th>
                <th style="width: 120px; text-align: center;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($capaians as $c)
                @php
                  $isKompeten = ($c->status === 'kompeten');
                @endphp
                <tr>
                  <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                  <td>
                    <span class="font-weight-600 text-dark d-block">
                      {{ $c->siswa->nama_siswa ?? 'Siswa' }}
                    </span>
                    <small class="text-muted">NIS: {{ $c->siswa->nis ?? $c->siswa->nisn ?? '-' }}</small>
                  </td>
                  <td>
                    <span class="badge badge-light border text-primary font-weight-bold px-2 py-1">
                      {{ $c->siswa->kelas->nama_kelas ?? '-' }}
                    </span>
                  </td>
                  <td>
                    <div class="font-weight-bold text-dark d-block">
                      {{ $c->kompetensi->nama_kompetensi ?? '-' }}
                    </div>
                    <small class="text-muted">
                      [{{ $c->kompetensi->kode_kompetensi ?? '-' }}] KKM: {{ floatval($c->kkm) }}
                    </small>
                  </td>
                  <td class="text-center">
                    @if($c->jenis_capaian === 'praktik')
                      <span class="badge badge-info px-2 py-1 font-weight-bold">Praktik</span>
                    @elseif($c->jenis_capaian === 'ukk')
                      <span class="badge badge-primary px-2 py-1 font-weight-bold">UKK</span>
                    @elseif($c->jenis_capaian === 'tkk')
                      <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold">TKK</span>
                    @else
                      <span class="badge badge-success px-2 py-1 font-weight-bold">Sertifikat</span>
                    @endif
                  </td>
                  <td class="text-center font-weight-bold" style="font-size: 0.95rem;">
                    <span class="{{ $isKompeten ? 'text-success' : 'text-danger' }}">
                      {{ floatval($c->nilai) }}
                    </span>
                  </td>
                  <td class="text-center">
                    <span class="badge {{ $isKompeten ? 'badge-success' : 'badge-danger' }} px-2 py-1 font-weight-bold">
                      {{ $isKompeten ? 'Kompeten' : 'Belum Kompeten' }}
                    </span>
                  </td>
                  <td>
                    <span class="small text-dark font-weight-500">
                      {{ \Carbon\Carbon::parse($c->tanggal)->translatedFormat('d M Y') }}
                    </span>
                  </td>
                  <td>
                    <span class="small text-muted font-weight-500">
                      {{ $c->penilai->nama_guru ?? 'Penilai' }}
                    </span>
                  </td>
                  <td class="text-center">
                    @if($c->siswa)
                      <a href="/admin/kejuruan/capaian-siswa/transkrip/{{ $c->id_siswa }}" class="btn btn-xs btn-outline-primary px-2" style="border-radius: 4px;" title="Buka Transkrip Portofolio Siswa">
                        <i class="fas fa-file-invoice mr-1"></i> Transkrip
                      </a>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="10" class="text-center py-5">
                    <i class="fas fa-medal text-muted mb-2" style="font-size: 2.5rem; opacity: 0.5;"></i>
                    <h6 class="font-weight-bold text-dark">Belum Ada Rekor Capaian Siswa</h6>
                    <p class="text-muted small mb-0">Rekor capaian otomatis tercatat saat guru memfinalkan nilai pada modul Penilaian Praktik.</p>
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
@endsection
