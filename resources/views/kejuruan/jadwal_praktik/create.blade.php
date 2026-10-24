@extends($layout)
@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-calendar-plus"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Tambah Jadwal Praktik</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Atur alokasi penggunaan laboratorium kejuruan tanpa bentrok waktu</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/kejuruan/jadwal-praktik" class="text-primary font-weight-500">Jadwal Praktik</a></li>
          <li class="breadcrumb-item active">Tambah Jadwal</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-exclamation-triangle mr-1"></i> {!! session('error') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Jadwal Praktik
            </h5>
          </div>

          <form action="/admin/kejuruan/jadwal-praktik" method="POST">
            @csrf
            <div class="card-body p-4 pt-2">
              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label for="id_kelas" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Kelas Siswa <span class="text-danger">*</span></label>
                  <select name="id_kelas" id="id_kelas" class="form-control select2bs4 @error('id_kelas') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelas as $k)
                      <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                        {{ $k->nama_kelas }} ({{ $k->jurusan->nama_jurusan ?? $k->kode_jurusan }})
                      </option>
                    @endforeach
                  </select>
                  @error('id_kelas')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                  <label for="id_lab" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Laboratorium / Bengkel <span class="text-danger">*</span></label>
                  <select name="id_lab" id="id_lab" class="form-control select2bs4 @error('id_lab') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Laboratorium</option>
                    @foreach($labs as $l)
                      <option value="{{ $l->id_lab }}" {{ old('id_lab') == $l->id_lab ? 'selected' : '' }}>
                        {{ $l->nama_lab }} (Kapasitas: {{ $l->kapasitas ?: '36' }})
                      </option>
                    @endforeach
                  </select>
                  @error('id_lab')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label for="id_mapel" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Mata Pelajaran Praktik <span class="text-danger">*</span></label>
                  <select name="id_mapel" id="id_mapel" class="form-control select2bs4 @error('id_mapel') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach($mapels as $m)
                      <option value="{{ $m->id_mapel }}" {{ old('id_mapel') == $m->id_mapel ? 'selected' : '' }}>
                        {{ $m->nama_mapel }}
                      </option>
                    @endforeach
                  </select>
                  @error('id_mapel')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                  <label for="id_guru" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Guru Pengajar / Instruktur <span class="text-danger">*</span></label>
                  <select name="id_guru" id="id_guru" class="form-control select2bs4 @error('id_guru') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Guru</option>
                    @foreach($gurus as $g)
                      <option value="{{ $g->id_guru }}" {{ old('id_guru', $user->id_guru) == $g->id_guru ? 'selected' : '' }}>
                        {{ $g->nama_guru }}
                      </option>
                    @endforeach
                  </select>
                  @error('id_guru')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="col-md-4 form-group mb-3">
                  <label for="hari" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Hari Pelaksanaan <span class="text-danger">*</span></label>
                  <select name="hari" id="hari" class="form-control @error('hari') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Hari</option>
                    @foreach($hariList as $h)
                      <option value="{{ $h }}" {{ old('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                  </select>
                  @error('hari')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-4 form-group mb-3">
                  <label for="jam_mulai" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Jam Mulai <span class="text-danger">*</span></label>
                  <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', '07:30') }}" required style="border-radius: 8px; height: 42px;">
                  @error('jam_mulai')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-4 form-group mb-3">
                  <label for="jam_selesai" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Jam Selesai <span class="text-danger">*</span></label>
                  <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', '09:30') }}" required style="border-radius: 8px; height: 42px;">
                  @error('jam_selesai')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <div class="form-group mb-2">
                <label for="alat_bahan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Alat / Bahan yang Harus Dibawa Siswa (Opsional)
                </label>
                <textarea class="form-control @error('alat_bahan') is-invalid @enderror" id="alat_bahan" name="alat_bahan" rows="3" placeholder="Contoh: Baju praktik (wearpack), flashdisk 16GB, kabel LAN crimping, atau modul jobsheet" style="border-radius: 8px;">{{ old('alat_bahan') }}</textarea>
                <small class="text-muted">Keterangan ini akan tampil pada kalender dan panduan siswa saat masuk sesi praktik.</small>
                @error('alat_bahan')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>
            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/kejuruan/jadwal-praktik" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Jadwal Praktik
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-12 col-lg-4 mt-3 mt-lg-0">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; background-color: #f8fafc;">
          <div class="card-body p-3">
            <h6 class="font-weight-bold text-dark mb-2">
              <i class="fas fa-shield-alt text-primary mr-1"></i> Proteksi Bentrok Otomatis
            </h6>
            <p class="text-muted small mb-2">Sistem secara cerdas memeriksa seluruh jadwal sebelum disimpan:</p>
            <ul class="text-muted small pl-3 mb-0" style="line-height: 1.6;">
              <li>Laboratorium tidak dapat digunakan dua kelas sekaligus pada jam sama.</li>
              <li>Guru tidak dapat dijadwalkan di dua lab berbeda pada jam yang sama.</li>
              <li>Guru dan kelas tidak akan bentrok dengan jadwal pelajaran teori umum.</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
