@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-file-medical text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Buat Surat Permohonan PKL Baru</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Input permohonan PKL siswa ke perusahaan mitra secara manual dari kantor BKK</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.pengajuan-pkl.index') }}">Pengajuan Surat PKL</a></li>
          <li class="breadcrumb-item active">Buat Baru</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="card shadow-sm border-0" style="border-radius: 14px;">
      <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between">
        <h5 class="card-title font-weight-bold text-dark mb-0">
          <i class="fas fa-edit text-primary mr-2"></i> Formulir Surat Permohonan PKL
        </h5>
        <a href="{{ route('admin.pengajuan-pkl.index') }}" class="btn btn-secondary btn-sm shadow-sm">
          <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
      </div>

      <form action="{{ route('admin.pengajuan-pkl.store') }}" method="POST">
        @csrf
        <div class="card-body">
          
          <!-- Bagian 1: Data Perusahaan Tujuan -->
          <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-building mr-1"></i> 1. Perusahaan Tujuan &amp; Penerima Surat</h6>
          <div class="row">
            <div class="col-md-6 form-group">
              <label class="font-weight-600 text-dark">Pilih dari Mitra Terdaftar (Opsional)</label>
              <select class="form-control select2" id="selectPerusahaan">
                <option value="">-- Ketik Nama Baru atau Pilih Mitra --</option>
                @foreach($perusahaan as $p)
                  <option value="{{ $p->id_perusahaan }}" 
                          data-nama="{{ $p->nama_perusahaan }}" 
                          data-alamat="{{ $p->alamat }}"
                          data-pic="{{ $p->pic ?: $p->penanggung_jawab }}">
                    {{ $p->nama_perusahaan }} ({{ $p->kota ?: 'Mitra' }})
                  </option>
                @endforeach
              </select>
              <input type="hidden" name="id_perusahaan" id="id_perusahaan">
            </div>
            <div class="col-md-6 form-group">
              <label class="font-weight-600 text-dark">Nama Perusahaan / Tempat PKL <span class="text-danger">*</span></label>
              <input type="text" name="nama_perusahaan" id="nama_perusahaan" class="form-control @error('nama_perusahaan') is-invalid @enderror" value="{{ old('nama_perusahaan') }}" required placeholder="Contoh: Hotel Le Meridian Jakarta">
              @error('nama_perusahaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 form-group">
              <label class="font-weight-600 text-dark">Ditujukan Kepada (Nama Penerima / Pimpinan) <span class="text-danger">*</span></label>
              <input type="text" name="ditujukan_kepada" id="ditujukan_kepada" class="form-control @error('ditujukan_kepada') is-invalid @enderror" value="{{ old('ditujukan_kepada', 'Ibu. Cathleen Abigail') }}" required placeholder="Contoh: Ibu. Cathleen Abigail / Pimpinan HRD">
              @error('ditujukan_kepada')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 form-group">
              <label class="font-weight-600 text-dark">Jabatan Penerima</label>
              <input type="text" name="jabatan_tujuan" class="form-control" value="{{ old('jabatan_tujuan', 'H.R & Learning Manager') }}" placeholder="Contoh: H.R & Learning Manager / HRD Manager">
            </div>
          </div>

          <div class="form-group">
            <label class="font-weight-600 text-dark">Alamat Perusahaan</label>
            <input type="text" name="alamat_perusahaan" id="alamat_perusahaan" class="form-control" value="{{ old('alamat_perusahaan') }}" placeholder="Contoh: Jl. Jend. Sudirman Kav. 18-20, Jakarta">
          </div>

          <hr class="my-4">

          <!-- Bagian 2: Periode & Nomor Surat -->
          <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-calendar-alt mr-1"></i> 2. Periode PKL &amp; Administrasi Surat</h6>
          <div class="row">
            <div class="col-md-6 form-group">
              <label class="font-weight-600 text-dark">Teks Periode PKL (Tampil di Surat) <span class="text-danger">*</span></label>
              <input type="text" name="periode_teks" class="form-control @error('periode_teks') is-invalid @enderror" value="{{ old('periode_teks', 'Januari 2027 – Juni 2027 ( 6 bulan )') }}" required placeholder="Contoh: Januari 2027 – Juni 2027 ( 6 bulan )">
              @error('periode_teks')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-3 form-group">
              <label class="font-weight-600 text-dark">Tanggal Mulai PKL</label>
              <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai') }}">
            </div>
            <div class="col-md-3 form-group">
              <label class="font-weight-600 text-dark">Tanggal Selesai PKL</label>
              <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}">
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 form-group">
              <label class="font-weight-600 text-dark">Nomor Surat Resmi</label>
              <input type="text" name="nomor_surat" class="form-control" value="{{ old('nomor_surat', $suggestedNomorSurat) }}" placeholder="Contoh: 4/OJT/SMK-WI/X/2024">
            </div>
            <div class="col-md-4 form-group">
              <label class="font-weight-600 text-dark">Tanggal Surat</label>
              <input type="date" name="tanggal_surat" class="form-control" value="{{ old('tanggal_surat', now()->toDateString()) }}">
            </div>
            <div class="col-md-4 form-group">
              <label class="font-weight-600 text-dark">Nama Penandatangan</label>
              <input type="text" name="nama_penandatangan" class="form-control" value="{{ old('nama_penandatangan', 'Nanan Supriatna') }}">
            </div>
          </div>

          <hr class="my-4">

          <!-- Bagian 3: Tabel Siswa yang Diajukan -->
          <div class="d-flex align-items-center justify-content-between mb-2">
            <h6 class="font-weight-bold text-primary mb-0"><i class="fas fa-users mr-1"></i> 3. Daftar Siswa yang Diajukan (Kelompok PKL)</h6>
            <button type="button" class="btn btn-success btn-sm font-weight-bold shadow-sm" id="btnTambahBaris">
              <i class="fas fa-user-plus mr-1"></i> Tambah Siswa
            </button>
          </div>
          <p class="text-muted small mb-3">Masukkan nama siswa dan program keahlian yang akan tercetak pada tabel surat permohonan PKL.</p>

          <div class="table-responsive">
            <table class="table table-bordered table-striped" id="tabelSiswaInput">
              <thead class="bg-light">
                <tr>
                  <th style="width: 50px;" class="text-center">No</th>
                  <th>Nama Siswa <span class="text-danger">*</span></th>
                  <th style="width: 250px;">Program Keahlian <span class="text-danger">*</span></th>
                  <th style="width: 140px;">NIS (Opsional)</th>
                  <th style="width: 60px;" class="text-center">Aksi</th>
                </tr>
              </thead>
              <tbody id="siswaContainer">
                <tr class="siswa-row">
                  <td class="text-center row-number font-weight-bold">1</td>
                  <td>
                    <input type="text" name="siswa[0][nama]" class="form-control form-control-sm input-nama" required placeholder="Ketik nama lengkap siswa...">
                    <input type="hidden" name="siswa[0][id_siswa]" class="input-id-siswa">
                  </td>
                  <td>
                    <input type="text" name="siswa[0][program_keahlian]" class="form-control form-control-sm input-jurusan" required placeholder="Contoh: Perhotelan / Kuliner">
                  </td>
                  <td>
                    <input type="text" name="siswa[0][nis]" class="form-control form-control-sm input-nis" placeholder="NIS">
                  </td>
                  <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-row" disabled>
                      <i class="fas fa-times"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Opsi Langsung ACC -->
          <div class="bg-light border rounded p-3 mt-4">
            <div class="custom-control custom-checkbox">
              <input type="checkbox" class="custom-control-input" id="statusLangsungAcc" name="status_langsung_acc" value="1" checked>
              <label class="custom-control-label font-weight-bold text-dark" for="statusLangsungAcc">
                Langsung Setujui (ACC) &amp; Terbitkan Surat Permohonan PKL ini
              </label>
              <small class="form-text text-muted">Jika dicentang, status pengajuan langsung Disetujui dan siap untuk dicetak / diunduh.</small>
            </div>
          </div>

        </div>
        <div class="card-footer bg-white border-top py-3" style="display: block !important;">
          <div class="d-flex align-items-center justify-content-between w-100">
            <a href="{{ route('admin.pengajuan-pkl.index') }}" class="btn btn-secondary px-3 shadow-sm">
              <i class="fas fa-arrow-left mr-1"></i> Batal
            </a>
            <div class="ml-auto">
              <button type="submit" class="btn btn-primary font-weight-bold px-4 shadow-sm">
                <i class="fas fa-save mr-1"></i> Simpan Surat Permohonan PKL
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  const selectPerusahaan = document.getElementById('selectPerusahaan');
  if (selectPerusahaan) {
    selectPerusahaan.addEventListener('change', function() {
      const opt = this.options[this.selectedIndex];
      if (this.value) {
        document.getElementById('id_perusahaan').value = this.value;
        document.getElementById('nama_perusahaan').value = opt.getAttribute('data-nama') || '';
        document.getElementById('alamat_perusahaan').value = opt.getAttribute('data-alamat') || '';
        if (opt.getAttribute('data-pic')) {
          document.getElementById('ditujukan_kepada').value = opt.getAttribute('data-pic');
        }
      } else {
        document.getElementById('id_perusahaan').value = '';
      }
    });
  }

  // Dynamic Row Siswa
  let rowIdx = 1;
  const container = document.getElementById('siswaContainer');
  const btnTambah = document.getElementById('btnTambahBaris');

  btnTambah.addEventListener('click', function() {
    const tr = document.createElement('tr');
    tr.className = 'siswa-row';
    tr.innerHTML = `
      <td class="text-center row-number font-weight-bold">${container.children.length + 1}</td>
      <td>
        <input type="text" name="siswa[${rowIdx}][nama]" class="form-control form-control-sm input-nama" required placeholder="Ketik nama lengkap siswa...">
        <input type="hidden" name="siswa[${rowIdx}][id_siswa]" class="input-id-siswa">
      </td>
      <td>
        <input type="text" name="siswa[${rowIdx}][program_keahlian]" class="form-control form-control-sm input-jurusan" required placeholder="Contoh: Perhotelan / Kuliner">
      </td>
      <td>
        <input type="text" name="siswa[${rowIdx}][nis]" class="form-control form-control-sm input-nis" placeholder="NIS">
      </td>
      <td class="text-center">
        <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-row">
          <i class="fas fa-times"></i>
        </button>
      </td>
    `;
    container.appendChild(tr);
    rowIdx++;
    updateRowNumbers();
  });

  container.addEventListener('click', function(e) {
    if (e.target.closest('.btn-hapus-row')) {
      const row = e.target.closest('tr');
      if (container.children.length > 1) {
        row.remove();
        updateRowNumbers();
      }
    }
  });

  function updateRowNumbers() {
    Array.from(container.children).forEach((row, i) => {
      row.querySelector('.row-number').textContent = i + 1;
      const delBtn = row.querySelector('.btn-hapus-row');
      if (container.children.length === 1) {
        delBtn.disabled = true;
      } else {
        delBtn.disabled = false;
      }
    });
  }
});
</script>
@endpush
