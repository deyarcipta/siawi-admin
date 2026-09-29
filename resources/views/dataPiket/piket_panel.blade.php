@extends($layout)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row align-items-center mb-2">
            <div class="col-sm-7 d-flex align-items-center">
                <i class="fas fa-user-clock text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
                <div class="d-flex flex-column justify-content-center">
                    <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Panel Kerja Guru Piket</h1>
                    <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Pencatatan siswa terlambat, izin keluar kelas, dan penanganan ketertiban</p>
                </div>
            </div>
            <div class="col-sm-5">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="#">Guru Piket</a></li>
                    <li class="breadcrumb-item active">Panel Piket</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <!-- Roster Piket Card -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title text-dark font-weight-bold m-0"><i class="fas fa-calendar-check text-primary mr-2"></i> Jadwal Guru Piket Hari Ini ({{ $todayDayInd }})</h5>
                    </div>
                    <div class="card-body py-3">
                        <div class="d-flex flex-wrap">
                            @forelse ($piketHariIni as $gp)
                                <div class="badge badge-info p-2 mr-2 mb-2" style="font-size: 14px;">
                                    <i class="fas fa-user mr-1"></i> {{ $gp->guru?->nama_guru ?? 'Guru Telah Dihapus' }} 
                                    <span class="badge badge-light ml-1">{{ $gp->waktu_awal }} - {{ $gp->waktu_akhir }}</span>
                                </div>
                            @empty
                                <div class="text-muted">Tidak ada jadwal guru piket terdaftar untuk hari ini.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Kolom Kiri: Pencatatan Terlambat -->
            <div class="col-lg-7">
                <div class="card mb-4">
                    <div class="card-header d-flex align-items-center">
                        <h3 class="card-title text-dark font-weight-bold mb-0"><i class="fas fa-user-plus text-primary mr-2"></i> Catat Siswa Terlambat</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Cari nama siswa di bawah ini, lalu klik tombol <strong>Catat Terlambat</strong> untuk memproses absensi terlambat dan menambahkan point pelanggaran.</p>
                        
                        <table id="siswaPiketTable" class="table table-bordered table-striped table-hover mt-3">
                            <thead>
                                <tr>
                                    <th style="width: 10px">No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswa as $s)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td><strong>{{ $s->nama_siswa }}</strong><br><small class="text-muted">NIS: {{ $s->nis }}</small></td>
                                    <td>{{ $s->kelas->nama_kelas ?? '-' }}</td>
                                    <td>
                                        <form action="{{ route('admin.guruPiket.catatTerlambat') }}" method="POST" class="form-terlambat">
                                            @csrf
                                            <input type="hidden" name="id_siswa" value="{{ $s->id_siswa }}">
                                            <button type="submit" class="btn btn-danger btn-sm btn-block btn-terlambat">
                                                <i class="fas fa-clock mr-1"></i> Terlambat
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Daftar Terlambat Hari Ini -->
            <div class="col-lg-5">
                <div class="card card-success card-outline">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-list mr-2"></i> Daftar Keterlambatan Hari Ini</h3>
                    </div>
                    <div class="card-body">
                        <table id="terlambatHariIniTable" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 10px">No</th>
                                    <th>Siswa</th>
                                    <th>Jam</th>
                                    <th>Keterangan</th>
                                    <th style="width: 50px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($absensiTerlambat as $at)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <strong>{{ $at->siswa->nama_siswa }}</strong><br>
                                        <small class="text-muted">{{ $at->kelas->nama_kelas ?? '-' }}</small>
                                    </td>
                                    <td><span class="badge badge-warning">{{ $at->jam_masuk }}</span></td>
                                    <td><small>{{ $at->keterangan }}</small></td>
                                    <td>
                                        <form action="{{ route('admin.guruPiket.hapusTerlambat', $at->id_absensi) }}" method="POST" class="form-hapus-terlambat">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm btn-hapus-terlambat" title="Hapus Keterlambatan">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Belum ada siswa yang dicatat terlambat hari ini.</td>
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

@push('scripts')
<script>
    $(document).ready(function() {
        $(document).on('click', '.btn-terlambat', function(e) {
            e.preventDefault();
            console.log("Aksi Terlambat diklik!");
            
            var form = $(this).closest('form');
            // Ambil nama siswa dari baris tabel yang bersangkutan
            var tdSiswa = $(this).closest('tr').find('td:nth-child(2)');
            var namaSiswa = tdSiswa.find('strong').text() || tdSiswa.text().trim();
            console.log("Nama siswa: " + namaSiswa);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Catat Keterlambatan?',
                    text: 'Siswa ' + namaSiswa + ' akan dicatat terlambat, dan point pelanggaran (+5) akan ditambahkan otomatis.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Catat!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log("Menyerahkan form terlambat via Swal...");
                        form.submit();
                    }
                });
            } else {
                console.log("Swal tidak terdefinisi. Menggunakan fallback confirm...");
                if (confirm('Catat keterlambatan untuk ' + namaSiswa + '? Siswa akan dicatat terlambat dan poin pelanggaran (+5) akan ditambahkan.')) {
                    form.submit();
                }
            }
        });

        $(document).on('click', '.btn-hapus-terlambat', function(e) {
            e.preventDefault();
            console.log("Hapus Terlambat diklik!");
            
            var form = $(this).closest('form');
            var namaSiswa = $(this).closest('tr').find('td:nth-child(2) strong').text();
            console.log("Nama siswa: " + namaSiswa);

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Batalkan Keterlambatan?',
                    text: 'Catatan keterlambatan ' + namaSiswa + ' hari ini akan dihapus, dan poin pelanggarannya akan dibatalkan.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        console.log("Menyerahkan form hapus terlambat...");
                        form.submit();
                    }
                });
            } else {
                if (confirm('Batalkan keterlambatan untuk ' + namaSiswa + '? Poin pelanggaran hari ini akan dihapus.')) {
                    form.submit();
                }
            }
        });
    });
</script>
@endpush
@endsection
