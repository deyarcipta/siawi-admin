@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-edit text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Edit Data Mutasi Siswa</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Perbarui catatan dan informasi mutasi siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/siswaMutasi" class="text-primary font-weight-500">Data Mutasi</a></li>
          <li class="breadcrumb-item active">Edit</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h3 class="card-title text-dark font-weight-bold mb-0">
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Edit Mutasi: {{ $edit->nama_siswa }}
            </h3>
            <a href="/admin/siswaMutasi" class="btn btn-secondary btn-sm shadow-sm">
              <i class="fas fa-arrow-left mr-1"></i> Kembali
            </a>
          </div>

          <form action="/admin/siswaMutasi/{{ $edit->id_mutasi }}" method="POST">
            @csrf
            @method('PUT')

            <div class="card-body">
              <div class="alert alert-light border mb-4">
                <div class="d-flex align-items-center">
                  <i class="fas fa-user-graduate text-info mr-3 fa-2x"></i>
                  <div>
                    <h6 class="font-weight-bold text-dark mb-1">{{ $edit->nama_siswa }}</h6>
                    <p class="text-muted small mb-0">
                      NIS: <span class="font-weight-600 text-dark">{{ $edit->nis ?? '-' }}</span> |
                      Kelas Terakhir: <span class="font-weight-600 text-dark">{{ $edit->kelas_terakhir ?? ($edit->kelas->nama_kelas ?? '-') }}</span>
                    </p>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label for="tanggal_mutasi" class="font-weight-600 text-dark">Tanggal Mutasi <span class="text-danger">*</span></label>
                <input type="date" class="form-control @error('tanggal_mutasi') is-invalid @enderror" id="tanggal_mutasi" name="tanggal_mutasi" value="{{ old('tanggal_mutasi', $edit->tanggal_mutasi ? $edit->tanggal_mutasi->format('Y-m-d') : '') }}" required>
                @error('tanggal_mutasi')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label for="jenis_mutasi" class="font-weight-600 text-dark">Jenis Mutasi <span class="text-danger">*</span></label>
                <select class="form-control @error('jenis_mutasi') is-invalid @enderror" id="jenis_mutasi" name="jenis_mutasi" required>
                  <option value="Pindah Sekolah" {{ old('jenis_mutasi', $edit->jenis_mutasi) == 'Pindah Sekolah' ? 'selected' : '' }}>Pindah Sekolah</option>
                  <option value="Mengundurkan Diri" {{ old('jenis_mutasi', $edit->jenis_mutasi) == 'Mengundurkan Diri' ? 'selected' : '' }}>Mengundurkan Diri</option>
                  <option value="Dikeluarkan (DO)" {{ old('jenis_mutasi', $edit->jenis_mutasi) == 'Dikeluarkan (DO)' ? 'selected' : '' }}>Dikeluarkan (DO)</option>
                  <option value="Wafat" {{ old('jenis_mutasi', $edit->jenis_mutasi) == 'Wafat' ? 'selected' : '' }}>Wafat</option>
                  <option value="Lainnya" {{ old('jenis_mutasi', $edit->jenis_mutasi) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('jenis_mutasi')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label for="sekolah_tujuan" class="font-weight-600 text-dark">Sekolah / Institusi Tujuan</label>
                <input type="text" class="form-control @error('sekolah_tujuan') is-invalid @enderror" id="sekolah_tujuan" name="sekolah_tujuan" value="{{ old('sekolah_tujuan', $edit->sekolah_tujuan) }}" placeholder="Contoh: SMAN 1 Cibinong">
                @error('sekolah_tujuan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="form-group">
                <label for="alasan_mutasi" class="font-weight-600 text-dark">Alasan / Catatan Keterangan</label>
                <textarea class="form-control @error('alasan_mutasi') is-invalid @enderror" id="alasan_mutasi" name="alasan_mutasi" rows="4" placeholder="Tuliskan keterangan / alasan siswa mutasi...">{{ old('alasan_mutasi', $edit->alasan_mutasi) }}</textarea>
                @error('alasan_mutasi')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="card-footer bg-light d-flex justify-content-between">
              <a href="/admin/siswaMutasi" class="btn btn-secondary btn-sm">Batal</a>
              <button type="submit" class="btn btn-warning btn-sm font-weight-600 text-white shadow-sm">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan Mutasi
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
