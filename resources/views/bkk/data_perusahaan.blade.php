@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0">Data Perusahaan</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Data Perusahaan</a></li>
          <li class="breadcrumb-item active">Data Perusahaan</li>
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
            <h3 class="card-title">Data Perusahaan</h3>
            <button class="btn btn-success ml-auto" data-toggle="modal" data-target="#modalTambahPerusahaan">
              Tambah Perusahaan
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
                @foreach ($perusahaan as $data)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $data->nama_perusahaan }}</td>
                  <td>{{ $data->alamat_perusahaan }}</td>
                  <td>{{ $data->penanggung_jawab }}</td>
                  <td>{{ $data->penanggung_jawab }}</td>
                  <td>
                    <form action="{{ route('admin.perusahaan.destroy', $data->id_perusahaan) }}" method="POST">
                      <a href="{{ route('admin.perusahaan.edit', $data->id_perusahaan) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
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

<!-- Modal Tambah Perusahaan -->
<div class="modal fade" id="modalTambahPerusahaan" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="{{ route('admin.perusahaan.store') }}" method="POST">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Tambah Perusahaan</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          <div class="form-group">
            <label for="nama_perusahaan">Nama Perusahaan</label>
            <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" required>
          </div>
          <div class="form-group">
            <label for="alamat_perusahaan">Alamat Perusahaan</label>
            <input type="text" class="form-control" id="alamat_perusahaan" name="alamat_perusahaan" required>
          </div>
          <div class="form-group">
            <label for="penanggung_jawab">Penanggung Jawab</label>
            <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection
