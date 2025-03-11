@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Review Rapot Siswa</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Rapot Siswa</a></li>
            <li class="breadcrumb-item active">Review Rapot Siswa</li>
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
              <h3 class="card-title">Form Review Rapot Siswa</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/rapot" method="POST">
              @csrf
              <div class="card-body">
              <table id="siswaTable" class="table table-bordered table-hover mt-2">
                  <thead>
                      <tr>
                          <th style="width: 10px">No</th>
                          <th>Nama Siswa</th>
                          <th>Semester</th>
                          <th>Rata - Rata</th>
                          <th>Rapot</th>
                          <th>Action</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($rapot as $data)
                      <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{$data->siswa->nama_siswa}}</td>
                          <td>{{$data->semester}}</td>
                          <td>{{$data->rata_rata}}</td>
                          <td>
                            <a href="{{asset("storage/file_rapot/$data->file_rapot")}}" target="_blank" rel="noopener noreferrer">Link Rapot</a>
                          </td>
                          <td>
                            <form action="/admin/rapot/{{$data->id_rapot}}" method="POST">
                              <a href="/admin/rapot/{{$data->id_rapot}}/edit" class="btn btn-success"><i class="fa fa-edit"></i></a>
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