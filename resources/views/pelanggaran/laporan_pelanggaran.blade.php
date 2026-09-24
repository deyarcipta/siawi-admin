@extends($layout)
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 font-weight-bold">Laporan Pelanggaran Siswa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
                    <li class="breadcrumb-item"><a href="/admin/pointSiswa">Point Siswa</a></li>
                    <li class="breadcrumb-item active">Laporan Pelanggaran</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Filter Card -->
        <div class="card card-outline card-primary shadow-sm mb-4">
            <div class="card-header py-2">
                <h3 class="card-title font-weight-bold text-primary">
                    <i class="fas fa-filter mr-1"></i> Filter Laporan Pelanggaran
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

        <!-- 4 Top Statistik Cards -->
        <div class="row">
            <div class="col-lg-3 col-6 mb-3">
                <div class="small-box bg-gradient-danger shadow-sm">
                    <div class="inner">
                        <h3>{{ number_format($summary['total_kasus']) }}</h3>
                        <p class="font-weight-bold">Total Kejadian Pelanggaran</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <div class="small-box bg-gradient-warning shadow-sm">
                    <div class="inner">
                        <h3>{{ number_format($summary['total_poin']) }}</h3>
                        <p class="font-weight-bold">Total Akumulasi Poin</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <div class="small-box bg-gradient-info shadow-sm">
                    <div class="inner">
                        <h3>{{ number_format($summary['total_siswa_pelanggar']) }}</h3>
                        <p class="font-weight-bold">Siswa Melakukan Pelanggaran</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-times"></i>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-6 mb-3">
                <div class="small-box bg-gradient-purple shadow-sm" style="background: linear-gradient(135deg, #8b5cf6, #6d28d9); color: white;">
                    <div class="inner">
                        <h3>{{ number_format($summary['total_siswa_sp']) }}</h3>
                        <p class="font-weight-bold">Siswa Mencapai Batas SP</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-envelope-open-text"></i>
                    </div>
                </div>
            </div>
        </div>

        @if($topPelanggaran->isNotEmpty())
            <!-- Top Pelanggaran Card -->
            <div class="card card-outline card-warning shadow-sm mb-4">
                <div class="card-header py-2">
                    <h3 class="card-title font-weight-bold text-warning mb-0">
                        <i class="fas fa-fire mr-1 text-danger"></i> Top 5 Pelanggaran Paling Sering Terjadi (Periode Ini)
                    </h3>
                </div>
                <div class="card-body py-2">
                    <div class="row">
                        @foreach($topPelanggaran as $idx => $tp)
                            <div class="col-md-4 col-sm-6 mb-2">
                                <div class="p-2 border rounded bg-light d-flex align-items-center justify-content-between">
                                    <div class="text-truncate mr-2">
                                        <span class="badge badge-warning mr-1">#{{ $idx + 1 }}</span>
                                        <strong class="text-sm">{{ $tp->point->nama_point ?? 'Pelanggaran' }}</strong>
                                    </div>
                                    <span class="badge badge-danger badge-pill">{{ $tp->total_kasus }} Kasus ({{ $tp->total_skor }} Poin)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Tabel Rekap Pelanggaran -->
        <div class="card card-outline card-primary shadow-sm">
            <div class="card-header py-2 d-flex align-items-center justify-content-between flex-wrap">
                <h3 class="card-title font-weight-bold mb-0">
                    <i class="fas fa-list-ol mr-1"></i> Data Rekapitulasi Pelanggaran Siswa
                </h3>
                <div class="card-tools d-flex align-items-center ml-auto flex-wrap mt-1 mt-sm-0">
                    <span class="text-muted small mr-3">
                        <i class="far fa-calendar-alt mr-1"></i> Periode: <strong>{{ \Carbon\Carbon::parse($tanggalMulai)->locale('id')->translatedFormat('d M Y') }}</strong> s/d <strong>{{ \Carbon\Carbon::parse($tanggalSelesai)->locale('id')->translatedFormat('d M Y') }}</strong>
                    </span>
                    <div id="table_search_slot" class="d-flex align-items-center"></div>
                </div>
            </div>
            <div class="card-body pt-2">
                <table id="example2" class="table table-bordered table-hover table-striped">
                    <thead>
                        <tr class="text-center">
                            <th style="width: 10px;">No</th>
                            <th class="text-left">Nama Siswa & NIS</th>
                            <th class="text-left">Kelas & Wali Kelas</th>
                            <th class="text-center" style="width: 110px;">Jml Kasus</th>
                            <th class="text-center" style="width: 110px;">Total Poin</th>
                            <th class="text-center" style="width: 120px;">Status Disiplin</th>
                            <th class="text-center" style="width: 140px;">Aksi</th>
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
                                <td class="text-center text-nowrap">
                                    <button type="button" class="btn btn-xs btn-info mr-1 btn-detail-riwayat" data-id="{{ $s->id_siswa }}" title="Lihat Kronologi Pelanggaran">
                                        <i class="fas fa-eye mr-1"></i> Riwayat
                                    </button>
                                    @if(str_contains($item['status_sp'], 'SP'))
                                        @php
                                            // Ekstrak level SP
                                            preg_match('/\d+/', $item['status_sp'], $m);
                                            $spLvl = $m[0] ?? 1;
                                        @endphp
                                        <a href="{{ route('admin.pointSiswa.sp_pdf', ['id_siswa' => $s->id_siswa, 'sp' => $spLvl]) }}" target="_blank" class="btn btn-xs btn-outline-danger" title="Cetak Surat Peringatan">
                                            <i class="fas fa-file-pdf"></i> SP {{ $spLvl }}
                                        </a>
                                    @endif
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
<style>
    .dataTables_wrapper .top {
        display: none !important;
    }
</style>
<script>
    $(function() {
        // Pindahkan box search DataTables ke dalam Card Header bersebelahan dengan Periode di sebelah kanan
        function moveSearchToHeader() {
            const searchBox = $('#example2_filter');
            const searchSlot = $('#table_search_slot');
            if (searchBox.length && searchSlot.length && !searchSlot.find('#example2_filter').length) {
                searchSlot.append(searchBox);
                searchBox.css({
                    'margin': '0',
                    'float': 'none',
                    'display': 'flex',
                    'align-items': 'center'
                });
                searchBox.find('label').css({
                    'margin': '0',
                    'display': 'flex',
                    'align-items': 'center',
                    'font-size': '13px',
                    'font-weight': '600',
                    'color': '#4a5568'
                });
                searchBox.find('input').addClass('form-control form-control-sm ml-2').css({
                    'height': '31px',
                    'width': '180px',
                    'display': 'inline-block'
                });
            }
        }

        moveSearchToHeader();
        setTimeout(moveSearchToHeader, 50);
        setTimeout(moveSearchToHeader, 200);

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
