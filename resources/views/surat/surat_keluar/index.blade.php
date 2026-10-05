@extends($layout)

@section('content')
<!-- Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem; flex-shrink: 0;">
          <i class="fas fa-paper-plane"></i>
        </div>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Buku Agenda Surat Keluar</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Pencatatan, penomoran otomatis terstandar, dan arsip berkas surat keluar sekolah</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item active">Surat Keluar</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="content">
  <div class="container-fluid">

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
        <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <!-- Statistic & Quick Info Cards -->
    <div class="row mb-3">
      <div class="col-md-4 col-sm-6 mb-3 mb-md-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #1d72fe !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Surat Keluar (Tahun {{ $selectedTahun }})</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.75rem; line-height: 1.1;">{{ $totalSuratTahunIni }} <span style="font-size: 0.9rem; font-weight: 500; color: #64748b;">Surat</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-calendar-alt text-primary mr-1"></i> Bulan ini: <strong class="text-dark">{{ $totalSuratBulanIni }}</strong> surat</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; font-size: 1.3rem; background: #eff6ff; color: #1d72fe; flex-shrink: 0;">
              <i class="fas fa-paper-plane"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-5 col-sm-6 mb-3 mb-md-0">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px; background: #ffffff; border-left: 4px solid #007bff !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Estimasi Nomor Berikutnya</div>
              <div class="font-weight-bold text-dark mt-1 font-monospace" style="font-size: 1.05rem; letter-spacing: 0.5px;">
                <code class="px-2 py-1 bg-light rounded text-primary border" id="quickNextNumber">{{ $nextNumberPreview['nomor_surat'] }}</code>
              </div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle text-info mr-1"></i> Format: [No.Urut]/[Kode]/SMK-WI/[Bulan]/[Tahun]</small>
            </div>
            <div>
              <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-3 shadow-none btn-copy" data-clipboard="{{ $nextNumberPreview['nomor_surat'] }}" title="Salin Nomor Berikutnya">
                <i class="fas fa-copy mr-1"></i> Salin
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-12">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px;">
          <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center text-center">
            <a href="{{ route('admin.surat-keluar.create') }}" class="btn btn-primary btn-block py-2 shadow-sm font-weight-bold" style="border-radius: 8px;">
              <i class="fas fa-plus-circle mr-1"></i> Buat Surat Baru
            </a>
            <a href="{{ route('admin.surat-masuk.index') }}" class="text-muted small mt-2">
              <i class="fas fa-inbox mr-1"></i> Lihat Buku Surat Masuk &rarr;
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <form action="{{ route('admin.surat-keluar.index') }}" method="GET" class="row align-items-end">
          <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Filter Tahun</label>
            <select name="tahun" class="form-control form-control-sm" onchange="this.form.submit()">
              @foreach($tahunList as $thn)
                <option value="{{ $thn }}" {{ $selectedTahun == $thn ? 'selected' : '' }}>Tahun {{ $thn }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Klasifikasi Surat</label>
            <select name="klasifikasi" class="form-control form-control-sm" onchange="this.form.submit()">
              <option value="">-- Semua Klasifikasi --</option>
              @foreach($klasifikasiList as $code => $label)
                <option value="{{ $code }}" {{ $selectedKlasifikasi == $code ? 'selected' : '' }}>[{{ $code }}] {{ $label }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-3 col-sm-8 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Pencarian</label>
            <div class="input-group input-group-sm">
              <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Nomor / Perihal / Tujuan...">
              <div class="input-group-append">
                <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
              </div>
            </div>
          </div>

          <div class="col-md-2 col-sm-4 text-right">
            @if(!empty($selectedKlasifikasi) || !empty($search) || $selectedTahun != \Carbon\Carbon::now()->format('Y'))
              <a href="{{ route('admin.surat-keluar.index') }}" class="btn btn-outline-secondary btn-sm btn-block">
                <i class="fas fa-undo mr-1"></i> Reset Filter
              </a>
            @endif
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
      <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
        <div>
          <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
            <i class="fas fa-list-ol text-primary mr-2"></i> Daftar Arsip Surat Keluar
          </h3>
          <span class="badge badge-info ml-2 px-2 py-1" style="font-size: 0.75rem;">Total: {{ $suratKeluar->count() }} Data</span>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th style="width: 50px; text-align: center;" class="py-3">No</th>
                <th style="width: 220px;" class="py-3">Nomor Surat</th>
                <th style="width: 110px;" class="py-3">Tanggal</th>
                <th class="py-3">Perihal & Tujuan</th>
                <th style="width: 140px;" class="py-3">Klasifikasi</th>
                <th style="width: 100px; text-align: center;" class="py-3">Berkas</th>
                <th style="width: 110px; text-align: center;" class="py-3">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($suratKeluar as $surat)
                <tr>
                  <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <strong class="text-dark mr-2" style="font-family: monospace; font-size: 0.92rem;">{{ $surat->nomor_surat }}</strong>
                      <button type="button" class="btn btn-xs btn-outline-secondary rounded-circle shadow-none btn-copy" data-clipboard="{{ $surat->nomor_surat }}" title="Salin Nomor Surat" style="width: 22px; height: 22px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                        <i class="fas fa-copy" style="font-size: 10px;"></i>
                      </button>
                    </div>
                    <small class="text-muted d-block">Urut: #{{ str_pad($surat->no_urut, 3, '0', STR_PAD_LEFT) }} | Tahun: {{ $surat->tahun }}</small>
                  </td>
                  <td>
                    <span class="font-weight-500 text-dark">{{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}</span>
                  </td>
                  <td>
                    <div class="font-weight-bold text-dark mb-1">{{ $surat->perihal }}</div>
                    <div class="text-muted small">
                      <i class="fas fa-paper-plane text-primary mr-1" style="font-size: 0.7rem;"></i> Kepada: <strong>{{ $surat->tujuan_surat }}</strong>
                    </div>
                    @if($surat->siswa)
                      <div class="mt-1">
                        <span class="badge badge-light border text-dark" style="font-size: 0.72rem;">
                          <i class="fas fa-user-graduate text-success mr-1"></i> Siswa: {{ $surat->siswa->nama_siswa }} ({{ $surat->siswa->kelas->nama_kelas ?? '-' }})
                        </span>
                      </div>
                    @endif
                    @if($surat->guru)
                      <div class="mt-1">
                        <span class="badge badge-light border text-dark" style="font-size: 0.72rem;">
                          <i class="fas fa-chalkboard-teacher text-info mr-1"></i> Guru: {{ $surat->guru->nama_guru }}
                        </span>
                      </div>
                    @endif
                    @if($surat->penandatangan)
                      <div class="text-muted small mt-1">
                        <i class="fas fa-signature text-secondary mr-1" style="font-size: 0.7rem;"></i> Ttd: {{ $surat->penandatangan }}
                      </div>
                    @endif
                  </td>
                  <td>
                    <span class="badge badge-pill badge-primary px-2 py-1 font-weight-500" style="font-size: 0.75rem;">
                      {{ $surat->kode_klasifikasi }}
                    </span>
                    <small class="text-muted d-block mt-1" style="font-size: 0.72rem; line-height: 1.2;">
                      {{ $surat->nama_klasifikasi }}
                    </small>
                  </td>
                  <td class="text-center">
                    @if($surat->file_lampiran)
                      @php
                        $ext = pathinfo($surat->file_lampiran, PATHINFO_EXTENSION);
                        $isPdf = strtolower($ext) === 'pdf';
                        $fileUrl = asset('storage/lampiran_surat_keluar/' . $surat->file_lampiran);
                      @endphp
                      <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-info rounded-pill px-2 py-1 shadow-sm" title="Lihat Berkas">
                        <i class="fas {{ $isPdf ? 'fa-file-pdf text-danger' : 'fa-file-image text-primary' }} mr-1"></i> Berkas
                      </a>
                    @else
                      <span class="badge badge-light text-muted border px-2 py-1" style="font-size: 0.72rem;">Tanpa Berkas</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm">
                      <a href="{{ route('admin.surat-keluar.edit', $surat->id) }}" class="btn btn-light border text-primary" title="Edit Surat">
                        <i class="fas fa-edit"></i>
                      </a>
                      <button type="button" class="btn btn-light border text-danger btn-delete" data-id="{{ $surat->id }}" data-nomor="{{ $surat->nomor_surat }}" title="Hapus Surat">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                    <form id="delete-form-{{ $surat->id }}" action="{{ route('admin.surat-keluar.destroy', $surat->id) }}" method="POST" style="display: none;">
                      @csrf
                      @method('DELETE')
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-5 text-muted">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                      <i class="fas fa-folder-open text-muted mb-2" style="font-size: 2.5rem; opacity: 0.4;"></i>
                      <h6 class="font-weight-bold text-secondary mb-1">Belum Ada Arsip Surat Keluar</h6>
                      <p class="small text-muted mb-3">Klik tombol di bawah untuk membuat dan menomori surat keluar pertama Anda.</p>
                      <a href="{{ route('admin.surat-keluar.create') }}" class="btn btn-primary btn-sm px-3 rounded-pill shadow-sm">
                        <i class="fas fa-plus mr-1"></i> Buat Surat Baru
                      </a>
                    </div>
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

@section('scripts')
<script>
$(document).ready(function() {
  // Salin Nomor Surat ke Clipboard
  $('.btn-copy').on('click', function() {
    var textToCopy = $(this).data('clipboard');
    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard.writeText(textToCopy).then(function() {
        showCopyToast(textToCopy);
      });
    } else {
      var tempInput = $('<input>');
      $('body').append(tempInput);
      tempInput.val(textToCopy).select();
      document.execCommand('copy');
      tempInput.remove();
      showCopyToast(textToCopy);
    }
  });

  function showCopyToast(text) {
    if (typeof Swal !== 'undefined') {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Nomor surat disalin!',
        text: text,
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
      });
    } else {
      alert('Nomor surat berhasil disalin: ' + text);
    }
  }

  // Konfirmasi Hapus Surat
  $('.btn-delete').on('click', function() {
    var id = $(this).data('id');
    var nomor = $(this).data('nomor');

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Hapus Surat Keluar?',
        text: 'Surat No: ' + nomor + ' akan dihapus permanen dari arsip!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash-alt mr-1"></i> Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          $('#delete-form-' + id).submit();
        }
      });
    } else {
      if (confirm('Apakah Anda yakin ingin menghapus surat no: ' + nomor + '?')) {
        $('#delete-form-' + id).submit();
      }
    }
  });
});
</script>
@endsection
@endsection
