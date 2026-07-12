@extends($layout)
@section('content')
<style>
  /* Custom dashboard styling */
  .dashboard-card {
    border-radius: 12px !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04) !important;
    border: none !important;
    margin-bottom: 24px;
    background: #fff;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .dashboard-card:hover {
    box-shadow: 0 6px 20px rgba(0,0,0,0.06) !important;
  }
  .dashboard-card .card-header {
    background-color: #fff !important;
    border-bottom: 1px solid rgba(0,0,0,0.06) !important;
    padding: 18px 20px !important;
    border-top-left-radius: 12px !important;
    border-top-right-radius: 12px !important;
  }
  .dashboard-card .card-title {
    font-size: 1.05rem !important;
    font-weight: 700 !important;
    color: #2d3748 !important;
    margin: 0;
  }
  .dashboard-card .card-body {
    padding: 20px !important;
  }
  
  /* Tables */
  .table-custom {
    border-collapse: separate !important;
    border-spacing: 0 !important;
    width: 100% !important;
    margin-top: 5px !important;
  }
  .table-custom thead th {
    background-color: #f8fafc !important;
    color: #4a5568 !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    font-size: 0.75rem !important;
    letter-spacing: 0.05em !important;
    border-bottom: 2px solid #edf2f7 !important;
    border-top: none !important;
    padding: 12px 16px !important;
  }
  .table-custom tbody td {
    padding: 12px 16px !important;
    vertical-align: middle !important;
    border-bottom: 1px solid #edf2f7 !important;
    color: #4a5568 !important;
    font-size: 0.88rem !important;
  }
  .table-custom tbody tr:last-child td {
    border-bottom: none !important;
  }
  .table-custom tbody tr:hover td {
    background-color: #f7fafc !important;
  }
  
  /* Stat boxes */
  .small-box-custom {
    border-radius: 12px !important;
    border: none !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1) !important;
    overflow: hidden;
    margin-bottom: 24px;
    position: relative;
    color: #fff !important;
  }
  .small-box-custom:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
  }
  .small-box-custom .inner {
    padding: 20px !important;
    z-index: 2;
    position: relative;
  }
  .small-box-custom h3 {
    font-size: 2rem !important;
    font-weight: 800 !important;
    margin: 0 0 4px 0 !important;
    white-space: nowrap !important;
  }
  .small-box-custom p {
    font-size: 0.9rem !important;
    font-weight: 500 !important;
    margin-bottom: 0 !important;
    opacity: 0.85;
  }
  .small-box-custom .icon {
    position: absolute;
    right: 15px;
    top: 15px;
    font-size: 3.2rem;
    opacity: 0.15;
    transition: all 0.3s ease;
    z-index: 1;
  }
  .small-box-custom:hover .icon {
    transform: scale(1.1) rotate(5deg);
    opacity: 0.22;
  }
  .small-box-footer-custom {
    display: block;
    text-align: center;
    padding: 6px 0;
    color: rgba(255, 255, 255, 0.85) !important;
    background: rgba(0, 0, 0, 0.1);
    font-size: 0.8rem;
    text-decoration: none !important;
    z-index: 3;
    position: relative;
    font-weight: 500;
    transition: background-color 0.2s;
  }
  .small-box-footer-custom:hover {
    background: rgba(0, 0, 0, 0.18);
    color: #fff !important;
  }
  
  /* Tabs styling */
  .custom-tabs .nav-link {
    border: none !important;
    color: #718096 !important;
    font-weight: 600 !important;
    padding: 8px 16px !important;
    border-radius: 6px !important;
    margin-right: 5px;
    font-size: 0.85rem;
    background-color: transparent !important;
    transition: all 0.2s ease;
  }
  .custom-tabs .nav-link.active {
    background-color: #ebf8ff !important;
    color: #3182ce !important;
  }
  .custom-tabs .nav-link:hover:not(.active) {
    background-color: #f7fafc !important;
    color: #4a5568 !important;
  }

  /* Custom badging */
  .badge-soft-danger {
    background-color: #fed7d7 !important;
    color: #9b2c2c !important;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 6px;
  }
  .badge-soft-warning {
    background-color: #feebc8 !important;
    color: #c05621 !important;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 6px;
  }
  .badge-soft-success {
    background-color: #c6f6d5 !important;
    color: #22543d !important;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 6px;
  }
  .badge-soft-info {
    background-color: #e2e8f0 !important;
    color: #4a5568 !important;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 6px;
  }
  .badge-soft-purple {
    background-color: #e9d8fd !important;
    color: #553c9a !important;
    font-weight: 600;
    padding: 5px 10px;
    border-radius: 6px;
  }
</style>

<!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 font-weight-bold text-dark" style="font-size: 1.7rem;">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          <!-- Row 1: Summary Boxes -->
          <div class="row">
            <!-- Box 1: Jumlah Siswa -->
            <div class="col-lg col-md-4 col-sm-6 col-12">
              <div class="small-box-custom" style="background: linear-gradient(135deg, #3182ce 0%, #2b6cb0 100%);">
                <div class="inner">
                  <h3>{{$totalSiswa}}</h3>
                  <p>Jumlah Siswa</p>
                </div>
                <div class="icon">
                  <i class="fas fa-id-card"></i>
                </div>
                <a href="/admin/siswa" class="small-box-footer-custom">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            
            <!-- Box 2: Kehadiran Siswa -->
            <div class="col-lg col-md-4 col-sm-6 col-12">
              <div class="small-box-custom" style="background: linear-gradient(135deg, #38a169 0%, #2f855a 100%);">
                <div class="inner">
                  <h3>{{$totalHadir}}/{{$totalSiswa}}</h3>
                  <p>Kehadiran Siswa</p>
                </div>
                <div class="icon">
                  <i class="fas fa-user-check"></i>
                </div>
                <a href="/admin/absensi" class="small-box-footer-custom">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <!-- Box 3: Siswa Terlambat Hari Ini -->
            <div class="col-lg col-md-4 col-sm-6 col-12">
              <div class="small-box-custom" style="background: linear-gradient(135deg, #e53e3e 0%, #c53030 100%);">
                <div class="inner">
                  <h3>{{$totalTerlambat}}</h3>
                  <p>Siswa Terlambat</p>
                </div>
                <div class="icon">
                  <i class="fas fa-clock"></i>
                </div>
                <a href="#siswa-terlambat-section" class="small-box-footer-custom">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <!-- Box 4: Siswa Tidak Hadir -->
            <div class="col-lg col-md-6 col-sm-6 col-12">
              <div class="small-box-custom" style="background: linear-gradient(135deg, #dd6b20 0%, #c05621 100%);">
                <div class="inner">
                  <h3>{{$jumlahTidakHadirAll}}</h3>
                  <p>Siswa Tidak Hadir</p>
                </div>
                <div class="icon">
                  <i class="fas fa-user-times"></i>
                </div>
                <a href="/admin/siswa-tidak-hadir" class="small-box-footer-custom">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <!-- Box 5: Kehadiran Guru -->
            <div class="col-lg col-md-6 col-sm-6 col-12">
              <div class="small-box-custom" style="background: linear-gradient(135deg, #805ad5 0%, #6b46c1 100%);">
                <div class="inner">
                  <h3>{{$totalGuruHadir}}/{{$totalGuru}}</h3>
                  <p>Kehadiran Guru</p>
                </div>
                <div class="icon">
                  <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <a href="/admin/absensi_guru" class="small-box-footer-custom">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>

          <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-lg-6">
                <!-- Data Kehadiran Kelas Card -->
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-school text-primary mr-2"></i> Data Kehadiran Kelas (Belum Absen)</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-custom table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Nama Kelas</th>
                                        <th>Jumlah Siswa</th>
                                        <th>Jumlah Belum Absen</th>
                                        <th style="width: 80px; text-align: center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($kelasData as $data)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $data['kelas']->nama_kelas }}</strong></td>
                                        <td>{{ $data['totalSiswaKelas'] }} Siswa</td>
                                        <td><span class="badge badge-soft-warning">{{ $data['jumlahBelumAbsen'] }} Belum Absen</span></td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-success" data-toggle="modal" data-target="#modalSiswa{{$data['kelas']->id_kelas}}">
                                                <i class="fa fa-eye"></i> Detail
                                            </button>
                                            <!-- Modal -->
                                            <div class="modal fade" id="modalSiswa{{$data['kelas']->id_kelas}}" tabindex="-1" aria-labelledby="modalLabel{{$data['kelas']->id_kelas}}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: none;">
                                                        <div class="modal-header bg-light">
                                                            <h5 class="modal-title font-weight-bold" id="modalLabel{{$data['kelas']->id_kelas}}">
                                                                Siswa Belum Absen - {{ $data['kelas']->nama_kelas }}
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('admin.absensi.simpan') }}" method="POST">
                                                            @csrf
                                                            <div class="modal-body p-0" style="max-height: 400px; overflow-y: auto;">
                                                                <ul class="list-group list-group-flush">
                                                                    @foreach ($data['siswaBelumAbsen'] as $siswa)
                                                                        <li class="list-group-item d-flex justify-content-between align-items-center py-3">
                                                                            <span class="font-weight-600 text-dark">{{ $siswa->nama_siswa }}</span>
                                                                            <input type="hidden" name="id_siswa[]" value="{{ $siswa->id_siswa }}">
                                                                            <input type="hidden" name="id_kelas[]" value="{{ $data['kelas']->id_kelas }}">
                                                                            <input type="hidden" name="id_jurusan[]" value="{{ $data['id_jurusan'] }}">
                                                                            <div class="w-30">
                                                                                <select name="kehadiran[]" class="form-control form-control-sm">
                                                                                    <option value="hadir">Hadir</option>
                                                                                    <option value="sakit">Sakit</option>
                                                                                    <option value="izin">Izin</option>
                                                                                    <option value="alfa">Alfa</option>
                                                                                </select>
                                                                            </div>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                            <div class="modal-footer bg-light">
                                                                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                                                                <button type="submit" class="btn btn-primary btn-sm">Simpan Kehadiran</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Semua kelas sudah melakukan absensi hari ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- NEW: Siswa Terlambat Hari Ini Card -->
                <div id="siswa-terlambat-section" class="card dashboard-card">
                    <div class="card-header border-0">
                        <h5 class="card-title"><i class="fas fa-clock text-danger mr-2"></i> Siswa Terlambat Hari Ini</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-custom table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Jam Masuk</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($siswaTerlambat as $st)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $st->siswa->nama_siswa ?? 'Tidak Diketahui' }}</strong></td>
                                        <td>{{ $st->kelas->nama_kelas ?? '-' }}</td>
                                        <td><span class="badge badge-soft-danger">{{ $st->jam_masuk ?? '-' }}</span></td>
                                        <td>
                                            <small class="text-muted font-weight-500">
                                                {{ $st->keterangan }}
                                            </small>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-3">Tidak ada data keterlambatan hari ini.</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col-md-6 -->

            <!-- Kolom Kanan -->
            <div class="col-lg-6">
                <!-- Card Guru Piket Hari Ini -->
                <div class="card dashboard-card">
                    <div class="card-header">
                        <h5 class="card-title"><i class="fas fa-user-shield text-info mr-2"></i> Guru Piket Hari Ini ({{ $todayDayInd }})</h5>
                    </div>
                    <div class="card-body">
                        @if($guruPiketHariIni->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-custom table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Nama Guru</th>
                                        <th>Waktu Tugas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($guruPiketHariIni as $gp)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $gp->guru->nama_guru }}</strong></td>
                                        <td><span class="badge badge-soft-info"><i class="far fa-clock mr-1"></i> {{ $gp->waktu_awal }} - {{ $gp->waktu_akhir }}</span></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-light text-center text-muted mb-0" style="border: 1px dashed #e2e8f0; border-radius: 8px;">
                            Tidak ada jadwal guru piket untuk hari ini.
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Radar Siswa Kritis Card -->
                <div class="card dashboard-card">
                    <div class="card-header border-0">
                        <h5 class="card-title text-dark"><i class="fas fa-exclamation-triangle text-warning mr-2"></i> Radar Siswa Kritis (Pelanggaran Tertinggi)</h5>
                    </div>
                    <div class="card-body">
                        @if($siswaKritis->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-custom table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Poin</th>
                                        <th>Status SP</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswaKritis as $sk)
                                    @if($sk->siswa)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>{{ $sk->siswa->nama_siswa }}</strong></td>
                                        <td>{{ $sk->siswa->kelas->nama_kelas ?? '-' }}</td>
                                        <td><span class="badge badge-soft-danger">{{ $sk->total_skor }} Poin</span></td>
                                        <td>
                                            @if($sk->total_skor >= 75)
                                                <span class="badge badge-soft-danger">SP 3 (Orang Tua)</span>
                                            @elseif($sk->total_skor >= 50)
                                                <span class="badge badge-soft-warning">SP 2</span>
                                            @elseif($sk->total_skor >= 25)
                                                <span class="badge badge-soft-info">SP 1</span>
                                            @else
                                                <span class="badge badge-soft-success">Aman</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-light text-center text-muted mb-0" style="border: 1px dashed #e2e8f0; border-radius: 8px;">
                            Tidak ada data pelanggaran siswa yang tercatat.
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Combined TOP 5 Kehadiran Card (Siswa & Guru) -->
                <div class="card dashboard-card">
                    <div class="card-header d-flex p-0 align-items-center">
                        <h3 class="card-title p-3 font-weight-bold text-dark">
                            <i class="fas fa-trophy text-warning mr-2"></i> Kehadiran Terawal
                        </h3>
                        <ul class="nav nav-pills ml-auto p-2 custom-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#tab_siswa" data-toggle="tab">🏆 Siswa</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#tab_guru" data-toggle="tab">👨‍🏫 Guru</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="tab_siswa">
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">No</th>
                                                <th>Nama Siswa</th>
                                                <th>Nama Kelas</th>
                                                <th>Jam Datang</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($siswaTerajin as $index => $data)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><strong>{{ $data->siswa->nama_siswa }}</strong></td>
                                                <td>{{ $data->kelas->nama_kelas }}</td>
                                                <td><span class="badge badge-soft-success"><i class="far fa-clock mr-1"></i> {{ $data->jam_masuk }}</span></td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted py-3">Belum ada data kehadiran siswa hari ini.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="tab_guru">
                                <div class="table-responsive">
                                    <table class="table table-custom table-hover">
                                        <thead>
                                            <tr>
                                                <th style="width: 10px">No</th>
                                                <th>Nama Guru</th>
                                                <th>Jam Datang</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($guruTerajin as $index => $data)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><strong>{{ $data->guru->nama_guru }}</strong></td>
                                                <td><span class="badge badge-soft-success"><i class="far fa-clock mr-1"></i> {{ $data->jam_masuk }}</span></td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="3" class="text-center text-muted py-3">Belum ada data kehadiran guru hari ini.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- /.tab-pane -->
                        </div>
                        <!-- /.tab-content -->
                    </div>
                </div><!-- /.card -->
            </div>
            <!-- /.col-md-6 -->
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
<!-- AdminLTE App -->
<script src="{{ asset('lte/dist/js/adminlte.min.js') }}"></script>
<!-- SweetAlert2 -->
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
@endsection