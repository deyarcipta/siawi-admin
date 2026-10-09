@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-plus-circle"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Input / Tambah Poin Siswa</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Pilih jenis pelanggaran dan catat skor poin kedisiplinan</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/pointSiswa" class="text-primary font-weight-500">Poin Siswa</a></li>
          <li class="breadcrumb-item active">Tambah Poin</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- general form elements -->
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-exclamation-circle text-primary mr-2"></i> Formulir Input Poin Pelanggaran Siswa
            </h5>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="p-3 mb-4 rounded-lg bg-light border">
              <div class="row">
                <div class="col-sm-4 mb-2 mb-sm-0">
                  <small class="text-muted d-block">Nama Siswa</small>
                  <strong class="text-dark">{{$siswa->nama_siswa}}</strong>
                </div>
                <div class="col-sm-4 mb-2 mb-sm-0">
                  <small class="text-muted d-block">No. Induk Siswa (NIS)</small>
                  <strong class="text-dark">{{$siswa->nis}}</strong>
                </div>
                <div class="col-sm-4">
                  <small class="text-muted d-block">Kelas</small>
                  <span class="badge badge-primary px-2 py-1">{{$siswa->kelas->nama_kelas}}</span>
                </div>
              </div>
            </div>

            <div class="form-group mb-3">
              <label for="searchInput" class="font-weight-600 text-dark">Cari Pelanggaran</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                </div>
                <input type="text" class="form-control border-left-0" id="searchInput" placeholder="Ketik kata kunci nama pelanggaran atau jenis...">
              </div>
            </div>

            <div class="table-responsive">
              <table id="siswaTable" class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                  <tr>
                    <th style="width: 50px;">No</th>
                    <th>Nama Pelanggaran</th>
                    <th>Jenis</th>
                    <th class="text-center" style="width: 100px;">Skor Poin</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($point as $index => $data)
                  <tr>
                    <td class="text-center text-muted">{{ $index + 1 }}</td>
                    <td class="font-weight-600 text-dark">{{ $data->nama_point }}</td>
                    <td><span class="badge badge-light border">{{ $data->jenis_point }}</span></td>
                    <td class="text-center"><span class="badge badge-danger px-2 py-1 font-weight-bold">+{{ $data->skor_point }}</span></td>
                    <td class="text-center">
                      <a href="{{ route('admin.pointSiswa.inputPoint', [
                          'id_point' => $data->id_point,
                          'id_siswa' => $siswa->id_siswa,
                          'id_kelas' => $siswa->id_kelas ?? ($siswa->kelas->id_kelas ?? 0),
                          'id_jurusan' => $siswa->id_jurusan ?? ($siswa->jurusan?->id_jurusan ?? 1),
                          'tanggal' => $carbonDate,
                      ]) }}" class="btn btn-sm btn-danger px-3 shadow-sm" style="border-radius: 6px;">
                        <i class="fas fa-plus mr-1"></i> Proses
                      </a>
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <!-- /.card-body -->
          <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
            <a href="/admin/pointSiswa" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
              <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
          </div>
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