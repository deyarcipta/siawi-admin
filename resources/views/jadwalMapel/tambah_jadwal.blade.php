@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-calendar-plus"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Tambah Jadwal Pelajaran</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Alokasikan jadwal mengajar mata pelajaran ke kelas dan guru</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/jadwal" class="text-primary font-weight-500">Jadwal Pelajaran</a></li>
          <li class="breadcrumb-item active">Tambah Jadwal</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 14px; overflow: hidden;">
          <div class="card-header bg-white py-3 border-0 d-flex align-items-center">
            <h5 class="card-title font-weight-bold text-dark mb-0" style="font-size: 1.05rem;">
              <i class="fas fa-plus-circle text-primary mr-2"></i> Formulir Tambah Jadwal Pelajaran
            </h5>
          </div>

          <form action="/admin/jadwal" method="POST">
            @csrf
            <div class="card-body p-4 pt-2">
                <div class="form-group mb-3">
                  <label for="id_mapel" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Mata Pelajaran <span class="text-danger">*</span></label>
                  <select class="form-control @error('id_mapel') is-invalid @enderror" name="id_mapel" id="id_mapel" style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Nama Mapel</option>
                    @foreach ($mapel as $mpl)
                      <option value="{{$mpl->id_mapel}}" {{ old('id_mapel') == $mpl->id_mapel ? 'selected' : '' }}>{{$mpl->nama_mapel}}</option>
                    @endforeach
                  </select>
                  @error('id_mapel')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
                <div class="form-group mb-3">
                  <label for="id_guru" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Guru Pengampu <span class="text-danger">*</span></label>
                  <select class="form-control @error('id_guru') is-invalid @enderror" name="id_guru" id="id_guru" style="border-radius: 8px; height: 42px;">
                    <option value="">Pilih Nama Guru</option>
                    @foreach ($guru as $gru)
                      <option value="{{$gru->id_guru}}" {{ old('id_guru') == $gru->id_guru ? 'selected' : '' }}>{{$gru->nama_guru}}</option>
                    @endforeach
                  </select>
                  @error('id_guru')
                    <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                  @enderror
                </div>
                <div class="row">
                  <div class="form-group col-6 mb-3">
                    <label for="hari" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Hari <span class="text-danger">*</span></label>
                    <select class="form-control @error('hari') is-invalid @enderror" name="hari" id="hari" style="border-radius: 8px; height: 42px;">
                      <option value="">Pilih Hari</option>
                        <option value="senin" {{ old('hari') == 'senin' ? 'selected' : '' }}>Senin</option>
                        <option value="selasa" {{ old('hari') == 'selasa' ? 'selected' : '' }}>Selasa</option>
                        <option value="rabu" {{ old('hari') == 'rabu' ? 'selected' : '' }}>Rabu</option>
                        <option value="kamis" {{ old('hari') == 'kamis' ? 'selected' : '' }}>Kamis</option>
                        <option value="jumat" {{ old('hari') == 'jumat' ? 'selected' : '' }}>Jumat</option>
                        <option value="sabtu" {{ old('hari') == 'sabtu' ? 'selected' : '' }}>Sabtu</option>
                    </select>
                    @error('hari')
                      <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                    @enderror
                  </div>
                  <div class="form-group col-6 mb-3">
                    <label for="id_kelas" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Kelas <span class="text-danger">*</span></label>
                    <select class="form-control @error('id_kelas') is-invalid @enderror" name="id_kelas" id="id_kelas" style="border-radius: 8px; height: 42px;">
                      <option value="">Pilih Kelas</option>
                        @foreach ($kelas as $kls)
                          <option value="{{$kls->id_kelas}}" {{ old('id_kelas') == $kls->id_kelas ? 'selected' : '' }}>{{$kls->nama_kelas}}</option>
                        @endforeach
                    </select>
                    @error('id_kelas')
                      <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-6 mb-3">
                    <label for="jam_awal" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Jam Ke (Mulai) <span class="text-danger">*</span></label>
                    <select class="form-control @error('jam_awal') is-invalid @enderror" name="jam_awal" id="jam_awal" style="border-radius: 8px; height: 42px;">
                      <option value="">Pilih Jam Awal</option>
                      @for($i=1; $i<=12; $i++)
                        <option value="{{$i}}" {{ old('jam_awal') == $i ? 'selected' : '' }}>Jam ke-{{$i}}</option>
                      @endfor
                    </select>
                    @error('jam_awal')
                      <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                    @enderror
                  </div>
                  <div class="form-group col-6 mb-3">
                    <label for="jam_akhir" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Jam Ke (Selesai) <span class="text-danger">*</span></label>
                    <select class="form-control @error('jam_akhir') is-invalid @enderror" name="jam_akhir" id="jam_akhir" style="border-radius: 8px; height: 42px;">
                      <option value="">Pilih Jam Akhir</option>
                      @for($i=1; $i<=12; $i++)
                        <option value="{{$i}}" {{ old('jam_akhir') == $i ? 'selected' : '' }}>Jam ke-{{$i}}</option>
                      @endfor
                    </select>
                    @error('jam_akhir')
                      <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-6 mb-3">
                    <label for="waktu_awal" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Waktu Mulai <span class="text-danger">*</span></label>
                    <input type="time" class="form-control @error('waktu_awal') is-invalid @enderror" id="waktu_awal" name="waktu_awal" value="{{old('waktu_awal')}}" style="border-radius: 8px; height: 42px;">
                    @error('waktu_awal')
                      <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                    @enderror
                  </div>
                  <div class="form-group col-6 mb-3">
                    <label for="waktu_akhir" class="font-weight-bold text-dark" style="font-size: 0.85rem;">Waktu Selesai <span class="text-danger">*</span></label>
                    <input type="time" class="form-control @error('waktu_akhir') is-invalid @enderror" id="waktu_akhir" name="waktu_akhir" value="{{old('waktu_akhir')}}" style="border-radius: 8px; height: 42px;">
                    @error('waktu_akhir')
                      <small class="text-danger font-weight-500 mt-1 d-block">{{ $message }}</small>
                    @enderror
                  </div>
                </div>
              </div>

              <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
                <a href="/admin/jadwal" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                  <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                  <i class="fas fa-save mr-1"></i> Simpan Jadwal
                </button>
              </div>
            </form>
          </div>
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