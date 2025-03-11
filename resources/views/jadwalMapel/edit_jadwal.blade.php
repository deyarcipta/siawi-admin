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
                      <option value="{{$edit->hari}}">{{$edit->hari}}</option>
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
                    <label for="kelas">Kelas</label>
                    <select class="form-control" name="kelas" id="kelas">
                      <option value="{{$edit->kelas}}">{{$edit->kelas}}</option>
                        @foreach ($kelas as $kls)
                          <option value="{{$kls->kode_kelas}}">{{$kls->kode_kelas}}</option>
                        @endforeach
                    </select>
                    @error('kelas')
                      <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                  </div>
                </div>
                <div class="row">
                  <div class="form-group col-6">
                    <label for="jam_awal">Jam Awal</label>
                    <select class="form-control" name="jam_awal" id="jam_awal">
                      <option value="{{$edit->jam_awal}}">{{$edit->jam_awal}}</option>
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
                      <option value="{{$edit->jam_akhir}}">{{$edit->jam_akhir}}</option>
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
@endsection