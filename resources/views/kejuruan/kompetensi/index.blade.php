@extends($layout)

@section('content')
<!-- Content Header -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <i class="fas fa-layer-group text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
        <div class="d-flex flex-column justify-content-center">
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Capaian & Alur Tujuan Pembelajaran (CP / ATP)</h1>
          <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Standar Elemen Capaian Pembelajaran (CP) dan Alur Tujuan Pembelajaran (ATP / TP) Kurikulum Merdeka Kejuruan</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-primary font-weight-500">Dashboard</a></li>
          <li class="breadcrumb-item"><a href="/admin/kompetensi" class="text-primary font-weight-500">Kejuruan</a></li>
          <li class="breadcrumb-item active">CP & ATP</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="border-radius: 10px;">
        <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-3" role="alert" style="border-radius: 10px;">
        <i class="fas fa-exclamation-circle mr-2"></i> <strong>Perhatian:</strong>
        <ul class="mb-0 mt-1 pl-3">
          @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
          @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    @php
      $countCp = $capaianPembelajarans->count();
      $countTp = $kompetensis->count();
      $countFaseE = $capaianPembelajarans->where('fase', 'E')->count();
      $countFaseF = $capaianPembelajarans->where('fase', 'F')->count();
      $activeTab = request('tab', 'struktur');
    @endphp

    <!-- Summary Stat Cards -->
    <div class="row mb-3">
      <!-- Total Elemen CP -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #0284c7 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Master Elemen CP</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countCp }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Elemen</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-award text-primary mr-1"></i> Standar Capaian</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f0f9ff; color: #0284c7; flex-shrink: 0;">
              <i class="fas fa-award"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Total Alur TP -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Tujuan Pembelajaran (TP)</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countTp }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Butir ATP</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-tasks text-success mr-1"></i> Modul Praktik & Job Sheet</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #ecfdf5; color: #10b981; flex-shrink: 0;">
              <i class="fas fa-tasks"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Fase E (Kelas X) -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #16a34a !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Fase E (Kelas X)</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countFaseE }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Elemen CP</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-book-open text-success mr-1"></i> Dasar Program Kejuruan</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #dcfce7; color: #16a34a; flex-shrink: 0;">
              <i class="fas fa-book-open"></i>
            </div>
          </div>
        </div>
      </div>

      <!-- Fase F (Kelas XI & XII) -->
      <div class="col-lg-3 col-6 mb-3 mb-lg-0">
        <div class="card border-0 shadow-sm h-100 bg-white" style="border-radius: 12px; border-left: 4px solid #8b5cf6 !important;">
          <div class="card-body p-3 d-flex align-items-center justify-content-between">
            <div>
              <div class="text-muted text-uppercase font-weight-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">Fase F (Kelas XI - XII)</div>
              <div class="font-weight-bold text-dark mt-1" style="font-size: 1.7rem; line-height: 1.1;">{{ $countFaseF }} <span style="font-size: 0.85rem; font-weight: 500; color: #64748b;">Elemen CP</span></div>
              <small class="text-muted mt-1 d-block"><i class="fas fa-user-graduate text-purple mr-1"></i> Konsentrasi & UKK</small>
            </div>
            <div class="rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 46px; height: 46px; font-size: 1.25rem; background: #f5f3ff; color: #8b5cf6; flex-shrink: 0;">
              <i class="fas fa-user-graduate"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Navigation Tabs & Actions Header -->
    <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
      <div class="card-body p-2 p-md-3 d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
        <!-- Tabs -->
        <ul class="nav nav-pills" id="cpAtpTabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link font-weight-bold {{ $activeTab === 'struktur' ? 'active' : '' }}" id="tab-struktur-link" data-toggle="pill" href="#tab-struktur" role="tab" style="border-radius: 8px;">
              <i class="fas fa-sitemap mr-1"></i> Alur CP & ATP (Hierarki)
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold {{ $activeTab === 'cp' ? 'active' : '' }}" id="tab-cp-link" data-toggle="pill" href="#tab-cp" role="tab" style="border-radius: 8px;">
              <i class="fas fa-award mr-1"></i> Master Elemen CP ({{ $countCp }})
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link font-weight-bold {{ $activeTab === 'tp' ? 'active' : '' }}" id="tab-tp-link" data-toggle="pill" href="#tab-tp" role="tab" style="border-radius: 8px;">
              <i class="fas fa-list-ul mr-1"></i> Semua Tujuan Pembelajaran ({{ $countTp }})
            </a>
          </li>
        </ul>

        <!-- Action Buttons -->
        <div class="d-flex align-items-center flex-wrap" style="gap: 8px;">
          <button type="button" class="btn btn-outline-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalTambahCp" style="border-radius: 8px;">
            <i class="fas fa-plus mr-1"></i> Tambah Elemen CP
          </button>
          <a href="/admin/kompetensi/create" class="btn btn-primary btn-sm font-weight-bold shadow-sm" style="border-radius: 8px;">
            <i class="fas fa-plus mr-1"></i> Tambah Tujuan Pembelajaran (TP)
          </a>
        </div>
      </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="cpAtpTabsContent">

      <!-- =================================================================== -->
      <!-- TAB 1: STRUKTUR HIERARKI CP & ATP ACCORDION VIEW                   -->
      <!-- =================================================================== -->
      <div class="tab-pane fade {{ $activeTab === 'struktur' ? 'show active' : '' }}" id="tab-struktur" role="tabpanel">
        
        @if($capaianPembelajarans->isEmpty())
          <div class="card border-0 shadow-sm text-center py-5" style="border-radius: 12px;">
            <div class="card-body">
              <i class="fas fa-folder-open text-muted mb-3" style="font-size: 3rem;"></i>
              <h5 class="text-dark font-weight-bold">Belum Ada Elemen Capaian Pembelajaran (CP)</h5>
              <p class="text-muted small max-w-lg mx-auto" style="max-width: 500px;">
                Mulailah dengan membuat Elemen Capaian Pembelajaran (CP) sesuai dokumen Kepmen/BSKAP Kurikulum Merdeka. Setelah itu, susun Alur Tujuan Pembelajaran (ATP) di bawahnya.
              </p>
              <button type="button" class="btn btn-primary px-4 font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalTambahCp" style="border-radius: 8px;">
                <i class="fas fa-plus mr-1"></i> Buat Elemen CP Pertama
              </button>
            </div>
          </div>
        @else
          <div class="accordion" id="accordionCp">
            @foreach($capaianPembelajarans as $cp)
              @php
                $childTps = $cp->tujuanPembelajarans;
                $faseBadgeClass = $cp->fase === 'E' ? 'badge-success' : 'badge-warning text-dark';
              @endphp
              <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; overflow: hidden;">
                <!-- Header Accordion -->
                <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap" id="heading-cp-{{ $cp->id_cp }}" style="gap: 12px;">
                  <div class="d-flex align-items-center flex-grow-1" style="gap: 12px; cursor: pointer;" data-toggle="collapse" data-target="#collapse-cp-{{ $cp->id_cp }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                    <span class="badge {{ $faseBadgeClass }} px-2 py-1 font-weight-bold" style="font-size: 0.82rem;">
                      {{ $cp->fase_label }}
                    </span>
                    <span class="badge badge-light border text-primary font-weight-bold px-2 py-1" style="font-size: 0.82rem;">
                      {{ $cp->kode_elemen }}
                    </span>
                    <div>
                      <h5 class="mb-0 font-weight-bold text-dark" style="font-size: 1.05rem;">
                        {{ $cp->nama_elemen }}
                      </h5>
                      <div class="text-muted small mt-1">
                        <span class="mr-3"><i class="fas fa-graduation-cap mr-1 text-secondary"></i> {{ $cp->jurusan->nama_jurusan ?? '-' }}</span>
                        <span class="mr-3"><i class="fas fa-tasks mr-1 text-success"></i> <strong>{{ $childTps->count() }}</strong> TP dalam Alur</span>
                        @if($cp->total_jp > 0)
                          <span><i class="fas fa-clock mr-1 text-info"></i> <strong>{{ $cp->total_jp }}</strong> JP</span>
                        @endif
                      </div>
                    </div>
                  </div>

                  <!-- Quick Actions on Header -->
                  <div class="d-flex align-items-center" style="gap: 6px;">
                    <a href="/admin/kompetensi/create?cp_id={{ $cp->id_cp }}" class="btn btn-sm btn-outline-success font-weight-bold" title="Tambah TP ke Elemen Ini" style="border-radius: 6px;">
                      <i class="fas fa-plus mr-1"></i> Tambah TP
                    </a>
                    <button type="button" class="btn btn-sm btn-outline-primary btn-edit-cp" 
                            data-id="{{ $cp->id_cp }}"
                            data-jurusan="{{ $cp->id_jurusan }}"
                            data-kode="{{ $cp->kode_elemen }}"
                            data-nama="{{ $cp->nama_elemen }}"
                            data-fase="{{ $cp->fase }}"
                            data-deskripsi="{{ $cp->deskripsi_cp }}"
                            title="Edit Elemen CP" style="border-radius: 6px;">
                      <i class="fas fa-edit"></i>
                    </button>
                    <form action="/admin/kompetensi/cp/{{ $cp->id_cp }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Elemen CP ini? TP di dalamnya akan tetap tersimpan.');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Elemen CP" style="border-radius: 6px;">
                        <i class="fas fa-trash-alt"></i>
                      </button>
                    </form>
                    <button class="btn btn-sm btn-light border text-muted ml-2" type="button" data-toggle="collapse" data-target="#collapse-cp-{{ $cp->id_cp }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}">
                      <i class="fas fa-chevron-down"></i>
                    </button>
                  </div>
                </div>

                <!-- Collapse Body -->
                <div id="collapse-cp-{{ $cp->id_cp }}" class="collapse {{ $loop->first ? 'show' : '' }}" data-parent="#accordionCp">
                  <div class="card-body pt-0 px-3 pb-3">
                    <!-- Narasi Elemen CP -->
                    @if($cp->deskripsi_cp)
                      <div class="p-3 mb-3 rounded bg-light border" style="font-size: 0.88rem; line-height: 1.5; color: #334155; border-left: 4px solid #0284c7 !important;">
                        <div class="font-weight-bold text-dark mb-1" style="font-size: 0.82rem; text-transform: uppercase; letter-spacing: 0.5px;">
                          <i class="fas fa-align-left text-primary mr-1"></i> Deskripsi Capaian Pembelajaran:
                        </div>
                        {{ $cp->deskripsi_cp }}
                      </div>
                    @endif

                    <!-- Sub-tabel Alur TP (ATP) -->
                    @if($childTps->isEmpty())
                      <div class="text-center py-4 text-muted bg-white border rounded" style="border-style: dashed !important;">
                        <i class="fas fa-info-circle mr-1"></i> Belum ada Alur Tujuan Pembelajaran (ATP) pada elemen ini.
                        <div class="mt-2">
                          <a href="/admin/kompetensi/create?cp_id={{ $cp->id_cp }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus mr-1"></i> Tambahkan Alur TP Sekarang
                          </a>
                        </div>
                      </div>
                    @else
                      <div class="table-responsive bg-white border rounded" style="overflow: hidden;">
                        <table class="table table-hover align-middle mb-0" style="font-size: 0.86rem;">
                          <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.73rem; letter-spacing: 0.5px;">
                            <tr>
                              <th style="width: 70px" class="text-center">Alur #</th>
                              <th style="width: 120px">Kode TP</th>
                              <th>Rumusan Tujuan Pembelajaran (TP)</th>
                              <th style="width: 200px">Materi Pokok / Lingkup</th>
                              <th style="width: 80px" class="text-center">Alokasi</th>
                              <th style="width: 90px" class="text-center">Kelas/Sem</th>
                              <th style="width: 80px" class="text-center">KKTP</th>
                              <th style="width: 90px" class="text-center">Aksi</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach($childTps as $tp)
                              <tr>
                                <td class="text-center align-middle font-weight-bold">
                                  <span class="badge badge-secondary px-2 py-1" style="font-size: 0.78rem;">
                                    {{ $tp->urutan_atp ?? $loop->iteration }}
                                  </span>
                                </td>
                                <td class="align-middle font-weight-bold text-primary font-monospace">
                                  {{ $tp->kode_kompetensi }}
                                </td>
                                <td class="align-middle font-weight-600 text-dark">
                                  {{ $tp->nama_kompetensi }}
                                </td>
                                <td class="align-middle text-muted small">
                                  {{ $tp->materi_pokok ?? '-' }}
                                </td>
                                <td class="text-center align-middle">
                                  @if($tp->alokasi_jp)
                                    <span class="badge badge-light border text-info font-weight-bold px-2 py-1">
                                      {{ $tp->alokasi_jp }} JP
                                    </span>
                                  @else
                                    <span class="text-muted">-</span>
                                  @endif
                                </td>
                                <td class="text-center align-middle">
                                  <span class="badge badge-light border px-2 py-1">
                                    {{ $tp->tingkat }} / S{{ $tp->semester }}
                                  </span>
                                </td>
                                <td class="text-center align-middle font-weight-bold text-primary">
                                  {{ floatval($tp->kkm ?? 75) }}
                                </td>
                                <td class="text-center align-middle">
                                  <div class="btn-group btn-group-sm" role="group">
                                    <a href="/admin/kompetensi/{{ $tp->id_kompetensi }}/edit" class="btn btn-outline-primary" title="Ubah TP">
                                      <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="/admin/kompetensi/{{ $tp->id_kompetensi }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Tujuan Pembelajaran (TP) ini?');">
                                      @csrf
                                      @method('DELETE')
                                      <button type="submit" class="btn btn-outline-danger" title="Hapus TP">
                                        <i class="fas fa-trash-alt"></i>
                                      </button>
                                    </form>
                                  </div>
                                </td>
                              </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    @endif

                  </div>
                </div>
              </div>
            @endforeach

            <!-- TP Belum Dikelompokkan (Mandiri / Unmapped) -->
            @php
              $unmappedTps = $kompetensis->whereNull('id_cp');
            @endphp
            @if($unmappedTps->isNotEmpty())
              <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px; overflow: hidden; border-left: 4px solid #f59e0b !important;">
                <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap" id="heading-unmapped">
                  <div class="d-flex align-items-center" style="gap: 12px; cursor: pointer;" data-toggle="collapse" data-target="#collapse-unmapped">
                    <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold">
                      TP Mandiri (Belum Dipetakan ke CP)
                    </span>
                    <span class="text-muted small">
                      Ada <strong>{{ $unmappedTps->count() }}</strong> TP yang belum dikaitkan ke Elemen CP induk.
                    </span>
                  </div>
                  <button class="btn btn-sm btn-light border text-muted" type="button" data-toggle="collapse" data-target="#collapse-unmapped">
                    <i class="fas fa-chevron-down"></i>
                  </button>
                </div>
                <div id="collapse-unmapped" class="collapse">
                  <div class="card-body pt-0 px-3 pb-3">
                    <div class="alert alert-light border small text-muted mb-3">
                      <i class="fas fa-info-circle text-warning mr-1"></i> Anda dapat mengedit TP berikut untuk menetapkan Elemen Capaian Pembelajaran (CP) induk dan nomor alur pembelajarannya (ATP).
                    </div>
                    <div class="table-responsive bg-white border rounded">
                      <table class="table table-hover align-middle mb-0" style="font-size: 0.86rem;">
                        <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.73rem;">
                          <tr>
                            <th style="width: 45px" class="text-center">No</th>
                            <th style="width: 130px">Kode TP</th>
                            <th>Rumusan Tujuan Pembelajaran</th>
                            <th style="width: 140px">Program Keahlian</th>
                            <th style="width: 90px" class="text-center">Kelas</th>
                            <th style="width: 90px" class="text-center">KKTP</th>
                            <th style="width: 110px" class="text-center">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($unmappedTps as $utp)
                            <tr>
                              <td class="text-center align-middle text-muted">{{ $loop->iteration }}</td>
                              <td class="align-middle font-weight-bold text-primary">{{ $utp->kode_kompetensi }}</td>
                              <td class="align-middle font-weight-600 text-dark">{{ $utp->nama_kompetensi }}</td>
                              <td class="align-middle">{{ $utp->jurusan->nama_singkat ?? '-' }}</td>
                              <td class="text-center align-middle">{{ $utp->tingkat }}</td>
                              <td class="text-center align-middle font-weight-bold text-primary">{{ floatval($utp->kkm ?? 75) }}</td>
                              <td class="text-center align-middle">
                                <a href="/admin/kompetensi/{{ $utp->id_kompetensi }}/edit" class="btn btn-sm btn-outline-primary" title="Tautkan ke Elemen CP">
                                  <i class="fas fa-link mr-1"></i> Tautkan
                                </a>
                              </td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
            @endif

          </div>
        @endif

      </div>

      <!-- =================================================================== -->
      <!-- TAB 2: KELOLA MASTER ELEMEN CAPAIAN PEMBELAJARAN (CP)               -->
      <!-- =================================================================== -->
      <div class="tab-pane fade {{ $activeTab === 'cp' ? 'show active' : '' }}" id="tab-cp" role="tabpanel">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
            <div>
              <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
                <i class="fas fa-award text-primary mr-2"></i> Master Elemen Capaian Pembelajaran (CP)
              </h3>
              <span class="badge badge-light border text-secondary ml-2 px-2 py-1" style="font-size: 0.75rem;">
                Total: {{ $countCp }} Elemen
              </span>
            </div>
            <button type="button" class="btn btn-primary btn-sm font-weight-bold shadow-sm" data-toggle="modal" data-target="#modalTambahCp" style="border-radius: 8px;">
              <i class="fas fa-plus mr-1"></i> Tambah Elemen CP Baru
            </button>
          </div>

          <div class="card-body p-0">
            <div class="table-responsive">
              <table class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                  <tr>
                    <th style="width: 45px" class="text-center">No</th>
                    <th style="width: 120px">Kode Elemen</th>
                    <th style="width: 220px">Nama Elemen CP</th>
                    <th>Deskripsi / Narasi Capaian</th>
                    <th style="width: 130px" class="text-center">Fase</th>
                    <th style="width: 140px" class="text-center">Program Keahlian</th>
                    <th style="width: 110px" class="text-center">Jumlah TP</th>
                    <th style="width: 110px" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($capaianPembelajarans as $cp)
                    <tr>
                      <td class="text-center align-middle font-weight-bold text-muted">{{ $loop->iteration }}</td>
                      <td class="align-middle">
                        <span class="badge badge-light border text-primary px-2 py-1 font-weight-bold" style="font-size: 0.82rem;">
                          {{ $cp->kode_elemen }}
                        </span>
                      </td>
                      <td class="align-middle font-weight-bold text-dark">
                        {{ $cp->nama_elemen }}
                      </td>
                      <td class="align-middle text-muted small" style="max-width: 320px;">
                        {{ $cp->deskripsi_cp ? \Illuminate\Support\Str::limit($cp->deskripsi_cp, 140) : '-' }}
                      </td>
                      <td class="text-center align-middle">
                        @if($cp->fase === 'E')
                          <span class="badge badge-success px-2 py-1">Fase E (Kelas X)</span>
                        @else
                          <span class="badge badge-warning text-dark px-2 py-1">Fase F (XI & XII)</span>
                        @endif
                      </td>
                      <td class="text-center align-middle">
                        <span class="badge badge-info px-2 py-1" title="{{ $cp->jurusan->nama_jurusan ?? '-' }}">
                          {{ $cp->jurusan->nama_singkat ?? '-' }}
                        </span>
                      </td>
                      <td class="text-center align-middle">
                        <span class="badge badge-light border px-2 py-1 font-weight-bold text-success">
                          {{ $cp->tujuanPembelajarans->count() }} TP
                        </span>
                      </td>
                      <td class="text-center align-middle">
                        <div class="btn-group btn-group-sm" role="group">
                          <button type="button" class="btn btn-outline-primary btn-edit-cp"
                                  data-id="{{ $cp->id_cp }}"
                                  data-jurusan="{{ $cp->id_jurusan }}"
                                  data-kode="{{ $cp->kode_elemen }}"
                                  data-nama="{{ $cp->nama_elemen }}"
                                  data-fase="{{ $cp->fase }}"
                                  data-deskripsi="{{ $cp->deskripsi_cp }}"
                                  title="Ubah Elemen CP">
                            <i class="fas fa-edit"></i>
                          </button>
                          <form action="/admin/kompetensi/cp/{{ $cp->id_cp }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Elemen CP ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" title="Hapus Elemen CP">
                              <i class="fas fa-trash-alt"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center py-4 text-muted">
                        <i class="fas fa-info-circle mr-1"></i> Belum ada data Elemen Capaian Pembelajaran.
                      </td>
                    </tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- =================================================================== -->
      <!-- TAB 3: TABEL SEMUA TUJUAN PEMBELAJARAN (FLAT VIEW)                  -->
      <!-- =================================================================== -->
      <div class="tab-pane fade {{ $activeTab === 'tp' ? 'show active' : '' }}" id="tab-tp" role="tabpanel">
        <div class="card border-0 shadow-sm" style="border-radius: 12px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap" style="gap: 12px;">
            <div>
              <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1rem;">
                <i class="fas fa-list-ul text-primary mr-2"></i> Daftar Seluruh Tujuan Pembelajaran (TP)
              </h3>
              <span class="badge badge-light border text-secondary ml-2 px-2 py-1" style="font-size: 0.75rem;">
                Total: {{ $countTp }} Butir TP
              </span>
            </div>
            <a href="/admin/kompetensi/create" class="btn btn-primary btn-sm font-weight-bold shadow-sm" style="border-radius: 8px;">
              <i class="fas fa-plus mr-1"></i> Tambah TP Baru
            </a>
          </div>

          <div class="card-body p-0">
            <div class="table-responsive">
              <table id="example2" class="table table-hover align-middle mb-0" style="font-size: 0.88rem;">
                <thead class="bg-light text-secondary text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                  <tr>
                    <th style="width: 45px" class="text-center">No</th>
                    <th style="width: 120px">Kode TP</th>
                    <th>Tujuan Pembelajaran</th>
                    <th style="width: 180px">Induk Elemen CP</th>
                    <th style="width: 120px" class="text-center">Program Keahlian</th>
                    <th style="width: 90px" class="text-center">Fase / Kelas</th>
                    <th style="width: 80px" class="text-center">Sem.</th>
                    <th style="width: 75px" class="text-center">JP</th>
                    <th style="width: 80px" class="text-center">KKTP</th>
                    <th style="width: 95px" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse ($kompetensis as $k)
                    <tr>
                      <td class="text-center align-middle font-weight-bold text-muted">{{ $loop->iteration }}</td>
                      <td class="align-middle">
                        <span class="badge badge-light border text-primary px-2 py-1 font-weight-bold" style="font-size: 0.82rem;">
                          {{ $k->kode_kompetensi }}
                        </span>
                      </td>
                      <td class="align-middle font-weight-600 text-dark">
                        {{ $k->nama_kompetensi }}
                        @if($k->materi_pokok)
                          <div class="text-muted small mt-1 font-weight-normal"><i class="fas fa-tag mr-1 text-secondary"></i> {{ $k->materi_pokok }}</div>
                        @endif
                      </td>
                      <td class="align-middle">
                        @if($k->capaianPembelajaran)
                          <span class="badge badge-light border text-dark px-2 py-1 font-weight-500" title="{{ $k->capaianPembelajaran->nama_elemen }}">
                            {{ $k->capaianPembelajaran->kode_elemen }}
                          </span>
                          <span class="small text-muted d-block mt-1">{{ \Illuminate\Support\Str::limit($k->capaianPembelajaran->nama_elemen, 22) }}</span>
                        @else
                          <span class="badge badge-warning text-dark px-2 py-1">Mandiri (Tanpa CP)</span>
                        @endif
                      </td>
                      <td class="text-center align-middle">
                        <span class="badge badge-info px-2 py-1 font-weight-bold" title="{{ $k->jurusan->nama_jurusan ?? '-' }}">
                          {{ $k->jurusan->nama_singkat ?? '-' }}
                        </span>
                      </td>
                      <td class="text-center align-middle">
                        @if(in_array($k->tingkat, ['X', '10']))
                          <span class="badge badge-success px-2 py-1">Fase E (X)</span>
                        @elseif(in_array($k->tingkat, ['XI', '11']))
                          <span class="badge badge-warning text-dark px-2 py-1">Fase F (XI)</span>
                        @elseif(in_array($k->tingkat, ['XII', '12']))
                          <span class="badge text-white px-2 py-1" style="background: #8b5cf6;">Fase F (XII)</span>
                        @else
                          <span class="badge badge-secondary px-2 py-1">{{ $k->tingkat }}</span>
                        @endif
                      </td>
                      <td class="text-center align-middle">
                        <span class="badge badge-light border px-2 py-1">S{{ $k->semester }}</span>
                      </td>
                      <td class="text-center align-middle font-weight-bold text-info">
                        {{ $k->alokasi_jp ? $k->alokasi_jp . ' JP' : '-' }}
                      </td>
                      <td class="text-center align-middle font-weight-bold text-primary">
                        {{ floatval($k->kkm ?? 75) }}
                      </td>
                      <td class="text-center align-middle">
                        <div class="btn-group btn-group-sm" role="group">
                          <a href="/admin/kompetensi/{{ $k->id_kompetensi }}/edit" class="btn btn-outline-primary" title="Ubah Data">
                            <i class="fas fa-edit"></i>
                          </a>
                          <form action="/admin/kompetensi/{{ $k->id_kompetensi }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus TP ini?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger" title="Hapus Data">
                              <i class="fas fa-trash-alt"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>
                  @empty
                    <tr>
                      <td colspan="10" class="text-center py-4 text-muted">
                        <i class="fas fa-info-circle mr-1"></i> Belum ada data Tujuan Pembelajaran (TP).
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

<!-- ======================================================================= -->
<!-- MODAL TAMBAH ELEMEN CAPAIAN PEMBELAJARAN (CP)                           -->
<!-- ======================================================================= -->
<div class="modal fade" id="modalTambahCp" tabindex="-1" role="dialog" aria-labelledby="modalTambahCpTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
      <div class="modal-header bg-primary text-white py-3">
        <h5 class="modal-title font-weight-bold" id="modalTambahCpTitle">
          <i class="fas fa-plus-circle mr-2"></i> Tambah Elemen Capaian Pembelajaran (CP)
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="/admin/kompetensi/cp" method="POST">
        @csrf
        <div class="modal-body p-4">
          <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label for="id_jurusan_cp" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                Program Keahlian / Jurusan <span class="text-danger">*</span>
              </label>
              <select name="id_jurusan" id="id_jurusan_cp" class="form-control" required style="border-radius: 8px;">
                <option value="">Pilih Program Keahlian</option>
                @foreach($jurusans as $j)
                  <option value="{{ $j->id_jurusan }}" {{ old('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                    {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 form-group mb-3">
              <label for="fase_cp" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                Fase Kurikulum Merdeka <span class="text-danger">*</span>
              </label>
              <select name="fase" id="fase_cp" class="form-control" required style="border-radius: 8px;">
                <option value="E">Fase E — Kelas X (Dasar Program Keahlian)</option>
                <option value="F">Fase F — Kelas XI & XII (Konsentrasi Keahlian)</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 form-group mb-3">
              <label for="kode_elemen_cp" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                Kode Elemen <span class="text-danger">*</span>
              </label>
              <input type="text" name="kode_elemen" id="kode_elemen_cp" class="form-control font-weight-bold" placeholder="Contoh: EL-01 atau CP-TKJ-01" required style="border-radius: 8px;">
            </div>
            <div class="col-md-8 form-group mb-3">
              <label for="nama_elemen_cp" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                Nama Elemen Capaian Pembelajaran <span class="text-danger">*</span>
              </label>
              <input type="text" name="nama_elemen" id="nama_elemen_cp" class="form-control font-weight-600" placeholder="Contoh: Perencanaan dan Pengalamatan Jaringan" required style="border-radius: 8px;">
            </div>
          </div>

          <div class="form-group mb-0">
            <label for="deskripsi_cp" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
              Deskripsi / Narasi Capaian Pembelajaran (CP)
            </label>
            <textarea name="deskripsi_cp" id="deskripsi_cp" rows="4" class="form-control" placeholder="Tuliskan rumusan capaian kompetensi elemen ini sebagaimana tercantum pada standar kurikulum..." style="border-radius: 8px;"></textarea>
            <small class="form-text text-muted">Narasi CP akan menjadi payung standar kompetensi bagi seluruh Tujuan Pembelajaran (TP) di bawah elemen ini.</small>
          </div>
        </div>

        <div class="modal-footer bg-light py-3 px-4 d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
          <button type="submit" class="btn btn-primary px-4 font-weight-bold" style="border-radius: 8px;">
            <i class="fas fa-save mr-1"></i> Simpan Elemen CP
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ======================================================================= -->
<!-- MODAL EDIT ELEMEN CAPAIAN PEMBELAJARAN (CP)                             -->
<!-- ======================================================================= -->
<div class="modal fade" id="modalEditCp" tabindex="-1" role="dialog" aria-labelledby="modalEditCpTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content border-0 shadow" style="border-radius: 14px; overflow: hidden;">
      <div class="modal-header bg-primary text-white py-3">
        <h5 class="modal-title font-weight-bold" id="modalEditCpTitle">
          <i class="fas fa-edit mr-2"></i> Ubah Elemen Capaian Pembelajaran (CP)
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formEditCp" action="" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body p-4">
          <div class="row">
            <div class="col-md-6 form-group mb-3">
              <label for="edit_id_jurusan_cp" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                Program Keahlian / Jurusan <span class="text-danger">*</span>
              </label>
              <select name="id_jurusan" id="edit_id_jurusan_cp" class="form-control" required style="border-radius: 8px;">
                <option value="">Pilih Program Keahlian</option>
                @foreach($jurusans as $j)
                  <option value="{{ $j->id_jurusan }}">
                    {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                  </option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6 form-group mb-3">
              <label for="edit_fase_cp" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                Fase Kurikulum Merdeka <span class="text-danger">*</span>
              </label>
              <select name="fase" id="edit_fase_cp" class="form-control" required style="border-radius: 8px;">
                <option value="E">Fase E — Kelas X (Dasar Program Keahlian)</option>
                <option value="F">Fase F — Kelas XI & XII (Konsentrasi Keahlian)</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-md-4 form-group mb-3">
              <label for="edit_kode_elemen_cp" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                Kode Elemen <span class="text-danger">*</span>
              </label>
              <input type="text" name="kode_elemen" id="edit_kode_elemen_cp" class="form-control font-weight-bold" required style="border-radius: 8px;">
            </div>
            <div class="col-md-8 form-group mb-3">
              <label for="edit_nama_elemen_cp" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                Nama Elemen Capaian Pembelajaran <span class="text-danger">*</span>
              </label>
              <input type="text" name="nama_elemen" id="edit_nama_elemen_cp" class="form-control font-weight-600" required style="border-radius: 8px;">
            </div>
          </div>

          <div class="form-group mb-0">
            <label for="edit_deskripsi_cp" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
              Deskripsi / Narasi Capaian Pembelajaran (CP)
            </label>
            <textarea name="deskripsi_cp" id="edit_deskripsi_cp" rows="4" class="form-control" style="border-radius: 8px;"></textarea>
          </div>
        </div>

        <div class="modal-footer bg-light py-3 px-4 d-flex justify-content-between">
          <button type="button" class="btn btn-outline-secondary" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
          <button type="submit" class="btn btn-primary px-4 font-weight-bold" style="border-radius: 8px;">
            <i class="fas fa-save mr-1"></i> Perbarui Elemen CP
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
$(document).ready(function() {
  // Populate Edit CP Modal
  $('.btn-edit-cp').on('click', function() {
    var id = $(this).data('id');
    var jurusan = $(this).data('jurusan');
    var kode = $(this).data('kode');
    var nama = $(this).data('nama');
    var fase = $(this).data('fase');
    var deskripsi = $(this).data('deskripsi');

    $('#formEditCp').attr('action', '/admin/kompetensi/cp/' + id);
    $('#edit_id_jurusan_cp').val(jurusan);
    $('#edit_fase_cp').val(fase);
    $('#edit_kode_elemen_cp').val(kode);
    $('#edit_nama_elemen_cp').val(nama);
    $('#edit_deskripsi_cp').val(deskripsi);

    $('#modalEditCp').modal('show');
  });

  // Preserve Active Tab on Page Reload
  $('a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
    var target = $(e.target).attr("href");
    var tabKey = target === '#tab-cp' ? 'cp' : (target === '#tab-tp' ? 'tp' : 'struktur');
    if (history.pushState) {
      var newurl = window.location.protocol + "//" + window.location.host + window.location.pathname + '?tab=' + tabKey;
      window.history.pushState({path:newurl},'',newurl);
    }
  });
});
</script>
@endpush
@endsection
