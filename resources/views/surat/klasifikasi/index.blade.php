@extends($layout)

@section('content')
<!-- Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-tags text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Klasifikasi Surat</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Master data kode dan kategori peruntukan klasifikasi penomoran surat sekolah</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/surat-keluar" class="text-primary font-weight-500">Agenda Surat</a></li>
          <li class="breadcrumb-item active">Klasifikasi Surat</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="content">
  <div class="container-fluid">

    <!-- Flash Messages -->
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
        <i class="fas fa-exclamation-triangle mr-2"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
        <i class="fas fa-exclamation-circle mr-2"></i> <strong>Terdapat kesalahan input:</strong>
        <ul class="mb-0 mt-1 pl-3">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <!-- Action Bar & Card -->
    <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px;">
      <div class="card-header bg-white py-3 border-0 d-flex flex-wrap align-items-center justify-content-between">
        <div class="d-flex align-items-center mb-2 mb-md-0">
          <h5 class="m-0 font-weight-bold text-dark">
            <i class="fas fa-list text-primary mr-2"></i> Daftar Klasifikasi Surat
          </h5>
          <span class="badge badge-pill badge-light border ml-2 px-3 py-1 font-weight-bold text-muted">
            Total: {{ $klasifikasi->total() }} Data
          </span>
        </div>

        <div class="d-flex align-items-center flex-wrap" style="gap: 10px;">
          <!-- Search Form -->
          <form action="{{ route('admin.klasifikasi-surat.index') }}" method="GET" class="d-flex align-items-center">
            <div class="input-group input-group-sm" style="width: 240px;">
              <input type="text" name="search" class="form-control rounded-left" placeholder="Cari kode / nama..." value="{{ $search }}">
              <div class="input-group-append">
                <button type="submit" class="btn btn-primary rounded-right px-3">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
            @if(!empty($search))
              <a href="{{ route('admin.klasifikasi-surat.index') }}" class="btn btn-sm btn-outline-secondary ml-2" title="Reset Filter">
                <i class="fas fa-times"></i>
              </a>
            @endif
          </form>

          <!-- Button Tambah -->
          <button type="button" class="btn btn-sm btn-primary font-weight-bold shadow-sm px-3" data-toggle="modal" data-target="#modalTambah" style="border-radius: 8px;">
            <i class="fas fa-plus mr-1"></i> Tambah Klasifikasi
          </button>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
            <thead class="bg-light text-muted text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th class="text-center py-3" style="width: 60px;">No</th>
                <th class="py-3" style="width: 140px;">Kode Surat</th>
                <th class="py-3">Nama Klasifikasi / Peruntukan</th>
                <th class="py-3">Keterangan</th>
                <th class="text-center py-3" style="width: 90px;">Urutan</th>
                <th class="text-center py-3" style="width: 110px;">Status</th>
                <th class="text-center py-3" style="width: 140px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($klasifikasi as $index => $item)
                <tr>
                  <td class="text-center font-weight-bold text-muted">
                    {{ $klasifikasi->firstItem() + $index }}
                  </td>
                  <td>
                    <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 0.85rem; letter-spacing: 0.5px;">
                      {{ $item->kode }}
                    </span>
                  </td>
                  <td class="font-weight-600 text-dark">
                    {{ $item->nama }}
                  </td>
                  <td class="text-muted" style="font-size: 0.85rem;">
                    {{ $item->keterangan ?? '-' }}
                  </td>
                  <td class="text-center font-weight-bold text-muted">
                    {{ $item->urutan }}
                  </td>
                  <td class="text-center">
                    @if($item->is_active)
                      <span class="badge badge-success px-2 py-1 font-weight-bold"><i class="fas fa-check-circle mr-1"></i> Aktif</span>
                    @else
                      <span class="badge badge-secondary px-2 py-1 font-weight-bold"><i class="fas fa-ban mr-1"></i> Nonaktif</span>
                    @endif
                  </td>
                  <td class="text-center">
                    <div class="btn-group btn-group-sm" role="group">
                      <!-- Edit Button -->
                      <button type="button" class="btn btn-outline-info btn-edit" 
                        data-toggle="modal" 
                        data-target="#modalEdit"
                        data-id="{{ $item->id }}"
                        data-kode="{{ $item->kode }}"
                        data-nama="{{ $item->nama }}"
                        data-keterangan="{{ $item->keterangan }}"
                        data-urutan="{{ $item->urutan }}"
                        data-is_active="{{ $item->is_active ? '1' : '0' }}"
                        title="Edit Klasifikasi">
                        <i class="fas fa-edit"></i>
                      </button>

                      <!-- Delete Form -->
                      <form action="{{ route('admin.klasifikasi-surat.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus klasifikasi {{ $item->kode }}?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger" title="Hapus Klasifikasi">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="text-center py-5 text-muted">
                    <i class="fas fa-folder-open mb-2" style="font-size: 2.5rem; color: #cbd5e1;"></i>
                    <p class="mb-0 font-weight-500">Belum ada data klasifikasi surat.</p>
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if($klasifikasi->hasPages())
        <div class="card-footer bg-white py-3 border-0 d-flex justify-content-end">
          {{ $klasifikasi->links() }}
        </div>
      @endif
    </div>

  </div>
</div>

<!-- MODAL TAMBAH KLASIFIKASI -->
<div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow" style="border-radius: 14px;">
      <form action="{{ route('admin.klasifikasi-surat.store') }}" method="POST">
        @csrf
        <div class="modal-header bg-primary text-white border-0" style="border-top-left-radius: 14px; border-top-right-radius: 14px;">
          <h5 class="modal-title font-weight-bold" id="modalTambahLabel">
            <i class="fas fa-plus-circle mr-2"></i> Tambah Klasifikasi Surat
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">
          
          <div class="form-group mb-3">
            <label class="font-weight-bold text-dark" style="font-size: 0.88rem;">Kode Klasifikasi <span class="text-danger">*</span></label>
            <input type="text" name="kode" class="form-control text-uppercase" placeholder="Contoh: UND-ORTU, SK-GURU, PKL" required style="border-radius: 8px;">
            <small class="form-text text-muted">Kode singkat yang akan masuk ke dalam format nomor surat resmi.</small>
          </div>

          <div class="form-group mb-3">
            <label class="font-weight-bold text-dark" style="font-size: 0.88rem;">Nama Klasifikasi / Peruntukan <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" placeholder="Contoh: Surat Undangan Rapat Wali Murid" required style="border-radius: 8px;">
          </div>

          <div class="form-group mb-3">
            <label class="font-weight-bold text-dark" style="font-size: 0.88rem;">Keterangan (Opsional)</label>
            <textarea name="keterangan" class="form-control" rows="2" placeholder="Catatan tambahan mengenai penggunaan klasifikasi ini..." style="border-radius: 8px;"></textarea>
          </div>

          <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label class="font-weight-bold text-dark" style="font-size: 0.88rem;">Nomor Urutan</label>
              <input type="number" name="urutan" class="form-control" value="0" min="0" style="border-radius: 8px;">
              <small class="form-text text-muted">Urutan tampilan pada dropdown.</small>
            </div>
            <div class="col-md-6 form-group mb-3 d-flex flex-column justify-content-center">
              <label class="font-weight-bold text-dark" style="font-size: 0.88rem;">Status Aktif</label>
              <div class="custom-control custom-switch mt-1">
                <input type="checkbox" class="custom-control-input" id="tambahActive" name="is_active" value="1" checked>
                <label class="custom-control-label font-weight-500" for="tambahActive">Aktif / Dapat Dipilih</label>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer bg-light border-0 py-3" style="border-bottom-left-radius: 14px; border-bottom-right-radius: 14px;">
          <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
          <button type="submit" class="btn btn-primary font-weight-bold px-4 shadow-sm" style="border-radius: 8px;">
            <i class="fas fa-save mr-1"></i> Simpan Klasifikasi
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- MODAL EDIT KLASIFIKASI -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow" style="border-radius: 14px;">
      <form id="formEdit" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header bg-info text-white border-0" style="border-top-left-radius: 14px; border-top-right-radius: 14px;">
          <h5 class="modal-title font-weight-bold" id="modalEditLabel">
            <i class="fas fa-edit mr-2"></i> Edit Klasifikasi Surat
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-4">
          
          <div class="form-group mb-3">
            <label class="font-weight-bold text-dark" style="font-size: 0.88rem;">Kode Klasifikasi <span class="text-danger">*</span></label>
            <input type="text" name="kode" id="editKode" class="form-control text-uppercase" required style="border-radius: 8px;">
          </div>

          <div class="form-group mb-3">
            <label class="font-weight-bold text-dark" style="font-size: 0.88rem;">Nama Klasifikasi / Peruntukan <span class="text-danger">*</span></label>
            <input type="text" name="nama" id="editNama" class="form-control" required style="border-radius: 8px;">
          </div>

          <div class="form-group mb-3">
            <label class="font-weight-bold text-dark" style="font-size: 0.88rem;">Keterangan (Opsional)</label>
            <textarea name="keterangan" id="editKeterangan" class="form-control" rows="2" style="border-radius: 8px;"></textarea>
          </div>

          <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label class="font-weight-bold text-dark" style="font-size: 0.88rem;">Nomor Urutan</label>
              <input type="number" name="urutan" id="editUrutan" class="form-control" min="0" style="border-radius: 8px;">
            </div>
            <div class="col-md-6 form-group mb-3 d-flex flex-column justify-content-center">
              <label class="font-weight-bold text-dark" style="font-size: 0.88rem;">Status Aktif</label>
              <div class="custom-control custom-switch mt-1">
                <input type="checkbox" class="custom-control-input" id="editActive" name="is_active" value="1">
                <label class="custom-control-label font-weight-500" for="editActive">Aktif / Dapat Dipilih</label>
              </div>
            </div>
          </div>

        </div>
        <div class="modal-footer bg-light border-0 py-3" style="border-bottom-left-radius: 14px; border-bottom-right-radius: 14px;">
          <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
          <button type="submit" class="btn btn-info font-weight-bold text-white px-4 shadow-sm" style="border-radius: 8px;">
            <i class="fas fa-save mr-1"></i> Update Klasifikasi
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
  $('.btn-edit').on('click', function() {
    var id = $(this).data('id');
    var kode = $(this).data('kode');
    var nama = $(this).data('nama');
    var keterangan = $(this).data('keterangan');
    var urutan = $(this).data('urutan');
    var isActive = $(this).data('is_active');

    $('#formEdit').attr('action', '/admin/klasifikasi-surat/' + id);
    $('#editKode').val(kode);
    $('#editNama').val(nama);
    $('#editKeterangan').val(keterangan);
    $('#editUrutan').val(urutan);
    $('#editActive').prop('checked', isActive == '1');
  });
});
</script>
@endpush
@endsection
