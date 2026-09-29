@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-user-graduate text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Edit Data Alumni</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Perbarui biodata alumni, status penelusuran tamatan, dan kontak</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dataAlumni" class="text-primary font-weight-500">Data Alumni</a></li>
          <li class="breadcrumb-item active">Edit Alumni</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between w-100">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Edit Data Alumni
            </h5>
            <div class="card-tools ml-auto">
              <a href="/admin/dataAlumni" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Daftar Alumni
              </a>
            </div>
          </div>

          <form action="/admin/alumni/{{ $edit->id_alumni }}" method="POST" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="card-body">

              <!-- Bagian 1: Data Akademik & Kelulusan -->
              <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-graduation-cap mr-1"></i> Data Akademik & Kelulusan</h6>
              <div class="row">
                <div class="form-group col-md-4">
                  <label for="nis" class="font-weight-600 text-dark">NIS</label>
                  <input type="text" class="form-control @error('nis') is-invalid @enderror" id="nis" name="nis" value="{{ old('nis', $edit->nis) }}" placeholder="Nomor Induk Siswa">
                  @error('nis')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group col-md-4">
                  <label for="nisn" class="font-weight-600 text-dark">NISN</label>
                  <input type="text" class="form-control @error('nisn') is-invalid @enderror" id="nisn" name="nisn" value="{{ old('nisn', $edit->nisn) }}" placeholder="NISN Nasional">
                  @error('nisn')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group col-md-4">
                  <label for="tahun_lulus" class="font-weight-600 text-dark">Tahun Kelulusan <span class="text-danger">*</span></label>
                  <input type="number" class="form-control @error('tahun_lulus') is-invalid @enderror" id="tahun_lulus" name="tahun_lulus" value="{{ old('tahun_lulus', $edit->tahun_lulus) }}" placeholder="Contoh: 2024" required>
                  @error('tahun_lulus')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-6">
                  <label for="id_jurusan" class="font-weight-600 text-dark">Kompetensi Keahlian / Jurusan <span class="text-danger">*</span></label>
                  <select class="form-control @error('id_jurusan') is-invalid @enderror" name="id_jurusan" id="id_jurusan" required>
                    <option value="">-- Pilih Jurusan --</option>
                    @foreach ($jurusan as $j)
                      <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan', $edit->id_jurusan) == $j->id_jurusan ? 'selected' : '' }}>
                        {{ $j->nama_jurusan }}
                      </option>
                    @endforeach
                  </select>
                  @error('id_jurusan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group col-md-6">
                  <label for="status" class="font-weight-600 text-dark">Status Tamatan Saat Ini</label>
                  <select class="form-control @error('status') is-invalid @enderror" name="status" id="status">
                    <option value="-" {{ old('status', $edit->status) == '-' ? 'selected' : '' }}>-- Belum Diketahui / Lainnya --</option>
                    <option value="Bekerja" {{ old('status', $edit->status) == 'Bekerja' ? 'selected' : '' }}>Bekerja</option>
                    <option value="Melanjutkan Kuliah" {{ old('status', $edit->status) == 'Melanjutkan Kuliah' ? 'selected' : '' }}>Melanjutkan Kuliah</option>
                    <option value="Wirausaha" {{ old('status', $edit->status) == 'Wirausaha' ? 'selected' : '' }}>Wirausaha / Buka Usaha</option>
                    <option value="Mencari Kerja" {{ old('status', $edit->status) == 'Mencari Kerja' ? 'selected' : '' }}>Mencari Kerja</option>
                  </select>
                  @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <hr class="my-4">

              <!-- Bagian 2: Data Pribadi Alumni -->
              <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-user mr-1"></i> Biodata Pribadi</h6>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="nama" class="font-weight-600 text-dark">Nama Lengkap Alumni <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama', $edit->nama) }}" placeholder="Nama lengkap alumni" required>
                  @error('nama')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group col-md-3">
                  <label for="jenis_kelamin" class="font-weight-600 text-dark">Jenis Kelamin</label>
                  <select class="form-control @error('jenis_kelamin') is-invalid @enderror" name="jenis_kelamin" id="jenis_kelamin">
                    <option value="">-- Pilih --</option>
                    <option value="L" {{ old('jenis_kelamin', $edit->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="P" {{ old('jenis_kelamin', $edit->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                  </select>
                </div>
                <div class="form-group col-md-3">
                  <label for="agama" class="font-weight-600 text-dark">Agama</label>
                  <input type="text" class="form-control @error('agama') is-invalid @enderror" id="agama" name="agama" value="{{ old('agama', $edit->agama) }}" placeholder="Agama">
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-6">
                  <label for="tempat_lahir" class="font-weight-600 text-dark">Tempat Lahir</label>
                  <input type="text" class="form-control @error('tempat_lahir') is-invalid @enderror" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $edit->tempat_lahir) }}" placeholder="Kota kelahiran">
                </div>
                <div class="form-group col-md-6">
                  <label for="tanggal_lahir" class="font-weight-600 text-dark">Tanggal Lahir</label>
                  <input type="date" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $edit->tanggal_lahir) }}">
                </div>
              </div>

              <hr class="my-4">

              <!-- Bagian 3: Kontak & Alamat -->
              <h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-map-marker-alt mr-1"></i> Kontak & Domisili</h6>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="no_hp" class="font-weight-600 text-dark">Nomor WhatsApp / HP</label>
                  <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" value="{{ old('no_hp', $edit->no_hp) }}" placeholder="Contoh: 081234567890">
                </div>
                <div class="form-group col-md-6">
                  <label for="email" class="font-weight-600 text-dark">Email</label>
                  <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $edit->email) }}" placeholder="alamat@email.com">
                </div>
              </div>

              <div class="row">
                <div class="form-group col-md-8">
                  <label for="alamat" class="font-weight-600 text-dark">Alamat Lengkap</label>
                  <textarea class="form-control @error('alamat') is-invalid @enderror" id="alamat" name="alamat" rows="3" placeholder="Alamat tempat tinggal saat ini...">{{ old('alamat', $edit->alamat) }}</textarea>
                </div>
                <div class="form-group col-md-4">
                  <label class="font-weight-600 text-dark">Foto Alumni</label>
                  <div class="d-flex align-items-center gap-3">
                    @if($edit->foto && $edit->foto != 'avatar.jpg')
                      <img src="{{ asset('storage/foto-siswa/' . $edit->foto) }}" alt="Foto" class="img-thumbnail rounded mr-3 shadow-sm" style="width: 70px; height: 85px; object-fit: cover;">
                    @else
                      <div class="avatar-placeholder rounded bg-light d-flex align-items-center justify-content-center text-secondary font-weight-bold mr-3 shadow-sm" style="width: 70px; height: 85px; font-size: 1.5rem;">
                        {{ strtoupper(substr($edit->nama ?? 'A', 0, 1)) }}
                      </div>
                    @endif
                    <div>
                      <input type="file" class="form-control-file @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*">
                      <small class="text-muted d-block mt-1">Format: JPG, PNG, WEBP (Maks 2MB)</small>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            <div class="card-footer bg-light d-flex align-items-center justify-content-between">
              <a href="/admin/dataAlumni" class="btn btn-secondary btn-sm shadow-sm">
                <i class="fas fa-arrow-left mr-1"></i> Batal & Kembali
              </a>
              <button type="submit" class="btn btn-warning btn-sm text-white font-weight-600 shadow-sm ml-auto">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
