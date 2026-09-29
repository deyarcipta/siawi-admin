@extends($layout)
@section('content')
<!-- Content Header -->
<div class="content-header pb-2">
  <div class="container-fluid">
    <div class="row align-items-center mb-2">
      <div class="col-sm-7 d-flex align-items-center">
        <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm mr-3" style="width: 46px; height: 46px; font-size: 1.25rem;">
          <i class="fas fa-sun"></i>
        </div>
        <div>
          <h1 class="m-0 font-weight-bold text-dark" style="line-height: 1.2; font-size: 1.35rem;">Edit Piket Pembiasaan Pagi</h1>
          <p class="text-muted mt-1 mb-0" style="font-size: 0.84rem;">Perbarui data petugas piket pembiasaan pagi siswa</p>
        </div>
      </div>
      <div class="col-sm-5">
        <ol class="breadcrumb float-sm-right bg-transparent p-0 mb-0">
          <li class="breadcrumb-item"><a href="/admin/piketPembiasaanPagi" class="text-primary font-weight-500">Piket Pembiasaan Pagi</a></li>
          <li class="breadcrumb-item active">Edit</li>
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
              <i class="fas fa-edit text-primary mr-2"></i> Formulir Edit Piket Pembiasaan Pagi
            </h5>
          </div>
          <!-- /.card-header -->
          <!-- form start -->
          <form action="/admin/piketPembiasaanPagi/{{$edit->id_pembiasaan}}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
              <div class="form-group">
                <label for="id_guru">Pilih Guru</label>
                <select class="form-control" name="id_guru" id="id_guru" required>
                  <option value="">Pilih Guru</option>
                  @foreach($guru as $gru)
                    <option value="{{ $gru->id_guru }}" {{ old('id_guru', $edit->id_guru) == $gru->id_guru ? 'selected' : '' }}>{{ $gru->nama_guru }}</option>
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
                    <option value="{{ $h }}" {{ old('hari', $edit->hari) == $h ? 'selected' : '' }}>{{ $h }}</option>
                  @endforeach
                </select>
                @error('hari')
                  <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror
              </div>
              <div class="row">
                <div class="form-group col-md-6">
                  <label for="waktu_awal">Jam Mulai</label>
                  <input type="time" class="form-control" id="waktu_awal" name="waktu_awal" value="{{old('waktu_awal', $edit->waktu_awal)}}" required>
                  @error('waktu_awal')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group col-md-6 mb-0">
                  <label for="waktu_akhir">Jam Selesai</label>
                  <input type="time" class="form-control" id="waktu_akhir" name="waktu_akhir" value="{{old('waktu_akhir', $edit->waktu_akhir)}}" required>
                  @error('waktu_akhir')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                  @enderror
                </div>
              </div>
            </div>
            <!-- /.card-body -->

            <div class="card-footer bg-light py-3 px-4 d-flex align-items-center">
              <a href="/admin/piketPembiasaanPagi" class="btn btn-outline-secondary px-3" style="border-radius: 8px; font-weight: 500;">
                <i class="fas fa-arrow-left mr-1"></i> Kembali
              </a>
              <button type="submit" class="btn btn-primary ml-auto px-4 shadow-sm" style="border-radius: 8px; font-weight: 600;">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
        <!-- /.card -->
      </div>
    </div>
  </div>
</div>
@endsection
