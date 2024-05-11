@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Tambah Guru</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Guru</a></li>
            <li class="breadcrumb-item active">Tambah Guru</li>
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
              <h3 class="card-title">Form Tambah Guru</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="/admin/guru" method="POST">
              @csrf
              <div class="card-body">
                <div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" class="form-control" id="username" placeholder="Enter Username" name="username" value="{{old('username')}}">
                  @error('username')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="password">Password</label>
                  <input type="text" class="form-control" id="password" placeholder="Enter Password" name="password" value="{{old('password')}}">
                  @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="nama_guru">Nama Guru</label>
                  <input type="text" class="form-control" id="nama_guru" placeholder="Enter Nama" name="nama_guru" value="{{old('nama_guru')}}">
                  @error('nama_guru')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="username">Role</label>
                  <select class="form-control" name="role" id="role">
                    <option value="{{old('role')}}">Pilih Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kurikulum" {{ old('role') == 'kurikulum' ? 'selected' : '' }}>Kurikulum</option>
                    <option value="kesiswaan" {{ old('role') == 'kesiswaan' ? 'selected' : '' }}>Kesiswaan</option>
                    <option value="guru" {{ old('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                  </select>
                  @error('role')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                  {{-- <input type="text" class="form-control" id="username" placeholder="Enter Username" name="username" value="{{old('username')}}">
                  @error('username')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror --}}
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