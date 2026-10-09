@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-user-edit"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Edit Data Guru</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Perbarui data identitas pengajar, kontak, dan hak akses sistem</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/guru" class="text-primary font-weight-500">Data Guru</a></li>
          <li class="breadcrumb-item active">Edit Guru</li>
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
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Edit Data Guru
            </h5>
          </div>

          <form action="/admin/guru/{{ $edit->id_guru }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body p-4 pt-2">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label for="username" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Username Akun <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" placeholder="Masukkan Username" name="username" value="{{ old('username', $edit->username) }}" required style="border-radius: 8px; height: 42px;">
                  @error('username')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label for="nama_guru" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Nama Lengkap Guru <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('nama_guru') is-invalid @enderror" id="nama_guru" placeholder="Masukkan Nama Lengkap" name="nama_guru" value="{{ old('nama_guru', $edit->nama_guru) }}" required style="border-radius: 8px; height: 42px;">
                  @error('nama_guru')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label for="no_hp" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Nomor WhatsApp / HP</label>
                  <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" placeholder="Contoh: 08123456789" name="no_hp" value="{{ old('no_hp', $edit->no_hp) }}" style="border-radius: 8px; height: 42px;">
                  @error('no_hp')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-12 mb-3">
                  <label class="font-weight-bold text-dark d-block" style="font-size: 0.85rem;">
                    Hak Akses Sistem (Multi-Role / Bisa Pilih Lebih Dari 1) <span class="text-danger">*</span>
                  </label>
                  <p class="text-muted small mb-2">Centang semua jabatan/peran yang dipegang oleh guru ini. Guru akan otomatis mendapatkan hak akses gabungan.</p>
                  <div class="d-flex flex-wrap" style="gap: 10px;">
                    @php
                      $availableRoles = [
                        'guru' => ['label' => 'Guru Mapel', 'desc' => 'Jurnal mengajar & jadwal'],
                        'wali_kelas' => ['label' => 'Wali Kelas', 'desc' => 'E-Rapot & rekap kelas'],
                        'kesiswaan' => ['label' => 'Kesiswaan', 'desc' => 'Poin pelanggaran, kelalaian'],
                        'kurikulum' => ['label' => 'Kurikulum', 'desc' => 'Jadwal, modul, & kalender'],
                        'hubin' => ['label' => 'Hubin (BKK & PKL)', 'desc' => 'Mitra DU/DI, PKL, & Alumni'],
                        'tata_usaha' => ['label' => 'Tata Usaha', 'desc' => 'Persuratan & data master'],
                        'keuangan' => ['label' => 'Keuangan', 'desc' => 'Tagihan siswa'],
                        'admin' => ['label' => 'Admin', 'desc' => 'Akses penuh sistem'],
                      ];
                      $selectedRoles = old('roles', $edit->roles_list ?? ['guru']);
                    @endphp
                    @foreach($availableRoles as $rKey => $rMeta)
                      <div class="custom-control custom-checkbox mr-3 mb-2" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 16px 8px 36px; min-width: 175px;">
                        <input type="checkbox" class="custom-control-input" id="role_{{ $rKey }}" name="roles[]" value="{{ $rKey }}" {{ in_array($rKey, $selectedRoles) ? 'checked' : '' }}>
                        <label class="custom-control-label font-weight-bold text-dark" for="role_{{ $rKey }}" style="cursor: pointer; font-size: 0.84rem;">
                          {{ $rMeta['label'] }}
                          <small class="d-block text-muted font-weight-normal" style="font-size: 0.72rem;">{{ $rMeta['desc'] }}</small>
                        </label>
                      </div>
                    @endforeach
                  </div>
                  @error('roles')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label for="id_face" class="font-weight-bold text-dark" style="font-size: 0.85rem;">ID Face Recognition</label>
                  <input type="text" class="form-control @error('id_face') is-invalid @enderror" id="id_face" placeholder="ID Face (Opsional)" name="id_face" value="{{ old('id_face', $edit->id_face) }}" style="border-radius: 8px; height: 42px;">
                  @error('id_face')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>
            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/guru" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
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