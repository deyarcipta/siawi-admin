@extends($layout)
@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-edit"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Pengisian Jurnal Praktik</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Catat kegiatan pembelajaran dan progres jobsheet laboratorium siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/kejuruan/jurnal-praktik" class="text-primary font-weight-500">Jurnal Praktik</a></li>
          <li class="breadcrumb-item active">Isi Jurnal</li>
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
              <i class="fas fa-clipboard-check text-primary mr-2"></i> Formulir Jurnal Pertemuan
            </h5>
          </div>

          <form action="/admin/kejuruan/jurnal-praktik" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="card-body p-4 pt-2">
              <div class="row">
                <div class="col-md-8 form-group mb-3">
                  <label for="id_jadwal_praktik" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Sesi Jadwal Praktik <span class="text-danger">*</span></label>
                  <select name="id_jadwal_praktik" id="id_jadwal_praktik" class="form-control select2bs4 @error('id_jadwal_praktik') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Jadwal Praktik</option>
                    @foreach($jadwalList as $jl)
                      <option value="{{ $jl->id_jadwal_praktik }}" {{ (old('id_jadwal_praktik', $jadwal->id_jadwal_praktik ?? null) == $jl->id_jadwal_praktik) ? 'selected' : '' }}>
                        {{ $jl->hari }} ({{ substr($jl->jam_mulai, 0, 5) }}-{{ substr($jl->jam_selesai, 0, 5) }}) - {{ $jl->kelas->nama_kelas ?? '-' }} - {{ $jl->mapel->nama_mapel ?? '-' }} [{{ $jl->lab->nama_lab ?? '-' }}]
                      </option>
                    @endforeach
                  </select>
                  @error('id_jadwal_praktik')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-4 form-group mb-3">
                  <label for="tanggal" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Tanggal Praktik <span class="text-danger">*</span></label>
                  <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" value="{{ old('tanggal', $tanggal) }}" required style="border-radius: 8px; height: 42px;">
                  @error('tanggal')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <div class="form-group mb-3">
                <label for="modul_praktik_id" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Pilih Modul Praktik (Bank Modul) <span class="badge badge-light border text-primary ml-1">Opsional</span>
                </label>
                <select name="modul_praktik_id" id="modul_praktik_id" class="form-control select2bs4 @error('modul_praktik_id') is-invalid @enderror" style="border-radius: 8px; height: 42px;">
                  <option value="" data-judul="" data-alat="">-- Bebas / Tanpa Modul Khusus --</option>
                  @foreach($modulList as $m)
                    <option value="{{ $m->id_modul_praktik }}" 
                      data-judul="{{ $m->judul }}" 
                      data-alat="{{ $m->alat_bahan }}"
                      {{ old('modul_praktik_id') == $m->id_modul_praktik ? 'selected' : '' }}>
                      [{{ $m->kode_modul }}] {{ $m->judul }} (v{{ $m->versi }}) - {{ $m->kompetensi->nama_kompetensi ?? '' }}
                    </option>
                  @endforeach
                </select>
                <small class="text-muted">Memilih modul akan otomatis menyarankan materi dan daftar alat/bahan di bawah.</small>
                @error('modul_praktik_id')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="materi" class="font-weight-bold text-dark d-flex align-items-center justify-content-between" style="font-size: 0.85rem;">
                  <span>Materi Praktik / Topik Jobsheet <span class="text-danger">*</span></span>
                  <small class="text-muted font-weight-normal"><i class="fas fa-magic text-primary mr-1"></i> Editor Dokumen</small>
                </label>
                <textarea class="form-control summernote-kejuruan @error('materi') is-invalid @enderror" id="materi" name="materi" rows="4" placeholder="Tuliskan materi praktik, kompetensi yang dipraktikkan, atau rangkuman jobsheet...">{{ old('materi') }}</textarea>
                @error('materi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label for="jumlah_hadir" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Jumlah Siswa Hadir <span class="text-danger">*</span>
                  </label>
                  <div class="input-group">
                    <input type="number" class="form-control @error('jumlah_hadir') is-invalid @enderror" id="jumlah_hadir" name="jumlah_hadir" value="{{ old('jumlah_hadir', $defaultHadir) }}" min="0" required style="border-radius: 8px 0 0 8px; height: 42px;">
                    <div class="input-group-append">
                      <span class="input-group-text bg-light text-muted" style="border-radius: 0 8px 8px 0;">Siswa</span>
                    </div>
                  </div>
                  <small class="text-muted">Nilai otomatis disinkronkan dari absensi harian kelas.</small>
                  @error('jumlah_hadir')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                  <label for="foto_praktik" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Dokumentasi Foto Praktik (Opsional)
                  </label>
                  <input type="file" class="form-control-file @error('foto_praktik') is-invalid @enderror" id="foto_praktik" name="foto_praktik" accept="image/*" style="font-size: 0.85rem;">
                  <small class="text-muted d-block mt-1">Format: JPG, PNG, WebP (Maks. 3MB)</small>
                  @error('foto_praktik')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <div class="form-group mb-3">
                <label for="alat_dipakai" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Alat & Software Laboratorium yang Digunakan
                </label>
                <input type="text" class="form-control @error('alat_dipakai') is-invalid @enderror" id="alat_dipakai" name="alat_dipakai" placeholder="Contoh: 18 Unit PC Client, 9 Unit Mikrotik RB750, Winbox v3.37, Kabel UTP Cat6" value="{{ old('alat_dipakai') }}" style="border-radius: 8px; height: 42px;">
                @error('alat_dipakai')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label for="kendala" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Kendala Praktik (Jika Ada)
                  </label>
                  <textarea class="form-control @error('kendala') is-invalid @enderror" id="kendala" name="kendala" rows="2" placeholder="Contoh: 1 unit adaptor router rusak, koneksi LAN switch 2 longgar" style="border-radius: 8px;">{{ old('kendala') }}</textarea>
                  @error('kendala')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                  <label for="catatan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                    Catatan Tambahan / Tugas Lanjutan
                  </label>
                  <textarea class="form-control @error('catatan') is-invalid @enderror" id="catatan" name="catatan" rows="2" placeholder="Contoh: Jobsheet nomor 4 dilanjutkan pada pertemuan minggu depan" style="border-radius: 8px;">{{ old('catatan') }}</textarea>
                  @error('catatan')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>
            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/kejuruan/jurnal-praktik" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Jurnal Praktik
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-12 col-lg-4 mt-3 mt-lg-0">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; background-color: #f8fafc;">
          <div class="card-body p-3">
            <h6 class="font-weight-bold text-dark mb-2">
              <i class="fas fa-info-circle text-primary mr-1"></i> Ketentuan Jurnal
            </h6>
            <ul class="text-muted small pl-3 mb-0" style="line-height: 1.6;">
              <li>Guru mengisi satu jurnal untuk setiap pertemuan praktik yang terlaksana.</li>
              <li>Data absensi diambil otomatis dari sistem presensi kelas harian.</li>
              <li>Jurnal yang disimpan akan berstatus <strong>Menunggu Persetujuan</strong> dari Kepala Program Keahlian (Kaprog).</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
  .note-editor.note-frame {
    border: 1px solid #cbd5e1 !important;
    border-radius: 10px !important;
    overflow: hidden;
    box-shadow: 0 1px 2px rgba(0,0,0,0.04);
  }
  .note-editor.note-frame .note-toolbar {
    background-color: #f8fafc !important;
    border-bottom: 1px solid #e2e8f0 !important;
    padding: 6px 8px !important;
  }
  .note-editor.note-frame .note-btn {
    background: #ffffff !important;
    border: 1px solid #cbd5e1 !important;
    color: #334155 !important;
    border-radius: 6px !important;
    padding: 3px 8px !important;
    font-size: 0.82rem !important;
  }
  .note-editor.note-frame .note-btn:hover {
    background: #e2e8f0 !important;
    color: #0f172a !important;
  }
  .note-editor.note-frame .note-btn.active {
    background: #0284c7 !important;
    color: #ffffff !important;
    border-color: #0284c7 !important;
  }
  .note-editor.note-frame .note-statusbar {
    background-color: #f8fafc !important;
    border-top: 1px solid #e2e8f0 !important;
  }
</style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Inisialisasi Summernote dengan toolbar mirip CBT / Dokumen Pembelajaran
    if (window.jQuery && $.fn.summernote) {
      $('#materi').summernote({
        placeholder: 'Tuliskan materi praktik, topik jobsheet, atau poin-poin kegiatan praktikum di sini...',
        tabsize: 2,
        height: 180,
        toolbar: [
          ['style', ['bold', 'italic', 'underline', 'strikethrough']],
          ['insert', ['picture', 'video', 'link']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['misc', ['codeview', 'clear']]
        ]
      });
    }

    const modulSelect = document.getElementById('modul_praktik_id');
    const alatInput = document.getElementById('alat_dipakai');

    if (modulSelect) {
      modulSelect.addEventListener('change', function () {
        const selected = this.options[this.selectedIndex];
        const judul = selected.getAttribute('data-judul');
        const alat = selected.getAttribute('data-alat');

        if (judul) {
          if (window.jQuery && $.fn.summernote) {
            $('#materi').summernote('code', judul);
          } else {
            const materiInput = document.getElementById('materi');
            if (materiInput) materiInput.value = judul;
          }
        }
        if (alat && alatInput && !alatInput.value.trim()) {
          alatInput.value = alat;
        }
      });
    }
  });
</script>
@endsection
