@extends($layout)
@section('content')
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-calendar-alt"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Ubah Jadwal Praktik</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Perbarui waktu, ruangan, atau perlengkapan jadwal praktik</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/kejuruan/jadwal-praktik" class="text-primary font-weight-500">Jadwal Praktik</a></li>
          <li class="breadcrumb-item active">Ubah Jadwal</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-exclamation-triangle mr-1"></i> {!! session('error') !!}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    @endif

    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center justify-content-between flex-wrap">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Ubah Jadwal Praktik
            </h5>
            <span class="badge badge-light border text-secondary px-2 py-1" style="font-size: 0.75rem;">
              TA {{ $jadwal->tahun_ajaran ?? '2026/2027' }} &bull; Semester {{ $jadwal->semester ?? 'Ganjil' }}
            </span>
          </div>

          <form action="/admin/kejuruan/jadwal-praktik/{{ $jadwal->id_jadwal_praktik }}" method="POST" id="formEditJadwalPraktik">
            @csrf
            @method('PUT')
            <input type="hidden" name="id_jadwal_mapel" id="id_jadwal_mapel" value="{{ old('id_jadwal_mapel', $jadwal->id_jadwal_mapel) }}">

            <div class="card-body p-4 pt-2">

              <!-- Tipe & Siklus Jadwal Praktik -->
              <div class="p-3 mb-4 rounded-lg border bg-white shadow-sm" style="border-radius: 12px !important;">
                <label class="font-weight-bold text-dark mb-2" style="font-size: 0.88rem;">
                  <i class="fas fa-sliders-h text-primary mr-1"></i> Tipe & Frekuensi Praktik
                </label>
                <div class="row">
                  <div class="col-md-6 mb-2 mb-md-0">
                    <div class="custom-control custom-radio p-2 border rounded" style="background: #fafafa; border-radius: 8px !important;">
                      <input type="radio" id="tipe_rutin" name="tipe_jadwal" value="rutin" class="custom-control-input" {{ old('tipe_jadwal', $jadwal->tipe_jadwal ?? 'rutin') === 'rutin' ? 'checked' : '' }}>
                      <label class="custom-control-label font-weight-bold text-dark" for="tipe_rutin" style="cursor: pointer;">
                        Jadwal Rutin Mingguan
                        <small class="d-block text-muted font-weight-normal">Berlaku berulang (setiap minggu / 2 minggu sekali)</small>
                      </label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="custom-control custom-radio p-2 border rounded" style="background: #fafafa; border-radius: 8px !important;">
                      <input type="radio" id="tipe_insidental" name="tipe_jadwal" value="insidental" class="custom-control-input" {{ old('tipe_jadwal', $jadwal->tipe_jadwal) === 'insidental' ? 'checked' : '' }}>
                      <label class="custom-control-label font-weight-bold text-dark" for="tipe_insidental" style="cursor: pointer;">
                        Praktik Tambahan / Dadakan
                        <small class="d-block text-muted font-weight-normal">Hanya berlaku pada tanggal tertentu (tidak berulang)</small>
                      </label>
                    </div>
                  </div>
                </div>

                <!-- Frekuensi -->
                <div id="section_frekuensi" class="mt-3 pt-3 border-top {{ old('tipe_jadwal', $jadwal->tipe_jadwal ?? 'rutin') === 'rutin' ? '' : 'd-none' }}">
                  <label class="font-weight-bold text-secondary small text-uppercase mb-2">Frekuensi Siklus</label>
                  <div class="row">
                    <div class="col-md-4 mb-2 mb-md-0">
                      <div class="custom-control custom-radio">
                        <input type="radio" id="freq_setiap" name="frekuensi" value="setiap_minggu" class="custom-control-input" {{ old('frekuensi', $jadwal->frekuensi ?? 'setiap_minggu') === 'setiap_minggu' ? 'checked' : '' }}>
                        <label class="custom-control-label small font-weight-bold" for="freq_setiap" style="cursor: pointer;">Setiap Minggu (Rutin)</label>
                      </div>
                    </div>
                    <div class="col-md-4 mb-2 mb-md-0">
                      <div class="custom-control custom-radio">
                        <input type="radio" id="freq_ganjil" name="frekuensi" value="minggu_ganjil" class="custom-control-input" {{ old('frekuensi', $jadwal->frekuensi) === 'minggu_ganjil' ? 'checked' : '' }}>
                        <label class="custom-control-label small font-weight-bold" for="freq_ganjil" style="cursor: pointer;">2 Minggu Sekali (Minggu Ganjil)</label>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="custom-control custom-radio">
                        <input type="radio" id="freq_genap" name="frekuensi" value="minggu_genap" class="custom-control-input" {{ old('frekuensi', $jadwal->frekuensi) === 'minggu_genap' ? 'checked' : '' }}>
                        <label class="custom-control-label small font-weight-bold" for="freq_genap" style="cursor: pointer;">2 Minggu Sekali (Minggu Genap)</label>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Input Tanggal Khusus -->
                <div id="section_tanggal_khusus" class="mt-3 pt-3 border-top {{ old('tipe_jadwal', $jadwal->tipe_jadwal) === 'insidental' ? '' : 'd-none' }}">
                  <div class="form-group mb-0">
                    <label for="tanggal_khusus" class="font-weight-bold text-dark small text-uppercase mb-1">
                      Tanggal Pelaksanaan Spesifik <span class="text-danger">*</span>
                    </label>
                    <input type="date" class="form-control" id="tanggal_khusus" name="tanggal_khusus" value="{{ old('tanggal_khusus', $jadwal->tanggal_khusus ?? date('Y-m-d')) }}" style="max-width: 260px; height: 42px; border-radius: 8px;">
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label for="id_kelas" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Kelas Siswa <span class="text-danger">*</span></label>
                  <select name="id_kelas" id="id_kelas" class="form-control select2bs4 @error('id_kelas') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelas as $k)
                      <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $jadwal->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                        {{ $k->nama_kelas }} ({{ $k->jurusan->nama_jurusan ?? $k->kode_jurusan }})
                      </option>
                    @endforeach
                  </select>
                  @error('id_kelas')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                  <label for="id_lab" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Laboratorium / Bengkel <span class="text-danger">*</span></label>
                  <select name="id_lab" id="id_lab" class="form-control select2bs4 @error('id_lab') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Laboratorium</option>
                    @foreach($labs as $l)
                      <option value="{{ $l->id_lab }}" {{ old('id_lab', $jadwal->id_lab) == $l->id_lab ? 'selected' : '' }}>
                        {{ $l->nama_lab }} (Kapasitas: {{ $l->kapasitas ?: '36' }})
                      </option>
                    @endforeach
                  </select>
                  @error('id_lab')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-3">
                  <label for="id_mapel" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Mata Pelajaran Praktik <span class="text-danger">*</span></label>
                  <select name="id_mapel" id="id_mapel" class="form-control select2bs4 @error('id_mapel') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach($mapels as $m)
                      <option value="{{ $m->id_mapel }}" {{ old('id_mapel', $jadwal->id_mapel) == $m->id_mapel ? 'selected' : '' }}>
                        {{ $m->nama_mapel }}
                      </option>
                    @endforeach
                  </select>
                  @error('id_mapel')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-6 form-group mb-3">
                  <label for="id_guru" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Guru Pengajar / Instruktur <span class="text-danger">*</span></label>
                  <select name="id_guru" id="id_guru" class="form-control select2bs4 @error('id_guru') is-invalid @enderror" required style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Guru</option>
                    @foreach($gurus as $g)
                      <option value="{{ $g->id_guru }}" {{ old('id_guru', $jadwal->id_guru) == $g->id_guru ? 'selected' : '' }}>
                        {{ $g->nama_guru }}
                      </option>
                    @endforeach
                  </select>
                  @error('id_guru')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <div class="row">
                <div class="col-md-4 form-group mb-3" id="hari_group">
                  <label for="hari" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Hari Pelaksanaan <span class="text-danger">*</span></label>
                  <select name="hari" id="hari" class="form-control @error('hari') is-invalid @enderror" style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Hari</option>
                    @foreach($hariList as $h)
                      <option value="{{ $h }}" {{ old('hari', $jadwal->hari) == $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                  </select>
                  @error('hari')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-4 form-group mb-3">
                  <label for="jam_mulai" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Jam Mulai <span class="text-danger">*</span></label>
                  <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai', substr($jadwal->jam_mulai, 0, 5)) }}" required style="border-radius: 8px; height: 42px;">
                  @error('jam_mulai')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>

                <div class="col-md-4 form-group mb-3">
                  <label for="jam_selesai" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Jam Selesai <span class="text-danger">*</span></label>
                  <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai', substr($jadwal->jam_selesai, 0, 5)) }}" required style="border-radius: 8px; height: 42px;">
                  @error('jam_selesai')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
              </div>

              <!-- Opsi Praktik Blok / Dispensasi KBM -->
              <div class="p-3 mb-3 rounded-lg border bg-light" style="border-radius: 10px;">
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input" id="override_mapel" name="override_mapel" value="1">
                  <label class="custom-control-label font-weight-bold text-dark" for="override_mapel" style="cursor: pointer; font-size: 0.86rem;">
                    Izinkan Mengambil Jam Pelajaran Lain (Praktik Blok / Dispensasi KBM)
                  </label>
                  <small class="d-block text-muted mt-1" style="font-size: 0.78rem;">
                    Centang jika sesi praktik ini mengambil alih jam mata pelajaran umum lainnya atas persetujuan Kurikulum.
                  </small>
                </div>
              </div>

              <div class="form-group mb-2">
                <label for="alat_bahan" class="font-weight-bold text-dark" style="font-size: 0.85rem;">
                  Alat / Bahan yang Harus Dibawa Siswa (Opsional)
                </label>
                <textarea class="form-control @error('alat_bahan') is-invalid @enderror" id="alat_bahan" name="alat_bahan" rows="2" style="border-radius: 8px;">{{ old('alat_bahan', $jadwal->alat_bahan) }}</textarea>
                @error('alat_bahan')
                  <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                @enderror
              </div>
            </div>

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/kejuruan/jadwal-praktik" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Batal
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Perbarui Jadwal Praktik
              </button>
            </div>
          </form>
        </div>
      </div>

      <div class="col-12 col-lg-4 mt-3 mt-lg-0">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; background-color: #f8fafc;">
          <div class="card-body p-3">
            <h6 class="font-weight-bold text-dark mb-2">
              <i class="fas fa-info-circle text-primary mr-1"></i> Informasi Perubahan
            </h6>
            <p class="text-muted small mb-2">Perubahan waktu atau laboratorium akan otomatis dicek ulang agar tidak bertabrakan dengan jadwal kelas lain.</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
  $(document).ready(function() {
    $('input[name="tipe_jadwal"]').on('change', function() {
      if ($(this).val() === 'insidental') {
        $('#section_frekuensi').addClass('d-none');
        $('#section_tanggal_khusus').removeClass('d-none');
        $('#hari_group').hide();
        $('#hari').prop('required', false);
        $('#tanggal_khusus').prop('required', true);
      } else {
        $('#section_frekuensi').removeClass('d-none');
        $('#section_tanggal_khusus').addClass('d-none');
        $('#hari_group').show();
        $('#hari').prop('required', true);
        $('#tanggal_khusus').prop('required', false);
      }
    });

    if ($('input[name="tipe_jadwal"]:checked').val() === 'insidental') {
      $('#hari_group').hide();
      $('#hari').prop('required', false);
      $('#tanggal_khusus').prop('required', true);
    }
  });
</script>
@endpush
@endsection
