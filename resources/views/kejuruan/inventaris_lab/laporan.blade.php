@extends($layout)

@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-file-invoice"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Laporan Inventaris Laboratorium</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Rekapitulasi sarana prasarana, peralatan, dan bahan praktik per laboratorium</p>
        </div>
      </div>
      <div class="col-sm-5 text-sm-right mt-2 mt-sm-0">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/kejuruan/inventaris-lab" class="text-primary font-weight-500">Inventaris Lab</a></li>
          <li class="breadcrumb-item active">Laporan</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content pb-4">
    <div class="container-fluid">

      <!-- Filter Laboratorium -->
      <div class="card shadow-sm border-0 mb-4" style="border-radius: 10px;">
        <div class="card-body py-3">
          <form method="GET" action="/admin/kejuruan/inventaris-lab/laporan" class="row align-items-center">
            <div class="col-md-5 mb-2 mb-md-0">
              <label class="small text-muted font-weight-bold text-uppercase mb-1 d-block">Pilih Laboratorium / Bengkel</label>
              <select name="lab_id" class="form-control" onchange="this.form.submit()" style="border-radius: 6px;">
                @foreach($labs as $l)
                  <option value="{{ $l->id_lab }}" {{ ($selectedLab && $selectedLab->id_lab == $l->id_lab) ? 'selected' : '' }}>
                    {{ $l->nama_lab }} ({{ $l->jurusan->nama_jurusan ?? 'Semua Jurusan' }})
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-7 text-md-right mt-2 mt-md-0">
              @if($selectedLab)
                <a href="/admin/kejuruan/inventaris-lab/cetak-qr?lab_id={{ $selectedLab->id_lab }}" target="_blank" class="btn btn-outline-dark btn-sm px-3 mr-2 font-weight-bold" style="border-radius: 6px;">
                  <i class="fas fa-qrcode mr-1"></i> Cetak QR Lab Ini
                </a>
                <a href="/admin/kejuruan/inventaris-lab/laporan/excel?lab_id={{ $selectedLab->id_lab }}" class="btn btn-success btn-sm px-3 mr-2 font-weight-bold shadow-sm" style="border-radius: 6px;">
                  <i class="fas fa-file-excel mr-1"></i> Unduh Excel
                </a>
                <a href="/admin/kejuruan/inventaris-lab/laporan/pdf?lab_id={{ $selectedLab->id_lab }}" target="_blank" class="btn btn-danger btn-sm px-3 font-weight-bold shadow-sm" style="border-radius: 6px;">
                  <i class="fas fa-file-pdf mr-1"></i> Cetak Dokumen PDF
                </a>
              @endif
            </div>
          </form>
        </div>
      </div>

      @if($selectedLab)
        <!-- Ringkasan Statistik Lab -->
        <div class="row">
          <div class="col-lg-3 col-sm-6 mb-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 10px; background: #ffffff;">
              <div class="card-body d-flex align-items-center">
                <div class="rounded-circle p-3 mr-3" style="background: #e0f2fe; color: #0284c7;">
                  <i class="fas fa-boxes fa-2x"></i>
                </div>
                <div>
                  <div class="text-muted small text-uppercase font-weight-bold">Total Jenis Aset</div>
                  <div class="font-weight-bold text-dark" style="font-size: 1.4rem;">{{ $asets->count() }} Item</div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-sm-6 mb-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 10px; background: #ffffff;">
              <div class="card-body d-flex align-items-center">
                <div class="rounded-circle p-3 mr-3" style="background: #e0e7ff; color: #4338ca;">
                  <i class="fas fa-tools fa-2x"></i>
                </div>
                <div>
                  <div class="text-muted small text-uppercase font-weight-bold">Total Alat & Mesin</div>
                  <div class="font-weight-bold text-dark" style="font-size: 1.4rem;">{{ $asets->where('kategori', 'alat')->sum('jumlah') }} Unit</div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-sm-6 mb-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 10px; background: #ffffff;">
              <div class="card-body d-flex align-items-center">
                <div class="rounded-circle p-3 mr-3" style="background: #fef3c7; color: #d97706;">
                  <i class="fas fa-vial fa-2x"></i>
                </div>
                <div>
                  <div class="text-muted small text-uppercase font-weight-bold">Bahan Habis Pakai</div>
                  <div class="font-weight-bold text-dark" style="font-size: 1.4rem;">{{ $asets->where('kategori', 'bahan_habis_pakai')->count() }} Jenis</div>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-3 col-sm-6 mb-3">
            <div class="card shadow-sm border-0 h-100" style="border-radius: 10px; background: #ffffff;">
              <div class="card-body d-flex align-items-center">
                <div class="rounded-circle p-3 mr-3" style="background: #dcfce7; color: #16a34a;">
                  <i class="fas fa-money-bill-wave fa-2x"></i>
                </div>
                <div>
                  <div class="text-muted small text-uppercase font-weight-bold">Estimasi Total Nilai</div>
                  <div class="font-weight-bold text-success" style="font-size: 1.25rem;">Rp {{ number_format($totalNilaiAset, 0, ',', '.') }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabel Rekapitulasi Aset -->
        <div class="card card-outline card-primary shadow-sm" style="border-radius: 10px;">
          <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
            <h3 class="card-title font-weight-bold text-dark mb-0">
              <i class="fas fa-list text-primary mr-2"></i>Daftar Inventaris: {{ $selectedLab->nama_lab }}
            </h3>
            <span class="badge badge-light border px-2 py-1 text-muted">
              Lokasi: {{ $selectedLab->lokasi ?: 'Gedung Kejuruan' }}
            </span>
          </div>
          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover table-striped mb-0">
                <thead class="thead-light">
                  <tr>
                    <th style="width: 40px;" class="text-center py-3">No</th>
                    <th class="py-3">Kode Aset</th>
                    <th class="py-3">Nama Barang</th>
                    <th class="py-3">Kategori</th>
                    <th class="py-3 text-center">Jml Total</th>
                    <th class="py-3 text-center">Tersedia</th>
                    <th class="py-3 text-center">Kondisi</th>
                    <th class="py-3 text-right">Harga Satuan (Rp)</th>
                    <th class="py-3 text-right px-3">Subtotal (Rp)</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($asets as $index => $a)
                    <tr>
                      <td class="text-center py-3 text-muted">{{ $index + 1 }}</td>
                      <td class="py-3 font-weight-bold">
                        <a href="/admin/kejuruan/inventaris-lab/aset/{{ $a->id_aset }}" class="text-primary">
                          {{ $a->kode_aset }}
                        </a>
                      </td>
                      <td class="py-3">
                        <div class="font-weight-600 text-dark">{{ $a->nama_aset }}</div>
                        <small class="text-muted">{{ $a->spesifikasi ?: $a->sumber_dana }}</small>
                      </td>
                      <td class="py-3">
                        @if($a->kategori === 'alat')
                          <span class="badge badge-info">Alat</span>
                        @else
                          <span class="badge badge-warning">Bahan</span>
                        @endif
                      </td>
                      <td class="py-3 text-center font-weight-bold">{{ $a->jumlah }} {{ $a->satuan }}</td>
                      <td class="py-3 text-center font-weight-bold {{ $a->stok_tersedia <= $a->stok_minimum ? 'text-danger' : 'text-success' }}">
                        {{ $a->stok_tersedia }} {{ $a->satuan }}
                      </td>
                      <td class="py-3 text-center">
                        @if($a->kondisi === 'baik')
                          <span class="badge badge-success">Baik</span>
                        @elseif($a->kondisi === 'rusak_ringan')
                          <span class="badge badge-warning">Rusak Ringan</span>
                        @else
                          <span class="badge badge-danger">Rusak Berat</span>
                        @endif
                      </td>
                      <td class="py-3 text-right">{{ number_format($a->harga_satuan, 0, ',', '.') }}</td>
                      <td class="py-3 text-right font-weight-bold text-dark px-3">
                        {{ number_format($a->jumlah * $a->harga_satuan, 0, ',', '.') }}
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="9" class="text-center py-5 text-muted">
                        <i class="fas fa-boxes fa-3x mb-3 d-block text-secondary"></i>
                        Belum ada aset laboratorium yang terdaftar di lab ini.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
                @if($asets->isNotEmpty())
                  <tfoot class="thead-light font-weight-bold">
                    <tr>
                      <td colspan="8" class="text-right py-3">TOTAL KESELURUHAN ESTIMASI NILAI ASET:</td>
                      <td class="text-right py-3 text-success px-3" style="font-size: 1.1rem;">
                        Rp {{ number_format($totalNilaiAset, 0, ',', '.') }}
                      </td>
                    </tr>
                  </tfoot>
                @endif
              </table>
            </div>
          </div>
        </div>
      @else
        <div class="card shadow-sm border-0 text-center py-5" style="border-radius: 10px;">
          <div class="card-body">
            <i class="fas fa-flask fa-3x text-muted mb-3"></i>
            <h5 class="text-dark font-weight-bold">Tidak ada laboratorium yang tersedia</h5>
            <p class="text-muted">Silakan tambahkan data laboratorium terlebih dahulu di menu Master Lab.</p>
          </div>
        </div>
      @endif

  </div>
</div>
@endsection
