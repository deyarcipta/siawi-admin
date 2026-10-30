@extends($layout)
@php
  $fromLaporan = request('from') === 'laporan-praktik';
  $backUrl = $fromLaporan ? '/admin/kejuruan/laporan-praktik' : '/admin/kejuruan/jurnal-praktik';
  $parentTitle = $fromLaporan ? 'Laporan Praktik' : 'Jurnal Praktik';
@endphp
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
          <li class="breadcrumb-item"><a href="{{ $backUrl }}" class="text-primary font-weight-500">{{ $parentTitle }}</a></li>
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
              <span class="text-muted d-block small mb-2">Materi Praktik / Topik Jobsheet</span>
              <div class="text-dark font-weight-500 mb-0 content-materi" style="line-height: 1.6;">
                {!! $jurnal->materi !!}
              </div>
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
            <div class="mt-4 pt-3 border-top">
              <div class="d-flex align-items-center justify-content-between mb-3">
                <h6 class="font-weight-bold text-dark mb-0">
                  <i class="fas fa-user-check text-primary mr-1"></i> Rekapitulasi Presensi Praktik Siswa
                </h6>
                <span class="badge badge-light border text-muted">Total: {{ $jurnal->presensis->count() }} Siswa</span>
              </div>

              @if($jurnal->presensis->count() > 0)
                <div class="table-responsive rounded border" style="max-height: 320px;">
                  <table class="table table-sm table-hover mb-0" style="font-size: 0.85rem;">
                    <thead class="bg-light text-muted">
                      <tr>
                        <th style="width: 40px;" class="text-center">No</th>
                        <th>Nama Siswa</th>
                        <th style="width: 140px;" class="text-center">Status</th>
                        <th>Keterangan</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach($jurnal->presensis as $idx => $p)
                        <tr>
                          <td class="text-center align-middle">{{ $idx + 1 }}</td>
                          <td class="align-middle">
                            <span class="font-weight-bold text-dark">{{ $p->siswa->nama_siswa ?? 'Siswa' }}</span>
                            <small class="text-muted d-block">NISN: {{ $p->siswa->nisn ?? ($p->siswa->nis ?? '-') }}</small>
                          </td>
                          <td class="text-center align-middle">
                            @if($p->status === 'hadir')
                              <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Hadir</span>
                            @elseif($p->status === 'sakit')
                              <span class="badge badge-warning text-dark px-2 py-1"><i class="fas fa-ambulance mr-1"></i> Sakit</span>
                            @elseif($p->status === 'izin')
                              <span class="badge badge-info px-2 py-1"><i class="fas fa-info-circle mr-1"></i> Izin</span>
                            @else
                              <span class="badge badge-danger px-2 py-1"><i class="fas fa-times mr-1"></i> Alpa</span>
                            @endif
                          </td>
                          <td class="align-middle text-muted">{{ $p->catatan ?: '-' }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @else
                <div class="alert alert-light border text-muted py-2 small mb-0">
                  <i class="fas fa-info-circle mr-1"></i> Belum ada detail presensi per siswa yang tercatat pada jurnal ini.
                </div>
              @endif
            </div>
          </div>

          <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
            <a href="{{ $backUrl }}" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
              <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            @if($user->hasRole('admin') || ($jurnal->status_approval !== 'disetujui' && $jurnal->jadwalPraktik->id_guru == $user->id_guru))
              <a href="/admin/kejuruan/jurnal-praktik/{{ $jurnal->id_jurnal_praktik }}/edit{{ $fromLaporan ? '?from=laporan-praktik' : '' }}" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
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
              <div class="small text-muted mb-2">
                <strong>Tanggal:</strong> {{ $jurnal->approved_at ? $jurnal->approved_at->format('d/m/Y H:i') : '-' }}
              </div>
              @if($jurnal->catatan_kaprog)
                <div class="p-2 rounded bg-white border small text-dark mb-2">
                  <strong>Catatan Kaprog:</strong><br>
                  {{ $jurnal->catatan_kaprog }}
                </div>
              @endif
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
              <div class="small text-muted mb-2">
                Silakan lakukan revisi data jurnal dan simpan kembali.
              </div>
            @else
              <div class="alert alert-warning py-2 px-3 small mb-2 text-dark">
                <i class="fas fa-hourglass-half mr-1"></i> Sedang menunggu verifikasi dari Kepala Program Keahlian.
              </div>
            @endif

            @if($user->hasAnyRole(['admin', 'kaprog']))
              <hr class="my-3">
              <h6 class="font-weight-bold text-dark mb-2" style="font-size: 0.88rem;">
                <i class="fas fa-stamp text-success mr-1"></i> Tindakan Verifikasi
              </h6>
              <form action="/admin/kejuruan/jurnal-praktik/{{ $jurnal->id_jurnal_praktik }}/approve{{ $fromLaporan ? '?from=laporan-praktik' : '' }}" method="POST">
                @csrf
                @if($fromLaporan)
                  <input type="hidden" name="from" value="laporan-praktik">
                @endif
                <div class="form-group mb-2">
                  <div class="d-flex align-items-center" style="gap: 15px;">
                    <div class="custom-control custom-radio">
                      <input type="radio" id="showStatusSetuju" name="status" value="disetujui" class="custom-control-input" {{ $jurnal->status_approval === 'disetujui' || $jurnal->status_approval === 'menunggu' ? 'checked' : '' }}>
                      <label class="custom-control-label font-weight-bold text-success" for="showStatusSetuju" style="font-size: 0.85rem;">
                        <i class="fas fa-check mr-1"></i> Disetujui
                      </label>
                    </div>
                    <div class="custom-control custom-radio">
                      <input type="radio" id="showStatusTolak" name="status" value="ditolak" class="custom-control-input" {{ $jurnal->status_approval === 'ditolak' ? 'checked' : '' }}>
                      <label class="custom-control-label font-weight-bold text-danger" for="showStatusTolak" style="font-size: 0.85rem;">
                        <i class="fas fa-times mr-1"></i> Ditolak
                      </label>
                    </div>
                  </div>
                </div>

                <div class="form-group mb-3">
                  <label class="text-secondary small font-weight-bold mb-1">Catatan Kaprog</label>
                  <textarea name="catatan_kaprog" class="form-control form-control-sm" rows="3" placeholder="Tuliskan arahan atau alasan jika ditolak..." style="border-radius: 8px;">{{ old('catatan_kaprog', $jurnal->catatan_kaprog) }}</textarea>
                </div>

                <button type="submit" class="btn btn-sm btn-success btn-block font-weight-bold shadow-sm" style="border-radius: 8px;">
                  <i class="fas fa-check-double mr-1"></i> Simpan Status Verifikasi
                </button>
              </form>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
