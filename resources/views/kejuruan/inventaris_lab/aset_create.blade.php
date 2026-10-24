@extends($layout)

@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-boxes"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Tambah Aset / Bahan Lab</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Registrasi barang, alat praktik, atau bahan habis pakai ke laboratorium</p>
        </div>
      </div>
      <div class="col-sm-5 text-sm-right mt-2 mt-sm-0">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/kejuruan/inventaris-lab" class="text-primary font-weight-500">Inventaris Lab</a></li>
          <li class="breadcrumb-item active">Tambah Aset</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content pb-4">
  <div class="container-fluid">
    
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px;">
      <i class="fas fa-exclamation-circle mr-2"></i> {{ session('error') }}
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 10px;">
      <div class="font-weight-bold mb-1"><i class="fas fa-exclamation-triangle mr-2"></i> Terdapat kesalahan pada formulir:</div>
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

    <form action="/admin/kejuruan/inventaris-lab/aset" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="row">
        <!-- Kolom Utama: Identitas & Spesifikasi -->
        <div class="col-lg-8">
          <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; overflow: hidden;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
              <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
                <i class="fas fa-info-circle text-primary mr-2"></i>Informasi Utama Aset
              </h5>
            </div>
            <div class="card-body p-4 pt-3">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Pilih Laboratorium / Bengkel <span class="text-danger">*</span></label>
                  <select name="id_lab" class="form-control select2bs4 @error('id_lab') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="">-- Pilih Laboratorium --</option>
                    @foreach($labs as $l)
                      <option value="{{ $l->id_lab }}" {{ old('id_lab') == $l->id_lab ? 'selected' : '' }}>
                        {{ $l->nama_lab }} ({{ $l->jurusan->nama_jurusan ?? 'Semua Jurusan' }})
                      </option>
                    @endforeach
                  </select>
                  @error('id_lab')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-6 mb-3">
                  <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Kategori Barang <span class="text-danger">*</span></label>
                  <select name="kategori" id="kategoriSelect" class="form-control @error('kategori') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="alat" {{ old('kategori', 'alat') === 'alat' ? 'selected' : '' }}>Alat / Peralatan (Dapat Dipinjam)</option>
                    <option value="bahan_habis_pakai" {{ old('kategori') === 'bahan_habis_pakai' ? 'selected' : '' }}>Bahan Habis Pakai (Konsumsi Praktik)</option>
                  </select>
                  @error('kategori')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <div class="mb-3">
                <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Nama Barang / Aset <span class="text-danger">*</span></label>
                <input type="text" name="nama_aset" class="form-control @error('nama_aset') is-invalid @enderror" value="{{ old('nama_aset') }}" placeholder="Contoh: Osiloskop Digital 100MHz / Timah Solder 0.8mm" required style="border-radius: 8px; height: 42px;">
                @error('nama_aset')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <small class="text-muted mt-1 d-block">Kode aset unik dibuat otomatis oleh sistem berdasarkan singkatan lab dan nomor urut.</small>
              </div>

              <div class="row">
                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Jumlah Awal <span class="text-danger">*</span></label>
                  <input type="number" name="jumlah" min="1" class="form-control @error('jumlah') is-invalid @enderror" value="{{ old('jumlah', 1) }}" required style="border-radius: 8px; height: 42px;">
                  @error('jumlah')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Satuan <span class="text-danger">*</span></label>
                  <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror" value="{{ old('satuan', 'Unit') }}" placeholder="Unit / Pcs / Roll / Box" required style="border-radius: 8px; height: 42px;">
                  @error('satuan')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>

                <div class="col-md-4 mb-3">
                  <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Batas Stok Minimum</label>
                  <input type="number" name="stok_minimum" min="0" class="form-control @error('stok_minimum') is-invalid @enderror" value="{{ old('stok_minimum', 5) }}" style="border-radius: 8px; height: 42px;">
                  <small class="text-muted mt-1 d-block">Peringatan aktif saat stok &le; angka ini.</small>
                </div>
              </div>

              <div class="mb-3">
                <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Spesifikasi Teknis / Merek / Tipe</label>
                <textarea name="spesifikasi" rows="3" class="form-control" placeholder="Contoh: Brand Rigol DS1054Z, 4 Channel, 50MHz, tegangan 220V AC" style="border-radius: 8px;">{{ old('spesifikasi') }}</textarea>
              </div>

              <div class="mb-0">
                <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Catatan Tambahan / Lokasi Rak Simpan</label>
                <textarea name="keterangan" rows="2" class="form-control" placeholder="Contoh: Rak B3, lemari kaca terkunci" style="border-radius: 8px;">{{ old('keterangan') }}</textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- Kolom Samping: Kondisi, Nilai & Foto -->
        <div class="col-lg-4">
          <div class="card border-0 shadow-sm mb-4" style="border-radius: 14px; overflow: hidden;">
            <div class="card-header bg-white py-3 border-bottom d-flex align-items-center">
              <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
                <i class="fas fa-cog text-secondary mr-2"></i>Kondisi & Perolehan
              </h5>
            </div>
            <div class="card-body p-4 pt-3">
              <div class="mb-3">
                <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Kondisi Fisik <span class="text-danger">*</span></label>
                <select name="kondisi" class="form-control" required style="border-radius: 8px; height: 42px;">
                  <option value="baik" {{ old('kondisi') === 'baik' ? 'selected' : '' }}>Baik (Siap Pakai)</option>
                  <option value="rusak_ringan" {{ old('kondisi') === 'rusak_ringan' ? 'selected' : '' }}>Rusak Ringan (Bisa Digunakan)</option>
                  <option value="rusak_berat" {{ old('kondisi') === 'rusak_berat' ? 'selected' : '' }}>Rusak Berat (Tidak Berfungsi)</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Tahun Perolehan <span class="text-danger">*</span></label>
                <input type="number" name="tahun_perolehan" min="2000" max="{{ date('Y') + 1 }}" class="form-control @error('tahun_perolehan') is-invalid @enderror" value="{{ old('tahun_perolehan', date('Y')) }}" required style="border-radius: 8px; height: 42px;">
                @error('tahun_perolehan')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="mb-3">
                <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Sumber Dana / Pengadaan</label>
                <input type="text" name="sumber_dana" class="form-control" value="{{ old('sumber_dana', 'BOS / DAK') }}" placeholder="Contoh: BOS 2024 / Hibah Industri" style="border-radius: 8px; height: 42px;">
              </div>

              <div class="mb-3">
                <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Harga Perolehan Satuan (Rp)</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text bg-light font-weight-bold" style="border-radius: 8px 0 0 8px;">Rp</span>
                  </div>
                  <input type="number" step="0.01" name="harga_satuan" class="form-control" value="{{ old('harga_satuan', 0) }}" placeholder="0" style="border-radius: 0 8px 8px 0; height: 42px;">
                </div>
              </div>

              <div class="mb-0">
                <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Foto Dokumentasi Aset</label>
                <input type="file" name="foto" class="form-control-file @error('foto') is-invalid @enderror" accept="image/*">
                <small class="text-muted d-block mt-1">Format JPG, PNG, atau WEBP. Maksimal 3MB.</small>
                @error('foto')
                  <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
              </div>
            </div>
          </div>

          <div class="card border-0 shadow-sm" style="border-radius: 14px; background: #ffffff;">
            <div class="card-body p-3">
              <button type="submit" class="btn btn-primary btn-block py-2 font-weight-bold shadow-sm" style="border-radius: 8px;">
                <i class="fas fa-save mr-2"></i>Simpan Data Aset
              </button>
              <a href="/admin/kejuruan/inventaris-lab" class="btn btn-light btn-block py-2 font-weight-bold text-muted border mt-2" style="border-radius: 8px;">
                Kembali ke Inventaris
              </a>
            </div>
          </div>
        </div>
      </div>
    </form>

  </div>
</div>
@endsection
