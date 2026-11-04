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
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; overflow: hidden;">
      <div class="card-body p-4">
        <!-- Baris 1: Judul Utama, Badge Status & Tombol Aksi Kanan -->
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between pb-3 border-bottom">
          <div class="mb-3 mb-lg-0">
            <div class="d-flex align-items-center flex-wrap mb-1" style="gap: 8px;">
              @if($penilaian->modul)
                <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 0.8rem; border-radius: 6px;">
                  <i class="fas fa-book-open mr-1"></i> Modul Praktik
                </span>
              @else
                <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" style="font-size: 0.8rem; border-radius: 6px;">
                  <i class="fas fa-bolt mr-1"></i> Jalur Cepat (Langsung ATP)
                </span>
              @endif

              @if($penilaian->status === 'final')
                <span class="badge badge-success px-2 py-1 font-weight-bold" style="font-size: 0.8rem; border-radius: 6px;">
                  <i class="fas fa-check-circle mr-1"></i> Nilai Resmi Difinalkan
                </span>
              @else
                <span class="badge badge-secondary px-2 py-1 font-weight-bold" style="font-size: 0.8rem; border-radius: 6px;">
                  <i class="fas fa-pencil-alt mr-1"></i> Masih Draf Guru
                </span>
              @endif
            </div>

            <h4 class="font-weight-bold text-dark mb-0 mt-2" style="font-size: 1.35rem; line-height: 1.3;">
              @if($penilaian->modul)
                {{ $penilaian->modul->judul }}
              @else
                @if($penilaian->kompetensis && $penilaian->kompetensis->count() > 1)
                  Penilaian {{ $penilaian->kompetensis->count() }} Butir Tujuan Pembelajaran (ATP)
                @else
                  {{ $penilaian->kompetensi->nama_kompetensi ?? 'Penilaian Praktik' }}
                @endif
              @endif
            </h4>
          </div>

          <!-- Tombol Aksi di Kanan Atas -->
          <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
            <a href="/admin/kejuruan/penilaian-praktik" class="btn btn-outline-secondary btn-sm px-3 shadow-none" style="border-radius: 8px; font-weight: 600;">
              <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
            <a href="/admin/kejuruan/penilaian-praktik/{{ $penilaian->id_penilaian_praktik }}/input" class="btn btn-primary btn-sm px-3 shadow-sm" style="border-radius: 8px; font-weight: 600;">
              <i class="fas fa-edit mr-1"></i> Edit Nilai Matriks
            </a>
            @if($penilaian->status !== 'final')
              <form action="/admin/kejuruan/penilaian-praktik/{{ $penilaian->id_penilaian_praktik }}/finalkan" method="POST" class="d-inline" onsubmit="return confirmSwal(event, 'Finalkan nilai sekarang? Nilai akan resmi tercatat ke Portofolio Siswa dan tampil ke portal siswa.')">
                @csrf
                <button type="submit" class="btn btn-success btn-sm px-3 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                  <i class="fas fa-check-double mr-1"></i> Finalkan Sekarang
                </button>
              </form>
            @endif
          </div>
        </div>

        <!-- Baris 2: Butir ATP yang Dinilai (Jika Multi / Single) -->
        @if($penilaian->kompetensis && $penilaian->kompetensis->count() > 1)
          <div class="py-2 border-bottom">
            <div class="d-flex align-items-center justify-content-between mb-2">
              <span class="font-weight-bold text-dark small">
                <i class="fas fa-tasks text-success mr-1"></i> Tujuan Pembelajaran (ATP) Dinilai: 
                <span class="badge badge-light border text-primary ml-1">{{ $penilaian->kompetensis->count() }} Butir</span>
              </span>
              @if($penilaian->kompetensis->count() > 4)
                <span class="text-muted small"><i class="fas fa-arrows-alt-v mr-1"></i>Dapat di-scroll</span>
              @endif
            </div>
            <div class="row g-2" style="max-height: 110px; overflow-y: auto; padding-right: 4px;">
              @foreach($penilaian->kompetensis as $kp)
                <div class="col-md-6 mb-2">
                  <div class="px-2 py-1 border rounded bg-light d-flex align-items-center justify-content-between h-100" style="font-size: 0.82rem; border-color: #e2e8f0 !important;">
                    <div class="d-flex align-items-center text-truncate mr-2">
                      <span class="badge badge-primary font-weight-bold mr-2" style="font-size: 0.75rem;">{{ $kp->kode_kompetensi }}</span>
                      <span class="text-dark font-weight-500 text-truncate" title="{{ $kp->nama_kompetensi }}">{{ $kp->nama_kompetensi }}</span>
                    </div>
                    <span class="badge badge-light border text-secondary font-weight-bold flex-shrink-0" style="font-size: 0.75rem;">KKTP: {{ floatval($kp->kkm ?? 75) }}</span>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @elseif($penilaian->kompetensi)
          <div class="py-3 border-bottom">
            <div class="d-flex flex-wrap align-items-center" style="gap: 20px; font-size: 0.88rem;">
              @if($penilaian->kompetensi->capaianPembelajaran)
                <div>
                  <span class="text-muted"><i class="fas fa-layer-group text-primary mr-1"></i> Elemen CP:</span>
                  <strong class="text-dark ml-1">[{{ $penilaian->kompetensi->capaianPembelajaran->kode_elemen }}] {{ $penilaian->kompetensi->capaianPembelajaran->nama_elemen }}</strong>
                </div>
              @endif
              <div>
                <span class="text-muted"><i class="fas fa-tasks text-success mr-1"></i> Butir TP:</span>
                <strong class="text-dark ml-1">[{{ $penilaian->kompetensi->kode_kompetensi ?? '-' }}] {{ $penilaian->kompetensi->nama_kompetensi ?? '-' }}</strong>
              </div>
            </div>
          </div>
        @endif

        <!-- Baris 3: Informasi Ringkas (Metadata Strip) -->
        <div class="pt-3">
          <div class="row text-center text-md-left align-items-center" style="row-gap: 12px;">
            <div class="col-6 col-md-3 border-right">
              <span class="text-muted small d-block mb-1"><i class="fas fa-chalkboard text-primary mr-1"></i> Kelas</span>
              <strong class="text-dark" style="font-size: 0.95rem;">{{ $penilaian->kelas->nama_kelas ?? '-' }}</strong>
            </div>
            <div class="col-6 col-md-3 border-right">
              <span class="text-muted small d-block mb-1"><i class="fas fa-calendar-day text-primary mr-1"></i> Tanggal Penilaian</span>
              <strong class="text-dark" style="font-size: 0.95rem;">{{ \Carbon\Carbon::parse($penilaian->tanggal_penilaian)->translatedFormat('d F Y') }}</strong>
            </div>
            <div class="col-6 col-md-3 border-right">
              <span class="text-muted small d-block mb-1"><i class="fas fa-user-tie text-primary mr-1"></i> Guru Penilai</span>
              <strong class="text-dark" style="font-size: 0.95rem;">{{ $penilaian->guru->nama_guru ?? '-' }}</strong>
            </div>
            <div class="col-6 col-md-3">
              <span class="text-muted small d-block mb-1"><i class="fas fa-award text-warning mr-1"></i> Standar KKTP (Rata-rata)</span>
              <span class="badge badge-primary px-3 py-1 font-weight-bold" style="font-size: 0.92rem;">
                {{ floatval($penilaian->kkm_saat_penilaian) }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel Rekapitulasi Nilai Siswa -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
      <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
        <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1rem;">
          <i class="fas fa-user-graduate text-primary mr-2"></i> Rekapitulasi Nilai Siswa ({{ $penilaian->nilaiSiswas->count() }} Siswa)
        </h5>
        @if($penilaian->status === 'final')
          <span class="badge badge-success px-2 py-1 font-weight-bold">
            <i class="fas fa-check-circle mr-1"></i> Nilai Resmi Difinalkan
          </span>
        @else
          <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold">
            <i class="fas fa-pencil-alt mr-1"></i> Masih Draf (Belum Rilis ke Siswa)
          </span>
        @endif
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" id="tableRekapNilai" style="font-size: 0.9rem;">
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
              @foreach($penilaian->nilaiSiswas as $ns)
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
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>

  </div>
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function() {
    if ($.fn.DataTable && !$.fn.DataTable.isDataTable('#tableRekapNilai')) {
      $('#tableRekapNilai').DataTable({
        paging: true,
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua Siswa"]],
        ordering: true,
        order: [[0, 'asc']],
        searching: true,
        info: true,
        autoWidth: false,
        responsive: false,
        language: {
          search: "Cari Siswa:",
          searchPlaceholder: "Ketik nama / NIS...",
          lengthMenu: "Tampilkan _MENU_ data",
          info: "Menampilkan <strong>_START_ - _END_</strong> dari <strong>_TOTAL_</strong> siswa",
          zeroRecords: "Tidak ada data siswa yang cocok",
          emptyTable: "Belum ada siswa terdaftar pada sesi ini",
          paginate: {
            previous: "‹",
            next: "›"
          }
        }
      });
    }
  });
</script>
@endpush
