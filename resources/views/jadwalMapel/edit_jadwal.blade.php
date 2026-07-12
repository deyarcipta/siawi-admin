@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Jadwal</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Jadwal</a></li>
            <li class="breadcrumb-item active">Edit Jadwal</li>
          </ol>
        </div><!-- /.col -->
      </div><!-- /.row -->
    </div><!-- /.container-fluid -->
  </div>
  <div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12">
          <!-- general form elements -->
          <div class="card card-primary">
            <div class="card-header">
              <h3 class="card-title">Form Edit Jadwal</h3>
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
              <!-- /.card-body -->
              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
          <!-- /.card -->
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