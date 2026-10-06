<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Live Display Panel - {{ $setting->nama_sekolah ?? 'SMK Wisata Indonesia' }}</title>
  
  <!-- Google Fonts: Inter & Outfit -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Bootstrap 4.6 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

  <style>
    :root {
      --bg-dark: #050811;
      --bg-card: rgba(10, 18, 36, 0.88);
      --bg-card-border: rgba(0, 168, 255, 0.25);
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
        radial-gradient(at 5% 5%, rgba(0, 168, 255, 0.16) 0px, transparent 40%),
        radial-gradient(at 95% 95%, rgba(99, 102, 241, 0.14) 0px, transparent 40%),
        radial-gradient(at 50% 50%, rgba(7, 12, 24, 0.98) 0px, transparent 100%);
      color: var(--text-main);
      font-family: 'Plus Jakarta Sans', sans-serif;
      display: flex;
      flex-direction: column;
      user-select: none;
    }

    /* Top Navigation / Status Header */
    .top-bar {
      flex-shrink: 0;
      padding: 8px 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: rgba(5, 9, 18, 0.92);
      backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(0, 168, 255, 0.2);
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
      z-index: 100;
      height: 58px;
    }

    .brand-logo-img {
      width: 38px;
      height: 38px;
      object-fit: contain;
      border-radius: 8px;
      padding: 2px;
      background: rgba(0, 168, 255, 0.1);
      border: 1px solid rgba(0, 168, 255, 0.35);
      filter: drop-shadow(0 2px 10px rgba(0, 168, 255, 0.5));
    }

    .brand-title {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 1.18rem;
      letter-spacing: -0.01em;
      color: #ffffff;
      line-height: 1.1;
      text-shadow: 0 0 12px rgba(255, 255, 255, 0.2);
    }

    .brand-subtitle {
      font-size: 0.72rem;
      color: var(--neon-cyan);
      font-weight: 700;
      letter-spacing: 0.08em;
      text-transform: uppercase;
    }

    .status-badge-live {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: rgba(16, 185, 129, 0.16);
      border: 1px solid rgba(16, 185, 129, 0.45);
      color: #34d399;
      font-size: 0.75rem;
      font-weight: 800;
      padding: 4px 12px;
      border-radius: 20px;
      letter-spacing: 0.06em;
      box-shadow: 0 0 12px rgba(16, 185, 129, 0.25);
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
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.14);
      color: #ffffff;
      padding: 5px 14px;
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
      color: #050811;
      border-color: var(--neon-blue);
      box-shadow: 0 0 15px rgba(0, 210, 255, 0.45);
    }

    /* Main Dashboard Layout */
    .panel-container {
      padding: 12px 18px 14px;
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 12px;
      min-height: 0;
      overflow: hidden;
    }

    .main-grid-row {
      display: grid;
      grid-template-columns: 1.75fr 1fr;
      gap: 14px;
      flex: 1;
      min-height: 0;
      align-items: stretch;
    }

    @media (max-width: 991.98px) {
      .main-grid-row {
        grid-template-columns: 1fr;
      }
    }

    /* Video Player Frame with Ambient Glow */
    .video-screen-frame {
      position: relative;
      background: #020409;
      border-radius: 14px;
      border: 1px solid rgba(0, 168, 255, 0.35);
      box-shadow: 0 8px 30px rgba(0, 0, 0, 0.7), inset 0 0 25px rgba(0, 168, 255, 0.12);
      overflow: hidden;
      display: flex;
      flex-direction: column;
      justify-content: flex-end;
      height: 100%;
      min-height: 0;
    }

    /* Ambient backdrop video for smooth fill on vertical/portrait videos */
    .video-ambient-backdrop {
      position: absolute;
      top: -10%;
      left: -10%;
      width: 120%;
      height: 120%;
      object-fit: cover;
      filter: blur(24px) brightness(0.55);
      opacity: 0.6;
      z-index: 1;
      pointer-events: none;
    }

    .video-element-main {
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: contain;
      z-index: 2;
    }

    .video-overlay-banner {
      position: relative;
      z-index: 10;
      background: linear-gradient(180deg, rgba(5, 9, 18, 0) 0%, rgba(5, 9, 18, 0.95) 85%);
      backdrop-filter: blur(8px);
      padding: 10px 18px 14px;
      text-align: center;
      border-top: 1px solid rgba(0, 210, 255, 0.22);
    }

    .greeting-title {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: clamp(1.1rem, 1.6vw, 1.45rem);
      letter-spacing: -0.01em;
      color: #ffffff;
      text-transform: uppercase;
      text-shadow: 0 2px 10px rgba(0, 0, 0, 0.9);
      margin-bottom: 2px;
      line-height: 1.15;
    }

    .greeting-subtitle {
      font-size: clamp(0.78rem, 0.95vw, 0.92rem);
      font-weight: 600;
      color: var(--neon-cyan);
      text-shadow: 0 2px 8px rgba(0, 0, 0, 0.9);
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    /* Right Column: Sleek Top 5 Rankings */
    .sidebar-ranking-column {
      display: flex;
      flex-direction: column;
      gap: 12px;
      height: 100%;
      min-height: 0;
    }

    .ranking-card {
      background: linear-gradient(145deg, rgba(14, 24, 46, 0.9) 0%, rgba(7, 13, 27, 0.95) 100%);
      border: 1px solid var(--bg-card-border);
      border-radius: 14px;
      padding: 12px 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.45);
      backdrop-filter: blur(14px);
      flex: 1;
      min-height: 0;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      position: relative;
    }

    .ranking-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 20px;
      right: 20px;
      height: 1.5px;
      background: linear-gradient(90deg, transparent, rgba(0, 210, 255, 0.6), transparent);
    }

    .ranking-card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-bottom: 8px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.07);
      margin-bottom: 6px;
      flex-shrink: 0;
    }

    .ranking-card-title-group {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .ranking-card-title {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 0.88rem;
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
      gap: 6px;
      flex: 1;
      min-height: 0;
      justify-content: space-evenly;
    }

    .ranking-item {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 5px 12px;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.05);
      font-size: 0.88rem;
      transition: all 0.2s ease;
      position: relative;
      overflow: hidden;
    }

    .ranking-item:hover {
      background: rgba(0, 168, 255, 0.08);
      border-color: rgba(0, 168, 255, 0.35);
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
      flex: 1;
      min-width: 0;
    }

    .rank-num-name span:last-child {
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .rank-pill {
      width: 22px;
      height: 22px;
      border-radius: 6px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Outfit', sans-serif;
      font-weight: 900;
      font-size: 0.76rem;
      flex-shrink: 0;
    }

    .rank-pill-1 {
      background: linear-gradient(135deg, #f59e0b, #d97706);
      color: #ffffff;
      box-shadow: 0 0 10px rgba(245, 158, 11, 0.5);
    }

    .rank-pill-2 {
      background: linear-gradient(135deg, #94a3b8, #64748b);
      color: #ffffff;
      box-shadow: 0 0 8px rgba(148, 163, 184, 0.4);
    }

    .rank-pill-3 {
      background: linear-gradient(135deg, #b45309, #78350f);
      color: #ffffff;
      box-shadow: 0 0 8px rgba(180, 83, 9, 0.4);
    }

    .rank-pill-default {
      background: rgba(255, 255, 255, 0.07);
      color: var(--neon-blue);
    }

    .badge-percent {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 0.84rem;
      color: #34d399;
      background: rgba(16, 185, 129, 0.12);
      padding: 2px 8px;
      border-radius: 6px;
      border: 1px solid rgba(16, 185, 129, 0.35);
      white-space: nowrap;
      flex-shrink: 0;
      margin-left: 8px;
      box-shadow: 0 0 8px rgba(16, 185, 129, 0.15);
    }

    .badge-time {
      font-family: 'Outfit', sans-serif;
      font-weight: 700;
      font-size: 0.84rem;
      color: #38bdf8;
      background: rgba(56, 189, 248, 0.12);
      padding: 2px 8px;
      border-radius: 6px;
      border: 1px solid rgba(56, 189, 248, 0.35);
      white-space: nowrap;
      flex-shrink: 0;
      margin-left: 8px;
      box-shadow: 0 0 8px rgba(56, 189, 248, 0.15);
    }

    /* Bottom Broadcast News Ticker: Siswa Terlambat */
    .late-students-section {
      background: linear-gradient(90deg, rgba(239, 68, 68, 0.12) 0%, rgba(13, 22, 41, 0.92) 20%, rgba(13, 22, 41, 0.92) 100%);
      border: 1px solid rgba(239, 68, 68, 0.38);
      border-radius: 14px;
      padding: 10px 16px 12px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.5);
      backdrop-filter: blur(14px);
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .late-section-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-shrink: 0;
    }

    .late-section-title-wrap {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .late-section-title {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 0.94rem;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      color: #f87171;
      margin-bottom: 0;
    }

    .late-section-badge {
      background: rgba(239, 68, 68, 0.22);
      border: 1px solid rgba(239, 68, 68, 0.45);
      color: #fca5a5;
      font-size: 0.78rem;
      font-weight: 800;
      padding: 2px 10px;
      border-radius: 12px;
      font-family: 'Outfit', sans-serif;
    }

    .late-cards-container {
      display: flex;
      gap: 14px;
      overflow-x: auto;
      scrollbar-width: none;
      -ms-overflow-style: none;
      scroll-behavior: auto;
      padding: 2px 0;
    }

    .late-cards-container::-webkit-scrollbar {
      display: none;
    }

    .late-student-card {
      min-width: 310px;
      max-width: 340px;
      height: 84px;
      background: linear-gradient(135deg, rgba(20, 30, 52, 0.95) 0%, rgba(9, 15, 27, 0.95) 100%);
      border: 1px solid rgba(239, 68, 68, 0.38);
      border-left: 4px solid #ef4444;
      border-radius: 12px;
      padding: 8px 14px;
      display: flex;
      align-items: center;
      gap: 14px;
      position: relative;
      flex-shrink: 0;
      box-shadow: 0 4px 14px rgba(0, 0, 0, 0.35);
      transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .late-student-card:hover {
      transform: translateY(-1px);
      border-color: rgba(239, 68, 68, 0.85);
    }

    .student-avatar-wrapper {
      position: relative;
      width: 66px;
      height: 66px;
      flex-shrink: 0;
    }

    .student-avatar-img {
      width: 66px;
      height: 66px;
      border-radius: 10px;
      object-fit: cover;
      border: 2px solid #ef4444;
      background: #1e293b;
      display: block;
    }

    .student-avatar-fallback {
      width: 66px;
      height: 66px;
      border-radius: 10px;
      background: linear-gradient(135deg, rgba(239, 68, 68, 0.3), rgba(239, 68, 68, 0.1));
      border: 2px solid #ef4444;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.65rem;
      font-weight: 800;
      color: #fca5a5;
    }

    .point-badge-corner {
      position: absolute;
      top: -5px;
      right: -6px;
      background: #ef4444;
      color: #ffffff;
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 0.74rem;
      padding: 2px 6px;
      border-radius: 8px;
      border: 2px solid #090e1a;
      box-shadow: 0 2px 4px rgba(0,0,0,0.5);
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
      font-size: 0.98rem;
      color: #ffffff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 2px;
      line-height: 1.2;
    }

    .student-card-class {
      font-size: 0.8rem;
      font-weight: 600;
      color: #94a3b8;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 4px;
    }

    .time-badge-late {
      display: inline-flex;
      align-items: center;
      align-self: flex-start;
      gap: 5px;
      background: rgba(239, 68, 68, 0.2);
      border: 1px solid rgba(239, 68, 68, 0.45);
      color: #fca5a5;
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 0.76rem;
      padding: 2px 8px;
      border-radius: 5px;
    }

    .empty-late-state {
      padding: 14px;
      text-align: center;
      width: 100%;
      height: 75px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #34d399;
      font-weight: 600;
      font-size: 0.92rem;
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
        <div class="mr-3 d-flex align-items-center justify-content-center rounded-circle" style="width: 38px; height: 38px; background: var(--neon-blue); color: #050811; font-weight: 900; font-size: 1.1rem; box-shadow: 0 0 12px rgba(0, 210, 255, 0.5);">
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
      
      <!-- Central Video Frame with Ambient Backdrop -->
      <div class="video-screen-frame">
        @if($videoUrl)
          <!-- Ambient blurred background for non-16:9 videos -->
          <video class="video-ambient-backdrop" autoplay muted loop playsinline>
            <source src="{{ $videoUrl }}" type="video/mp4">
          </video>
          <!-- Sharp foreground main video -->
          <video class="video-element-main" id="mainVideoPlayer" autoplay muted loop playsinline>
            <source src="{{ $videoUrl }}" type="video/mp4">
            Video format tidak didukung browser.
          </video>
        @else
          <!-- Default Animated School Display if no custom MP4 uploaded -->
          <div class="video-element-main d-flex align-items-center justify-content-center" style="background: radial-gradient(circle at center, #0f1f3d 0%, #050b18 100%);">
            <div class="text-center p-4">
              <div class="mb-3">
                <i class="fas fa-school text-primary" style="font-size: 4rem; filter: drop-shadow(0 0 20px rgba(0, 168, 255, 0.6));"></i>
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
            <span><i class="far fa-calendar-alt mr-1"></i> <span id="liveDateText">{{ $tanggalFormatted }}</span></span>
            <span style="opacity: 0.4;">|</span>
            <span><i class="far fa-clock mr-1"></i> <span id="liveClockText">{{ $jamFormatted }}</span></span>
          </div>
        </div>
      </div>

      <!-- Right Column: Top 5 Attendance Rankings -->
      <div class="sidebar-ranking-column">
        
        <!-- 1. TOP 5 KEHADIRAN KELAS BULAN INI -->
        <div class="ranking-card">
          <div class="ranking-card-header">
            <div class="ranking-card-title-group">
              <span style="font-size: 1.1rem; filter: drop-shadow(0 0 6px rgba(245, 158, 11, 0.6));">🏆</span>
              <h4 class="ranking-card-title">TOP 5 KEHADIRAN KELAS (BULAN INI)</h4>
            </div>
          </div>
          <ul class="ranking-list" id="topKelasListContainer">
            @forelse($topKelasBulanan as $index => $kelas)
              @php
                $pillClass = $index === 0 ? 'rank-pill-1' : ($index === 1 ? 'rank-pill-2' : ($index === 2 ? 'rank-pill-3' : 'rank-pill-default'));
              @endphp
              <li class="ranking-item">
                <div class="rank-num-name">
                  <span class="rank-pill {{ $pillClass }}">{{ $loop->iteration }}</span>
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
            <div class="ranking-card-title-group">
              <span style="font-size: 1.1rem; color: #38bdf8; filter: drop-shadow(0 0 6px rgba(56, 189, 248, 0.6));"><i class="fas fa-bolt"></i></span>
              <h4 class="ranking-card-title">TOP 5 SISWA TERCEPAT (HARI INI)</h4>
            </div>
          </div>
          <ul class="ranking-list" id="topSiswaTercepatContainer">
            @forelse($topSiswaTercepat as $index => $siswa)
              @php
                $pillClass = $index === 0 ? 'rank-pill-1' : ($index === 1 ? 'rank-pill-2' : ($index === 2 ? 'rank-pill-3' : 'rank-pill-default'));
              @endphp
              <li class="ranking-item">
                <div class="rank-num-name">
                  <span class="rank-pill {{ $pillClass }}">{{ $loop->iteration }}</span>
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

    <!-- Row 2: Bottom Broadcast News Ticker (Siswa Terlambat) -->
    <div class="late-students-section">
      <div class="late-section-header">
        <div class="late-section-title-wrap">
          <i class="fas fa-exclamation-triangle text-danger" style="font-size: 0.95rem; filter: drop-shadow(0 0 6px rgba(239, 68, 68, 0.6));"></i>
          <h4 class="late-section-title">DATA SISWA TERLAMBAT HARI INI</h4>
        </div>
        <span class="late-section-badge"><span id="totalLateCount">{{ $totalTerlambat }}</span> SISWA</span>
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

    // Helper for Rank Pills
    function getPillClass(index) {
      if (index === 0) return 'rank-pill-1';
      if (index === 1) return 'rank-pill-2';
      if (index === 2) return 'rank-pill-3';
      return 'rank-pill-default';
    }

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
              const pClass = getPillClass(i);
              html += `<li class="ranking-item">
                <div class="rank-num-name">
                  <span class="rank-pill ${pClass}">${i + 1}</span>
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
              const pClass = getPillClass(i);
              html += `<li class="ranking-item">
                <div class="rank-num-name">
                  <span class="rank-pill ${pClass}">${i + 1}</span>
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

