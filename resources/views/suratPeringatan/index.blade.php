@extends($layout)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-7 d-flex align-items-center">
                <i class="fas fa-envelope-open-text text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
                <div class="d-flex flex-column justify-content-center">
                    <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Manajemen Surat Peringatan (SP)</h1>
                    <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Penerbitan surat peringatan kedisiplinan dan rekapitulasi arsip nomor surat resmi</p>
                </div>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Kedisiplinan</a></li>
                    <li class="breadcrumb-item active">Surat Peringatan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Stat Summary Widgets -->
        <div class="row mb-3">
            <div class="col-xl-3 col-md-6 col-12 mb-3">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; border-left: 4px solid #f59e0b !important;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted font-weight-600 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Antrean Perlu SP</span>
                            <h3 class="font-weight-bold text-dark mb-0 mt-1">{{ $totalAntrean }}</h3>
                            <small class="text-warning font-weight-bold">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Perlu Tindakan
                            </small>
                        </div>
                        <div class="bg-light d-flex align-items-center justify-content-center rounded-circle" style="width: 50px; height: 50px; background-color: #fef3c7 !important;">
                            <i class="fas fa-user-clock text-warning" style="font-size: 1.4rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12 mb-3">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; border-left: 4px solid #1d72fe !important;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted font-weight-600 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Total SP Diterbitkan</span>
                            <h3 class="font-weight-bold text-dark mb-0 mt-1">{{ $totalSpDiterbitkan }}</h3>
                            <small class="text-primary font-weight-bold">
                                <i class="fas fa-file-invoice mr-1"></i> Nomor Resmi Tercatat
                            </small>
                        </div>
                        <div class="bg-light d-flex align-items-center justify-content-center rounded-circle" style="width: 50px; height: 50px; background-color: #dbeafe !important;">
                            <i class="fas fa-file-signature text-primary" style="font-size: 1.4rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12 mb-3">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; border-left: 4px solid #10b981 !important;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted font-weight-600 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Berkas TTD Terunggah</span>
                            <h3 class="font-weight-bold text-dark mb-0 mt-1">{{ $totalSudahTtd }}</h3>
                            <small class="text-success font-weight-bold">
                                <i class="fas fa-check-circle mr-1"></i> Arsip Digital Lengkap
                            </small>
                        </div>
                        <div class="bg-light d-flex align-items-center justify-content-center rounded-circle" style="width: 50px; height: 50px; background-color: #d1fae5 !important;">
                            <i class="fas fa-file-check text-success" style="font-size: 1.4rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12 mb-3">
                <div class="card h-100 shadow-sm border-0" style="border-radius: 12px; border-left: 4px solid #64748b !important;">
                    <div class="card-body p-3 d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-muted font-weight-600 text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Menunggu Berkas TTD</span>
                            <h3 class="font-weight-bold text-dark mb-0 mt-1">{{ $totalBelumTtd }}</h3>
                            <small class="text-secondary font-weight-bold">
                                <i class="fas fa-hourglass-half mr-1"></i> Belum Diunggah
                            </small>
                        </div>
                        <div class="bg-light d-flex align-items-center justify-content-center rounded-circle" style="width: 50px; height: 50px; background-color: #f1f5f9 !important;">
                            <i class="fas fa-file-upload text-secondary" style="font-size: 1.4rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 1: Antrean Siswa Perlu Penerbitan SP -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between" style="border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: 1px solid #edf2f7;">
                        <div class="d-flex align-items-center">
                            <span class="d-inline-flex align-items-center justify-content-center bg-warning text-white rounded-circle mr-2" style="width: 32px; height: 32px;">
                                <i class="fas fa-user-clock" style="font-size: 0.9rem;"></i>
                            </span>
                            <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1.05rem;">
                                Antrean Siswa Perlu Penerbitan SP
                            </h3>
                            <span class="badge badge-warning text-dark font-weight-bold ml-2 px-2 py-1" style="font-size: 0.8rem;">
                                {{ $antreanSp->count() }} Siswa
                            </span>
                        </div>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.location.reload();" title="Segarkan Antrean">
                            <i class="fas fa-sync-alt mr-1"></i> Segarkan
                        </button>
                    </div>
                    <div class="card-body p-3">
                        @if ($antreanSp->isNotEmpty())
                            <div class="alert alert-light border d-flex align-items-center mb-3 py-2 px-3" style="border-radius: 8px; background-color: #fffbeb; border-color: #fde68a !important;">
                                <i class="fas fa-info-circle text-warning mr-2" style="font-size: 1.1rem;"></i>
                                <span class="text-dark font-weight-500" style="font-size: 0.88rem;">
                                    Siswa di bawah ini telah mencapai ambang batas poin pelanggaran tetapi nomor Surat Peringatan resminya belum tercatat di sistem. Klik tombol <strong>Terbitkan & Cetak SP</strong> untuk mencatat nomor surat dan mengunduh berkas.
                                </span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered table-hover align-middle mb-0">
                                    <thead class="bg-light text-dark">
                                        <tr>
                                            <th style="width: 10px;" class="text-center">No</th>
                                            <th>Nama Siswa</th>
                                            <th>Kelas</th>
                                            <th class="text-center">Akumulasi Poin</th>
                                            <th class="text-center">Kebutuhan SP</th>
                                            <th class="text-center" style="width: 250px;">Aksi Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($antreanSp as $item)
                                        <tr>
                                            <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                            <td class="align-middle">
                                                <div class="font-weight-bold text-dark">{{ $item->siswa->nama_siswa ?? 'N/A' }}</div>
                                                <small class="text-muted">NISN: {{ $item->siswa->nisn ?? '-' }} | NIS: {{ $item->siswa->nis ?? '-' }}</small>
                                            </td>
                                            <td class="align-middle">
                                                <span class="badge badge-light border text-dark px-2 py-1 font-weight-500">
                                                    {{ $item->siswa->kelas->nama_kelas ?? 'N/A' }}
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-danger px-2 py-1 font-weight-bold" style="font-size: 0.88rem;">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> {{ $item->total_point }} Poin
                                                </span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="badge badge-warning text-dark px-2 py-1 font-weight-bold" style="font-size: 0.85rem;">
                                                    Perlu SP-{{ $item->sp_level }}
                                                </span>
                                                <small class="text-muted d-block mt-1">Rekomendasi Utama (Min. {{ $item->threshold }} Poin)</small>
                                                @if (!empty($item->pending_lower_levels))
                                                    @foreach ($item->pending_lower_levels as $p)
                                                        <small class="text-secondary d-block mt-1 font-weight-500">
                                                            <i class="fas fa-clock text-warning mr-1"></i> SP-{{ $p['sp_level'] }} belum terbit
                                                        </small>
                                                    @endforeach
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="d-flex align-items-center justify-content-center flex-wrap" style="gap: 6px;">
                                                    <!-- Lihat Riwayat Poin -->
                                                    <a href="{{ route('admin.pointSiswa.review_point_siswa', $item->id_siswa) }}" 
                                                       class="btn btn-outline-info btn-sm" 
                                                       target="_blank" 
                                                       title="Lihat riwayat rincian pelanggaran siswa">
                                                        <i class="fas fa-history mr-1"></i> Riwayat Poin
                                                    </a>

                                                    <!-- Terbitkan & Cetak Rekomendasi Utama -->
                                                    <a href="{{ route('admin.pointSiswa.sp_pdf', ['id_siswa' => $item->id_siswa, 'sp' => $item->sp_level]) }}" 
                                                       class="btn btn-primary btn-sm btn-terbitkan-sp font-weight-bold" 
                                                       target="_blank" 
                                                       title="Terbitkan nomor resmi dan cetak SP-{{ $item->sp_level }} (Rekomendasi Utama)">
                                                        <i class="fas fa-print mr-1"></i> Terbitkan SP-{{ $item->sp_level }}
                                                    </a>

                                                    <!-- Opsi Terbitkan SP Tertunda jika ada -->
                                                    @if (!empty($item->pending_lower_levels))
                                                        @foreach ($item->pending_lower_levels as $p)
                                                            <a href="{{ route('admin.pointSiswa.sp_pdf', ['id_siswa' => $item->id_siswa, 'sp' => $p['sp_level']]) }}" 
                                                               class="btn btn-outline-warning btn-sm btn-terbitkan-sp text-dark font-weight-500" 
                                                               target="_blank" 
                                                               title="Opsi terbitkan SP-{{ $p['sp_level'] }} yang tertunda jika ingin menjaga prosedur pembinaan bertahap">
                                                                <i class="fas fa-clock mr-1"></i> Opsi: SP-{{ $p['sp_level'] }} (Tertunda)
                                                            </a>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4 text-muted">
                                <div class="mb-2">
                                    <i class="fas fa-check-circle text-success" style="font-size: 2.6rem;"></i>
                                </div>
                                <h5 class="font-weight-bold text-dark mb-1">Tidak Ada Antrean Penerbitan SP</h5>
                                <p class="text-muted mb-0" style="font-size: 0.88rem;">
                                    Seluruh siswa dengan akumulasi poin kritis saat ini telah memiliki Surat Peringatan (SP) resmi yang diterbitkan.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Data Nomor Surat Peringatan (Arsip Resmi) -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 12px;">
                    <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between" style="border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: 1px solid #edf2f7;">
                        <div class="d-flex align-items-center">
                            <span class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mr-2" style="width: 32px; height: 32px;">
                                <i class="fas fa-archive" style="font-size: 0.9rem;"></i>
                            </span>
                            <h3 class="card-title text-dark font-weight-bold mb-0" style="font-size: 1.05rem;">
                                Data Nomor Surat Peringatan Resmi
                            </h3>
                            <span class="badge badge-primary font-weight-bold ml-2 px-2 py-1" style="font-size: 0.8rem;">
                                {{ $suratPeringatan->count() }} Surat
                            </span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="table-responsive">
                            <table id="spTable" class="table table-bordered table-hover table-striped mb-0">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th style="width: 10px" class="text-center">No</th>
                                        <th>Nomor Surat</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th class="text-center">SP</th>
                                        <th>Tanggal Dibuat</th>
                                        <th class="text-center">Status TTD</th>
                                        <th class="text-center" style="width: 140px;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($suratPeringatan as $sp)
                                    <tr>
                                        <td class="text-center align-middle">{{ $loop->iteration }}</td>
                                        <td class="align-middle">
                                            <span class="font-weight-bold text-dark">{{ $sp->nomor_surat }}</span>
                                        </td>
                                        <td class="align-middle">{{ $sp->siswa->nama_siswa ?? 'N/A' }}</td>
                                        <td class="align-middle">{{ $sp->kelas->nama_kelas ?? 'N/A' }}</td>
                                        <td class="text-center align-middle">
                                            <span class="badge badge-warning text-dark font-weight-bold px-2 py-1">SP-{{ $sp->sp_level }}</span>
                                        </td>
                                        <td class="align-middle">{{ \Carbon\Carbon::parse($sp->created_at)->translatedFormat('d F Y') }}</td>
                                        <td class="text-center align-middle">
                                            @if ($sp->file_ttd)
                                                <span class="badge badge-success px-2 py-1">
                                                    <i class="fas fa-check-circle mr-1"></i> Sudah Diunggah
                                                </span>
                                            @else
                                                <span class="badge badge-danger px-2 py-1">
                                                    <i class="fas fa-times-circle mr-1"></i> Belum Diunggah
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center align-middle">
                                            <div class="btn-group">
                                                <!-- Cetak SP -->
                                                <a href="{{ route('admin.pointSiswa.sp_pdf', ['id_siswa' => $sp->id_siswa, 'sp' => $sp->sp_level]) }}" 
                                                   class="btn btn-info btn-sm" 
                                                   target="_blank" 
                                                   title="Cetak Ulang Dokumen SP">
                                                    <i class="fas fa-print"></i>
                                                </a>

                                                <!-- Upload SP ttd -->
                                                <button type="button" 
                                                        class="btn btn-warning btn-sm text-white" 
                                                        data-toggle="modal" 
                                                        data-target="#uploadModal{{ $sp->id_sp }}" 
                                                        title="Unggah SP Bertanda Tangan">
                                                    <i class="fas fa-upload"></i>
                                                </button>

                                                <!-- Lihat Dokumen TTD -->
                                                @if ($sp->file_ttd)
                                                    <a href="{{ asset('storage/sp_ttd/' . $sp->file_ttd) }}" 
                                                       class="btn btn-success btn-sm" 
                                                       target="_blank" 
                                                       title="Lihat Berkas TTD">
                                                        <i class="fas fa-file-pdf"></i>
                                                    </a>
                                                @endif
                                            </div>

                                            <!-- Modal Upload SP TTD -->
                                            <div class="modal fade text-left" id="uploadModal{{ $sp->id_sp }}" tabindex="-1" role="dialog" aria-labelledby="uploadModalLabel{{ $sp->id_sp }}" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
                                                        <form action="{{ route('admin.suratPeringatan.uploadTtd', $sp->id_sp) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #1d72fe 0%, #0b1f3a 100%);">
                                                                <h5 class="modal-title font-weight-bold" id="uploadModalLabel{{ $sp->id_sp }}">
                                                                    <i class="fas fa-file-upload mr-2"></i> Unggah SP Bertanda Tangan
                                                                </h5>
                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body p-4">
                                                                <div class="form-group mb-3">
                                                                    <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Nomor Surat</label>
                                                                    <input type="text" class="form-control font-weight-bold bg-light" value="{{ $sp->nomor_surat }}" readonly disabled style="border-radius: 8px; height: 42px;">
                                                                </div>
                                                                <div class="form-group mb-3">
                                                                    <label class="font-weight-bold text-dark" style="font-size: 0.85rem;">Nama Siswa</label>
                                                                    <input type="text" class="form-control font-weight-bold bg-light" value="{{ $sp->siswa->nama_siswa ?? 'N/A' }}" readonly disabled style="border-radius: 8px; height: 42px;">
                                                                </div>
                                                                <div class="form-group mb-0">
                                                                    <label for="file_ttd_{{ $sp->id_sp }}" class="font-weight-bold text-dark" style="font-size: 0.85rem;">File SP Bertanda Tangan (PDF/Gambar) <span class="text-danger">*</span></label>
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input" name="file_ttd" id="file_ttd_{{ $sp->id_sp }}" accept=".pdf,image/*" required>
                                                                        <label class="custom-file-label" for="file_ttd_{{ $sp->id_sp }}" style="border-radius: 8px;">Pilih Berkas...</label>
                                                                    </div>
                                                                    <small class="form-text text-muted mt-1">Format: PDF, JPG, JPEG, PNG (Maksimal 4MB)</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer bg-light py-3 px-4">
                                                                <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
                                                                <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" style="border-radius: 8px;">
                                                                    <i class="fas fa-save mr-1"></i> Unggah Berkas
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
                                        <td colspan="8" class="text-center text-muted py-4">Belum ada Surat Peringatan (SP) yang diterbitkan.</td>
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

@push('scripts')
<script>
@if ($message = Session::get('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ $message }}'
    });
@endif

@if ($message = Session::get('failed'))
    Swal.fire({
        icon: 'error',
        title: 'Gagal!',
        text: '{{ $message }}'
    });
@endif

$(document).ready(function() {
    // Custom file input label update
    $('.custom-file-input').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });

    // Ketika tombol Terbitkan & Cetak diklik, jadwalkan reload otomatis antrean setelah 2 detik
    $('.btn-terbitkan-sp').on('click', function() {
        setTimeout(function() {
            window.location.reload();
        }, 2000);
    });
});
</script>
@endpush
@endsection
