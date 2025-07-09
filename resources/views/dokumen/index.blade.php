@extends($layout)

@section('content')
<!-- Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Data Dokumen</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Data Dokumen</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Form Pilih Kelas -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title">Pilih Kelas</h3>
          </div>
          <div class="card-body">
            <form action="/admin/dokumen" method="GET">
              @csrf
              <div class="form-group">
                <label for="kelas">Kelas</label>
                <select class="form-control" id="kelas" name="kelas" required>
                  <option value="">Pilih Kelas</option>
                  @foreach($kelas as $kls)
                    <option value="{{ $kls->id_kelas }}" {{ $kelasId == $kls->id_kelas ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                  @endforeach
                </select>
              </div>
              <button type="submit" class="btn btn-primary">Tampilkan Data</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@if(isset($siswa))
<!-- Tabel Dokumen Per Siswa -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title">Data Dokumen Siswa</h3>
            <button type="button" class="btn btn-success ml-auto" data-toggle="modal" data-target="#modalTambahDokumen">
              Tambah Dokumen
            </button>
          </div>
          <div class="card-body">
            <table class="table table-bordered table-hover mt-2">
              <thead>
                <tr>
                  <th style="width: 10px">No</th>
                  <th>Nama Siswa</th>
                  <th>Jumlah Dokumen</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($siswa as $data)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $data->nama_siswa }}</td>
                    <td>{{ $data->dokumen->count() ?? 0 }}</td>
                    <td>
                      <a href="/admin/dokumen/{{$data->id_siswa}}" class="btn btn-success"><i class="fa fa-eye"></i></a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <input type="hidden" name="kelas_id" value="{{ $dataKelas->id_kelas }}">
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah Dokumen -->
<div class="modal fade" id="modalTambahDokumen" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <form action="{{ route('admin.dokumen.store') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Dokumen Siswa</h5>
          <button type="button" class="close" data-dismiss="modal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="id_siswa">Pilih Siswa</label>
            <select name="id_siswa" class="form-control" required>
              <option value="">Pilih Siswa</option>
              @foreach($siswa as $item)
                <option value="{{ $item->id_siswa }}">{{ $item->nama_siswa }}</option>
              @endforeach
            </select>
          </div>
          <input type="text" name="id_kelas" id="id_kelas" value="{{$kelasId}}" hidden>
          <div class="form-group">
            <label for="jenis_dokumen">Jenis Dokumen</label>
            <input type="text" name="jenis_dokumen" class="form-control" placeholder="Contoh: Kartu Pelajar" required>
          </div>

          <div class="form-group">
            <label for="file_dokumen">Upload Dokumen</label>
            <input type="file" name="file_dokumen" class="form-control-file" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endif
@endsection
