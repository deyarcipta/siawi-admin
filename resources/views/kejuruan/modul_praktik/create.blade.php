@extends($layout)

@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-plus"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Buat Modul Praktik Baru</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Susun modul pekerjaan praktik kejuruan beserta rubrik indikator penilaiannya</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/modul-praktik" class="text-primary font-weight-500">Modul Praktik</a></li>
          <li class="breadcrumb-item active">Buat Baru</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 10px;">
        <h6 class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-1"></i> Periksa Isian Formulir:</h6>
        <ul class="mb-0 pl-3">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <form action="/admin/kejuruan/modul-praktik" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <!-- Kolom Kiri: Informasi Inti Modul -->
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom">
              <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1rem;">
                <i class="fas fa-file-alt text-primary mr-2"></i> Materi & Panduan Praktikum
              </h5>
            </div>
            <div class="card-body p-4">

              <div class="form-group mb-3">
                <label for="id_kompetensi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Target Capaian Kompetensi <span class="text-danger">*</span>
                </label>
                <select name="id_kompetensi" id="id_kompetensi" class="form-control select2bs4 @error('id_kompetensi') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                  <option value="">-- Pilih Capaian Kompetensi --</option>
                  @foreach($kompetensis as $k)
                    <option value="{{ $k->id_kompetensi }}" {{ old('id_kompetensi') == $k->id_kompetensi ? 'selected' : '' }}>
                      [{{ $k->kode_kompetensi }}] {{ $k->nama_kompetensi }} - Kelas {{ $k->tingkat }} (Sem. {{ $k->semester }})
                      @if($k->jurusan) ({{ $k->jurusan->nama_jurusan }}) @endif
                    </option>
                  @endforeach
                </select>
                @error('id_kompetensi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="judul" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Judul Modul Praktik / Jobsheet <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul') }}" placeholder="Contoh: Konfigurasi Jaringan Routing Statis Mikrotik" required style="border-radius: 8px; height: 42px;">
                @error('judul')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="tujuan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Tujuan Pembelajaran Praktik <span class="text-danger">*</span>
                </label>
                <textarea class="form-control @error('tujuan') is-invalid @enderror" id="tujuan" name="tujuan" rows="3" placeholder="Siswa mampu mengonfigurasi IP address, tabel routing, dan menguji konektivitas antar subnet..." required style="border-radius: 8px;">{{ old('tujuan') }}</textarea>
                @error('tujuan')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="alat_bahan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Alat dan Bahan Praktik <span class="text-danger">*</span>
                </label>
                <textarea class="form-control @error('alat_bahan') is-invalid @enderror" id="alat_bahan" name="alat_bahan" rows="3" placeholder="1. Routerboard Mikrotik RB941&#10;2. Kabel UTP Patch Cord Cat6&#10;3. PC Komputer Client&#10;4. Aplikasi Winbox..." required style="border-radius: 8px;">{{ old('alat_bahan') }}</textarea>
                @error('alat_bahan')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="langkah_kerja" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Langkah-Langkah Kerja / Prosedur Praktik <span class="text-danger">*</span>
                </label>
                <textarea class="form-control @error('langkah_kerja') is-invalid @enderror" id="langkah_kerja" name="langkah_kerja" rows="6" placeholder="Langkah 1: Hubungkan PC ke port Ether2 routerboard.&#10;Langkah 2: Buka aplikasi Winbox dan temukan MAC address router.&#10;Langkah 3: Lakukan konfigurasi IP address sesuai skema topologi..." required style="border-radius: 8px;">{{ old('langkah_kerja') }}</textarea>
                @error('langkah_kerja')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

            </div>
          </div>

          <!-- Card Rubrik Penilaian -->
          <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
              <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1rem;">
                <i class="fas fa-list-check text-primary mr-2"></i> Rubrik Indikator Penilaian
              </h5>
              <button type="button" class="btn btn-outline-primary btn-sm px-3" id="btnAddRubrik" style="border-radius: 6px;">
                <i class="fas fa-plus mr-1"></i> Tambah Indikator
              </button>
            </div>
            <div class="card-body p-4">
              <p class="text-muted small mb-3">
                Tentukan aspek keterampilan yang dinilai beserta bobotnya untuk sesi penilaian praktikum nanti.
              </p>

              <div id="rubrikContainer">
                <div class="rubrik-row p-3 mb-2 rounded border bg-light" id="rubrik-0">
                  <div class="row">
                    <div class="col-md-7 form-group mb-2">
                      <label class="small font-weight-bold text-dark mb-1">Nama Indikator / Kriteria Keterampilan</label>
                      <input type="text" name="rubrik_indikator[]" class="form-control" placeholder="Contoh: Keselamatan Kerja & Penggunaan K3" value="Persiapan Alat & K3" required style="border-radius: 6px;">
                    </div>
                    <div class="col-md-3 form-group mb-2">
                      <label class="small font-weight-bold text-dark mb-1">Bobot (%)</label>
                      <input type="number" name="rubrik_bobot[]" class="form-control" value="20" min="1" max="100" required style="border-radius: 6px;">
                    </div>
                    <div class="col-md-2 form-group mb-2 d-flex align-items-end">
                      <button type="button" class="btn btn-outline-danger btn-block btn-remove-rubrik" style="border-radius: 6px; height: 38px;" title="Hapus Indikator">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="rubrik-row p-3 mb-2 rounded border bg-light" id="rubrik-1">
                  <div class="row">
                    <div class="col-md-7 form-group mb-2">
                      <label class="small font-weight-bold text-dark mb-1">Nama Indikator / Kriteria Keterampilan</label>
                      <input type="text" name="rubrik_indikator[]" class="form-control" placeholder="Contoh: Langkah Kerja & Konfigurasi" value="Proses Kerja & Konfigurasi" required style="border-radius: 6px;">
                    </div>
                    <div class="col-md-3 form-group mb-2">
                      <label class="small font-weight-bold text-dark mb-1">Bobot (%)</label>
                      <input type="number" name="rubrik_bobot[]" class="form-control" value="50" min="1" max="100" required style="border-radius: 6px;">
                    </div>
                    <div class="col-md-2 form-group mb-2 d-flex align-items-end">
                      <button type="button" class="btn btn-outline-danger btn-block btn-remove-rubrik" style="border-radius: 6px; height: 38px;" title="Hapus Indikator">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>

                <div class="rubrik-row p-3 mb-2 rounded border bg-light" id="rubrik-2">
                  <div class="row">
                    <div class="col-md-7 form-group mb-2">
                      <label class="small font-weight-bold text-dark mb-1">Nama Indikator / Kriteria Keterampilan</label>
                      <input type="text" name="rubrik_indikator[]" class="form-control" placeholder="Contoh: Pengujian & Hasil Akhir" value="Hasil Kerja & Pengujian" required style="border-radius: 6px;">
                    </div>
                    <div class="col-md-3 form-group mb-2">
                      <label class="small font-weight-bold text-dark mb-1">Bobot (%)</label>
                      <input type="number" name="rubrik_bobot[]" class="form-control" value="30" min="1" max="100" required style="border-radius: 6px;">
                    </div>
                    <div class="col-md-2 form-group mb-2 d-flex align-items-end">
                      <button type="button" class="btn btn-outline-danger btn-block btn-remove-rubrik" style="border-radius: 6px; height: 38px;" title="Hapus Indikator">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>

        <!-- Kolom Kanan: Pengaturan & Lampiran -->
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom">
              <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1rem;">
                <i class="fas fa-cog text-primary mr-2"></i> Publikasi & Lampiran
              </h5>
            </div>
            <div class="card-body p-4">

              <div class="form-group mb-3">
                <label for="status" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Status Modul <span class="text-danger">*</span>
                </label>
                <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                  <option value="terbit" {{ old('status') === 'terbit' ? 'selected' : '' }}>Terbitkan Langsung</option>
                  <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Simpan Sebagai Draft</option>
                </select>
                <small class="text-muted">Modul berstatus <strong>Terbit</strong> dapat langsung dipilih guru saat mengisi Jurnal Praktik.</small>
              </div>

              <div class="form-group mb-3">
                <label for="file_lembar_kerja" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Lampiran Berkas Jobsheet / Gambar (Opsional)
                </label>
                <input type="file" class="form-control-file @error('file_lembar_kerja') is-invalid @enderror" id="file_lembar_kerja" name="file_lembar_kerja" accept=".pdf,image/*">
                <small class="text-muted d-block mt-1">Format didukung: PDF, JPG, PNG (Maksimal 10MB).</small>
                @error('file_lembar_kerja')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="alert alert-info border-0 p-3 mb-0" style="border-radius: 8px; font-size: 0.83rem;">
                <i class="fas fa-info-circle mr-1"></i> Modul akan dibuat pada versi perdana <strong>v1.0</strong>. Setiap perbaikan di kemudian hari dapat disimpan sebagai revisi versi baru dengan riwayat lengkap.
              </div>

            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/kejuruan/modul-praktik" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Batal
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Modul
              </button>
            </div>
          </div>
        </div>
      </div>
    </form>

  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('rubrikContainer');
    const btnAdd = document.getElementById('btnAddRubrik');
    let rubrikCount = 3;

    btnAdd.addEventListener('click', function () {
      const rowId = 'rubrik-' + rubrikCount;
      const html = `
        <div class="rubrik-row p-3 mb-2 rounded border bg-light" id="${rowId}">
          <div class="row">
            <div class="col-md-7 form-group mb-2">
              <label class="small font-weight-bold text-dark mb-1">Nama Indikator / Kriteria Keterampilan</label>
              <input type="text" name="rubrik_indikator[]" class="form-control" placeholder="Contoh: Ketepatan Analisa & Laporan" required style="border-radius: 6px;">
            </div>
            <div class="col-md-3 form-group mb-2">
              <label class="small font-weight-bold text-dark mb-1">Bobot (%)</label>
              <input type="number" name="rubrik_bobot[]" class="form-control" value="20" min="1" max="100" required style="border-radius: 6px;">
            </div>
            <div class="col-md-2 form-group mb-2 d-flex align-items-end">
              <button type="button" class="btn btn-outline-danger btn-block btn-remove-rubrik" style="border-radius: 6px; height: 38px;" title="Hapus Indikator">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>
        </div>
      `;
      container.insertAdjacentHTML('beforeend', html);
      rubrikCount++;
    });

    container.addEventListener('click', function (e) {
      if (e.target.closest('.btn-remove-rubrik')) {
        const row = e.target.closest('.rubrik-row');
        if (container.querySelectorAll('.rubrik-row').length > 1) {
          row.remove();
        } else {
          alert('Minimal harus ada 1 indikator penilaian.');
        }
      }
    });
  });
</script>
@endsection
