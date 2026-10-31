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
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Edit Modul Praktik</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Perbarui materi, lembar kerja, atau buat revisi versi baru untuk modul ini</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/modul-praktik" class="text-primary font-weight-500">Modul Praktik</a></li>
          <li class="breadcrumb-item active">Edit</li>
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

    <form action="/admin/kejuruan/modul-praktik/{{ $modul->id_modul_praktik }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row">
        <!-- Kolom Kiri: Informasi Inti Modul -->
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
              <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1rem;">
                <i class="fas fa-file-alt text-primary mr-2"></i> Materi & Panduan Praktikum
              </h5>
              <span class="badge badge-light border text-primary font-weight-bold px-2 py-1">
                {{ $modul->kode_modul }} - Versi Saat Ini: v{{ $modul->versi }}
              </span>
            </div>
            <div class="card-body p-4">

              <div class="form-group mb-3">
                <div class="d-flex align-items-center justify-content-between mb-1">
                  <label for="kompetensi_ids" class="font-weight-bold text-dark mb-0" style="font-size: 0.85rem;">
                    Target Capaian Pembelajaran / Tujuan Pembelajaran (CP / TP) <span class="text-danger">*</span>
                  </label>
                  <small class="text-muted"><i class="fas fa-layer-group text-primary mr-1"></i> Bisa pilih lebih dari 1 CP/TP</small>
                </div>
                @php
                  $existingIds = $modul->kompetensis->pluck('id_kompetensi')->toArray();
                  if (empty($existingIds) && $modul->id_kompetensi) {
                    $existingIds = [$modul->id_kompetensi];
                  }
                  $selectedKompetensiIds = (array) old('kompetensi_ids', old('id_kompetensi', $existingIds));
                @endphp
                <select name="kompetensi_ids[]" id="kompetensi_ids" class="form-control select2 @error('kompetensi_ids') is-invalid @enderror @error('id_kompetensi') is-invalid @enderror" multiple="multiple" data-placeholder="-- Pilih satu atau beberapa Target CP / TP yang diampu --" required style="border-radius: 8px;">
                  @foreach($kompetensis as $k)
                    <option value="{{ $k->id_kompetensi }}" {{ in_array($k->id_kompetensi, $selectedKompetensiIds) ? 'selected' : '' }}>
                      [{{ $k->kode_kompetensi }}] {{ $k->nama_kompetensi }} - Kelas {{ $k->tingkat }} (Sem. {{ $k->semester }})
                      @if($k->jurusan) ({{ $k->jurusan->kode_jurusan ?? $k->jurusan->nama_jurusan }}) @endif
                    </option>
                  @endforeach
                </select>
                <small class="text-muted d-block mt-1">
                  <i class="fas fa-info-circle text-info mr-1"></i> Praktikum berbasis proyek (PjBL) atau integrasi modul dapat mengaitkan beberapa Capaian / Tujuan Pembelajaran sekaligus. Target pertama yang dipilih akan menjadi target utama.
                </small>
                @error('kompetensi_ids')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
                @error('id_kompetensi')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="judul" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Judul Modul Praktik / Jobsheet <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul', $modul->judul) }}" required style="border-radius: 8px; height: 42px;">
                @error('judul')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="tujuan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Tujuan Pembelajaran Praktik <span class="text-danger">*</span>
                </label>
                <textarea class="form-control @error('tujuan') is-invalid @enderror" id="tujuan" name="tujuan" rows="3" required style="border-radius: 8px;">{{ old('tujuan', $modul->tujuan) }}</textarea>
                @error('tujuan')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="alat_bahan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Alat dan Bahan Praktik <span class="text-danger">*</span>
                </label>
                <textarea class="form-control @error('alat_bahan') is-invalid @enderror" id="alat_bahan" name="alat_bahan" rows="3" required style="border-radius: 8px;">{{ old('alat_bahan', $modul->alat_bahan) }}</textarea>
                @error('alat_bahan')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>

              <div class="form-group mb-3">
                <label for="langkah_kerja" class="font-weight-bold text-dark d-flex align-items-center justify-content-between" style="font-size: 0.85rem;">
                  <span>Langkah-Langkah Kerja / Prosedur Praktik <span class="text-danger">*</span></span>
                  <small class="text-muted font-weight-normal"><i class="fas fa-magic text-primary mr-1"></i> Editor Dokumen Jobsheet</small>
                </label>
                <textarea class="form-control summernote-kejuruan @error('langkah_kerja') is-invalid @enderror" id="langkah_kerja" name="langkah_kerja" rows="6">{{ old('langkah_kerja', $modul->langkah_kerja) }}</textarea>
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
              <div id="rubrikContainer">
                @forelse($modul->rubriks as $idx => $r)
                  <div class="rubrik-row p-3 mb-2 rounded border bg-light" id="rubrik-{{ $idx }}">
                    <div class="row">
                      <div class="col-md-7 form-group mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Nama Indikator / Kriteria Keterampilan</label>
                        <input type="text" name="rubrik_indikator[]" class="form-control" value="{{ $r->nama_indikator }}" required style="border-radius: 6px;">
                      </div>
                      <div class="col-md-3 form-group mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Bobot (%)</label>
                        <input type="number" name="rubrik_bobot[]" class="form-control" value="{{ $r->bobot }}" min="1" max="100" required style="border-radius: 6px;">
                      </div>
                      <div class="col-md-2 form-group mb-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger btn-block btn-remove-rubrik" style="border-radius: 6px; height: 38px;" title="Hapus Indikator">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                @empty
                  <div class="rubrik-row p-3 mb-2 rounded border bg-light" id="rubrik-0">
                    <div class="row">
                      <div class="col-md-7 form-group mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Nama Indikator / Kriteria Keterampilan</label>
                        <input type="text" name="rubrik_indikator[]" class="form-control" value="Hasil Keterampilan & Pengujian" required style="border-radius: 6px;">
                      </div>
                      <div class="col-md-3 form-group mb-2">
                        <label class="small font-weight-bold text-dark mb-1">Bobot (%)</label>
                        <input type="number" name="rubrik_bobot[]" class="form-control" value="100" min="1" max="100" required style="border-radius: 6px;">
                      </div>
                      <div class="col-md-2 form-group mb-2 d-flex align-items-end">
                        <button type="button" class="btn btn-outline-danger btn-block btn-remove-rubrik" style="border-radius: 6px; height: 38px;" title="Hapus Indikator">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </div>
                    </div>
                  </div>
                @endforelse
              </div>
            </div>
          </div>
        </div>

        <!-- Kolom Kanan: Pengaturan Versi & Lampiran -->
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 border-bottom">
              <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1rem;">
                <i class="fas fa-history text-primary mr-2"></i> Opsi Revisi Versi Baru
              </h5>
            </div>
            <div class="card-body p-4">

              <div class="custom-control custom-checkbox mb-3">
                <input type="checkbox" class="custom-control-input" id="is_new_version" name="is_new_version" value="1">
                <label class="custom-control-label font-weight-bold text-dark" for="is_new_version" style="font-size: 0.9rem;">
                  Simpan sebagai revisi versi baru
                </label>
                <small class="text-muted d-block mt-1">
                  Centang opsi ini jika Anda melakukan perubahan materi signifikan. Nomor versi akan otomatis dinaikkan dan snapshot versi saat ini tetap diarsipkan.
                </small>
              </div>

              <div class="form-group mb-3">
                <label for="catatan_revisi" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Catatan Perubahan / Rangkuman Revisi
                </label>
                <textarea class="form-control" id="catatan_revisi" name="catatan_revisi" rows="3" placeholder="Contoh: Menambahkan topologi baru dan pembaruan langkah pengujian..." style="border-radius: 8px;"></textarea>
              </div>

              <div class="form-group mb-3">
                <label for="status" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Status Modul <span class="text-danger">*</span>
                </label>
                <select name="status" id="status" class="form-control" required style="border-radius: 8px; height: 42px;">
                  <option value="terbit" {{ old('status', $modul->status) === 'terbit' ? 'selected' : '' }}>Terbit (Bisa dipakai jurnal/penilaian)</option>
                  <option value="draft" {{ old('status', $modul->status) === 'draft' ? 'selected' : '' }}>Draft (Hanya disimpan)</option>
                  <option value="arsip" {{ old('status', $modul->status) === 'arsip' ? 'selected' : '' }}>Arsip (Dinonaktifkan)</option>
                </select>
              </div>

              <div class="form-group mb-3">
                <label for="file_lembar_kerja" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Ganti Berkas Jobsheet (Opsional)
                </label>
                @if($modul->file_lembar_kerja)
                  <div class="mb-2 p-2 bg-light rounded text-truncate small">
                    <i class="fas fa-paperclip text-primary mr-1"></i> Berkas aktif: 
                    <a href="{{ asset('storage/modul_praktik/' . $modul->file_lembar_kerja) }}" target="_blank" class="font-weight-bold">
                      Lihat Dokumen
                    </a>
                  </div>
                @endif
                <input type="file" class="form-control-file" id="file_lembar_kerja" name="file_lembar_kerja" accept=".pdf,image/*">
                <small class="text-muted d-block mt-1">Kosongkan jika tidak ingin mengganti berkas lampiran.</small>
              </div>

            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/kejuruan/modul-praktik/{{ $modul->id_modul_praktik }}" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Batal
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Perbarui Modul
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
    let rubrikCount = container.querySelectorAll('.rubrik-row').length + 1;

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

    if (window.jQuery && $.fn.select2) {
      $('#kompetensi_ids').select2({
        theme: 'bootstrap4',
        width: '100%',
        placeholder: '-- Pilih satu atau beberapa Target CP / TP yang diampu --',
        allowClear: true
      });
    }

    if (window.jQuery && $.fn.summernote) {
      $('#langkah_kerja').summernote({
        placeholder: 'Tuliskan prosedur langkah kerja, panduan keselamatan kerja, dan tugas praktik di sini...',
        tabsize: 2,
        height: 240,
        tooltip: false,
        toolbar: [
          ['style', ['bold', 'italic', 'underline', 'strikethrough']],
          ['insert', ['picture', 'video', 'link']],
          ['para', ['ul', 'ol', 'paragraph']],
          ['misc', ['codeview', 'clear']]
        ]
      });
    }
  });
</script>

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
@endsection
