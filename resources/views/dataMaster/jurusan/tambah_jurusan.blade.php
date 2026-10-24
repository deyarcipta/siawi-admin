@extends($layout)
@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-graduation-cap"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Tambah Program Keahlian</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Daftarkan kompetensi keahlian kejuruan baru</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/jurusan" class="text-primary font-weight-500">Data Jurusan</a></li>
          <li class="breadcrumb-item active">Tambah Jurusan</li>
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
              <i class="fas fa-plus-circle text-primary mr-2"></i> Formulir Tambah Jurusan
            </h5>
          </div>

          <form action="/admin/jurusan" method="POST">
            @csrf
            <div class="card-body p-4 pt-2">
              <div class="form-group mb-3">
                <label for="kode_jurusan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Kode Jurusan <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('kode_jurusan') is-invalid @enderror" id="kode_jurusan" placeholder="Contoh: TJKT" name="kode_jurusan" value="{{ old('kode_jurusan') }}" required style="border-radius: 8px; height: 42px;">
                @error('kode_jurusan')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>
              <div class="form-group mb-3">
                <label for="nama_jurusan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Nama Konsentrasi / Program Keahlian <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('nama_jurusan') is-invalid @enderror" id="nama_jurusan" placeholder="Contoh: Teknik Jaringan Komputer dan Telekomunikasi" name="nama_jurusan" value="{{ old('nama_jurusan') }}" required style="border-radius: 8px; height: 42px;">
                @error('nama_jurusan')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>
              <div class="form-group mb-2">
                <div class="custom-control custom-switch">
                  <input type="checkbox" class="custom-control-input" id="aktif" name="aktif" value="1" {{ old('aktif', '1') == '1' ? 'checked' : '' }}>
                  <label class="custom-control-label font-weight-bold text-dark" for="aktif" style="font-size: 0.85rem; cursor: pointer;">Status Aktif</label>
                </div>
                <small class="text-muted">Jurusan aktif dapat dipilih untuk penugasan kelas, guru, dan modul pembelajaran.</small>
              </div>

              @if($user->hasRole('admin'))
              <div class="form-group mb-2 pt-3 border-top">
                <label class="font-weight-bold text-dark d-block mb-1" style="font-size: 0.88rem;">
                  <i class="fas fa-cubes text-primary mr-1"></i> Modul Kejuruan Aktif (Khusus Admin)
                </label>
                <p class="text-muted small mb-2">Pilih modul apa saja yang diaktifkan untuk program keahlian ini. Modul yang dinonaktifkan tidak akan membebani menu atau transkrip siswa.</p>

                <div class="p-3 bg-light rounded border" style="gap: 12px; display: flex; flex-direction: column;">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="modul_tkk" name="modul_tkk" value="1" {{ old('modul_tkk', '1') == '1' ? 'checked' : '' }}>
                    <label class="custom-control-label font-weight-bold text-dark" for="modul_tkk" style="cursor: pointer; font-size: 0.85rem;">
                      Tanda Kecakapan Khusus (TKK)
                    </label>
                    <small class="text-muted d-block">Skema lencana kompetensi dan piagam keahlian siswa.</small>
                  </div>

                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="modul_ukk" name="modul_ukk" value="1" {{ old('modul_ukk', '1') == '1' ? 'checked' : '' }}>
                    <label class="custom-control-label font-weight-bold text-dark" for="modul_ukk" style="cursor: pointer; font-size: 0.85rem;">
                      Uji Kompetensi Keahlian (UKK)
                    </label>
                    <small class="text-muted d-block">Penilaian sidang / sertifikasi kompetensi akhir tingkatan.</small>
                  </div>

                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="modul_praktik" name="modul_praktik" value="1" {{ old('modul_praktik', '1') == '1' ? 'checked' : '' }}>
                    <label class="custom-control-label font-weight-bold text-dark" for="modul_praktik" style="cursor: pointer; font-size: 0.85rem;">
                      Pelaksanaan Praktik Kejuruan
                    </label>
                    <small class="text-muted d-block">Jadwal lab, modul praktik, jurnal mengajar, dan rekap penilaian praktik.</small>
                  </div>

                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="modul_portofolio" name="modul_portofolio" value="1" {{ old('modul_portofolio', '1') == '1' ? 'checked' : '' }}>
                    <label class="custom-control-label font-weight-bold text-dark" for="modul_portofolio" style="cursor: pointer; font-size: 0.85rem;">
                      Portofolio & Sertifikat Eksternal
                    </label>
                    <small class="text-muted d-block">Pencatatan karya, sertifikat industri, dan lomba siswa.</small>
                  </div>

                  <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="modul_lab" name="modul_lab" value="1" {{ old('modul_lab', '1') == '1' ? 'checked' : '' }}>
                    <label class="custom-control-label font-weight-bold text-dark" for="modul_lab" style="cursor: pointer; font-size: 0.85rem;">
                      Laboratorium & Inventaris Bahan
                    </label>
                    <small class="text-muted d-block">Pengelolaan lab kejuruan dan barang habis pakai praktik.</small>
                  </div>
                </div>
              </div>
              @endif
            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/jurusan" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Jurusan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection