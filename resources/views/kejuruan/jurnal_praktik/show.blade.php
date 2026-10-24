@extends($layout)
@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-file-alt"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Detail Jurnal Praktik</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Informasi lengkap pelaksanaan sesi praktik laboratorium</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/kejuruan/jurnal-praktik" class="text-primary font-weight-500">Jurnal Praktik</a></li>
          <li class="breadcrumb-item active">Detail Jurnal</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-check-circle mr-1"></i> {!! session('success') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <div>
              <span class="badge badge-primary px-2 py-1 mr-2 font-weight-bold">
                {{ $jurnal->jadwalPraktik->kelas->nama_kelas ?? '-' }}
              </span>
              <span class="font-weight-bold text-dark" style="font-size: 1.05rem;">
                {{ $jurnal->jadwalPraktik->mapel->nama_mapel ?? '-' }}
              </span>
            </div>
            <div>
              @if($jurnal->status_approval === 'disetujui')
                <span class="badge badge-success px-3 py-1" style="font-size: 0.85rem;">
                  <i class="fas fa-check-circle mr-1"></i> Disetujui Kaprog
                </span>
              @elseif($jurnal->status_approval === 'ditolak')
                <span class="badge badge-danger px-3 py-1" style="font-size: 0.85rem;">
                  <i class="fas fa-times-circle mr-1"></i> Ditolak
                </span>
              @else
                <span class="badge badge-warning text-dark px-3 py-1" style="font-size: 0.85rem;">
                  <i class="fas fa-hourglass-half mr-1"></i> Menunggu Persetujuan
                </span>
              @endif
            </div>
          </div>

          <div class="card-body p-4">
            <div class="row mb-3 pb-3 border-bottom">
              <div class="col-sm-6 mb-2 mb-sm-0">
                <span class="text-muted d-block small">Tanggal & Waktu</span>
                <span class="font-weight-bold text-dark">
                  {{ $jurnal->tanggal->translatedFormat('l, d F Y') }} ({{ substr($jurnal->jadwalPraktik->jam_mulai, 0, 5) }} - {{ substr($jurnal->jadwalPraktik->jam_selesai, 0, 5) }})
                </span>
              </div>
              <div class="col-sm-6">
                <span class="text-muted d-block small">Laboratorium</span>
                <span class="font-weight-bold text-dark">
                  {{ $jurnal->jadwalPraktik->lab->nama_lab ?? '-' }}
                </span>
              </div>
            </div>

            <div class="row mb-3 pb-3 border-bottom">
              <div class="col-sm-6 mb-2 mb-sm-0">
                <span class="text-muted d-block small">Guru Pengajar / Instruktur</span>
                <span class="font-weight-bold text-dark">
                  {{ $jurnal->jadwalPraktik->guru->nama_guru ?? '-' }}
                </span>
              </div>
              <div class="col-sm-6">
                <span class="text-muted d-block small">Jumlah Siswa Hadir</span>
                <span class="badge badge-info px-2 py-1 font-weight-bold" style="font-size: 0.9rem;">
                  {{ $jurnal->jumlah_hadir }} Siswa Hadir
                </span>
              </div>
            </div>

            <div class="mb-3 pb-3 border-bottom">
              <span class="text-muted d-block small mb-1">Materi Praktik / Topik Jobsheet</span>
              <p class="text-dark font-weight-500 mb-0" style="white-space: pre-line;">{{ $jurnal->materi }}</p>
            </div>

            <div class="mb-3 pb-3 border-bottom">
              <span class="text-muted d-block small mb-1">Peralatan & Software yang Digunakan</span>
              <p class="text-dark mb-0">{{ $jurnal->alat_dipakai ?: '-' }}</p>
            </div>

            <div class="row mb-3 pb-3 border-bottom">
              <div class="col-md-6 mb-2 mb-md-0">
                <span class="text-muted d-block small mb-1">Kendala Pelaksanaan</span>
                <p class="text-dark mb-0">{{ $jurnal->kendala ?: 'Tidak ada kendala' }}</p>
              </div>
              <div class="col-md-6">
                <span class="text-muted d-block small mb-1">Catatan Tambahan</span>
                <p class="text-dark mb-0">{{ $jurnal->catatan ?: '-' }}</p>
              </div>
            </div>

            @if($jurnal->foto_praktik)
              <div class="mb-2">
                <span class="text-muted d-block small mb-2">Dokumentasi Foto Praktik</span>
                <a href="{{ asset('storage/' . $jurnal->foto_praktik) }}" target="_blank">
                  <img src="{{ asset('storage/' . $jurnal->foto_praktik) }}" alt="Foto Praktik" class="img-fluid rounded border shadow-sm" style="max-height: 280px;">
                </a>
              </div>
            @endif
          </div>

          <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
            <a href="/admin/kejuruan/jurnal-praktik" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
              <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            @if($user->hasRole('admin') || ($jurnal->status_approval !== 'disetujui' && $jurnal->jadwalPraktik->id_guru == $user->id_guru))
              <a href="/admin/kejuruan/jurnal-praktik/{{ $jurnal->id_jurnal_praktik }}/edit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-edit mr-1"></i> Ubah Jurnal
              </a>
            @endif
          </div>
        </div>
      </div>

      <div class="col-12 col-lg-4">
        <!-- Status & Catatan Persetujuan Kaprog -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 14px; background-color: #f8fafc;">
          <div class="card-body p-3">
            <h6 class="font-weight-bold text-dark mb-2">
              <i class="fas fa-clipboard-check text-primary mr-1"></i> Status Persetujuan Kaprog
            </h6>

            @if($jurnal->status_approval === 'disetujui')
              <div class="alert alert-success py-2 px-3 small mb-2">
                <i class="fas fa-check-circle mr-1"></i> Jurnal telah diverifikasi dan disetujui.
              </div>
              <div class="small text-muted mb-1">
                <strong>Disetujui oleh:</strong> {{ $jurnal->approver->nama_guru ?? 'Kaprog' }}
              </div>
              <div class="small text-muted mb-0">
                <strong>Tanggal:</strong> {{ $jurnal->approved_at ? $jurnal->approved_at->format('d/m/Y H:i') : '-' }}
              </div>
            @elseif($jurnal->status_approval === 'ditolak')
              <div class="alert alert-danger py-2 px-3 small mb-2">
                <i class="fas fa-exclamation-circle mr-1"></i> Jurnal ditolak.
              </div>
              @if($jurnal->catatan_kaprog)
                <div class="p-2 rounded bg-white border small text-dark mb-2">
                  <strong>Catatan Kaprog:</strong><br>
                  {{ $jurnal->catatan_kaprog }}
                </div>
              @endif
              <div class="small text-muted">
                Silakan lakukan revisi data jurnal dan simpan kembali.
              </div>
            @else
              <div class="alert alert-warning py-2 px-3 small mb-0 text-dark">
                <i class="fas fa-hourglass-half mr-1"></i> Sedang menunggu verifikasi dari Kepala Program Keahlian.
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
