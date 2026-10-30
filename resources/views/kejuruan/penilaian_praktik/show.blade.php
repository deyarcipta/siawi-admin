@extends($layout)

@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-poll"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Hasil Penilaian Praktik</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Rekapitulasi capaian keterampilan siswa kelas {{ $penilaian->kelas->nama_kelas ?? '-' }}</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/penilaian-praktik" class="text-primary font-weight-500">Penilaian Praktik</a></li>
          <li class="breadcrumb-item active">Hasil</li>
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

    <!-- Kartu Statistik Capaian Kelas -->
    <div class="row mb-3">
      <div class="col-6 col-md-3 mb-2">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
          <div class="card-body p-3 d-flex align-items-center">
            <div class="bg-light text-primary rounded-circle p-3 mr-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
              <i class="fas fa-users" style="font-size: 1.2rem;"></i>
            </div>
            <div>
              <span class="text-muted small d-block">Total Siswa</span>
              <h4 class="font-weight-bold text-dark mb-0">{{ $totalSiswa }}</h4>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3 mb-2">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
          <div class="card-body p-3 d-flex align-items-center">
            <div class="bg-light text-success rounded-circle p-3 mr-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
              <i class="fas fa-check-circle" style="font-size: 1.2rem;"></i>
            </div>
            <div>
              <span class="text-muted small d-block">Kompeten (&ge; {{ floatval($penilaian->kkm_saat_penilaian) }})</span>
              <h4 class="font-weight-bold text-success mb-0">{{ $kompetenCount }}</h4>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3 mb-2">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
          <div class="card-body p-3 d-flex align-items-center">
            <div class="bg-light text-danger rounded-circle p-3 mr-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
              <i class="fas fa-times-circle" style="font-size: 1.2rem;"></i>
            </div>
            <div>
              <span class="text-muted small d-block">Belum Kompeten</span>
              <h4 class="font-weight-bold text-danger mb-0">{{ $belumKompetenCount }}</h4>
            </div>
          </div>
        </div>
      </div>

      <div class="col-6 col-md-3 mb-2">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
          <div class="card-body p-3 d-flex align-items-center">
            <div class="bg-light text-info rounded-circle p-3 mr-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
              <i class="fas fa-calculator" style="font-size: 1.2rem;"></i>
            </div>
            <div>
              <span class="text-muted small d-block">Rata-Rata Kelas</span>
              <h4 class="font-weight-bold text-dark mb-0">{{ $rataRata }}</h4>
            </div>
          </div>
        </div>
      </div>
    </div>

    @if(isset($missingCount) && $missingCount > 0)
      <div class="alert alert-warning border-0 shadow-sm mb-3 d-flex align-items-center justify-content-between flex-wrap" style="border-radius: 10px; background-color: #fff9db; color: #856404; border-left: 4px solid #f59f00 !important;">
        <div class="d-flex align-items-center mb-2 mb-md-0">
          <i class="fas fa-exclamation-triangle mr-3 text-warning" style="font-size: 1.35rem;"></i>
          <div>
            <strong style="font-size: 0.95rem;">Terdapat {{ $missingCount }} siswa di kelas {{ $penilaian->kelas->nama_kelas ?? '-' }} yang belum terdaftar pada sesi penilaian ini.</strong>
            <div class="small text-muted mt-0">Anda dapat menyinkronkan atau menambahkan siswa tersebut melalui halaman input nilai matriks.</div>
          </div>
        </div>
        <div>
          <a href="/admin/kejuruan/penilaian-praktik/{{ $penilaian->id_penilaian_praktik }}/input" class="btn btn-warning btn-sm font-weight-bold px-3 shadow-sm text-dark" style="border-radius: 6px;">
            <i class="fas fa-edit mr-1"></i> Buka Matriks & Sinkronkan
          </a>
        </div>
      </div>
    @endif

    <!-- Informasi Detail & Tombol Aksi -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <div class="d-flex align-items-center justify-content-between flex-wrap">
          <div class="mb-2 mb-md-0">
            <h5 class="font-weight-bold text-dark mb-1">
              {{ $penilaian->modul->judul ?? 'Modul Praktik' }}
            </h5>
            <div class="text-muted small">
              <span class="mr-3"><i class="fas fa-chalkboard mr-1 text-primary"></i> Kelas: <strong>{{ $penilaian->kelas->nama_kelas ?? '-' }}</strong></span>
              <span class="mr-3"><i class="fas fa-calendar-day mr-1 text-primary"></i> Tanggal: <strong>{{ \Carbon\Carbon::parse($penilaian->tanggal_penilaian)->translatedFormat('d F Y') }}</strong></span>
              <span class="mr-3"><i class="fas fa-user-tie mr-1 text-primary"></i> Guru: <strong>{{ $penilaian->guru->nama_guru ?? '-' }}</strong></span>
              <span><i class="fas fa-award mr-1 text-primary"></i> KKTP / KKM: <strong>{{ floatval($penilaian->kkm_saat_penilaian) }}</strong></span>
            </div>
          </div>

          <div class="d-flex align-items-center">
            <a href="/admin/kejuruan/penilaian-praktik" class="btn btn-outline-secondary btn-sm px-3 mr-2" style="border-radius: 6px;">
              <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <a href="/admin/kejuruan/penilaian-praktik/{{ $penilaian->id_penilaian_praktik }}/input" class="btn btn-primary btn-sm px-3 mr-2 shadow-sm" style="border-radius: 6px; font-weight: 600;">
              <i class="fas fa-edit mr-1"></i> Edit Nilai Matriks
            </a>
            @if($penilaian->status !== 'final')
              <form action="/admin/kejuruan/penilaian-praktik/{{ $penilaian->id_penilaian_praktik }}/finalkan" method="POST" class="d-inline" onsubmit="return confirm('Finalkan nilai sekarang? Nilai akan resmi tercatat ke Portofolio Siswa dan tampil ke portal siswa.')">
                @csrf
                <button type="submit" class="btn btn-success btn-sm px-3 shadow-sm" style="border-radius: 6px; font-weight: 600;">
                  <i class="fas fa-check-double mr-1"></i> Finalkan Sekarang
                </button>
              </form>
            @endif
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel Rekapitulasi Nilai Siswa -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
      <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
        <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1rem;">
          <i class="fas fa-user-graduate text-primary mr-2"></i> Daftar Nilai Hasil Praktikum Siswa
        </h5>
        @if($penilaian->status === 'final')
          <span class="badge badge-success px-2 py-1 font-weight-bold">
            <i class="fas fa-check-circle mr-1"></i> Nilai Resmi Difinalkan
          </span>
        @else
          <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold">
            <i class="fas fa-pencil-alt mr-1"></i> Masih Draft (Belum Rilis ke Siswa)
          </span>
        @endif
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
            <thead class="bg-light">
              <tr>
                <th style="width: 45px; text-align: center;">No</th>
                <th style="width: 120px;">NIS/NISN</th>
                <th>Nama Siswa</th>
                @foreach($rubriks as $r)
                  <th style="text-align: center; font-size: 0.8rem;">
                    {{ $r->nama_indikator }}
                    <span class="d-block text-muted small">({{ floatval($r->bobot) }}%)</span>
                  </th>
                @endforeach
                <th style="width: 100px; text-align: center;">Nilai Awal</th>
                <th style="width: 100px; text-align: center;">Remedial</th>
                <th style="width: 100px; text-align: center; background-color: #f1f5f9;">Nilai Akhir</th>
                <th style="width: 130px; text-align: center;">Status</th>
                <th>Catatan Guru</th>
              </tr>
            </thead>
            <tbody>
              @forelse($penilaian->nilaiSiswas as $ns)
                @php
                  $isKompeten = ($ns->status_kompetensi === 'kompeten');
                  $indikatorVals = $ns->nilai_per_indikator ?? [];
                @endphp
                <tr>
                  <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                  <td>{{ $ns->siswa->nis ?? $ns->siswa->nisn ?? '-' }}</td>
                  <td class="font-weight-600 text-dark">
                    {{ $ns->siswa->nama_siswa ?? 'Siswa' }}
                  </td>
                  @foreach($rubriks as $r)
                    <td class="text-center">
                      {{ isset($indikatorVals[$r->id_rubrik]) ? floatval($indikatorVals[$r->id_rubrik]) : ($rubriks->count() === 1 ? floatval($ns->nilai_awal) : '-') }}
                    </td>
                  @endforeach
                  <td class="text-center font-weight-500">
                    {{ floatval($ns->nilai_awal) }}
                  </td>
                  <td class="text-center">
                    @if($ns->is_remedial && $ns->nilai_remedial !== null)
                      <span class="badge badge-info font-weight-bold px-2 py-1">
                        {{ floatval($ns->nilai_remedial) }}
                      </span>
                    @else
                      <span class="text-muted">-</span>
                    @endif
                  </td>
                  <td class="text-center font-weight-bold bg-light" style="font-size: 1rem;">
                    <span class="{{ $isKompeten ? 'text-success' : 'text-danger' }}">
                      {{ floatval($ns->nilai_akhir) }}
                    </span>
                  </td>
                  <td class="text-center">
                    <span class="badge {{ $isKompeten ? 'badge-success' : 'badge-danger' }} px-2 py-1 font-weight-bold">
                      {{ $isKompeten ? 'Kompeten' : 'Belum Kompeten' }}
                    </span>
                  </td>
                  <td class="small text-muted">
                    {{ $ns->catatan_guru ?: '-' }}
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="{{ 7 + $rubriks->count() }}" class="text-center py-4 text-muted">
                    Tidak ada siswa terdaftar pada kelas ini.
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
