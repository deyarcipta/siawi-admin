@extends($layout)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-7 d-flex align-items-center">
                <i class="fas fa-envelope-open-text text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
                <div class="d-flex flex-column justify-content-center">
                    <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Manajemen Surat Peringatan (SP)</h1>
                    <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Penerbitan surat teguran resmi kedisiplinan dan arsip nomor surat</p>
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
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h3 class="card-title text-dark font-weight-bold mb-0">
                            <i class="fas fa-table text-primary mr-2"></i> Data Nomor Surat Peringatan
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="spTable" class="table table-bordered table-hover table-striped">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Nomor Surat</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>SP</th>
                                        <th>Tanggal Dibuat</th>
                                        <th class="text-center">Status TTD</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($suratPeringatan as $sp)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $sp->nomor_surat }}</strong></td>
                                        <td>{{ $sp->siswa->nama_siswa ?? 'N/A' }}</td>
                                        <td>{{ $sp->kelas->nama_kelas ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-warning">SP-{{ $sp->sp_level }}</span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($sp->created_at)->translatedFormat('d F Y') }}</td>
                                        <td class="text-center">
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
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <!-- Cetak SP -->
                                                <a href="{{ route('admin.pointSiswa.sp_pdf', ['id_siswa' => $sp->id_siswa, 'sp' => $sp->sp_level]) }}" 
                                                   class="btn btn-info btn-sm" 
                                                   target="_blank" 
                                                   title="Cetak SP">
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
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <form action="{{ route('admin.suratPeringatan.uploadTtd', $sp->id_sp) }}" method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-header bg-warning text-white">
                                                                <h5 class="modal-title" id="uploadModalLabel{{ $sp->id_sp }}">
                                                                    <i class="fas fa-file-upload mr-1"></i> Unggah SP Bertanda Tangan
                                                                </h5>
                                                                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label>Nomor Surat</label>
                                                                    <input type="text" class="form-control" value="{{ $sp->nomor_surat }}" readonly disabled>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Nama Siswa</label>
                                                                    <input type="text" class="form-control" value="{{ $sp->siswa->nama_siswa ?? 'N/A' }}" readonly disabled>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="file_ttd">File SP Bertanda Tangan (PDF/Gambar)</label>
                                                                    <div class="custom-file">
                                                                        <input type="file" class="custom-file-input" name="file_ttd" id="file_ttd_{{ $sp->id_sp }}" accept=".pdf,image/*" required>
                                                                        <label class="custom-file-label" for="file_ttd_{{ $sp->id_sp }}">Pilih Berkas...</label>
                                                                    </div>
                                                                    <small class="form-text text-muted">Format file: PDF, JPG, JPEG, PNG (Maks. 4MB)</small>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                                                                <button type="submit" class="btn btn-warning text-white">Unggah Berkas</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Belum ada Surat Peringatan (SP) yang diterbitkan.</td>
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
});
</script>
@endpush
@endsection
