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
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Detail Aset Laboratorium</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">{{ $aset->nama_aset }} ({{ $aset->kode_aset }})</p>
        </div>
      </div>
      <div class="col-sm-5 text-sm-right mt-2 mt-sm-0">
        <a href="/admin/kejuruan/inventaris-lab/cetak-qr?aset_id={{ $aset->id_aset }}" class="btn btn-outline-dark btn-sm px-3 mr-2 font-weight-bold" target="_blank" style="border-radius: 6px;">
          <i class="fas fa-qrcode mr-1"></i> Cetak QR
        </a>
        <a href="/admin/kejuruan/inventaris-lab/aset/{{ $aset->id_aset }}/edit" class="btn btn-primary btn-sm px-3 mr-2 shadow-sm font-weight-bold" style="border-radius: 6px;">
          <i class="fas fa-edit mr-1"></i> Ubah Data
        </a>
        <a href="/admin/kejuruan/inventaris-lab" class="btn btn-outline-secondary btn-sm px-3" style="border-radius: 6px;">
          <i class="fas fa-arrow-left mr-1"></i> Kembali
        </a>
      </div>
    </div>
  </div>
</div>

<div class="content pb-4">
    <div class="container-fluid">

      @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert" style="border-radius: 8px;">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      @endif

      <!-- Info Utama & QR Code -->
      <div class="row">
        <!-- Kolom Info Aset -->
        <div class="col-lg-8">
          <div class="card card-outline card-primary shadow-sm mb-4" style="border-radius: 10px;">
            <div class="card-body">
              <div class="d-flex align-items-start justify-content-between flex-wrap">
                <div>
                  <div class="d-flex align-items-center mb-2">
                    @if($aset->kategori === 'alat')
                      <span class="badge badge-info px-2 py-1 mr-2"><i class="fas fa-tools mr-1"></i> Alat / Peralatan</span>
                    @else
                      <span class="badge badge-warning px-2 py-1 mr-2"><i class="fas fa-vial mr-1"></i> Bahan Habis Pakai</span>
                    @endif

                    @if($aset->kondisi === 'baik')
                      <span class="badge badge-success px-2 py-1"><i class="fas fa-check mr-1"></i> Kondisi Baik</span>
                    @elseif($aset->kondisi === 'rusak_ringan')
                      <span class="badge badge-warning px-2 py-1"><i class="fas fa-exclamation-triangle mr-1"></i> Rusak Ringan</span>
                    @else
                      <span class="badge badge-danger px-2 py-1"><i class="fas fa-times-circle mr-1"></i> Rusak Berat</span>
                    @endif
                  </div>

                  <h2 class="font-weight-bold text-dark mb-1" style="font-size: 1.6rem;">
                    {{ $aset->nama_aset }}
                  </h2>
                  <div class="text-muted font-weight-600 mb-3">
                    Kode Aset: <span class="badge badge-light border text-primary" style="font-size: 0.95rem;">{{ $aset->kode_aset }}</span>
                  </div>
                </div>

                @if($aset->foto)
                  <div class="ml-auto mb-3">
                    <img src="{{ asset('storage/' . $aset->foto) }}" alt="Foto {{ $aset->nama_aset }}" class="img-thumbnail rounded shadow-sm" style="max-height: 120px; max-width: 160px; object-fit: cover;">
                  </div>
                @endif
              </div>

              <!-- Bar Status Ketersediaan Stok -->
              <div class="bg-light p-3 rounded mb-4 border">
                <div class="row text-center">
                  <div class="col-4 border-right">
                    <div class="text-muted small text-uppercase font-weight-bold">Total Stok</div>
                    <div class="font-weight-bold text-dark" style="font-size: 1.4rem;">
                      {{ $aset->jumlah }} <span class="small font-weight-normal text-muted">{{ $aset->satuan }}</span>
                    </div>
                  </div>
                  <div class="col-4 border-right">
                    <div class="text-muted small text-uppercase font-weight-bold">Stok Tersedia</div>
                    <div class="font-weight-bold {{ $aset->stok_tersedia <= $aset->stok_minimum ? 'text-danger' : 'text-success' }}" style="font-size: 1.4rem;">
                      {{ $aset->stok_tersedia }} <span class="small font-weight-normal text-muted">{{ $aset->satuan }}</span>
                    </div>
                  </div>
                  <div class="col-4">
                    <div class="text-muted small text-uppercase font-weight-bold">Dipinjam / Digunakan</div>
                    <div class="font-weight-bold text-warning" style="font-size: 1.4rem;">
                      {{ max(0, $aset->jumlah - $aset->stok_tersedia) }} <span class="small font-weight-normal text-muted">{{ $aset->satuan }}</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Rincian Data Teknis -->
              <div class="table-responsive">
                <table class="table table-sm table-borderless mb-0">
                  <tbody>
                    <tr>
                      <td style="width: 200px;" class="text-muted font-weight-600">Lokasi Laboratorium</td>
                      <td class="font-weight-bold text-dark">: {{ $aset->lab->nama_lab ?? '-' }} ({{ $aset->lab->jurusan->nama_jurusan ?? 'Semua Jurusan' }})</td>
                    </tr>
                    <tr>
                      <td class="text-muted font-weight-600">Tahun Pengadaan</td>
                      <td class="text-dark">: {{ $aset->tahun_perolehan }}</td>
                    </tr>
                    <tr>
                      <td class="text-muted font-weight-600">Sumber Anggaran</td>
                      <td class="text-dark">: {{ $aset->sumber_dana ?: 'Dana Sekolah / BOS' }}</td>
                    </tr>
                    <tr>
                      <td class="text-muted font-weight-600">Harga Satuan</td>
                      <td class="text-dark">: Rp {{ number_format($aset->harga_satuan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                      <td class="text-muted font-weight-600">Total Nilai Barang</td>
                      <td class="text-dark font-weight-bold">: Rp {{ number_format($aset->jumlah * $aset->harga_satuan, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                      <td class="text-muted font-weight-600">Batas Stok Minimum</td>
                      <td class="text-dark">: {{ $aset->stok_minimum }} {{ $aset->satuan }}</td>
                    </tr>
                    <tr>
                      <td class="text-muted font-weight-600">Spesifikasi Lengkap</td>
                      <td class="text-dark">: {{ $aset->spesifikasi ?: 'Tidak ada catatan spesifikasi khusus.' }}</td>
                    </tr>
                    <tr>
                      <td class="text-muted font-weight-600">Catatan / Posisi Rak</td>
                      <td class="text-dark">: {{ $aset->keterangan ?: 'Tidak ada keterangan rak penyimpanan.' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

            </div>
          </div>
        </div>

        <!-- Kolom Kartu QR Code & Label Stiker Preview -->
        <div class="col-lg-4">
          <div class="card shadow-sm border-0 text-center mb-4" style="border-radius: 10px; background: #ffffff;">
            <div class="card-header bg-white py-3 border-bottom">
              <h5 class="card-title font-weight-bold text-dark mb-0 d-block text-center w-100">
                <i class="fas fa-qrcode text-primary mr-2"></i>Label QR Inventaris
              </h5>
            </div>
            <div class="card-body p-4">
              <div class="p-3 d-inline-block bg-white border rounded shadow-sm mb-3">
                {!! SimpleSoftwareIO\QrCode\Facades\QrCode::size(140)->generate(url('/admin/kejuruan/inventaris-lab/aset/' . $aset->id_aset)) !!}
              </div>
              <div class="font-weight-bold text-dark" style="font-size: 1.1rem;">
                {{ $aset->kode_aset }}
              </div>
              <div class="text-muted small mb-3">
                {{ $setting->nama_sekolah ?? 'SMK Negeri' }}
              </div>

              <a href="/admin/kejuruan/inventaris-lab/cetak-qr?aset_id={{ $aset->id_aset }}" target="_blank" class="btn btn-outline-dark btn-block font-weight-bold shadow-sm" style="border-radius: 8px;">
                <i class="fas fa-print mr-2"></i>Buka Cetak Stiker
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- Riwayat Terkait Aset (Peminjaman / Pemakaian / Perawatan) -->
      <div class="card card-outline card-secondary shadow-sm" style="border-radius: 10px;">
        <div class="card-header bg-white p-2 border-bottom">
          <ul class="nav nav-pills" id="historyTab" role="tablist">
            @if($aset->kategori === 'alat')
            <li class="nav-item">
              <a class="nav-link active font-weight-bold py-2 px-3" id="peminjaman-tab" data-toggle="pill" href="#peminjaman" role="tab">
                <i class="fas fa-people-carry mr-1"></i> Riwayat Peminjaman ({{ $aset->peminjamans->count() }})
              </a>
            </li>
            @endif
            @if($aset->kategori === 'bahan_habis_pakai')
            <li class="nav-item">
              <a class="nav-link active font-weight-bold py-2 px-3" id="pemakaian-tab" data-toggle="pill" href="#pemakaian" role="tab">
                <i class="fas fa-vial mr-1"></i> Riwayat Pemakaian Bahan ({{ $aset->pemakaians->count() }})
              </a>
            </li>
            @endif
            <li class="nav-item">
              <a class="nav-link {{ $aset->kategori === 'alat' ? '' : '' }} font-weight-bold py-2 px-3" id="perawatan-tab" data-toggle="pill" href="#perawatan" role="tab">
                <i class="fas fa-wrench mr-1"></i> Catatan Kerusakan & Perawatan ({{ $aset->perawatans->count() }})
              </a>
            </li>
          </ul>
        </div>
        <div class="card-body p-0">
          <div class="tab-content" id="historyTabContent">
            
            @if($aset->kategori === 'alat')
            <!-- Tab Riwayat Peminjaman -->
            <div class="tab-pane fade show active" id="peminjaman" role="tabpanel">
              <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                  <thead class="thead-light">
                    <tr>
                      <th class="py-3 px-3">Kode Pinjam</th>
                      <th class="py-3">Peminjam</th>
                      <th class="py-3 text-center">Jumlah</th>
                      <th class="py-3">Tgl Pinjam</th>
                      <th class="py-3">Rencana Kembali</th>
                      <th class="py-3 text-center">Status</th>
                      <th class="py-3">Kondisi Sesudah</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($aset->peminjamans as $pinjam)
                      <tr>
                        <td class="font-weight-bold text-dark px-3">{{ $pinjam->kode_peminjaman }}</td>
                        <td>
                          @if($pinjam->tipe_peminjam === 'guru')
                            <div class="font-weight-bold text-dark">{{ $pinjam->peminjamGuru->nama_guru ?? 'Guru' }}</div>
                            <span class="badge badge-light border">Guru</span>
                          @else
                            <div class="font-weight-bold text-dark">{{ $pinjam->peminjamSiswa->nama_siswa ?? 'Siswa' }}</div>
                            <span class="badge badge-light border">Siswa ({{ $pinjam->peminjamSiswa->kelas->nama_kelas ?? '-' }})</span>
                          @endif
                        </td>
                        <td class="text-center font-weight-bold">{{ $pinjam->jumlah }} {{ $aset->satuan }}</td>
                        <td>{{ date('d/m/Y', strtotime($pinjam->tanggal_pinjam)) }}</td>
                        <td>{{ date('d/m/Y', strtotime($pinjam->rencana_kembali)) }}</td>
                        <td class="text-center">
                          @if($pinjam->status === 'dipinjam')
                            <span class="badge badge-warning px-2 py-1 font-weight-bold">Dipinjam</span>
                          @elseif($pinjam->status === 'kembali')
                            <span class="badge badge-success px-2 py-1 font-weight-bold">Kembali</span>
                          @else
                            <span class="badge badge-danger px-2 py-1 font-weight-bold">Terlambat</span>
                          @endif
                        </td>
                        <td>
                          @if($pinjam->status === 'kembali')
                            <span class="text-muted small">Kembali: {{ date('d/m/Y', strtotime($pinjam->tanggal_kembali)) }}</span><br>
                            <span class="badge badge-info">{{ $pinjam->kondisi_sesudah }}</span>
                          @else
                            <span class="text-muted">-</span>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                          <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary"></i>
                          Belum ada riwayat peminjaman untuk alat ini.
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
            @endif

            @if($aset->kategori === 'bahan_habis_pakai')
            <!-- Tab Riwayat Pemakaian Bahan -->
            <div class="tab-pane fade show active" id="pemakaian" role="tabpanel">
              <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                  <thead class="thead-light">
                    <tr>
                      <th class="py-3 px-3">Tanggal</th>
                      <th class="py-3">Guru / Pengguna</th>
                      <th class="py-3 text-center">Jumlah Dipakai</th>
                      <th class="py-3 text-center">Sisa Stok Saat Itu</th>
                      <th class="py-3">Keperluan Praktik</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($aset->pemakaians as $pakai)
                      <tr>
                        <td class="px-3 font-weight-bold text-dark">{{ date('d/m/Y', strtotime($pakai->tanggal_pakai)) }}</td>
                        <td>
                          <div class="font-weight-bold text-dark">{{ $pakai->penggunaGuru->nama_guru ?? 'Guru Praktik' }}</div>
                          <span class="text-muted small">{{ $pakai->catatan }}</span>
                        </td>
                        <td class="text-center font-weight-bold text-danger">
                          -{{ $pakai->jumlah_pakai }} {{ $aset->satuan }}
                        </td>
                        <td class="text-center font-weight-bold text-dark">
                          {{ $pakai->sisa_stok_saat_itu }} {{ $aset->satuan }}
                        </td>
                        <td>{{ $pakai->keperluan ?: 'Praktik Kejuruan' }}</td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                          <i class="fas fa-inbox fa-2x mb-2 d-block text-secondary"></i>
                          Belum ada riwayat pemakaian untuk bahan ini.
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
            @endif

            <!-- Tab Catatan Perawatan -->
            <div class="tab-pane fade {{ $aset->kategori !== 'alat' && $aset->kategori !== 'bahan_habis_pakai' ? 'show active' : '' }}" id="perawatan" role="tabpanel">
              <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                  <thead class="thead-light">
                    <tr>
                      <th class="py-3 px-3">No / Jenis</th>
                      <th class="py-3">Tgl Jadwal</th>
                      <th class="py-3">Deskripsi Kendala</th>
                      <th class="py-3">Tindakan / Teknisi</th>
                      <th class="py-3 text-center">Biaya (Rp)</th>
                      <th class="py-3 text-center">Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse($aset->perawatans as $rawat)
                      <tr>
                        <td class="font-weight-bold text-dark px-3">
                          #{{ $rawat->id_perawatan }}<br>
                          <span class="badge badge-light border">{{ ucfirst(str_replace('_', ' ', $rawat->jenis)) }}</span>
                        </td>
                        <td>{{ date('d/m/Y', strtotime($rawat->tanggal_jadwal)) }}</td>
                        <td>
                          <div class="font-weight-bold text-dark">{{ $rawat->deskripsi_kendala }}</div>
                          <span class="text-muted small">Pelapor: {{ $rawat->pelapor->nama_guru ?? 'Petugas Lab' }}</span>
                        </td>
                        <td>
                          <div>{{ $rawat->tindakan_perbaikan ?: 'Menunggu tindakan' }}</div>
                          <small class="text-muted">Teknisi: {{ $rawat->teknisi ?: '-' }}</small>
                        </td>
                        <td class="text-center font-weight-bold">
                          {{ $rawat->biaya > 0 ? 'Rp ' . number_format($rawat->biaya, 0, ',', '.') : '-' }}
                        </td>
                        <td class="text-center">
                          @if($rawat->status === 'terjadwal')
                            <span class="badge badge-secondary px-2 py-1">Terjadwal</span>
                          @elseif($rawat->status === 'dalam_pengerjaan')
                            <span class="badge badge-warning px-2 py-1">Dalam Pengerjaan</span>
                          @elseif($rawat->status === 'selesai')
                            <span class="badge badge-success px-2 py-1">Selesai</span>
                          @else
                            <span class="badge badge-danger px-2 py-1">Rusak Berat</span>
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                          <i class="fas fa-check-circle fa-2x mb-2 d-block text-success"></i>
                          Aset ini belum pernah dilaporkan mengalami kerusakan.
                        </td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>

          </div>
        </div>
  </div>
</div>
@endsection
