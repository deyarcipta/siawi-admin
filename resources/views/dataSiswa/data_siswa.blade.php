@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-user-graduate text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Siswa</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Kelola data induk siswa, akun login, dan penempatan kelas</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Siswa</li>
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
              <i class="fas fa-table text-primary mr-2"></i> Data Siswa
            </h3>
            <div class="ml-auto">
              <a href="{{ route('admin.siswa.download') }}" class="btn btn-primary btn-sm"><i class="fas fa-download mr-1"></i> Download Siswa</a>
              @if($user && $user->hasAnyRole(['admin', 'tata_usaha', 'kurikulum']))
              <a href="/admin/siswa/create" class="btn btn-success btn-sm"><i class="fas fa-plus mr-1"></i> Tambah Siswa</a>
              @endif
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Nis</th>
                <th>Nama Siswa</th>
                <th>Kelas</th>
                <th>Username</th>
                {{-- <th>Password</th> --}}
                <th>Action</th>
              </tr>
              </thead>
              <tbody>
              @php
                $canManageMaster = $user && $user->hasAnyRole(['admin', 'tata_usaha', 'kurikulum']);
                $isWaliKelas = $user && $user->hasRole('wali_kelas');
                $walasKelasIds = $user ? $user->getKelasWaliIds() : [];
              @endphp
              @foreach ($siswa as $data)
              @php
                $canEditThisSiswa = $canManageMaster || ($isWaliKelas && in_array($data->id_kelas, $walasKelasIds));
              @endphp
              <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$data->nis}}</td>
                <td>{{$data->nama_siswa}}</td>
                <td>{{$data->kelas->nama_kelas ?? '-'}}</td>
                <td>{{$data->nis}}</td>
                {{-- <td>{{$data->password}}</td> --}}
                <td>
                <form action="/admin/siswa/{{$data->id_siswa}}" method="POST" class="form-delete">
                    <a href="/admin/siswa/{{$data->id_siswa}}" class="btn btn-success btn-sm" title="Detail Siswa"><i class="fa fa-eye"></i></a>
                    @if($canEditThisSiswa)
                    <a href="{{route('admin.siswa.reset', $data->id_siswa)}}" class="btn btn-primary btn-sm" title="Reset Password"><i class="fa fa-key" style="color: white"></i></a>
                    <a href="/admin/siswa/{{$data->id_siswa}}/edit" class="btn btn-warning btn-sm" title="Edit Data Siswa"><i class="fa fa-edit" style="color: white"></i></a>
                    @endif
                    @if($canManageMaster)
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-danger btn-sm btn-delete" title="Hapus Siswa"><i class="fa fa-trash"></i></button>
                    @endif
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
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const deleteButtons = document.querySelectorAll('.btn-delete');

  deleteButtons.forEach(function (button) {
    button.addEventListener('click', function (e) {
      const form = this.closest('form');

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data yang dihapus tidak bisa dikembalikan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
});
</script>
@endpush
