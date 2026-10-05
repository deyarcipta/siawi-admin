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

    <!-- Quick Action / Notification Dropdown -->
    <div class="dropdown mr-2">
      <button type="button" class="navbar-circle-btn dropdown-toggle d-none d-sm-flex position-relative" id="notifDropdownBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" title="Notifikasi & Agenda">
        <i class="far fa-bell" style="font-size: 0.95rem;"></i>
        <span class="badge badge-danger notif-badge-pill d-none" id="notif-badge" style="position: absolute; top: -3px; right: -3px; font-size: 0.65rem; border-radius: 10px; padding: 2px 5px; font-weight: 700; border: 2px solid #ffffff; min-width: 18px;">0</span>
      </button>

      <div class="dropdown-menu dropdown-menu-right shadow-lg border-0 notif-dropdown-box" aria-labelledby="notifDropdownBtn" style="width: 360px; max-width: 92vw; border-radius: 14px; margin-top: 10px; padding: 0; overflow: hidden; border: 1px solid #e2e8f0 !important;">
        <!-- Dropdown Header -->
        <div class="d-flex align-items-center justify-content-between px-3 py-2 border-bottom" style="background: #f8fafc;">
          <div class="d-flex align-items-center">
            <i class="fas fa-bell text-primary mr-2" style="font-size: 0.85rem;"></i>
            <span class="font-weight-bold text-dark" style="font-size: 0.88rem;">Notifikasi & Agenda</span>
          </div>
          <span class="badge badge-primary px-2 py-1" id="notif-total-badge" style="font-size: 0.72rem; border-radius: 6px; font-weight: 600;">0 Baru</span>
        </div>

        <!-- Dropdown List Container -->
        <div id="notif-list-body" style="max-height: 380px; overflow-y: auto; background: #ffffff;">
          <!-- Loading State -->
          <div class="p-4 text-center text-muted" id="notif-loading-state" style="font-size: 0.85rem;">
            <i class="fas fa-spinner fa-spin mr-2 text-primary"></i> Memuat notifikasi...
          </div>
          <!-- Empty State -->
          <div class="p-4 text-center text-muted d-none" id="notif-empty-state">
            <div style="width: 44px; height: 44px; background: #f1f5f9; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 8px;">
              <i class="fas fa-check-circle text-success" style="font-size: 1.25rem;"></i>
            </div>
            <div class="font-weight-bold text-dark" style="font-size: 0.85rem;">Semua Terpantau Baik</div>
            <div class="text-muted mt-1" style="font-size: 0.75rem;">Tidak ada peringatan atau agenda baru saat ini.</div>
          </div>
          <!-- Items Wrapper -->
          <div id="notif-items-wrapper"></div>
        </div>

        <!-- Dropdown Footer -->
        <div class="p-2 border-top bg-light text-center d-flex justify-content-around align-items-center" style="font-size: 0.78rem;">
          <a href="/admin/informasi" class="text-primary font-weight-bold text-decoration-none">
            <i class="fas fa-bullhorn mr-1"></i> Informasi
          </a>
          <span class="text-muted">•</span>
          <a href="/admin/kalender" class="text-primary font-weight-bold text-decoration-none">
            <i class="far fa-calendar-alt mr-1"></i> Kalender
          </a>
          <span class="text-muted">•</span>
          <a href="javascript:void(0)" onclick="loadNotifications(true)" class="text-secondary font-weight-bold text-decoration-none" title="Muat Ulang">
            <i class="fas fa-sync-alt mr-1"></i> Segarkan
          </a>
        </div>
      </div>
    </div>

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
          @elseif($user->role == 'tata_usaha')
            Tata Usaha
          @elseif($user->role == 'keuangan')
            Keuangan
          @elseif($user->role == 'guru')
            Guru
          @elseif($user->role == 'kesiswaan')
            Kesiswaan
          @elseif($user->role == 'kurikulum')
            Kurikulum
          @else
            Staff
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
          <div style="font-size: 0.75rem; opacity: 0.85;">
            @if($user->role == 'wali_kelas')
              Wali Kelas
            @elseif($user->role == 'tata_usaha')
              Tata Usaha
            @elseif($user->role == 'keuangan')
              Keuangan
            @else
              {{ ucfirst($user->role) }}
            @endif
          </div>
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

<style>
  /* Notification Dropdown Custom Styles */
  .navbar-circle-btn.dropdown-toggle::after {
    display: none !important;
  }
  .notif-dropdown-box {
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12) !important;
    animation: notifFadeIn 0.2s cubic-bezier(0.16, 1, 0.3, 1);
  }
  @keyframes notifFadeIn {
    from {
      opacity: 0;
      transform: translateY(-6px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  .notif-item-link {
    display: flex;
    align-items: flex-start;
    padding: 10px 14px;
    border-bottom: 1px solid #f1f5f9;
    text-decoration: none !important;
    transition: background 0.15s ease;
  }
  .notif-item-link:hover, .notif-item-link:focus {
    background-color: #f8fafc !important;
  }
  .notif-item-link:last-child {
    border-bottom: none;
  }
  .notif-icon-circle {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: #ffffff;
    font-size: 0.82rem;
  }
  .notif-title {
    font-weight: 600;
    font-size: 0.82rem;
    color: #0f172a;
    line-height: 1.25;
  }
  .notif-time {
    font-size: 0.68rem;
    color: #94a3b8;
    white-space: nowrap;
    margin-left: 8px;
    flex-shrink: 0;
  }
  .notif-desc {
    font-size: 0.74rem;
    color: #475569;
    line-height: 1.35;
    margin-top: 2px;
  }
</style>

<script>
  var currentUserId = '{{ $user->id_guru ?? $user->id ?? 1 }}';
  var readStorageKey = 'siawi_read_notifs_' + currentUserId;

  function getReadNotifIds() {
    try {
      var stored = localStorage.getItem(readStorageKey);
      return stored ? JSON.parse(stored) : [];
    } catch (e) {
      return [];
    }
  }

  function saveReadNotifIds(ids) {
    try {
      localStorage.setItem(readStorageKey, JSON.stringify(ids));
    } catch (e) {}
  }

  function renderNotificationData(data) {
    var loadingEl = document.getElementById('notif-loading-state');
    var emptyEl = document.getElementById('notif-empty-state');
    var wrapperEl = document.getElementById('notif-items-wrapper');
    var badgeEl = document.getElementById('notif-badge');
    var totalBadgeEl = document.getElementById('notif-total-badge');

    if (loadingEl) loadingEl.classList.add('d-none');

    if (!data.items || data.items.length === 0) {
      if (badgeEl) badgeEl.classList.add('d-none');
      if (totalBadgeEl) totalBadgeEl.textContent = '0 Baru';
      if (emptyEl) emptyEl.classList.remove('d-none');
      if (wrapperEl) wrapperEl.innerHTML = '';
      return;
    }

    var readIds = getReadNotifIds();
    var unreadCount = 0;

    data.items.forEach(function(item) {
      if (readIds.indexOf(item.id) === -1) {
        unreadCount++;
      }
    });

    if (badgeEl) {
      if (unreadCount > 0) {
        badgeEl.textContent = unreadCount > 99 ? '99+' : unreadCount;
        badgeEl.classList.remove('d-none');
      } else {
        badgeEl.classList.add('d-none');
      }
    }

    if (totalBadgeEl) {
      totalBadgeEl.textContent = unreadCount > 0 ? unreadCount + ' Baru' : data.items.length + ' Total';
    }

    if (emptyEl) emptyEl.classList.add('d-none');

    var html = '';
    data.items.forEach(function(item) {
      var isUnread = readIds.indexOf(item.id) === -1;
      html += '<a href="' + item.url + '" class="notif-item-link ' + (isUnread ? 'bg-light' : '') + '">' +
        '<div class="mr-3 mt-1 flex-shrink-0">' +
          '<div class="notif-icon-circle ' + item.icon_bg + '">' +
            '<i class="' + item.icon + '"></i>' +
          '</div>' +
        '</div>' +
        '<div class="flex-grow-1" style="min-width: 0;">' +
          '<div class="d-flex align-items-center justify-content-between mb-1">' +
            '<span class="notif-title ' + (isUnread ? 'font-weight-bold text-primary' : '') + '">' +
              (isUnread ? '<span class="badge badge-danger mr-1" style="font-size: 0.6rem; padding: 2px 5px; vertical-align: middle;">Baru</span> ' : '') +
              item.title +
            '</span>' +
            '<span class="notif-time">' + item.time + '</span>' +
          '</div>' +
          '<div class="notif-desc">' + item.message + '</div>' +
        '</div>' +
      '</a>';
    });

    if (wrapperEl) wrapperEl.innerHTML = html;
  }

  function loadNotifications(isManual = false) {
    var loadingEl = document.getElementById('notif-loading-state');
    var emptyEl = document.getElementById('notif-empty-state');
    var wrapperEl = document.getElementById('notif-items-wrapper');

    if (isManual) {
      if (loadingEl) loadingEl.classList.remove('d-none');
      if (emptyEl) emptyEl.classList.add('d-none');
      if (wrapperEl) wrapperEl.innerHTML = '';
    }

    fetch('{{ route("admin.notifications.get") }}', {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
    .then(function(res) {
      if (!res.ok) throw new Error('Network error');
      return res.json();
    })
    .then(function(data) {
      renderNotificationData(data);
    })
    .catch(function(err) {
      console.warn('Gagal memuat notifikasi:', err);
      if (loadingEl) loadingEl.classList.add('d-none');
      if (wrapperEl) {
        wrapperEl.innerHTML = '<div class="p-3 text-center text-muted" style="font-size: 0.8rem;">' +
          '<i class="fas fa-exclamation-circle text-danger mr-1"></i> Gagal memuat notifikasi. ' +
          '<a href="javascript:void(0)" onclick="loadNotifications(true)" class="text-primary font-weight-bold ml-1">Coba Lagi</a>' +
        '</div>';
      }
    });
  }

  // Inisialisasi otomatis menggunakan pure Vanilla JavaScript
  loadNotifications();

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
      loadNotifications();
      initNotifListeners();
    });
  } else {
    initNotifListeners();
  }

  function initNotifListeners() {
    var notifBtn = document.getElementById('notifDropdownBtn');
    if (notifBtn) {
      notifBtn.addEventListener('click', function() {
        fetch('{{ route("admin.notifications.get") }}', {
          headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(function(res) { return res.json(); })
        .then(function(data) {
          renderNotificationData(data);
          if (data.items && data.items.length > 0) {
            var allIds = data.items.map(function(it) { return it.id; });
            saveReadNotifIds(allIds);
            var badgeEl = document.getElementById('notif-badge');
            if (badgeEl) {
              badgeEl.classList.add('d-none');
            }
          }
        })
        .catch(function() {
          loadNotifications(true);
        });
      });
    }
  }
</script>