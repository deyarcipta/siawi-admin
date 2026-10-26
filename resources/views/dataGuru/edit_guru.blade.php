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
                  <style>
                    .role-card-grid {
                      display: grid;
                      grid-template-columns: repeat(4, 1fr);
                      gap: 12px;
                    }
                    @media (max-width: 991.98px) {
                      .role-card-grid {
                        grid-template-columns: repeat(2, 1fr);
                      }
                    }
                    @media (max-width: 575.98px) {
                      .role-card-grid {
                        grid-template-columns: 1fr;
                      }
                    }
                    .role-card-box {
                      background: #f8fafc;
                      border: 1px solid #e2e8f0;
                      border-radius: 10px;
                      padding: 10px 12px 10px 36px;
                      height: 100%;
                      min-height: 64px;
                      display: flex;
                      align-items: center;
                      transition: all 0.2s ease;
                      box-sizing: border-box;
                      margin: 0 !important;
                    }
                    .role-card-box:hover {
                      border-color: #cbd5e1;
                      background: #f1f5f9;
                    }
                  </style>
                  <div class="role-card-grid">
                    @php
                      $availableRoles = [
                        'guru' => ['label' => 'Guru Mapel', 'desc' => 'Jurnal mengajar & jadwal'],
                        'kaprog' => ['label' => 'Kaprog', 'desc' => 'Kepala program keahlian'],
                        'guru_jurusan' => ['label' => 'Guru Jurusan', 'desc' => 'Guru produktif kejuruan'],
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
                      <div class="custom-control custom-checkbox role-card-box">
                        <input type="checkbox" class="custom-control-input role-checkbox" id="role_{{ $rKey }}" name="roles[]" value="{{ $rKey }}" {{ in_array($rKey, $selectedRoles) ? 'checked' : '' }}>
                        <label class="custom-control-label font-weight-bold text-dark w-100 mb-0" for="role_{{ $rKey }}" style="cursor: pointer; font-size: 0.84rem; line-height: 1.25;">
                          <span class="d-block" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $rMeta['label'] }}</span>
                          <small class="d-block text-muted font-weight-normal mt-1" style="font-size: 0.72rem; line-height: 1.2; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $rMeta['desc'] }}</small>
                        </label>
                      </div>
                    @endforeach
                  </div>
                  @error('roles')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror

                  <!-- Container Pilihan Program Keahlian untuk Kaprog -->
                  <div id="kaprog_jurusan_box" class="mt-3 p-3 bg-white rounded border shadow-sm" style="display: {{ in_array('kaprog', $selectedRoles) ? 'block' : 'none' }}; border-color: #cbd5e1 !important;">
                    <label for="id_jurusan_kaprog" class="font-weight-bold text-dark d-block mb-1" style="font-size: 0.85rem;">
                      Program Keahlian (Khusus Kaprog) <span class="text-danger">*</span>
                    </label>
                    <p class="text-muted small mb-2">Pilih jurusan/program keahlian yang dipimpin oleh guru ini.</p>
                    <select name="id_jurusan_kaprog" id="id_jurusan_kaprog" class="form-control select2bs4 @error('id_jurusan_kaprog') is-invalid @enderror" style="border-radius: 8px; height: 42px;">
                      <option value="">Pilih Program Keahlian</option>
                      @if(isset($jurusans))
                        @foreach($jurusans as $j)
                          <option value="{{ $j->id_jurusan }}" {{ (old('id_jurusan_kaprog', $kaprogJurusanId ?? null) == $j->id_jurusan) ? 'selected' : '' }}>
                            {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                          </option>
                        @endforeach
                      @endif
                    </select>
                    @error('id_jurusan_kaprog')
                      <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                    @enderror
                  </div>

                  <!-- Container Pilihan Program Keahlian untuk Guru Jurusan (Bisa Pilih Lebih dari 1 Jurusan) -->
                  <div id="guru_jurusan_box" class="mt-3 p-3 bg-white rounded border shadow-sm" style="display: {{ in_array('guru_jurusan', $selectedRoles) ? 'block' : 'none' }}; border-color: #cbd5e1 !important; border-radius: 12px;">
                    <div class="d-flex align-items-center justify-content-between flex-wrap mb-1">
                      <label class="font-weight-bold text-dark d-block mb-0" style="font-size: 0.86rem;">
                        <i class="fas fa-layer-group text-primary mr-1"></i> Program Keahlian (Guru Jurusan)
                      </label>
                      <span class="badge badge-light border text-primary px-2 py-1 font-weight-500" style="font-size: 0.72rem;">
                        <i class="fas fa-check-double mr-1"></i> Bisa Pilih Lebih dari 1 Jurusan
                      </span>
                    </div>
                    <p class="text-muted small mb-3">Centang seluruh program keahlian tempat guru ini mengajar materi dan jadwal praktik kejuruan.</p>

                    <style>
                      .jurusan-card-grid {
                        display: grid;
                        grid-template-columns: repeat(2, 1fr);
                        gap: 10px;
                      }
                      @media (max-width: 768px) {
                        .jurusan-card-grid {
                          grid-template-columns: 1fr;
                        }
                      }
                      .jurusan-card-box {
                        background: #f8fafc;
                        border: 1.5px solid #e2e8f0;
                        border-radius: 12px;
                        padding: 10px 14px;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        transition: all 0.2s ease;
                        margin: 0 !important;
                      }
                      .jurusan-card-box:hover {
                        border-color: #93c5fd;
                        background: #f0f9ff;
                      }
                      .jurusan-card-box:has(.custom-control-input:checked) {
                        border-color: #0284c7 !important;
                        background: #f0f9ff !important;
                        box-shadow: 0 2px 6px rgba(2, 132, 199, 0.08);
                      }
                      .jurusan-card-box .icon-box {
                        width: 40px;
                        height: 40px;
                        border-radius: 10px;
                        background: #e2e8f0;
                        color: #475569;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        font-size: 1.1rem;
                        flex-shrink: 0;
                        transition: all 0.2s ease;
                      }
                      .jurusan-card-box:has(.custom-control-input:checked) .icon-box {
                        background: #e0f2fe;
                        color: #0284c7;
                      }
                    </style>

                    <div class="jurusan-card-grid">
                      @if(isset($jurusans))
                        @php
                          $currentJurusanGurus = old('id_jurusan_guru', $guruJurusanIds ?? []);
                        @endphp
                        @foreach($jurusans as $j)
                          <div class="jurusan-card-box position-relative">
                            <div class="d-flex align-items-center" style="gap: 12px; min-width: 0; flex: 1;">
                              <div class="icon-box">
                                <i class="fas fa-graduation-cap"></i>
                              </div>
                              <div style="min-width: 0;">
                                <label class="font-weight-bold text-dark text-truncate mb-0 d-block" for="jurusan_guru_{{ $j->id_jurusan }}" style="cursor: pointer; font-size: 0.88rem;" title="{{ $j->nama_jurusan }}">
                                  {{ $j->nama_jurusan }}
                                </label>
                                <div class="d-flex align-items-center mt-1" style="gap: 6px;">
                                  <span class="badge badge-light border text-secondary px-1 py-0" style="font-size: 0.72rem; font-weight: 600;">
                                    {{ $j->kode_jurusan }}
                                  </span>
                                  @if($j->labs && $j->labs->count() > 0)
                                    <span class="badge badge-light border text-primary px-1 py-0" style="font-size: 0.7rem;">
                                      {{ $j->labs->count() }} Lab
                                    </span>
                                  @endif
                                </div>
                              </div>
                            </div>
                            <div class="custom-control custom-checkbox ml-2">
                              <input type="checkbox" class="custom-control-input" id="jurusan_guru_{{ $j->id_jurusan }}" name="id_jurusan_guru[]" value="{{ $j->id_jurusan }}" {{ in_array($j->id_jurusan, $currentJurusanGurus) ? 'checked' : '' }}>
                              <label class="custom-control-label" for="jurusan_guru_{{ $j->id_jurusan }}" style="cursor: pointer;"></label>
                            </div>
                          </div>
                        @endforeach
                      @endif
                    </div>
                    @error('id_jurusan_guru')
                      <small class="text-danger font-weight-500 mt-2 d-block">{{ $message }}</small>
                    @enderror
                  </div>

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

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const roleKaprog = document.getElementById('role_kaprog');
    const boxKaprog = document.getElementById('kaprog_jurusan_box');
    const selectKaprog = document.getElementById('id_jurusan_kaprog');

    const roleGuruJurusan = document.getElementById('role_guru_jurusan');
    const boxGuruJurusan = document.getElementById('guru_jurusan_box');

    function toggleKaprog() {
      if (roleKaprog && roleKaprog.checked) {
        boxKaprog.style.display = 'block';
        if (selectKaprog) selectKaprog.setAttribute('required', 'required');
      } else {
        boxKaprog.style.display = 'none';
        if (selectKaprog) selectKaprog.removeAttribute('required');
      }
    }

    function toggleGuruJurusan() {
      if (roleGuruJurusan && roleGuruJurusan.checked) {
        boxGuruJurusan.style.display = 'block';
      } else {
        boxGuruJurusan.style.display = 'none';
      }
    }

    if (roleKaprog) {
      roleKaprog.addEventListener('change', toggleKaprog);
      toggleKaprog();
    }

    if (roleGuruJurusan) {
      roleGuruJurusan.addEventListener('change', toggleGuruJurusan);
      toggleGuruJurusan();
    }
  });
</script>
@endsection