@extends($layout)

@section('content')
<!-- Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <a href="{{ route('admin.surat-keluar.index') }}" class="btn btn-light rounded-circle shadow-sm mr-3 text-secondary" style="width: 42px; height: 42px; display: inline-flex; align-items: center; justify-content: center;">
          <i class="fas fa-arrow-left"></i>
        </a>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Buat & Catat Surat Keluar Baru</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Nomor surat akan digenerate otomatis secara berurutan sesuai klasifikasi & tanggal surat</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="{{ route('admin.surat-keluar.index') }}" class="text-primary font-weight-500">Surat Keluar</a></li>
          <li class="breadcrumb-item active">Buat Baru</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main Content -->
<div class="content">
  <div class="container-fluid">

    <!-- Error Messages -->
    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0" role="alert" style="border-radius: 10px;">
        <h6 class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-2"></i> Mohon lengkapi data berikut:</h6>
        <ul class="mb-0 pl-3">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <form action="{{ route('admin.surat-keluar.store') }}" method="POST" enctype="multipart/form-data" id="formSuratKeluar">
      @csrf

      <div class="row">
        <!-- Kolom Kiri: Form Input -->
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
              <i class="fas fa-file-signature text-primary mr-2"></i>
              <h5 class="font-weight-bold text-dark mb-0" style="font-size: 1rem;">Formulir Informasi Surat</h5>
            </div>

            <div class="card-body">
              <div class="row">
                <!-- Klasifikasi Surat -->
                <div class="col-md-6 form-group">
                  <label for="kode_klasifikasi" class="font-weight-bold text-dark mb-1">
                    Klasifikasi Surat <span class="text-danger">*</span>
                  </label>
                  <select class="form-control select2 @error('kode_klasifikasi') is-invalid @enderror" id="kode_klasifikasi" name="kode_klasifikasi" required>
                    @foreach($klasifikasiList as $kode => $nama)
                      <option value="{{ $kode }}" {{ old('kode_klasifikasi', 'TU') == $kode ? 'selected' : '' }}>
                        [{{ $kode }}] {{ $nama }}
                      </option>
                    @endforeach
                  </select>
                  <small class="text-muted">Menentukan kode segmen surat (contoh: TU, SK-SISWA, ST).</small>
                </div>

                <!-- Tanggal Surat -->
                <div class="col-md-6 form-group">
                  <label for="tanggal_surat" class="font-weight-bold text-dark mb-1">
                    Tanggal Surat <span class="text-danger">*</span>
                  </label>
                  <input type="date" class="form-control @error('tanggal_surat') is-invalid @enderror" id="tanggal_surat" name="tanggal_surat" value="{{ old('tanggal_surat', $today) }}" required>
                  <small class="text-muted">Bulan & tahun surat akan disesuaikan otomatis dari tanggal ini.</small>
                </div>
              </div>

              <!-- Perihal -->
              <div class="form-group">
                <label for="perihal" class="font-weight-bold text-dark mb-1">
                  Perihal / Hal Surat <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('perihal') is-invalid @enderror" id="perihal" name="perihal" value="{{ old('perihal') }}" placeholder="Contoh: Surat Keterangan Aktif Sekolah / Undangan Rapat Komite" required>
              </div>

              <!-- Tujuan Surat -->
              <div class="form-group">
                <label for="tujuan_surat" class="font-weight-bold text-dark mb-1">
                  Tujuan / Penerima Surat <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('tujuan_surat') is-invalid @enderror" id="tujuan_surat" name="tujuan_surat" value="{{ old('tujuan_surat') }}" placeholder="Contoh: Orang Tua / Wali Siswa / PT. Astra Honda Motor" required>
              </div>

              <div class="row">
                <!-- Penandatangan -->
                <div class="col-md-6 form-group">
                  <label for="penandatangan" class="font-weight-bold text-dark mb-1">
                    Penandatangan Surat
                  </label>
                  <input type="text" class="form-control @error('penandatangan') is-invalid @enderror" id="penandatangan" name="penandatangan" value="{{ old('penandatangan', 'Kepala Sekolah') }}" placeholder="Contoh: Kepala Sekolah / Kepala Tata Usaha">
                </div>

                <!-- Kategori Terkait (Opsional) -->
                <div class="col-md-6 form-group">
                  <label for="id_siswa" class="font-weight-bold text-dark mb-1">
                    Siswa Terkait <small class="text-muted">(Jika ada, opsional)</small>
                  </label>
                  <select class="form-control select2 @error('id_siswa') is-invalid @enderror" id="id_siswa" name="id_siswa">
                    <option value="">-- Bukan Surat Khusus Siswa --</option>
                    @foreach($siswaList as $s)
                      <option value="{{ $s->id_siswa }}" {{ old('id_siswa') == $s->id_siswa ? 'selected' : '' }}>
                        {{ $s->nama_siswa }} ({{ $s->kelas->nama_kelas ?? 'Tanpa Kelas' }})
                      </option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="row">
                <!-- Guru Terkait (Opsional) -->
                <div class="col-md-6 form-group">
                  <label for="id_guru" class="font-weight-bold text-dark mb-1">
                    Guru / Pegawai Terkait <small class="text-muted">(Jika ada, misal Surat Tugas)</small>
                  </label>
                  <select class="form-control select2 @error('id_guru') is-invalid @enderror" id="id_guru" name="id_guru">
                    <option value="">-- Bukan Surat Tugas Guru --</option>
                    @foreach($guruList as $g)
                      <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                        {{ $g->nama_guru }}
                      </option>
                    @endforeach
                  </select>
                </div>

                <!-- File Lampiran -->
                <div class="col-md-6 form-group">
                  <label for="file_lampiran" class="font-weight-bold text-dark mb-1">
                    Arsip / Scan Berkas <small class="text-muted">(PDF / JPG / PNG, Max 5MB)</small>
                  </label>
                  <div class="custom-file">
                    <input type="file" class="custom-file-input @error('file_lampiran') is-invalid @enderror" id="file_lampiran" name="file_lampiran" accept=".pdf,.jpg,.jpeg,.png">
                    <label class="custom-file-label" for="file_lampiran">Pilih berkas arsip...</label>
                  </div>
                </div>
              </div>

              <!-- Ringkasan / Keterangan Tambahan -->
              <div class="form-group mb-0">
                <label for="keterangan" class="font-weight-bold text-dark mb-1">
                  Catatan / Keterangan Tambahan <small class="text-muted">(Opsional)</small>
                </label>
                <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3" placeholder="Tambahkan catatan khusus terkait surat ini jika diperlukan...">{{ old('keterangan') }}</textarea>
              </div>

            </div>
          </div>
        </div>

        <!-- Kolom Kanan: Live Preview Penomoran & Card Aksi -->
        <div class="col-lg-4">
          <!-- Live Preview Box -->
          <div class="card border-0 shadow-sm mb-4 bg-white" style="border-radius: 12px; border-top: 4px solid #1d72fe !important;">
            <div class="card-body p-4 text-center">
              <div class="text-primary text-uppercase font-weight-bold" style="font-size: 0.75rem; letter-spacing: 1px;">
                <i class="fas fa-magic mr-1"></i> Preview Nomor Otomatis
              </div>
              
              <div class="my-3 p-3 rounded text-dark" style="background: #f8fafc; border: 1px dashed #cbd5e1;">
                <div class="text-muted small mb-1" style="font-size: 0.78rem;">Nomor Surat Yang Akan Diterbitkan:</div>
                <div id="previewNomorSurat" class="font-weight-bold text-primary font-monospace" style="font-size: 1.15rem; word-break: break-all;">
                  {{ $nextNumberPreview['nomor_surat'] }}
                </div>
              </div>

              <div class="row text-left mt-3">
                <div class="col-6 mb-2">
                  <small class="text-muted d-block" style="font-size: 0.75rem;">No. Urut:</small>
                  <span id="previewNoUrut" class="font-weight-bold text-dark font-monospace" style="font-size: 0.95rem;">#{{ str_pad($nextNumberPreview['no_urut'], 3, '0', STR_PAD_LEFT) }}</span>
                </div>
                <div class="col-6 mb-2">
                  <small class="text-muted d-block" style="font-size: 0.75rem;">Bulan Romawi:</small>
                  <span id="previewBulan" class="font-weight-bold text-dark font-monospace" style="font-size: 0.95rem;">{{ $nextNumberPreview['bulan_romawi'] }}</span>
                </div>
                <div class="col-6">
                  <small class="text-muted d-block" style="font-size: 0.75rem;">Tahun Arsip:</small>
                  <span id="previewTahun" class="font-weight-bold text-dark font-monospace" style="font-size: 0.95rem;">{{ $nextNumberPreview['tahun'] }}</span>
                </div>
                <div class="col-6">
                  <small class="text-muted d-block" style="font-size: 0.75rem;">Kode Sekolah:</small>
                  <span class="font-weight-bold text-dark font-monospace" style="font-size: 0.95rem;">SMK-WI</span>
                </div>
              </div>

              <div class="border-top pt-3 mt-3 text-left">
                <small class="text-muted" style="font-size: 0.75rem; line-height: 1.3; display: block;">
                  <i class="fas fa-shield-alt text-info mr-1"></i> Sistem menjamin nomor urut tidak duplikat dengan database lock otomatis saat penyimpanan.
                </small>
              </div>
            </div>
          </div>

          <!-- Card Aksi Submit -->
          <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body p-4">
              <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold shadow-sm" style="border-radius: 8px;">
                <i class="fas fa-save mr-1"></i> Simpan & Terbitkan Nomor
              </button>
              <a href="{{ route('admin.surat-keluar.index') }}" class="btn btn-outline-secondary btn-block mt-2" style="border-radius: 8px;">
                <i class="fas fa-times mr-1"></i> Batalkan
              </a>
            </div>
          </div>
        </div>
      </div>

    </form>

  </div>
</div>

@section('scripts')
<script>
$(document).ready(function() {
  // Update label input custom file
  bsCustomFileInput.init();

  // Function untuk fetch preview nomor surat secara real-time via AJAX
  function updateNomorPreview() {
    var kode = $('#kode_klasifikasi').val();
    var tanggal = $('#tanggal_surat').val();

    if (!kode || !tanggal) return;

    $('#previewNomorSurat').html('<i class="fas fa-spinner fa-spin text-muted"></i> Memuat...');

    $.ajax({
      url: "{{ route('admin.surat-keluar.preview-nomor') }}",
      type: 'GET',
      data: {
        kode_klasifikasi: kode,
        tanggal_surat: tanggal
      },
      dataType: 'json',
      success: function(res) {
        if (res.success) {
          $('#previewNomorSurat').text(res.nomor_surat);
          $('#previewNoUrut').text('#' + String(res.no_urut).padStart(3, '0'));
          $('#previewBulan').text(res.bulan_romawi);
          $('#previewTahun').text(res.tahun);
        }
      },
      error: function() {
        $('#previewNomorSurat').text('001/' + kode + '/SMK-WI/...');
      }
    });
  }

  // Trigger saat klasifikasi atau tanggal berubah
  $('#kode_klasifikasi, #tanggal_surat').on('change', function() {
    updateNomorPreview();
  });
});
</script>
@endsection
@endsection
