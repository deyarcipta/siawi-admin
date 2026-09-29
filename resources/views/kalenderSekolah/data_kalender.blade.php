@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-calendar-alt text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Kalender Akademik Sekolah</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Agenda kegiatan belajar mengajar, libur nasional, dan ujian sekolah</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item active">Kalender Sekolah</li>
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
                <i class="fas fa-table text-primary mr-2"></i> Data Agenda Kalender Sekolah
              </h3>
              <a href="/admin/kalender/create" class="btn btn-success btn-sm ml-auto mt-2 mt-md-0">
                <i class="fas fa-plus mr-1"></i> Tambah Agenda
              </a>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover align-middle">
                  <thead>
                    <tr>
                      <th style="width: 10px" class="text-center">No</th>
                      <th>Kegiatan / Agenda</th>
                      <th style="width: 160px" class="text-center">Tanggal Mulai</th>
                      <th style="width: 160px" class="text-center">Tanggal Selesai</th>
                      <th style="width: 110px" class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($kalender as $data)
                    <tr>
                      <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                      <td>
                        <div class="font-weight-bold text-dark" style="font-size: 0.88rem;">{{ $data->kegiatan }}</div>
                      </td>
                      <td class="text-center">
                        <span class="text-dark font-weight-500" style="font-size: 0.84rem;">
                          {{ $data->tgl_mulai ? \Carbon\Carbon::parse($data->tgl_mulai)->translatedFormat('d M Y') : '-' }}
                        </span>
                      </td>
                      <td class="text-center">
                        <span class="text-dark font-weight-500" style="font-size: 0.84rem;">
                          {{ $data->tgl_akhir ? \Carbon\Carbon::parse($data->tgl_akhir)->translatedFormat('d M Y') : '-' }}
                        </span>
                      </td>
                      <td class="text-center">
                        <div class="d-inline-flex align-items-center" style="gap: 5px;">
                          <a href="/admin/kalender/{{$data->id}}/edit" class="btn-action btn-action-edit" title="Edit Agenda">
                            <i class="fa fa-pencil-alt"></i>
                          </a>
                          <form action="/admin/kalender/{{$data->id}}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-action-delete" title="Hapus Agenda">
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