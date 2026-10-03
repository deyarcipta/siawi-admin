<!-- Modern Topbar Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light d-flex align-items-center justify-content-between">
  <!-- Left Side: Hamburger & Universal Search -->
  <div class="d-flex align-items-center">
    <button class="nav-link btn btn-link text-secondary p-0 mr-3 d-lg-none" data-widget="pushmenu" role="button">
      <i class="fas fa-bars" style="font-size: 1.1rem;"></i>
    </button>
    <div class="navbar-search-pill d-none d-md-flex">
      <i class="fas fa-search text-muted" style="font-size: 0.85rem;"></i>
      <input type="text" id="global-dashboard-search" placeholder="Cari siswa, kelas, atau guru..." autocomplete="off">
    </div>
  </div>

  <!-- Right Side: Date, Notification, and User Profile Pill -->
  <div class="d-flex align-items-center">
    <!-- Dynamic Indonesian Date -->
    <div class="navbar-date-badge d-none d-lg-flex">
      <span>{{ $tanggalHariIni ?? \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}</span>
    </div>

    <!-- Quick Action / Notification Circles -->
    <button type="button" class="navbar-circle-btn d-none d-sm-flex" title="Notifikasi">
      <i class="far fa-bell" style="font-size: 0.95rem;"></i>
    </button>
    <button type="button" class="navbar-circle-btn d-none d-sm-flex" title="Bantuan / Info" onclick="location.href='/admin/informasi'">
      <i class="far fa-question-circle" style="font-size: 0.95rem;"></i>
    </button>

    <!-- User Profile Dropdown Pill -->
    <div class="dropdown">
      <a href="#" class="navbar-admin-pill dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
        @if($user && $user->foto && file_exists(storage_path('app/public/foto_guru/' . $user->foto)))
          <img src="{{ asset('storage/foto_guru/' . $user->foto) }}" alt="{{ $user->nama_guru }}" class="user-pill-avatar rounded-circle mr-1" style="width: 24px; height: 24px; object-fit: cover;">
        @else
          <span class="user-pill-avatar" style="width: 24px; height: 24px; background: rgba(255,255,255,0.25); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700;">
            {{ strtoupper(substr($user->nama_guru ?? 'A', 0, 1)) }}
          </span>
        @endif
        <span class="d-none d-sm-inline">
          @if($user->role == 'admin')
            Admin
          @elseif($user->role == 'wali_kelas')
            Wali Kelas
          @elseif($user->role == 'guru')
            Guru
          @elseif($user->role == 'kesiswaan')
            Kesiswaan
          @elseif($user->role == 'kurikulum')
            Kurikulum
          @else
            User
          @endif
        </span>
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow-lg border-0" style="border-radius: 12px; min-width: 230px; margin-top: 10px; overflow: hidden;">
        <div class="p-3 text-center" style="background: linear-gradient(135deg, #1d72fe 0%, #0b1f3a 100%); color: #ffffff;">
          @if($user && $user->foto && file_exists(storage_path('app/public/foto_guru/' . $user->foto)))
            <img src="{{ asset('storage/foto_guru/' . $user->foto) }}" alt="{{ $user->nama_guru }}" class="rounded-circle shadow-sm" style="width: 52px; height: 52px; object-fit: cover; margin: 0 auto 8px; border: 2px solid #ffffff; display: block;">
          @else
            <div style="width: 48px; height: 48px; background: #ffffff; color: #1d72fe; border-radius: 50%; margin: 0 auto 8px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: 800;">
              {{ strtoupper(substr($user->nama_guru ?? 'A', 0, 1)) }}
            </div>
          @endif
          <div class="font-weight-bold" style="font-size: 0.95rem;">{{ $user->nama_guru }}</div>
          <div style="font-size: 0.75rem; opacity: 0.85;">{{ $user->role == 'wali_kelas' ? 'Wali Kelas' : ucfirst($user->role) }}</div>
        </div>
        <div class="p-2">
          <a href="{{ route('admin.guru.profile', $user->id_guru ?? 1) }}" class="dropdown-item py-2" style="border-radius: 8px; font-size: 0.85rem; font-weight: 500;">
            <i class="fas fa-user-circle mr-2 text-primary"></i> Profil Saya
          </a>
          <div class="dropdown-divider my-1"></div>
          <a href="{{ route('logout') }}" class="dropdown-item py-2 text-danger" style="border-radius: 8px; font-size: 0.85rem; font-weight: 600;">
            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>
<!-- /.navbar -->