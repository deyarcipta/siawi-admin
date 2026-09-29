@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-user-times text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Siswa Tidak Hadir</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Daftar siswa yang berhalangan hadir (Sakit, Izin, Alfa) per rentang tanggal</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="#">Absensi Siswa</a></li>
          <li class="breadcrumb-item active">Siswa Tidak Hadir</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">

    <!-- Filter Form -->
    <div class="card mb-4">
      <div class="card-header d-flex align-items-center">
        <h3 class="card-title text-dark font-weight-bold mb-0">
          <i class="fas fa-sliders-h text-primary mr-2"></i> Filter Rentang Tanggal
        </h3>
      </div>
      <div class="card-body">
        <form method="GET" action="{{ url('/admin/siswa-tidak-hadir') }}" class="row align-items-end">
          <div class="form-group col-md-4 mb-3 mb-md-0">
            <label for="tanggal_mulai" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
          </div>
          <div class="form-group col-md-4 mb-3 mb-md-0">
            <label for="tanggal_akhir" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
          </div>
          <div class="col-md-4 d-flex">
            <button type="submit" class="btn btn-primary btn-sm flex-fill mr-2"><i class="fas fa-search mr-1"></i> Cari</button>
            <a href="{{ url('/admin/siswa-tidak-hadir?today=1') }}" class="btn btn-success btn-sm flex-fill"><i class="fas fa-calendar-day mr-1"></i> Hari Ini</a>
          </div>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card">
      <div class="card-header d-flex align-items-center">
        <h3 class="card-title text-dark font-weight-bold mb-0">
          <i class="fas fa-table text-primary mr-2"></i> 
          @if(request('tanggal_mulai') && request('tanggal_akhir'))
            Data Siswa Tidak Hadir ({{ \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d M Y') }} - {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d M Y') }})
          @elseif(request('today'))
            Data Siswa Tidak Hadir Hari Ini ({{ now()->format('d M Y') }})
          @else
            Data Ketidakhadiran Siswa
          @endif
        </h3>
      </div>
      <div class="card-body">
        @if(!is_null($dataTidakHadir) && count($dataTidakHadir) > 0)
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Tanggal</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($dataTidakHadir as $item)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $item->siswa->nama_siswa }}</td>
                  <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                  <td>{{ ucfirst($item->kehadiran) }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @elseif(request()->has('tanggal_mulai') || request('today'))
          <div class="alert alert-warning">Tidak ada data ketidakhadiran ditemukan.</div>
        @else
          <div class="alert alert-info">Silakan pilih rentang tanggal atau klik tombol "Tampilkan Hari Ini".</div>
        @endif
      </div>
    </div>

  </div>
</div>
@endsection
