@extends($layout)
@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Review Point Siswa</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Point Siswa</a></li>
                    <li class="breadcrumb-item active">Review Point Siswa</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Form Review Point Siswa</h3>
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
                            Siswa ini telah mengumpulkan <strong>{{ $total_point }}</strong> poin pelanggaran. Batas toleransi terlewati. Anda dapat mengunduh Surat Peringatan resmi di bawah ini:
                            <div class="mt-2">
                                @foreach ($spRules as $spLevel => $threshold)
                                    @if ($total_point >= $threshold)
                                        <a href="{{ route('admin.pointSiswa.sp_pdf', ['id_siswa' => $siswa->id_siswa, 'sp' => $spLevel]) }}" class="btn btn-dark btn-sm mt-1 mr-2" target="_blank">
                                            <i class="fas fa-file-pdf mr-1"></i> Cetak SP {{ $spLevel }} (Min. {{ $threshold }} Poin)
                                        </a>
                                    @endif
                                @endforeach
                            </div>
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
                                    <td>{{ $data->guru->nama_guru }}</td>
                                    <td>{{ $data->guru->role }}</td>
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
                    <div class="card-footer"></div>
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
