@extends($layout)
@section('content')
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Rekap Kelalaian Absen</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Home</a></li>
            <li class="breadcrumb-item active">Rekap Kelalaian</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          
          <div class="card card-default">
            <div class="card-header">
              <h3 class="card-title">Filter Rekap Kelalaian Input Absensi</h3>
            </div>
            <div class="card-body">
              <form action="{{ route('admin.rekapBelumAbsen.index') }}" method="GET" class="form-inline">
                <div class="form-group mr-2">
                  <label for="tanggal" class="mr-2">Pilih Tanggal: </label>
                  <input type="date" name="tanggal" id="tanggal" class="form-control" value="{{ $date }}">
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
              </form>
            </div>
          </div>

          <div class="card card-primary card-outline mt-3">
            <div class="card-header d-flex align-items-center">
              <h3 class="card-title font-weight-bold">
                <i class="fa fa-clipboard-list mr-1"></i> Rekap Kelalaian Input - Tanggal: {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }} ({{ $dayInd ?? '' }})
              </h3>
              @if($criteriaMet && count($kelasBelumAbsen) > 0)
                <a href="{{ route('admin.rekapBelumAbsen.export', ['tanggal' => $date]) }}" class="btn btn-success btn-sm ml-auto">
                  <i class="fa fa-file-excel mr-1"></i> Export Excel
                </a>
              @endif
            </div>

            <div class="card-body">
              @if(!$criteriaMet)
                <div class="alert alert-warning text-center shadow-sm">
                  <h5 class="font-weight-bold mb-2"><i class="fa fa-exclamation-triangle mr-1"></i> Kriteria Tidak Terpenuhi!</h5>
                  Data kelalaian input absensi hanya dapat ditampilkan jika minimal terdapat 2 kelas yang seluruh siswanya telah mengisi/diisi absensinya. <br>
                  (Saat ini baru <strong>{{ $fullClassesCount }}</strong> kelas yang sudah full mengisi absensi).
                </div>
              @else
                
                @if(count($guruPiket) > 0)
                  <div class="alert alert-info shadow-sm">
                    <h6 class="font-weight-bold mb-2"><i class="fa fa-user-clock mr-1"></i> Guru Piket Bertugas Hari Ini:</h6>
                    <ul class="mb-0 pl-3">
                      @foreach($guruPiket as $gp)
                        <li>{{ $gp->guru->nama_guru }} ({{ $gp->waktu_awal }} - {{ $gp->waktu_akhir }})</li>
                      @endforeach
                    </ul>
                  </div>
                @else
                  <div class="alert alert-secondary shadow-sm">
                    <i class="fa fa-info-circle mr-1"></i> Tidak ada jadwal Guru Piket yang diatur untuk hari {{ $dayInd }}.
                  </div>
                @endif

                @if(count($kelasBelumAbsen) > 0)
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
                      <thead class="bg-primary text-white">
                        <tr>
                          <th style="width: 10px">No</th>
                          <th>Nama Kelas</th>
                          <th>Total Siswa</th>
                          <th>Jumlah Belum Absen</th>
                          <th style="width: 120px" class="text-center">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach($kelasBelumAbsen as $item)
                          <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong>{{ $item['kelas']->nama_kelas }}</strong></td>
                            <td>{{ $item['totalSiswa'] }} Siswa</td>
                            <td>
                              <span class="badge badge-danger px-2 py-1" style="font-size: 13px;">
                                {{ $item['jumlahBelumAbsen'] }} Belum Absen
                              </span>
                            </td>
                            <td class="text-center">
                              <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#modalDetail{{ $item['kelas']->id_kelas }}" title="Lihat detail siswa yang belum diinput">
                                <i class="fa fa-eye"></i> Detail
                              </button>

                              <!-- Modal Detail Siswa Belum Absen -->
                              <div class="modal fade text-left" id="modalDetail{{ $item['kelas']->id_kelas }}" tabindex="-1" role="dialog" aria-labelledby="modalLabel{{ $item['kelas']->id_kelas }}" aria-hidden="true">
                                <div class="modal-dialog modal-lg" role="document">
                                  <div class="modal-content">
                                    <div class="modal-header bg-info text-white">
                                      <h5 class="modal-title" id="modalLabel{{ $item['kelas']->id_kelas }}">
                                        <i class="fa fa-users mr-1"></i> Siswa Belum Absen - {{ $item['kelas']->nama_kelas }}
                                      </h5>
                                      <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                      <p class="mb-3">Daftar siswa di kelas <strong>{{ $item['kelas']->nama_kelas }}</strong> yang datanya belum masuk ke database absensi harian pada tanggal <strong>{{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y') }}</strong>:</p>
                                      
                                      <table class="table table-bordered table-striped table-hover">
                                        <thead class="bg-secondary text-white">
                                          <tr>
                                            <th style="width: 10px">No</th>
                                            <th>NIS</th>
                                            <th>Nama Siswa</th>
                                            <th>Status</th>
                                          </tr>
                                        </thead>
                                        <tbody>
                                          @foreach($item['siswaBelumAbsen'] as $siswa)
                                            <tr>
                                              <td>{{ $loop->iteration }}</td>
                                              <td>{{ $siswa->nis }}</td>
                                              <td><strong>{{ $siswa->nama_siswa }}</strong></td>
                                              <td><span class="badge badge-danger">Belum Diinput</span></td>
                                            </tr>
                                          @endforeach
                                        </tbody>
                                      </table>
                                    </div>
                                    <div class="modal-footer bg-light d-flex justify-content-between">
                                      <div>
                                        <strong>Total Siswa:</strong> {{ $item['totalSiswa'] }} | 
                                        <strong>Belum Absen:</strong> {{ $item['jumlahBelumAbsen'] }}
                                      </div>
                                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                @else
                  <div class="alert alert-success text-center shadow-sm">
                    <h5 class="font-weight-bold mb-2"><i class="fa fa-check-circle mr-1"></i> Absensi Selesai!</h5>
                    Seluruh siswa di semua kelas telah terisi kehadirannya untuk hari ini.
                  </div>
                @endif

              @endif
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
@endsection
