@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Tambah Point Siswa</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Point Siswa</a></li>
            <li class="breadcrumb-item active">Tambah Point Siswa</li>
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
              <h3 class="card-title">Form Tambah Point Siswa</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            {{-- <form action="/pointSiswa/input_point" method="POST"> --}}
              {{-- @csrf --}}
              <div class="card-body">
                <p style="margin:0">Nama Siswa : {{$siswa->nama_siswa}}</p>
                <p style="margin:0">No. Induk Siswa : {{$siswa->nis}}</p>
                <p style="margin:0">Kelas : {{$siswa->kelas->nama_kelas}}</p>
                {{-- <p style="margin:0">tanggal : {{$carbonDate}}</p> --}}
                <div class="form-group">
                  <label for="searchInput">Masukan Kata Kunci Pelanggaran Siswa</label>
                  <input type="text" class="form-control" id="searchInput" placeholder="Cari...">
                </div>
              <table id="siswaTable" class="table table-bordered table-hover mt-2">
                  <thead>
                      <tr>
                          <th style="width: 10px">No</th>
                          <th>Nama Pelanggaran</th>
                          <th>Jenis Pelanggaran</th>
                          <th>Point</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($point as $data)
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{$data->nama_point}}</td>
                          <td>{{$data->jenis_point}}</td>
                          <td>{{$data->skor_point}}</td>
                          <td>
                            <form action="/admin/pointSiswa/inputPoint" method="POST">
                              @csrf
                              <a href="{{ route('admin.pointSiswa.inputPoint', [
                                'id_siswa' => $siswa->id_siswa,
                                'id_point' => $data->id_point,
                                'id_kelas' => $siswa->kelas->id_kelas,
                                'id_jurusan' => $siswa->jurusan,
                                'skor_point' => $data->skor_point,
                                'tanggal' => $carbonDate,
                            ]) }}" class="btn btn-danger ml-auto">Proses</a>
                              {{-- <a href="{{ route('pointSiswa.inputPoint', [
                                'id_siswa' => $siswa->id_siswa,
                                'id_point' => $data->id_point,
                                'id_kelas' => $siswa->kelas->id_kelas,
                                'id_jurusan' => $siswa->jurusan,
                                'skor_point' => $data->skor_point,
                                'tanggal' => $carbonDate,
                            ]) }}" class="btn btn-danger ml-auto">Proses</a> --}}
                            </form>
                          </td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer">
              </div>
            {{-- </form> --}}
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </div>
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