@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Review Point Siswa</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Point Siswa</a></li>
            <li class="breadcrumb-item active">Review Point Siswa</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Form Review Point Siswa</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/pointSiswa" method="POST">
              @csrf
              <div class="card-body">
                <p style="margin:0">Nama Siswa : {{$siswa->nama_siswa}}</p>
                <p style="margin:0">No. Induk Siswa : {{$siswa->nis}}</p>
                <p style="margin:0">Kelas : {{$siswa->kelas->nama_kelas}}</p>
                <p style="margin:0">Total Point : {{$total_point}}</p>
              <table id="siswaTable" class="table table-bordered table-hover mt-2">
                  <thead>
                      <tr>
                          <th style="width: 10px">No</th>
                          <th>Nama Pelanggaran</th>
                          <th>Tanggal Pelanggaran</th>
                          <th>Nama Pelapor</th>
                          <th>Level Pelapor</th>
                          <th>Point</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($pointSiswa as $data)
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{$data->point->nama_point}}</td>
                          <td>{{$data->tanggal}}</td>
                          <td>{{$data->guru->nama_guru}}</td>
                          <td>{{$data->guru->role}}</td>
                          <td>{{$data->skor_point}}</td>
                          <td>
                            <form action="{{url('/admin/pointSiswa/'.$data->id_point_siswa)}}" method="POST">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                          </td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
                {{-- <input type="date" class="form-control" name="tanggal" value="{{ date('Y-m-d') }}" hidden> --}}
                {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </div>
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
  <script>
    $(document).ready(function() {
        $('#searchInput').on('keyup', function() {
            var searchText = $(this).val().toLowerCase();
            $('#siswaTable tbody tr').each(function() {
                var currentRowText = $(this).text().toLowerCase();
                if (currentRowText.indexOf(searchText) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
@endsection