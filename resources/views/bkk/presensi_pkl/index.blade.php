@extends($layout)
@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-user-clock text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Monitoring Presensi Siswa PKL</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Pemantauan kehadiran harian siswa PKL berbasis titik lokasi radius kantor mitra (Geofencing)</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="#">BKK & Hubin</a></li>
          <li class="breadcrumb-item active">Presensi PKL</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">

    <!-- Stat Cards Overview -->
    <div class="row mb-3">
      <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon bg-primary text-white" style="border-radius: 10px;"><i class="fas fa-user-graduate"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">TOTAL SISWA PKL</span>
            <span class="info-box-number text-dark font-weight-bold" style="font-size: 1.35rem;">{{ $totalSiswaPkl }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Siswa</small></span>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon bg-success text-white" style="border-radius: 10px;"><i class="fas fa-check-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">SUDAH PRESENSI</span>
            <span class="info-box-number text-dark font-weight-bold" style="font-size: 1.35rem;">{{ $totalHadir }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Siswa</small></span>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon bg-danger text-white" style="border-radius: 10px;"><i class="fas fa-times-circle"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">BELUM ABSEN</span>
            <span class="info-box-number text-dark font-weight-bold" style="font-size: 1.35rem;">{{ $totalBelumAbsen }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Siswa</small></span>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon text-white" style="background-color: #0284c7; border-radius: 10px;"><i class="fas fa-building"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">TOTAL MITRA DU/DI</span>
            <span class="info-box-number text-dark font-weight-bold" style="font-size: 1.35rem;">{{ $perusahaanList->count() }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Mitra</small></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 14px;">
      <div class="card-body p-3">
        <form action="{{ route('admin.absensi-pkl.index') }}" method="GET" class="form-row align-items-end">
          <div class="col-md-3 col-sm-6 mb-2">
            <label class="font-weight-bold text-dark small mb-1">
              <i class="fas fa-calendar-alt text-primary mr-1"></i> Tanggal Presensi
            </label>
            <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ $tanggal }}" style="border-radius: 6px;">
          </div>
          <div class="col-md-3 col-sm-6 mb-2">
            <label class="font-weight-bold text-dark small mb-1">
              <i class="fas fa-building text-info mr-1"></i> Mitra DU/DI
            </label>
            <select name="id_perusahaan" class="form-control form-control-sm select2" style="border-radius: 6px; width: 100%;">
              <option value="">-- Semua Mitra DU/DI --</option>
              @foreach($perusahaanList as $p)
                <option value="{{ $p->id_perusahaan }}" {{ $idPerusahaan == $p->id_perusahaan ? 'selected' : '' }}>
                  {{ $p->nama_perusahaan }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2 col-sm-6 mb-2">
            <label class="font-weight-bold text-dark small mb-1">
              <i class="fas fa-chalkboard text-secondary mr-1"></i> Kelas
            </label>
            <select name="id_kelas" class="form-control form-control-sm select2" style="border-radius: 6px; width: 100%;">
              <option value="">-- Semua Kelas --</option>
              @foreach($kelasList as $k)
                <option value="{{ $k->id_kelas }}" {{ $idKelas == $k->id_kelas ? 'selected' : '' }}>
                  {{ $k->nama_kelas }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-2 col-sm-6 mb-2">
            <label class="font-weight-bold text-dark small mb-1">
              <i class="fas fa-filter text-warning mr-1"></i> Status Kehadiran
            </label>
            <select name="status" class="form-control form-control-sm" style="border-radius: 6px;">
              <option value="semua" {{ $filterStatus == 'semua' ? 'selected' : '' }}>Semua Status</option>
              <option value="hadir" {{ $filterStatus == 'hadir' ? 'selected' : '' }}>Sudah Presensi</option>
              <option value="belum_absen" {{ $filterStatus == 'belum_absen' ? 'selected' : '' }}>Belum Absen</option>
            </select>
          </div>
          <div class="col-md-2 col-sm-12 mb-2 d-flex">
            <button type="submit" class="btn btn-primary btn-sm flex-fill mr-1 font-weight-bold" style="border-radius: 6px;">
              <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.absensi-pkl.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 6px;" title="Reset Filter">
              <i class="fas fa-redo"></i>
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Main Table Card -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-2 py-sm-3 d-flex flex-wrap justify-content-between align-items-center" style="gap: 8px;">
            <div class="d-flex align-items-center pr-1" style="min-width: 0;">
              <i class="fas fa-clipboard-check text-primary mr-2" style="font-size: 1.25rem;"></i>
              <div style="min-width: 0;">
                <h3 class="card-title text-dark font-weight-bold mb-0 text-truncate" style="font-size: 0.95rem; line-height: 1.2; float: none;">
                  Rekap Kehadiran Presensi PKL
                </h3>
                <div class="text-muted d-none d-sm-block" style="font-size: 0.74rem;">
                  Tanggal: {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                </div>
              </div>
            </div>
            <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
              @php
                $mode = $setting->pkl_attendance_mode ?? 'radius_foto_lock';
              @endphp
              @if($mode === 'radius_lock')
                <span class="badge badge-success px-3 py-2 font-weight-normal" style="border-radius: 8px; font-size: 0.8rem;">
                  <i class="fas fa-feather-alt mr-1"></i> Mode: Radius + Lock Device
                </span>
              @elseif($mode === 'radius_foto')
                <span class="badge badge-info px-3 py-2 font-weight-normal" style="border-radius: 8px; font-size: 0.8rem;">
                  <i class="fas fa-camera mr-1"></i> Mode: Radius + Foto Selfie
                </span>
              @else
                <span class="badge badge-primary px-3 py-2 font-weight-normal" style="border-radius: 8px; font-size: 0.8rem;">
                  <i class="fas fa-shield-alt mr-1"></i> Mode: Radius + Foto + Lock Device
                </span>
              @endif
            </div>
          </div>

      <div class="card-body p-3">
        <div class="table-responsive">
          <table id="example2" class="table table-bordered table-hover table-striped align-middle">
            <thead class="bg-light text-dark">
              <tr>
                <th style="width: 10px" class="text-center">No</th>
                <th style="min-width: 160px;">Siswa & Kelas</th>
                <th style="min-width: 160px;">Tempat PKL (DU/DI)</th>
                <th style="min-width: 140px;">Presensi Masuk</th>
                <th style="min-width: 140px;">Presensi Pulang</th>
                <th class="text-center" style="min-width: 120px;">Status</th>
                <th class="text-center" style="min-width: 80px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($dataList as $item)
                @php
                  $abs = $item->absensi;
                @endphp
                <tr>
                  <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                  <td>
                    <div class="font-weight-bold text-dark" style="font-size: 0.92rem;">
                      {{ $item->siswa->nama_siswa ?? '-' }}
                    </div>
                    <small class="text-muted d-block">
                      NIS: {{ $item->siswa->nis ?? '-' }} | Kelas: <strong>{{ $item->kelas->nama_kelas ?? ($item->siswa->kelas->nama_kelas ?? '-') }}</strong>
                    </small>
                    <!-- Status Lock Device -->
                    <div class="mt-1 d-flex align-items-center flex-wrap" style="gap: 4px;">
                      @if(!empty($item->siswa->device_id))
                        <span class="badge badge-light border text-primary" style="font-size: 0.72rem;" title="ID Perangkat: {{ $item->siswa->device_id }}">
                          <i class="fas fa-mobile-alt mr-1"></i> {{ $item->siswa->device_name ?: 'HP Terkunci' }}
                        </span>
                        <form action="{{ route('admin.absensi-pkl.reset-device', $item->siswa->id_siswa) }}" method="POST" class="d-inline form-reset-device">
                          @csrf
                          <button type="button" class="btn btn-xs btn-outline-warning btn-confirm-reset-device" data-nama="{{ $item->siswa->nama_siswa ?? 'siswa ini' }}" title="Reset kuncian HP agar siswa bisa login di perangkat lain">
                            <i class="fas fa-sync-alt mr-1"></i> Reset HP
                          </button>
                        </form>
                      @else
                        <span class="badge badge-light border text-muted" style="font-size: 0.72rem;">
                          <i class="fas fa-lock-open mr-1"></i> HP Belum Terkunci
                        </span>
                      @endif
                    </div>
                  </td>
                  <td>
                    <div class="font-weight-600 text-dark" style="font-size: 0.88rem;">
                      <i class="fas fa-building text-primary mr-1"></i> {{ $item->perusahaan->nama_perusahaan ?? '-' }}
                    </div>
                    @if($item->perusahaan && $item->perusahaan->latitude && $item->perusahaan->longitude)
                      <small class="text-success d-block" style="font-size: 0.74rem;">
                        <i class="fas fa-crosshairs mr-1"></i> Radius: {{ $item->perusahaan->radius_meter ?? 50 }}m
                      </small>
                    @else
                      <small class="text-danger d-block" style="font-size: 0.74rem;">
                        <i class="fas fa-exclamation-circle mr-1"></i> Titik kantor belum disetel
                      </small>
                    @endif
                  </td>
                  <td>
                    @if($abs && $abs->jam_masuk)
                      <div class="font-weight-bold text-dark">
                        <i class="far fa-clock text-success mr-1"></i> {{ substr($abs->jam_masuk, 0, 5) }} WIB
                      </div>
                      <small class="text-muted d-block" style="font-size: 0.74rem;">
                        Jarak: <strong>{{ $abs->jarak_masuk_meter ?? 0 }}m</strong> dari kantor
                      </small>
                      @if($abs->foto_masuk_url)
                        <button type="button" class="btn btn-xs btn-outline-primary mt-1 btn-preview-foto" data-foto="{{ $abs->foto_masuk_url }}" data-judul="Foto Selfie Masuk - {{ $item->siswa->nama_siswa ?? '' }}" data-jam="{{ substr($abs->jam_masuk, 0, 5) }} WIB" data-jarak="{{ $abs->jarak_masuk_meter ?? 0 }}m">
                          <i class="fas fa-camera mr-1"></i> Lihat Foto
                        </button>
                      @endif
                    @else
                      <span class="text-muted font-italic" style="font-size: 0.82rem;">- Belum masuk -</span>
                    @endif
                  </td>
                  <td>
                    @if($abs && $abs->jam_pulang)
                      <div class="font-weight-bold text-dark">
                        <i class="far fa-clock text-danger mr-1"></i> {{ substr($abs->jam_pulang, 0, 5) }} WIB
                      </div>
                      <small class="text-muted d-block" style="font-size: 0.74rem;">
                        Jarak: <strong>{{ $abs->jarak_pulang_meter ?? 0 }}m</strong> dari kantor
                      </small>
                      @if($abs->foto_pulang_url)
                        <button type="button" class="btn btn-xs btn-outline-primary mt-1 btn-preview-foto" data-foto="{{ $abs->foto_pulang_url }}" data-judul="Foto Selfie Pulang - {{ $item->siswa->nama_siswa ?? '' }}" data-jam="{{ substr($abs->jam_pulang, 0, 5) }} WIB" data-jarak="{{ $abs->jarak_pulang_meter ?? 0 }}m">
                          <i class="fas fa-camera mr-1"></i> Lihat Foto
                        </button>
                      @endif
                    @else
                      <span class="text-muted font-italic" style="font-size: 0.82rem;">- Belum pulang -</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($item->status_kehadiran === 'hadir')
                      <span class="badge badge-success px-2 py-1 font-weight-bold" style="border-radius: 6px;">
                        <i class="fas fa-check-circle mr-1"></i> Hadir
                      </span>
                    @else
                      <span class="badge badge-secondary px-2 py-1 font-weight-normal" style="border-radius: 6px;">
                        Belum Absen
                      </span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($abs)
                      <form action="{{ route('admin.absensi-pkl.destroy', $abs->id_absensi_pkl) }}" method="POST" class="d-inline" onsubmit="return confirm('Reset presensi siswa ini untuk tanggal {{ $tanggal }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Reset Absensi">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-4 text-muted">
                    <i class="fas fa-inbox fa-2x d-block mb-2 text-muted" style="opacity: 0.4;"></i>
                    Tidak ada data siswa PKL aktif pada tanggal yang dipilih.
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

  </div><!-- /.container-fluid -->
</div><!-- /.content -->

<!-- Modal Preview Foto Selfie -->
<div class="modal fade" id="modalPreviewFoto" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 14px; overflow: hidden;">
      <div class="modal-header bg-dark text-white py-2 px-3">
        <h6 class="modal-title font-weight-bold" id="previewFotoJudul">Bukti Selfie Presensi</h6>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body p-3 text-center bg-light">
        <img id="previewFotoImg" src="" alt="Selfie Presensi" class="img-fluid rounded border shadow-sm mb-2" style="max-height: 420px; object-fit: cover;">
        <div class="d-flex justify-content-around text-muted small mt-2">
          <span><i class="far fa-clock mr-1"></i> Waktu: <strong id="previewFotoJam">-</strong></span>
          <span><i class="fas fa-map-marker-alt mr-1"></i> Jarak: <strong id="previewFotoJarak">-</strong></span>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
  $('.select2').select2({ theme: 'bootstrap4' });

  $('.btn-preview-foto').on('click', function () {
    let fotoUrl = $(this).data('foto');
    let judul = $(this).data('judul');
    let jam = $(this).data('jam');
    let jarak = $(this).data('jarak');

    $('#previewFotoImg').attr('src', fotoUrl);
    $('#previewFotoJudul').text(judul);
    $('#previewFotoJam').text(jam);
    $('#previewFotoJarak').text(jarak);
    $('#modalPreviewFoto').modal('show');
  });

  $('.btn-confirm-reset-device').on('click', function (e) {
    e.preventDefault();
    let form = $(this).closest('form');
    let nama = $(this).data('nama');

    Swal.fire({
      title: 'Reset Kuncian Perangkat?',
      html: `Lepaskan kuncian HP untuk <b>${nama}</b>?<br><small class="text-muted">Siswa akan dapat mendaftarkan HP baru pada login berikutnya.</small>`,
      icon: 'question',
      showCancelButton: true,
      confirmButtonColor: '#ffc107',
      cancelButtonColor: '#6c757d',
      confirmButtonText: '<span class="text-dark"><i class="fas fa-sync-alt mr-1"></i> Ya, Reset</span>',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });
});
</script>
@endpush

