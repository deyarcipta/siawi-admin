@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-7 d-flex align-items-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div>
                    <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Review & Riwayat Poin Siswa</h1>
                    <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Rincian akumulasi skor poin pelanggaran, catatan kedisiplinan, dan penerbitan SP</p>
                </div>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
                    <li class="breadcrumb-item"><a href="/admin/pointSiswa" class="text-primary font-weight-500">Point Siswa</a></li>
                    <li class="breadcrumb-item active">Review Poin</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
                    <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
                        <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
                            <i class="fas fa-user-shield text-primary mr-2"></i> Rekapitulasi Kedisiplinan Siswa
                        </h5>
                    </div>
                    <div class="card-body">
                        <p style="margin:0">Nama Siswa : {{ $siswa->nama_siswa }}</p>
                        <p style="margin:0">No. Induk Siswa : {{ $siswa->nis }}</p>
                        <p style="margin:0">Kelas : {{ $siswa->kelas->nama_kelas }}</p>
                        @php
                            $spRules = $setting->sp_settings['sp_rules'] ?? [];
                            ksort($spRules);
                            $minSpThreshold = count($spRules) > 0 ? reset($spRules) : 25;
                            $maxSpThreshold = count($spRules) > 0 ? end($spRules) : 75;
                            
                            $textClass = 'text-success';
                            if ($total_point >= $maxSpThreshold) {
                                $textClass = 'text-danger';
                            } elseif ($total_point >= $minSpThreshold) {
                                $textClass = 'text-warning';
                            }
                        @endphp
                        <p style="margin:0">Total Point : <strong class="{{ $textClass }}">{{ $total_point }}</strong></p>
                        
                        @if ($total_point >= $minSpThreshold)
                        @php
                            $alertClass = 'alert-info';
                            if ($total_point >= $maxSpThreshold) {
                                $alertClass = 'alert-danger';
                            } elseif (count($spRules) > 1) {
                                $alertClass = 'alert-warning';
                            }
                        @endphp
                        <div class="alert {{ $alertClass }} mt-3">
                            <h5><i class="icon fas fa-exclamation-triangle"></i> Status Kritis Poin Pelanggaran!</h5>
                            Siswa ini telah mengumpulkan <strong>{{ $total_point }}</strong> poin pelanggaran. Batas toleransi terlewati.
                            
                            @if($user && $user->hasAnyRole(['admin', 'kesiswaan', 'wali_kelas', 'kurikulum']))
                            <div class="mt-2">
                                <span class="d-block mb-1" style="font-size: 0.9rem;">Unduh Surat Peringatan resmi:</span>
                                @foreach ($spRules as $spLevel => $threshold)
                                    @if ($total_point >= $threshold)
                                        <a href="{{ route('admin.pointSiswa.sp_pdf', ['id_siswa' => $siswa->id_siswa, 'sp' => $spLevel]) }}" class="btn btn-dark btn-sm mt-1 mr-2" target="_blank">
                                            <i class="fas fa-file-pdf mr-1"></i> Cetak SP {{ $spLevel }} (Min. {{ $threshold }} Poin)
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                            @else
                            <p class="mb-0 mt-2 text-dark font-weight-500" style="font-size: 0.85rem;">
                                <i class="fas fa-info-circle mr-1 text-primary"></i> Penerbitan dan pencetakan Surat Peringatan (SP) resmi merupakan wewenang Tim Kesiswaan, Guru BK, dan Wali Kelas.
                            </p>
                            @endif
                        </div>
                        @endif

                        <table id="siswaTable" class="table table-bordered table-hover mt-2">
                            <thead>
                                <tr>
                                    <th style="width: 10px">No</th>
                                    <th>Nama Pelanggaran</th>
                                    <th>Tanggal Pelanggaran</th>
                                    <th>Nama Pelapor</th>
                                    <th>Level Pelapor</th>
                                    <th>Point</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pointSiswa as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->point->nama_point }}</td>
                                    <td>{{ $data->tanggal }}</td>
                                    <td>{{ $data->guru?->nama_guru ?? 'Guru Telah Dihapus' }}</td>
                                    <td>{{ $data->guru?->role ?? '-' }}</td>
                                    <td>{{ $data->skor_point }}</td>
                                    <td>
                                        <form action="{{ route('admin.pointSiswa.destroy', $data->id_point_siswa) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
                        <a href="/admin/pointSiswa" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                            <i class="fas fa-arrow-left mr-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
</script>

<script>
$(document).ready(function() {
    $('#searchInput').on('keyup', function() {
        var searchText = $(this).val().toLowerCase();
        $('#siswaTable tbody tr').each(function() {
            var currentRowText = $(this).text().toLowerCase();
            $(this).toggle(currentRowText.indexOf(searchText) !== -1);
        });
    });
});
</script>
@endsection
