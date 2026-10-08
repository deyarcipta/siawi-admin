@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-calendar-day text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Absensi Siswa</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Catatan presensi harian, riwayat terlewat, dan monitoring kehadiran</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Absensi Siswa</a></li>
            <li class="breadcrumb-item active">Absensi Harian</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->

  <div class="content">
    <div class="container-fluid">

      <!-- Filter & Mode Card -->
      <div class="card shadow-sm border-0 mb-3">
        <div class="card-body p-3">
          <form action="{{ url('/admin/absensi') }}" method="GET" class="row align-items-end" id="filter-absensi-form">
            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
              <label for="filter-tanggal" class="small font-weight-bold text-muted mb-1"><i class="fas fa-calendar-alt mr-1"></i> Pilih Tanggal</label>
              <input type="date" class="form-control form-control-sm" id="filter-tanggal" name="tanggal" value="{{ $tanggal }}">
            </div>

            <div class="col-md-3 col-sm-6 mb-2 mb-md-0">
              <label for="filter-kelas" class="small font-weight-bold text-muted mb-1"><i class="fas fa-school mr-1"></i> Pilih Kelas</label>
              <select class="form-control form-control-sm" id="filter-kelas" name="kelas">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelasList as $k)
                  <option value="{{ $k->id_kelas }}" {{ ($selectedKelas == $k->id_kelas) ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="col-md-3 col-sm-6 mb-2 mb-md-0 d-flex align-items-center" style="gap: 6px;">
              <button type="submit" class="btn btn-primary btn-sm px-3">
                <i class="fas fa-filter mr-1"></i> Filter
              </button>
              @if(!$isToday || $selectedKelas)
                <a href="{{ url('/admin/absensi') }}" class="btn btn-outline-secondary btn-sm" title="Kembali ke Hari Ini">
                  <i class="fas fa-undo mr-1"></i> Hari Ini
                </a>
              @endif
            </div>

            <div class="col-md-3 col-sm-6 text-md-right mt-2 mt-md-0">
              @if($isToday)
                <span class="badge badge-success px-3 py-2 shadow-sm font-weight-normal" style="font-size: 0.85rem;">
                  <i class="fas fa-circle text-white mr-1" style="font-size: 0.55rem;"></i> <strong>Live Mode</strong> (Auto-Refresh 60s)
                </span>
              @else
                <span class="badge badge-warning text-dark px-3 py-2 shadow-sm font-weight-normal" style="font-size: 0.85rem;">
                  <i class="fas fa-history mr-1"></i> <strong>Riwayat Terlewat</strong> (Auto-Refresh Nonaktif)
                </span>
              @endif
            </div>
          </form>
        </div>
      </div>

      <div class="row">
        <div class="col-lg-12">
          <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap bg-white py-3">
              <h3 class="card-title text-dark font-weight-bold mb-0">
                <i class="fas fa-clipboard-list text-primary mr-2"></i> Presensi: {{ $hari }}, {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                @if($selectedKelas)
                  @php
                    $activeKelas = $kelasList->firstWhere('id_kelas', $selectedKelas);
                  @endphp
                  <span class="badge badge-info ml-2 font-weight-normal">{{ $activeKelas ? $activeKelas->nama_kelas : '' }}</span>
                @endif
              </h3>
              
              <div class="d-flex align-items-center ml-auto flex-wrap" style="gap: 8px;">
                <a href="{{ url('/admin/downloadAbsensiHarianSiswa?tanggal=' . $tanggal . ($selectedKelas ? '&kelas=' . $selectedKelas : '')) }}" class="btn btn-success btn-sm my-1 shadow-sm">
                  <i class="fa fa-file-excel mr-1"></i> Download Data
                </a>
                <button type="button" class="btn btn-primary btn-sm my-1 shadow-sm" data-toggle="modal" data-target="#tambahKehadiranModal">
                  <i class="fa fa-plus mr-1"></i> Tambah Kehadiran
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="table-responsive">
                <table id="example2" class="table table-bordered table-hover table-striped">
                  <thead class="thead-light">
                    <tr>
                      <th style="width: 10px" class="text-center">No</th>
                      <th>Nama Siswa</th>
                      <th>Kelas</th>
                      <th>Jam Masuk</th>
                      <th>Jam Pulang</th>
                      <th class="text-center">Status</th>
                      <th>Keterangan</th>
                      <th style="width: 110px" class="text-center">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                  @forelse ($absensiSiswa as $data)
                    @php
                      $statusLower = strtolower(trim($data->kehadiran ?? ''));
                      $badgeClass = 'badge-secondary';
                      if ($statusLower === 'hadir' || $statusLower === 'masuk') {
                          $badgeClass = 'badge-success';
                      } elseif ($statusLower === 'izin') {
                          $badgeClass = 'badge-info';
                      } elseif ($statusLower === 'sakit') {
                          $badgeClass = 'badge-warning';
                      } elseif ($statusLower === 'alfa') {
                          $badgeClass = 'badge-danger';
                      }
                    @endphp
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td class="font-weight-bold text-dark">{{ $data->siswa->nama_siswa ?? '-' }}</td>
                      <td><span class="badge badge-light border text-dark">{{ $data->siswa->kelas->nama_kelas ?? ($data->kelas->nama_kelas ?? '-') }}</span></td>
                      <td>{{ $data->jam_masuk ?? '-' }}</td>
                      <td>{{ $data->jam_pulang ?? '-' }}</td>
                      <td class="text-center">
                        <span class="badge {{ $badgeClass }} px-2 py-1" style="font-size: 0.85rem;">
                          {{ ucfirst($data->kehadiran) }}
                        </span>
                      </td>
                      <td>
                        @if($data->keterangan && $data->keterangan !== '-')
                          <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>{{ $data->keterangan }}</small>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td class="text-center">
                        <div class="d-inline-flex" style="gap: 4px;">
                          <button type="button" class="btn btn-warning btn-sm text-white" title="Edit / Koreksi Kehadiran" data-toggle="modal" data-target="#editKehadiran{{ $data->id_absensi }}">
                            <i class="fa fa-edit"></i>
                          </button>
                          <form action="{{ route('admin.absensi.destroy', $data->id_absensi) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm btn-delete" title="Hapus Data">
                              <i class="fa fa-trash"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>

                    <!-- Modal Edit Kehadiran -->
                    <div class="modal fade" id="editKehadiran{{ $data->id_absensi }}" tabindex="-1" aria-labelledby="editModalLabel{{ $data->id_absensi }}" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content shadow">
                          <div class="modal-header bg-warning text-dark">
                            <h5 class="modal-title font-weight-bold" id="editModalLabel{{ $data->id_absensi }}">
                              <i class="fas fa-edit mr-1"></i> Edit & Koreksi Kehadiran
                            </h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form action="{{ url('/admin/edit-kehadiran/' . $data->id_absensi) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                              <div class="form-group">
                                <label class="font-weight-bold">Nama Siswa</label>
                                <input type="text" class="form-control" value="{{ $data->siswa->nama_siswa ?? '-' }}" readonly>
                              </div>
                              <div class="form-group">
                                <label class="font-weight-bold">Tanggal Presensi</label>
                                <input type="text" class="form-control bg-light" value="{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('l, d F Y') }}" readonly>
                              </div>
                              <div class="form-group">
                                <label for="kehadiran_{{ $data->id_absensi }}" class="font-weight-bold">Status Kehadiran <span class="text-danger">*</span></label>
                                <select name="kehadiran" id="kehadiran_{{ $data->id_absensi }}" class="form-control" required>
                                  <option value="Hadir" {{ strtolower($data->kehadiran) == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                  <option value="Izin" {{ strtolower($data->kehadiran) == 'izin' ? 'selected' : '' }}>Izin</option>
                                  <option value="Sakit" {{ strtolower($data->kehadiran) == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                  <option value="Alfa" {{ strtolower($data->kehadiran) == 'alfa' ? 'selected' : '' }}>Alfa</option>
                                </select>
                              </div>
                              <div class="row">
                                <div class="col-6">
                                  <div class="form-group">
                                    <label class="font-weight-bold">Jam Masuk</label>
                                    <input type="text" name="jam_masuk" class="form-control" value="{{ $data->jam_masuk ?? '-' }}" placeholder="07:00:00 atau -">
                                  </div>
                                </div>
                                <div class="col-6">
                                  <div class="form-group">
                                    <label class="font-weight-bold">Jam Pulang</label>
                                    <input type="text" name="jam_pulang" class="form-control" value="{{ $data->jam_pulang ?? '-' }}" placeholder="15:00:00 atau -">
                                  </div>
                                </div>
                              </div>
                              <div class="form-group">
                                <label class="font-weight-bold">Keterangan / Catatan</label>
                                <input type="text" name="keterangan" class="form-control" value="{{ $data->keterangan == '-' ? '' : $data->keterangan }}" placeholder="Contoh: Surat Dokter / Dispensasi Kegiatan / Terlambat">
                                <small class="text-muted">Kosongkan atau beri tanda - jika tidak ada catatan khusus.</small>
                              </div>
                            </div>
                            <div class="modal-footer bg-light">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                              <button type="submit" class="btn btn-primary font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center py-4 text-muted">
                        <i class="fas fa-info-circle fa-2x mb-2 d-block text-secondary"></i>
                        Tidak ada data absensi untuk tanggal <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</strong>
                        @if($selectedKelas)
                          pada kelas yang dipilih.
                        @endif
                      </td>
                    </tr>
                  @endforelse
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card --> 
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Kehadiran -->
  <div class="modal fade" id="tambahKehadiranModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content shadow">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title font-weight-bold" id="modalLabel">
            <i class="fas fa-user-plus mr-1"></i> Tambah / Input Kehadiran
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('admin.absensi.tambah-kehadiran') }}" method="POST" id="form-tambah-kehadiran-siswa">
          @csrf
          <div class="modal-body">
            <div class="form-group">
              <label for="input_tanggal" class="font-weight-bold">Tanggal Absensi <span class="text-danger">*</span></label>
              <input type="date" name="tanggal" id="input_tanggal" class="form-control" value="{{ $tanggal }}" required>
              <small class="text-muted">Ubah tanggal jika ingin menginput data kehadiran yang terlewat pada tanggal sebelumnya.</small>
            </div>
            <div class="form-group">
              <label for="id_kelas" class="font-weight-bold">Pilih Kelas <span class="text-danger">*</span></label>
              <select name="id_kelas" id="id_kelas" class="form-control" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelasList as $kelas)
                  <option value="{{ $kelas->id_kelas }}" {{ $selectedKelas == $kelas->id_kelas ? 'selected' : '' }}>
                    {{ $kelas->nama_kelas }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="id_siswa" class="font-weight-bold">Nama Siswa <span class="text-danger">*</span></label>
              <select name="id_siswa" id="id_siswa" class="form-control" required>
                <option value="">-- Pilih Siswa (Pilih Kelas Dulu) --</option>
                @foreach($siswaList as $siswa)
                  <option value="{{ $siswa->id_siswa }}">{{ $siswa->nama_siswa }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="kehadiran_baru" class="font-weight-bold">Status Kehadiran <span class="text-danger">*</span></label>
              <select name="kehadiran" id="kehadiran_baru" class="form-control" required>
                <option value="Hadir">Hadir</option>
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
                <option value="Alfa">Alfa</option>
              </select>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="font-weight-bold">Jam Masuk (Opsional)</label>
                  <input type="text" name="jam_masuk" class="form-control" placeholder="Contoh: 07:15:00">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="font-weight-bold">Jam Pulang (Opsional)</label>
                  <input type="text" name="jam_pulang" class="form-control" placeholder="Contoh: 15:00:00">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label class="font-weight-bold">Keterangan (Opsional)</label>
              <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Sakit tipus (ada surat dokter) / Izin">
            </div>
          </div>
          <div class="modal-footer bg-light">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success font-weight-bold" id="btn-submit-kehadiran-siswa">
              <i class="fas fa-save mr-1"></i> Simpan Kehadiran
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
  <script>
    $(document).ready(function() {
      // Dynamic dropdown Siswa based on Kelas
      $('#id_kelas').on('change', function() {
        var idKelas = $(this).val();
        if (idKelas) {
          $.ajax({
            url: '/admin/get-siswa-by-kelas/' + idKelas,
            type: "GET",
            dataType: "json",
            success: function(data) {
              $('#id_siswa').empty();
              $('#id_siswa').append('<option value="">-- Pilih Siswa --</option>');
              $.each(data, function(key, siswa) {
                $('#id_siswa').append('<option value="'+ siswa.id_siswa +'">'+ siswa.nama_siswa +'</option>');
              });
            }
          });
        } else {
          $('#id_siswa').empty();
          $('#id_siswa').append('<option value="">-- Pilih Siswa (Pilih Kelas Dulu) --</option>');
        }
      });
    });

    // Auto-reload mekanisme:
    // HANYA aktif jika tanggal yang difilter adalah hari ini ($isToday === true)
    // dan TIDAK ADA modal yang sedang terbuka / aktif
    var isToday = {{ $isToday ? 'true' : 'false' }};
    if (isToday) {
      setInterval(function() {
        var isModalOpen = $('.modal.show').length > 0 || $('body').hasClass('modal-open');
        if (!isModalOpen) {
          window.location.reload();
        }
      }, 60000);
    }

    // Mencegah double submit/double click
    const formKehadiranSiswa = document.getElementById('form-tambah-kehadiran-siswa');
    if (formKehadiranSiswa) {
      formKehadiranSiswa.addEventListener('submit', function() {
        const btnSubmit = document.getElementById('btn-submit-kehadiran-siswa');
        if (btnSubmit) {
          btnSubmit.disabled = true;
          btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm mr-1" role="status"></span> Menyimpan...';
        }
      });
    }

    document.addEventListener('DOMContentLoaded', function () {
      const deleteButtons = document.querySelectorAll('.btn-delete');

      deleteButtons.forEach(function (button) {
        button.addEventListener('click', function (e) {
          const form = this.closest('form');

          Swal.fire({
            title: 'Yakin ingin menghapus?',
            text: "Data absensi yang dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              form.submit();
            }
          });
        });
      });
    });
  </script>
@endpush
