@extends($layout)

@section('content')
<style>
  @media print {
    .main-sidebar, .main-header, .content-header, .no-print, .btn, .breadcrumb {
      display: none !important;
    }
    .content-wrapper {
      margin-left: 0 !important;
      padding: 0 !important;
      background: white !important;
    }
    .card {
      border: none !important;
      box-shadow: none !important;
    }
    body {
      background: white !important;
      font-size: 11pt !important;
      color: black !important;
    }
  }
</style>

<div class="content-header pb-2 no-print">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-file-invoice"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Transkrip Capaian Kejuruan</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Lembar resmi portofolio ketuntasan kompetensi praktik kejuruan siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/capaian-siswa" class="text-primary font-weight-500">Capaian Siswa</a></li>
          <li class="breadcrumb-item active">Transkrip</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">

    <!-- Action Toolbar (No Print) -->
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
      <a href="/admin/kejuruan/capaian-siswa" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 8px;">
        <i class="fas fa-arrow-left mr-1"></i> Kembali ke Buku Induk
      </a>
      <div>
        <button type="button" onclick="window.print()" class="btn btn-primary btn-sm px-3 shadow-sm font-weight-600" style="border-radius: 8px;">
          <i class="fas fa-print mr-1"></i> Cetak Lembar Transkrip
        </button>
      </div>
    </div>

    <!-- Lembar Dokumen Transkrip -->
    <div class="card border-0 shadow-sm p-4" style="border-radius: 14px; background: white;">
      
      <!-- Kop Surat Banner -->
      @if($setting && $setting->kop_surat && file_exists(public_path('storage/kop_surat/' . $setting->kop_surat)))
        <div class="text-center mb-3 pb-2 border-bottom">
          <img src="{{ asset('storage/kop_surat/' . $setting->kop_surat) }}" alt="Kop Surat" style="max-width: 100%; height: auto; max-height: 120px;">
        </div>
      @else
        <div class="text-center mb-3 pb-3 border-bottom">
          <h4 class="font-weight-bold text-dark mb-1 text-uppercase">{{ $setting->nama_sekolah ?? 'SMK WISATA INDONESIA' }}</h4>
          <p class="text-muted small mb-0">{{ $setting->alamat ?? 'Jl. Raya Wisata Indonesia No. 1' }} | Telp: {{ $setting->no_telp ?? '-' }}</p>
          <p class="text-muted small mb-0">Website: {{ $setting->website ?? '-' }} | Email: {{ $setting->email ?? '-' }}</p>
        </div>
      @endif

      <!-- Judul Dokumen -->
      <div class="text-center mb-4">
        <h5 class="font-weight-bold text-dark mb-1 text-uppercase" style="letter-spacing: 0.5px;">
          TRANSKRIP CAPAIAN KOMPETENSI KEJURUAN
        </h5>
        <span class="text-muted small">Tahun Ajaran: {{ $setting->tahun_ajaran ?? '2026/2027' }}</span>
      </div>

      <!-- Identitas Siswa -->
      <div class="row mb-4 p-3 rounded" style="background-color: #f8fafc; border: 1px solid #e2e8f0;">
        <div class="col-sm-6 mb-2 mb-sm-0">
          <table class="table table-borderless table-sm mb-0 small">
            <tr>
              <td style="width: 130px;" class="text-muted">Nama Siswa</td>
              <td style="width: 10px;">:</td>
              <td class="font-weight-bold text-dark">{{ $siswa->nama_siswa }}</td>
            </tr>
            <tr>
              <td class="text-muted">NIS / NISN</td>
              <td>:</td>
              <td class="font-weight-bold text-dark">{{ $siswa->nis ?? '-' }} / {{ $siswa->nisn ?? '-' }}</td>
            </tr>
            <tr>
              <td class="text-muted">Kelas</td>
              <td>:</td>
              <td class="font-weight-bold text-dark">{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
            </tr>
          </table>
        </div>
        <div class="col-sm-6">
          <table class="table table-borderless table-sm mb-0 small">
            <tr>
              <td style="width: 140px;" class="text-muted">Program Keahlian</td>
              <td style="width: 10px;">:</td>
              <td class="font-weight-bold text-dark">{{ $siswa->kelas->jurusan->nama_jurusan ?? ($siswa->jurusan->nama_jurusan ?? 'Kejuruan') }}</td>
            </tr>
            <tr>
              <td class="text-muted">Kompetensi Tuntas</td>
              <td>:</td>
              <td class="font-weight-bold text-success">{{ $kompetenCount }} dari {{ $totalKompetensi }} Capaian</td>
            </tr>
            <tr>
              <td class="text-muted">Persentase Kelulusan</td>
              <td>:</td>
              <td>
                <span class="badge {{ $persentase >= 75 ? 'badge-success' : 'badge-warning text-dark' }} font-weight-bold px-2 py-1">
                  {{ $persentase }}%
                </span>
              </td>
            </tr>
          </table>
        </div>
      </div>

      <!-- Tabel Daftar Capaian Kompetensi -->
      <div class="table-responsive mb-4">
        <table class="table table-bordered table-sm align-middle mb-0" style="font-size: 0.88rem;">
          <thead class="bg-light text-center">
            <tr>
              <th style="width: 40px; vertical-align: middle;">No</th>
              <th style="width: 90px; vertical-align: middle;">Kode</th>
              <th style="vertical-align: middle; text-align: left;">Capaian / Standar Kompetensi</th>
              <th style="width: 60px; vertical-align: middle;">Kelas</th>
              <th style="width: 50px; vertical-align: middle;">Sem.</th>
              <th style="width: 60px; vertical-align: middle;">KKM</th>
              <th style="width: 70px; vertical-align: middle;">Nilai</th>
              <th style="width: 110px; vertical-align: middle;">Status</th>
              <th style="width: 90px; vertical-align: middle;">Sumber</th>
              <th style="width: 100px; vertical-align: middle;">Tanggal</th>
            </tr>
          </thead>
          <tbody>
            @forelse($kompetensis as $k)
              @php
                $capaian = $capaians[$k->id_kompetensi] ?? null;
                $hasRecord = ($capaian !== null);
                $isKompeten = ($hasRecord && $capaian->status === 'kompeten');
              @endphp
              <tr>
                <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                <td class="text-center font-weight-bold">{{ $k->kode_kompetensi }}</td>
                <td>{{ $k->nama_kompetensi }}</td>
                <td class="text-center">{{ $k->tingkat }}</td>
                <td class="text-center">{{ $k->semester }}</td>
                <td class="text-center font-weight-500">{{ floatval($k->kkm) }}</td>
                <td class="text-center font-weight-bold" style="font-size: 0.95rem;">
                  @if($hasRecord)
                    <span class="{{ $isKompeten ? 'text-success' : 'text-danger' }}">
                      {{ floatval($capaian->nilai) }}
                    </span>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td class="text-center">
                  @if($hasRecord)
                    <span class="badge {{ $isKompeten ? 'badge-success' : 'badge-danger' }} px-2 py-1 font-weight-bold">
                      {{ $isKompeten ? 'Kompeten' : 'Belum' }}
                    </span>
                  @else
                    <span class="badge badge-light border text-muted px-2 py-1">Belum Uji</span>
                  @endif
                </td>
                <td class="text-center text-capitalize small">
                  {{ $hasRecord ? $capaian->jenis_capaian : '-' }}
                </td>
                <td class="text-center small">
                  {{ $hasRecord ? \Carbon\Carbon::parse($capaian->tanggal)->format('d/m/Y') : '-' }}
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="10" class="text-center py-4 text-muted">
                  Belum ada daftar kompetensi kejuruan yang dikonfigurasi.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Tanda Tangan Resmi Pengesahan -->
      <div class="row mt-5 pt-3" style="page-break-inside: avoid;">
        <div class="col-6 text-center">
          <p class="mb-1 text-muted small">Mengetahui,</p>
          <p class="font-weight-bold text-dark mb-5">Kepala Program Keahlian</p>
          <div style="border-bottom: 1px solid #333; width: 65%; margin: 40px auto 5px auto;"></div>
          <p class="text-muted small mb-0">NIP / NUPTK: ..............................</p>
        </div>
        <div class="col-6 text-center">
          <p class="mb-1 text-muted small">Ditetapkan pada: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
          <p class="font-weight-bold text-dark mb-5">Guru Pembimbing / Penguji</p>
          <div style="border-bottom: 1px solid #333; width: 65%; margin: 40px auto 5px auto;"></div>
          <p class="text-muted small mb-0">NIP / NUPTK: ..............................</p>
        </div>
      </div>

    </div>

  </div>
</div>
@endsection
