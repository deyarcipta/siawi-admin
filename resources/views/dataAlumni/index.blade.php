@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-user-graduate text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Alumni Siswa</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Database penelusuran tamatan dan tahun kelulusan alumni</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Data Alumni</li>
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
                <i class="fas fa-table text-primary mr-2"></i> Data Alumni Siswa
              </h3>
              <div class="ml-auto">
                <a href="{{ route('admin.alumni.download') }}" class="btn btn-primary btn-sm"><i class="fas fa-download mr-1"></i> Download Alumni</a>
                @if($user && $user->hasAnyRole(['admin', 'tata_usaha', 'kurikulum']))
                <a href="/admin/alumni/create" class="btn btn-success btn-sm"><i class="fas fa-plus mr-1"></i> Tambah Alumni</a>
                @endif
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
              <table id="example2" class="table table-bordered table-hover table-striped">
                <thead>
                  <tr>
                    <th style="width: 10px">No</th>
                    <th>NIS</th>
                    <th>Nama Alumni</th>
                    <th>Jurusan</th>
                    <th>Tahun Lulus</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @php
                    $canManageAlumni = $user && $user->hasAnyRole(['admin', 'tata_usaha', 'kurikulum']);
                  @endphp
                  @foreach ($alumni as $data)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->nis ?? '-' }}</td>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->jurusan->nama_jurusan ?? '-' }}</td>
                    <td>{{ $data->tahun_lulus }}</td>
                    <td>{{ $data->status ?? '-' }}</td>
                    <td>
                      <form action="/admin/alumni/{{ $data->id_alumni }}" method="POST" class="form-delete">
                        <a href="/admin/alumni/{{ $data->id_alumni }}" class="btn btn-success btn-sm" title="Detail"><i class="fa fa-eye"></i></a>
                        @if($canManageAlumni)
                        <a href="/admin/alumni/{{ $data->id_alumni }}/edit" class="btn btn-warning btn-sm" title="Edit"><i class="fa fa-edit" style="color: white"></i></a>
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-danger btn-sm btn-delete" title="Hapus"><i class="fa fa-trash"></i></button>
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
        text: "Data alumni yang dihapus tidak bisa dikembalikan!",
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
