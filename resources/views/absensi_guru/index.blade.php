@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row align-items-center mb-2">
        <div class="col-sm-7 d-flex align-items-center">
          <i class="fas fa-calendar-check text-primary mr-3" style="font-size: 2rem; flex-shrink: 0;"></i>
          <div class="d-flex flex-column justify-content-center">
            <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2;">Data Absensi Harian Guru</h1>
            <p class="text-muted mt-1 mb-0" style="line-height: 1.2; font-size: 0.85rem;">Log presensi kehadiran dan jam pulang guru realtime harian</p>
          </div>
        </div>
        <div class="col-sm-5">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="#">Absensi Guru</a></li>
            <li class="breadcrumb-item active">Absensi Harian</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-header -->
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
        <div class="card">
          <div class="card-header d-flex align-items-center">
            <h3 class="card-title text-dark font-weight-bold mb-0">
              <i class="fas fa-table text-primary mr-2"></i> Absensi Guru - Hari {{ $hari }}
            </h3>
            <a href="{{ url('/admin/downloadAbsensiHarian') }}" class="btn btn-success btn-sm ml-auto"><i class="fas fa-download mr-1"></i> Download Data</a>
            <button type="button" class="btn btn-primary btn-sm ml-2" data-toggle="modal" data-target="#tambahKehadiranModal">
                <i class="fas fa-plus mr-1"></i> Tambah Kehadiran
            </button>
        </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example2" class="table table-bordered table-hover table-striped">
              <thead>
              <tr>
                <th style="width: 10px">No</th>
                <th>Nama Guru</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Status</th>
              </tr>
              </thead>
              <tbody>
              @foreach ($absensiGuru as $data)
              <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$data->guru?->nama_guru ?? 'Guru Telah Dihapus'}}</td>
                <td>{{$data->jam_masuk ?? '-'}}</td>
                <td>{{$data->jam_pulang ?? '-'}}</td>
                <td>{{$data->kehadiran}}</td>
              </tr>
              @endforeach
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card --> 
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Tambah Kehadiran Guru -->
  <div class="modal fade" id="tambahKehadiranModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <form action="{{ url('/admin/tambah-kehadiran') }}" method="POST" id="form-tambah-kehadiran">
        @csrf
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
          <div class="modal-header text-white px-4 py-3" style="background: linear-gradient(135deg, #1d72fe 0%, #0b1f3a 100%);">
            <h5 class="modal-title font-weight-bold" id="modalLabel">
              <i class="fas fa-user-plus mr-2"></i> Tambah Kehadiran Guru
            </h5>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close" style="opacity: 0.85;">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body p-4">
            <div class="form-group mb-3">
              <label for="id_guru" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Nama Guru <span class="text-danger">*</span></label>
              <select name="id_guru" id="id_guru" class="form-control" required style="border-radius: 8px; height: 42px;">
                <option value="">-- Pilih Guru --</option>
                @foreach($guruList as $guru)
                  <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group mb-0">
              <label for="kehadiran" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Status Kehadiran <span class="text-danger">*</span></label>
              <select name="kehadiran" id="kehadiran" class="form-control" required style="border-radius: 8px; height: 42px;">
                <option value="Hadir">Hadir</option>
                <option value="Izin">Izin</option>
                <option value="Sakit">Sakit</option>
              </select>
            </div>
          </div>
          <div class="modal-footer bg-light py-3 px-4">
            <button type="button" class="btn btn-secondary px-3" data-dismiss="modal" style="border-radius: 8px;">Batal</button>
            <button type="submit" class="btn btn-primary px-4 font-weight-bold shadow-sm" id="btn-submit-kehadiran" style="border-radius: 8px;">
              <i class="fas fa-save mr-1"></i> Simpan Kehadiran
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection

@push('scripts')
  <script>
    // Melakukan refresh setiap 10 detik
    setInterval(function() {
      // Hanya reload jika modal tidak sedang terbuka agar tidak merusak input user
      if (!$('#tambahKehadiranModal').hasClass('show') && !$('.modal').hasClass('show')) {
          window.location.reload();
      }
    }, 10000);

    // Mencegah double submit/double click
    const formKehadiran = document.getElementById('form-tambah-kehadiran');
    if (formKehadiran) {
        formKehadiran.addEventListener('submit', function() {
            const btnSubmit = document.getElementById('btn-submit-kehadiran');
            if (btnSubmit) {
                btnSubmit.disabled = true;
                btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm mr-1" role="status"></span> Menyimpan...';
            }
        });
    }
  </script>
@endpush
