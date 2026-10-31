@extends($layout)

@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-book-open"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Detail Modul Praktik</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Rincian jobsheet, prosedur kerja, rubrik penilaian, dan riwayat revisi</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/modul-praktik" class="text-primary font-weight-500">Modul Praktik</a></li>
          <li class="breadcrumb-item active">{{ $modul->kode_modul }}</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">

    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="row">
      <!-- Kolom Kiri: Rincian Modul -->
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap">
            <div>
              <span class="badge badge-light border text-primary font-weight-bold px-2 py-1 mr-1" style="font-size: 0.8rem;">
                {{ $modul->kode_modul }}
              </span>
              <span class="badge badge-secondary font-weight-bold px-2 py-1" style="font-size: 0.78rem;">
                Versi {{ $modul->versi }}
              </span>
              @if($modul->status === 'terbit')
                <span class="badge badge-success font-weight-bold ml-1 px-2 py-1">
                  <i class="fas fa-check-circle mr-1"></i> Terbit
                </span>
              @elseif($modul->status === 'draft')
                <span class="badge badge-warning text-dark font-weight-bold ml-1 px-2 py-1">
                  <i class="fas fa-pencil-alt mr-1"></i> Draft
                </span>
              @else
                <span class="badge badge-secondary font-weight-bold ml-1 px-2 py-1">
                  Arsip
                </span>
              @endif
            </div>

            <div class="mt-2 mt-sm-0">
              <a href="/admin/kejuruan/modul-praktik" class="btn btn-outline-secondary btn-sm px-3 mr-1" style="border-radius: 6px;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              @if($user->hasRole('admin') || $user->hasRole('kaprog') || $modul->id_guru == $user->id_guru)
                <a href="/admin/kejuruan/modul-praktik/{{ $modul->id_modul_praktik }}/edit" class="btn btn-primary btn-sm px-3 shadow-sm" style="border-radius: 6px; font-weight: 600;">
                  <i class="fas fa-edit mr-1"></i> Edit / Revisi Modul
                </a>
              @endif
            </div>
          </div>

          <div class="card-body p-4">
            <h3 class="font-weight-bold text-dark mb-3" style="font-size: 1.35rem; line-height: 1.3;">
              {{ $modul->judul }}
            </h3>

            <div class="row mb-4 pb-3 border-bottom">
              <div class="col-sm-7 mb-2 mb-sm-0">
                <span class="text-muted d-block small mb-1">Target Capaian Pembelajaran / Tujuan Pembelajaran (CP / TP):</span>
                @php
                  $targets = $modul->kompetensis && $modul->kompetensis->count() > 0 
                    ? $modul->kompetensis 
                    : ($modul->kompetensi ? collect([$modul->kompetensi]) : collect());
                @endphp
                @if($targets->count() > 0)
                  <div class="d-flex flex-column" style="gap: 6px;">
                    @foreach($targets as $index => $k)
                      <div class="d-inline-flex align-items-center p-2 rounded {{ $index === 0 ? 'bg-primary-light border-primary' : 'bg-light border' }}" style="border: 1px solid {{ $index === 0 ? '#bbf7d0' : '#e2e8f0' }}; font-size: 0.85rem;">
                        <span class="badge {{ $index === 0 ? 'badge-primary' : 'badge-secondary' }} mr-2 px-2 py-1" style="font-size: 0.75rem;">
                          {{ $k->kode_kompetensi }}
                        </span>
                        <span class="font-weight-600 text-dark flex-grow-1">
                          {{ $k->nama_kompetensi }}
                        </span>
                        @if($k->jurusan)
                          <span class="badge badge-info ml-2">{{ $k->jurusan->kode_jurusan ?? $k->jurusan->nama_jurusan }}</span>
                        @endif
                        @if($index === 0 && $targets->count() > 1)
                          <span class="badge badge-success ml-1" style="font-size: 0.68rem;">Utama</span>
                        @endif
                      </div>
                    @endforeach
                  </div>
                @else
                  <span class="text-muted">-</span>
                @endif
              </div>
              <div class="col-sm-5 text-sm-right mt-3 mt-sm-0">
                <span class="text-muted d-block small">Guru Pengampu / Pembuat:</span>
                <span class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                  <i class="fas fa-user-tie text-secondary mr-1"></i> {{ $modul->guru->nama_guru ?? 'Guru Pengampu' }}
                </span>
                <div class="mt-2 text-muted small">
                  <span><i class="fas fa-calendar-alt mr-1"></i> Dibuat: {{ \Carbon\Carbon::parse($modul->created_at)->translatedFormat('d F Y') }}</span>
                </div>
              </div>
            </div>

            <!-- Bagian 1: Tujuan -->
            <div class="mb-4">
              <h6 class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                <i class="fas fa-bullseye text-primary mr-2"></i> Tujuan Pembelajaran Praktik
              </h6>
              <div class="p-3 bg-light rounded text-dark" style="font-size: 0.9rem; line-height: 1.6; white-space: pre-line;">
                {{ $modul->tujuan }}
              </div>
            </div>

            <!-- Bagian 2: Alat & Bahan -->
            <div class="mb-4">
              <h6 class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                <i class="fas fa-tools text-primary mr-2"></i> Alat dan Bahan yang Diperlukan
              </h6>
              <div class="p-3 bg-light rounded text-dark" style="font-size: 0.9rem; line-height: 1.6; white-space: pre-line;">
                {{ $modul->alat_bahan }}
              </div>
            </div>

            <!-- Bagian 3: Langkah Kerja -->
            <div class="mb-4">
              <h6 class="font-weight-bold text-dark mb-2" style="font-size: 0.95rem;">
                <i class="fas fa-tasks text-primary mr-2"></i> Prosedur & Langkah Kerja Praktikum
              </h6>
              <div class="p-3 rounded border text-dark" style="font-size: 0.92rem; line-height: 1.7; background-color: #fbfbfb;">
                {!! $modul->langkah_kerja !!}
              </div>
            </div>

            <!-- Bagian 4: Lampiran Dokumen Jobsheet -->
            @if($modul->file_lembar_kerja)
              <div class="mb-2 p-3 border rounded d-flex align-items-center justify-content-between flex-wrap" style="background-color: #f0f7ff; border-color: #cce5ff !important;">
                <div class="d-flex align-items-center mb-2 mb-sm-0">
                  <div class="bg-primary text-white p-3 rounded mr-3">
                    <i class="fas fa-file-pdf" style="font-size: 1.5rem;"></i>
                  </div>
                  <div>
                    <h6 class="font-weight-bold text-dark mb-0">Lembar Kerja / Jobsheet Terlampir</h6>
                    <small class="text-muted">{{ $modul->file_lembar_kerja }}</small>
                  </div>
                </div>
                <a href="{{ asset('storage/modul_praktik/' . $modul->file_lembar_kerja) }}" target="_blank" class="btn btn-primary btn-sm px-3 shadow-sm" style="border-radius: 6px; font-weight: 600;">
                  <i class="fas fa-download mr-1"></i> Unduh / Buka Dokumen
                </a>
              </div>
            @endif

          </div>
        </div>
      </div>

      <!-- Kolom Kanan: Rubrik Penilaian & Riwayat Versi -->
      <div class="col-lg-4">

        <!-- Card Rubrik Penilaian -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 0.98rem;">
              <i class="fas fa-list-check text-primary mr-2"></i> Rubrik Penilaian
            </h5>
            <span class="badge badge-info font-weight-bold px-2 py-1">
              {{ $modul->rubriks->count() }} Aspek
            </span>
          </div>
          <div class="card-body p-3">
            @forelse($modul->rubriks as $r)
              <div class="d-flex justify-content-between align-items-center p-2 mb-2 rounded border bg-light">
                <div>
                  <span class="font-weight-bold text-dark d-block" style="font-size: 0.88rem;">
                    {{ $r->nama_indikator }}
                  </span>
                  @if($r->deskripsi)
                    <small class="text-muted d-block">{{ $r->deskripsi }}</small>
                  @endif
                </div>
                <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 0.8rem;">
                  {{ floatval($r->bobot) }}%
                </span>
              </div>
            @empty
              <p class="text-muted small text-center my-3">Belum ada rubrik khusus. Penilaian akan menggunakan indikator penilaian tunggal standar.</p>
            @endforelse
          </div>
        </div>

        <!-- Card Riwayat Versi -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
          <div class="card-header bg-white py-3 border-bottom">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 0.98rem;">
              <i class="fas fa-history text-primary mr-2"></i> Riwayat Revisi Modul
            </h5>
          </div>
          <div class="card-body p-3">
            <div class="timeline-wrapper pl-2" style="border-left: 2px solid #e2e8f0; margin-left: 8px;">
              @forelse($modul->versis as $v)
                <div class="position-relative mb-3 pl-3">
                  <div class="position-absolute bg-primary rounded-circle" style="width: 10px; height: 10px; left: -14px; top: 5px;"></div>
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="badge badge-dark font-weight-bold" style="font-size: 0.75rem;">
                      v{{ $v->versi }}
                    </span>
                    <small class="text-muted" style="font-size: 0.75rem;">
                      {{ $v->created_at ? $v->created_at->format('d M Y') : '-' }}
                    </small>
                  </div>
                  <p class="text-dark small mb-1 font-weight-500">
                    {{ $v->catatan_revisi ?: 'Revisi modul versi ' . $v->versi }}
                  </p>
                  <small class="text-muted d-block">
                    Oleh: {{ $v->pengubah->nama_guru ?? 'Guru' }}
                  </small>
                </div>
              @empty
                <p class="text-muted small text-center my-2">Versi perdana v{{ $modul->versi }}.</p>
              @endforelse
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</div>
@endsection
