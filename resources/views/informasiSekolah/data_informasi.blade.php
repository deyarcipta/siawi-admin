@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Informasi Sekolah</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Informasi Sekolah</li>
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
            <h3 class="card-title">Data Informasi</h3>
            <a href="/admin/informasi/create" class="btn btn-success ml-auto">Tambah Informasi</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover">
              <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Informasi</th>
                <th>Tanggal Awal</th>
                <th>Tanggal Akhir</th>
                <th>file edaran</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($informasi as $data)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$data->informasi}}</td>
                <td>{{$data->tanggal_awal}}</td>
                <td>{{$data->tanggal_akhir}}</td>
                <td>
                  <a href="{{ asset("storage/file-informasi/$data->file") }}" target="_blank">{{$data->file}}</a>
                </td>
                <td>
                  {{-- <form action="#" method="POST"> --}}
                  <form action="/admin/informasi/{{$data->id}}" method="POST">
                    {{-- <a href="informasi/{{$data->id}}" class="btn btn-success"><i class="fa fa-eye"></i></a> --}}
                    <a href="/admin/informasi/{{$data->id}}/edit" class="btn btn-warning"><i class="fa fa-edit" style="color: white"></i></a>
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
@endsection