@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-chalkboard-teacher text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Guru</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Kelola data tenaga pendidik, hak akses role, dan kontak WhatsApp</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Guru</li>
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
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title text-dark font-weight-bold mb-0">
              <i class="fas fa-table text-primary mr-2"></i> Data Guru
            </h3>
            <a href="/admin/guru/create" class="btn btn-success btn-sm ml-auto"><i class="fas fa-plus mr-1"></i> Tambah Guru</a>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Nama Guru</th>
                <th>Username</th>
                {{-- <th>Password</th> --}}
                <th>No HP / WhatsApp</th>
                <th>Role</th>
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
                @foreach ($guru as $gru)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$gru->nama_guru}}</td>
                <td>{{$gru->username}}</td>
                {{-- <td>{{ $gru->password }}</td> --}}
                <td>{{$gru->no_hp ?? '-'}}</td>
                <td>
                  @if($gru->role == 'admin')
                    <span class="badge badge-primary px-2 py-1">Admin</span>
                  @elseif($gru->role == 'wali_kelas')
                    <span class="badge badge-success px-2 py-1">Wali Kelas</span>
                  @elseif($gru->role == 'kurikulum')
                    <span class="badge badge-info px-2 py-1">Kurikulum</span>
                  @elseif($gru->role == 'kesiswaan')
                    <span class="badge badge-warning px-2 py-1">Kesiswaan</span>
                  @elseif($gru->role == 'guru')
                    <span class="badge badge-secondary px-2 py-1">Guru</span>
                  @else
                    <span class="badge badge-light px-2 py-1">{{ ucfirst($gru->role) }}</span>
                  @endif
                </td>
                <td>
                  <form action="guru/{{$gru->id_guru}}" method="POST">
                    <a href="{{route('admin.guru.reset', $gru->id_guru)}}" class="btn btn-primary"><i class="fa fa-key" style="color: white"></i></a>
                    <a href="{{route('admin.guru.edit', $gru->id_guru)}}" class="btn btn-warning"><i class="fa fa-edit" style="color: white"></i></a>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger"><i class="fa fa-trash" style="color: white"></i></button>
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