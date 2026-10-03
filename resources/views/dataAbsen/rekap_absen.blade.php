@extends($layout)

@section('content')
<!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-clipboard-list text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Rekap Absensi Siswa</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Rekapitulasi rinci kehadiran hadir, sakit, izin, terlambat, dan alfa</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Absensi Siswa</a></li>
            <li class="breadcrumb-item active">Rekap Siswa</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

<div class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-lg-12">
              <div class="card">
                  <div class="card-header d-flex align-items-center">
                      <h3 class="card-title text-dark font-weight-bold mb-0">
                          <i class="fas fa-sliders-h text-primary mr-2"></i> Filter Data Rekap Kehadiran
                      </h3>
                  </div>
                  <div class="card-body">
                      <form action="/admin/rekapAbsenSiswa" method="GET">
                          @csrf
                          <div class="row align-items-end">
                            <div class="form-group col-md-3 mb-3 mb-md-0">
                                <label for="kelas" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Pilih Kelas</label>
                                <select class="form-control" id="kelas" name="kelas" required>
                                    @if(Auth::user()->role != 'wali_kelas')
                                    <option value="">-- Pilih Kelas --</option>
                                    @endif
                                    @foreach($kelas as $kls)
                                    <option value="{{ $kls->id_kelas }}" {{ ($kelasId == $kls->id_kelas || count($kelas) == 1) ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                    @endforeach
                                </select>
                              </div>
                              <div class="form-group col-md-3 mb-3 mb-md-0">
                                  <label for="tanggal_awal" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Awal</label>
                                  <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" required value="{{ $tanggalAwal ?? '' }}">
                              </div>
                              <div class="form-group col-md-3 mb-3 mb-md-0">
                                <label for="tanggal_akhir" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required value="{{ $tanggalAkhir ?? '' }}">
                            </div>
                              <div class="col-md-3">
                                <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Tampilkan Data</button>
                            </div>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>

      @if(isset($rekapKehadiran))
      <div class="row">
          <div class="col-lg-12">
              <div class="card">
                  <div class="card-header d-flex align-items-center">
                      <h3 class="card-title text-dark font-weight-bold mb-0">
                          <i class="fas fa-table text-primary mr-2"></i> Data Rekap Absensi Siswa
                      </h3>
                      <a href="/admin/exportExcelRekapSiswa?id_kelas={{ $kelasId }}&tanggal_awal={{ $tanggalAwal }}&tanggal_akhir={{ $tanggalAkhir }}" class="btn btn-success btn-sm ml-auto"><i class="fas fa-file-excel mr-1"></i> Download Excel</a>
                  </div>
                  <div class="card-body">
                      <div class="table-responsive">
                          <table id="example2" class="table table-bordered table-hover text-center align-middle">
                              <thead>
                                  <tr>
                                      <th style="width: 10px" rowspan="2" class="align-middle">No</th>
                                      <th rowspan="2" class="align-middle text-left">Nama Siswa</th>
                                      @foreach (range(strtotime($tanggalAwal), strtotime($tanggalAkhir), 86400) as $date)
                                          <th colspan="2">{{ date('d M', $date) }}</th>
                                      @endforeach
                                  </tr>
                                  <tr>
                                      @foreach (range(strtotime($tanggalAwal), strtotime($tanggalAkhir), 86400) as $date)
                                          <th>In</th>
                                          <th>Out</th>
                                      @endforeach
                                  </tr>
                              </thead>
                              <tbody>
                                @foreach ($rekapKehadiran as $index => $kehadiran)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td class="text-left font-weight-bold">{{ $kehadiran->first()->siswa->nama_siswa ?? '-' }}</td>
                                    @foreach (range(strtotime($tanggalAwal), strtotime($tanggalAkhir), 86400) as $date)
                                        @php
                                            $currentDate = date('Y-m-d', $date);
                                            $record = $kehadiran->firstWhere('tanggal', $currentDate);
                                            if ($record) {
                                                $status = strtolower(trim($record->kehadiran));
                                            } else {
                                                $status = '';
                                            }
                                        @endphp
                                        @if($record && in_array($status, ['sakit', 'izin', 'alfa']))
                                            <td>
                                                <span class="badge {{ $status == 'sakit' ? 'badge-soft-warning' : ($status == 'izin' ? 'badge-soft-info' : 'badge-soft-danger') }} px-2 py-1 text-uppercase font-weight-bold" style="font-size: 0.72rem;">
                                                    {{ $record->kehadiran }}
                                                </span>
                                            </td>
                                            <td>-</td>
                                        @else
                                            <td>{{ ($record && $record->jam_masuk) ? $record->jam_masuk : '-' }}</td>
                                            <td>{{ ($record && $record->jam_pulang) ? $record->jam_pulang : '-' }}</td>
                                        @endif
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      @endif
  </div>
</div>
@endsection