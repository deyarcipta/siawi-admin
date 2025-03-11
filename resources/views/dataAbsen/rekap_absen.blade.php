@extends($layout)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Data Rekap Absensi Kelas</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Data Rekap Absensi Kelas</li>
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
                      <h3 class="card-title">Pilih Data Rekap Kehadiran</h3>
                  </div>
                  <div class="card-body">
                      <form action="/admin/rekapAbsen" method="GET">
                          @csrf
                          <div class="row">
                              <div class="form-group col-4">
                                  <label for="tanggal_awal">Tanggal Awal</label>
                                  <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" required value="{{ $tanggal_awal ?? '' }}">
                              </div>
                              <div class="form-group col-4">
                                <label for="tanggal_akhir">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required value="{{ $tanggal_akhir ?? '' }}">
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
                      <h3 class="card-title">Data Rekap Absensi Kelas</h3>
                  </div>
                  <div class="card-body">
                      <table class="table table-bordered table-hover">
                          <thead>
                              <tr>
                                  <th style="width: 10px">No</th>
                                  <th>Nama Kelas</th>
                                  <th>Presentase Kehadiran</th>
                              </tr>
                          </thead>
                          <tbody>
                              @foreach ($rekapKehadiran as $index => $data)
                              <tr>
                                  <td>{{ $index + 1 }}</td>
                                  <td>{{ $data['nama_kelas'] }}</td>
                                  <td>
                                      @php
                                      $presentase = $data['presentase'];
                                      $badgeClass = '';

                                      if ($presentase > 90) {
                                          $badgeClass = 'badge-success';
                                      } elseif ($presentase >= 80 && $presentase <= 90) {
                                          $badgeClass = 'badge-warning';
                                      } else {
                                          $badgeClass = 'badge-danger';
                                      }
                                      @endphp
                                      <span class="badge {{ $badgeClass }}">{{ $presentase }}%</span>
                                  </td>
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
