@extends($layout)

@section('content')
<!-- Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-folder-open text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Dokumen Siswa</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Arsip berkas digital, sertifikat, dan dokumen kelengkapan siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item active">Data Dokumen</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="content">
  <div class="container-fluid">
    <!-- Filter Card -->
    <div class="row mb-3">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0 mb-3" style="border-radius: 12px;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
            <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 0.95rem;">
              <i class="fas fa-sliders-h text-primary mr-2"></i> Filter Dokumen Per Kelas
            </h3>
          </div>
          <div class="card-body pt-0 pb-3">
            <form action="/admin/dokumen" method="GET">
              <div class="row align-items-end">
                <div class="form-group col-md-5 mb-0">
                  <label for="kelas" class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Pilih Kelas</label>
                  <select class="form-control" id="kelas" name="kelas" onchange="this.form.submit()">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelas as $kls)
                      <option value="{{ $kls->id_kelas }}" {{ $kelasId == $kls->id_kelas ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group col-md-3 mb-0 mt-2 mt-md-0 d-flex">
                  <button type="submit" class="btn btn-primary mr-2"><i class="fas fa-search mr-1"></i> Tampilkan</button>
                  @if(!empty($kelasId))
                    <a href="/admin/dokumen" class="btn btn-outline-secondary"><i class="fas fa-undo mr-1"></i> Reset</a>
                  @endif
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Tabel Dokumen Per Siswa -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0" style="border-radius: 12px;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center flex-wrap">
            <div>
              <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
                <i class="fas fa-table text-primary mr-2"></i> Data Dokumen Siswa
              </h3>
              @if($dataKelas)
                <span class="badge badge-primary ml-2 px-2 py-1" style="font-size: 0.78rem;">Kelas: {{ $dataKelas->nama_kelas }}</span>
              @endif
            </div>
            <div class="ml-auto">
              <button type="button" class="btn btn-success btn-sm shadow-sm" data-toggle="modal" data-target="#modalTambahDokumen" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-plus mr-1"></i> Tambah Dokumen
              </button>
            </div>
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table id="example1" class="table table-bordered table-hover align-middle mb-0">
                <thead>
                  <tr class="bg-light">
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th style="text-align: center; width: 140px;">Jumlah Dokumen</th>
                    <th style="width: 100px; text-align: center;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($siswa as $data)
                    <tr>
                      <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                      <td>
                        <span class="font-weight-600 text-dark">{{ $data->nama_siswa }}</span>
                        @if($data->nisn || $data->nis)
                          <br><small class="text-muted">NISN/NIS: {{ $data->nisn ?? $data->nis }}</small>
                        @endif
                      </td>
                      <td>
                        <span class="badge badge-light border text-secondary px-2 py-1" style="font-size: 0.78rem;">
                          {{ $data->kelas->nama_kelas ?? '-' }}
                        </span>
                      </td>
                      <td class="text-center">
                        @php $docCount = $data->dokumen->count() ?? 0; @endphp
                        @if($docCount > 0)
                          <span class="badge badge-success px-2 py-1" style="font-size: 0.80rem; border-radius: 6px;">
                            <i class="fas fa-file-alt mr-1"></i> {{ $docCount }} Dokumen
                          </span>
                        @else
                          <span class="badge badge-secondary px-2 py-1" style="font-size: 0.78rem; opacity: 0.7; border-radius: 6px;">
                            0 Dokumen
                          </span>
                        @endif
                      </td>
                      <td class="text-center">
                        <div class="d-inline-flex align-items-center justify-content-center">
                          <a href="/admin/dokumen/{{$data->id_siswa}}" class="btn-action btn-action-view" title="Lihat & Kelola Dokumen">
                            <i class="fas fa-folder-open"></i>
                          </a>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="5" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2 text-muted" style="opacity: 0.4;"></i>
                        <p class="mb-0">Tidak ada data siswa ditemukan.</p>
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

<!-- Modal Tambah Dokumen -->
<div class="modal fade" id="modalTambahDokumen" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
      <form action="/admin/dokumen" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #1d72fe 0%, #0b1f3a 100%);">
          <h5 class="modal-title font-weight-bold" id="modalLabel">
            <i class="fas fa-file-upload mr-2"></i> Tambah Dokumen Siswa
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">
          <div class="form-group mb-3">
            <label for="id_siswa" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Pilih Siswa <span class="text-danger">*</span></label>
            <select name="id_siswa" id="id_siswa" class="form-control" required style="border-radius: 8px; height: 42px;">
              <option value="">-- Pilih Siswa --</option>
              @foreach($allSiswa as $item)
                <option value="{{ $item->id_siswa }}">
                  {{ $item->nama_siswa }} ({{ $item->kelas->nama_kelas ?? 'Tanpa Kelas' }})
                </option>
              @endforeach
            </select>
          </div>
          <div class="form-group mb-3">
            <label for="jenis_dokumen" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Jenis / Nama Dokumen <span class="text-danger">*</span></label>
            <input type="text" name="jenis_dokumen" id="jenis_dokumen" class="form-control" placeholder="Contoh: Ijazah SMP, Akta Kelahiran, Sertifikat PKL" required style="border-radius: 8px; height: 42px;">
          </div>
          <div class="form-group mb-0">
            <label for="file_dokumen" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Upload Berkas (Format PDF) <span class="text-danger">*</span></label>
            <input type="file" name="file_dokumen" id="file_dokumen" class="form-control-file p-2 border" accept=".pdf" required style="border-radius: 8px; background: #f8fafc;">
            <small class="text-muted mt-1 d-block"><i class="fas fa-info-circle mr-1"></i> File harus berformat PDF dengan ukuran maksimal 5MB.</small>
          </div>
        </div>
        <div class="modal-footer bg-light py-3 px-4">
          <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
          <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
            <i class="fas fa-save mr-1"></i> Simpan Dokumen
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
