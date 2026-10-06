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

    html, body {
      width: 100%;
      height: 100vh;
      max-height: 100vh;
      overflow: hidden;
      background-color: var(--bg-dark);
      background-image: 
        radial-gradient(at 15% 15%, rgba(0, 168, 255, 0.12) 0px, transparent 50%),
        radial-gradient(at 85% 85%, rgba(99, 102, 241, 0.12) 0px, transparent 50%),
        radial-gradient(at 50% 50%, rgba(15, 23, 42, 0.95) 0px, transparent 100%);
      color: var(--text-main);
      font-family: 'Plus Jakarta Sans', sans-serif;
      display: flex;
      flex-direction: column;
      user-select: none;
    }

    /* Top Navigation / Status Header */
    .top-bar {
      flex-shrink: 0;
      padding: 10px 20px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(7, 11, 20, 0.85);
      backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      z-index: 100;
      height: 60px;
    }

    .brand-logo-img {
      width: 38px;
      height: 38px;
      object-fit: contain;
      filter: drop-shadow(0 2px 8px rgba(0, 168, 255, 0.4));
    }

    .brand-title {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: clamp(1rem, 1.3vw, 1.25rem);
      letter-spacing: -0.02em;
      color: #ffffff;
      line-height: 1.1;
    }

    .brand-subtitle {
      font-size: clamp(0.68rem, 0.8vw, 0.78rem);
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
      font-size: 0.75rem;
      font-weight: 700;
      padding: 4px 10px;
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
      padding: 5px 12px;
      border-radius: 8px;
      font-size: 0.78rem;
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

    /* Main Grid Layout filling 100% remaining viewport */
    .panel-container {
      padding: clamp(8px, 1.2vh, 16px) clamp(12px, 1.4vw, 24px);
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: clamp(8px, 1.2vh, 14px);
      min-height: 0;
      overflow: hidden;
    }

    .main-grid-row {
      display: grid;
      grid-template-columns: 1.65fr 1fr;
      gap: clamp(10px, 1.2vw, 18px);
      flex: 2.3;
      min-height: 0;
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
      border-radius: clamp(12px, 1.6vh, 18px);
      border: 2px solid rgba(0, 168, 255, 0.6);
      box-shadow: 0 0 30px rgba(0, 168, 255, 0.25), inset 0 0 20px rgba(0, 168, 255, 0.15);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      height: 100%;
      min-height: 0;
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
      padding: clamp(6px, 1.2vh, 14px) clamp(12px, 1.5vw, 22px);
      text-align: center;
      border-top: 1px solid rgba(0, 210, 255, 0.35);
    }

    .greeting-title {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: clamp(1.05rem, 1.7vw, 1.55rem);
      letter-spacing: -0.01em;
      color: #ffffff;
      text-transform: uppercase;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8);
      margin-bottom: 2px;
      line-height: 1.2;
    }

    .greeting-subtitle {
      font-size: clamp(0.75rem, 1vw, 0.95rem);
      font-weight: 600;
      color: var(--neon-cyan);
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.8);
    }

    /* Right Side Ranking Cards */
    .sidebar-ranking-column {
      display: flex;
      flex-direction: column;
      gap: clamp(8px, 1vh, 12px);
      height: 100%;
      min-height: 0;
    }

    .ranking-card {
      background: var(--bg-card);
      border: 1px solid var(--bg-card-border);
      border-radius: clamp(10px, 1.4vh, 16px);
      padding: clamp(8px, 1.1vh, 14px) clamp(10px, 1.2vw, 16px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
      backdrop-filter: blur(14px);
      flex: 1;
      min-height: 0;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .ranking-card-header {
      display: flex;
      align-items: center;
      gap: 8px;
      padding-bottom: clamp(4px, 0.6vh, 8px);
      border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      margin-bottom: clamp(4px, 0.6vh, 8px);
      flex-shrink: 0;
    }

    .ranking-card-icon {
      font-size: clamp(0.95rem, 1.1vw, 1.2rem);
    }

    .ranking-card-title {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: clamp(0.76rem, 0.95vw, 0.92rem);
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
      gap: clamp(3px, 0.5vh, 6px);
      flex: 1;
      min-height: 0;
      justify-content: space-evenly;
    }

    .ranking-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: clamp(3px, 0.5vh, 6px) clamp(8px, 0.8vw, 12px);
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.04);
      font-size: clamp(0.75rem, 0.9vw, 0.9rem);
    }

    .ranking-item:hover {
      background: rgba(0, 168, 255, 0.08);
      border-color: rgba(0, 168, 255, 0.2);
    }

    .rank-num-name {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 700;
      color: #f1f5f9;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      flex: 1;
      min-width: 0;
    }

    .rank-num-name span:last-child {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .rank-num {
      color: var(--neon-blue);
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: clamp(0.8rem, 0.95vw, 0.95rem);
      width: 18px;
      flex-shrink: 0;
    }

    .badge-percent {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: clamp(0.72rem, 0.85vw, 0.88rem);
      color: #34d399;
      background: rgba(16, 185, 129, 0.12);
      padding: 2px 8px;
      border-radius: 6px;
      border: 1px solid rgba(16, 185, 129, 0.3);
      white-space: nowrap;
      flex-shrink: 0;
      margin-left: 8px;
    }

    .badge-time {
      font-family: 'Outfit', sans-serif;
      font-weight: 700;
      font-size: clamp(0.72rem, 0.85vw, 0.86rem);
      color: #38bdf8;
      background: rgba(56, 189, 248, 0.12);
      padding: 2px 8px;
      border-radius: 6px;
      border: 1px solid rgba(56, 189, 248, 0.3);
      white-space: nowrap;
      flex-shrink: 0;
      margin-left: 8px;
    }

    /* Bottom Late Students Section - Responsively Scaled with Flex */
    .late-students-section {
      background: var(--bg-card);
      border: 1px solid rgba(239, 68, 68, 0.3);
      border-radius: clamp(12px, 1.6vh, 18px);
      padding: clamp(8px, 1.2vh, 14px) clamp(12px, 1.4vw, 20px);
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
      backdrop-filter: blur(14px);
      flex: 1.15;
      min-height: 0;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .late-section-header {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: clamp(4px, 0.8vh, 8px);
      flex-shrink: 0;
    }

    .late-section-title {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: clamp(0.82rem, 1.05vw, 1.05rem);
      letter-spacing: 0.04em;
      text-transform: uppercase;
      color: #f87171;
      margin-bottom: 0;
    }

    .late-cards-container {
      display: flex;
      gap: clamp(10px, 1.2vw, 16px);
      overflow-x: auto;
      padding-bottom: 2px;
      scrollbar-width: none;
      -ms-overflow-style: none;
      scroll-behavior: auto;
      flex: 1;
      min-height: 0;
      align-items: stretch;
    }

    .late-cards-container::-webkit-scrollbar {
      display: none;
    }

    .late-student-card {
      min-width: clamp(250px, 20vw, 360px);
      max-width: clamp(280px, 24vw, 420px);
      background: #090e1a;
      border: 1px solid rgba(239, 68, 68, 0.45);
      border-radius: clamp(10px, 1.4vh, 14px);
      padding: clamp(6px, 1vh, 12px) clamp(10px, 1.1vw, 16px);
      display: flex;
      align-items: center;
      gap: clamp(10px, 1.2vw, 16px);
      position: relative;
      flex-shrink: 0;
      height: 100%;
      transition: transform 0.2s ease, box-shadow 0.2s ease;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .late-student-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 0 16px rgba(239, 68, 68, 0.35);
      border-color: rgba(239, 68, 68, 0.7);
    }

    .student-avatar-wrapper {
      position: relative;
      height: 100%;
      aspect-ratio: 1 / 1;
      max-height: clamp(54px, 10vh, 90px);
      width: auto;
      flex-shrink: 0;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .student-avatar-img {
      width: 100%;
      height: 100%;
      aspect-ratio: 1 / 1;
      border-radius: clamp(8px, 1.2vh, 12px);
      object-fit: cover;
      border: 2px solid #ef4444;
      background: #1e293b;
      display: block;
    }

    .student-avatar-fallback {
      width: 100%;
      height: 100%;
      aspect-ratio: 1 / 1;
      border-radius: clamp(8px, 1.2vh, 12px);
      background: linear-gradient(135deg, rgba(239, 68, 68, 0.25), rgba(239, 68, 68, 0.08));
      border: 2px solid #ef4444;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: clamp(1.3rem, 2vh, 1.8rem);
      font-weight: 800;
      color: #f87171;
    }

    .point-badge-corner {
      position: absolute;
      top: -4px;
      right: -5px;
      background: #ef4444;
      color: #ffffff;
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: clamp(0.68rem, 0.8vw, 0.82rem);
      padding: 2px 5px;
      border-radius: 8px;
      border: 2px solid #090e1a;
      box-shadow: 0 2px 5px rgba(0,0,0,0.6);
      line-height: 1;
    }

    .student-card-info {
      flex: 1;
      min-width: 0;
      display: flex;
      flex-direction: column;
      justify-content: center;
      text-align: left;
    }

    .student-card-name {
      font-weight: 800;
      font-size: clamp(0.85rem, 1.1vw, 1.15rem);
      color: #ffffff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: clamp(1px, 0.4vh, 4px);
      line-height: 1.2;
    }

    .student-card-class {
      font-size: clamp(0.72rem, 0.9vw, 0.92rem);
      font-weight: 600;
      color: #94a3b8;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: clamp(3px, 0.6vh, 6px);
    }

    .time-badge-late {
      display: inline-flex;
      align-items: center;
      align-self: flex-start;
      gap: 4px;
      background: rgba(239, 68, 68, 0.16);
      border: 1px solid rgba(239, 68, 68, 0.45);
      color: #fca5a5;
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: clamp(0.72rem, 0.9vw, 0.88rem);
      padding: clamp(1px, 0.3vh, 4px) clamp(6px, 0.6vw, 10px);
      border-radius: 5px;
    }

    .empty-late-state {
      padding: 16px;
      text-align: center;
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #34d399;
      font-weight: 600;
      font-size: clamp(0.85rem, 1vw, 1.05rem);
      background: rgba(16, 185, 129, 0.05);
      border-radius: 10px;
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
        @if(count($siswaTerlambatList) > 0)
          @php
            $repeatCount = count($siswaTerlambatList) <= 3 ? 4 : 2;
          @endphp
          @for($r = 0; $r < $repeatCount; $r++)
            @foreach($siswaTerlambatList as $item)
              <div class="late-student-card">
                <div class="student-avatar-wrapper">
                  @if($item['foto_url'])
                    <img src="{{ $item['foto_url'] }}" alt="{{ $item['nama'] }}" class="student-avatar-img">
                  @else
                    <div class="student-avatar-fallback">{{ $item['inisial'] }}</div>
                  @endif
                  <span class="point-badge-corner" title="Poin Pelanggaran">{{ $item['poin'] }}</span>
                </div>
                <div class="student-card-info">
                  <div class="student-card-name" title="{{ $item['nama'] }}">{{ $item['nama'] }}</div>
                  <div class="student-card-class">{{ $item['kelas'] }}</div>
                  <div class="time-badge-late">
                    <i class="far fa-clock"></i> {{ $item['jam_masuk'] }}
                  </div>
                </div>
              </div>
            @endforeach
          @endfor
        @else
          <div class="empty-late-state" id="emptyLateState">
            <i class="fas fa-check-circle mr-2"></i> Tidak ada siswa terlambat pada hari ini. Semua siswa hadir tepat waktu!
          </div>
        @endif
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
            currentRepeatCount = 1;
          } else {
            let singleSetHtml = '';
            d.siswaTerlambatList.forEach(item => {
              const avatar = item.foto_url 
                ? `<img src="${item.foto_url}" alt="${item.nama}" class="student-avatar-img">`
                : `<div class="student-avatar-fallback">${item.inisial}</div>`;

              singleSetHtml += `<div class="late-student-card">
                <div class="student-avatar-wrapper">
                  ${avatar}
                  <span class="point-badge-corner" title="Poin Pelanggaran">${item.poin}</span>
                </div>
                <div class="student-card-info">
                  <div class="student-card-name" title="${item.nama}">${item.nama}</div>
                  <div class="student-card-class">${item.kelas}</div>
                  <div class="time-badge-late">
                    <i class="far fa-clock"></i> ${item.jam_masuk}
                  </div>
                </div>
              </div>`;
            });

            currentRepeatCount = d.siswaTerlambatList.length <= 3 ? 4 : 2;
            let fullHtml = '';
            for (let r = 0; r < currentRepeatCount; r++) {
              fullHtml += singleSetHtml;
            }
            lateContainer.innerHTML = fullHtml;
          }
        }
      })
      .catch(err => {
        console.warn('Background polling error:', err);
      });
    }

    // Interval background update setiap 25 detik
    setInterval(fetchLivePanelData, 25000);

    // 4. Infinite Seamless Marquee Loop untuk Kartu Siswa Terlambat di TV
    let isTickerPaused = false;
    let scrollPos = 0;
    const scrollSpeed = 0.8; // Kecepatan gerak kontinu (pixel per frame)
    let currentRepeatCount = {{ count($siswaTerlambatList) > 0 ? (count($siswaTerlambatList) <= 3 ? 4 : 2) : 1 }};

    function initAutoScrollLateCards() {
      const container = document.getElementById('lateStudentsContainer');
      if (!container) return;

      container.addEventListener('mouseenter', () => isTickerPaused = true);
      container.addEventListener('mouseleave', () => isTickerPaused = false);
      container.addEventListener('touchstart', () => isTickerPaused = true, { passive: true });
      container.addEventListener('touchend', () => isTickerPaused = false, { passive: true });

      function tickerStep() {
        if (!isTickerPaused && container) {
          const totalScrollWidth = container.scrollWidth;
          const setWidth = totalScrollWidth / currentRepeatCount;

          if (setWidth > 0 && totalScrollWidth > container.clientWidth) {
            scrollPos += scrollSpeed;
            if (scrollPos >= setWidth) {
              scrollPos -= setWidth;
            }
            container.scrollLeft = scrollPos;
          }
        }
        requestAnimationFrame(tickerStep);
      }

      requestAnimationFrame(tickerStep);
    }

    // Inisialisasi saat halaman selesai dimuat
    window.addEventListener('DOMContentLoaded', function() {
      const vid = document.getElementById('mainVideoPlayer');
      if (vid) {
        vid.play().catch(e => console.log('Autoplay handled:', e));
      }
      initAutoScrollLateCards();
    });
  </script>
</body>
</html>
