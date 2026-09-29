@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-calendar-alt"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Edit Jadwal Pelajaran</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Perbarui jadwal mengajar guru, mata pelajaran, kelas, dan alokasi jam</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/jadwal" class="text-primary font-weight-500">Jadwal Pelajaran</a></li>
          <li class="breadcrumb-item active">Edit Jadwal</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- general form elements -->
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Edit Jadwal Pelajaran
            </h5>
          </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/jadwal/{{$edit->id_jadwal}}" method="POST">
              @method('PUT')
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="id_mapel">Nama Mapel</label>
                  <select class="form-control" name="id_mapel" id="id_mapel">
                    @foreach ($mapel as $mpl)
                    <option value="{{ $mpl->id_mapel }}" {{ $edit->id_mapel == $mpl->id_mapel ? 'selected' : '' }}> {{ $mpl->nama_mapel }}</option>
                    @endforeach
                  </select>
                  @error('id_mapel')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="id_guru">Nama Guru</label>
                  <select class="form-control" name="id_guru" id="id_guru">
                    @foreach ($guru as $gru)
                    <option value="{{ $gru->id_guru }}" {{ $edit->id_guru == $gru->id_guru ? 'selected' : '' }}> {{ $gru->nama_guru }}</option>
                    @endforeach
                  </select>
                  @error('id_guru')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <label for="hari">Hari</label>
                    <select class="form-control" name="hari" id="hari">
                        <option value="senin" {{ strtolower($edit->hari) == 'senin' ? 'selected' : '' }}>Senin</option>
                        <option value="selasa" {{ strtolower($edit->hari) == 'selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="rabu" {{ strtolower($edit->hari) == 'rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="kamis" {{ strtolower($edit->hari) == 'kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="jumat" {{ strtolower($edit->hari) == 'jumat' ? 'selected' : '' }}>Jumat</option>
                    </select>
                    @error('hari')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="id_kelas">Kelas</label>
                    <select class="form-control" name="id_kelas" id="id_kelas">
                      <option value="">Pilih Kelas</option>
                      @foreach ($kelas as $kls)
                        <option value="{{$kls->id_kelas}}" {{ $edit->id_kelas == $kls->id_kelas ? 'selected' : '' }}>{{$kls->nama_kelas}}</option>
                      @endforeach
                    </select>
                    @error('id_kelas')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <label for="jam_awal">Jam Awal</label>
                    <select class="form-control" name="jam_awal" id="jam_awal">
                        @for ($i = 1; $i <= 10; $i++)
                          <option value="{{ $i }}" {{ $edit->jam_awal == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    @error('jam_awal')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="jam_akhir">Jam Akhir</label>
                    <select class="form-control" name="jam_akhir" id="jam_akhir">
                        @for ($i = 1; $i <= 10; $i++)
                          <option value="{{ $i }}" {{ $edit->jam_akhir == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                    @error('jam_akhir')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <label for="waktu_awal">Waktu Awal</label>
                    <input type="time" class="form-control" id="waktu_awal" placeholder="Enter Waktu Awal" name="waktu_awal" value="{{$edit->waktu_awal}}">
                    @error('waktu_awal')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="waktu_akhir">Waktu Akhir</label>
                    <input type="time" class="form-control" id="waktu_akhir" placeholder="Enter Waktu Akhir" name="waktu_akhir" value="{{$edit->waktu_akhir}}">
                    @error('waktu_akhir')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
              </div>
            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/jadwal" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script>
  document.addEventListener('DOMContentLoaded', function() {
      const jamPelajaran = @json($setting->jam_pelajaran ?? []);

      const jamAwalSelect = document.getElementById('jam_awal');
      const jamAkhirSelect = document.getElementById('jam_akhir');
      const waktuAwalInput = document.getElementById('waktu_awal');
      const waktuAkhirInput = document.getElementById('waktu_akhir');

      function updateWaktuAwal() {
          const jamAwal = jamAwalSelect.value;
          if (jamPelajaran[jamAwal] && jamPelajaran[jamAwal].mulai) {
              waktuAwalInput.value = jamPelajaran[jamAwal].mulai;
          }
      }

      function updateWaktuAkhir() {
          const jamAkhir = jamAkhirSelect.value;
          if (jamPelajaran[jamAkhir] && jamPelajaran[jamAkhir].selesai) {
              waktuAkhirInput.value = jamPelajaran[jamAkhir].selesai;
          }
      }

      jamAwalSelect.addEventListener('change', updateWaktuAwal);
      jamAkhirSelect.addEventListener('change', updateWaktuAkhir);
  });
  </script>
@endsection