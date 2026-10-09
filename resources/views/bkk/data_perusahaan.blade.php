@extends($layout)
@section('content')
<style>
  @media (max-width: 768px) {
    .alamat-clamp {
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
      max-width: 140px;
      font-size: 0.78rem !important;
      line-height: 1.35 !important;
    }
  }
  @media (min-width: 769px) {
    .alamat-clamp {
      display: block;
      max-width: 260px;
    }
  }
</style>
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
                    <th style="min-width: 150px;">Alamat Industri</th>
                    <th style="min-width: 200px;">Penanggung Jawab & PIC</th>
                    <th class="text-center" style="min-width: 140px;">Siswa PKL</th>
                    <th class="text-center" style="min-width: 150px;">Aksi</th>
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
                      <div class="d-flex align-items-start" title="{{ $data->alamat_perusahaan }}" style="cursor: help;">
                        <i class="fas fa-map-marker-alt text-danger mr-1 mt-1 flex-shrink-0" style="font-size: 0.8rem;"></i>
                        <span class="alamat-clamp text-muted" style="font-size: 0.86rem; line-height: 1.35;">{{ $data->alamat_perusahaan }}</span>
                      </div>
                    </td>
                    <td>
                      <!-- Penanggung Jawab Sekolah (Guru Pembimbing) -->
                      <div class="mb-1" title="Penanggung Jawab Sekolah (Guru Pembimbing)">
                        <small class="text-muted d-block font-weight-bold" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.3px;">
                          <i class="fas fa-chalkboard-teacher text-primary mr-1"></i> Pembimbing Sekolah:
                        </small>
                        @if($data->guru)
                          <span class="font-weight-600 text-dark" style="font-size: 0.86rem;">
                            {{ $data->guru->nama_guru }}
                          </span>
                        @else
                          <span class="badge badge-light border text-muted font-weight-normal py-1 px-2" style="font-size: 0.75rem;">
                            Belum ditentukan
                          </span>
                        @endif
                      </div>

                      <!-- Penanggung Jawab Industri (DU/DI) -->
                      <div class="mb-1" title="Penanggung Jawab Industri / DU/DI">
                        <small class="text-muted d-block font-weight-bold" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.3px;">
                          <i class="fas fa-building text-secondary mr-1"></i> PJ Industri / HRD:
                        </small>
                        <span class="font-weight-600 text-dark" style="font-size: 0.86rem;">{{ $data->penanggung_jawab }}</span>
                      </div>

                      <!-- PIC & Kontak Lapangan Industri -->
                      @if(!empty($data->pic) || !empty($data->kontak_pic))
                        <div class="mt-1 d-flex flex-wrap align-items-center" style="gap: 4px; font-size: 0.76rem;">
                          @if(!empty($data->pic))
                            <span class="badge badge-light border text-dark font-weight-normal py-1 px-2 text-truncate" style="max-width: 150px;" title="PIC Lapangan: {{ $data->pic }}">
                              <i class="fas fa-id-badge text-primary mr-1"></i>{{ $data->pic }}
                            </span>
                          @endif
                          @if(!empty($data->kontak_pic))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $data->kontak_pic) }}" target="_blank" class="badge badge-success font-weight-normal py-1 px-2 shadow-sm text-white" title="Hubungi WA PIC: {{ $data->kontak_pic }}" style="border-radius: 6px;">
                              <i class="fab fa-whatsapp mr-1"></i>{{ $data->kontak_pic }}
                            </a>
                          @endif
                        </div>
                      @endif
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
                        <!-- Tombol Detail Lengkap Perusahaan -->
                        <button 
                          type="button" 
                          class="btn btn-sm btn-info text-white px-2"
                          data-toggle="modal" 
                          data-target="#modalDetailPerusahaan_{{ $data->id_perusahaan }}"
                          title="Lihat Detail Lengkap Perusahaan"
                          style="border-radius: 6px 0 0 6px;"
                        >
                          <i class="fas fa-eye"></i>
                        </button>

                        <!-- Tombol Kelola / Plotting Siswa PKL (Hanya Icon People) -->
                        <button 
                          type="button" 
                          class="btn btn-sm btn-primary px-2"
                          data-toggle="modal" 
                          data-target="#modalKelolaPkl_{{ $data->id_perusahaan }}"
                          title="Plotting Siswa PKL"
                        >
                          <i class="fas fa-users"></i>
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
                          data-id-guru="{{ $data->id_guru ?? '' }}"
                          data-pic="{{ $data->pic ?? '' }}"
                          data-kontak-pic="{{ $data->kontak_pic ?? '' }}"
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
<!-- ========================================================================= -->
<!-- MODAL DETAIL LENGKAP PERUSAHAAN MITRA                                      -->
<!-- ========================================================================= -->
<div class="modal fade" id="modalDetailPerusahaan_{{ $data->id_perusahaan }}" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel_{{ $data->id_perusahaan }}" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
      
      <!-- Modal Header -->
      <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #1d72fe 0%, #0b1f3a 100%);">
        <div>
          <h5 class="modal-title font-weight-bold mb-0" id="modalDetailLabel_{{ $data->id_perusahaan }}">
            <i class="fas fa-building mr-2"></i> Detail Lengkap Mitra Perusahaan
          </h5>
          <small class="text-white-50 d-block mt-1">
            Informasi lengkap profil kemitraan, pembimbing sekolah, penanggung jawab industri, dan siswa PKL
          </small>
        </div>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <!-- Modal Body -->
      <div class="modal-body p-4" style="background-color: #f8fafc;">
        
        <!-- Header Info Card: Profil & Alamat -->
        <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
          <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center pb-3 border-bottom mb-3" style="gap: 10px;">
              <div>
                <span class="badge badge-primary px-2 py-1 font-weight-bold mb-1" style="font-size: 0.75rem;">
                  <i class="fas fa-handshake mr-1"></i> MITRA INDUSTRI / DU/DI
                </span>
                <h4 class="font-weight-bold text-dark mb-0" style="line-height: 1.2;">{{ $data->nama_perusahaan }}</h4>
              </div>
              <div class="d-flex align-items-center" style="gap: 6px;">
                <button type="button" class="btn btn-sm btn-outline-warning font-weight-bold btn-edit-perusahaan btn-switch-modal" 
                  data-target="#modalEditPerusahaan"
                  data-id="{{ $data->id_perusahaan }}"
                  data-nama="{{ $data->nama_perusahaan }}"
                  data-alamat="{{ $data->alamat_perusahaan }}"
                  data-pj="{{ $data->penanggung_jawab }}"
                  data-id-guru="{{ $data->id_guru ?? '' }}"
                  data-pic="{{ $data->pic ?? '' }}"
                  data-kontak-pic="{{ $data->kontak_pic ?? '' }}"
                  style="border-radius: 8px;">
                  <i class="fas fa-edit mr-1"></i> Edit Data
                </button>
                <button type="button" class="btn btn-sm btn-primary font-weight-bold btn-switch-modal" 
                  data-target="#modalKelolaPkl_{{ $data->id_perusahaan }}"
                  style="border-radius: 8px;">
                  <i class="fas fa-users mr-1"></i> Plotting Siswa
                </button>
              </div>
            </div>

            <!-- Alamat Lengkap -->
            <div class="row align-items-center">
              <div class="col-md-9 mb-2 mb-md-0">
                <div class="text-muted font-weight-bold mb-1" style="font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.3px;">
                  <i class="fas fa-map-marker-alt text-danger mr-1"></i> Alamat Lengkap Industri:
                </div>
                <div class="text-dark" style="font-size: 0.92rem; line-height: 1.5; white-space: pre-line;">{{ $data->alamat_perusahaan }}</div>
              </div>
              <div class="col-md-3 text-md-right">
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($data->nama_perusahaan . ' ' . $data->alamat_perusahaan) }}" target="_blank" class="btn btn-outline-secondary btn-sm font-weight-bold" style="border-radius: 8px;" title="Cari lokasi di Google Maps">
                  <i class="fas fa-external-link-alt mr-1"></i> Google Maps
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- Dua Kolom: Pihak Sekolah vs Pihak DU/DI -->
        <div class="row mb-3">
          <!-- Kolom 1: Pihak Sekolah (Internal) -->
          <div class="col-md-6 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
              <div class="card-header bg-white py-2 px-3 border-0 d-flex align-items-center">
                <span class="badge bg-primary text-white p-2 mr-2" style="border-radius: 8px;">
                  <i class="fas fa-chalkboard-teacher"></i>
                </span>
                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">Pihak Sekolah (Internal)</span>
              </div>
              <div class="card-body p-3 pt-0">
                <div class="mb-2">
                  <small class="text-muted d-block font-weight-bold" style="font-size: 0.75rem;">GURU PEMBIMBING PKL</small>
                  @if($data->guru)
                    <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                      {{ $data->guru->nama_guru }}
                    </div>
                    @if(!empty($data->guru->no_hp))
                      <div class="mt-1">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $data->guru->no_hp) }}" target="_blank" class="badge badge-success px-2 py-1 font-weight-normal shadow-sm" style="border-radius: 6px;">
                          <i class="fab fa-whatsapp mr-1"></i> {{ $data->guru->no_hp }}
                        </a>
                      </div>
                    @endif
                  @else
                    <span class="badge badge-light border text-muted py-1 px-2 font-weight-normal mt-1" style="font-size: 0.8rem;">
                      Belum Ditentukan
                    </span>
                  @endif
                </div>
                <div class="text-muted" style="font-size: 0.8rem;">
                  Bertanggung jawab melakukan monitoring, bimbingan berkala, dan evaluasi capaian siswa magang di mitra ini.
                </div>
              </div>
            </div>
          </div>

          <!-- Kolom 2: Pihak Industri (Eksternal DU/DI) -->
          <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 12px;">
              <div class="card-header bg-white py-2 px-3 border-0 d-flex align-items-center">
                <span class="badge bg-info text-white p-2 mr-2" style="border-radius: 8px;">
                  <i class="fas fa-building"></i>
                </span>
                <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">Pihak Industri (Mitra DU/DI)</span>
              </div>
              <div class="card-body p-3 pt-0">
                <div class="mb-2">
                  <small class="text-muted d-block font-weight-bold" style="font-size: 0.75rem;">PENANGGUNG JAWAB / PIMPINAN DU/DI</small>
                  <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                    {{ $data->penanggung_jawab }}
                  </div>
                </div>
                <div class="mb-2">
                  <small class="text-muted d-block font-weight-bold" style="font-size: 0.75rem;">PIC LAPANGAN / PEMBIMBING DU/DI</small>
                  <div class="font-weight-bold text-dark" style="font-size: 0.95rem;">
                    {{ $data->pic ?: '-' }}
                  </div>
                </div>
                <div>
                  <small class="text-muted d-block font-weight-bold" style="font-size: 0.75rem;">KONTAK WHATSAPP PIC</small>
                  @if(!empty($data->kontak_pic))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $data->kontak_pic) }}" target="_blank" class="btn btn-success btn-sm font-weight-bold px-3 py-1 mt-1 shadow-sm" style="border-radius: 6px;">
                      <i class="fab fa-whatsapp mr-1"></i> Hubungi WA ({{ $data->kontak_pic }})
                    </a>
                  @else
                    <span class="text-muted font-italic" style="font-size: 0.85rem;">Belum ada kontak terdaftar</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Card 3: Rekap Data Siswa PKL -->
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
          <div class="card-header bg-white py-3 px-3 border-0 d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center" style="gap: 8px;">
            <div class="d-flex align-items-center">
              <span class="badge bg-success text-white p-2 mr-2" style="border-radius: 8px;">
                <i class="fas fa-user-graduate"></i>
              </span>
              <div>
                <h6 class="font-weight-bold text-dark mb-0">Daftar Siswa Magang PKL ({{ $data->siswaPkl->count() }})</h6>
                <small class="text-muted">Data siswa yang pernah dan sedang ditempatkan di mitra ini</small>
              </div>
            </div>
            <div class="d-flex flex-wrap align-items-center" style="gap: 4px;">
              <span class="badge badge-success px-2 py-1 font-weight-bold shadow-sm" style="border-radius: 6px;">
                <i class="fas fa-running mr-1"></i> {{ $data->siswa_aktif_count }} Aktif
              </span>
              <span class="badge text-white px-2 py-1 font-weight-bold shadow-sm" style="background-color: #0284c7; border-radius: 6px;">
                <i class="fas fa-calendar-check mr-1"></i> {{ $data->siswa_ditempatkan_count }} Ditempatkan
              </span>
              <span class="badge badge-secondary px-2 py-1 font-weight-bold" style="border-radius: 6px;">
                <i class="fas fa-check-circle mr-1"></i> {{ $data->siswaPkl->where('status', 'selesai')->count() }} Selesai
              </span>
            </div>
          </div>

          <div class="card-body p-0">
            @if($data->siswaPkl->isNotEmpty())
              <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0" style="font-size: 0.86rem;">
                  <thead class="bg-light">
                    <tr>
                      <th class="text-center" style="width: 10px;">No</th>
                      <th>Nama Siswa & NIS</th>
                      <th>Kelas</th>
                      <th>Periode Magang</th>
                      <th class="text-center">Status</th>
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
                        <i class="fas fa-calendar-alt text-primary mr-1"></i>
                        {{ \Carbon\Carbon::parse($pkl->tanggal_mulai)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($pkl->tanggal_selesai)->translatedFormat('d M Y') }}
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
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @else
              <div class="text-center py-4 px-3 text-muted">
                <i class="fas fa-user-graduate fa-2x mb-2 text-secondary d-block"></i>
                <div class="font-weight-bold">Belum Ada Siswa Ditempatkan</div>
                <small class="d-block mt-1">Gunakan tombol <b>"Plotting Siswa"</b> untuk menempatkan siswa magang di mitra industri ini.</small>
              </div>
            @endif
          </div>
        </div>

      </div>

      <!-- Modal Footer -->
      <div class="modal-footer bg-light py-2 px-4 d-flex justify-content-between">
        <span class="text-muted" style="font-size: 0.8rem;">
          <i class="fas fa-info-circle mr-1"></i> Terdaftar sejak: {{ $data->created_at ? $data->created_at->translatedFormat('d M Y') : '-' }}
        </span>
        <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Tutup</button>
      </div>

    </div>
  </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL KELOLA & PLOTTING SISWA PKL (PER PERUSAHAAN)                        -->
<!-- ========================================================================= -->
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
            <i class="fas fa-map-marker-alt mr-1"></i> {{ $data->alamat_perusahaan }}
            @if($data->guru)
              | <i class="fas fa-chalkboard-teacher mr-1"></i> Pembimbing Sekolah: {{ $data->guru->nama_guru }}
            @endif
            | <i class="fas fa-user-tie mr-1"></i> PJ DU/DI: {{ $data->penanggung_jawab }}
            @if(!empty($data->pic))
              | <i class="fas fa-id-badge mr-1"></i> PIC: {{ $data->pic }} {{ !empty($data->kontak_pic) ? '('.$data->kontak_pic.')' : '' }}
            @endif
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
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
          <div class="row">
            <!-- Kolom Kiri: Profil & Alamat Perusahaan -->
            <div class="col-lg-6">
              <div class="form-group mb-3">
                <label for="nama_perusahaan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Nama Perusahaan / Hotel / Industri <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="nama_perusahaan" name="nama_perusahaan" placeholder="Contoh: Hotel Grand Mercure / PT Telkom" required style="border-radius: 8px; height: 42px;">
              </div>
              <div class="form-group mb-3">
                <label for="alamat_perusahaan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Alamat Lengkap Perusahaan <span class="text-danger">*</span>
                </label>
                <textarea class="form-control" id="alamat_perusahaan" name="alamat_perusahaan" rows="5" placeholder="Masukkan alamat lengkap (jalan, nomor gedung, kelurahan, kecamatan, kota/kabupaten)..." required style="border-radius: 8px; min-height: 140px; resize: vertical; line-height: 1.5;"></textarea>
                <small class="text-muted d-block mt-1">
                  <i class="fas fa-arrows-alt-v mr-1"></i> Area input dapat ditarik ke bawah jika membutuhkan ruang lebih lebar.
                </small>
              </div>
            </div>

            <!-- Kolom Kanan: Penanggung Jawab & Pembimbing -->
            <div class="col-lg-6">
              <!-- Penanggung Jawab dari Sekolah -->
              <div class="form-group mb-3">
                <label for="id_guru" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  <i class="fas fa-chalkboard-teacher text-primary mr-1"></i> Penanggung Jawab Sekolah (Guru Pembimbing)
                </label>
                <select class="form-control select2-modal" id="id_guru" name="id_guru" style="width: 100%;">
                  <option value="">-- Pilih dari Data Guru (Opsional) --</option>
                  @foreach ($guruList as $guru)
                    <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru }}</option>
                  @endforeach
                </select>
                <small class="text-muted d-block mt-1">
                  Guru pembimbing PKL yang ditugaskan dari pihak sekolah untuk mitra ini.
                </small>
              </div>

              <!-- Penanggung Jawab dari Industri -->
              <div class="form-group mb-3">
                <label for="penanggung_jawab" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  <i class="fas fa-building text-secondary mr-1"></i> Penanggung Jawab Industri (Pimpinan DU/DI / HRD) <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="penanggung_jawab" name="penanggung_jawab" placeholder="Contoh: Bpk. Kurniawan / HRD Manager" required style="border-radius: 8px; height: 42px;">
              </div>

              <!-- PIC & Kontak Lapangan Industri -->
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group mb-2">
                    <label for="pic" class="font-weight-bold text-dark" style="font-size: 0.82rem;">
                      Nama PIC Lapangan DU/DI
                    </label>
                    <input type="text" class="form-control" id="pic" name="pic" placeholder="Contoh: Ibu Rina" style="border-radius: 8px; height: 40px; font-size: 0.85rem;">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group mb-2">
                    <label for="kontak_pic" class="font-weight-bold text-dark" style="font-size: 0.82rem;">
                      No. WA PIC Lapangan
                    </label>
                    <input type="text" class="form-control" id="kontak_pic" name="kontak_pic" placeholder="Contoh: 081234567890" style="border-radius: 8px; height: 40px; font-size: 0.85rem;">
                  </div>
                </div>
              </div>
            </div>
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
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
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
          <div class="row">
            <!-- Kolom Kiri: Profil & Alamat Perusahaan -->
            <div class="col-lg-6">
              <div class="form-group mb-3">
                <label for="edit_nama_perusahaan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Nama Perusahaan / Hotel / Industri <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="edit_nama_perusahaan" name="nama_perusahaan" required style="border-radius: 8px; height: 42px;">
              </div>
              <div class="form-group mb-3">
                <label for="edit_alamat_perusahaan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Alamat Lengkap Perusahaan <span class="text-danger">*</span>
                </label>
                <textarea class="form-control" id="edit_alamat_perusahaan" name="alamat_perusahaan" rows="5" required style="border-radius: 8px; min-height: 140px; resize: vertical; line-height: 1.5;"></textarea>
                <small class="text-muted d-block mt-1">
                  <i class="fas fa-arrows-alt-v mr-1"></i> Area input dapat ditarik ke bawah jika membutuhkan ruang lebih lebar.
                </small>
              </div>
            </div>

            <!-- Kolom Kanan: Penanggung Jawab & Pembimbing -->
            <div class="col-lg-6">
              <!-- Penanggung Jawab dari Sekolah -->
              <div class="form-group mb-3">
                <label for="edit_id_guru" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  <i class="fas fa-chalkboard-teacher text-primary mr-1"></i> Penanggung Jawab Sekolah (Guru Pembimbing)
                </label>
                <select class="form-control select2-modal" id="edit_id_guru" name="id_guru" style="width: 100%;">
                  <option value="">-- Pilih dari Data Guru (Opsional) --</option>
                  @foreach ($guruList as $guru)
                    <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru }}</option>
                  @endforeach
                </select>
                <small class="text-muted d-block mt-1">
                  Guru pembimbing PKL yang ditugaskan dari pihak sekolah untuk mitra ini.
                </small>
              </div>

              <!-- Penanggung Jawab dari Industri -->
              <div class="form-group mb-3">
                <label for="edit_penanggung_jawab" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  <i class="fas fa-building text-secondary mr-1"></i> Penanggung Jawab Industri (Pimpinan DU/DI / HRD) <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control" id="edit_penanggung_jawab" name="penanggung_jawab" required style="border-radius: 8px; height: 42px;">
              </div>

              <!-- PIC & Kontak Lapangan Industri -->
              <div class="row">
                <div class="col-sm-6">
                  <div class="form-group mb-2">
                    <label for="edit_pic" class="font-weight-bold text-dark" style="font-size: 0.82rem;">
                      Nama PIC Lapangan DU/DI
                    </label>
                    <input type="text" class="form-control" id="edit_pic" name="pic" placeholder="Contoh: Ibu Rina" style="border-radius: 8px; height: 40px; font-size: 0.85rem;">
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group mb-2">
                    <label for="edit_kontak_pic" class="font-weight-bold text-dark" style="font-size: 0.82rem;">
                      No. WA PIC Lapangan
                    </label>
                    <input type="text" class="form-control" id="edit_kontak_pic" name="kontak_pic" placeholder="Contoh: 081234567890" style="border-radius: 8px; height: 40px; font-size: 0.85rem;">
                  </div>
                </div>
              </div>
            </div>
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
  $('#modalTambahPerusahaan, #modalEditPerusahaan, .modal-kelola-pkl').on('shown.bs.modal', function () {
    $(this).find('.select2-modal').select2({
      theme: 'bootstrap4',
      width: '100%',
      dropdownParent: $(this)
    });
  });

  // Reset Select2 saat modal tambah dibuka
  $('#modalTambahPerusahaan').on('show.bs.modal', function () {
    $('#id_guru').val('').trigger('change');
  });

  // Smooth modal switch (misal dari Detail ke Edit atau Plotting)
  $(document).on('click', '.btn-switch-modal', function (e) {
    e.preventDefault();
    let targetModalId = $(this).data('target');
    let currentModal = $(this).closest('.modal');
    currentModal.modal('hide');
    currentModal.one('hidden.bs.modal', function () {
      $(targetModalId).modal('show');
    });
  });

  // Tombol Edit Perusahaan
  $('.btn-edit-perusahaan').on('click', function () {
    let id = $(this).data('id');
    let nama = $(this).data('nama');
    let alamat = $(this).data('alamat');
    let pj = $(this).data('pj');
    let idGuru = $(this).data('id-guru') || '';
    let pic = $(this).data('pic') || '';
    let kontakPic = $(this).data('kontak-pic') || '';

    $('#edit_nama_perusahaan').val(nama);
    $('#edit_alamat_perusahaan').val(alamat);
    $('#edit_penanggung_jawab').val(pj);
    $('#edit_id_guru').val(idGuru).trigger('change');
    $('#edit_pic').val(pic);
    $('#edit_kontak_pic').val(kontakPic);

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
