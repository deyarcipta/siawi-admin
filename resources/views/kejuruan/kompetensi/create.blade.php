@extends($layout)
@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-award"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Tambah Kompetensi Keahlian</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Daftarkan standar capaian kompetensi kejuruan baru</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/kompetensi" class="text-primary font-weight-500">Data Kompetensi</a></li>
          <li class="breadcrumb-item active">Tambah Kompetensi</li>
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
              <i class="fas fa-plus-circle text-primary mr-2"></i> Formulir Tambah Kompetensi
            </h5>
          </div>

          <form action="/admin/kompetensi" method="POST">
            @csrf
            <div class="card-body p-4 pt-2">
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
                <label for="kode_kompetensi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Kode Kompetensi <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('kode_kompetensi') is-invalid @enderror" id="kode_kompetensi" placeholder="Contoh: TKJ-KD-01" name="kode_kompetensi" value="{{ old('kode_kompetensi') }}" required style="border-radius: 8px; height: 42px;">
                @error('kode_kompetensi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="nama_kompetensi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Nama Capaian / Kompetensi <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama_kompetensi') is-invalid @enderror" id="nama_kompetensi" placeholder="Contoh: Mengkonfigurasi Routing Dinamis BGP" name="nama_kompetensi" value="{{ old('nama_kompetensi') }}" required style="border-radius: 8px; height: 42px;">
                @error('nama_kompetensi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label for="tingkat" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Tingkat Kelas <span class="text-danger">*</span></label>
                  <select name="tingkat" id="tingkat" class="form-control @error('tingkat') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Tingkat</option>
                    <option value="X" {{ old('tingkat') == 'X' ? 'selected' : '' }}>Kelas X (Sepuluh)</option>
                    <option value="XI" {{ old('tingkat') == 'XI' ? 'selected' : '' }}>Kelas XI (Sebelas)</option>
                    <option value="XII" {{ old('tingkat') == 'XII' ? 'selected' : '' }}>Kelas XII (Dua Belas)</option>
                  </select>
                  @error('tingkat')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                  <label for="semester" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Semester <span class="text-danger">*</span></label>
                  <select name="semester" id="semester" class="form-control @error('semester') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Semester</option>
                    <option value="1" {{ old('semester') == '1' ? 'selected' : '' }}>Semester 1 (Ganjil)</option>
                    <option value="2" {{ old('semester') == '2' ? 'selected' : '' }}>Semester 2 (Genap)</option>
                  </select>
                  @error('semester')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>
            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/kompetensi" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Kompetensi
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
