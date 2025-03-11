@extends($layout)
@section('content')
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Edit Profile</h1>
        </div><!-- /.col -->
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Profile</a></li>
            <li class="breadcrumb-item active">Edit Profile</li>
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
              <h3 class="card-title">Form Edit Guru</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form action="{{ url('/admin/dashboard/' . $edit->id_guru) }}" method="POST">
              @csrf
              @method('PUT')
              <div class="card-body">
                <div class="form-group">
                  <label for="username">Username</label>
                  <input type="text" class="form-control" id="username" placeholder="Enter Username" name="username" value="{{ old('username', $edit->username) }}" readonly>
                  @error('username')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="password">New Password (Kosongkan Jika Tidak Ingin Merubah Password)</label>
                  <input type="password" class="form-control" id="password" placeholder="Enter New Password" name="password">
                  @error('password')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group">
                  <label for="nama_guru">Nama Guru</label>
                  <input type="text" class="form-control" id="nama_guru" placeholder="Enter Nama" name="nama_guru" value="{{ old('nama_guru', $edit->nama_guru) }}">
                  @error('nama_guru')
                    <div class="alert alert-danger">{{ $message }}</div>
                  @enderror
                </div>
                <div class="form-group" hidden>
                  <label for="role">Role</label>
                  <select class="form-control" name="role" id="role">
                    <option value="admin" {{ old('role', $edit->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="kurikulum" {{ old('role', $edit->role) == 'kurikulum' ? 'selected' : '' }}>Kurikulum</option>
                    <option value="kesiswaan" {{ old('role', $edit->role) == 'kesiswaan' ? 'selected' : '' }}>Kesiswaan</option>
                    <option value="guru" {{ old('role', $edit->role) == 'guru' ? 'selected' : '' }}>Guru</option>
                  </select>
                  @error('role')
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
