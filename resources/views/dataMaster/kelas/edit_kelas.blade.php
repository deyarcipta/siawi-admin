@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Kelas</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Kelas</a></li>
            <li class="breadcrumb-item active">Edit Kelas</li>
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
              <h3 class="card-title">Form Edit Kelas</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/kelas/{{$edit->id_kelas  }}" method="POST">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-group">
                  <label for="kode_kelas">Kode Kelas</label>
                  <input type="text" class="form-control" id="kode_kelas" placeholder="Enter Kode kelas" name="kode_kelas" value="{{$edit->kode_kelas}}">
                  @error('kode_kelas')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="kode_level">Pilih Level</label>
                  <select class="form-control" name="kode_level" id="kode_level">
                    @foreach ($level as $lvl)
                      <option value="{{$lvl->kode_level}}">{{$lvl->kode_level}}</option>
                    @endforeach
                  </select>
                  @error('kode_level')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="nama_kelas">Nama Kelas</label>
                  <input type="text" class="form-control" id="nama_kelas" placeholder="Enter Nama kelas" name="nama_kelas" value="{{$edit->nama_kelas}}">
                  @error('nama_kelas')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="kode_jurusan">Pilih Jurusan</label>
                  <select name="kode_jurusan" id="kode_jurusan" class="form-control">
                    @foreach ($jurusan as $jur)
                      <option value="{{ $jur->kode_jurusan }}" {{ $edit->kode_jurusan == $jur->kode_jurusan ? 'selected' : '' }}> {{ $jur->kode_jurusan }}</option>
                    @endforeach
                  </select>
                  @error('kode_jurusan')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
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