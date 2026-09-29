@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-user-clock text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Jadwal Guru Piket</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Pengaturan jadwal giliran piket mingguan dan pembagian jam tugas</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Guru Piket</a></li>
            <li class="breadcrumb-item active">Jadwal Piket</li>
          </ol>
        </div>
      </div>
    </div>
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
            <h3 class="card-title text-dark font-weight-bold mb-0">
              <i class="fas fa-table text-primary mr-2"></i> Data Jadwal Guru Piket
            </h3>
            <a href="/admin/guruPiket/create" class="btn btn-success btn-sm ml-auto"><i class="fas fa-plus mr-1"></i> Tambah Piket</a>
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
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($piket as $pkt)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$pkt->guru?->nama_guru ?? 'Guru Telah Dihapus'}}</td>
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
