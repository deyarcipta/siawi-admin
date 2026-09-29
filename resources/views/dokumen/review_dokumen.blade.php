@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-folder-open text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Detail Dokumen Siswa</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">
            Daftar seluruh berkas digital & dokumen milik siswa
          </p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/dokumen">Data Dokumen</a></li>
          <li class="breadcrumb-item active">Detail</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center flex-wrap">
            <div>
              <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
                <i class="fas fa-list text-primary mr-2"></i> Berkas Dokumen: 
                <span class="text-primary">{{ $dokumen->first()->siswa->nama_siswa ?? 'Siswa' }}</span>
              </h3>
            </div>
            <div class="ml-auto">
              <a href="/admin/dokumen" class="btn btn-outline-secondary btn-sm mr-2" style="border-radius: 8px;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-hover align-middle mb-0">
                <thead>
                  <tr class="bg-light">
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>Nama Siswa</th>
                    <th>Jenis / Nama Dokumen</th>
                    <th style="text-align: center; width: 140px;">Berkas File</th>
                    <th style="width: 120px; text-align: center;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($dokumen as $data)
                    <tr>
                      <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                      <td>
                        <span class="font-weight-600 text-dark">{{ $data->siswa->nama_siswa ?? '-' }}</span>
                        <br><small class="text-muted">Kelas: {{ $data->siswa->kelas->nama_kelas ?? '-' }}</small>
                      </td>
                      <td>
                        <span class="badge badge-info px-2 py-1" style="font-size: 0.82rem; border-radius: 6px;">
                          <i class="fas fa-file-alt mr-1"></i> {{ $data->jenis_dokumen }}
                        </span>
                      </td>
                      <td class="text-center">
                        <a href="{{ asset("storage/file_dokumen/$data->file_dokumen") }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-danger btn-sm" style="border-radius: 6px; font-weight: 500; font-size: 0.78rem;">
                          <i class="fas fa-file-pdf mr-1"></i> Buka PDF
                        </a>
                      </td>
                      <td class="text-center">
                        <div class="d-inline-flex align-items-center justify-content-center" style="gap: 6px;">
                          <a href="/admin/dokumen/{{$data->id_dokumen}}/edit" class="btn-action btn-action-edit" title="Edit Dokumen">
                            <i class="fas fa-edit"></i>
                          </a>
                          <form action="/admin/dokumen/{{$data->id_dokumen}}" method="POST" class="d-inline form-delete-dokumen" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-action-delete" title="Hapus Dokumen">
                              <i class="fas fa-trash-alt"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center py-4 text-muted">
                        <i class="fas fa-folder-open fa-2x mb-2 text-muted" style="opacity: 0.4;"></i>
                        <p class="mb-0">Belum ada dokumen yang diunggah untuk siswa ini.</p>
                      </td>
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
</div>
@endsection