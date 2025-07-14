@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Data Siswa PKL</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Data Siswa PKL</a></li>
          <li class="breadcrumb-item active">Data Siswa PKL</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title">Data Siswa PKL</h3>
            <button class="btn btn-success ml-auto" data-toggle="modal" data-target="#modalTambahSiswaPkl">
              Tambah Siswa PKL
            </button>
          </div>

          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
                <tr>
                  <th style="width: 10px">No</th>
                  <th>Nama Perusahaan</th>
                  <th>Alamat</th>
                  <th>Penanggung Jawab</th>
                  <th>Jumlah Siswa PKL</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_siswa_pkl as $data)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $data->siswa->nama_siswa }}</td>
                  <td>{{ $data->kelas->nama_kelas }}</td>
                  <td>{{ $data->perusahaan->nama_perusahaan }}</td>
                  <td>{{ $data->tanggal_mulai }}</td>
                  <td>
                    <form action="{{ route('admin.siswaPkl.destroy', $data->id_siswa_pkl) }}" method="POST">
                      <a href="{{ route('admin.siswaPkl.edit', $data->id_siswa_pkl) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
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
        </div> 
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Siswa PKL -->
<div class="modal fade" id="modalTambahSiswaPkl" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('admin.siswaPkl.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Tambah Siswa PKL</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="form-group">
            <label for="id_kelas">Kelas</label>
            <select name="id_kelas" id="id_kelas" class="form-control">
              <option value="">Pilih Kelas</option>
              @foreach($kelasList as $kelas)
              <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group">
            <label for="id_siswa">Nama Siswa</label>
              <select name="id_siswa" id="id_siswa" class="form-control">
                <option value="">Pilih siswa</option>
                @foreach($siswaList as $siswa)
                <option value="{{ $siswa->id_siswa }}">{{ $siswa->nama_siswa }}</option>
                @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="id_perusahaan">Perusahaan</label>
            <select name="id_perusahaan" class="form-control" required>
              <option value="">-- Pilih Perusahaan --</option>
              @foreach($perusahaan as $item)
                <option value="{{ $item->id_perusahaan }}">{{ $item->nama_perusahaan }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group">
            <label for="tanggal_mulai">Tanggal Mulai</label>
            <input type="date" class="form-control" name="tanggal_mulai" required>
          </div>

          <div class="form-group">
            <label for="tanggal_selesai">Tanggal Selesai</label>
            <input type="date" class="form-control" name="tanggal_selesai" required>
          </div>
        </div>
        <input type="text" name="status" value="PKL" hidden>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
      $('#id_kelas').on('change', function() {
          var idKelas = $(this).val();
          if (idKelas) {
              $.ajax({
                  url: '/admin/get-siswa-by-kelas/' + idKelas,
                  type: "GET",
                  dataType: "json",
                  success: function(data) {
                      $('#id_siswa').empty();
                      $('#id_siswa').append('<option value="">Pilih Siswa</option>');
                      $.each(data, function(key, siswa) {
                          $('#id_siswa').append('<option value="'+ siswa.id_siswa +'">'+ siswa.nama_siswa +'</option>');
                      });
                  }
              });
          } else {
              $('#id_siswa').empty();
              $('#id_siswa').append('<option value="">Pilih Siswa</option>');
          }
      });
  });
  </script>
@endpush