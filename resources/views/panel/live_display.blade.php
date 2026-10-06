<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Live Display Panel - {{ $setting->nama_sekolah ?? 'SMK Wisata Indonesia' }}</title>
  
  <!-- Google Fonts: Inter & Outfit -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Bootstrap 4.6 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

  <style>
    :root {
      --bg-dark: #070b14;
      --bg-card: rgba(13, 22, 41, 0.85);
      --bg-card-border: rgba(0, 168, 255, 0.28);
      --neon-blue: #00d2ff;
      --neon-cyan: #00f0ff;
      --neon-amber: #f59e0b;
      --neon-green: #10b981;
      --neon-red: #ef4444;
      --text-main: #f8fafc;
      --text-sub: #94a3b8;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background-color: var(--bg-dark);
      background-image: 
        radial-gradient(at 15% 15%, rgba(0, 168, 255, 0.12) 0px, transparent 50%),
        radial-gradient(at 85% 85%, rgba(99, 102, 241, 0.12) 0px, transparent 50%),
        radial-gradient(at 50% 50%, rgba(15, 23, 42, 0.95) 0px, transparent 100%);
      color: var(--text-main);
      font-family: 'Plus Jakarta Sans', sans-serif;
      min-height: 100vh;
      overflow-x: hidden;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      user-select: none;
    }

    /* Top Navigation / Status Header */
    .top-bar {
      padding: 12px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(7, 11, 20, 0.8);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      z-index: 100;
    }

    .brand-logo-img {
      width: 42px;
      height: 42px;
      object-fit: contain;
      filter: drop-shadow(0 2px 8px rgba(0, 168, 255, 0.4));
    }

    .brand-title {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 1.25rem;
      letter-spacing: -0.02em;
      color: #ffffff;
      line-height: 1.1;
    }

    .brand-subtitle {
      font-size: 0.75rem;
      color: var(--neon-cyan);
      font-weight: 600;
      letter-spacing: 0.05em;
      text-transform: uppercase;
    }

    .status-badge-live {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(16, 185, 129, 0.15);
      border: 1px solid rgba(16, 185, 129, 0.4);
      color: #34d399;
      font-size: 0.76rem;
      font-weight: 700;
      padding: 5px 12px;
      border-radius: 20px;
      letter-spacing: 0.05em;
    }

    .pulse-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background-color: #10b981;
      box-shadow: 0 0 10px #10b981;
      animation: pulseAnimation 1.5s infinite;
    }

    @keyframes pulseAnimation {
      0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
      70% { transform: scale(1.1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
      100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    }

    .btn-panel-action {
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.15);
      color: #ffffff;
      padding: 6px 14px;
      border-radius: 8px;
      font-size: 0.8rem;
      font-weight: 600;
      transition: all 0.2s ease;
      text-decoration: none !important;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-panel-action:hover {
      background: var(--neon-blue);
      color: #070b14;
      border-color: var(--neon-blue);
      box-shadow: 0 0 15px rgba(0, 210, 255, 0.4);
    }

    /* Main Grid Layout */
    .panel-container {
      padding: 16px 24px 20px;
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .main-grid-row {
      display: grid;
      grid-template-columns: 1.65fr 1fr;
      gap: 20px;
      align-items: stretch;
    }

    @media (max-width: 991.98px) {
      .main-grid-row {
        grid-template-columns: 1fr;
      }
    }

    /* Central Video Screen */
    .video-screen-frame {
      position: relative;
      background: #000000;
      border-radius: 16px;
      border: 2px solid rgba(0, 168, 255, 0.6);
      box-shadow: 0 0 30px rgba(0, 168, 255, 0.25), inset 0 0 20px rgba(0, 168, 255, 0.15);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      min-height: 380px;
      height: 100%;
    }

    .video-element {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: 1;
    }

    .video-overlay-banner {
      position: relative;
      z-index: 10;
      background: linear-gradient(180deg, rgba(7, 15, 33, 0.2) 0%, rgba(7, 15, 33, 0.95) 100%);
      backdrop-filter: blur(8px);
      padding: 16px 20px;
      text-align: center;
      border-top: 1px solid rgba(0, 210, 255, 0.35);
    }

    .greeting-title {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 1.5rem;
      letter-spacing: -0.01em;
      color: #ffffff;
      text-transform: uppercase;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8);
      margin-bottom: 4px;
    }

    .greeting-subtitle {
      font-size: 0.92rem;
      font-weight: 600;
      color: var(--neon-cyan);
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
    }

    /* Right Side Ranking Cards */
    .sidebar-ranking-column {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .ranking-card {
      background: var(--bg-card);
      border: 1px solid var(--bg-card-border);
      border-radius: 16px;
      padding: 16px 18px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
      backdrop-filter: blur(14px);
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .ranking-card-header {
      display: flex;
      align-items: center;
      gap: 10px;
      padding-bottom: 12px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      margin-bottom: 12px;
    }

    .ranking-card-icon {
      font-size: 1.15rem;
    }

    .ranking-card-title {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 0.92rem;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      color: #ffffff;
      margin-bottom: 0;
    }

    .ranking-list {
      list-style: none;
      padding: 0;
      margin: 0;
      display: flex;
      flex-direction: column;
      gap: 8px;
      flex: 1;
      justify-content: space-around;
    }

    .ranking-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 6px 10px;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.04);
      font-size: 0.88rem;
    }

    .ranking-item:hover {
      background: rgba(0, 168, 255, 0.08);
      border-color: rgba(0, 168, 255, 0.2);
    }

    .rank-num-name {
      display: flex;
      align-items: center;
      gap: 10px;
      font-weight: 700;
      color: #f1f5f9;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .rank-num {
      color: var(--neon-blue);
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 0.95rem;
      width: 18px;
    }

    .badge-percent {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 0.88rem;
      color: #34d399;
      background: rgba(16, 185, 129, 0.12);
      padding: 2px 10px;
      border-radius: 6px;
      border: 1px solid rgba(16, 185, 129, 0.3);
      white-space: nowrap;
    }

    .badge-time {
      font-family: 'Outfit', sans-serif;
      font-weight: 700;
      font-size: 0.86rem;
      color: #38bdf8;
      background: rgba(56, 189, 248, 0.12);
      padding: 2px 10px;
      border-radius: 6px;
      border: 1px solid rgba(56, 189, 248, 0.3);
      white-space: nowrap;
    }

    /* Bottom Late Students Section */
    .late-students-section {
      background: var(--bg-card);
      border: 1px solid rgba(239, 68, 68, 0.3);
      border-radius: 16px;
      padding: 16px 20px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(14px);
    }

    .late-section-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 14px;
    }

    .late-section-title {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 0.98rem;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      color: #f87171;
      margin-bottom: 0;
    }

    .late-cards-container {
      display: flex;
      gap: 14px;
      overflow-x: auto;
      padding-bottom: 6px;
      scrollbar-width: thin;
      scrollbar-color: rgba(239, 68, 68, 0.4) transparent;
    }

    .late-cards-container::-webkit-scrollbar {
      height: 5px;
    }
    .late-cards-container::-webkit-scrollbar-thumb {
      background: rgba(239, 68, 68, 0.4);
      border-radius: 10px;
    }

    .late-student-card {
      min-width: 175px;
      max-width: 185px;
      background: #090e1a;
      border: 1px solid rgba(239, 68, 68, 0.45);
      border-radius: 14px;
      padding: 14px 12px;
      text-align: center;
      position: relative;
      flex-shrink: 0;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .late-student-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 15px rgba(239, 68, 68, 0.3);
    }

    .student-avatar-wrapper {
      position: relative;
      width: 58px;
      height: 58px;
      margin: 0 auto 10px;
    }

    .student-avatar-img {
      width: 58px;
      height: 58px;
      border-radius: 50%;
      object-fit: cover;
      border: 2px solid #ef4444;
    }

    .student-avatar-fallback {
      width: 58px;
      height: 58px;
      border-radius: 50%;
      background: rgba(239, 68, 68, 0.15);
      border: 2px solid #ef4444;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.35rem;
      font-weight: 800;
      color: #f87171;
    }

    .point-badge-corner {
      position: absolute;
      top: -4px;
      right: -10px;
      background: #ef4444;
      color: #ffffff;
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 0.72rem;
      padding: 2px 7px;
      border-radius: 10px;
      border: 2px solid #090e1a;
      box-shadow: 0 2px 5px rgba(0,0,0,0.5);
    }

    .student-card-name {
      font-weight: 700;
      font-size: 0.84rem;
      color: #ffffff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 2px;
    }

    .student-card-class {
      font-size: 0.72rem;
      font-weight: 600;
      color: #94a3b8;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 8px;
    }

    .time-badge-late {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: rgba(239, 68, 68, 0.15);
      border: 1px solid rgba(239, 68, 68, 0.4);
      color: #f87171;
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 0.78rem;
      padding: 3px 10px;
      border-radius: 20px;
    }

    .empty-late-state {
      padding: 24px;
      text-align: center;
      width: 100%;
      color: #34d399;
      font-weight: 600;
      font-size: 0.95rem;
      background: rgba(16, 185, 129, 0.05);
      border-radius: 12px;
      border: 1px dashed rgba(16, 185, 129, 0.3);
    }
  </style>
</head>
<body>

  <!-- Top Status & Navigation Bar -->
  <header class="top-bar">
    <div class="d-flex align-items-center">
      @if($setting && $setting->logo && file_exists(public_path('storage/gambar/' . $setting->logo)))
        <img src="{{ asset('storage/gambar/' . $setting->logo) }}" alt="Logo" class="brand-logo-img mr-3">
      @else
        <div class="mr-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px; background: var(--neon-blue); color: #070b14; font-weight: 900; font-size: 1.2rem;">
          S
        </div>
      @endif
      <div>
        <div class="brand-title">{{ $setting->nama_sekolah ?? 'SMK WISATA INDONESIA' }}</div>
        <div class="brand-subtitle">{{ $setting->nama_app ?? 'SIAWI' }} • LIVE WALLBOARD PANEL</div>
      </div>
    </div>

    <div class="d-flex align-items-center" style="gap: 12px;">
      <div class="status-badge-live">
        <span class="pulse-dot"></span> LIVE STREAM
      </div>
      <button type="button" class="btn-panel-action" id="btnFullscreen" onclick="toggleFullScreen()" title="Mode Layar Penuh (F11)">
        <i class="fas fa-expand"></i> Layar Penuh
      </button>
      <a href="/admin/dashboard" class="btn-panel-action" title="Kembali ke Dashboard">
        <i class="fas fa-arrow-left"></i> Dashboard
      </a>
    </div>
  </header>

  <!-- Main Content Dashboard -->
  <main class="panel-container">
    
    <!-- Row 1: Central Media + Sidebar Top 5 Rankings -->
    <div class="main-grid-row">
      
      <!-- Central Video Frame -->
      <div class="video-screen-frame">
        @if($videoUrl)
          <video class="video-element" id="mainVideoPlayer" autoplay muted loop playsinline>
            <source src="{{ $videoUrl }}" type="video/mp4">
            Video format tidak didukung browser.
          </video>
        @else
          <!-- Default Animated School Display if no custom MP4 uploaded -->
          <div class="video-element d-flex align-items-center justify-content-center" style="background: radial-gradient(circle at center, #0f1f3d 0%, #050b18 100%);">
            <div class="text-center p-4">
              <div class="mb-3">
                <i class="fas fa-school text-primary" style="font-size: 4.5rem; filter: drop-shadow(0 0 20px rgba(0, 168, 255, 0.6));"></i>
              </div>
              <h2 class="font-weight-bold text-white mb-1" style="font-family: 'Outfit'; letter-spacing: 0.05em;">{{ $setting->nama_sekolah ?? 'SMK WISATA INDONESIA' }}</h2>
              <p class="text-muted small mb-0">Video dapat diunggah melalui menu Pengaturan Aplikasi</p>
            </div>
          </div>
        @endif

        <!-- Floating Glass Banner at Bottom of Video -->
        <div class="video-overlay-banner">
          <div class="greeting-title" id="liveGreetingText">{{ $greetingHeader }}</div>
          <div class="greeting-subtitle">
            <span id="liveDateText">{{ $tanggalFormatted }}</span> | <span id="liveClockText">{{ $jamFormatted }}</span>
          </div>
        </div>
      </div>

      <!-- Right Column: Top 5 Attendance Rankings -->
      <div class="sidebar-ranking-column">
        
        <!-- 1. TOP 5 KEHADIRAN KELAS BULAN INI -->
        <div class="ranking-card">
          <div class="ranking-card-header">
            <span class="ranking-card-icon">🥇</span>
            <h4 class="ranking-card-title">TOP 5 KEHADIRAN KELAS (BULAN INI)</h4>
          </div>
          <ul class="ranking-list" id="topKelasListContainer">
            @forelse($topKelasBulanan as $index => $kelas)
              <li class="ranking-item">
                <div class="rank-num-name">
                  <span class="rank-num">{{ $loop->iteration }}.</span>
                  <span>{{ $kelas['nama_kelas'] }}</span>
                </div>
                <span class="badge-percent">({{ $kelas['persen_label'] }})</span>
              </li>
            @empty
              <li class="ranking-item text-center text-muted py-3">
                <span>Belum ada data rekap bulan ini</span>
              </li>
            @endforelse
          </ul>
        </div>

        <!-- 2. TOP 5 KEHADIRAN SISWA TERCEPAT HARI INI -->
        <div class="ranking-card">
          <div class="ranking-card-header">
            <span class="ranking-card-icon" style="color: #38bdf8;"><i class="fas fa-bolt"></i></span>
            <h4 class="ranking-card-title">TOP 5 SISWA TERCEPAT (HARI INI)</h4>
          </div>
          <ul class="ranking-list" id="topSiswaTercepatContainer">
            @forelse($topSiswaTercepat as $index => $siswa)
              <li class="ranking-item">
                <div class="rank-num-name">
                  <span class="rank-num">{{ $loop->iteration }}.</span>
                  <span title="{{ $siswa['nama'] }} ({{ $siswa['kelas'] }})">{{ $siswa['nama'] }}</span>
                </div>
                <span class="badge-time">{{ $siswa['jam_masuk'] }}</span>
              </li>
            @empty
              <li class="ranking-item text-center text-muted py-3">
                <span>Belum ada siswa yang hadir hari ini</span>
              </li>
            @endforelse
          </ul>
        </div>

      </div>
    </div>

    <!-- Row 2: Bottom Late Students Live Ticker -->
    <div class="late-students-section">
      <div class="late-section-header">
        <i class="fas fa-exclamation-triangle text-danger" style="font-size: 1.25rem;"></i>
        <h4 class="late-section-title">DATA SISWA TERLAMBAT HARI INI (<span id="totalLateCount">{{ $totalTerlambat }}</span>)</h4>
      </div>

      <div class="late-cards-container" id="lateStudentsContainer">
        @forelse($siswaTerlambatList as $item)
          <div class="late-student-card">
            <div class="student-avatar-wrapper">
              @if($item['foto_url'])
                <img src="{{ $item['foto_url'] }}" alt="{{ $item['nama'] }}" class="student-avatar-img">
              @else
                <div class="student-avatar-fallback">{{ $item['inisial'] }}</div>
              @endif
              <span class="point-badge-corner" title="Poin Pelanggaran">{{ $item['poin'] }}</span>
            </div>
            <div class="student-card-name" title="{{ $item['nama'] }}">{{ $item['nama'] }}</div>
            <div class="student-card-class">{{ $item['kelas'] }}</div>
            <div class="time-badge-late">
              <i class="far fa-clock"></i> {{ $item['jam_masuk'] }}
            </div>
          </div>
        @empty
          <div class="empty-late-state" id="emptyLateState">
            <i class="fas fa-check-circle mr-2"></i> Tidak ada siswa terlambat pada hari ini. Semua siswa hadir tepat waktu!
          </div>
        @endforelse
      </div>
    </div>

  </main>

  <!-- Live Polling & Clock Javascript -->
  <script>
    // 1. Live Real-time Clock
    function updateLiveClock() {
      const now = new Date();
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      const seconds = String(now.getSeconds()).padStart(2, '0');
      
      const clockEl = document.getElementById('liveClockText');
      if (clockEl) {
        clockEl.textContent = `${hours}:${minutes}:${seconds} WIB`;
      }
    }
    setInterval(updateLiveClock, 1000);
    updateLiveClock();

    // 2. Fullscreen Toggle
    function toggleFullScreen() {
      if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => {
          console.warn('Fullscreen request failed:', err);
        });
        document.getElementById('btnFullscreen').innerHTML = '<i class="fas fa-compress"></i> Keluar Layar Penuh';
      } else {
        if (document.exitFullscreen) {
          document.exitFullscreen();
          document.getElementById('btnFullscreen').innerHTML = '<i class="fas fa-expand"></i> Layar Penuh';
        }
      }
    }

    document.addEventListener('fullscreenchange', function() {
      const btn = document.getElementById('btnFullscreen');
      if (btn) {
        if (document.fullscreenElement) {
          btn.innerHTML = '<i class="fas fa-compress"></i> Keluar Layar Penuh';
        } else {
          btn.innerHTML = '<i class="fas fa-expand"></i> Layar Penuh';
        }
      }
    });

    // 3. Background Live Polling (Every 25 seconds) tanpa reload video
    function fetchLivePanelData() {
      fetch('{{ url("/admin/live-panel/data") }}', {
        headers: {
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest'
        }
      })
      .then(res => res.json())
      .then(res => {
        if (!res.success || !res.data) return;
        const d = res.data;

        // Update Greeting & Tanggal
        if (d.greetingHeader) document.getElementById('liveGreetingText').textContent = d.greetingHeader;
        if (d.tanggalFormatted) document.getElementById('liveDateText').textContent = d.tanggalFormatted;
        if (d.totalTerlambat !== undefined) document.getElementById('totalLateCount').textContent = d.totalTerlambat;

        // Update Top Kelas Bulanan
        const topKelasEl = document.getElementById('topKelasListContainer');
        if (topKelasEl && d.topKelasBulanan) {
          if (d.topKelasBulanan.length === 0) {
            topKelasEl.innerHTML = '<li class="ranking-item text-center text-muted py-3"><span>Belum ada data rekap bulan ini</span></li>';
          } else {
            let html = '';
            d.topKelasBulanan.forEach((k, i) => {
              html += `<li class="ranking-item">
                <div class="rank-num-name">
                  <span class="rank-num">${i + 1}.</span>
                  <span>${k.nama_kelas}</span>
                </div>
                <span class="badge-percent">(${k.persen_label})</span>
              </li>`;
            });
            topKelasEl.innerHTML = html;
          }
        }

        // Update Top Siswa Tercepat Hari Ini
        const topSiswaEl = document.getElementById('topSiswaTercepatContainer');
        if (topSiswaEl && d.topSiswaTercepat) {
          if (d.topSiswaTercepat.length === 0) {
            topSiswaEl.innerHTML = '<li class="ranking-item text-center text-muted py-3"><span>Belum ada siswa yang hadir hari ini</span></li>';
          } else {
            let html = '';
            d.topSiswaTercepat.forEach((s, i) => {
              html += `<li class="ranking-item">
                <div class="rank-num-name">
                  <span class="rank-num">${i + 1}.</span>
                  <span title="${s.nama} (${s.kelas})">${s.nama}</span>
                </div>
                <span class="badge-time">${s.jam_masuk}</span>
              </li>`;
            });
            topSiswaEl.innerHTML = html;
          }
        }

        // Update Siswa Terlambat Hari Ini
        const lateContainer = document.getElementById('lateStudentsContainer');
        if (lateContainer && d.siswaTerlambatList) {
          if (d.siswaTerlambatList.length === 0) {
            lateContainer.innerHTML = '<div class="empty-late-state" id="emptyLateState"><i class="fas fa-check-circle mr-2"></i> Tidak ada siswa terlambat pada hari ini. Semua siswa hadir tepat waktu!</div>';
          } else {
            let html = '';
            d.siswaTerlambatList.forEach(item => {
              const avatar = item.foto_url 
                ? `<img src="${item.foto_url}" alt="${item.nama}" class="student-avatar-img">`
                : `<div class="student-avatar-fallback">${item.inisial}</div>`;

              html += `<div class="late-student-card">
                <div class="student-avatar-wrapper">
                  ${avatar}
                  <span class="point-badge-corner" title="Poin Pelanggaran">${item.poin}</span>
                </div>
                <div class="student-card-name" title="${item.nama}">${item.nama}</div>
                <div class="student-card-class">${item.kelas}</div>
                <div class="time-badge-late">
                  <i class="far fa-clock"></i> ${item.jam_masuk}
                </div>
              </div>`;
            });
            lateContainer.innerHTML = html;
          }
        }
      })
      .catch(err => {
        console.warn('Background polling error:', err);
      });
    }

    // Interval background update setiap 25 detik
    setInterval(fetchLivePanelData, 25000);

    // Otomatis play video saat termuat
    window.addEventListener('DOMContentLoaded', function() {
      const vid = document.getElementById('mainVideoPlayer');
      if (vid) {
        vid.play().catch(e => console.log('Autoplay handled:', e));
      }
    });
  </script>
</body>
</html>
