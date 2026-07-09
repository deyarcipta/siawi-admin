@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Tambah Guru Piket</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/admin/guruPiket">Guru Piket</a></li>
            <li class="breadcrumb-item active">Tambah</li>
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
              <h3 class="card-title">Form Tambah Guru Piket</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/guruPiket" method="POST">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="id_guru">Pilih Guru</label>
                  <select class="form-control" name="id_guru" id="id_guru" required>
                    <option value="">Pilih Guru</option>
                    @foreach($guru as $gru)
                      <option value="{{ $gru->id_guru }}" {{ old('id_guru') == $gru->id_guru ? 'selected' : '' }}>{{ $gru->nama_guru }}</option>
                    @endforeach
                  </select>
                  @error('id_guru')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="hari">Hari</label>
                  <select class="form-control" name="hari" id="hari" required>
                    <option value="">Pilih Hari</option>
                    @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                      <option value="{{ $h }}" {{ old('hari') == $h ? 'selected' : '' }}>{{ $h }}</option>
                    @endforeach
                  </select>
                  @error('hari')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="waktu_awal">Jam Mulai</label>
                  <input type="time" class="form-control" id="waktu_awal" name="waktu_awal" value="{{old('waktu_awal', '07:00')}}" required>
                  @error('waktu_awal')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="waktu_akhir">Jam Selesai</label>
                  <input type="time" class="form-control" id="waktu_akhir" name="waktu_akhir" value="{{old('waktu_akhir', '14:00')}}" required>
                  @error('waktu_akhir')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" class="btn btn-primary">Submit</button>
                <a href="/admin/guruPiket" class="btn btn-default">Kembali</a>
              </div>
            </form>
          </div>
          <!-- /.card -->
        </div>
      </div>
    </div>
  </div>
@endsection
