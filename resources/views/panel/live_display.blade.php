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

    /* Bottom Broadcast News Ticker: Siswa Terlambat (Taller & Prominent) */
    .late-students-section {
      background: linear-gradient(90deg, rgba(239, 68, 68, 0.14) 0%, rgba(13, 22, 41, 0.94) 20%, rgba(13, 22, 41, 0.94) 100%);
      border: 1px solid rgba(239, 68, 68, 0.42);
      border-radius: 16px;
      padding: 14px 20px 16px;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.55);
      backdrop-filter: blur(14px);
      flex-shrink: 0;
      display: flex;
      flex-direction: column;
      gap: 12px;
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
      gap: 10px;
    }

    .late-section-title {
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 1.1rem;
      letter-spacing: 0.04em;
      text-transform: uppercase;
      color: #f87171;
      margin-bottom: 0;
    }

    .late-section-badge {
      background: rgba(239, 68, 68, 0.25);
      border: 1px solid rgba(239, 68, 68, 0.5);
      color: #fca5a5;
      font-size: 0.88rem;
      font-weight: 800;
      padding: 3px 14px;
      border-radius: 12px;
      font-family: 'Outfit', sans-serif;
    }

    .late-cards-container {
      display: flex;
      gap: 16px;
      overflow-x: auto;
      scrollbar-width: none;
      -ms-overflow-style: none;
      scroll-behavior: auto;
      padding: 2px 0 4px;
    }

    .late-cards-container::-webkit-scrollbar {
      display: none;
    }

    .late-student-card {
      min-width: 360px;
      max-width: 400px;
      height: 114px;
      background: linear-gradient(135deg, rgba(24, 34, 60, 0.95) 0%, rgba(10, 16, 30, 0.95) 100%);
      border: 1px solid rgba(239, 68, 68, 0.42);
      border-left: 5px solid #ef4444;
      border-radius: 14px;
      padding: 12px 18px;
      display: flex;
      align-items: center;
      gap: 16px;
      position: relative;
      flex-shrink: 0;
      box-shadow: 0 4px 18px rgba(0, 0, 0, 0.45);
      transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .late-student-card:hover {
      transform: translateY(-2px);
      border-color: rgba(239, 68, 68, 0.95);
      box-shadow: 0 6px 22px rgba(239, 68, 68, 0.3);
    }

    .student-avatar-wrapper {
      position: relative;
      width: 90px;
      height: 90px;
      flex-shrink: 0;
    }

    .student-avatar-img {
      width: 90px;
      height: 90px;
      border-radius: 12px;
      object-fit: cover;
      border: 2px solid #ef4444;
      background: #1e293b;
      display: block;
      box-shadow: 0 3px 12px rgba(0,0,0,0.5);
    }

    .student-avatar-fallback {
      width: 90px;
      height: 90px;
      border-radius: 12px;
      background: linear-gradient(135deg, rgba(239, 68, 68, 0.35), rgba(239, 68, 68, 0.12));
      border: 2px solid #ef4444;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.2rem;
      font-weight: 800;
      color: #fca5a5;
      box-shadow: 0 3px 12px rgba(0,0,0,0.5);
    }

    .point-badge-corner {
      position: absolute;
      top: -6px;
      right: -6px;
      background: #ef4444;
      color: #ffffff;
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 0.84rem;
      padding: 3px 8px;
      border-radius: 8px;
      border: 2px solid #090e1a;
      box-shadow: 0 2px 6px rgba(0,0,0,0.6);
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
      font-size: 1.15rem;
      color: #ffffff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 4px;
      line-height: 1.2;
    }

    .student-card-class {
      font-size: 0.92rem;
      font-weight: 600;
      color: #94a3b8;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      margin-bottom: 6px;
    }

    .time-badge-late {
      display: inline-flex;
      align-items: center;
      align-self: flex-start;
      gap: 6px;
      background: rgba(239, 68, 68, 0.22);
      border: 1px solid rgba(239, 68, 68, 0.5);
      color: #fca5a5;
      font-family: 'Outfit', sans-serif;
      font-weight: 800;
      font-size: 0.88rem;
      padding: 3px 12px;
      border-radius: 6px;
    }

    .empty-late-state {
      padding: 18px;
      text-align: center;
      width: 100%;
      height: 100px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #34d399;
      font-weight: 600;
      font-size: 1.05rem;
      background: rgba(16, 185, 129, 0.05);
      border-radius: 12px;
      border: 1px dashed rgba(16, 185, 129, 0.3);
    }

    /* Floating Toast Notification Container (Top-Center) */
    .attendance-toast-container {
      position: fixed;
      top: 68px;
      left: 50%;
      transform: translateX(-50%);
      z-index: 99999;
      display: flex;
      flex-direction: column;
      gap: 12px;
      pointer-events: none;
      max-width: 480px;
      width: calc(100% - 48px);
      align-items: center;
    }

    .attendance-toast-card {
      background: rgba(8, 14, 28, 0.96);
      backdrop-filter: blur(24px);
      border-radius: 18px;
      padding: 14px 20px;
      display: flex;
      align-items: center;
      gap: 16px;
      box-shadow: 0 14px 45px rgba(0, 0, 0, 0.75), 0 0 35px rgba(0, 210, 255, 0.35);
      border: 2px solid rgba(0, 210, 255, 0.6);
      animation: toastSlideDown 0.45s cubic-bezier(0.16, 1, 0.3, 1) forwards;
      transition: all 0.4s ease;
      position: relative;
      overflow: hidden;
      pointer-events: auto;
      width: 100%;
    }

    .attendance-toast-card.status-masuk {
      border-color: #10b981;
      box-shadow: 0 14px 45px rgba(0, 0, 0, 0.75), 0 0 35px rgba(16, 185, 129, 0.45);
    }

    .attendance-toast-card.status-terlambat {
      border-color: #ef4444;
      box-shadow: 0 14px 45px rgba(0, 0, 0, 0.75), 0 0 35px rgba(239, 68, 68, 0.45);
    }

    .attendance-toast-card.status-pulang {
      border-color: #00d2ff;
      box-shadow: 0 14px 45px rgba(0, 0, 0, 0.75), 0 0 35px rgba(0, 210, 255, 0.45);
    }

    .attendance-toast-card.toast-hiding {
      animation: toastFadeUp 0.4s cubic-bezier(0.7, 0, 0.84, 0) forwards;
    }

    /* Clean Cross-Fade Transition for Top Rankings */
    .ranking-slide-layer {
      flex: 1;
      min-height: 0;
      display: flex;
      flex-direction: column;
      transition: opacity 0.35s ease;
      width: 100%;
    }

    .ranking-slide-layer.slide-hidden {
      display: none !important;
      opacity: 0;
    }

    .ranking-slide-layer.slide-visible {
      display: flex !important;
      opacity: 1;
    }

    .toast-avatar {
      width: 62px;
      height: 62px;
      border-radius: 12px;
      object-fit: cover;
      flex-shrink: 0;
      border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .toast-avatar-fallback {
      width: 62px;
      height: 62px;
      border-radius: 12px;
      background: linear-gradient(135deg, rgba(0, 210, 255, 0.25), rgba(99, 102, 241, 0.25));
      border: 2px solid rgba(0, 210, 255, 0.4);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.6rem;
      font-weight: 800;
      color: #ffffff;
      flex-shrink: 0;
    }

    .toast-content {
      flex: 1;
      min-width: 0;
    }

    .toast-badge-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 3px 10px;
      border-radius: 20px;
      font-size: 0.72rem;
      font-weight: 800;
      letter-spacing: 0.05em;
      text-transform: uppercase;
      margin-bottom: 4px;
    }

    .toast-badge-pill.pill-masuk {
      background: rgba(16, 185, 129, 0.2);
      color: #34d399;
      border: 1px solid rgba(16, 185, 129, 0.5);
    }

    .toast-badge-pill.pill-terlambat {
      background: rgba(239, 68, 68, 0.2);
      color: #fca5a5;
      border: 1px solid rgba(239, 68, 68, 0.5);
    }

    .toast-badge-pill.pill-pulang {
      background: rgba(0, 210, 255, 0.2);
      color: #38bdf8;
      border: 1px solid rgba(0, 210, 255, 0.5);
    }

    .toast-name {
      font-size: 1.05rem;
      font-weight: 800;
      color: #ffffff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      line-height: 1.2;
      margin-bottom: 2px;
    }

    .toast-meta {
      font-size: 0.82rem;
      color: #94a3b8;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .toast-progress-bar {
      position: absolute;
      bottom: 0;
      left: 0;
      height: 3px;
      background: linear-gradient(90deg, #00d2ff, #10b981);
      width: 100%;
      animation: toastProgress 5.5s linear forwards;
    }

    @keyframes toastSlideDown {
      0% {
        opacity: 0;
        transform: translateY(-40px) scale(0.92);
      }
      60% {
        opacity: 1;
        transform: translateY(6px) scale(1.02);
      }
      100% {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    @keyframes toastFadeUp {
      0% {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
      100% {
        opacity: 0;
        transform: translateY(-30px) scale(0.92);
      }
    }

    @keyframes toastProgress {
      from { width: 100%; }
      to { width: 0%; }
    }
  </style>
</head>
<body>

  <!-- Container Notifikasi Presensi Melayang (Toast) -->
  <div class="attendance-toast-container" id="attendanceToastContainer"></div>

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
        
        <!-- 1. TOP 5 KEHADIRAN KELAS & SISWA TERAJIN (CLEAN AUTO-FADE) -->
        <div class="ranking-card" id="cardRankingRotator">
          <div class="ranking-card-header">
            <div class="ranking-card-title-group" id="slideHeaderGroup" style="transition: opacity 0.35s ease;">
              <span id="slideIcon" style="font-size: 1.1rem; filter: drop-shadow(0 0 6px rgba(245, 158, 11, 0.6));">🏆</span>
              <h4 class="ranking-card-title" id="slideTitle">TOP 5 KEHADIRAN KELAS (BULAN INI)</h4>
            </div>
          </div>

          <!-- Slide 1: Kelas -->
          <div class="ranking-slide-layer slide-visible" id="layerSlideKelas">
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

          <!-- Slide 2: Siswa Terajin Bulan Ini -->
          <div class="ranking-slide-layer slide-hidden" id="layerSlideSiswa">
            <ul class="ranking-list" id="topSiswaBulananContainer">
              @forelse($topSiswaBulanan ?? [] as $index => $sb)
                @php
                  $pillClass = $index === 0 ? 'rank-pill-1' : ($index === 1 ? 'rank-pill-2' : ($index === 2 ? 'rank-pill-3' : 'rank-pill-default'));
                @endphp
                <li class="ranking-item">
                  <div class="rank-num-name">
                    <span class="rank-pill {{ $pillClass }}">{{ $loop->iteration }}</span>
                    <span title="{{ $sb['nama'] }} ({{ $sb['kelas'] }})">{{ $sb['nama'] }}</span>
                  </div>
                  <div class="d-flex align-items-center" style="gap: 6px;">
                    <span class="badge-time" style="font-size: 0.76rem;" title="Kelas: {{ $sb['kelas'] }}">{{ $sb['kelas'] }}</span>
                    <span class="badge-percent" style="font-size: 0.76rem;">({{ $sb['persen_label'] }})</span>
                  </div>
                </li>
              @empty
                <li class="ranking-item text-center text-muted py-3">
                  <span>Belum ada data siswa bulan ini</span>
                </li>
              @endforelse
            </ul>
          </div>
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
            $repeatCount = count($siswaTerlambatList) <= 2 ? 6 : (count($siswaTerlambatList) <= 4 ? 4 : 2);
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

    // 3. Auto-Transition Halus Card 1: Top Kelas <-> Top Siswa Terajin (Tanpa Dot/Bar)
    let currentSlide = 0;
    const slideData = [
      {
        icon: '🏆',
        title: 'TOP 5 KEHADIRAN KELAS (BULAN INI)',
        showLayer: 'layerSlideKelas',
        hideLayer: 'layerSlideSiswa'
      },
      {
        icon: '⭐',
        title: 'TOP 5 SISWA TERAJIN (BULAN INI)',
        showLayer: 'layerSlideSiswa',
        hideLayer: 'layerSlideKelas'
      }
    ];

    function rotateTopRankings() {
      const headerGroup = document.getElementById('slideHeaderGroup');
      currentSlide = (currentSlide + 1) % slideData.length;
      const target = slideData[currentSlide];

      const showEl = document.getElementById(target.showLayer);
      const hideEl = document.getElementById(target.hideLayer);

      if (headerGroup) headerGroup.style.opacity = '0';
      if (hideEl) hideEl.style.opacity = '0';

      setTimeout(() => {
        const iconEl = document.getElementById('slideIcon');
        const titleEl = document.getElementById('slideTitle');
        if (iconEl) iconEl.textContent = target.icon;
        if (titleEl) titleEl.textContent = target.title;

        if (hideEl) {
          hideEl.classList.remove('slide-visible');
          hideEl.classList.add('slide-hidden');
        }
        if (showEl) {
          showEl.classList.remove('slide-hidden');
          showEl.classList.add('slide-visible');
          showEl.style.opacity = '0';
          setTimeout(() => {
            showEl.style.opacity = '1';
          }, 30);
        }

        if (headerGroup) headerGroup.style.opacity = '1';
      }, 350);
    }

    setInterval(rotateTopRankings, 8000);

    // 4. Background Live Polling (Every 5 seconds) tanpa reload video
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

        // Update Top Siswa Bulanan (Terajin)
        const topSiswaBulananEl = document.getElementById('topSiswaBulananContainer');
        if (topSiswaBulananEl && d.topSiswaBulanan) {
          if (d.topSiswaBulanan.length === 0) {
            topSiswaBulananEl.innerHTML = '<li class="ranking-item text-center text-muted py-3"><span>Belum ada data siswa bulan ini</span></li>';
          } else {
            let html = '';
            d.topSiswaBulanan.forEach((sb, i) => {
              const pClass = getPillClass(i);
              html += `<li class="ranking-item">
                <div class="rank-num-name">
                  <span class="rank-pill ${pClass}">${i + 1}</span>
                  <span title="${sb.nama} (${sb.kelas})">${sb.nama}</span>
                </div>
                <div class="d-flex align-items-center" style="gap: 6px;">
                  <span class="badge-time" style="font-size: 0.76rem;" title="Kelas: ${sb.kelas}">${sb.kelas}</span>
                  <span class="badge-percent" style="font-size: 0.76rem;">(${sb.persen_label})</span>
                </div>
              </li>`;
            });
            topSiswaBulananEl.innerHTML = html;
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

        // Update Siswa Terlambat Hari Ini (Hanya re-render jika data benar-benar berubah untuk mencegah blink)
        const lateContainer = document.getElementById('lateStudentsContainer');
        if (lateContainer && d.siswaTerlambatList) {
          const lateFingerprint = JSON.stringify(d.siswaTerlambatList.map(item => (item.id || item.nama) + '-' + item.jam_masuk + '-' + (item.foto_url || '')));
          if (lateFingerprint !== lastLateStudentsFingerprint) {
            lastLateStudentsFingerprint = lateFingerprint;

            if (d.siswaTerlambatList.length === 0) {
              lateContainer.innerHTML = '<div class="empty-late-state" id="emptyLateState"><i class="fas fa-check-circle mr-2"></i> Tidak ada siswa terlambat pada hari ini. Semua siswa hadir tepat waktu!</div>';
              scrollPos = 0;
              lateContainer.scrollLeft = 0;
            } else {
              let singleSetHtml = '';
              d.siswaTerlambatList.forEach(item => {
                const avatar = item.foto_url 
                  ? `<img src="${item.foto_url}" alt="${item.nama}" class="student-avatar-img">`
                  : `<div class="student-avatar-fallback">${item.inisial}</div>`;

                singleSetHtml += `<div class="late-student-card">
                  <div class="student-avatar-wrapper">
                    ${avatar}
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

              const repeatCount = d.siswaTerlambatList.length <= 2 ? 6 : (d.siswaTerlambatList.length <= 4 ? 4 : 2);
              let fullHtml = '';
              for (let r = 0; r < repeatCount; r++) {
                fullHtml += singleSetHtml;
              }
              lateContainer.innerHTML = fullHtml;
              // Pertahankan posisi scroll saat render ulang agar tidak melompat ke 0
              lateContainer.scrollLeft = scrollPos;
            }
          }
        }

        // Cek Notifikasi Presensi Siswa Baru (Real-time Popup)
        if (d.latestAbsensi && Array.isArray(d.latestAbsensi)) {
          const newAbsensis = d.latestAbsensi.filter(item => !seenAbsensiKeys.has(item.key)).reverse();
          newAbsensis.forEach((item, index) => {
            seenAbsensiKeys.add(item.key);
            setTimeout(() => {
              showAttendanceNotification(item);
            }, index * 350);
          });
        }
      })
      .catch(err => {
        console.warn('Background polling error:', err);
      });
    }

    // Inisialisasi Riwayat Absensi yang sudah tercatat saat awal load
    const seenAbsensiKeys = new Set();
    const initialAbsensi = @json($latestAbsensi ?? []);
    if (Array.isArray(initialAbsensi)) {
      initialAbsensi.forEach(item => {
        if (item && item.key) {
          seenAbsensiKeys.add(item.key);
        }
      });
    }

    // Audio chime untuk notifikasi presensi (Web Audio API tanpa file external)
    function playAttendanceChime(isTerlambat) {
      try {
        const AudioCtx = window.AudioContext || window.webkitAudioContext;
        if (!AudioCtx) return;
        const ctx = new AudioCtx();
        if (ctx.state === 'suspended') {
          ctx.resume();
        }
        const osc = ctx.createOscillator();
        const gain = ctx.createGain();
        osc.type = isTerlambat ? 'triangle' : 'sine';
        
        const now = ctx.currentTime;
        if (isTerlambat) {
          osc.frequency.setValueAtTime(440, now);
          osc.frequency.exponentialRampToValueAtTime(349.23, now + 0.18);
        } else {
          osc.frequency.setValueAtTime(523.25, now);
          osc.frequency.exponentialRampToValueAtTime(659.25, now + 0.12);
          osc.frequency.exponentialRampToValueAtTime(783.99, now + 0.24);
        }

        gain.gain.setValueAtTime(0.2, now);
        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.45);

        osc.connect(gain);
        gain.connect(ctx.destination);
        osc.start(now);
        osc.stop(now + 0.45);
      } catch (err) {}
    }

    // Tampilkan Toast Notifikasi Presensi Melayang
    function showAttendanceNotification(item) {
      const container = document.getElementById('attendanceToastContainer');
      if (!container) return;

      const isTerlambat = item.is_terlambat;
      const isPulang = (item.tipe === 'PULANG');

      let statusClass = 'status-masuk';
      let pillClass = 'pill-masuk';
      let pillIcon = 'fa-check-circle';
      let pillText = 'PRESENSI MASUK';

      if (isPulang) {
        statusClass = 'status-pulang';
        pillClass = 'pill-pulang';
        pillIcon = 'fa-home';
        pillText = 'PRESENSI PULANG';
      } else if (isTerlambat) {
        statusClass = 'status-terlambat';
        pillClass = 'pill-terlambat';
        pillIcon = 'fa-exclamation-triangle';
        pillText = 'MASUK (TERLAMBAT)';
      }

      const avatarHtml = item.foto_url 
        ? `<img src="${item.foto_url}" alt="${item.nama}" class="toast-avatar">`
        : `<div class="toast-avatar-fallback">${item.inisial || 'S'}</div>`;

      const toast = document.createElement('div');
      toast.className = `attendance-toast-card ${statusClass}`;
      toast.innerHTML = `
        ${avatarHtml}
        <div class="toast-content">
          <div class="toast-badge-pill ${pillClass}">
            <i class="fas ${pillIcon}"></i> ${pillText} • ${item.jam} WIB
          </div>
          <div class="toast-name" title="${item.nama}">${item.nama}</div>
          <div class="toast-meta">
            <span><i class="fas fa-graduation-cap"></i> ${item.kelas}</span>
            ${item.nis && item.nis !== '-' ? `<span>• NIS: ${item.nis}</span>` : ''}
          </div>
        </div>
        <div class="toast-progress-bar"></div>
      `;

      container.appendChild(toast);
      playAttendanceChime(isTerlambat);

      // Otomatis tutup setelah 5.5 detik
      setTimeout(() => {
        toast.classList.add('toast-hiding');
        setTimeout(() => {
          toast.remove();
        }, 450);
      }, 5500);
    }

    // Interval background update setiap 5 detik (real-time live panel)
    setInterval(fetchLivePanelData, 5000);

    // Inisialisasi Sidik Jari Data Siswa Terlambat (Mencegah Re-render / Blink Ticker)
    let lastLateStudentsFingerprint = @json(collect($siswaTerlambatList)->map(function($item) {
        return ($item['id'] ?? $item['nama'] ?? '') . '-' . ($item['jam_masuk'] ?? '') . '-' . ($item['foto_url'] ?? '');
    })->values());
    if (typeof lastLateStudentsFingerprint !== 'string') {
      lastLateStudentsFingerprint = JSON.stringify(lastLateStudentsFingerprint);
    }

    // 4. Infinite Seamless Marquee Loop untuk Kartu Siswa Terlambat di TV (Zero Stutter & Zero Blink)
    let isTickerPaused = false;
    let scrollPos = 0;
    const scrollSpeed = 0.8; // Kecepatan gerak kontinu (pixel per frame)

    function initAutoScrollLateCards() {
      const container = document.getElementById('lateStudentsContainer');
      if (!container) return;

      container.addEventListener('mouseenter', () => isTickerPaused = true);
      container.addEventListener('mouseleave', () => isTickerPaused = false);
      container.addEventListener('touchstart', () => isTickerPaused = true, { passive: true });
      container.addEventListener('touchend', () => isTickerPaused = false, { passive: true });

      function tickerStep() {
        if (!isTickerPaused && container) {
          const cards = container.querySelectorAll('.late-student-card');
          if (cards.length >= 2 && container.scrollWidth > container.clientWidth) {
            const halfIndex = Math.floor(cards.length / 2);
            // Mengukur jarak fisik presisi antara set awal ke set duplikat (menghilangkan loncatan pixel/gap)
            const loopDistance = cards[halfIndex].offsetLeft - cards[0].offsetLeft;

            if (loopDistance > 0) {
              scrollPos += scrollSpeed;
              if (scrollPos >= loopDistance) {
                scrollPos -= loopDistance;
              }
              container.scrollLeft = scrollPos;
            }
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

