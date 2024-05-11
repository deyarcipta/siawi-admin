@extends($layout)
@section('content')
<!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-info">
                <div class="inner">
                  <h3>{{$totalSiswa}}</h3>
  
                  <p>Jumlah Siswa</p>
                </div>
                <div class="icon">
                  <i class="ion ion-android-people"></i>
                </div>
                <a href="/siswa" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>{{$presentaseKehadiran}}<sup style="font-size: 20px">%</sup></h3>
  
                  <p>Kehadiran Hari Ini</p>
                </div>
                <div class="icon">
                  <i class="ion ion-stats-bars"></i>
                </div>
                <a href="/absensi" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>{{$jumlahTidakHadir}}</h3>
  
                  <p>Siswa Tidak Hadir</p>
                </div>
                <div class="icon">
                  <i class="ion ion-android-exit"></i>
                </div>
                <a href="/absensi" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3>{{$totalModul}}</h3>
  
                  <p>Jumlah Modul</p>
                </div>
                <div class="icon">
                  <i class="ion ion-ios-bookmarks"></i>
                </div>
                <a href="/modul" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-6">
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h5 class="m-0">Siswa Tidak Hadir</h5>
                </div>
                <div class="card-body">
                  <table id="" class="table table-bordered table-hover mt-2">
                    <thead>
                        <tr>
                            <th style="width: 10px">No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($absensi as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$data->siswa->nama_siswa}}</td>
                            <td>{{$data->kelas->nama_kelas}}</td>
                            <td>{{$data->kehadiran}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
              </div>
            </div>
            <!-- /.col-md-6 -->
            <div class="col-lg-6">
              <div class="card card-primary card-outline">
                <div class="card-header">
                  <h5 class="m-0">Point Siswa Terbaru</h5>
                </div>
                <div class="card-body">
                  <table id="" class="table table-bordered table-hover mt-2">
                    <thead>
                        <tr>
                            <th style="width: 10px">No</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Point</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($point as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{$data->siswa->nama_siswa}}</td>
                            <td>{{$data->kelas->nama_kelas}}</td>
                            <td>{{$data->skor_point}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
              </div><!-- /.card -->
            </div>
            <!-- /.col-md-6 -->
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
<!-- AdminLTE App -->
<script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  @if ($message = Session::get('success'))
      Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: '{{ $message }}'
      });
  @endif

  @if ($message = Session::get('failed'))
      Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: '{{ $message }}'
      });
  @endif
</script>
@endsection