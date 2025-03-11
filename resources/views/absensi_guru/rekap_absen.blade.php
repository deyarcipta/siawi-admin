@extends($layout)

@section('content')
<!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Data Rekap Absensi Guru</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Data Rekap Absensi Guru</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>

<div class="content">
  <div class="container-fluid">
      <div class="row">
          <div class="col-lg-12">
              <div class="card">
                  <div class="card-header d-flex align-items-center">
                      <h3 class="card-title">Pilih Data Rekap Kehadiran</h3>
                  </div>
                  <div class="card-body">
                      <form action="/admin/rekapAbsenGuru" method="GET">
                          @csrf
                          <div class="row">
                              <div class="form-group col-4">
                                  <label for="tanggal_awal">Tanggal Awal</label>
                                  <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" required value="{{ $tanggalAwal ?? '' }}">
                              </div>
                              <div class="form-group col-4">
                                <label for="tanggal_akhir">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required value="{{ $tanggalAkhir ?? '' }}">
                            </div>
                              <div class="col-md-2">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="submit" class="btn btn-primary btn-block">Tampilkan Data</button>
                                </div>
                            </div>
                          </div>
                      </form>
                  </div>
              </div>
          </div>
      </div>

      @if(isset($rekapKehadiran))
      <div class="row mt-4">
          <div class="col-lg-12">
              <div class="card">
                  <div class="card-header d-flex align-items-center">
                      <h3 class="card-title">Data Rekap Absensi Guru</h3>
                      <a href="/admin/exportExcel?tanggal_awal={{ $tanggalAwal }}&tanggal_akhir={{ $tanggalAkhir }}" class="btn btn-success ml-auto">Download Excel</a>
                  </div>
                  <div class="card-body">
                      <table class="table table-bordered table-hover text-center">
                          <thead>
                              <tr>
                                  <th style="width: 10px" rowspan="2" class="align-middle">No</th>
                                  <th rowspan="2" class="align-middle">Nama Guru</th>
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
                                  <td>{{$loop->iteration}}</td>
                                  <td class="text-left">{{ $kehadiran->first()->guru->nama_guru }}</td>
                                  @foreach (range(strtotime($tanggalAwal), strtotime($tanggalAkhir), 86400) as $date)
                                      @php
                                          $record = $kehadiran->firstWhere('tanggal', date('Y-m-d', $date));
                                      @endphp
                                      <td>{{ $record ? $record->jam_masuk : '-' }}</td>
                                      <td>{{ $record ? $record->jam_pulang : '-' }}</td>
                                  @endforeach
                              </tr>
                              @endforeach
                          </tbody>
                      </table>
                  </div>
              </div>
          </div>
      </div>
      @endif
  </div>
</div>
@endsection