@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Data Siswa Tidak Hadir</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Data Siswa Tidak Hadir</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">

    <!-- Filter Form -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Filter Rentang Tanggal</h3>
      </div>
      <div class="card-body">
        <form method="GET" action="{{ url('/admin/siswa-tidak-hadir') }}" class="form-inline">
          <div class="form-group mr-2">
            <label for="tanggal_mulai" class="mr-2">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
          </div>
          <div class="form-group mr-2">
            <label for="tanggal_akhir" class="mr-2">Tanggal Akhir</label>
            <input type="date" name="tanggal_akhir" id="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
          </div>
          <button type="submit" class="btn btn-primary">Cari</button>
          <a href="{{ url('/admin/siswa-tidak-hadir?today=1') }}" class="btn btn-success ml-2">Tampilkan Hari Ini</a>
        </form>
      </div>
    </div>

    <!-- Data Table -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">
          @if(request('tanggal_mulai') && request('tanggal_akhir'))
            Data Siswa Tidak Hadir dari <b>{{ \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d M Y') }}</b> 
            sampai <b>{{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d M Y') }}</b>
          @elseif(request('today'))
            Data Siswa Tidak Hadir Hari Ini ({{ now()->format('d M Y') }})
          @else
            Silakan pilih tanggal terlebih dahulu
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
