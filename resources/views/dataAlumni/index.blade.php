@extends($layout)

@section('content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Data Alumni</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Data Alumni</li>
          </ol>
        </div>
      </div>
    </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
      <div class="card">
        <div class="card-header d-flex align-items-center">
          <h3 class="card-title">Data Alumni</h3>
          <div class="ml-auto">
            <a href="{{ route('admin.siswa.download') }}" class="btn btn-primary">Download Alumni</a>
            <a href="/admin/siswa/create" class="btn btn-success">Tambah Alumni</a>
          </div>
        </div>
        <div class="card-body">
          <table id="example2" class="table table-bordered table-hover table-striped">
            <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>NIS</th>
                <th>Nama Alumni</th>
                <th>Jurusan</th>
                <th>Tahun Lulus</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($alumni as $a)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $a->nis }}</td>
                <td>{{ $a->nama }}</td>
                <td>{{ $a->jurusan->nama_jurusan ?? '-' }}</td>
                <td>{{ $a->tahun_lulus }}</td>
                <td>
                  <a href="{{ url('/admin/alumni/'.$a->id_alumni.'/edit') }}" class="btn btn-success btn-sm">
                    <i class="fa fa-edit"></i>
                  </a>
                  <form action="{{ url('/admin/alumni/'.$a->id_alumni) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus alumni ini?');">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm">
                      <i class="fa fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="6" class="text-center">Belum ada data alumni.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
      </div>
    </div>
  </div>
</div>
@endsection
