@extends($layout)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-7 d-flex align-items-center">
                <i class="fas fa-star-half-alt text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
                <div class="d-flex flex-column justify-content-center">
                    <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Point Kedisiplinan Siswa</h1>
                    <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Pencatatan poin pelanggaran dan penghargaan prestasi siswa per kelas</p>
                </div>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Kedisiplinan</a></li>
                    <li class="breadcrumb-item active">Point Siswa</li>
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
                            <i class="fas fa-sliders-h text-primary mr-2"></i> Filter Data Point Per Kelas
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="/admin/pointSiswa" method="GET">
                            @csrf
                            <div class="row align-items-end">
                                <div class="form-group col-md-5 mb-3 mb-md-0">
                                    <label for="tanggal" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Tanggal (Opsional)</label>
                                    <input type="datetime-local" class="form-control" id="tanggal" name="tanggal" value="{{ $tanggal ?? '' }}">
                                </div>
                                <div class="form-group col-md-5 mb-3 mb-md-0">
                                    <label for="kelas" class="font-weight-bold text-secondary" style="font-size: 0.78rem; text-transform: uppercase;">Kelas</label>
                                    <select class="form-control" id="kelas" name="kelas" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($kelas as $kls)
                                        <option value="{{ $kls->id_kelas }}" {{ $kelasId == $kls->id_kelas ? 'selected' : '' }}>{{ $kls->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-2 mb-0">
                                    <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search mr-1"></i> Tampilkan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($siswa))
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header d-flex align-items-center">
                        <h3 class="card-title text-dark font-weight-bold mb-0">
                            <i class="fas fa-table text-primary mr-2"></i> Data Siswa & Point
                        </h3>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px">No</th>
                                    <th>Nama Siswa</th>
                                    <th style="width: 220px; text-align: center;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswa as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <input type="hidden" name="siswa[{{ $data->id_siswa }}][id_siswa]" value="{{ $data->id_siswa }}">
                                        <span class="font-weight-bold text-dark">{{ $data->nama_siswa }}</span>
                                    </td>
                                    <td class="text-center" style="white-space: nowrap;">
                                        <a href="{{ route('admin.pointSiswa.review_point_siswa', ['id_siswa' => $data->id_siswa]) }}" class="btn btn-info btn-sm mr-1">
                                            <i class="fas fa-eye mr-1"></i> Lihat Poin
                                        </a>
                                        <a href="/admin/pointSiswa/proses/{{$data->id_siswa}}/{{$tanggal}}" class="btn btn-danger btn-sm">
                                            <i class="fas fa-plus mr-1"></i> Input Poin
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <input type="hidden" name="kelas_id" value="{{ $dataKelas->id_kelas }}">
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

@endsection
