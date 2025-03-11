<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
  </ul>

  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <img src="{{ asset('lte/dist/img/default.jpg') }}" class="img-circle elevation-1" alt="User Image" width="30">
        <span class="pl-2">
          @if($user->role == 'admin')
            Administrator
          @elseif($user->role == 'guru')
            Guru
          @elseif($user->role == 'kesiswaan')
            Kesiswaan
          @elseif($user->role == 'kurikulum')
            Kurikulum
          @else
            Role tidak ditemukan
          @endif
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <div class="dropdown-body bg-success text-center" style="height: 160px;">
          <img src="{{ asset('lte/dist/img/default.jpg') }}" class="img-circle elevation-1 mt-3" alt="User Image" width="80" style="border: 2px solid #fff;">
          <div class="mt-2">{{$user->nama_guru}}</div>
          <div style="margin-top: -0.5rem;">NIP:-</div>
        </div>
        <div class="d-flex justify-content-between" style="background-color: white; padding: 7px;">
          <a href="{{route('admin.guru.profile', $user->id_guru)}}" class="btn btn-light text-secondary border">
            <i class="fas fa-user"></i> Profile
          </a>
          <a href="{{route('logout')}}" class="btn btn-light text-secondary border">
            <i class="fas fa-sign-out-alt"></i> Keluar
          </a>
        </div>
      </div>
    </li>
  </ul>
</nav>
<!-- /.navbar -->