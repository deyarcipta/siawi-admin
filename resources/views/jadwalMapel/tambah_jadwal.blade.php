@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Tambah Jadwal</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Jadwal</a></li>
            <li class="breadcrumb-item active">Tambah Jadwal</li>
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
              <h3 class="card-title">Form Tambah Jadwal</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/jadwal" method="POST">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="id_mapel">Nama Mapel</label>
                  <select class="form-control" name="id_mapel" id="id_mapel">
                    <option value="{{old('id_mapel')}}">Pilih Nama Mapel</option>
                    @foreach ($mapel as $mpl)
                      <option value="{{$mpl->id_mapel}}">{{$mpl->nama_mapel}}</option>
                    @endforeach
                  </select>
                  @error('id_mapel')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="id_guru">Nama Guru</label>
                  <select class="form-control" name="id_guru" id="id_guru">
                    <option value="{{old('id_guru')}}">Pilih Nama Guru</option>
                    @foreach ($guru as $gru)
                      <option value="{{$gru->id_guru}}">{{$gru->nama_guru}}</option>
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
                      <option value="{{old('hari')}}">Pilih Hari</option>
                        <option value="senin">Senin</option>
                        <option value="selasa">Selasa</option>
                        <option value="rabu">Rabu</option>
                        <option value="kamis">Kamis</option>
                        <option value="jumat">Jumat</option>
                    </select>
                    @error('hari')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="id_kelas">Kelas</label>
                    <select class="form-control" name="id_kelas" id="id_kelas">
                      <option value="{{old('id_kelas')}}">Pilih Kelas</option>
                        @foreach ($kelas as $kls)
                          <option value="{{$kls->id_kelas}}">{{$kls->nama_kelas}}</option>
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
                      <option value="{{old('jam_awal')}}">Pilih Jam Awal</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                    @error('jam_awal')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="jam_akhir">Jam Akhir</label>
                    <select class="form-control" name="jam_akhir" id="jam_akhir">
                      <option value="{{old('jam_akhir')}}">Pilih Jam Akhir</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                    </select>
                    @error('jam_akhir')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <label for="waktu_awal">Waktu Awal</label>
                    <input type="time" class="form-control" id="waktu_awal" placeholder="Enter Waktu Awal" name="waktu_awal" value="{{old('waktu_awal')}}">
                    @error('waktu_awal')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                  <div class="form-group col-6">
                    <label for="waktu_akhir">Waktu Akhir</label>
                    <input type="time" class="form-control" id="waktu_akhir" placeholder="Enter Waktu Akhir" name="waktu_akhir" value="{{old('waktu_akhir')}}">
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
@endsection