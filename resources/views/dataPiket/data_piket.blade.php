@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Guru Piket</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Guru Piket</li>
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
        @if(session('success'))
            <div class="alert alert-success">{!! session('success') !!}</div>
        @endif
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title">Jadwal Guru Piket</h3>
            <a href="/admin/guruPiket/create" class="btn btn-success ml-auto">Tambah Piket</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead class="bg-primary text-white">
              <tr>
                <th style="width: 10px">No</th>
                <th>Nama Guru</th>
                <th>Hari</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th style="width: 120px">Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($piket as $pkt)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$pkt->guru->nama_guru}}</td>
                <td>{{$pkt->hari}}</td>
                <td>{{$pkt->waktu_awal}}</td>
                <td>{{$pkt->waktu_akhir}}</td>
                <td>
                  <form action="guruPiket/{{$pkt->id_piket}}" method="POST">
                    <a href="{{route('admin.guruPiket.edit', $pkt->id_piket)}}" class="btn btn-warning"><i class="fa fa-edit" style="color: white"></i></a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus jadwal piket ini?')"><i class="fa fa-trash" style="color: white"></i></button>
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
@endsection
