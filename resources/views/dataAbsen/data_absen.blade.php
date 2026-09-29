@extends($layout)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-7 d-flex align-items-center">
                <i class="fas fa-calendar-check text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
                <div class="d-flex flex-column justify-content-center">
                    <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Rekap Absensi Kelas</h1>
                    <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Rekapitulasi dan evaluasi tingkat persentase kehadiran per kelas</p>
                </div>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
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
                      <h3 class="card-title text-dark font-weight-bold mb-0">
                          <i class="fas fa-sliders-h text-primary mr-2"></i> Filter Data Periode Absensi
                      </h3>
                  </div>
                  <div class="card-body">
                      <form action="/admin/rekapAbsen" method="GET">
                          @csrf
                          <div class="row align-items-end">
                              <div class="form-group col-md-4 mb-3 mb-md-0">
                                  <label for="tanggal_awal" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Awal</label>
                                  <input type="date" class="form-control" id="tanggal_awal" name="tanggal_awal" required value="{{ $tanggal_awal ?? '' }}">
                              </div>
                              <div class="form-group col-md-4 mb-3 mb-md-0">
                                  <label for="tanggal_akhir" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Akhir</label>
                                  <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required value="{{ $tanggal_akhir ?? '' }}">
                              </div>
                              <div class="form-group col-md-2 mb-0">
                                  <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Tampilkan</button>
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
                          <i class="fas fa-table text-primary mr-2"></i> Data Rekap Absensi Kelas
                      </h3>
                  </div>
                  <div class="card-body">
                      <table class="table table-bordered table-hover">
                          <thead>
                              <tr>
                                  <th style="width: 10px">No</th>
                                  <th>Nama Kelas</th>
                                  <th>Presentase Kehadiran</th>
                                  <th>Action</th>
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
                                  <td>
                                    <a href="/admin/showRekapAbsen?tanggal_awal={{ $tanggal_awal }}&tanggal_akhir={{ $tanggal_akhir }}&id_kelas={{ $data['id_kelas'] }}" class="btn btn-primary">
                                        <i class="fa fa-eye"></i>
                                    </a>
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
