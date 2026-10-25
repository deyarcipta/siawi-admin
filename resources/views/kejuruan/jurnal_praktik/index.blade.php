@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-book-reader text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Jurnal Praktik Guru</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Pencatatan realisasi kegiatan praktik, ketercapaian materi, dan absensi siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/jurnal-praktik" class="text-primary font-weight-500">Kejuruan</a></li>
          <li class="breadcrumb-item active">Jurnal Praktik</li>
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

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
        <i class="fas fa-exclamation-circle mr-2"></i> {!! session('error') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <!-- Summary Stat Cards -->
    <div class="row mb-3">
      <!-- Total Jurnal Tercatat -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Jurnal Praktik</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $jurnals->count() }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Entri</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-database text-primary mr-1"></i> Rekapitulasi</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-book"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Sesi Hari Ini -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Jadwal Hari Ini</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ count($jadwalHariIni) }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Sesi</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-calendar-day text-success mr-1"></i> {{ $todayIndo }}</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-calendar-check"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Terisi Hari Ini -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #8b5cf6 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Terisi Hari Ini</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ count($jurnalHariIniJadwalIds) }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Sesi</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-check text-purple mr-1"></i> Dari {{ count($jadwalHariIni) }} Sesi</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f5f3ff; color: #8b5cf6; flex-shrink: 0;">
              <i class="fas fa-clipboard-check"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Menunggu Verifikasi -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Status Approval</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $jurnals->where('status_approval', 'menunggu')->count() }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Menunggu</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-hourglass-half text-warning mr-1"></i> Perlu Verifikasi Kaprog</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fffbeb; color: #f59e0b; flex-shrink: 0;">
              <i class="fas fa-user-check"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Toolbar Card -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <form method="GET" action="/admin/kejuruan/jurnal-praktik" class="row align-items-end">
          <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Awal</label>
            <input type="date" name="tanggal_awal" class="form-control form-control-sm" value="{{ request('tanggal_awal') }}">
          </div>

          <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" class="form-control form-control-sm" value="{{ request('tanggal_akhir') }}">
          </div>

          <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Status Approval</label>
            <div class="d-flex align-items-center" style="gap: 8px;">
              <select name="status_approval" class="form-control form-control-sm" style="flex: 1;">
                <option value="">-- Semua Status --</option>
                <option value="menunggu" {{ request('status_approval') === 'menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="disetujui" {{ request('status_approval') === 'disetujui' ? 'selected' : '' }}>Disetujui Kaprog</option>
                <option value="ditolak" {{ request('status_approval') === 'ditolak' ? 'selected' : '' }}>Ditolak (Perlu Revisi)</option>
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
            <a href="/admin/kejuruan/jurnal-praktik/create" class="btn btn-primary btn-sm shadow-sm font-weight-bold">
              <i class="fas fa-plus mr-1"></i> Isi Jurnal Baru
            </a>
            <a href="/admin/kejuruan/jadwal-praktik/create?tipe=insidental" class="btn btn-warning btn-sm shadow-sm font-weight-bold text-dark">
              <i class="fas fa-bolt mr-1"></i> + Praktik Dadakan / Tambahan
            </a>
            <a href="/admin/kejuruan/laporan-praktik" class="btn btn-outline-secondary btn-sm shadow-sm">
              <i class="fas fa-file-invoice mr-1"></i> Rekap Laporan
            </a>
          </div>

          <div>
            @if(request()->anyFilled(['tanggal_awal', 'tanggal_akhir', 'status_approval']))
              <a href="/admin/kejuruan/jurnal-praktik" class="btn btn-link text-muted btn-sm p-0">
                <i class="fas fa-undo mr-1"></i> Reset Filter
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- SECTION: JADWAL HARI INI SIAP DIISI -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; background: linear-gradient(to right, #ffffff, #f8fafc); border-left: 4px solid #0284c7 !important;">
      <div class="card-header bg-transparent border-0 pt-3 pb-1 d-flex align-items-center justify-content-between">
        <h6 class="font-weight-bold text-dark mb-0">
          <i class="fas fa-calendar-day text-primary mr-1"></i> Jadwal Praktik Hari Ini ({{ $todayIndo }}, {{ \Carbon\Carbon::parse($todayDate)->translatedFormat('d F Y') }})
        </h6>
        <span class="badge badge-light border text-muted px-2 py-1 font-weight-normal">
          {{ count($jadwalHariIni) }} Sesi Terjadwal
        </span>
      </div>
      <div class="card-body p-3">
        @if(count($jadwalHariIni) > 0)
          <div class="row">
            @foreach($jadwalHariIni as $jh)
              @php
                $isFilled = in_array($jh->id_jadwal_praktik, $jurnalHariIniJadwalIds);
              @endphp
              <div class="col-md-6 col-lg-4 mb-2 mb-lg-0">
                <div class="card border shadow-sm mb-2 {{ $isFilled ? 'border-success' : 'border-primary' }}" style="border-radius: 10px; background-color: #fff;">
                  <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                      <span class="badge badge-primary px-2 font-weight-bold" style="font-size: 0.75rem;">
                        {{ substr($jh->jam_mulai, 0, 5) }} - {{ substr($jh->jam_selesai, 0, 5) }}
                      </span>
                      @if($isFilled)
                        <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Selesai Diisi</span>
                      @else
                        <span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-clock mr-1"></i> Siap Diisi</span>
                      @endif
                    </div>

                    <h6 class="font-weight-bold text-dark mb-1 mt-2 text-truncate" title="{{ $jh->mapel->nama_mapel ?? '-' }}">
                      {{ $jh->mapel->nama_mapel ?? '-' }}
                    </h6>
                    <div class="small text-muted mb-1">
                      <i class="fas fa-users mr-1 text-secondary"></i> Kelas: <strong>{{ $jh->kelas->nama_kelas ?? '-' }}</strong>
                    </div>
                    <div class="small text-muted mb-3">
                      <i class="fas fa-flask mr-1 text-secondary"></i> Ruang: {{ $jh->lab->nama_lab ?? '-' }}
                    </div>

                    @if($isFilled)
                      <span class="btn btn-outline-success btn-sm btn-block disabled" style="border-radius: 6px; font-weight: 500;">
                        <i class="fas fa-check-circle mr-1"></i> Jurnal Sudah Tercatat
                      </span>
                    @else
                      <a href="/admin/kejuruan/jurnal-praktik/create?id_jadwal_praktik={{ $jh->id_jadwal_praktik }}&tanggal={{ $todayDate }}" class="btn btn-primary btn-sm btn-block shadow-sm" style="border-radius: 6px; font-weight: 600;">
                        <i class="fas fa-pen mr-1"></i> Isi Jurnal Sekarang
                      </a>
                    @endif
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="text-center py-3 text-muted small">
            <i class="far fa-smile mr-1 text-primary"></i> Tidak ada agenda jadwal praktik untuk Anda pada hari {{ $todayIndo }} ini.
          </div>
        @endif
      </div>
    </div>

    <!-- SECTION: RIWAYAT JURNAL PRAKTIK -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
      <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
        <div>
          <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
            <i class="fas fa-list-ul text-primary mr-2"></i> Riwayat Jurnal Praktik
          </h3>
          <span class="badge badge-light border text-secondary ml-2 px-2 py-1" style="font-size: 0.75rem;">
            Total: {{ $jurnals->count() }} Data
          </span>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th style="width: 50px" class="text-center">No</th>
                <th style="width: 110px">Tanggal</th>
                <th>Kelas & Mapel</th>
                <th>Laboratorium</th>
                <th>Instruktur / Guru</th>
                <th>Materi Praktik</th>
                <th style="width: 90px" class="text-center">Hadir</th>
                <th style="width: 120px" class="text-center">Status</th>
                <th style="width: 110px" class="text-center">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($jurnals as $j)
                <tr>
                  <td class="text-center align-middle">{{ $loop->iteration }}</td>
                  <td class="align-middle font-weight-500">
                    {{ $j->tanggal->format('d/m/Y') }}
                  </td>
                  <td class="align-middle">
                    <div class="font-weight-bold text-dark">{{ $j->jadwalPraktik->mapel->nama_mapel ?? '-' }}</div>
                    <small class="text-muted"><i class="fas fa-users mr-1"></i> {{ $j->jadwalPraktik->kelas->nama_kelas ?? '-' }}</small>
                  </td>
                  <td class="align-middle">
                    <span class="badge badge-light border font-weight-500">
                      {{ $j->jadwalPraktik->lab->nama_lab ?? '-' }}
                    </span>
                  </td>
                  <td class="align-middle text-muted">
                    {{ $j->jadwalPraktik->guru->nama_guru ?? '-' }}
                  </td>
                  <td class="align-middle" style="max-width: 200px;">
                    <span class="d-inline-block text-truncate" style="max-width: 190px;" title="{{ $j->materi }}">
                      {{ $j->materi }}
                    </span>
                  </td>
                  <td class="text-center align-middle">
                    <span class="badge badge-info px-2 py-1 font-weight-bold">
                      {{ $j->jumlah_hadir }} Siswa
                    </span>
                  </td>
                  <td class="text-center align-middle">
                    @if($j->status_approval === 'disetujui')
                      <span class="badge badge-success px-2 py-1">
                        <i class="fas fa-check mr-1"></i> Disetujui
                      </span>
                    @elseif($j->status_approval === 'ditolak')
                      <span class="badge badge-danger px-2 py-1" title="{{ $j->catatan_kaprog }}">
                        <i class="fas fa-times mr-1"></i> Ditolak
                      </span>
                    @else
                      <span class="badge badge-warning text-dark px-2 py-1">
                        <i class="fas fa-hourglass-half mr-1"></i> Menunggu
                      </span>
                    @endif
                  </td>
                  <td class="text-center align-middle">
                    <div class="btn-group" role="group">
                      <a href="/admin/kejuruan/jurnal-praktik/{{ $j->id_jurnal_praktik }}" class="btn btn-sm btn-outline-info" title="Lihat Detail">
                        <i class="fas fa-eye"></i>
                      </a>
                      @if($user->hasRole('admin') || ($j->status_approval !== 'disetujui' && $j->jadwalPraktik->id_guru == $user->id_guru))
                        <a href="/admin/kejuruan/jurnal-praktik/{{ $j->id_jurnal_praktik }}/edit" class="btn btn-sm btn-outline-primary" title="Ubah">
                          <i class="fas fa-edit"></i>
                        </a>
                      @endif
                      @if($user->hasAnyRole(['admin', 'kaprog']))
                        <form action="/admin/kejuruan/jurnal-praktik/{{ $j->id_jurnal_praktik }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus jurnal ini?')">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </form>
                      @endif
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="9" class="text-center py-4 text-muted">
                    <i class="fas fa-info-circle mr-1"></i> Belum ada rekaman jurnal praktik yang diisi.
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
