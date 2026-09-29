@extends($layout)

@section('content')
  <!-- Content Header -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-user-check text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Rekap Kehadiran Guru & Staf</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Laporan presensi, ketepatan waktu hadir, dan izin guru</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Rekap Guru</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Content -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">

          <!-- Filter Card -->
          <div class="card mb-3">
              <div class="card-header d-flex align-items-center">
                  <h3 class="card-title text-dark font-weight-bold mb-0">
                      <i class="fas fa-sliders-h text-primary mr-2"></i> Filter Data Kehadiran Guru
                  </h3>
              </div>
              <div class="card-body">
                  <form method="GET" action="{{ route('admin.rekapGuru.index') }}" class="row align-items-end">
                      <div class="col-md-3 mb-3 mb-md-0">
                          <label for="tanggal_awal" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Awal</label>
                          <input type="date" id="tanggal_awal" name="tanggal_awal" class="form-control"
                                value="{{ $tanggalAwal }}">
                      </div>
                      <div class="col-md-3 mb-3 mb-md-0">
                          <label for="tanggal_akhir" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Akhir</label>
                          <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control"
                                value="{{ $tanggalAkhir }}">
                      </div>
                      <div class="col-md-3 mb-3 mb-md-0">
                          <label for="guru_id" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Guru</label>
                          <select name="guru_id" id="guru_id" class="form-control">
                              <option value="">-- Semua Guru --</option>
                              @foreach($guruList as $guru)
                                  <option value="{{ $guru->id_guru }}" {{ $guruId == $guru->id_guru ? 'selected' : '' }}>
                                      {{ $guru->nama_guru }}
                                  </option>
                              @endforeach
                          </select>
                      </div>
                      <div class="col-md-3">
                          <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Tampilkan Data</button>
                      </div>
                  </form>
              </div>
          </div>

          <!-- Data Table Card -->
          <div class="card">
            <div class="card-header d-flex align-items-center">
              <h3 class="card-title text-dark font-weight-bold mb-0">
                <i class="fas fa-table text-primary mr-2"></i> Daftar Rekap Kehadiran Guru
              </h3>

              @if(!empty($tanggalAwal) && !empty($tanggalAkhir))
                <a href="{{ route('admin.rekapGuru.export', request()->all()) }}" 
                  class="btn btn-success btn-sm ml-auto">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </a>
                <a href="{{ route('admin.rekapGuru.downloadPdf', request()->all()) }}" 
                  class="btn btn-danger btn-sm ml-2" target="_blank">
                    <i class="fas fa-file-pdf mr-1"></i> Download PDF
                </a>
              @endif
            </div>

            <div class="card-body">
              @if(empty($tanggalAwal) || empty($tanggalAkhir))
                <div class="alert alert-info text-center">
                  Silakan pilih filter tanggal dan guru terlebih dahulu.
                </div>
              @elseif(count($data) === 0)
                <div class="alert alert-warning text-center">
                  Tidak ada data kehadiran pada rentang tanggal yang dipilih.
                </div>
              @else
                <table id="example2" class="table table-bordered table-hover table-striped">
                  <thead>
                    <tr>
                      <th style="width: 10px">No</th>
                      <th>Nama Guru</th>
                      <th>Mata Pelajaran</th>
                      <th>Kelas</th>
                      <th>Tanggal</th>
                      <th>Tanggal Isi Jurnal</th>
                      <th>Jam Pelajaran</th>
                      <th>Materi</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data as $row)
                      <tr>
                          <td>{{ $row['no'] }}</td>
                          <td>{{ $row['nama_guru'] }}</td>
                          <td>{{ $row['mapel'] }}</td>
                          <td>{{ $row['kelas'] }}</td>
                          <td>{{ $row['tanggal'] }}</td>
                          <td>
                            {{-- ✅ Format created_at --}}
                            {{ \Carbon\Carbon::parse($row['created_at'])->format('d-m-Y H:i') }}
                          </td>
                          <td>{{ $row['jam_jurnal'] }}</td>
                          <td>{{ $row['jurnal'] }}</td>
                          <td>
                            {{-- Badge Absensi --}}
                            <span class="badge 
                                @if($row['status_absensi'] == 'Hadir') bg-success 
                                @elseif($row['status_absensi'] == 'Sakit') bg-warning 
                                @elseif($row['status_absensi'] == 'Izin') bg-info 
                                @else bg-danger @endif">
                                {{ $row['status_absensi'] }}
                            </span>

                            {{-- Badge Jurnal --}}
                            <span class="badge bg-{{ $row['status_jurnal']=='Isi Jurnal'?'success':'warning' }}">
                              {{ $row['status_jurnal'] }}
                            </span>
                          </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @endif
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection
