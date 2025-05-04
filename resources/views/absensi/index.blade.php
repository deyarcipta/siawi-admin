@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Data Absensi Harian Siswa</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Data Absensi Harian Siswa</li>
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
            <h3 class="card-title">Absensi Siswa Hari <b>{{$hari}}</b></h3>
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
                <th>Nama Siswa</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($absensiSiswa as $data)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$data->siswa->nama_siswa}}</td>
                <td>{{$data->jam_masuk ?? '-'}}</td>
                <td>{{$data->jam_pulang ?? '-'}}</td>
                <td>{{$data->kehadiran}}</td>
                <td>
                  <button type="button" class="btn btn-danger ml-2" data-toggle="modal" data-target="#editKehadiran{{$data->id_absensi}}"><i class="fa fa-edit" style="color: white"></i></button>
                </td>
              </tr>
                <!-- Modal Edit Kehadiran -->
                <div class="modal fade" id="editKehadiran{{$data->id_absensi}}" tabindex="-1" aria-labelledby="editModalLabel{{$data->id_absensi}}" aria-hidden="true">
                  <div class="modal-dialog">
                      <div class="modal-content">
                          <div class="modal-header">
                              <h5 class="modal-title" id="editModalLabel{{$data->id_abensi}}">Edit Kehadiran Siswa</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                          <div class="modal-body">
                              <form action="{{ url('/admin/edit-kehadiran/'.$data->id_absensi) }}" method="POST">
                                  @csrf
                                  @method('PUT')
                                  <div class="form-group">
                                      <label for="nama_siswa">Nama Siswa</label>
                                      <input type="text" class="form-control" value="{{ $data->siswa->nama_siswa }}" readonly>
                                  </div>
                                  <div class="form-group">
                                      <label for="kehadiran">Status Kehadiran</label>
                                      <select name="kehadiran" class="form-control">
                                          <option value="Hadir" {{ $data->kehadiran == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                          <option value="Izin" {{ $data->kehadiran == 'izin' ? 'selected' : '' }}>Izin</option>
                                          <option value="Sakit" {{ $data->kehadiran == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                          <option value="Alfa" {{ $data->kehadiran == 'alfa' ? 'selected' : '' }}>Alfa</option>
                                      </select>
                                  </div>
                                  <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                              </form>
                          </div>
                      </div>
                  </div>
                </div>
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
                  <h5 class="modal-title" id="modalLabel">Tambah Kehadiran Siswa</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                  <form action="{{ route('admin.absensi.tambah-kehadiran') }}" method="POST">
                      @csrf
                      <div class="form-group">
                          <label for="id_kelas">Kelas</label>
                          <select name="id_kelas" id="id_kelas" class="form-control">
                              <option value="">Pilih Kelas</option>
                              @foreach($kelasList as $kelas)
                                  <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                              @endforeach
                          </select>
                      </div>
                      <div class="form-group">
                          <label for="id_siswa">Nama Siswa</label>
                          <select name="id_siswa" id="id_siswa" class="form-control">
                              <option value="">Pilih siswa</option>
                              @foreach($siswaList as $siswa)
                                  <option value="{{ $siswa->id_siswa }}">{{ $siswa->nama_siswa }}</option>
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

    $(document).ready(function() {
      $('#id_kelas').on('change', function() {
          var idKelas = $(this).val();
          if (idKelas) {
              $.ajax({
                  url: '/admin/get-siswa-by-kelas/' + idKelas,
                  type: "GET",
                  dataType: "json",
                  success: function(data) {
                      $('#id_siswa').empty();
                      $('#id_siswa').append('<option value="">Pilih Siswa</option>');
                      $.each(data, function(key, siswa) {
                          $('#id_siswa').append('<option value="'+ siswa.id_siswa +'">'+ siswa.nama_siswa +'</option>');
                      });
                  }
              });
          } else {
              $('#id_siswa').empty();
              $('#id_siswa').append('<option value="">Pilih Siswa</option>');
          }
      });
  });
    // Melakukan refresh setiap 30 detik (30000 milidetik)
    setInterval(function() {
      window.location.href = window.location.href; // Reload halaman
    }, 60000);
  </script>
@endpush
