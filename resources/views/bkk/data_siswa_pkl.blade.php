@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-clipboard-list text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Rekapitulasi & Monitoring Siswa PKL</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Pemantauan menyeluruh penempatan magang kerja industri seluruh siswa dan kelas</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="#">BKK & Hubin</a></li>
          <li class="breadcrumb-item active">Rekap Siswa PKL</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Content -->
<div class="content">
  <div class="container-fluid">

    <!-- Stat Summary Cards -->
    <div class="row mb-3">
      <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon bg-success text-white" style="border-radius: 10px;"><i class="fas fa-running"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">SEDANG AKTIF PKL</span>
            <span class="info-box-number text-dark font-weight-bold" style="font-size: 1.35rem;">{{ $totalPklAktif ?? 0 }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Siswa</small></span>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon text-white" style="background-color: #0284c7; border-radius: 10px;"><i class="fas fa-calendar-check"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">SUDAH DITEMPATKAN</span>
            <span class="info-box-number text-dark font-weight-bold" style="font-size: 1.35rem;">{{ $totalPklDitempatkan ?? 0 }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Siswa</small></span>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon bg-secondary text-white" style="border-radius: 10px;"><i class="fas fa-user-check"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">SELESAI PKL</span>
            <span class="info-box-number text-dark font-weight-bold" style="font-size: 1.35rem;">{{ $totalPklSelesai ?? 0 }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Siswa</small></span>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-12 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon bg-primary text-white" style="border-radius: 10px;"><i class="fas fa-building"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">TOTAL MITRA DU/DI</span>
            <span class="info-box-number text-dark font-weight-bold" style="font-size: 1.35rem;">{{ $totalMitra ?? 0 }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Perusahaan</small></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Card -->
    <div class="card shadow-sm border-0 mb-3" style="border-radius: 14px;">
      <div class="card-body p-3">
        <form action="{{ route('admin.siswaPkl.index') }}" method="GET" class="form-row align-items-end">
          <div class="col-md-4 col-sm-6 mb-2">
            <label class="font-weight-bold text-dark small mb-1"><i class="fas fa-building text-primary mr-1"></i> Filter Perusahaan</label>
            <select name="id_perusahaan" class="form-control form-control-sm select2" style="border-radius: 6px;">
              <option value="">-- Semua Perusahaan --</option>
              @foreach($perusahaan as $item)
                <option value="{{ $item->id_perusahaan }}" {{ request('id_perusahaan') == $item->id_perusahaan ? 'selected' : '' }}>
                  {{ $item->nama_perusahaan }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3 col-sm-6 mb-2">
            <label class="font-weight-bold text-dark small mb-1"><i class="fas fa-chalkboard text-info mr-1"></i> Filter Kelas</label>
            <select name="id_kelas" class="form-control form-control-sm" style="border-radius: 6px;">
              <option value="">-- Semua Kelas --</option>
              @foreach($kelasList as $kelas)
                <option value="{{ $kelas->id_kelas }}" {{ request('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                  {{ $kelas->nama_kelas }}
                </option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3 col-sm-6 mb-2">
            <label class="font-weight-bold text-dark small mb-1"><i class="fas fa-filter text-warning mr-1"></i> Filter Status</label>
            <select name="status" class="form-control form-control-sm" style="border-radius: 6px;">
              <option value="">-- Semua Status --</option>
              <option value="belum_mulai" {{ request('status') == 'belum_mulai' ? 'selected' : '' }}>Sudah Ditempatkan (Belum Mulai)</option>
              <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Sedang PKL (Aktif Berjalan)</option>
              <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
          </div>
          <div class="col-md-2 col-sm-6 mb-2 d-flex">
            <button type="submit" class="btn btn-primary btn-sm flex-fill mr-1 font-weight-bold" style="border-radius: 6px;">
              <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('admin.siswaPkl.index') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 6px;" title="Reset Filter">
              <i class="fas fa-redo"></i>
            </a>
          </div>
        </form>
      </div>
    </div>

    <!-- Main Table Card -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 d-flex flex-wrap align-items-center">
            <h3 class="card-title text-dark font-weight-bold mb-0">
              <i class="fas fa-table text-primary mr-2"></i> Data Seluruh Siswa Magang PKL
            </h3>
            <div class="ml-auto mt-2 mt-sm-0">
              <a href="{{ route('admin.perusahaan.index') }}" class="btn btn-outline-primary btn-sm px-3 mr-2 font-weight-bold" style="border-radius: 8px;">
                <i class="fas fa-building mr-1"></i> Plotting via Mitra DU/DI
              </a>
              <button class="btn btn-success btn-sm px-3 shadow-sm font-weight-bold" style="border-radius: 8px;" data-toggle="modal" data-target="#modalTambahSiswaPkl">
                <i class="fas fa-plus mr-1"></i> Tambah Manual
              </button>
            </div>
          </div>

          <div class="card-body p-3">
            <div class="table-responsive">
              <table id="example2" class="table table-bordered table-hover table-striped align-middle">
                <thead class="bg-light text-dark">
                  <tr>
                    <th style="width: 10px" class="text-center">No</th>
                    <th>Nama Siswa & NISN</th>
                    <th>Kelas</th>
                    <th>Perusahaan Mitra DU/DI</th>
                    <th>Periode PKL</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" style="width: 130px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($data_siswa_pkl as $data)
                  <tr>
                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                    <td>
                      <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                        {{ $data->siswa->nama_siswa ?? 'Siswa Tidak Ditemukan' }}
                      </div>
                      <small class="text-muted">NISN: {{ $data->siswa->nisn ?? $data->siswa->nis ?? '-' }}</small>
                    </td>
                    <td>
                      <span class="badge badge-light border text-dark font-weight-normal px-2 py-1">
                        {{ $data->kelas->nama_kelas ?? ($data->siswa->kelas->nama_kelas ?? '-') }}
                      </span>
                    </td>
                    <td>
                      <div class="font-weight-500 text-dark">
                        <i class="fas fa-building text-primary mr-1"></i>
                        {{ $data->perusahaan->nama_perusahaan ?? 'Perusahaan Tidak Ditemukan' }}
                      </div>
                      <small class="text-muted d-block" style="font-size: 0.78rem;">
                        {{ $data->perusahaan->alamat_perusahaan ?? '' }}
                      </small>
                    </td>
                    <td>
                      <div style="font-size: 0.85rem;" class="text-dark font-weight-500">
                        <i class="fas fa-calendar-alt text-info mr-1"></i>
                        {{ \Carbon\Carbon::parse($data->tanggal_mulai)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($data->tanggal_selesai)->translatedFormat('d M Y') }}
                      </div>
                    </td>
                    <td class="text-center">
                      @if($data->status_pkl === 'belum_mulai')
                        <span class="badge px-2 py-1 font-weight-bold text-white shadow-sm" style="background-color: #0284c7; border-radius: 6px;">
                          <i class="fas fa-calendar-check mr-1"></i> Sudah Ditempatkan
                        </span>
                      @elseif($data->status_pkl === 'aktif')
                        <span class="badge badge-success px-2 py-1 font-weight-bold shadow-sm" style="border-radius: 6px;">
                          <i class="fas fa-running mr-1"></i> Sedang PKL
                        </span>
                      @else
                        <span class="badge badge-secondary px-2 py-1 font-weight-bold" style="border-radius: 6px;">
                          <i class="fas fa-check-circle mr-1"></i> Selesai
                        </span>
                      @endif
                    </td>
                    <td class="text-center">
                      <div class="btn-group" role="group">
                        @if($data->status_pkl !== 'selesai')
                          <!-- Form Quick Selesai -->
                          <form action="{{ route('admin.siswaPkl.update', $data->id_siswa_pkl) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="quick_status" value="1">
                            <input type="hidden" name="status" value="selesai">
                            <button type="submit" class="btn btn-sm btn-outline-success px-2" title="Tandai PKL Selesai" onclick="return confirm('Tandai siswa ini telah selesai PKL?')">
                              <i class="fas fa-check"></i>
                            </button>
                          </form>
                        @endif

                        <!-- Tombol Edit Modal -->
                        <button 
                          type="button" 
                          class="btn btn-sm btn-warning text-white btn-edit px-2 ml-1" 
                          data-toggle="modal" 
                          data-target="#modalEditSiswaPkl"
                          data-id="{{ $data->id_siswa_pkl }}"
                          data-id_kelas="{{ $data->id_kelas ?? ($data->siswa->id_kelas ?? '') }}"
                          data-id_siswa="{{ $data->id_siswa }}"
                          data-id_perusahaan="{{ $data->id_perusahaan }}"
                          data-tanggal_mulai="{{ $data->tanggal_mulai }}"
                          data-tanggal_selesai="{{ $data->tanggal_selesai }}"
                          data-status="{{ $data->status }}"
                          title="Edit Data Penempatan"
                        >
                          <i class="fa fa-edit"></i>
                        </button>

                        <!-- Tombol Hapus -->
                        <button 
                          type="button" 
                          class="btn btn-sm btn-danger btn-delete-swal px-2 ml-1" 
                          data-id="{{ $data->id_siswa_pkl }}"
                          data-nama="{{ $data->siswa->nama_siswa ?? 'Siswa' }}"
                          data-action="{{ route('admin.siswaPkl.destroy', $data->id_siswa_pkl) }}"
                          title="Hapus Penempatan"
                        >
                          <i class="fa fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="7" class="text-center py-4 text-muted">
                      <i class="fas fa-user-tie fa-2x mb-2 text-secondary d-block"></i>
                      Tidak ada data siswa PKL yang sesuai kriteria filter.
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

<!-- ========================================================================= -->
<!-- MODAL TAMBAH SISWA PKL                                                    -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalTambahSiswaPkl" tabindex="-1" role="dialog" aria-labelledby="modalTambahSiswaPklLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form action="{{ route('admin.siswaPkl.store') }}" method="POST">
      @csrf
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
        <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #1d72fe 0%, #0b1f3a 100%);">
          <h5 class="modal-title font-weight-bold" id="modalTambahSiswaPklLabel">
            <i class="fas fa-user-plus mr-2"></i> Tambah Penempatan Siswa PKL
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body p-4">
          <div class="form-group mb-3">
            <label class="font-weight-bold text-dark small">Pilih Perusahaan Mitra <span class="text-danger">*</span></label>
            <select name="id_perusahaan" class="form-control select2" required style="width: 100%;">
              <option value="">-- Pilih Perusahaan --</option>
              @foreach($perusahaan as $item)
                <option value="{{ $item->id_perusahaan }}">{{ $item->nama_perusahaan }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group mb-3">
            <label class="font-weight-bold text-dark small">
              Pilih Siswa <span class="text-danger">*</span>
              <small class="text-muted font-weight-normal">(Bisa pilih multiple siswa)</small>
            </label>
            <select name="id_siswa[]" class="form-control select2" multiple required data-placeholder="Cari siswa atau kelas..." style="width: 100%;">
              @foreach($siswaList as $siswa)
                <option value="{{ $siswa->id_siswa }}">
                  {{ $siswa->nama_siswa }} - {{ $siswa->kelas->nama_kelas ?? 'Tanpa Kelas' }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6 mb-3">
              <label class="font-weight-bold text-dark small">Tanggal Mulai <span class="text-danger">*</span></label>
              <input type="date" class="form-control" name="tanggal_mulai" value="{{ date('Y-m-d') }}" required style="border-radius: 8px;">
            </div>
            <div class="form-group col-md-6 mb-3">
              <label class="font-weight-bold text-dark small">Tanggal Selesai <span class="text-danger">*</span></label>
              <input type="date" class="form-control" name="tanggal_selesai" value="{{ date('Y-m-d', strtotime('+3 months')) }}" required style="border-radius: 8px;">
            </div>
          </div>

          <input type="hidden" name="status" value="PKL">
        </div>

        <div class="modal-footer bg-light py-3 px-4">
          <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
          <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
            <i class="fas fa-save mr-1"></i> Simpan Penempatan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL EDIT SISWA PKL                                                      -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalEditSiswaPkl" tabindex="-1" role="dialog" aria-labelledby="modalEditSiswaPklLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form id="formEditSiswaPkl" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
        <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #1d72fe 0%, #0b1f3a 100%);">
          <h5 class="modal-title font-weight-bold" id="modalEditSiswaPklLabel">
            <i class="fas fa-edit mr-2"></i> Edit Data Siswa PKL
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body p-4">
          <div class="form-group mb-3">
            <label class="font-weight-bold text-dark small">Perusahaan Mitra <span class="text-danger">*</span></label>
            <select name="id_perusahaan" id="edit_id_perusahaan" class="form-control" required style="border-radius: 8px;">
              <option value="">-- Pilih Perusahaan --</option>
              @foreach($perusahaan as $item)
                <option value="{{ $item->id_perusahaan }}">{{ $item->nama_perusahaan }}</option>
              @endforeach
            </select>
          </div>

          <div class="form-group mb-3">
            <label class="font-weight-bold text-dark small">Nama Siswa <span class="text-danger">*</span></label>
            <select name="id_siswa" id="edit_id_siswa" class="form-control" required style="border-radius: 8px;">
              <option value="">-- Pilih Siswa --</option>
              @foreach($siswaList as $siswa)
                <option value="{{ $siswa->id_siswa }}">
                  {{ $siswa->nama_siswa }} - {{ $siswa->kelas->nama_kelas ?? 'Tanpa Kelas' }}
                </option>
              @endforeach
            </select>
          </div>

          <div class="form-row">
            <div class="form-group col-md-6 mb-3">
              <label class="font-weight-bold text-dark small">Tanggal Mulai <span class="text-danger">*</span></label>
              <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" class="form-control" required style="border-radius: 8px;">
            </div>
            <div class="form-group col-md-6 mb-3">
              <label class="font-weight-bold text-dark small">Tanggal Selesai <span class="text-danger">*</span></label>
              <input type="date" name="tanggal_selesai" id="edit_tanggal_selesai" class="form-control" required style="border-radius: 8px;">
            </div>
          </div>

          <div class="form-group mb-0">
            <label class="font-weight-bold text-dark small">Status Pelaksanaan <span class="text-danger">*</span></label>
            <select name="status" id="edit_status" class="form-control" required style="border-radius: 8px;">
              <option value="PKL">Sedang PKL</option>
              <option value="selesai">Selesai</option>
            </select>
          </div>
        </div>

        <div class="modal-footer bg-light py-3 px-4">
          <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
          <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
            <i class="fas fa-save mr-1"></i> Update Data
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
  // Tombol Edit Siswa PKL
  $('.btn-edit').on('click', function () {
    let id = $(this).data('id');
    let idSiswa = $(this).data('id_siswa');
    let idPerusahaan = $(this).data('id_perusahaan');
    let tglMulai = $(this).data('tanggal_mulai');
    let tglSelesai = $(this).data('tanggal_selesai');
    let status = $(this).data('status');

    $('#edit_id_siswa').val(idSiswa);
    $('#edit_id_perusahaan').val(idPerusahaan);
    $('#edit_tanggal_mulai').val(tglMulai);
    $('#edit_tanggal_selesai').val(tglSelesai);
    $('#edit_status').val(status);

    $('#formEditSiswaPkl').attr('action', '/admin/siswaPkl/' + id);
  });

  // Delete with SweetAlert
  $('.btn-delete-swal').click(function (e) {
    e.preventDefault();
    let actionUrl = $(this).data('action');
    let namaSiswa = $(this).data('nama') || 'siswa ini';

    Swal.fire({
      title: 'Hapus Penempatan PKL?',
      html: `Yakin ingin menghapus data PKL siswa <b>${namaSiswa}</b>?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        let form = $('<form>', { method: 'POST', action: actionUrl });
        form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
        form.append($('<input>', { type: 'hidden', name: '_method', value: 'DELETE' }));
        $('body').append(form);
        form.submit();
      }
    });
  });
});
</script>
@endpush