@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-user-check text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Matriks Kelayakan & Pemberian TKK</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Deteksi otomatis progres pemenuhan syarat kompetensi dan validasi pengesahan piagam TKK siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/tkk" class="text-primary font-weight-500">Master TKK</a></li>
          <li class="breadcrumb-item active">Pemberian TKK</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
        <i class="fas fa-check-circle mr-2"></i> {!! session('success') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 12px;">
        <i class="fas fa-exclamation-circle mr-2"></i> {!! session('error') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <!-- Filter Card: Pilih TKK & Kelas -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <form method="GET" action="/admin/kejuruan/tkk/pemberian" class="row align-items-end">
          <div class="col-md-5 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Pilih Jenis TKK</label>
            <select name="id_jenis_tkk" class="form-control form-control-sm select2bs4" onchange="this.form.submit()">
              @foreach($jenisTkks as $t)
                <option value="{{ $t->id_jenis_tkk }}" {{ ($activeTkk && $activeTkk->id_jenis_tkk == $t->id_jenis_tkk) ? 'selected' : '' }}>
                  [{{ strtoupper($t->tingkat) }}] {{ $t->nomor_klaster ? 'Klaster #'.$t->nomor_klaster.' - ' : '' }}{{ $t->nama_tkk }} ({{ $t->jurusan->nama_jurusan ?? '-' }})
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-4 col-sm-6 mb-2 mb-md-0">
            <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Pilih Kelas Siswa</label>
            <div class="d-flex align-items-center" style="gap: 8px;">
              <select name="id_kelas" class="form-control form-control-sm select2bs4" style="flex: 1;" onchange="this.form.submit()">
                <option value="">-- Semua Kelas (Penerima & Siswa Layak) --</option>
                @foreach($kelas as $k)
                  <option value="{{ $k->id_kelas }}" {{ $selectedKelasId == $k->id_kelas ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                  </option>
                @endforeach
              </select>
              <button type="submit" class="btn btn-primary btn-sm px-3 d-flex align-items-center justify-content-center shadow-sm" style="height: 31px; flex-shrink: 0;" title="Tampilkan Siswa">
                <i class="fas fa-search"></i>
              </button>
            </div>
          </div>
        </form>

        <div class="d-flex align-items-center justify-content-between mt-3 pt-2 border-top flex-wrap" style="gap: 8px;">
          <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
            <a href="/admin/kejuruan/tkk" class="btn btn-outline-secondary btn-sm shadow-sm">
              <i class="fas fa-arrow-left mr-1"></i> Master Jenis TKK
            </a>
            <a href="/admin/kejuruan/tkk/rekap" class="btn btn-outline-info btn-sm shadow-sm">
              <i class="fas fa-file-invoice mr-1"></i> Rekapitulasi Hubin
            </a>
            @if($user->hasAnyRole(['admin', 'kaprog']))
            <a href="/admin/kejuruan/pengaturan-nomor" class="btn btn-outline-primary btn-sm shadow-sm font-weight-bold">
              <i class="fas fa-barcode mr-1"></i> Format Nomor
            </a>
            @endif
          </div>

          @if($activeTkk)
            <div class="d-flex align-items-center flex-wrap" style="gap: 6px;">
              @if($activeTkk->nomor_klaster)
                <span class="badge badge-primary px-2 py-1 font-weight-bold">
                  <i class="fas fa-layer-group mr-1"></i> Klaster #{{ $activeTkk->nomor_klaster }}
                </span>
              @endif
              @if($activeTkk->kode_tkk)
                <span class="badge badge-light border text-monospace px-2 py-1">
                  {{ $activeTkk->kode_tkk }}
                </span>
              @endif
              <div class="small text-muted ml-1">
                Syarat: <strong>{{ $activeTkk->syarats->count() }} Unit Kompetensi</strong>
              </div>
            </div>
          @endif
        </div>
      </div>
    </div>

    @php
      $siswaMemenuhiSyarat = $siswaList->where('tkk_status', 'memenuhi_syarat');
      $jumlahMemenuhiSyarat = $siswaMemenuhiSyarat->count();
      $defaultPrefixPiagam = $activeTkk ? ('TKK/' . ($activeTkk->jurusan->kode_jurusan ?? 'KEJ') . '/' . ($activeTkk->nomor_klaster ? 'KL'.$activeTkk->nomor_klaster : 'GEN') . '/' . date('Y')) : '';
    @endphp

    <!-- Data Table Card Siswa & Status Syarat -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
      <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
        <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
          <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
            <i class="fas fa-list-ul text-primary mr-2"></i> Daftar Kelayakan Siswa: {{ $activeTkk ? ($activeTkk->nomor_klaster ? 'Klaster #'.$activeTkk->nomor_klaster.' - ' : '').$activeTkk->nama_tkk : 'TKK Terpilih' }}
          </h3>
          <span class="badge badge-light border text-secondary px-2 py-1" style="font-size: 0.75rem;">
            Total: {{ $siswaList->count() }} Siswa
          </span>
          @if($jumlahMemenuhiSyarat > 0)
            <span class="badge badge-primary px-2 py-1 font-weight-bold" style="font-size: 0.75rem;">
              <i class="fas fa-star mr-1"></i> {{ $jumlahMemenuhiSyarat }} Siswa Siap Disahkan
            </span>
          @endif
        </div>

        <div>
          <button type="button" class="btn btn-sm {{ $jumlahMemenuhiSyarat > 0 ? 'btn-success' : 'btn-outline-primary' }} font-weight-bold shadow-sm" id="btnTriggerSahkanMasal">
            <i class="fas fa-check-double mr-1"></i> Sahkan Masal <span id="countSelectedBadgeText">({{ $jumlahMemenuhiSyarat > 0 ? $jumlahMemenuhiSyarat . ' Siswa Layak' : 'Siswa Terpilih' }})</span>
          </button>
        </div>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th style="width: 40px" class="text-center">
                  <input type="checkbox" id="checkAllSiswa" title="Pilih / Batalkan Semua">
                </th>
                <th style="width: 45px" class="text-center">No</th>
                <th>Nama Siswa</th>
                <th>NIS</th>
                <th style="width: 170px" class="text-center">Syarat Tercapai</th>
                <th style="width: 170px" class="text-center">Status Kelayakan</th>
                <th>Piagam / Bukti</th>
                <th style="width: 150px" class="text-center">Tindakan</th>
              </tr>
            </thead>
            <tbody>
              @forelse($siswaList as $s)
                <tr>
                  <td class="text-center align-middle">
                    @if($s->tkk_status === 'diberikan')
                      <i class="fas fa-check-circle text-success" title="Sudah Resmi Diberikan"></i>
                    @else
                      <input type="checkbox" name="siswa_ids[]" value="{{ $s->id_siswa }}" class="check-siswa" form="formSahkanMasal" {{ $s->tkk_status === 'memenuhi_syarat' ? 'checked' : '' }}>
                    @endif
                  </td>
                  <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                  <td class="align-middle">
                    <div class="font-weight-600 text-dark">{{ $s->nama_siswa }}</div>
                    @if($s->kelas)
                      <span class="badge badge-light border text-muted px-1 py-0 mt-1" style="font-size: 0.72rem;">
                        <i class="fas fa-graduation-cap mr-1"></i>{{ $s->kelas->nama_kelas }}
                      </span>
                    @endif
                  </td>
                  <td class="align-middle text-muted">{{ $s->nis ?: '-' }}</td>
                  <td class="text-center align-middle">
                    <div class="d-flex flex-column align-items-center">
                      <span class="badge badge-light border font-weight-bold px-2 py-0 mb-1" style="font-size: 0.78rem;">
                        {{ $s->tkk_syarat_lulus }} / {{ $s->tkk_syarat_total }} Unit ({{ $s->tkk_syarat_persen ?? 0 }}%)
                      </span>
                      <div class="progress w-100" style="height: 6px; border-radius: 4px; background-color: #e2e8f0; max-width: 120px;">
                        <div class="progress-bar {{ ($s->tkk_syarat_persen ?? 0) >= 100 ? 'bg-success' : (($s->tkk_syarat_persen ?? 0) > 0 ? 'bg-info' : 'bg-secondary') }}" 
                             role="progressbar" 
                             style="width: {{ $s->tkk_syarat_persen ?? 0 }}%" 
                             aria-valuenow="{{ $s->tkk_syarat_persen ?? 0 }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100"></div>
                      </div>
                    </div>
                  </td>
                  <td class="text-center align-middle">
                    @if($s->tkk_status === 'diberikan')
                      <span class="badge badge-success px-2 py-1 font-weight-bold">
                        <i class="fas fa-check-double mr-1"></i> Telah Diberikan
                      </span>
                    @elseif($s->tkk_status === 'memenuhi_syarat')
                      <span class="badge badge-primary px-2 py-1 font-weight-bold">
                        <i class="fas fa-star mr-1"></i> Memenuhi Syarat (100%)
                      </span>
                    @elseif($s->tkk_status === 'proses')
                      <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Proses ({{ $s->tkk_syarat_persen ?? 0 }}%)
                      </span>
                    @else
                      <span class="badge badge-secondary px-2 py-1 font-weight-bold">
                        Belum Memulai
                      </span>
                    @endif
                  </td>
                  <td class="align-middle">
                    @if($s->tkk_rekor && $s->tkk_rekor->nomor_piagam)
                      <span class="font-weight-500 text-dark d-block">No: {{ $s->tkk_rekor->nomor_piagam }}</span>
                      <small class="text-muted">{{ $s->tkk_rekor->tanggal_perolehan ? $s->tkk_rekor->tanggal_perolehan->format('d/m/Y') : '' }}</small>
                    @else
                      <span class="text-muted font-italic">-</span>
                    @endif
                  </td>
                  <td class="text-center align-middle">
                    @php
                      $isSudahDiberikan = ($s->tkk_status === 'diberikan' && $s->tkk_rekor);
                      $isLayak100 = ($s->tkk_syarat_persen >= 100);
                      $currNomorPiagam = ($s->tkk_rekor && $s->tkk_rekor->nomor_piagam) ? $s->tkk_rekor->nomor_piagam : '';
                    @endphp

                    @if($isSudahDiberikan)
                      <!-- Siswa yang Sudah Resmi Menerima Piagam -->
                      <div class="btn-group" role="group">
                        <a href="/admin/kejuruan/tkk/piagam/{{ $s->tkk_rekor->id_siswa_tkk }}" target="_blank" class="btn btn-sm btn-outline-success font-weight-bold shadow-sm" title="Buka dan Cetak Piagam Sertifikat">
                          <i class="fas fa-print mr-1"></i> Cetak
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <span class="sr-only">Menu Tambahan</span>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right shadow-sm border-0" style="border-radius: 8px;">
                          <a class="dropdown-item py-2" href="#" data-toggle="modal" data-target="#modalSahkan{{ $s->id_siswa }}">
                            <i class="fas fa-edit mr-2 text-primary"></i> Edit Data Piagam
                          </a>
                          <div class="dropdown-divider"></div>
                          <form action="/admin/kejuruan/tkk/batalkan/{{ $s->tkk_rekor->id_siswa_tkk }}" method="POST" onsubmit="return confirm('Batalkan pengesahan TKK untuk siswa {{ addslashes($s->nama_siswa) }}? Status akan dikembalikan ke Dalam Proses.')">
                            @csrf
                            <button type="submit" class="dropdown-item py-2 text-danger">
                              <i class="fas fa-undo-alt mr-2"></i> Batalkan Pengesahan
                            </button>
                          </form>
                        </div>
                      </div>

                    @elseif($isLayak100)
                      <!-- Siswa Memenuhi Syarat 100% -->
                      <button type="button" class="btn btn-sm btn-success px-3 shadow-sm font-weight-bold" data-toggle="modal" data-target="#modalSahkan{{ $s->id_siswa }}" title="Sahkan Piagam TKK Siswa">
                        <i class="fas fa-award mr-1"></i> Sahkan TKK
                      </button>

                    @else
                      <!-- Siswa Belum 100% (Dalam Proses / Belum Memenuhi) -->
                      <div class="btn-group" role="group">
                        <button type="button" class="btn btn-sm btn-outline-secondary px-2" disabled title="Belum memenuhi syarat (baru {{ $s->tkk_syarat_lulus }} / {{ $s->tkk_syarat_total }} unit)">
                          <i class="fas fa-lock mr-1"></i> Belum Layak
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning px-2" data-toggle="modal" data-target="#modalSahkan{{ $s->id_siswa }}" title="Dispensasi Pengesahan Berdasarkan Portofolio Eksternal">
                          <i class="fas fa-shield-alt mr-1"></i> Dispensasi
                        </button>
                      </div>
                    @endif

                    <!-- Modal Sahkan / Edit TKK -->
                    <div class="modal fade text-left" id="modalSahkan{{ $s->id_siswa }}" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
                          <div class="modal-header {{ $isSudahDiberikan ? 'bg-success' : ($isLayak100 ? 'bg-primary' : 'bg-warning text-dark') }} text-white py-3 px-4">
                            <h6 class="modal-title font-weight-bold mb-0">
                              <i class="fas fa-award mr-2"></i>
                              @if($isSudahDiberikan)
                                Edit Pengesahan Piagam TKK: {{ $s->nama_siswa }}
                              @elseif($isLayak100)
                                Pengesahan Piagam TKK: {{ $s->nama_siswa }}
                              @else
                                Pengesahan Khusus (Dispensasi): {{ $s->nama_siswa }}
                              @endif
                            </h6>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <form action="/admin/kejuruan/tkk/sahkan" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id_siswa" value="{{ $s->id_siswa }}">
                            <input type="hidden" name="id_jenis_tkk" value="{{ $activeTkk->id_jenis_tkk }}">

                            <div class="modal-body p-4">
                              <!-- Ringkasan Info Siswa & Status Syarat -->
                              <div class="p-3 rounded bg-light border mb-3">
                                <div class="row">
                                  <div class="col-md-6 border-right">
                                    <span class="text-muted small d-block">Siswa & Kelas:</span>
                                    <strong class="text-dark">{{ $s->nama_siswa }}</strong>
                                    <div class="small text-muted">NIS: {{ $s->nis ?? '-' }} • Kelas: {{ $s->kelas->nama_kelas ?? '-' }}</div>
                                  </div>
                                  <div class="col-md-6 mt-2 mt-md-0">
                                    <span class="text-muted small d-block">Skema Lencana TKK:</span>
                                    <strong class="text-dark">{{ $activeTkk->nomor_klaster ? 'Klaster #'.$activeTkk->nomor_klaster.' : ' : '' }}{{ $activeTkk->nama_tkk }}</strong>
                                    <div class="small text-primary font-weight-bold">Tingkat {{ ucfirst($activeTkk->tingkat) }}</div>
                                  </div>
                                </div>
                              </div>

                              <!-- Rincian Checklist Unit Kompetensi Prasyarat -->
                              <div class="mb-3">
                                <label class="font-weight-bold text-dark small mb-2 d-flex align-items-center justify-content-between">
                                  <span><i class="fas fa-tasks mr-1 text-primary"></i> Status Pemenuhan Unit Kompetensi ({{ $s->tkk_syarat_lulus }} / {{ $s->tkk_syarat_total }} Unit Lulus)</span>
                                  <span class="badge {{ $isLayak100 ? 'badge-success' : 'badge-warning text-dark' }} px-2 py-1 font-weight-bold">
                                    {{ $s->tkk_syarat_persen }}% Terpenuhi
                                  </span>
                                </label>
                                <div class="table-responsive border rounded" style="max-height: 180px; overflow-y: auto;">
                                  <table class="table table-sm table-striped mb-0 small">
                                    <thead class="bg-light">
                                      <tr>
                                        <th style="width: 30px;" class="text-center">#</th>
                                        <th>Kode & Nama Unit Kompetensi</th>
                                        <th style="width: 130px;" class="text-center">Status</th>
                                        <th style="width: 80px;" class="text-center">Nilai</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      @foreach($s->tkk_syarat_details ?? [] as $uIdx => $uDet)
                                        <tr>
                                          <td class="text-center font-weight-bold text-muted">{{ $uIdx + 1 }}</td>
                                          <td>
                                            <span class="badge badge-light border text-monospace mr-1">{{ $uDet->kode_kompetensi }}</span>
                                            {{ $uDet->nama_kompetensi }}
                                          </td>
                                          <td class="text-center">
                                            @if($uDet->is_lulus)
                                              <span class="badge badge-success px-2 py-0"><i class="fas fa-check-circle mr-1"></i> Kompeten</span>
                                            @else
                                              <span class="badge badge-secondary px-2 py-0"><i class="fas fa-clock mr-1"></i> Belum Lulus</span>
                                            @endif
                                          </td>
                                          <td class="text-center font-weight-bold">
                                            {{ $uDet->nilai !== null ? $uDet->nilai : '-' }}
                                          </td>
                                        </tr>
                                      @endforeach
                                    </tbody>
                                  </table>
                                </div>
                              </div>

                              @if(!$isLayak100 && !$isSudahDiberikan)
                                <!-- Peringatan & Opsi Dispensasi Jika Belum 100% -->
                                <div class="alert alert-warning border-0 shadow-sm p-3 mb-3 small" style="border-radius: 8px; background-color: #fff9db; color: #856404; border-left: 4px solid #f59f00 !important;">
                                  <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-exclamation-triangle mr-2 text-warning" style="font-size: 1.1rem;"></i>
                                    <strong>Perhatian: Siswa belum memenuhi 100% unit kompetensi prasyarat skema ini.</strong>
                                  </div>
                                  <div class="custom-control custom-checkbox mt-2">
                                    <input type="checkbox" class="custom-control-input" name="is_dispensasi" value="1" id="dispCheck{{ $s->id_siswa }}" required>
                                    <label class="custom-control-label font-weight-bold text-dark" for="dispCheck{{ $s->id_siswa }}">
                                      Saya mengesahkan siswa ini melalui jalur Dispensasi Portofolio Luar / Sertifikasi Industri Setara
                                    </label>
                                  </div>
                                  <div class="mt-2">
                                    <label class="font-weight-bold text-dark small mb-1">Alasan Dispensasi / Nama Sertifikat Eksternal:</label>
                                    <input type="text" name="alasan_dispensasi" class="form-control form-control-sm" placeholder="Contoh: Memiliki Sertifikat Industri MikroTik / BNSP Setara">
                                  </div>
                                </div>
                              @endif

                              <!-- Form Data Piagam -->
                              <div class="row">
                                <div class="col-md-6 form-group mb-2">
                                  <label class="font-weight-bold text-dark small mb-1">Tanggal Perolehan / Pengesahan <span class="text-danger">*</span></label>
                                  <input type="date" name="tanggal_perolehan" class="form-control form-control-sm" value="{{ $s->tkk_rekor && $s->tkk_rekor->tanggal_perolehan ? $s->tkk_rekor->tanggal_perolehan->format('Y-m-d') : date('Y-m-d') }}" required>
                                </div>

                                <div class="col-md-6 form-group mb-2">
                                  <label class="font-weight-bold text-dark small mb-1">Nomor Piagam / Sertifikat TKK</label>
                                  <input type="text" name="nomor_piagam" class="form-control form-control-sm" placeholder="Otomatis: {{ $defaultNoPiagam }}" value="{{ $currNomorPiagam }}">
                                  <small class="text-muted d-block mt-1">Kosongkan untuk penomoran otomatis sistem (Perkiraan: <code>{{ $defaultNoPiagam }}</code>)</small>
                                </div>
                              </div>

                              <div class="form-group mb-2">
                                <label class="font-weight-bold text-dark small mb-1">Unggah Foto / Berkas Bukti Sertifikat (Opsional)</label>
                                <input type="file" name="file_bukti" class="form-control-file small" accept=".pdf,.jpg,.jpeg,.png">
                                <small class="text-muted d-block mt-1">Format: PDF, JPG, PNG (Maks 5MB)</small>
                              </div>

                              <div class="form-group mb-0">
                                <label class="font-weight-bold text-dark small mb-1">Catatan Penguji / Kaprog</label>
                                <textarea name="catatan" class="form-control form-control-sm" rows="2" placeholder="Catatan pengesahan lencana keahlian">{{ $s->tkk_rekor ? $s->tkk_rekor->catatan : ($isLayak100 ? 'Telah memenuhi seluruh unit kompetensi prasyarat skema ' . $activeTkk->nama_tkk : '') }}</textarea>
                              </div>
                            </div>

                            <div class="modal-footer bg-light py-2 px-3">
                              <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-dismiss="modal" style="border-radius: 6px;">Batal</button>
                              <button type="submit" class="btn btn-primary btn-sm px-3 font-weight-bold shadow-sm" style="border-radius: 6px;">
                                <i class="fas fa-check mr-1"></i> {{ $isSudahDiberikan ? 'Simpan Perubahan Piagam' : 'Sahkan & Catat ke Capaian Siswa' }}
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="8" class="text-center py-5 text-muted">
                    <i class="fas fa-user-clock mb-2" style="font-size: 2.5rem; opacity: 0.4;"></i>
                    <h6 class="font-weight-bold text-dark">Belum Ada Siswa Penerima atau Memenuhi Syarat 100%</h6>
                    <p class="small mb-0">Siswa akan otomatis muncul di sini setelah menuntaskan seluruh unit kompetensi syarat, atau pilih kelas tertentu di atas untuk menganalisis progres siswa.</p>
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

<!-- Modal Sahkan Masal -->
<div class="modal fade" id="modalSahkanMasal" tabindex="-1" role="dialog" aria-labelledby="modalSahkanMasalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content border-0 shadow" style="border-radius: 12px; overflow: hidden;">
      <form id="formSahkanMasal" action="{{ url('/admin/kejuruan/tkk/sahkan-masal') }}" method="POST">
        @csrf
        <input type="hidden" name="id_jenis_tkk" value="{{ $activeTkk ? $activeTkk->id_jenis_tkk : '' }}">
        <div class="modal-header bg-success text-white py-3">
          <h5 class="modal-title font-weight-bold" id="modalSahkanMasalLabel">
            <i class="fas fa-award mr-2"></i> Pengesahan Masal TKK
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body p-3">
          <div class="alert alert-info border-0 mb-3 small" style="border-radius: 8px;">
            <i class="fas fa-info-circle mr-1"></i> Anda akan mengesahkan TKK <strong>{{ $activeTkk ? $activeTkk->nama_tkk : '' }}</strong> untuk <strong id="modalSelectedCount">0</strong> siswa yang dicentang di tabel. Data capaian dan nomor piagam akan diterbitkan secara berurutan.
          </div>

          <div class="form-group mb-2">
            <label class="font-weight-bold text-dark small mb-1">Tanggal Pengesahan <span class="text-danger">*</span></label>
            <input type="date" name="tanggal_perolehan" class="form-control form-control-sm" value="{{ date('Y-m-d') }}" required>
          </div>

          <div class="form-group mb-2">
            <label class="font-weight-bold text-dark small mb-1">Prefix / Override Format Penomoran (Opsional)</label>
            <input type="text" name="prefix_piagam" class="form-control form-control-sm" placeholder="Kosongkan untuk penomoran otomatis sesuai format resmi">
            <small class="text-muted d-block mt-1">Jika dikosongkan, nomor otomatis digenerate berurutan mengikuti pengaturan: <code>{{ $defaultFormatTkk }}</code> (Mulai: <code>{{ $defaultNoPiagam }}</code>).</small>
          </div>

          <div class="form-group mb-0">
            <label class="font-weight-bold text-dark small mb-1">Catatan Pengesahan (Opsional)</label>
            <textarea name="catatan" class="form-control form-control-sm" rows="2" placeholder="Catatan pengesahan masal lencana TKK"></textarea>
          </div>
        </div>
        <div class="modal-footer bg-light py-2 px-3">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success btn-sm px-3 font-weight-bold">
            <i class="fas fa-check mr-1"></i> Sahkan Siswa Terpilih
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('checkAllSiswa');
    const checkboxes = document.querySelectorAll('.check-siswa');
    const btnTrigger = document.getElementById('btnTriggerSahkanMasal');
    const modalSelectedCount = document.getElementById('modalSelectedCount');

    function updateCount() {
      const selected = document.querySelectorAll('.check-siswa:checked').length;
      if (modalSelectedCount) modalSelectedCount.textContent = selected;
    }

    if (checkAll) {
      checkAll.addEventListener('change', function() {
        checkboxes.forEach(cb => cb.checked = checkAll.checked);
        updateCount();
      });
    }

    checkboxes.forEach(cb => {
      cb.addEventListener('change', updateCount);
    });

    if (btnTrigger) {
      btnTrigger.addEventListener('click', function() {
        const selectedCount = document.querySelectorAll('.check-siswa:checked').length;
        if (selectedCount === 0) {
          alert('Silakan centang minimal 1 siswa di kolom kotak centang tabel terlebih dahulu.');
          return;
        }
        updateCount();
        $('#modalSahkanMasal').modal('show');
      });
    }
  });
</script>
@endpush
@endsection
