@extends($layout)
@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-flask"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Tambah Laboratorium / Bengkel</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Daftarkan ruangan laboratorium atau bengkel praktik baru</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/lab" class="text-primary font-weight-500">Data Lab</a></li>
          <li class="breadcrumb-item active">Tambah Lab</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12 col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-plus-circle text-primary mr-2"></i> Formulir Tambah Laboratorium
            </h5>
          </div>

          <form action="/admin/lab" method="POST">
            @csrf
            <div class="card-body p-4 pt-2">
              <div class="form-group mb-3">
                <label for="nama_lab" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Nama Laboratorium / Bengkel <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama_lab') is-invalid @enderror" id="nama_lab" placeholder="Contoh: Lab Komputer Jaringan 1" name="nama_lab" value="{{ old('nama_lab') }}" required style="border-radius: 8px; height: 42px;">
                @error('nama_lab')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="kode_lab" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Kode Ruang / Lab (Opsional)</label>
                <input type="text" class="form-control @error('kode_lab') is-invalid @enderror" id="kode_lab" placeholder="Contoh: LAB-TKJ-01" name="kode_lab" value="{{ old('kode_lab') }}" style="border-radius: 8px; height: 42px;">
                @error('kode_lab')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="id_jurusan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Program Keahlian / Jurusan <span class="text-danger">*</span></label>
                <select name="id_jurusan" id="id_jurusan" class="form-control select2bs4 @error('id_jurusan') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                  <option value="">Pilih Program Keahlian</option>
                  @foreach($jurusans as $j)
                    <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                      {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                    </option>
                  @endforeach
                </select>
                @error('id_jurusan')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="kapasitas" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Kapasitas Siswa</label>
                <input type="number" class="form-control @error('kapasitas') is-invalid @enderror" id="kapasitas" placeholder="Contoh: 36" name="kapasitas" value="{{ old('kapasitas', 36) }}" min="0" style="border-radius: 8px; height: 42px;">
                @error('kapasitas')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-2">
                <label for="lokasi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Lokasi Ruangan (Opsional)</label>
                <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" placeholder="Contoh: Gedung B Lantai 2" name="lokasi" value="{{ old('lokasi') }}" style="border-radius: 8px; height: 42px;">
                @error('lokasi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>
            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/lab" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Laboratorium
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
