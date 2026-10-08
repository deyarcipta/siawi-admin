@extends($layout)
@section('content')

@push('styles')
<style>
  .absensi-toolbar-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 2px 6px -1px rgba(0, 0, 0, 0.04), 0 2px 4px -2px rgba(0, 0, 0, 0.02);
    margin-bottom: 1.25rem;
  }
  .filter-input-group {
    border-radius: 8px;
    overflow: hidden;
  }
  .filter-input-group .input-group-text {
    background-color: #f8fafc;
    border: 1px solid #cbd5e1;
    border-right: none;
    color: #64748b;
    padding: 0.45rem 0.75rem;
    font-size: 0.875rem;
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
  }
  .filter-input-group .form-control,
  .filter-input-group .custom-select {
    border: 1px solid #cbd5e1;
    color: #1e293b;
    font-weight: 500;
    font-size: 0.875rem;
    height: 38px;
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
    transition: all 0.2s ease;
  }
  .filter-input-group .form-control:focus,
  .filter-input-group .custom-select:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
  }
  .btn-filter-primary {
    height: 38px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0 16px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #2563eb;
    border-color: #2563eb;
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
    transition: all 0.2s;
  }
  .btn-filter-primary:hover {
    background: #1d4ed8;
    border-color: #1d4ed8;
    color: #ffffff;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
  }
  .btn-filter-reset {
    height: 38px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 0.875rem;
    padding: 0 14px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    color: #475569;
    transition: all 0.2s;
  }
  .btn-filter-reset:hover {
    background: #e2e8f0;
    color: #1e293b;
  }
  .status-pill-live {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    border-radius: 50px;
    background: #ecfdf5;
    border: 1px solid #a7f3d0;
    color: #065f46;
    font-size: 0.825rem;
    font-weight: 600;
    letter-spacing: 0.01em;
  }
  .status-pill-past {
    display: inline-flex;
    align-items: center;
    padding: 6px 14px;
    border-radius: 50px;
    background: #fffbeb;
    border: 1px solid #fde68a;
    color: #92400e;
    font-size: 0.825rem;
    font-weight: 600;
    letter-spacing: 0.01em;
  }
  .pulse-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background-color: #10b981;
    margin-right: 8px;
    position: relative;
    display: inline-block;
  }
  .pulse-dot::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    background-color: #10b981;
    border-radius: 50%;
    animation: livePulse 2s infinite ease-in-out;
  }
  @keyframes livePulse {
    0% { transform: scale(1); opacity: 0.8; }
    50% { transform: scale(2.3); opacity: 0; }
    100% { transform: scale(1); opacity: 0; }
  }
  .badge-soft-success {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
  }
  .badge-soft-info {
    background-color: #e0f2fe;
    color: #0369a1;
    border: 1px solid #bae6fd;
  }
  .badge-soft-warning {
    background-color: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
  }
  .badge-soft-danger {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
  }
</style>
@endpush

  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-calendar-day text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Absensi Siswa</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Catatan presensi harian, riwayat kehadiran terlewat, dan live monitoring</p>
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

      <!-- Professional Filter Toolbar Card -->
      <div class="card absensi-toolbar-card">
        <div class="card-body p-3">
          <form action="{{ url('/admin/absensi') }}" method="GET" class="d-flex flex-wrap align-items-center justify-content-between" style="gap: 12px;" id="filter-absensi-form">
            
            <!-- Left Filter Controls -->
            <div class="d-flex flex-wrap align-items-center" style="gap: 10px; flex: 1; min-width: 280px;">
              
              <!-- Input Tanggal -->
              <div class="input-group filter-input-group" style="width: 205px;" title="Pilih Tanggal Presensi">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-calendar-alt text-primary"></i></span>
                </div>
                <input type="date" class="form-control" id="filter-tanggal" name="tanggal" value="{{ $tanggal }}">
              </div>

              <!-- Dropdown Kelas -->
              <div class="input-group filter-input-group" style="width: 200px;" title="Filter berdasarkan Kelas">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-school text-primary"></i></span>
                </div>
                <select class="form-control custom-select" id="filter-kelas" name="kelas">
                  <option value="">-- Semua Kelas --</option>
                  @foreach($kelasList as $k)
                    <option value="{{ $k->id_kelas }}" {{ ($selectedKelas == $k->id_kelas) ? 'selected' : '' }}>
                      {{ $k->nama_kelas }}
                    </option>
                  @endforeach
                </select>
              </div>

              <!-- Submit & Reset Buttons -->
              <div class="d-inline-flex" style="gap: 6px;">
                <button type="submit" class="btn btn-filter-primary">
                  <i class="fas fa-filter"></i> Filter
                </button>

                @if(!$isToday || $selectedKelas)
                  <a href="{{ url('/admin/absensi') }}" class="btn btn-filter-reset" title="Kembali ke Presensi Hari Ini">
                    <i class="fas fa-undo"></i> Hari Ini
                  </a>
                @endif
              </div>

            </div>

            <!-- Right Status Indicator -->
            <div class="d-flex align-items-center">
              @if($isToday)
                <div class="status-pill-live" title="Auto-refresh aktif untuk memantau tap RFID kehadiran secara realtime">
                  <span class="pulse-dot"></span>
                  <span>Live Mode <span class="font-weight-normal text-muted ml-1">(Auto-Refresh 60s)</span></span>
                </div>
              @else
                <div class="status-pill-past" title="Auto-refresh dinonaktifkan saat melihat riwayat terlewat">
                  <i class="fas fa-history mr-2 text-warning"></i>
                  <span>Riwayat Terlewat <span class="font-weight-normal text-muted ml-1">(Auto-Refresh Nonaktif)</span></span>
                </div>
              @endif
            </div>

          </form>
        </div>
      </div>

      <!-- Main Data Table Card -->
      <div class="row">
        <div class="col-lg-12">
          <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header d-flex align-items-center justify-content-between flex-wrap bg-white py-3 border-bottom">
              <h3 class="card-title text-dark font-weight-bold mb-0">
                <i class="fas fa-clipboard-list text-primary mr-2"></i> Presensi: {{ $hari }}, {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                @if($selectedKelas)
                  @php
                    $activeKelas = $kelasList->firstWhere('id_kelas', $selectedKelas);
                  @endphp
                  <span class="badge badge-info ml-2 font-weight-normal px-2 py-1">{{ $activeKelas ? $activeKelas->nama_kelas : '' }}</span>
                @endif
              </h3>
              
              <div class="d-flex align-items-center ml-auto flex-wrap" style="gap: 8px;">
                <a href="{{ url('/admin/downloadAbsensiHarianSiswa?tanggal=' . $tanggal . ($selectedKelas ? '&kelas=' . $selectedKelas : '')) }}" class="btn btn-success btn-sm my-1 shadow-sm font-weight-bold">
                  <i class="fa fa-file-excel mr-1"></i> Download Data
                </a>
                <button type="button" class="btn btn-primary btn-sm my-1 shadow-sm font-weight-bold" data-toggle="modal" data-target="#tambahKehadiranModal">
                  <i class="fa fa-plus mr-1"></i> Tambah Kehadiran
                </button>
              </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
              <div class="table-responsive">
                <table id="example2" class="table table-hover table-striped mb-0">
                  <thead class="bg-light">
                    <tr>
                      <th style="width: 50px" class="text-center font-weight-bold">No</th>
                      <th class="font-weight-bold">Nama Siswa</th>
                      <th class="font-weight-bold">Kelas</th>
                      <th class="font-weight-bold">Jam Masuk</th>
                      <th class="font-weight-bold">Jam Pulang</th>
                      <th class="text-center font-weight-bold">Status</th>
                      <th class="font-weight-bold">Keterangan</th>
                      <th style="width: 110px" class="text-center font-weight-bold">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                  @forelse ($absensiSiswa as $data)
                    @php
                      $statusLower = strtolower(trim($data->kehadiran ?? ''));
                      $badgeClass = 'badge-secondary';
                      if ($statusLower === 'hadir' || $statusLower === 'masuk') {
                          $badgeClass = 'badge-soft-success';
                      } elseif ($statusLower === 'izin') {
                          $badgeClass = 'badge-soft-info';
                      } elseif ($statusLower === 'sakit') {
                          $badgeClass = 'badge-soft-warning';
                      } elseif ($statusLower === 'alfa') {
                          $badgeClass = 'badge-soft-danger';
                      }
                    @endphp
                    <tr>
                      <td class="text-center align-middle">{{ $loop->iteration }}</td>
                      <td class="font-weight-bold text-dark align-middle">{{ $data->siswa->nama_siswa ?? '-' }}</td>
                      <td class="align-middle"><span class="badge badge-light border text-dark font-weight-normal px-2 py-1">{{ $data->siswa->kelas->nama_kelas ?? ($data->kelas->nama_kelas ?? '-') }}</span></td>
                      <td class="align-middle">{{ $data->jam_masuk ?? '-' }}</td>
                      <td class="align-middle">{{ $data->jam_pulang ?? '-' }}</td>
                      <td class="text-center align-middle">
                        <span class="badge {{ $badgeClass }} px-3 py-1 font-weight-bold" style="font-size: 0.825rem; border-radius: 20px;">
                          {{ ucfirst($data->kehadiran) }}
                        </span>
                      </td>
                      <td class="align-middle">
                        @if($data->keterangan && $data->keterangan !== '-')
                          <small class="text-muted"><i class="fas fa-info-circle mr-1"></i>{{ $data->keterangan }}</small>
                        @else
                          <span class="text-muted">-</span>
                        @endif
                      </td>
                      <td class="text-center align-middle">
                        <div class="d-inline-flex" style="gap: 4px;">
                          <button type="button" class="btn btn-warning btn-sm text-white shadow-none" title="Edit / Koreksi Kehadiran" data-toggle="modal" data-target="#editKehadiran{{ $data->id_absensi }}">
                            <i class="fa fa-edit"></i>
                          </button>
                          <form action="{{ route('admin.absensi.destroy', $data->id_absensi) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-sm btn-delete shadow-none" title="Hapus Data">
                              <i class="fa fa-trash"></i>
                            </button>
                          </form>
                        </div>
                      </td>
                    </tr>

                    <!-- Modal Edit Kehadiran -->
                    <div class="modal fade" id="editKehadiran{{ $data->id_absensi }}" tabindex="-1" aria-labelledby="editModalLabel{{ $data->id_absensi }}" aria-hidden="true">
                      <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content shadow border-0" style="border-radius: 12px; overflow: hidden;">
                          <div class="modal-header bg-warning text-dark py-3">
                            <h5 class="modal-title font-weight-bold" id="editModalLabel{{ $data->id_absensi }}">
                              <i class="fas fa-edit mr-2"></i> Edit & Koreksi Kehadiran
                            </h5>
                            <button type="button" class="close text-dark" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>
                          <form action="{{ url('/admin/edit-kehadiran/' . $data->id_absensi) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-body p-4">
                              <div class="form-group">
                                <label class="font-weight-bold text-dark">Nama Siswa</label>
                                <input type="text" class="form-control" value="{{ $data->siswa->nama_siswa ?? '-' }}" readonly style="background-color: #f8fafc;">
                              </div>
                              <div class="form-group">
                                <label class="font-weight-bold text-dark">Tanggal Presensi</label>
                                <input type="text" class="form-control" value="{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('l, d F Y') }}" readonly style="background-color: #f8fafc;">
                              </div>
                              <div class="form-group">
                                <label for="kehadiran_{{ $data->id_absensi }}" class="font-weight-bold text-dark">Status Kehadiran <span class="text-danger">*</span></label>
                                <select name="kehadiran" id="kehadiran_{{ $data->id_absensi }}" class="form-control custom-select" required>
                                  <option value="Hadir" {{ strtolower($data->kehadiran) == 'hadir' ? 'selected' : '' }}>Hadir</option>
                                  <option value="Izin" {{ strtolower($data->kehadiran) == 'izin' ? 'selected' : '' }}>Izin</option>
                                  <option value="Sakit" {{ strtolower($data->kehadiran) == 'sakit' ? 'selected' : '' }}>Sakit</option>
                                  <option value="Alfa" {{ strtolower($data->kehadiran) == 'alfa' ? 'selected' : '' }}>Alfa</option>
                                </select>
                              </div>
                              <div class="row">
                                <div class="col-6">
                                  <div class="form-group">
                                    <label class="font-weight-bold text-dark">Jam Masuk</label>
                                    <input type="text" name="jam_masuk" class="form-control" value="{{ $data->jam_masuk ?? '-' }}" placeholder="07:00:00 atau -">
                                  </div>
                                </div>
                                <div class="col-6">
                                  <div class="form-group">
                                    <label class="font-weight-bold text-dark">Jam Pulang</label>
                                    <input type="text" name="jam_pulang" class="form-control" value="{{ $data->jam_pulang ?? '-' }}" placeholder="15:00:00 atau -">
                                  </div>
                                </div>
                              </div>
                              <div class="form-group mb-0">
                                <label class="font-weight-bold text-dark">Keterangan / Catatan</label>
                                <input type="text" name="keterangan" class="form-control" value="{{ $data->keterangan == '-' ? '' : $data->keterangan }}" placeholder="Contoh: Surat Dokter / Izin Dispensasi / Terlambat">
                                <small class="text-muted mt-1 d-block">Kosongkan atau beri tanda - jika tidak ada catatan khusus.</small>
                              </div>
                            </div>
                            <div class="modal-footer bg-light px-4 py-3">
                              <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Batal</button>
                              <button type="submit" class="btn btn-primary px-4 font-weight-bold">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                              </button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  @empty
                    <tr>
                      <td colspan="8" class="text-center py-5 text-muted">
                        <i class="fas fa-clipboard fa-3x mb-3 d-block text-secondary" style="opacity: 0.4;"></i>
                        <p class="font-weight-bold mb-1" style="font-size: 1.05rem;">Tidak Ada Data Absensi</p>
                        <p class="text-muted small mb-0">
                          Tidak ditemukan data absensi untuk tanggal <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</strong>
                          @if($selectedKelas)
                            pada kelas yang dipilih.
                          @endif
                        </p>
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
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content shadow border-0" style="border-radius: 12px; overflow: hidden;">
        <div class="modal-header bg-primary text-white py-3">
          <h5 class="modal-title font-weight-bold" id="modalLabel">
            <i class="fas fa-user-plus mr-2"></i> Tambah / Input Kehadiran
          </h5>
          <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form action="{{ route('admin.absensi.tambah-kehadiran') }}" method="POST" id="form-tambah-kehadiran-siswa">
          @csrf
          <div class="modal-body p-4">
            <div class="form-group">
              <label for="input_tanggal" class="font-weight-bold text-dark">Tanggal Absensi <span class="text-danger">*</span></label>
              <input type="date" name="tanggal" id="input_tanggal" class="form-control" value="{{ $tanggal }}" required>
              <small class="text-muted mt-1 d-block">Ubah tanggal jika ingin menginput data kehadiran yang terlewat pada tanggal sebelumnya.</small>
            </div>
            <div class="form-group">
              <label for="id_kelas" class="font-weight-bold text-dark">Pilih Kelas <span class="text-danger">*</span></label>
              <select name="id_kelas" id="id_kelas" class="form-control custom-select" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach($kelasList as $kelas)
                  <option value="{{ $kelas->id_kelas }}" {{ $selectedKelas == $kelas->id_kelas ? 'selected' : '' }}>
                    {{ $kelas->nama_kelas }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="id_siswa" class="font-weight-bold text-dark">Nama Siswa <span class="text-danger">*</span></label>
              <select name="id_siswa" id="id_siswa" class="form-control custom-select" required>
                <option value="">-- Pilih Siswa (Pilih Kelas Dulu) --</option>
                @foreach($siswaList as $siswa)
                  <option value="{{ $siswa->id_siswa }}">{{ $siswa->nama_siswa }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="kehadiran_baru" class="font-weight-bold text-dark">Status Kehadiran <span class="text-danger">*</span></label>
              <select name="kehadiran" id="kehadiran_baru" class="form-control custom-select" required>
                <option value="Hadir">Hadir</option>
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
                <option value="Alfa">Alfa</option>
              </select>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="font-weight-bold text-dark">Jam Masuk (Opsional)</label>
                  <input type="text" name="jam_masuk" class="form-control" placeholder="Contoh: 07:15:00">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="font-weight-bold text-dark">Jam Pulang (Opsional)</label>
                  <input type="text" name="jam_pulang" class="form-control" placeholder="Contoh: 15:00:00">
                </div>
              </div>
            </div>
            <div class="form-group mb-0">
              <label class="font-weight-bold text-dark">Keterangan (Opsional)</label>
              <input type="text" name="keterangan" class="form-control" placeholder="Contoh: Sakit tipus (ada surat dokter) / Izin">
            </div>
          </div>
          <div class="modal-footer bg-light px-4 py-3">
            <button type="button" class="btn btn-secondary px-3" data-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success px-4 font-weight-bold" id="btn-submit-kehadiran-siswa">
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
