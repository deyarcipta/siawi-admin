@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-user-cog"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Edit Profil Guru</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Kelola data profil, foto, kontak WhatsApp, serta beban mengajar</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item active">Edit Profil</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<style>
  .modern-input-group {
    display: flex;
    align-items: stretch;
    width: 100%;
    border-radius: 8px;
    border: 1px solid #d1d5db;
    background-color: #ffffff;
    transition: all 0.2s ease-in-out;
    overflow: hidden;
  }
  .modern-input-group:focus-within {
    border-color: #1d72fe;
    box-shadow: 0 0 0 3px rgba(29, 114, 254, 0.15);
  }
  .modern-input-group.is-invalid {
    border-color: #dc3545;
  }
  .modern-input-group.readonly-group {
    background-color: #f8fafc;
    border-color: #e2e8f0;
  }
  .modern-input-group .input-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 14px;
    background-color: #f8fafc;
    border-right: 1px solid #e2e8f0;
    color: #64748b;
    font-size: 0.95rem;
    flex-shrink: 0;
  }
  .modern-input-group.readonly-group .input-icon {
    background-color: #edf2f7;
    border-right: 1px solid #e2e8f0;
    color: #718096;
  }
  .modern-input-group input {
    flex: 1;
    height: 42px;
    padding: 8px 14px;
    font-size: 0.9rem;
    border: none;
    outline: none;
    background: transparent;
    color: #2d3748;
    width: 100%;
  }
  .modern-input-group input:disabled,
  .modern-input-group input[readonly] {
    background: transparent;
    cursor: default;
    color: #4a5568;
  }
  .modern-input-group .toggle-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 14px;
    border: none;
    background: transparent;
    color: #64748b;
    cursor: pointer;
    transition: color 0.2s;
  }
  .modern-input-group .toggle-btn:hover {
    color: #1d72fe;
  }
</style>

<!-- Main Content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      
      <!-- Left Column: Profile Summary & Wali Kelas Card -->
      <div class="col-lg-4 col-md-5 mb-4">
        
        <!-- Profile Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; overflow: hidden;">
          <div class="card-body text-center p-4">
            
            <!-- Avatar Foto Profil dengan Tombol Kamera Cepat -->
            <div class="position-relative d-inline-block mx-auto mb-2">
              <label for="fotoInput" style="cursor: pointer; display: inline-block; margin-bottom: 0;" title="Klik untuk memilih foto profil baru">
                @if($edit->foto && file_exists(storage_path('app/public/foto_guru/' . $edit->foto)))
                  <img src="{{ asset('storage/foto_guru/' . $edit->foto) }}" alt="{{ $edit->nama_guru }}" class="rounded-circle shadow" id="profileAvatarImg" style="width: 96px; height: 96px; object-fit: cover; border: 3px solid #1d72fe; transition: all 0.2s; display: block;">
                  <div id="profileAvatarInitial" class="align-items-center justify-content-center rounded-circle text-white shadow" style="width: 96px; height: 96px; font-size: 2.3rem; font-weight: 700; background: linear-gradient(135deg, #1d72fe 0%, #0052cc 100%); display: none;">
                    {{ strtoupper(substr($edit->nama_guru, 0, 1)) }}
                  </div>
                @else
                  <img src="" alt="Preview" class="rounded-circle shadow" id="profileAvatarImg" style="width: 96px; height: 96px; object-fit: cover; border: 3px solid #1d72fe; transition: all 0.2s; display: none;">
                  <div id="profileAvatarInitial" class="align-items-center justify-content-center rounded-circle text-white shadow" style="width: 96px; height: 96px; font-size: 2.3rem; font-weight: 700; background: linear-gradient(135deg, #1d72fe 0%, #0052cc 100%); display: flex;">
                    {{ strtoupper(substr($edit->nama_guru, 0, 1)) }}
                  </div>
                @endif
                
                <span class="position-absolute bg-primary text-white rounded-circle shadow d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; right: 0; bottom: 0; border: 2.5px solid #ffffff;">
                  <i class="fas fa-camera" style="font-size: 0.80rem;"></i>
                </span>
              </label>

              <!-- Hidden File Input terhubung ke Form Edit Profil -->
              <input type="file" name="foto" id="fotoInput" form="formEditProfile" class="d-none" accept="image/*" onchange="previewProfileImage(this)">
            </div>

            <div id="fotoNewBadge" class="mb-2" style="display: none;">
              <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" style="font-size: 0.75rem; border-radius: 6px;">
                <i class="fas fa-check mr-1"></i> Foto baru dipilih
              </span>
            </div>

            <small class="text-muted d-block mb-3" style="font-size: 0.75rem;"><i class="fas fa-info-circle mr-1"></i> Klik foto / ikon kamera untuk mengganti</small>

            <h5 class="font-weight-bold text-dark mb-1" style="font-size: 1.15rem;">{{ $edit->nama_guru }}</h5>
            <p class="text-muted mb-2" style="font-size: 0.85rem;">@<span>{{ $edit->username }}</span></p>
            
            <div class="d-flex flex-wrap justify-content-center align-items-center mb-3" style="gap: 6px;">
              @php
                $roleBadges = [
                  'admin' => ['class' => 'badge-danger', 'label' => 'Admin'],
                  'guru' => ['class' => 'badge-primary', 'label' => 'Guru Mapel'],
                  'wali_kelas' => ['class' => 'badge-success', 'label' => 'Wali Kelas'],
                  'kesiswaan' => ['class' => 'badge-info', 'label' => 'Kesiswaan'],
                  'kurikulum' => ['class' => 'badge-warning text-dark', 'label' => 'Kurikulum'],
                  'tata_usaha' => ['class' => 'badge-secondary', 'label' => 'Tata Usaha'],
                  'keuangan' => ['class' => 'badge-success', 'label' => 'Keuangan'],
                ];
                $activeRoles = $edit->roles_list ?? [$edit->role];
                $roleLabelsCombined = collect($activeRoles)->map(fn($r) => $roleBadges[$r]['label'] ?? ucfirst($r))->join(', ');
              @endphp
              @foreach($activeRoles as $r)
                @php $badgeMeta = $roleBadges[$r] ?? ['class' => 'badge-secondary', 'label' => ucfirst($r)]; @endphp
                <span class="badge {{ $badgeMeta['class'] }} px-3 py-1 text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem; border-radius: 20px;">
                  <i class="fas fa-shield-alt mr-1"></i> {{ $badgeMeta['label'] }}
                </span>
              @endforeach
            </div>

            <!-- Mini KPI Stats -->
            <div class="row no-gutters bg-light rounded-lg p-2 mb-3 text-center" style="border-radius: 10px; border: 1px solid #e2e8f0;">
              <div class="col-4 border-right">
                <span class="d-block font-weight-bold text-primary" style="font-size: 1.15rem;">{{ $totalJpSeminggu }}</span>
                <small class="text-muted text-uppercase font-weight-bold" style="font-size: 0.65rem;">Total JP</small>
              </div>
              <div class="col-4 border-right">
                <span class="d-block font-weight-bold text-dark" style="font-size: 1.15rem;">{{ $mapelDiampu->count() }}</span>
                <small class="text-muted text-uppercase font-weight-bold" style="font-size: 0.65rem;">Mapel</small>
              </div>
              <div class="col-4">
                <span class="d-block font-weight-bold text-success" style="font-size: 1.15rem;">{{ $jadwalMengajar->pluck('id_kelas')->unique()->count() }}</span>
                <small class="text-muted text-uppercase font-weight-bold" style="font-size: 0.65rem;">Kelas</small>
              </div>
            </div>

            <div class="text-left">
              <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted" style="font-size: 0.82rem;"><i class="fab fa-whatsapp text-success mr-2"></i> No. WhatsApp</span>
                <span class="font-weight-600 text-dark" style="font-size: 0.82rem;">{{ $edit->no_hp ?? '-' }}</span>
              </div>
              <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted" style="font-size: 0.82rem;"><i class="fas fa-id-badge text-secondary mr-2"></i> ID Guru</span>
                <span class="font-weight-bold text-dark" style="font-size: 0.82rem;">#{{ $edit->id_guru }}</span>
              </div>
              <div class="d-flex justify-content-between py-2 border-bottom">
                <span class="text-muted" style="font-size: 0.82rem;"><i class="fas fa-check-circle text-success mr-2"></i> Status Akun</span>
                <span class="badge badge-success px-2 py-1" style="font-size: 0.75rem; border-radius: 6px;">Aktif</span>
              </div>
              <div class="d-flex justify-content-between py-2">
                <span class="text-muted" style="font-size: 0.82rem;"><i class="fas fa-calendar-alt text-secondary mr-2"></i> Terdaftar</span>
                <span class="font-weight-600 text-dark" style="font-size: 0.82rem;">{{ $edit->created_at ? $edit->created_at->format('d M Y') : '-' }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Wali Kelas Detail Card (Jika Wali Kelas) -->
        @if(isset($kelasWali) && $kelasWali->isNotEmpty())
          <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; background: linear-gradient(145deg, #f0fdf4 0%, #dcfce7 100%); border: 1px solid #bbf7d0 !important;">
            <div class="card-body p-3">
              <div class="d-flex align-items-center mb-2">
                <div class="d-inline-flex align-items-center justify-content-center bg-success text-white rounded-circle shadow-sm mr-2" style="width: 34px; height: 34px; font-size: 0.95rem;">
                  <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <div>
                  <h6 class="font-weight-bold text-success mb-0" style="font-size: 0.92rem;">Amanah Wali Kelas</h6>
                  <small class="text-muted font-weight-500">Kelas Binaan yang Diampu</small>
                </div>
              </div>

              @foreach($kelasWali as $kw)
                <div class="bg-white p-3 rounded-lg border shadow-xs mt-2" style="border-radius: 10px; border-color: #86efac !important;">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="font-weight-bold text-dark" style="font-size: 1.05rem;">
                      <i class="fas fa-graduation-cap text-success mr-1"></i> {{ $kw->nama_kelas }}
                    </span>
                    <span class="badge badge-success px-2 py-1" style="border-radius: 6px; font-size: 0.78rem;">
                      {{ $kw->siswa->count() }} Siswa
                    </span>
                  </div>
                  <div class="text-muted mb-2" style="font-size: 0.78rem;">
                    <span>Jurusan: <strong>{{ $kw->jurusan->nama_jurusan ?? '-' }}</strong></span> &bull; 
                    <span>Tingkat: <strong>{{ $kw->level->nama_level ?? '-' }}</strong></span>
                  </div>
                  <div class="pt-2 border-top d-flex justify-content-between" style="gap: 6px;">
                    <a href="{{ url('/admin/absensi?kelas=' . $kw->id_kelas) }}" class="btn btn-outline-success btn-xs flex-fill" style="border-radius: 6px; font-size: 0.75rem; padding: 4px 6px;">
                      <i class="fas fa-calendar-check mr-1"></i> Absensi
                    </a>
                    <a href="{{ url('/admin/rapot?kelas=' . $kw->id_kelas) }}" class="btn btn-outline-primary btn-xs flex-fill" style="border-radius: 6px; font-size: 0.75rem; padding: 4px 6px;">
                      <i class="fas fa-book-reader mr-1"></i> Rapot
                    </a>
                    <a href="{{ url('/admin/laporan-bulanan-wa?kelas=' . $kw->id_kelas) }}" class="btn btn-outline-info btn-xs flex-fill" style="border-radius: 6px; font-size: 0.75rem; padding: 4px 6px;">
                      <i class="fab fa-whatsapp mr-1"></i> WA
                    </a>
                  </div>
                </div>
              @endforeach
            </div>
          </div>
        @endif

        <!-- Security Tips Card -->
        <div class="card border-0 shadow-sm" style="border-radius: 14px; background: #f8fafc;">
          <div class="card-body p-3">
            <h6 class="font-weight-bold text-dark mb-2" style="font-size: 0.88rem;">
              <i class="fas fa-shield-alt text-primary mr-2"></i> Panduan Keamanan
            </h6>
            <ul class="text-muted pl-3 mb-0" style="font-size: 0.80rem; line-height: 1.5;">
              <li class="mb-1">Gunakan kata sandi unik minimal 8 karakter.</li>
              <li class="mb-1">Pastikan No. WhatsApp aktif untuk notifikasi sistem.</li>
              <li>Jangan bagikan password login ke siapapun.</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Right Column: Profile Edit Form & Teaching Schedule -->
      <div class="col-lg-8 col-md-7">
        
        <!-- Card 1: Formulir Edit Profil -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-user-edit text-primary mr-2"></i> Informasi Akun & Keamanan
            </h5>
          </div>

          <form id="formEditProfile" action="{{ url('/admin/dashboard/' . $edit->id_guru) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="card-body p-4 pt-2">
              
              <div class="row">

                <!-- Nama Lengkap -->
                <div class="col-md-6 mb-3">
                  <label for="nama_guru" class="font-weight-bold text-dark mb-1" style="font-size: 0.85rem;">
                    Nama Lengkap & Gelar Guru <span class="text-danger">*</span>
                  </label>
                  <div class="modern-input-group @error('nama_guru') is-invalid @enderror">
                    <div class="input-icon text-muted">
                      <i class="fas fa-user-tie"></i>
                    </div>
                    <input type="text" id="nama_guru" name="nama_guru" value="{{ old('nama_guru', $edit->nama_guru) }}" required placeholder="Contoh: Deyar Cipta Rizky, S.Kom.">
                  </div>
                  @error('nama_guru')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <!-- Nomor WhatsApp -->
                <div class="col-md-6 mb-3">
                  <label for="no_hp" class="font-weight-bold text-dark mb-1" style="font-size: 0.85rem;">
                    Nomor WhatsApp / HP
                  </label>
                  <div class="modern-input-group @error('no_hp') is-invalid @enderror">
                    <div class="input-icon text-success">
                      <i class="fab fa-whatsapp"></i>
                    </div>
                    <input type="text" id="no_hp" name="no_hp" value="{{ old('no_hp', $edit->no_hp) }}" placeholder="Contoh: 081234567890">
                  </div>
                  <small class="text-muted mt-1 d-block" style="font-size: 0.78rem;"><i class="fas fa-info-circle mr-1"></i> Digunakan untuk notifikasi sistem & kontak resmi.</small>
                  @error('no_hp')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <!-- Username (Readonly) -->
                <div class="col-md-4 mb-3">
                  <label for="username" class="font-weight-bold text-dark mb-1" style="font-size: 0.85rem;">Username Akun</label>
                  <div class="modern-input-group readonly-group">
                    <div class="input-icon">
                      <i class="fas fa-at"></i>
                    </div>
                    <input type="text" id="username" name="username" value="{{ old('username', $edit->username) }}" readonly>
                  </div>
                  <small class="text-muted mt-1 d-block" style="font-size: 0.78rem;"><i class="fas fa-lock mr-1"></i> Username permanen.</small>
                </div>

                <!-- Role (Readonly) -->
                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold text-dark mb-1" style="font-size: 0.85rem;">Hak Akses Sistem (Role)</label>
                  <div class="modern-input-group readonly-group">
                    <div class="input-icon">
                      <i class="fas fa-user-shield"></i>
                    </div>
                    <input type="text" value="{{ $roleLabelsCombined }}" disabled style="font-weight: 600;">
                  </div>
                  <input type="hidden" name="role" value="{{ $edit->role }}">
                </div>

                <!-- Status Wali Kelas -->
                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold text-dark mb-1" style="font-size: 0.85rem;">Status Wali Kelas</label>
                  <div class="modern-input-group readonly-group">
                    <div class="input-icon">
                      <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                    <input type="text" value="{{ isset($kelasWali) && $kelasWali->isNotEmpty() ? $kelasWali->pluck('nama_kelas')->implode(', ') : '-' }}" disabled style="font-weight: 600;">
                  </div>
                </div>

                <!-- Password Baru (Opsional) -->
                <div class="col-md-12 mb-2">
                  <label for="password" class="font-weight-bold text-dark mb-1" style="font-size: 0.85rem;">
                    Ubah Password Baru <span class="text-muted font-weight-normal">(Kosongkan jika tidak ingin merubah password)</span>
                  </label>
                  <div class="modern-input-group @error('password') is-invalid @enderror">
                    <div class="input-icon text-muted">
                      <i class="fas fa-key"></i>
                    </div>
                    <input type="password" id="password" name="password" placeholder="Masukkan password baru jika ingin merubah (minimal 8 karakter)">
                    <button class="toggle-btn toggle-password" type="button" data-target="#password" title="Lihat/Sembunyikan password">
                      <i class="fas fa-eye"></i>
                    </button>
                  </div>
                  @error('password')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/dashboard" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali ke Dashboard
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan Profil
              </button>
            </div>

          </form>
        </div>

        <!-- Card 2: Status Penugasan Mengajar & Alokasi JP -->
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          
          <!-- Header Card Bersih & Terpisah Rapi -->
          <div class="card-header bg-white py-3 border-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap">
              <div class="d-flex align-items-center mb-1 mb-md-0">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-xs mr-3 flex-shrink-0" style="width: 40px; height: 40px; font-size: 1.1rem;">
                  <i class="fas fa-book-open"></i>
                </div>
                <div>
                  <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1.05rem; line-height: 1.3;">
                    Status Penugasan Mengajar & Alokasi JP
                  </h5>
                  <p class="text-muted mb-0" style="font-size: 0.80rem; line-height: 1.3;">
                    Daftar mata pelajaran yang diampu, kelas, serta rincian jam pelajaran mingguan
                  </p>
                </div>
              </div>
              <div class="mt-2 mt-md-0">
                <span class="badge badge-primary px-3 py-2 shadow-xs font-weight-bold" style="border-radius: 8px; font-size: 0.85rem;">
                  <i class="fas fa-clock mr-1"></i> Total: {{ $totalJpSeminggu }} JP / Minggu
                </span>
              </div>
            </div>
          </div>

          <div class="card-body p-4">
            
            @if(isset($mapelDiampu) && $mapelDiampu->isNotEmpty())
              
              <!-- Cards Ringkasan Per Mata Pelajaran -->
              <div class="row mb-4">
                @foreach($mapelDiampu as $idMapel => $m)
                  <div class="col-md-6 mb-3">
                    <div class="card h-100 border shadow-none" style="background: #f8fafc; border-radius: 10px; border-color: #e2e8f0 !important;">
                      <div class="card-body p-3 d-flex flex-column justify-content-between">
                        <div>
                          <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">
                              {{ $m['nama_mapel'] }}
                            </h6>
                            <span class="badge badge-primary px-2 py-1 font-weight-bold flex-shrink-0 ml-2" style="border-radius: 6px; font-size: 0.78rem;">
                              {{ $m['total_jp'] }} JP / Minggu
                            </span>
                          </div>
                          @if(!empty($m['kode_mapel']))
                            <small class="text-muted d-block mb-2 font-weight-500">Kode: {{ $m['kode_mapel'] }}</small>
                          @endif
                        </div>
                        
                        <div class="text-muted pt-2 border-top mt-2" style="font-size: 0.78rem;">
                          <span class="font-weight-600 text-secondary d-block mb-1">Kelas yang diajar:</span>
                          <div class="d-flex flex-wrap" style="gap: 4px;">
                            @foreach($m['kelas_list'] as $kNama)
                              <span class="badge badge-light border text-dark px-2 py-1" style="font-size: 0.75rem; background: #fff;">
                                <i class="fas fa-users mr-1 text-primary"></i> {{ $kNama }}
                              </span>
                            @endforeach
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>

              <!-- Judul Bagian Tabel Jadwal -->
              <div class="d-flex align-items-center mb-3">
                <h6 class="text-secondary font-weight-bold text-uppercase mb-0" style="font-size: 0.78rem; letter-spacing: 0.5px;">
                  <i class="fas fa-calendar-alt mr-1 text-primary"></i> Rincian Jadwal Mengajar Mingguan
                </h6>
              </div>

              <!-- Tabel Lengkap Jadwal Mengajar -->
              <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0" style="font-size: 0.84rem;">
                  <thead>
                    <tr class="bg-light">
                      <th style="width: 45px; text-align: center;">No</th>
                      <th style="width: 110px; text-align: center;">Hari</th>
                      <th style="width: 140px; text-align: center;">Jam Ke / Waktu</th>
                      <th>Mata Pelajaran</th>
                      <th>Kelas</th>
                      <th style="width: 90px; text-align: center;">Beban JP</th>
                    </tr>
                  </thead>
                  <tbody>
                    @php
                      $hariBadge = [
                        'Senin' => 'badge-primary',
                        'Selasa' => 'badge-info',
                        'Rabu' => 'badge-success',
                        'Kamis' => 'badge-warning text-dark',
                        'Jumat' => 'badge-danger',
                        'Sabtu' => 'badge-secondary'
                      ];
                    @endphp
                    @foreach($jadwalMengajar as $jm)
                      @php $hariNama = $jm->hari_formatted ?? ucfirst(strtolower($jm->hari)); @endphp
                      <tr>
                        <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                        <td class="text-center">
                          <span class="badge {{ $hariBadge[$hariNama] ?? 'badge-secondary' }} px-2 py-1 font-weight-bold" style="border-radius: 6px; font-size: 0.78rem;">
                            {{ $hariNama }}
                          </span>
                        </td>
                        <td class="text-center">
                          <strong class="text-dark">Jam {{ $jm->jam_awal }} - {{ $jm->jam_akhir }}</strong>
                          <br><small class="text-muted">{{ $jm->waktu_awal }} - {{ $jm->waktu_akhir }}</small>
                        </td>
                        <td>
                          <strong class="text-dark">{{ $jm->mapel->nama_mapel ?? '-' }}</strong>
                        </td>
                        <td>
                          <span class="badge badge-light border text-secondary px-2 py-1 font-weight-bold" style="font-size: 0.78rem;">
                            {{ $jm->kelas->nama_kelas ?? '-' }}
                          </span>
                        </td>
                        <td class="text-center">
                          <span class="badge badge-primary px-2 py-1 font-weight-bold" style="border-radius: 6px; font-size: 0.80rem;">
                            {{ $jm->jp_count }} JP
                          </span>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                  <tfoot class="bg-light font-weight-bold">
                    <tr>
                      <td colspan="5" class="text-right">Total Akumulasi Beban Mengajar:</td>
                      <td class="text-center text-primary font-weight-bold" style="font-size: 0.92rem;">
                        {{ $totalJpSeminggu }} JP
                      </td>
                    </tr>
                  </tfoot>
                </table>
              </div>

            @else
              <div class="text-center py-5 text-muted bg-light rounded-lg" style="border-radius: 10px;">
                <i class="fas fa-calendar-times fa-3x mb-3 text-muted" style="opacity: 0.35;"></i>
                <h6 class="font-weight-bold text-dark">Belum Ada Jadwal Mengajar</h6>
                <p class="mb-0" style="font-size: 0.84rem;">Belum ada penugasan mata pelajaran yang terdaftar untuk guru ini di sistem.</p>
              </div>
            @endif

          </div>
        </div>

      </div>

    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Live Preview Image on Selection
  function previewProfileImage(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        var imgAvatar = document.getElementById('profileAvatarImg');
        var initialAvatar = document.getElementById('profileAvatarInitial');
        var newBadge = document.getElementById('fotoNewBadge');
        
        if (imgAvatar) {
          imgAvatar.src = e.target.result;
          imgAvatar.style.display = 'block';
        }
        if (initialAvatar) {
          initialAvatar.style.display = 'none';
        }
        if (newBadge) {
          newBadge.style.display = 'block';
        }
      };
      reader.readAsDataURL(input.files[0]);
    }
  }

  $(document).ready(function() {
    // Toggle Password Visibility
    $('.toggle-password').on('click', function() {
      var targetInput = $($(this).data('target'));
      var icon = $(this).find('i');
      
      if (targetInput.attr('type') === 'password') {
        targetInput.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
      } else {
        targetInput.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
      }
    });
  });
</script>
@endpush
