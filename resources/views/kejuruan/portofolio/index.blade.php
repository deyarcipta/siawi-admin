@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-folder-open text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Portofolio & Sertifikat Siswa</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Dokumentasi digital karya nyata, piagam kejuaraan, dan sertifikasi keahlian siswa SMK</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kejuruan/portofolio" class="text-primary font-weight-500">Kejuruan</a></li>
          <li class="breadcrumb-item active">Portofolio & Sertifikat</li>
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

    <!-- Stat Cards -->
    <div class="row mb-3">
      <!-- Total Portofolio -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Total Berkas</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalPorto }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Dokumen</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-archive text-primary mr-1"></i> Terarsip di Sistem</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-folder-open"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Sertifikat Keahlian -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Sertifikat Industri</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalSertifikat }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Sertifikat</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-check-circle text-success mr-1"></i> Diklat / LSP / Vendor</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-certificate"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Proyek & Karya -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #8b5cf6 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Proyek Karya</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalKarya }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Karya</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-project-diagram text-purple mr-1"></i> Produk & Aplikasi</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f5f3ff; color: #8b5cf6; flex-shrink: 0;">
              <i class="fas fa-layer-group"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Menunggu Verifikasi -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Menunggu Verifikasi</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $totalMenunggu }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Pengajuan</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-clock text-warning mr-1"></i> Butuh Validasi Guru</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #fffbeb; color: #f59e0b; flex-shrink: 0;">
              <i class="fas fa-user-check"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filter Toolbar Card -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-3">
        <form method="GET" action="/admin/kejuruan/portofolio">
          <div class="row align-items-end">
            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
              <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Kategori</label>
              <select name="kategori" class="form-control form-control-sm">
                <option value="">Semua Kategori</option>
                <option value="sertifikat" {{ request('kategori') == 'sertifikat' ? 'selected' : '' }}>Sertifikat Kompetensi</option>
                <option value="proyek_karya" {{ request('kategori') == 'proyek_karya' ? 'selected' : '' }}>Proyek / Hasil Karya</option>
                <option value="prestasi_lomba" {{ request('kategori') == 'prestasi_lomba' ? 'selected' : '' }}>Prestasi Lomba / Juara</option>
              </select>
            </div>

            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
              <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Rombel Kelas</label>
              <select name="id_kelas" class="form-control form-control-sm select2bs4">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $k)
                  <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
              <label class="font-weight-bold text-secondary mb-1" style="font-size: 0.78rem; text-transform: uppercase;">Status Verifikasi</label>
              <select name="status_verifikasi" class="form-control form-control-sm">
                <option value="">Semua Status</option>
                <option value="disetujui" {{ request('status_verifikasi') == 'disetujui' ? 'selected' : '' }}>Disetujui (Tervalidasi)</option>
                <option value="menunggu" {{ request('status_verifikasi') == 'menunggu' ? 'selected' : '' }}>Menunggu Persetujuan</option>
                <option value="ditolak" {{ request('status_verifikasi') == 'ditolak' ? 'selected' : '' }}>Ditolak / Perlu Perbaikan</option>
              </select>
            </div>

            <div class="col-md-3 col-sm-6 d-flex align-items-center">
              <button type="submit" class="btn btn-sm btn-primary mr-2" style="border-radius: 8px;">
                <i class="fas fa-filter mr-1"></i> Filter
              </button>
              <a href="/admin/kejuruan/portofolio" class="btn btn-sm btn-outline-secondary" style="border-radius: 8px;">
                <i class="fas fa-undo mr-1"></i> Reset
              </a>
            </div>
          </div>
        </form>

        <!-- Lower Action Toolbar -->
        <div class="pt-3 mt-3 border-top d-flex flex-wrap justify-content-between align-items-center">
          <div class="d-flex align-items-center mb-2 mb-md-0">
            <span class="text-muted mr-3" style="font-size: 0.85rem;">
              <i class="fas fa-shield-alt mr-1 text-success"></i> Berkas tersimpan aman dalam format PDF, PNG, JPG (Maks. 5 MB) terhubung ke Buku Induk Capaian.
            </span>
          </div>
          <div class="d-flex align-items-center">
            <a href="/admin/kejuruan/capaian-siswa" class="btn btn-sm btn-outline-secondary mr-2" style="border-radius: 8px;">
              <i class="fas fa-book-reader mr-1"></i> Buku Induk Capaian
            </a>
            <a href="/admin/kejuruan/portofolio/create" class="btn btn-sm btn-primary" style="border-radius: 8px;">
              <i class="fas fa-plus mr-1"></i> Unggah Sertifikat / Karya
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Data Table Card -->
    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
      <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-dark">
          <i class="fas fa-list mr-2 text-primary"></i> Daftar Portofolio & Sertifikat
        </h6>
        <span class="badge badge-light border px-2 py-1" style="font-size: 0.8rem;">
          Total Berkas: {{ $portofolios->count() }}
        </span>
      </div>

      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
            <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
              <tr>
                <th class="py-3 px-3 text-center" style="width: 50px;">No</th>
                <th class="py-3 px-3">Nama Siswa / Kelas</th>
                <th class="py-3 px-3">Judul Karya / Sertifikat</th>
                <th class="py-3 px-3 text-center">Kategori</th>
                <th class="py-3 px-3">Penyelenggara / Lembaga</th>
                <th class="py-3 px-3 text-center">Tanggal</th>
                <th class="py-3 px-3 text-center">File & Tautan</th>
                <th class="py-3 px-3 text-center">Status</th>
                <th class="py-3 px-3 text-center" style="width: 140px;">Aksi</th>
              </tr>
            </thead>
            <tbody>
              @forelse($portofolios as $idx => $p)
                <tr>
                  <td class="text-center font-weight-bold text-muted px-3">
                    {{ $loop->iteration }}
                  </td>
                  <td class="px-3">
                    <div class="font-weight-bold text-dark">{{ $p->siswa->nama_siswa ?? '-' }}</div>
                    <small class="text-muted">{{ $p->siswa->kelas->nama_kelas ?? '-' }} | NIS: {{ $p->siswa->nis ?? '-' }}</small>
                  </td>
                  <td class="px-3">
                    <div class="font-weight-bold text-dark">{{ $p->judul }}</div>
                    @if($p->kompetensi)
                      <small class="text-primary d-block">
                        <i class="fas fa-tag mr-1"></i> [{{ $p->kompetensi->kode_kompetensi }}] {{ Str::limit($p->kompetensi->nama_kompetensi, 40) }}
                      </small>
                    @endif
                  </td>
                  <td class="text-center px-3">
                    @if($p->kategori == 'sertifikat')
                      <span class="badge badge-success px-2 py-1" style="font-size: 0.75rem;">
                        <i class="fas fa-certificate mr-1"></i> Sertifikat
                      </span>
                    @elseif($p->kategori == 'proyek_karya')
                      <span class="badge badge-info px-2 py-1" style="font-size: 0.75rem;">
                        <i class="fas fa-tools mr-1"></i> Proyek Karya
                      </span>
                    @else
                      <span class="badge badge-warning text-white px-2 py-1" style="font-size: 0.75rem;">
                        <i class="fas fa-trophy mr-1"></i> Lomba / Juara
                      </span>
                    @endif
                  </td>
                  <td class="px-3">
                    <span class="text-secondary">{{ $p->penyelenggara ?: '-' }}</span>
                  </td>
                  <td class="text-center px-3 text-nowrap">
                    {{ $p->tanggal_kegiatan ? \Carbon\Carbon::parse($p->tanggal_kegiatan)->format('d/m/Y') : '-' }}
                  </td>
                  <td class="text-center px-3 text-nowrap">
                    @if($p->file_dokumen)
                      <a href="{{ asset('storage/' . $p->file_dokumen) }}" target="_blank" class="btn btn-xs btn-outline-primary mr-1" title="Lihat Berkas">
                        <i class="fas fa-file-pdf mr-1"></i> Berkas
                      </a>
                    @endif
                    @if($p->tautan_karya)
                      <a href="{{ $p->tautan_karya }}" target="_blank" class="btn btn-xs btn-outline-info" title="Buka Tautan Online">
                        <i class="fas fa-external-link-alt"></i>
                      </a>
                    @endif
                    @if(!$p->file_dokumen && !$p->tautan_karya)
                      <span class="text-muted" style="font-size: 0.8rem;">-</span>
                    @endif
                  </td>
                  <td class="text-center px-3">
                    @if($p->status_verifikasi == 'disetujui')
                      <span class="badge badge-success px-2 py-1" style="font-size: 0.75rem;">
                        <i class="fas fa-check-circle mr-1"></i> Disetujui
                      </span>
                      @if($p->verifikator)
                        <small class="text-muted d-block" style="font-size: 0.7rem;">Oleh: {{ $p->verifikator->nama_guru }}</small>
                      @endif
                    @elseif($p->status_verifikasi == 'ditolak')
                      <span class="badge badge-danger px-2 py-1" style="font-size: 0.75rem;">
                        <i class="fas fa-times-circle mr-1"></i> Ditolak
                      </span>
                    @else
                      <span class="badge badge-warning text-white px-2 py-1" style="font-size: 0.75rem;">
                        <i class="fas fa-clock mr-1"></i> Menunggu
                      </span>
                    @endif
                  </td>
                  <td class="text-center px-3">
                    <div class="btn-group">
                      <!-- Tombol Verifikasi (Modal) -->
                      <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modalVerifikasi{{ $p->id_portofolio }}" title="Verifikasi Portofolio">
                        <i class="fas fa-check-double"></i>
                      </button>

                      <!-- Tombol Hapus -->
                      <form action="/admin/kejuruan/portofolio/{{ $p->id_portofolio }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus arsip portofolio ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Portofolio" style="border-top-left-radius: 0; border-bottom-left-radius: 0;">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </form>
                    </div>

                    <!-- Modal Verifikasi -->
                    <div class="modal fade" id="modalVerifikasi{{ $p->id_portofolio }}" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered text-left" role="document">
                        <div class="modal-content border-0 shadow" style="border-radius: 12px;">
                          <div class="modal-header bg-primary text-white" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                            <h5 class="modal-title font-weight-bold" style="font-size: 1.05rem;">
                              <i class="fas fa-stamp mr-2"></i> Verifikasi Portofolio Siswa
                            </h5>
                            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form action="/admin/kejuruan/portofolio/{{ $p->id_portofolio }}/verifikasi" method="POST">
                            @csrf
                            <div class="modal-body p-4">
                              <div class="p-3 bg-light rounded mb-3 border">
                                <div class="font-weight-bold text-dark">{{ $p->judul }}</div>
                                <div class="text-muted" style="font-size: 0.85rem;">Siswa: <strong>{{ $p->siswa->nama_siswa ?? '-' }}</strong> ({{ $p->siswa->kelas->nama_kelas ?? '-' }})</div>
                                <div class="text-muted" style="font-size: 0.85rem;">Penyelenggara: {{ $p->penyelenggara ?: '-' }}</div>
                                @if($p->deskripsi)
                                  <div class="mt-2 text-secondary" style="font-size: 0.83rem;">
                                    <strong>Deskripsi:</strong><br>
                                    {!! strip_tags($p->deskripsi, '<p><br><strong><b><i><em><u><ul><ol><li><a>') !!}
                                  </div>
                                @endif
                              </div>

                              <div class="form-group">
                                <label class="font-weight-bold text-secondary" style="font-size: 0.82rem;">Keputusan Verifikasi <span class="text-danger">*</span></label>
                                <select name="status_verifikasi" class="form-control" required>
                                  <option value="disetujui" {{ $p->status_verifikasi == 'disetujui' ? 'selected' : '' }}>Setujui (Validasi & Catat di Buku Induk Capaian)</option>
                                  <option value="ditolak" {{ $p->status_verifikasi == 'ditolak' ? 'selected' : '' }}>Tolak / Minta Revisi Berkas</option>
                                </select>
                              </div>

                              <div class="form-group mb-0">
                                <label class="font-weight-bold text-secondary" style="font-size: 0.82rem;">Catatan Penguji / Guru</label>
                                <textarea name="catatan_verifikasi" class="form-control" rows="3" placeholder="Masukkan catatan atau evaluasi terhadap portofolio ini...">{{ $p->catatan_verifikasi }}</textarea>
                              </div>
                            </div>
                            <div class="modal-footer bg-light border-0">
                              <button type="button" class="btn btn-sm btn-outline-secondary" data-dismiss="modal">Tutup</button>
                              <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-check mr-1"></i> Simpan Verifikasi
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
                  <td colspan="9" class="text-center py-5 text-muted">
                    <i class="fas fa-folder-open fa-3x mb-3 text-muted" style="opacity: 0.4;"></i>
                    <p class="mb-0 font-weight-500">Belum ada portofolio atau sertifikat yang diunggah.</p>
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
@endsection
