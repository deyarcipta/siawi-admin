@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-building text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Mitra Perusahaan & Plotting PKL</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Kelola kemitraan dunia usaha & industri (DU/DI) serta penempatan siswa magang kerja</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="#">BKK & Hubin</a></li>
          <li class="breadcrumb-item active">Mitra Perusahaan</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Content -->
<div class="content">
  <div class="container-fluid">

    <!-- Stat Cards Overview -->
    <div class="row mb-3">
      <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon bg-primary text-white" style="border-radius: 10px;"><i class="fas fa-building"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">TOTAL MITRA DU/DI</span>
            <span class="info-box-number text-dark font-weight-bold" style="font-size: 1.35rem;">{{ $totalMitra ?? $perusahaan->count() }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Mitra</small></span>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon bg-success text-white" style="border-radius: 10px;"><i class="fas fa-running"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">SEDANG AKTIF PKL</span>
            <span class="info-box-number text-dark font-weight-bold" style="font-size: 1.35rem;">{{ $totalSiswaPklAktif ?? 0 }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Siswa</small></span>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
        <div class="info-box shadow-sm border-0" style="border-radius: 12px;">
          <span class="info-box-icon text-white" style="background-color: #0284c7; border-radius: 10px;"><i class="fas fa-calendar-check"></i></span>
          <div class="info-box-content">
            <span class="info-box-text text-muted font-weight-bold" style="font-size: 0.78rem;">SUDAH DITEMPATKAN</span>
            <span class="info-box-number text-dark font-weight-bold" style="font-size: 1.35rem;">{{ $totalSiswaDitempatkan ?? 0 }} <small class="font-weight-normal text-muted" style="font-size: 0.8rem;">Siswa</small></span>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6 col-sm-12 mb-2 d-flex align-items-center">
        <a href="{{ route('admin.siswaPkl.index') }}" class="btn btn-outline-primary btn-block py-2 py-sm-3 shadow-sm font-weight-bold" style="border-radius: 12px; border-width: 2px;">
          <i class="fas fa-list-alt mr-1"></i> Rekapitulasi Global &rarr;
        </a>
      </div>
    </div>

    <!-- Main Card -->
    <div class="row">
      <div class="col-lg-12">
        <div class="card shadow-sm border-0" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-2 py-sm-3 d-flex justify-content-between align-items-center" style="gap: 10px;">
            <div class="d-flex align-items-center pr-1" style="min-width: 0;">
              <i class="fas fa-handshake text-primary mr-2" style="font-size: 1.25rem;"></i>
              <div style="min-width: 0;">
                <h3 class="card-title text-dark font-weight-bold mb-0 text-truncate" style="font-size: 0.95rem; line-height: 1.2; float: none;">
                  Mitra Perusahaan
                </h3>
                <div class="text-muted d-none d-sm-block" style="font-size: 0.74rem;">Penempatan & plotting siswa PKL</div>
              </div>
            </div>
            <div class="flex-shrink-0">
              <button class="btn btn-primary btn-sm px-2 px-sm-3 shadow-sm font-weight-bold" style="border-radius: 8px; font-size: 0.82rem; white-space: nowrap;" data-toggle="modal" data-target="#modalTambahPerusahaan">
                <i class="fas fa-plus mr-1"></i> <span class="d-none d-sm-inline">Tambah Mitra Perusahaan</span><span class="d-inline d-sm-none">Tambah Mitra</span>
              </button>
            </div>
          </div>

          <div class="card-body p-3">
            <div class="table-responsive">
              <table id="example2" class="table table-bordered table-hover table-striped align-middle">
                <thead class="bg-light text-dark">
                  <tr>
                    <th style="width: 10px" class="text-center">No</th>
                    <th>Nama Mitra Perusahaan</th>
                    <th>Alamat Industri</th>
                    <th>Penanggung Jawab</th>
                    <th class="text-center" style="min-width: 140px;">Siswa PKL</th>
                    <th class="text-center" style="min-width: 190px;">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($perusahaan as $data)
                  <tr>
                    <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                    <td>
                      <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                        <i class="fas fa-building text-primary mr-1"></i> {{ $data->nama_perusahaan }}
                      </div>
                    </td>
                    <td>
                      <span class="text-muted" style="font-size: 0.88rem;">
                        <i class="fas fa-map-marker-alt text-danger mr-1"></i> {{ $data->alamat_perusahaan }}
                      </span>
                    </td>
                    <td>
                      <span class="font-weight-500 text-dark" style="font-size: 0.88rem;">
                        <i class="fas fa-user-tie text-secondary mr-1"></i> {{ $data->penanggung_jawab }}
                      </span>
                    </td>
                    <td class="text-center">
                      @if($data->siswa_aktif_count > 0)
                        <span class="badge badge-success px-2 py-1 font-weight-bold mb-1 d-inline-block shadow-sm" style="font-size: 0.8rem; cursor: pointer;" data-toggle="modal" data-target="#modalKelolaPkl_{{ $data->id_perusahaan }}" title="Klik untuk lihat daftar siswa">
                          <i class="fas fa-running mr-1"></i> {{ $data->siswa_aktif_count }} Aktif
                        </span>
                      @endif

                      @if($data->siswa_ditempatkan_count > 0)
                        <span class="badge px-2 py-1 font-weight-bold text-white mb-1 d-inline-block shadow-sm" style="background-color: #0284c7; font-size: 0.8rem; cursor: pointer;" data-toggle="modal" data-target="#modalKelolaPkl_{{ $data->id_perusahaan }}" title="Klik untuk lihat daftar siswa">
                          <i class="fas fa-calendar-check mr-1"></i> {{ $data->siswa_ditempatkan_count }} Ditempatkan
                        </span>
                      @endif

                      @if($data->siswa_aktif_count == 0 && $data->siswa_ditempatkan_count == 0)
                        @if($data->siswa_total_count > 0)
                          <span class="badge badge-secondary px-2 py-1" style="font-size: 0.78rem; cursor: pointer;" data-toggle="modal" data-target="#modalKelolaPkl_{{ $data->id_perusahaan }}">
                            {{ $data->siswa_total_count }} Selesai
                          </span>
                        @else
                          <span class="badge badge-light border text-muted px-2 py-1" style="font-size: 0.78rem;">
                            0 Siswa
                          </span>
                        @endif
                      @endif
                    </td>
                    <td class="text-center">
                      <div class="btn-group" role="group">
                        <!-- Tombol Kelola / Plotting Siswa PKL -->
                        <button 
                          type="button" 
                          class="btn btn-sm btn-primary font-weight-bold px-2"
                          data-toggle="modal" 
                          data-target="#modalKelolaPkl_{{ $data->id_perusahaan }}"
                          title="Kelola & Plotting Siswa PKL di perusahaan ini"
                          style="border-radius: 6px 0 0 6px;"
                        >
                          <i class="fas fa-users mr-1"></i> Plotting Siswa
                        </button>

                        <!-- Tombol Edit Perusahaan -->
                        <button 
                          type="button" 
                          class="btn btn-sm btn-warning text-white btn-edit-perusahaan px-2"
                          data-toggle="modal"
                          data-target="#modalEditPerusahaan"
                          data-id="{{ $data->id_perusahaan }}"
                          data-nama="{{ $data->nama_perusahaan }}"
                          data-alamat="{{ $data->alamat_perusahaan }}"
                          data-pj="{{ $data->penanggung_jawab }}"
                          title="Edit Info Perusahaan"
                        >
                          <i class="fa fa-edit"></i>
                        </button>

                        <!-- Tombol Hapus Perusahaan -->
                        <button 
                          type="button" 
                          class="btn btn-sm btn-danger btn-delete-swal px-2" 
                          data-id="{{ $data->id_perusahaan }}"
                          data-nama="{{ $data->nama_perusahaan }}"
                          data-action="{{ route('admin.perusahaan.destroy', $data->id_perusahaan) }}"
                          title="Hapus Perusahaan"
                          style="border-radius: 0 6px 6px 0;"
                        >
                          <i class="fa fa-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  @empty
                  <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                      <i class="fas fa-building fa-2x mb-2 text-secondary d-block"></i>
                      Belum ada data mitra perusahaan. Klik tombol <b>"Tambah Mitra Perusahaan"</b> untuk memulai.
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
</div>

<!-- ========================================================================= -->
<!-- MODAL KELOLA & PLOTTING SISWA PKL (PER PERUSAHAAN)                        -->
<!-- ========================================================================= -->
@foreach ($perusahaan as $data)
<div class="modal fade modal-kelola-pkl" id="modalKelolaPkl_{{ $data->id_perusahaan }}" tabindex="-1" role="dialog" aria-labelledby="modalKelolaLabel_{{ $data->id_perusahaan }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
      
      <!-- Modal Header -->
      <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #1d72fe 0%, #0b1f3a 100%);">
        <div>
          <h5 class="modal-title font-weight-bold mb-0" id="modalKelolaLabel_{{ $data->id_perusahaan }}">
            <i class="fas fa-building mr-2"></i> Plotting & Siswa PKL: {{ $data->nama_perusahaan }}
          </h5>
          <small class="text-white-50 d-block mt-1">
            <i class="fas fa-map-marker-alt mr-1"></i> {{ $data->alamat_perusahaan }} | <i class="fas fa-user-tie mr-1"></i> PJ: {{ $data->penanggung_jawab }}
          </small>
        </div>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Navigation Tabs -->
      <div class="px-4 pt-3 pb-0 bg-light border-bottom">
        <ul class="nav nav-pills" id="pills-tab-{{ $data->id_perusahaan }}" role="tablist">
          <li class="nav-item mr-2">
            <a class="nav-link active font-weight-bold py-2 px-3" id="tab-list-{{ $data->id_perusahaan }}" data-toggle="pill" href="#pane-list-{{ $data->id_perusahaan }}" role="tab" style="border-radius: 8px;">
              <i class="fas fa-users mr-1"></i> Siswa di Tempatkan ({{ $data->siswaPkl->count() }})
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold py-2 px-3 btn-tambah-plot" id="tab-add-{{ $data->id_perusahaan }}" data-toggle="pill" href="#pane-add-{{ $data->id_perusahaan }}" role="tab" style="border-radius: 8px;">
              <i class="fas fa-user-plus mr-1"></i> + Tempatkan Siswa Baru
            </a>
          </li>
        </ul>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4">
        <div class="tab-content" id="pills-tabContent-{{ $data->id_perusahaan }}">
          
          <!-- TAB 1: DAFTAR SISWA DI PERUSAHAAN INI -->
          <div class="tab-pane fade show active" id="pane-list-{{ $data->id_perusahaan }}" role="tabpanel">
            @if($data->siswaPkl->isNotEmpty())
              <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                  <thead class="bg-light">
                    <tr>
                      <th style="width: 10px;" class="text-center">No</th>
                      <th>Nama Siswa & NIS</th>
                      <th>Kelas</th>
                      <th>Periode Magang PKL</th>
                      <th class="text-center">Status</th>
                      <th class="text-center" style="width: 110px;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($data->siswaPkl as $pkl)
                    <tr>
                      <td class="text-center font-weight-bold text-muted">{{ $loop->iteration }}</td>
                      <td>
                        <div class="font-weight-bold text-dark">{{ $pkl->siswa->nama_siswa ?? 'Siswa Tidak Ditemukan' }}</div>
                        <small class="text-muted">NIS: {{ $pkl->siswa->nis ?? $pkl->siswa->nisn ?? '-' }}</small>
                      </td>
                      <td>
                        <span class="badge badge-light border text-dark font-weight-normal px-2 py-1">
                          {{ $pkl->kelas->nama_kelas ?? ($pkl->siswa->kelas->nama_kelas ?? '-') }}
                        </span>
                      </td>
                      <td>
                        <div style="font-size: 0.85rem;" class="text-dark font-weight-500">
                          <i class="fas fa-calendar-alt text-primary mr-1"></i>
                          {{ \Carbon\Carbon::parse($pkl->tanggal_mulai)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($pkl->tanggal_selesai)->translatedFormat('d M Y') }}
                        </div>
                      </td>
                      <td class="text-center">
                        @if($pkl->status_pkl === 'belum_mulai')
                          <span class="badge px-2 py-1 font-weight-bold text-white shadow-sm" style="background-color: #0284c7; border-radius: 6px;">
                            <i class="fas fa-calendar-check mr-1"></i> Sudah Ditempatkan
                          </span>
                        @elseif($pkl->status_pkl === 'aktif')
                          <span class="badge badge-success px-2 py-1 font-weight-bold shadow-sm" style="border-radius: 6px;">
                            <i class="fas fa-running mr-1"></i> Sedang PKL
                          </span>
                        @else
                          <span class="badge badge-secondary px-2 py-1 font-weight-bold" style="border-radius: 6px;">
                            <i class="fas fa-check-circle mr-1"></i> Selesai
                          </span>
                        @endif
                      </td>
                      <td class="text-center">
                        <div class="btn-group" role="group">
                          @if($pkl->status_pkl !== 'selesai')
                            <!-- Form Quick Selesai -->
                            <form action="{{ route('admin.siswaPkl.update', $pkl->id_siswa_pkl) }}" method="POST" class="d-inline">
                              @csrf
                              @method('PUT')
                              <input type="hidden" name="quick_status" value="1">
                              <input type="hidden" name="status" value="selesai">
                              <button type="submit" class="btn btn-sm btn-outline-success px-2" title="Tandai PKL Selesai" onclick="return confirm('Tandai siswa ini telah menyelesaikan PKL?')">
                                <i class="fas fa-check"></i>
                              </button>
                            </form>
                          @endif

                          <!-- Form Hapus Penempatan -->
                          <form action="{{ route('admin.siswaPkl.destroy', $pkl->id_siswa_pkl) }}" method="POST" class="d-inline ml-1">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-sm btn-outline-danger px-2 btn-delete-siswa-pkl" title="Hapus dari tempat ini" data-nama="{{ $pkl->siswa->nama_siswa ?? 'Siswa' }}">
                              <i class="fas fa-trash"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-center py-5">
                <div class="mb-3 text-muted" style="font-size: 3rem; opacity: 0.35;">
                  <i class="fas fa-user-graduate"></i>
                </div>
                <h6 class="font-weight-bold text-dark">Belum Ada Siswa yang Ditempatkan</h6>
                <p class="text-muted small mb-3">Perusahaan ini belum memiliki siswa yang sedang atau pernah magang PKL.</p>
                <button type="button" class="btn btn-primary btn-sm px-3 shadow-sm font-weight-bold" onclick="$('#tab-add-{{ $data->id_perusahaan }}').tab('show')">
                  <i class="fas fa-user-plus mr-1"></i> Tempatkan Siswa Sekarang
                </button>
              </div>
            @endif
          </div>

          <!-- TAB 2: FORM TAMBAH / TEMPATKAN SISWA BARU -->
          <div class="tab-pane fade" id="pane-add-{{ $data->id_perusahaan }}" role="tabpanel">
            <div class="card border p-3 mb-0" style="border-radius: 10px; background: #fafbfc;">
              <div class="d-flex align-items-center mb-3">
                <i class="fas fa-id-badge text-primary mr-2" style="font-size: 1.25rem;"></i>
                <div class="font-weight-bold text-dark">Formulir Penempatan Siswa Magang</div>
              </div>

              <form action="{{ route('admin.siswaPkl.store') }}" method="POST">
                @csrf
                <input type="hidden" name="id_perusahaan" value="{{ $data->id_perusahaan }}">
                <input type="hidden" name="status" value="PKL">

                <!-- Pilih Siswa (Bisa Multiple / Single) -->
                <div class="form-group">
                  <label class="font-weight-bold text-dark" style="font-size: 0.88rem;">
                    Pilih Siswa <span class="text-danger">*</span>
                    <small class="text-muted font-weight-normal">(Bisa memilih lebih dari satu siswa sekaligus)</small>
                  </label>
                  <select name="id_siswa[]" class="form-control select2-modal" multiple="multiple" data-placeholder="Ketik nama atau kelas siswa..." required style="width: 100%;">
                    @foreach($siswaList as $s)
                      <option value="{{ $s->id_siswa }}">
                        {{ $s->nama_siswa }} - {{ $s->kelas->nama_kelas ?? 'Tanpa Kelas' }} (NIS: {{ $s->nis ?? $s->nisn ?? '-' }})
                      </option>
                    @endforeach
                  </select>
                  <small class="text-muted mt-1 d-block">
                    <i class="fas fa-info-circle text-info mr-1"></i> Kelas siswa otomatis terdeteksi dari data induk siswa.
                  </small>
                </div>

                <!-- Periode Tanggal -->
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <label class="font-weight-bold text-dark" style="font-size: 0.88rem;">
                      Tanggal Mulai PKL <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control" name="tanggal_mulai" value="{{ date('Y-m-d') }}" required style="border-radius: 8px; height: 42px;">
                  </div>
                  <div class="form-group col-md-6">
                    <label class="font-weight-bold text-dark" style="font-size: 0.88rem;">
                      Tanggal Selesai PKL <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control" name="tanggal_selesai" value="{{ date('Y-m-d', strtotime('+3 months')) }}" required style="border-radius: 8px; height: 42px;">
                  </div>
                </div>

                <div class="border-top pt-3 mt-2 d-flex align-items-center">
                  <button type="button" class="btn btn-secondary px-3" onclick="$('#tab-list-{{ $data->id_perusahaan }}').tab('show')" style="border-radius: 8px;">
                    Batal
                  </button>
                  <button type="submit" class="btn btn-primary ml-auto px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
                    <i class="fas fa-save mr-1"></i> Simpan Penempatan Siswa
                  </button>
                </div>
              </form>
            </div>
          </div>

        </div>
      </div>

      <div class="modal-footer bg-light py-2 px-4">
        <button type="button" class="btn btn-outline-secondary btn-sm px-3" data-dismiss="modal" style="border-radius: 6px;">
          Tutup
        </button>
      </div>

    </div>
  </div>
</div>
@endforeach

<!-- ========================================================================= -->
<!-- MODAL TAMBAH PERUSAHAAN MITRA                                             -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalTambahPerusahaan" tabindex="-1" role="dialog" aria-labelledby="modalTambahPerusahaanLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form action="{{ route('admin.perusahaan.store') }}" method="POST">
      @csrf
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
        <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #1d72fe 0%, #0b1f3a 100%);">
          <h5 class="modal-title font-weight-bold" id="modalTambahPerusahaanLabel">
            <i class="fas fa-plus-circle mr-2"></i> Tambah Mitra Perusahaan
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body p-4">
          <div class="form-group mb-3">
            <label for="nama_perusahaan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Nama Perusahaan / Hotel / Industri <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" placeholder="Contoh: Hotel Grand Mercure / PT Telkom" required style="border-radius: 8px; height: 42px;">
          </div>
          <div class="form-group mb-3">
            <label for="alamat_perusahaan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Alamat Lengkap Perusahaan <span class="text-danger">*</span></label>
            <textarea class="form-control" id="alamat_perusahaan" name="alamat_perusahaan" rows="3" placeholder="Alamat jalan, kota, atau lokasi cabang" required style="border-radius: 8px;"></textarea>
          </div>
          <div class="form-group mb-0">
            <label for="penanggung_jawab" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Penanggung Jawab / HRD / Kontak <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab" placeholder="Nama PIC atau nomor kontak" required style="border-radius: 8px; height: 42px;">
          </div>
        </div>

        <div class="modal-footer bg-light py-3 px-4">
          <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
          <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
            <i class="fas fa-save mr-1"></i> Simpan Mitra
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL EDIT PERUSAHAAN MITRA                                               -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalEditPerusahaan" tabindex="-1" role="dialog" aria-labelledby="modalEditPerusahaanLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form id="formEditPerusahaan" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
        <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #1d72fe 0%, #0b1f3a 100%);">
          <h5 class="modal-title font-weight-bold" id="modalEditPerusahaanLabel">
            <i class="fas fa-edit mr-2"></i> Edit Data Perusahaan
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body p-4">
          <div class="form-group mb-3">
            <label for="edit_nama_perusahaan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Nama Perusahaan <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_nama_perusahaan" name="nama_perusahaan" required style="border-radius: 8px; height: 42px;">
          </div>
          <div class="form-group mb-3">
            <label for="edit_alamat_perusahaan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Alamat Perusahaan <span class="text-danger">*</span></label>
            <textarea class="form-control" id="edit_alamat_perusahaan" name="alamat_perusahaan" rows="3" required style="border-radius: 8px;"></textarea>
          </div>
          <div class="form-group mb-0">
            <label for="edit_penanggung_jawab" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Penanggung Jawab / HRD <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="edit_penanggung_jawab" name="penanggung_jawab" required style="border-radius: 8px; height: 42px;">
          </div>
        </div>

        <div class="modal-footer bg-light py-3 px-4">
          <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
          <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
            <i class="fas fa-save mr-1"></i> Simpan Perubahan
          </button>
        </div>
      </div>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
  // Inisialisasi Select2 di dalam modal saat modal ditampilkan
  $('.modal-kelola-pkl').on('shown.bs.modal', function () {
    $(this).find('.select2-modal').select2({
      theme: 'bootstrap4',
      width: '100%',
      dropdownParent: $(this)
    });
  });

  // Tombol Edit Perusahaan
  $('.btn-edit-perusahaan').on('click', function () {
    let id = $(this).data('id');
    let nama = $(this).data('nama');
    let alamat = $(this).data('alamat');
    let pj = $(this).data('pj');

    $('#edit_nama_perusahaan').val(nama);
    $('#edit_alamat_perusahaan').val(alamat);
    $('#edit_penanggung_jawab').val(pj);

    $('#formEditPerusahaan').attr('action', '/admin/perusahaan/' + id);
  });

  // Delete Perusahaan with SweetAlert
  $('.btn-delete-swal').click(function (e) {
    e.preventDefault();
    let actionUrl = $(this).data('action');
    let namaPerusahaan = $(this).data('nama') || 'perusahaan ini';

    Swal.fire({
      title: 'Hapus Mitra Perusahaan?',
      html: `Yakin ingin menghapus <b>${namaPerusahaan}</b>?<br><small class="text-danger">Seluruh data riwayat siswa PKL di perusahaan ini juga akan terhapus.</small>`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: '<i class="fas fa-trash mr-1"></i> Ya, Hapus!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        let form = $('<form>', { method: 'POST', action: actionUrl });
        form.append($('<input>', { type: 'hidden', name: '_token', value: '{{ csrf_token() }}' }));
        form.append($('<input>', { type: 'hidden', name: '_method', value: 'DELETE' }));
        $('body').append(form);
        form.submit();
      }
    });
  });

  // Delete Siswa PKL from Modal with SweetAlert
  $('.btn-delete-siswa-pkl').click(function (e) {
    e.preventDefault();
    let form = $(this).closest('form');
    let namaSiswa = $(this).data('nama') || 'siswa ini';

    Swal.fire({
      title: 'Keluarkan Siswa dari PKL?',
      html: `Hapus penempatan <b>${namaSiswa}</b> dari perusahaan ini?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, Keluarkan',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });
});
</script>
@endpush
