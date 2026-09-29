@extends($layout)
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-7 d-flex align-items-center">
                <i class="fas fa-exclamation-triangle text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
                <div class="d-flex flex-column justify-content-center">
                    <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Laporan Pelanggaran Siswa</h1>
                    <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Rekapitulasi akumulasi poin pelanggaran dan evaluasi tindak lanjut SP</p>
                </div>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Kedisiplinan</a></li>
                    <li class="breadcrumb-item active">Laporan Pelanggaran</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Filter Card -->
        <div class="card mb-3">
            <div class="card-header d-flex align-items-center">
                <h3 class="card-title text-dark font-weight-bold mb-0">
                    <i class="fas fa-sliders-h text-primary mr-2"></i> Filter Laporan Pelanggaran
                </h3>
            </div>
            <div class="card-body py-3">
                <form action="{{ route('admin.laporanPelanggaran.index') }}" method="GET" id="filter_form">
                    <div class="row align-items-end">
                        <div class="col-md-3 col-sm-6 mb-2">
                            <label class="small font-weight-bold mb-1" for="tanggal_mulai">Tanggal Mulai</label>
                            <input type="date" class="form-control form-control-sm" id="tanggal_mulai" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <label class="small font-weight-bold mb-1" for="tanggal_selesai">Tanggal Selesai</label>
                            <input type="date" class="form-control form-control-sm" id="tanggal_selesai" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <label class="small font-weight-bold mb-1" for="id_kelas">Kelas</label>
                            <select class="form-control form-control-sm" id="id_kelas" name="id_kelas">
                                <option value="all" {{ $selectedKelas === 'all' ? 'selected' : '' }}>-- Semua Kelas --</option>
                                @foreach($daftarKelas as $k)
                                    <option value="{{ $k->id_kelas }}" {{ $selectedKelas == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <label class="small font-weight-bold mb-1" for="status_sp">Status Disiplin</label>
                            <select class="form-control form-control-sm" id="status_sp" name="status_sp">
                                <option value="all" {{ $selectedStatusSp === 'all' ? 'selected' : '' }}>-- Semua Status --</option>
                                <option value="sp_only" {{ $selectedStatusSp === 'sp_only' ? 'selected' : '' }}>Hanya Siswa Kena SP (SP 1, 2, 3)</option>
                                <option value="aman" {{ $selectedStatusSp === 'aman' ? 'selected' : '' }}>Hanya Siswa Belum SP (Aman)</option>
                            </select>
                        </div>
                        <div class="col-12 mt-2 d-flex flex-wrap align-items-center justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary btn-sm px-3 font-weight-bold mr-2">
                                    <i class="fas fa-search mr-1"></i> Terapkan Filter
                                </button>
                                <a href="{{ route('admin.laporanPelanggaran.index') }}" class="btn btn-outline-secondary btn-sm px-3">
                                    <i class="fas fa-redo mr-1"></i> Reset
                                </a>
                            </div>
                            <div class="mt-2 mt-sm-0">
                                <a href="{{ route('admin.laporanPelanggaran.exportExcel', request()->query()) }}" class="btn btn-success btn-sm font-weight-bold mr-2 shadow-sm">
                                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                                </a>
                                <a href="{{ route('admin.laporanPelanggaran.exportPdf', request()->query()) }}" target="_blank" class="btn btn-danger btn-sm font-weight-bold shadow-sm">
                                    <i class="fas fa-file-pdf mr-1"></i> Cetak / PDF
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- 4 Top Statistik KPI Cards (Modern Style matching Rekap Bulanan & WA) -->
        <div class="row mb-2">
            <!-- Total Kejadian Pelanggaran -->
            <div class="col-lg-3 col-sm-6 col-12 mb-3">
                <div class="card p-3 mb-0 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small font-weight-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.04em; text-transform: uppercase;">Total Kejadian</div>
                        <div class="font-weight-bold text-dark" style="font-size: 1.55rem; line-height: 1.1;">{{ number_format($summary['total_kasus']) }}</div>
                        <div class="text-muted mt-1" style="font-size: 0.75rem;"><span class="text-danger font-weight-600"><i class="fas fa-exclamation-triangle mr-1"></i> Kasus</span> tercatat</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px; background: #fee2e2; flex-shrink: 0;">
                        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 1.25rem;"></i>
                    </div>
                </div>
            </div>

            <!-- Total Akumulasi Poin -->
            <div class="col-lg-3 col-sm-6 col-12 mb-3">
                <div class="card p-3 mb-0 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small font-weight-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.04em; text-transform: uppercase;">Akumulasi Poin</div>
                        <div class="font-weight-bold text-dark" style="font-size: 1.55rem; line-height: 1.1;">{{ number_format($summary['total_poin']) }}</div>
                        <div class="text-muted mt-1" style="font-size: 0.75rem;"><span class="text-warning font-weight-600"><i class="fas fa-bolt mr-1"></i> Poin</span> akumulasi</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px; background: #fef3c7; flex-shrink: 0;">
                        <i class="fas fa-clipboard-list text-warning" style="font-size: 1.25rem;"></i>
                    </div>
                </div>
            </div>

            <!-- Siswa Melakukan Pelanggaran -->
            <div class="col-lg-3 col-sm-6 col-12 mb-3">
                <div class="card p-3 mb-0 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small font-weight-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.04em; text-transform: uppercase;">Siswa Melanggar</div>
                        <div class="font-weight-bold text-dark" style="font-size: 1.55rem; line-height: 1.1;">{{ number_format($summary['total_siswa_pelanggar']) }}</div>
                        <div class="text-muted mt-1" style="font-size: 0.75rem;"><span class="text-primary font-weight-600"><i class="fas fa-user mr-1"></i> Siswa</span> terlibat</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px; background: #e0f2fe; flex-shrink: 0;">
                        <i class="fas fa-user-times text-primary" style="font-size: 1.25rem;"></i>
                    </div>
                </div>
            </div>

            <!-- Siswa Mencapai Batas SP -->
            <div class="col-lg-3 col-sm-6 col-12 mb-3">
                <div class="card p-3 mb-0 h-100 d-flex flex-row align-items-center justify-content-between">
                    <div>
                        <div class="text-muted small font-weight-bold mb-1" style="font-size: 0.72rem; letter-spacing: 0.04em; text-transform: uppercase;">Batas SP / Kritis</div>
                        <div class="font-weight-bold text-dark" style="font-size: 1.55rem; line-height: 1.1;">{{ number_format($summary['total_siswa_sp']) }}</div>
                        <div class="text-muted mt-1" style="font-size: 0.75rem;"><span class="font-weight-600" style="color: #9333ea;"><i class="fas fa-file-alt mr-1"></i> Perlu SP</span> resmi</div>
                    </div>
                    <div class="d-flex align-items-center justify-content-center rounded-circle" style="width: 48px; height: 48px; background: #f3e8ff; flex-shrink: 0;">
                        <i class="fas fa-envelope-open-text" style="font-size: 1.25rem; color: #9333ea;"></i>
                    </div>
                </div>
            </div>
        </div>

        @if($topPelanggaran->isNotEmpty())
            <!-- Top Pelanggaran Card -->
            <div class="card mb-3">
                <div class="card-header d-flex align-items-center">
                    <h3 class="card-title text-dark font-weight-bold mb-0">
                        <i class="fas fa-fire mr-2 text-danger"></i> Top 5 Pelanggaran Paling Sering Terjadi (Periode Ini)
                    </h3>
                </div>
                <div class="card-body py-3">
                    <div class="row">
                        @foreach($topPelanggaran as $idx => $tp)
                            <div class="col-md-4 col-sm-6 col-12 mb-2">
                                <div class="p-2 px-3 border rounded-lg d-flex align-items-center justify-content-between" style="background: #fafbfd; border-color: #f1f5f9 !important; border-radius: 12px;">
                                    <div class="text-truncate mr-2 d-flex align-items-center" style="min-width: 0;">
                                        <span class="badge badge-warning mr-2" style="font-size: 0.7rem; padding: 3px 8px;">#{{ $idx + 1 }}</span>
                                        <strong class="text-dark text-truncate" style="font-size: 0.84rem;" title="{{ $tp->point->nama_point ?? 'Pelanggaran' }}">{{ $tp->point->nama_point ?? 'Pelanggaran' }}</strong>
                                    </div>
                                    <span class="badge badge-danger" style="flex-shrink: 0; font-size: 0.72rem;">{{ $tp->total_kasus }} Kasus ({{ $tp->total_skor }} Poin)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Tabel Rekap Pelanggaran -->
        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title text-dark font-weight-bold mb-0">
                    <i class="fas fa-table text-primary mr-2"></i> Data Rekapitulasi Pelanggaran Siswa
                </h3>
                <div class="card-tools d-flex align-items-center ml-auto flex-wrap mt-1 mt-sm-0">
                    <span class="text-muted small">
                        <i class="far fa-calendar-alt mr-1"></i> Periode: <strong>{{ \Carbon\Carbon::parse($tanggalMulai)->locale('id')->translatedFormat('d M Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($tanggalSelesai)->locale('id')->translatedFormat('d M Y') }}</strong>
                    </span>
                </div>
            </div>
            <div class="card-body pt-2">
                <table id="example2" class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr>
                            <th class="no-sort text-center" style="width: 50px; min-width: 50px;">NO</th>
                            <th class="text-left">NAMA SISWA & NIS</th>
                            <th class="text-left">KELAS & WALI KELAS</th>
                            <th class="text-center" style="width: 115px;">JML KASUS</th>
                            <th class="text-center" style="width: 115px;">TOTAL POIN</th>
                            <th class="text-center" style="width: 130px;">STATUS DISIPLIN</th>
                            <th class="no-sort text-center" style="width: 100px; min-width: 100px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dataRekap as $item)
                            @php
                                $s = $item['siswa'];
                                $spBadge = match($item['status_sp']) {
                                    'Aman' => 'badge-success',
                                    'SP 1' => 'badge-warning',
                                    'SP 2' => 'badge-orange text-white',
                                    default => 'badge-danger font-weight-bold'
                                };
                            @endphp
                            <tr class="align-middle">
                                <td class="text-center font-weight-bold">{{ $loop->iteration }}</td>
                                <td>
                                    <strong class="text-primary">{{ $s->nama_siswa }}</strong><br>
                                    <small class="text-muted"><i class="fas fa-id-card mr-1 text-secondary"></i>NIS: {{ $s->nis ?? '-' }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-light border">{{ $s->kelas->nama_kelas ?? '-' }}</span><br>
                                    <small class="text-muted"><i class="fas fa-user-tie mr-1 text-secondary"></i>{{ $s->kelas->waliKelas->nama_guru ?? '-' }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-secondary px-2 py-1">{{ $item['total_kasus'] }} Kejadian</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-danger px-2 py-1 font-weight-bold">{{ $item['total_poin'] }} Poin</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge {{ $spBadge }} px-3 py-1 font-weight-bold" style="{{ $item['status_sp'] === 'SP 2' ? 'background-color: #f97316;' : '' }}">
                                        {{ $item['status_sp'] }}
                                    </span>
                                </td>
                                <td class="text-center" style="white-space: nowrap;">
                                    <div class="d-inline-flex align-items-center justify-content-center" style="gap: 6px;">
                                        <button type="button" class="btn btn-action-view btn-detail-riwayat" data-id="{{ $s->id_siswa }}" title="Lihat Kronologi Pelanggaran">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @if(str_contains($item['status_sp'], 'SP'))
                                            @php
                                                // Ekstrak level SP
                                                preg_match('/\d+/', $item['status_sp'], $m);
                                                $spLvl = $m[0] ?? 1;
                                            @endphp
                                            <a href="{{ route('admin.pointSiswa.sp_pdf', ['id_siswa' => $s->id_siswa, 'sp' => $spLvl]) }}" target="_blank" class="btn btn-action-pdf" title="Cetak Surat Peringatan SP {{ $spLvl }}">
                                                <i class="fas fa-file-pdf"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Riwayat Pelanggaran Siswa -->
<div class="modal fade" id="modalDetailRiwayat" tabindex="-1" role="dialog" aria-labelledby="modalDetailRiwayatLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content shadow">
            <div class="modal-header bg-primary text-white py-2">
                <h5 class="modal-title font-weight-bold" id="modalDetailRiwayatLabel">
                    <i class="fas fa-history mr-2"></i> Riwayat Kronologi Pelanggaran Siswa
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <!-- Info Siswa Header -->
                <div class="p-3 bg-light rounded border mb-4 d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h5 class="font-weight-bold mb-1 text-primary" id="detail_nama_siswa">-</h5>
                        <p class="text-muted small mb-0">
                            NIS: <strong id="detail_nis">-</strong> | Kelas: <strong id="detail_kelas">-</strong> | Wali Kelas: <strong id="detail_walas">-</strong>
                        </p>
                    </div>
                    <div class="text-right mt-2 mt-sm-0">
                        <div class="small text-muted mb-1 font-weight-bold">Akumulasi Poin</div>
                        <span class="badge badge-danger p-2 px-3 text-sm font-weight-bold" id="detail_total_poin">0 Poin</span>
                        <span class="badge badge-warning p-2 px-3 text-sm font-weight-bold ml-1" id="detail_status_sp">Aman</span>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="detail_loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                    <p class="text-muted small mt-2">Memuat riwayat pelanggaran...</p>
                </div>

                <!-- Timeline / List Pelanggaran -->
                <div id="detail_content" class="d-none">
                    <h6 class="font-weight-bold text-secondary mb-3"><i class="fas fa-list-ul mr-1"></i> Rincian Seluruh Kasus Pelanggaran:</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-sm">
                            <thead class="bg-light text-center">
                                <tr>
                                    <th style="width: 40px;">No</th>
                                    <th>Waktu Kejadian</th>
                                    <th>Jenis Pelanggaran</th>
                                    <th>Kategori</th>
                                    <th style="width: 80px;">Skor</th>
                                    <th>Petugas / Guru Pencatat</th>
                                </tr>
                            </thead>
                            <tbody id="detail_tbody">
                                <!-- Rendered via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        // Handler Modal Detail Riwayat Pelanggaran Siswa
        const modalEl = $('#modalDetailRiwayat');

        $(document).on('click', '.btn-detail-riwayat', function() {
            const siswaId = $(this).data('id');
            const tglMulai = document.getElementById('tanggal_mulai').value;
            const tglSelesai = document.getElementById('tanggal_selesai').value;

            // Reset modal
            $('#detail_loading').removeClass('d-none');
            $('#detail_content').addClass('d-none');
            modalEl.modal('show');

            fetch(`/admin/laporan-pelanggaran/detail/${siswaId}?tanggal_mulai=${tglMulai}&tanggal_selesai=${tglSelesai}`)
                .then(res => res.json())
                .then(data => {
                    $('#detail_loading').addClass('d-none');
                    $('#detail_content').removeClass('d-none');

                    if (data.success) {
                        $('#detail_nama_siswa').text(data.siswa.nama);
                        $('#detail_nis').text(data.siswa.nis);
                        $('#detail_kelas').text(data.siswa.kelas);
                        $('#detail_walas').text(data.siswa.wali_kelas);
                        $('#detail_total_poin').text(data.total_poin + ' Poin');
                        $('#detail_status_sp').text(data.status_sp);

                        const tbody = document.getElementById('detail_tbody');
                        tbody.innerHTML = '';

                        if (data.riwayat.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-3">Tidak ada riwayat pelanggaran.</td></tr>';
                        } else {
                            data.riwayat.forEach((item, idx) => {
                                const tr = document.createElement('tr');
                                tr.innerHTML = `
                                    <td class="text-center font-weight-bold">${idx + 1}</td>
                                    <td class="small">${item.tanggal}</td>
                                    <td class="font-weight-bold">${item.nama_point}</td>
                                    <td><span class="badge badge-light border">${item.kategori}</span></td>
                                    <td class="text-center"><span class="badge badge-danger font-weight-bold">+${item.skor_point}</span></td>
                                    <td class="small text-muted">${item.guru}</td>
                                `;
                                tbody.appendChild(tr);
                            });
                        }
                    } else {
                        alert('Gagal memuat rincian siswa.');
                        modalEl.modal('hide');
                    }
                })
                .catch(err => {
                    $('#detail_loading').addClass('d-none');
                    alert('Gagal menghubungi server.');
                    modalEl.modal('hide');
                });
        });
    });
</script>
@endpush
