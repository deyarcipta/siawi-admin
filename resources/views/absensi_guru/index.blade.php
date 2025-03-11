@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Data Absensi Harian Guru</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Data Absensi Harian Guru</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <!-- /.content-header -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title">Absensi Guru Hari <b>{{$hari}}</b></h3>
            <a href="{{ url('/admin/downloadAbsensiHarian') }}" class="btn btn-success ml-auto">Download Data</a>
            <button type="button" class="btn btn-primary ml-2" data-toggle="modal" data-target="#tambahKehadiranModal">
                Tambah Kehadiran
            </button>
        </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Nama Guru</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Status</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($absensiGuru as $data)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$data->guru->nama_guru}}</td>
                <td>{{$data->jam_masuk ?? '-'}}</td>
                <td>{{$data->jam_pulang ?? '-'}}</td>
                <td>{{$data->kehadiran}}</td>
              </tr>
              @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card --> 
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="tambahKehadiranModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title" id="modalLabel">Tambah Kehadiran Guru</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form action="{{ url('/admin/tambah-kehadiran') }}" method="POST">
                      @csrf
                      <div class="form-group">
                          <label for="id_guru">Nama Guru</label>
                          <select name="id_guru" id="id_guru" class="form-control">
                              <option value="">Pilih Guru</option>
                              @foreach($guruList as $guru)
                                  <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru }}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="form-group">
                          <label for="kehadiran">Status Kehadiran</label>
                          <select name="kehadiran" id="kehadiran" class="form-control">
                              <option value="Hadir">Hadir</option>
                              <option value="Izin">Izin</option>
                              <option value="Sakit">Sakit</option>
                          </select>
                      </div>
                      <button type="submit" class="btn btn-success">Simpan</button>
                  </form>
              </div>
          </div>
      </div>
  </div>
@endsection

@push('scripts')
  <script>
    // Melakukan refresh setiap 30 detik (30000 milidetik)
    setInterval(function() {
      window.location.href = window.location.href; // Reload halaman
    }, 10000);
  </script>
@endpush
