@extends($layout)

@section('content')
<!-- Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem; flex-shrink: 0;">
          <i class="fas fa-inbox"></i>
        </div>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Buku Agenda Surat Masuk</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Pencatatan surat masuk, nomor agenda otomatis, arsip digital, dan disposisi pimpinan</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item active">Surat Masuk</li>
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
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Surat Masuk (Tahun {{ $selectedTahun }})</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.75rem; line-height: 1.1;">{{ $totalSuratTahunIni }} <span style="font-size: 0.9rem; font-weight: 500; color: #64748b;">Surat</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-calendar-alt text-success mr-1"></i> Bulan ini: <strong class="text-dark">{{ $totalSuratBulanIni }}</strong> surat</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px; font-size: 1.3rem; background: #f0fdf4; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-inbox"></i>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-5 col-sm-6 mb-3 mb-md-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #28a745 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Nomor Agenda Berikutnya</div>
              <div class="font-weight-bold text-dark mt-1 font-monospace" style="font-size: 1.05rem;">
                <code class="px-2 py-1 bg-light rounded text-success border">{{ $nextAgendaPreview['nomor_agenda'] }}</code>
              </div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle text-success mr-1"></i> Format: AGM/[No.Urut 3 Digit]/[Tahun]</small>
            </div>
            <div>
              <button type="button" class="btn btn-outline-success btn-sm rounded-pill px-3 shadow-none btn-copy" data-clipboard="{{ $nextAgendaPreview['nomor_agenda'] }}" title="Salin Nomor Agenda">
                <i class="fas fa-copy mr-1"></i> Salin
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="col-md-3 col-sm-12">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px;">
          <div class="card-body p-3 d-flex flex-column justify-content-center align-items-center text-center">
            <a href="{{ route('admin.surat-masuk.create') }}" class="btn btn-success btn-block py-2 shadow-sm font-weight-bold" style="border-radius: 8px;">
              <i class="fas fa-plus-circle mr-1"></i> Catat Surat Masuk
            </a>
            <a href="{{ route('admin.surat-keluar.index') }}" class="text-muted small mt-2">
              <i class="fas fa-paper-plane mr-1"></i> Lihat Buku Surat Keluar &rarr;
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <form action="{{ route('admin.surat-masuk.index') }}" method="GET" class="row align-items-end">
          <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Filter Tahun</label>
            <select name="tahun" class="form-control form-control-sm" onchange="this.form.submit()">
              @foreach($tahunList as $thn)
                <option value="{{ $thn }}" {{ $selectedTahun == $thn ? 'selected' : '' }}>Tahun {{ $thn }}</option>
              @endforeach
            </select>
          </div>

          <div class="col-md-6 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Pencarian</label>
            <div class="input-group input-group-sm">
              <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="No Agenda / No Surat Asal / Instansi Pengirim / Perihal...">
              <div class="input-group-append">
                <button type="submit" class="btn btn-success"><i class="fas fa-search"></i></button>
              </div>
            </div>
          </div>

          <div class="col-md-3 col-sm-12 text-right">
            @if(!empty($search) || $selectedTahun != \Carbon\Carbon::now()->format('Y'))
              <a href="{{ route('admin.surat-masuk.index') }}" class="btn btn-outline-secondary btn-sm btn-block">
                <i class="fas fa-undo mr-1"></i> Reset Pencarian
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
            <i class="fas fa-list-ol text-success mr-2"></i> Arsip Buku Agenda Surat Masuk
          </h3>
          <span class="badge badge-success ml-2 px-2 py-1" style="font-size: 0.75rem;">Total: {{ $suratMasuk->count() }} Data</span>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th style="width: 50px; text-align: center;" class="py-3">No</th>
                <th style="width: 140px;" class="py-3">No. Agenda</th>
                <th style="width: 190px;" class="py-3">Surat & Tanggal</th>
                <th class="py-3">Asal Surat & Perihal</th>
                <th style="width: 180px;" class="py-3">Disposisi</th>
                <th style="width: 100px; text-align: center;" class="py-3">Berkas</th>
                <th style="width: 110px; text-align: center;" class="py-3">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($suratMasuk as $surat)
                <tr>
                  <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                  <td>
                    <div class="d-flex align-items-center">
                      <strong class="text-success mr-2 font-monospace" style="font-size: 0.92rem;">{{ $surat->nomor_agenda }}</strong>
                      <button type="button" class="btn btn-xs btn-outline-secondary rounded-circle shadow-none btn-copy" data-clipboard="{{ $surat->nomor_agenda }}" title="Salin Nomor Agenda" style="width: 22px; height: 22px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                        <i class="fas fa-copy" style="font-size: 10px;"></i>
                      </button>
                    </div>
                    <small class="text-muted d-block">Urut: #{{ str_pad($surat->no_urut, 3, '0', STR_PAD_LEFT) }}</small>
                  </td>
                  <td>
                    <div class="font-weight-bold text-dark font-monospace" style="font-size: 0.85rem;">{{ $surat->nomor_surat_asal }}</div>
                    <small class="text-muted d-block mt-1">
                      <i class="far fa-calendar-alt text-secondary mr-1"></i> Tgl Surat: {{ \Carbon\Carbon::parse($surat->tanggal_surat)->format('d/m/Y') }}
                    </small>
                    <small class="text-muted d-block">
                      <i class="fas fa-check-double text-success mr-1"></i> Diterima: {{ \Carbon\Carbon::parse($surat->tanggal_diterima)->format('d/m/Y') }}
                    </small>
                  </td>
                  <td>
                    <div class="font-weight-bold text-dark mb-1">{{ $surat->perihal }}</div>
                    <div class="text-muted small">
                      <i class="fas fa-building text-primary mr-1"></i> Dari: <strong>{{ $surat->asal_surat }}</strong>
                    </div>
                    @if($surat->keterangan)
                      <small class="text-muted d-block mt-1 fst-italic">"{{ Str::limit($surat->keterangan, 70) }}"</small>
                    @endif
                  </td>
                  <td>
                    @if($surat->disposisi_kepada)
                      <span class="badge badge-light border text-primary font-weight-500 mb-1" style="font-size: 0.76rem;">
                        <i class="fas fa-user-tag mr-1"></i> {{ $surat->disposisi_kepada }}
                      </span>
                      @if($surat->isi_disposisi)
                        <small class="text-muted d-block" style="font-size: 0.75rem; line-height: 1.2;">
                          {{ Str::limit($surat->isi_disposisi, 60) }}
                        </small>
                      @endif
                    @else
                      <span class="badge badge-light text-muted border px-2 py-1" style="font-size: 0.72rem;">Belum Ada Disposisi</span>
                    @endif
                  </td>
                  <td class="text-center">
                    @if($surat->file_lampiran)
                      @php
                        $ext = pathinfo($surat->file_lampiran, PATHINFO_EXTENSION);
                        $isPdf = strtolower($ext) === 'pdf';
                        $fileUrl = asset('storage/lampiran_surat_masuk/' . $surat->file_lampiran);
                      @endphp
                      <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-2 py-1 shadow-sm" title="Lihat Berkas">
                        <i class="fas {{ $isPdf ? 'fa-file-pdf text-danger' : 'fa-file-image text-success' }} mr-1"></i> Berkas
                      </a>
                    @else
                      <span class="badge badge-light text-muted border px-2 py-1" style="font-size: 0.72rem;">Tanpa Berkas</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm">
                      <a href="{{ route('admin.surat-masuk.edit', $surat->id) }}" class="btn btn-light border text-primary" title="Edit Surat">
                        <i class="fas fa-edit"></i>
                      </a>
                      <button type="button" class="btn btn-light border text-danger btn-delete" data-id="{{ $surat->id }}" data-nomor="{{ $surat->nomor_agenda }}" title="Hapus Surat">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                    <form id="delete-form-{{ $surat->id }}" action="{{ route('admin.surat-masuk.destroy', $surat->id) }}" method="POST" style="display: none;">
                      @csrf
                      @method('DELETE')
                    </form>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-5 text-muted">
                    <div class="d-flex flex-column align-items-center justify-content-center">
                      <i class="fas fa-inbox text-muted mb-2" style="font-size: 2.5rem; opacity: 0.4;"></i>
                      <h6 class="font-weight-bold text-secondary mb-1">Belum Ada Catatan Surat Masuk</h6>
                      <p class="small text-muted mb-3">Klik tombol di bawah untuk mencatat surat masuk baru.</p>
                      <a href="{{ route('admin.surat-masuk.create') }}" class="btn btn-success btn-sm px-3 rounded-pill shadow-sm">
                        <i class="fas fa-plus mr-1"></i> Catat Surat Masuk
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
        title: 'Nomor agenda disalin!',
        text: text,
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
      });
    } else {
      alert('Nomor agenda berhasil disalin: ' + text);
    }
  }

  $('.btn-delete').on('click', function() {
    var id = $(this).data('id');
    var nomor = $(this).data('nomor');

    if (typeof Swal !== 'undefined') {
      Swal.fire({
        title: 'Hapus Surat Masuk?',
        text: 'Surat dengan Agenda: ' + nomor + ' akan dihapus permanen dari arsip!',
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
      if (confirm('Apakah Anda yakin ingin menghapus surat agenda: ' + nomor + '?')) {
        $('#delete-form-' + id).submit();
      }
    }
  });
});
</script>
@endsection
@endsection
