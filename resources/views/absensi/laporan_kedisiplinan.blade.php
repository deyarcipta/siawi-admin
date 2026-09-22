@extends($layout)
@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0 font-weight-bold text-dark">
            <i class="fas fa-fingerprint text-primary mr-2"></i>Laporan Kedisiplinan Absensi Siswa
          </h1>
          <p class="text-muted text-sm mb-0">Rekapitulasi metode absensi mingguan (Otomatis Mesin vs Manual Guru)</p>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
            <li class="breadcrumb-item"><a href="/admin/absensi">Absensi</a></li>
            <li class="breadcrumb-item active">Kedisiplinan Mesin</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      
      <!-- Filter Card -->
      <div class="card card-default shadow-sm">
        <div class="card-header bg-white border-bottom">
          <h3 class="card-title font-weight-bold text-secondary">
            <i class="fas fa-filter mr-1 text-primary"></i> Filter Periode & Kelas
          </h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
              <i class="fas fa-minus"></i>
            </button>
          </div>
        </div>
        <div class="card-body">
          <form action="{{ route('admin.laporanKedisiplinan.index') }}" method="GET">
            <div class="row">
              <div class="col-md-3 mb-2">
                <label for="tanggal" class="font-weight-bold d-flex justify-content-between align-items-center mb-1">
                  <span>Pilih Tanggal:</span>
                  <span class="badge badge-light border text-primary" style="font-size: 11px;">
                    <i class="far fa-calendar-alt mr-1"></i>{{ \Carbon\Carbon::parse($startDateStr)->format('d M') }} - {{ \Carbon\Carbon::parse($endDateStr)->format('d M Y') }}
                  </span>
                </label>
                <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $tanggalPilihan }}">
              </div>
              <div class="col-md-3 mb-2">
                <label for="id_kelas" class="font-weight-bold mb-1">Pilih Kelas:</label>
                <select name="id_kelas" id="id_kelas" class="form-control">
                  <option value="">-- Semua Kelas --</option>
                  @foreach($kelasList as $k)
                    <option value="{{ $k->id_kelas }}" {{ $selectedKelasId == $k->id_kelas ? 'selected' : '' }}>
                      {{ $k->nama_kelas }}
                    </option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-3 mb-2">
                <label for="kategori" class="font-weight-bold mb-1">Kategori Kedisiplinan:</label>
                <select name="kategori" id="kategori" class="form-control">
                  <option value="">-- Semua Kategori --</option>
                  <option value="tertib" {{ $selectedKategori == 'tertib' ? 'selected' : '' }}>🟢 Sangat Tertib (≥80%)</option>
                  <option value="cukup" {{ $selectedKategori == 'cukup' ? 'selected' : '' }}>🟡 Cukup Tertib (50% - 79%)</option>
                  <option value="perlu_pembinaan" {{ $selectedKategori == 'perlu_pembinaan' ? 'selected' : '' }}>🔴 Sering Manual (<50%)</option>
                </select>
              </div>
              <div class="col-md-3 mb-2">
                <label class="font-weight-bold mb-1 d-none d-md-block">&nbsp;</label>
                <div class="d-flex align-items-center">
                  <button type="submit" class="btn btn-primary mr-1 text-nowrap d-inline-flex align-items-center justify-content-center flex-grow-1" style="height: 38px;" title="Tampilkan Data">
                    <i class="fas fa-search mr-1"></i> <span>Tampilkan</span>
                  </button>
                  <a href="{{ route('admin.laporanKedisiplinan.index') }}" class="btn btn-secondary mr-1 d-inline-flex align-items-center justify-content-center px-3" style="height: 38px;" title="Reset Filter">
                    <i class="fas fa-undo"></i>
                  </a>
                  <a href="{{ route('admin.laporanKedisiplinan.export', ['tanggal' => $tanggalPilihan, 'id_kelas' => $selectedKelasId, 'kategori' => $selectedKategori]) }}" class="btn btn-success text-nowrap d-inline-flex align-items-center justify-content-center px-3" style="height: 38px;" title="Export Excel">
                    <i class="fas fa-file-excel mr-1"></i> <span>Excel</span>
                  </a>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>

      <!-- KPI Summary Cards -->
      <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box shadow-sm border">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
              <span class="info-box-text text-muted font-weight-bold">Total Siswa</span>
              <span class="info-box-number h4 mb-0 font-weight-bold">{{ $totalSiswa }}</span>
              <small class="text-muted">Dalam filter terpilih</small>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box shadow-sm border">
            <span class="info-box-icon bg-primary elevation-1"><i class="fas fa-chart-pie"></i></span>
            <div class="info-box-content">
              <span class="info-box-text text-muted font-weight-bold">Rata-rata Kepatuhan</span>
              <span class="info-box-number h4 mb-0 font-weight-bold">{{ $rataRataKepatuhan }}%</span>
              <div class="progress progress-xs mt-1">
                <div class="progress-bar bg-primary" style="width: {{ $rataRataKepatuhan }}%"></div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box shadow-sm border">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check-circle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text text-muted font-weight-bold">Tertib Mesin (≥80%)</span>
              <span class="info-box-number h4 mb-0 font-weight-bold text-success">{{ $countSangatTertib }}</span>
              <small class="text-muted">Rutin tap mesin mandiri</small>
            </div>
          </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
          <div class="info-box shadow-sm border">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-exclamation-triangle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text text-muted font-weight-bold">Sering Manual (<50%)</span>
              <span class="info-box-number h4 mb-0 font-weight-bold text-danger">{{ $countPerluPembinaan }}</span>
              <small class="text-muted">Perlu pembinaan / cek kartu</small>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Rekap Table Card -->
      <div class="card card-primary card-outline shadow-sm">
        <div class="card-header bg-white">
          <h3 class="card-title font-weight-bold text-dark my-1">
            <i class="fas fa-table mr-1 text-primary"></i> 
            Rekapitulasi Mingguan: {{ \Carbon\Carbon::parse($startDateStr)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($endDateStr)->translatedFormat('d F Y') }}
          </h3>
          <div class="card-tools">
            <span class="badge badge-light border px-3 py-2 text-secondary shadow-xs font-weight-normal">
              Menampilkan {{ count($rekapSiswa) }} data siswa
            </span>
          </div>
        </div>
        <div class="card-body">
          <table id="example2" class="table table-bordered table-hover table-striped">
            <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Siswa & Kelas</th>
                @foreach($daysInWeek as $day)
                  <th>
                    {{ $day['hari'] }}<br>
                    <small class="text-muted">{{ $day['tgl_formatted'] }}</small>
                  </th>
                @endforeach
                <th>Hadir Mesin</th>
                <th>Hadir Manual</th>
                <th>Disiplin Mesin</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($rekapSiswa as $item)
                @php
                  $siswa = $item['siswa'];
                  $harian = $item['harian'];
                @endphp
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>
                    <strong>{{ $siswa->nama_siswa }}</strong><br>
                    <small class="text-muted">NIS: {{ $siswa->nis ?? '-' }} | {{ $siswa->kelas->nama_kelas ?? '-' }}</small>
                  </td>

                  @foreach($daysInWeek as $day)
                    @php
                      $dayData = $harian[$day['tanggal']] ?? null;
                      $status = $dayData['status'] ?? 'belum_absen';
                    @endphp
                    <td>
                      @if($status === 'mesin')
                        <span class="badge badge-success px-2 py-1 d-block mb-1">
                          <i class="fas fa-fingerprint mr-1"></i> Mesin
                        </span>
                        <small class="text-muted d-block" style="font-size: 11px;">
                          {{ $dayData['jam_masuk'] !== '-' ? substr($dayData['jam_masuk'], 0, 5) : '-' }}
                          @if(!empty($dayData['jam_pulang']) && $dayData['jam_pulang'] !== '-')
                            - {{ substr($dayData['jam_pulang'], 0, 5) }}
                          @endif
                        </small>
                      @elseif($status === 'piket')
                        <span class="badge badge-warning px-2 py-1 d-block mb-1 text-dark" title="{{ $dayData['keterangan'] }}">
                          <i class="fas fa-user-clock mr-1"></i> Terlambat
                        </span>
                        <small class="text-muted d-block" style="font-size: 11px;">
                          {{ substr($dayData['jam_masuk'], 0, 5) }}
                        </small>
                      @elseif($status === 'manual')
                        <span class="badge badge-secondary px-2 py-1 d-block mb-1 text-white" style="background-color: #fd7e14;" title="Diinput manual oleh guru">
                          <i class="fas fa-edit mr-1"></i> Manual Guru
                        </span>
                        <small class="text-muted d-block" style="font-size: 11px;">
                          {{ $dayData['keterangan'] && $dayData['keterangan'] !== '-' ? \Illuminate\Support\Str::limit($dayData['keterangan'], 14) : 'Tanpa Scan' }}
                        </small>
                      @elseif($status === 'sakit')
                        <span class="badge badge-info px-2 py-1 d-block">Sakit</span>
                      @elseif($status === 'izin')
                        <span class="badge badge-primary px-2 py-1 d-block">Izin</span>
                      @elseif($status === 'alfa')
                        <span class="badge badge-danger px-2 py-1 d-block">Alfa</span>
                      @else
                        <span class="text-muted">-</span>
                      @endif
                    </td>
                  @endforeach

                  <td class="text-center">
                    <span class="badge badge-success px-2 py-1">{{ $item['total_hadir_mesin'] }} hari</span>
                  </td>
                  <td class="text-center">
                    <span class="badge badge-warning px-2 py-1">{{ $item['total_hadir_manual'] }} hari</span>
                  </td>

                  <td>
                    <div class="d-flex align-items-center">
                      <span class="font-weight-bold mr-2">{{ $item['persentase_mesin'] }}%</span>
                      <div class="progress progress-xs flex-grow-1" style="height: 6px;">
                        <div class="progress-bar bg-{{ $item['status_badge'] }}" style="width: {{ $item['persentase_mesin'] }}%"></div>
                      </div>
                    </div>
                  </td>

                  <td class="text-center">
                    <span class="badge badge-{{ $item['status_badge'] }} px-2 py-1">
                      {{ $item['status_label'] }}
                    </span>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        
        <!-- Legend / Petunjuk Indikator -->
        <div class="card-footer bg-light border-top">
          <div class="row align-items-center">
            <div class="col-md-12">
              <span class="font-weight-bold text-secondary mr-3"><i class="fas fa-info-circle mr-1"></i> Keterangan Status:</span>
              <span class="badge badge-success px-2 py-1 mr-2"><i class="fas fa-fingerprint mr-1"></i> Mesin (Face/Scan Otomatis)</span>
              <span class="badge px-2 py-1 mr-2 text-white" style="background-color: #fd7e14;"><i class="fas fa-edit mr-1"></i> Manual Guru</span>
              <span class="badge badge-warning px-2 py-1 mr-2"><i class="fas fa-user-clock mr-1"></i> Terlambat (Dicatat Piket)</span>
              <span class="badge badge-info px-2 py-1 mr-2">Sakit</span>
              <span class="badge badge-primary px-2 py-1 mr-2">Izin</span>
              <span class="badge badge-danger px-2 py-1 mr-2">Alfa</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
@endsection
