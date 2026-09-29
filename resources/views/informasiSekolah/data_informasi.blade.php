@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-bullhorn text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Informasi & Pengumuman Sekolah</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Publikasi pengumuman penting kepada siswa, guru, dan wali murid</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Informasi Sekolah</li>
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
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
              <h3 class="card-title text-dark font-weight-bold mb-0">
                <i class="fas fa-table text-primary mr-2"></i> Data Informasi & Pengumuman
              </h3>
              <a href="/admin/informasi/create" class="btn btn-success btn-sm ml-auto mt-2 mt-md-0">
                <i class="fas fa-plus mr-1"></i> Tambah Informasi
              </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover align-middle">
                  <thead>
                    <tr>
                      <th style="width: 10px" class="text-center">No</th>
                      <th>Informasi / Pengumuman</th>
                      <th style="width: 150px" class="text-center">Tanggal Awal</th>
                      <th style="width: 150px" class="text-center">Tanggal Akhir</th>
                      <th style="width: 160px" class="text-center">File Edaran</th>
                      <th style="width: 110px" class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($informasi as $data)
                    <tr>
                      <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                      <td>
                        <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $data->informasi }}</div>
                      </td>
                      <td class="text-center">
                        <span class="text-dark font-weight-500" style="font-size: 0.84rem;">
                          {{ $data->tanggal_awal ? \Carbon\Carbon::parse($data->tanggal_awal)->translatedFormat('d M Y') : '-' }}
                        </span>
                      </td>
                      <td class="text-center">
                        <span class="text-dark font-weight-500" style="font-size: 0.84rem;">
                          {{ $data->tanggal_akhir ? \Carbon\Carbon::parse($data->tanggal_akhir)->translatedFormat('d M Y') : '-' }}
                        </span>
                      </td>
                      <td class="text-center">
                        @if($data->file)
                          <a href="{{ asset("storage/file-informasi/$data->file") }}" target="_blank" class="btn btn-outline-danger btn-sm px-2 py-1 font-weight-600" style="border-radius: 8px; font-size: 0.78rem;">
                            <i class="fas fa-file-pdf text-danger mr-1"></i> Lihat Dokumen
                          </a>
                        @else
                          <span class="text-muted small">-</span>
                        @endif
                      </td>
                      <td class="text-center">
                        <div class="d-inline-flex align-items-center" style="gap: 5px;">
                          <a href="/admin/informasi/{{$data->id}}/edit" class="btn-action btn-action-edit" title="Edit Informasi">
                            <i class="fa fa-pencil-alt"></i>
                          </a>
                          <form action="/admin/informasi/{{$data->id}}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus informasi ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-action-delete" title="Hapus Informasi">
                              <i class="fa fa-trash"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card --> 
        </div>
      </div>
    </div>
  </div>
@endsection