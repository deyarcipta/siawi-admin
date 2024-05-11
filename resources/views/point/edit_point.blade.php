@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Point</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Point</a></li>
            <li class="breadcrumb-item active">Edit Point</li>
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
              <h3 class="card-title">Form Edit Point</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/point/{{$edit->id_point  }}" method="POST">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-group">
                  <label for="nama_point">Nama Point</label>
                  <input type="text" class="form-control" id="nama_point" placeholder="Enter Nama Point" name="nama_point" value="{{$edit->nama_point}}">
                  @error('nama_point')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="jenis_point">Jenis Point</label>
                  <input type="text" class="form-control" id="jenis_point" placeholder="Enter Jenis point" name="jenis_point" value="{{$edit->jenis_point}}">
                  @error('jenis_point')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="skor_point">Skor Point</label>
                  <input type="text" class="form-control" id="skor_point" placeholder="Enter Skor point" name="skor_point" value="{{$edit->skor_point}}">
                  @error('skor_point')
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