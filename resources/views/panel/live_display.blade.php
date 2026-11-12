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

  <!-- Chart.js v2.9.4 -->
  <script src="{{ asset('lte/plugins/chart.js/Chart.min.js') }}"></script>

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

    /* Stage Layering Inside Video Box */
    .stage-layer {
      position: absolute;
      inset: 0;
      display: flex;
      flex-direction: column;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.55s ease;
      z-index: 1;
    }

    .stage-layer.is-active {
      opacity: 1;
      pointer-events: auto;
      z-index: 5;
    }

    .video-stage-inner {
      justify-content: flex-end;
      background: #020409;
    }

    /* Subtle Top Stage Controls */
    .stage-top-hud {
      position: absolute;
      top: 10px;
      left: 12px;
      right: 12px;
      z-index: 25;
      display: flex;
      align-items: center;
      justify-content: space-between;
      pointer-events: none;
    }

    .stage-mode-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(5, 11, 24, 0.78);
      border: 1px solid rgba(148, 163, 184, 0.28);
      color: #cbd5e1;
      border-radius: 999px;
      padding: 4px 10px;
      font-size: 0.72rem;
      font-weight: 600;
      letter-spacing: 0.04em;
      backdrop-filter: blur(6px);
    }

    .stage-switch-btn {
      pointer-events: auto;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: rgba(5, 11, 24, 0.82);
      border: 1px solid rgba(0, 210, 255, 0.35);
      color: #e2e8f0;
      border-radius: 999px;
      padding: 4px 11px;
      font-size: 0.72rem;
      font-weight: 600;
      cursor: pointer;
      opacity: 0.28;
      transition: opacity 0.2s ease, background 0.2s ease, border-color 0.2s ease;
      backdrop-filter: blur(6px);
    }

    .video-screen-frame:hover .stage-switch-btn,
    .stage-switch-btn:focus-visible {
      opacity: 1;
      outline: none;
    }

    .stage-switch-btn:hover {
      background: rgba(0, 168, 255, 0.22);
      border-color: rgba(0, 210, 255, 0.65);
      color: #ffffff;
    }

    /* Data Visualization Stage Panel */
    .data-stage-panel {
      background: #070d1c;
      padding: 42px 20px 14px;
      gap: 10px;
    }

    .data-stage-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 12px;
      border-bottom: 1px solid rgba(148, 163, 184, 0.16);
      padding-bottom: 8px;
    }

    .data-stage-title {
      font-family: 'Outfit', sans-serif;
      font-size: 1.05rem;
      font-weight: 700;
      color: #f8fafc;
      margin: 0 0 2px;
      line-height: 1.25;
    }

    .data-stage-subtitle {
      font-size: 0.74rem;
      color: #94a3b8;
      margin: 0;
    }

    .data-stage-datetime {
      display: flex;
      align-items: center;
      gap: 8px;
      flex-shrink: 0;
      font-size: 0.76rem;
      font-weight: 600;
      color: #cbd5e1;
      background: rgba(15, 23, 42, 0.85);
      border: 1px solid rgba(148, 163, 184, 0.22);
      border-radius: 8px;
      padding: 5px 10px;
      font-variant-numeric: tabular-nums;
    }

    .data-kpi-strip {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 8px;
    }

    .data-kpi-card {
      background: rgba(15, 23, 42, 0.72);
      border: 1px solid rgba(148, 163, 184, 0.16);
      border-radius: 10px;
      padding: 7px 12px;
      display: flex;
      align-items: baseline;
      justify-content: space-between;
      gap: 8px;
    }

    .data-kpi-label {
      font-size: 0.73rem;
      color: #94a3b8;
      font-weight: 600;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .data-kpi-value {
      font-family: 'Outfit', sans-serif;
      font-size: 1.08rem;
      font-weight: 700;
      color: #f8fafc;
      font-variant-numeric: tabular-nums;
      white-space: nowrap;
    }

    .data-chart-wrap {
      flex: 1;
      min-height: 0;
      position: relative;
      background: rgba(10, 18, 36, 0.75);
      border: 1px solid rgba(148, 163, 184, 0.14);
      border-radius: 10px;
      padding: 10px 12px 6px;
    }

    .data-empty-state {
      position: absolute;
      inset: 0;
      display: none;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      padding: 16px;
      color: #94a3b8;
      font-size: 0.85rem;
    }

    .data-scope-footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      font-size: 0.71rem;
      color: #94a3b8;
      min-height: 16px;
    }

    .stage-legend-inline {
      display: inline-flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
    }

    .stage-legend-item {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      color: #cbd5e1;
    }

    .stage-legend-swatch {
      width: 9px;
      height: 9px;
      border-radius: 2px;
      display: inline-block;
    }

    .stage-progress-track {
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      height: 3px;
      background: rgba(148, 163, 184, 0.16);
      z-index: 30;
    }

    .stage-progress-fill {
      height: 100%;
      width: 0%;
      background: #00d2ff;
      transition: width 0.25s linear;
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
      
      <!-- Central Multi-Stage Box: Video + Non-PKL Attendance Bar & Curve -->
      <div class="video-screen-frame" id="wallboardRotatorFrame">
        <!-- Top Subtle Stage HUD -->
        <div class="stage-top-hud">
          <div class="stage-mode-pill" id="stageModePill">VIDEO 1 / {{ $panelVideoLoop ?? 2 }}</div>
          <button type="button" class="stage-switch-btn" id="btnStageSwitch" title="Ganti tampilan Video / Data Kelas">
            <i class="fas fa-exchange-alt"></i>
            <span id="btnStageSwitchLabel">Lihat Data Kelas</span>
          </button>
        </div>

        <!-- Stage 1: Video Player -->
        <div class="stage-layer video-stage-inner is-active" id="stageVideo">
          @if($videoUrl)
            <!-- Ambient blurred background for non-16:9 videos -->
            <video class="video-ambient-backdrop" id="backdropVideoPlayer" autoplay muted playsinline>
              <source src="{{ $videoUrl }}" type="video/mp4">
            </video>
            <!-- Sharp foreground main video -->
            <video class="video-element-main" id="mainVideoPlayer" autoplay muted playsinline>
              <source src="{{ $videoUrl }}" type="video/mp4">
              Video format tidak didukung browser.
            </video>
          @else
            <!-- Default School Display if no custom MP4 uploaded -->
            <div class="video-element-main d-flex align-items-center justify-content-center" style="background: radial-gradient(circle at center, #0f1f3d 0%, #050b18 100%);">
              <div class="text-center p-4">
                <div class="mb-3">
                  <i class="fas fa-school text-primary" style="font-size: 4rem;"></i>
                </div>
                <h2 class="font-weight-bold text-white mb-1" style="font-family: 'Outfit'; letter-spacing: 0.05em;">{{ $setting->nama_sekolah ?? 'SMK WISATA INDONESIA' }}</h2>
                <p class="text-muted small mb-0">Video belum diunggah. Unggah melalui menu Pengaturan Aplikasi.</p>
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

        <!-- Stage 2: Bar Chart Kehadiran Semua Kelas Hari Ini -->
        <div class="stage-layer data-stage-panel" id="stageBarKelas">
          <div class="data-stage-header">
            <div>
              <h2 class="data-stage-title">Tingkat Kehadiran Siswa Hari Ini per Kelas</h2>
              <p class="data-stage-subtitle" id="barStageSubtitle">
                Rekap kehadiran real-time ({{ $schoolTodaySummary['jumlah_kelas_aktif'] ?? 0 }} kelas aktif)
              </p>
            </div>
            <div class="data-stage-datetime">
              <span id="dataBarDateText">{{ $tanggalFormatted }}</span>
              <span style="opacity: 0.4;">|</span>
              <span id="dataBarClockText">{{ $jamFormatted }}</span>
            </div>
          </div>

          <div class="data-kpi-strip">
            <div class="data-kpi-card">
              <span class="data-kpi-label">Total Siswa</span>
              <span class="data-kpi-value" id="kpiTotalNonPkl">{{ $schoolTodaySummary['total_siswa_non_pkl'] ?? 0 }}</span>
            </div>
            <div class="data-kpi-card">
              <span class="data-kpi-label">Hadir Hari Ini</span>
              <span class="data-kpi-value" style="color: #10b981;" id="kpiHadirNonPkl">{{ $schoolTodaySummary['total_hadir'] ?? 0 }} ({{ number_format($schoolTodaySummary['persen_hadir'] ?? 0, 1) }}%)</span>
            </div>
            <div class="data-kpi-card">
              <span class="data-kpi-label">Terlambat / Belum Absen</span>
              <span class="data-kpi-value" style="color: #f59e0b;" id="kpiTerlambatBelum">{{ $schoolTodaySummary['total_terlambat'] ?? 0 }} / {{ $schoolTodaySummary['total_belum_absen'] ?? 0 }}</span>
            </div>
          </div>

          <div class="data-chart-wrap">
            <canvas id="barKelasChart"></canvas>
            <div class="data-empty-state" id="barKelasEmpty">
              <i class="fas fa-chart-bar mb-2" style="font-size: 1.8rem; color: #64748b;"></i>
              <span>Belum ada data kelas yang aktif untuk ditampilkan hari ini.</span>
            </div>
          </div>

          <div class="data-scope-footer">
            <div class="stage-legend-inline">
              <span class="stage-legend-item"><span class="stage-legend-swatch" style="background: #10b981;"></span> &ge; 90%</span>
              <span class="stage-legend-item"><span class="stage-legend-swatch" style="background: #f59e0b;"></span> 75% - 89%</span>
              <span class="stage-legend-item"><span class="stage-legend-swatch" style="background: #ef4444;"></span> &lt; 75%</span>
            </div>
            <span id="barFullPklNote">Pembaruan otomatis setiap 15 detik</span>
          </div>
        </div>

        <!-- Stage 3: Kurva Tren Kehadiran 7 Hari Aktif Terakhir -->
        <div class="stage-layer data-stage-panel" id="stageCurveTrend">
          <div class="data-stage-header">
            <div>
              <h2 class="data-stage-title">Kurva Tren Kehadiran 7 Hari Aktif Terakhir</h2>
              <p class="data-stage-subtitle">Perbandingan persentase hadir siswa berdasarkan tingkat kelas</p>
            </div>
            <div class="data-stage-datetime">
              <span id="dataCurveDateText">{{ $tanggalFormatted }}</span>
              <span style="opacity: 0.4;">|</span>
              <span id="dataCurveClockText">{{ $jamFormatted }}</span>
            </div>
          </div>

          <div class="data-kpi-strip">
            <div class="data-kpi-card">
              <span class="data-kpi-label">Tingkat X (Hari Terakhir)</span>
              <span class="data-kpi-value" style="color: #38bdf8;" id="kpiLevelX">-</span>
            </div>
            <div class="data-kpi-card">
              <span class="data-kpi-label">Tingkat XI (Hari Terakhir)</span>
              <span class="data-kpi-value" style="color: #10b981;" id="kpiLevelXI">-</span>
            </div>
            <div class="data-kpi-card">
              <span class="data-kpi-label">Tingkat XII (Hari Terakhir)</span>
              <span class="data-kpi-value" style="color: #f59e0b;" id="kpiLevelXII">-</span>
            </div>
          </div>

          <div class="data-chart-wrap">
            <canvas id="curveTrendChart"></canvas>
            <div class="data-empty-state" id="curveTrendEmpty">
              <i class="fas fa-chart-line mb-2" style="font-size: 1.8rem; color: #64748b;"></i>
              <span>Belum tersedia histori presensi untuk 7 hari aktif terakhir.</span>
            </div>
          </div>

          <div class="data-scope-footer">
            <span>Basis: Persentase hadir (tepat waktu + terlambat)</span>
            <span id="curveNextCycleNote">Kembali ke video setelah durasi data selesai</span>
          </div>
        </div>

        <!-- Progress Countdown Bar -->
        <div class="stage-progress-track">
          <div class="stage-progress-fill" id="stageProgressBar"></div>
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
      const formatted = `${hours}:${minutes}:${seconds} WIB`;

      ['liveClockText', 'dataBarClockText', 'dataCurveClockText'].forEach(function(id) {
        const el = document.getElementById(id);
        if (el) el.textContent = formatted;
      });
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
        if (d.tanggalFormatted) {
          ['liveDateText', 'dataBarDateText', 'dataCurveDateText'].forEach(function(id) {
            const el = document.getElementById(id);
            if (el) el.textContent = d.tanggalFormatted;
          });
        }
        if (d.totalTerlambat !== undefined) document.getElementById('totalLateCount').textContent = d.totalTerlambat;
        if (typeof updateWallboardAnalytics === 'function') {
          updateWallboardAnalytics(d);
        }

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

    // 5. Analytics Charts (Non-PKL) & Multi-Stage Video/Data Rotator
    let configVideoLoop = Math.max(1, Number({{ (int) ($panelVideoLoop ?? 2) }}));
    let configDataDuration = Math.max(15, Number({{ (int) ($panelDataDuration ?? 120) }}));
    let analyticsState = {
      allKelasToday: {{ \Illuminate\Support\Js::from($allKelasToday ?? []) }},
      schoolTodaySummary: {{ \Illuminate\Support\Js::from($schoolTodaySummary ?? []) }},
      kehadiranTrend7Days: {{ \Illuminate\Support\Js::from($kehadiranTrend7Days ?? ['labels' => [], 'all' => [], 'X' => [], 'XI' => [], 'XII' => []]) }}
    };

    let barKelasChartInstance = null;
    let curveTrendChartInstance = null;

    const rotatorState = {
      currentStage: 'video', // 'video' | 'bar' | 'curve'
      videoPlayCount: 1,
      dataElapsedMs: 0,
      dataIntervalId: null,
      fallbackTimerId: null
    };

    function getBarColor(pct) {
      if (pct >= 90) return '#10b981';
      if (pct >= 75) return '#f59e0b';
      return '#ef4444';
    }

    function formatPct(val) {
      if (val === null || val === undefined || isNaN(val)) return '-';
      return Number(val).toFixed(1) + '%';
    }

    function latestNonNull(arr) {
      if (!Array.isArray(arr)) return null;
      for (let i = arr.length - 1; i >= 0; i--) {
        if (arr[i] !== null && arr[i] !== undefined) return arr[i];
      }
      return null;
    }

    function renderAnalyticsSummary(summary, trend) {
      const s = summary || {};
      const t = trend || {};

      const elTotal = document.getElementById('kpiTotalNonPkl');
      const elHadir = document.getElementById('kpiHadirNonPkl');
      const elTerlambatBelum = document.getElementById('kpiTerlambatBelum');
      const elSub = document.getElementById('barStageSubtitle');
      const elFullPkl = document.getElementById('barFullPklNote');

      if (elTotal) elTotal.textContent = s.total_siswa_non_pkl ?? 0;
      if (elHadir) elHadir.textContent = `${s.total_hadir ?? 0} (${Number(s.persen_hadir ?? 0).toFixed(1)}%)`;
      if (elTerlambatBelum) elTerlambatBelum.textContent = `${s.total_terlambat ?? 0} / ${s.total_belum_absen ?? 0}`;
      if (elSub) {
        elSub.textContent = `Rekap kehadiran real-time (${s.jumlah_kelas_aktif ?? 0} kelas aktif)`;
      }
      if (elFullPkl) {
        elFullPkl.textContent = 'Pembaruan otomatis setiap 15 detik';
      }

      const elX = document.getElementById('kpiLevelX');
      const elXI = document.getElementById('kpiLevelXI');
      const elXII = document.getElementById('kpiLevelXII');
      if (elX) elX.textContent = formatPct(latestNonNull(t.X));
      if (elXI) elXI.textContent = formatPct(latestNonNull(t.XI));
      if (elXII) elXII.textContent = formatPct(latestNonNull(t.XII));
    }

    const barValueLabelPlugin = {
      afterDatasetsDraw: function(chart) {
        if (!chart || chart.canvas.id !== 'barKelasChart') return;
        const ctx = chart.ctx;
        const dataset = chart.data.datasets[0];
        const meta = chart.getDatasetMeta(0);
        if (!meta || !meta.data) return;

        ctx.save();
        ctx.fillStyle = '#f8fafc';
        ctx.font = (meta.data.length > 14 ? '600 9.5px' : '600 10.5px') + " 'Outfit', sans-serif";
        ctx.textAlign = 'center';
        ctx.textBaseline = 'bottom';

        meta.data.forEach(function(bar, idx) {
          const val = dataset.data[idx];
          if (val === null || val === undefined) return;
          const label = Math.round(Number(val)) + '%';
          ctx.fillText(label, bar._model.x, bar._model.y - 4);
        });
        ctx.restore();
      }
    };

    function renderBarKelasChart(kelasData) {
      const canvas = document.getElementById('barKelasChart');
      const emptyEl = document.getElementById('barKelasEmpty');
      if (!canvas) return;

      const list = Array.isArray(kelasData) ? kelasData : [];
      if (list.length === 0) {
        canvas.style.display = 'none';
        if (emptyEl) emptyEl.style.display = 'flex';
        if (barKelasChartInstance) {
          barKelasChartInstance.destroy();
          barKelasChartInstance = null;
        }
        return;
      }

      canvas.style.display = 'block';
      if (emptyEl) emptyEl.style.display = 'none';
      if (typeof Chart === 'undefined') return;

      const labels = list.map(k => k.nama_kelas);
      const values = list.map(k => Number(k.persen ?? 0));
      const colors = values.map(getBarColor);

      if (barKelasChartInstance) {
        barKelasChartInstance.data.labels = labels;
        barKelasChartInstance.data.datasets[0].data = values;
        barKelasChartInstance.data.datasets[0].backgroundColor = colors;
        barKelasChartInstance.data.datasets[0].borderColor = colors;
        barKelasChartInstance._kelasMetaList = list;
        barKelasChartInstance.update();
        return;
      }

      barKelasChartInstance = new Chart(canvas.getContext('2d'), {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Kehadiran (%)',
            data: values,
            backgroundColor: colors,
            borderColor: colors,
            borderWidth: 1,
            maxBarThickness: 42
          }]
        },
        plugins: [barValueLabelPlugin],
        options: {
          responsive: true,
          maintainAspectRatio: false,
          animation: { duration: 400 },
          layout: { padding: { top: 20, right: 6, left: 2, bottom: 0 } },
          legend: { display: false },
          tooltips: {
            backgroundColor: 'rgba(15, 23, 42, 0.95)',
            borderColor: 'rgba(0, 210, 255, 0.35)',
            borderWidth: 1,
            titleFontColor: '#f8fafc',
            bodyFontColor: '#cbd5e1',
            callbacks: {
              label: function(tooltipItem, data) {
                const idx = tooltipItem.index;
                const item = (barKelasChartInstance && barKelasChartInstance._kelasMetaList)
                  ? barKelasChartInstance._kelasMetaList[idx]
                  : null;
                if (!item) return ` Kehadiran: ${tooltipItem.yLabel}%`;
                return [
                  ` Hadir: ${item.hadir} / ${item.total_non_pkl} siswa (${item.persen}%)`,
                  ` Terlambat: ${item.terlambat} | Belum Absen: ${item.belum_absen}`
                ];
              }
            }
          },
          scales: {
            xAxes: [{
              gridLines: { display: false, drawBorder: true, color: 'rgba(148, 163, 184, 0.16)' },
              ticks: {
                fontColor: '#cbd5e1',
                fontSize: list.length > 14 ? 9.5 : 11,
                autoSkip: false,
                maxRotation: list.length > 10 ? 35 : 0,
                minRotation: 0
              }
            }],
            yAxes: [{
              gridLines: { color: 'rgba(148, 163, 184, 0.14)', zeroLineColor: 'rgba(148, 163, 184, 0.25)' },
              ticks: {
                min: 0,
                max: 100,
                stepSize: 25,
                fontColor: '#94a3b8',
                fontSize: 11,
                callback: function(value) { return value + '%'; }
              }
            }]
          }
        }
      });
      barKelasChartInstance._kelasMetaList = list;
    }

    function renderCurveTrendChart(trendData) {
      const canvas = document.getElementById('curveTrendChart');
      const emptyEl = document.getElementById('curveTrendEmpty');
      if (!canvas) return;

      const t = trendData || {};
      const labels = Array.isArray(t.labels) ? t.labels : [];
      if (labels.length === 0) {
        canvas.style.display = 'none';
        if (emptyEl) emptyEl.style.display = 'flex';
        if (curveTrendChartInstance) {
          curveTrendChartInstance.destroy();
          curveTrendChartInstance = null;
        }
        return;
      }

      canvas.style.display = 'block';
      if (emptyEl) emptyEl.style.display = 'none';
      if (typeof Chart === 'undefined') return;

      const candidates = [
        {
          key: 'all',
          label: 'Semua Kelas',
          borderColor: '#00d2ff',
          backgroundColor: 'rgba(0, 210, 255, 0.10)',
          borderWidth: 3,
          pointRadius: 3.5,
          fill: true,
          lineTension: 0.3,
          spanGaps: true
        },
        {
          key: 'X',
          label: 'Tingkat X',
          borderColor: '#38bdf8',
          backgroundColor: 'transparent',
          borderWidth: 2,
          borderDash: [5, 4],
          pointRadius: 3,
          fill: false,
          lineTension: 0.3,
          spanGaps: true
        },
        {
          key: 'XI',
          label: 'Tingkat XI',
          borderColor: '#10b981',
          backgroundColor: 'transparent',
          borderWidth: 2,
          pointRadius: 3,
          fill: false,
          lineTension: 0.3,
          spanGaps: true
        },
        {
          key: 'XII',
          label: 'Tingkat XII',
          borderColor: '#f59e0b',
          backgroundColor: 'transparent',
          borderWidth: 2,
          pointRadius: 3,
          fill: false,
          lineTension: 0.3,
          spanGaps: true
        }
      ];

      const datasets = [];
      candidates.forEach(function(cfg) {
        const series = Array.isArray(t[cfg.key]) ? t[cfg.key] : [];
        const hasAnyValue = series.some(v => v !== null && v !== undefined);
        if (!hasAnyValue) return;
        datasets.push({
          label: cfg.label,
          data: series,
          borderColor: cfg.borderColor,
          backgroundColor: cfg.backgroundColor,
          borderWidth: cfg.borderWidth,
          borderDash: cfg.borderDash || [],
          pointRadius: cfg.pointRadius,
          pointBackgroundColor: cfg.borderColor,
          fill: cfg.fill,
          lineTension: cfg.lineTension,
          spanGaps: cfg.spanGaps
        });
      });

      if (curveTrendChartInstance) {
        curveTrendChartInstance.data.labels = labels;
        curveTrendChartInstance.data.datasets = datasets;
        curveTrendChartInstance.update();
        return;
      }

      curveTrendChartInstance = new Chart(canvas.getContext('2d'), {
        type: 'line',
        data: { labels: labels, datasets: datasets },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          animation: { duration: 400 },
          layout: { padding: { top: 6, right: 8, left: 2, bottom: 0 } },
          legend: {
            display: true,
            position: 'top',
            labels: {
              fontColor: '#cbd5e1',
              fontSize: 11,
              boxWidth: 14,
              padding: 12
            }
          },
          tooltips: {
            mode: 'index',
            intersect: false,
            backgroundColor: 'rgba(15, 23, 42, 0.95)',
            borderColor: 'rgba(0, 210, 255, 0.35)',
            borderWidth: 1,
            callbacks: {
              label: function(tooltipItem, data) {
                const dsLabel = data.datasets[tooltipItem.datasetIndex].label || '';
                const val = tooltipItem.yLabel;
                return ` ${dsLabel}: ${val !== null && val !== undefined ? val + '%' : '-'}`;
              }
            }
          },
          scales: {
            xAxes: [{
              gridLines: { color: 'rgba(148, 163, 184, 0.10)' },
              ticks: { fontColor: '#cbd5e1', fontSize: 11 }
            }],
            yAxes: [{
              gridLines: { color: 'rgba(148, 163, 184, 0.14)', zeroLineColor: 'rgba(148, 163, 184, 0.25)' },
              ticks: {
                min: 0,
                max: 100,
                stepSize: 25,
                fontColor: '#94a3b8',
                fontSize: 11,
                callback: function(value) { return value + '%'; }
              }
            }]
          }
        }
      });
    }

    function updateWallboardAnalytics(d) {
      if (!d) return;
      if (d.panelVideoLoop !== undefined) {
        configVideoLoop = Math.max(1, Number(d.panelVideoLoop));
        if (rotatorState.currentStage === 'video' && document.getElementById('mainVideoPlayer')) {
          updateHudPill();
        }
      }
      if (d.panelDataDuration !== undefined) {
        configDataDuration = Math.max(15, Number(d.panelDataDuration));
      }
      if (d.allKelasToday !== undefined) analyticsState.allKelasToday = d.allKelasToday;
      if (d.schoolTodaySummary !== undefined) analyticsState.schoolTodaySummary = d.schoolTodaySummary;
      if (d.kehadiranTrend7Days !== undefined) analyticsState.kehadiranTrend7Days = d.kehadiranTrend7Days;

      renderAnalyticsSummary(analyticsState.schoolTodaySummary, analyticsState.kehadiranTrend7Days);
      renderBarKelasChart(analyticsState.allKelasToday);
      renderCurveTrendChart(analyticsState.kehadiranTrend7Days);
    }

    function clearRotatorTimers() {
      if (rotatorState.dataIntervalId) {
        clearInterval(rotatorState.dataIntervalId);
        rotatorState.dataIntervalId = null;
      }
      if (rotatorState.fallbackTimerId) {
        clearTimeout(rotatorState.fallbackTimerId);
        rotatorState.fallbackTimerId = null;
      }
    }

    function setStageProgress(pct) {
      const fill = document.getElementById('stageProgressBar');
      if (fill) {
        const clamped = Math.max(0, Math.min(100, Number(pct) || 0));
        fill.style.width = clamped + '%';
      }
    }

    function activateStageDom(stageName) {
      const map = {
        video: document.getElementById('stageVideo'),
        bar: document.getElementById('stageBarKelas'),
        curve: document.getElementById('stageCurveTrend')
      };
      Object.keys(map).forEach(function(key) {
        if (map[key]) {
          map[key].classList.toggle('is-active', key === stageName);
        }
      });
      rotatorState.currentStage = stageName;
      updateHudPill();

      if (stageName === 'bar' && barKelasChartInstance) {
        setTimeout(() => barKelasChartInstance.resize(), 60);
      } else if (stageName === 'curve' && curveTrendChartInstance) {
        setTimeout(() => curveTrendChartInstance.resize(), 60);
      }
    }

    function updateHudPill() {
      const pill = document.getElementById('stageModePill');
      const btnLabel = document.getElementById('btnStageSwitchLabel');
      const mainVid = document.getElementById('mainVideoPlayer');

      if (rotatorState.currentStage === 'video') {
        if (pill) {
          pill.textContent = mainVid
            ? `VIDEO ${rotatorState.videoPlayCount} / ${configVideoLoop}`
            : 'INFO SEKOLAH';
        }
        if (btnLabel) btnLabel.textContent = 'Lihat Data Kelas';
      } else if (rotatorState.currentStage === 'bar') {
        if (pill) pill.textContent = 'DATA 1 / 2 • PER KELAS';
        if (btnLabel) btnLabel.textContent = 'Ke Kurva Tren';
      } else {
        if (pill) pill.textContent = 'DATA 2 / 2 • KURVA TREN';
        if (btnLabel) btnLabel.textContent = 'Kembali ke Video';
      }
    }

    function pauseAllVideos() {
      const mainVid = document.getElementById('mainVideoPlayer');
      const bgVid = document.getElementById('backdropVideoPlayer');
      if (mainVid && !mainVid.paused) mainVid.pause();
      if (bgVid && !bgVid.paused) bgVid.pause();
    }

    function replayVideosFromStart() {
      const mainVid = document.getElementById('mainVideoPlayer');
      const bgVid = document.getElementById('backdropVideoPlayer');
      if (bgVid) {
        bgVid.currentTime = 0;
        bgVid.play().catch(() => {});
      }
      if (mainVid) {
        mainVid.currentTime = 0;
        mainVid.play().catch(() => {});
      }
    }

    function startVideoStage() {
      clearRotatorTimers();
      rotatorState.videoPlayCount = 1;
      activateStageDom('video');
      setStageProgress(0);

      const mainVid = document.getElementById('mainVideoPlayer');
      if (mainVid) {
        replayVideosFromStart();
      } else {
        const bannerSec = 12;
        const startedAt = Date.now();
        rotatorState.dataIntervalId = setInterval(function() {
          const elapsed = (Date.now() - startedAt) / 1000;
          setStageProgress((elapsed / bannerSec) * 100);
          if (elapsed >= bannerSec) {
            startDataStage('bar');
          }
        }, 250);
      }
    }

    function startDataStage(initialSlide) {
      clearRotatorTimers();
      pauseAllVideos();

      const totalMs = configDataDuration * 1000;
      const barMs = Math.ceil(totalMs / 2);
      rotatorState.dataElapsedMs = initialSlide === 'curve' ? barMs : 0;
      activateStageDom(initialSlide === 'curve' ? 'curve' : 'bar');

      let lastTick = Date.now();
      const noteEl = document.getElementById('curveNextCycleNote');

      function updateDataTick() {
        const now = Date.now();
        rotatorState.dataElapsedMs += (now - lastTick);
        lastTick = now;

        const pct = (rotatorState.dataElapsedMs / totalMs) * 100;
        setStageProgress(pct);

        if (rotatorState.dataElapsedMs >= totalMs) {
          startVideoStage();
          return;
        }

        if (rotatorState.dataElapsedMs >= barMs && rotatorState.currentStage === 'bar') {
          activateStageDom('curve');
        }

        const remainingSec = Math.max(0, Math.ceil((totalMs - rotatorState.dataElapsedMs) / 1000));
        if (noteEl && rotatorState.currentStage === 'curve') {
          noteEl.textContent = `Kembali ke video dalam ${remainingSec} detik`;
        }
      }

      updateDataTick();
      rotatorState.dataIntervalId = setInterval(updateDataTick, 250);
    }

    function initWallboardRotator() {
      const mainVid = document.getElementById('mainVideoPlayer');
      const btnSwitch = document.getElementById('btnStageSwitch');

      if (btnSwitch) {
        btnSwitch.addEventListener('click', function() {
          if (rotatorState.currentStage === 'video') {
            startDataStage('bar');
          } else if (rotatorState.currentStage === 'bar') {
            startDataStage('curve');
          } else {
            startVideoStage();
          }
        });
      }

      if (mainVid) {
        mainVid.addEventListener('timeupdate', function() {
          if (rotatorState.currentStage !== 'video') return;
          if (!mainVid.duration || isNaN(mainVid.duration)) return;
          const progress = ((rotatorState.videoPlayCount - 1) + (mainVid.currentTime / mainVid.duration)) / configVideoLoop;
          setStageProgress(progress * 100);
        });

        mainVid.addEventListener('ended', function() {
          if (rotatorState.currentStage !== 'video') return;
          if (rotatorState.videoPlayCount < configVideoLoop) {
            rotatorState.videoPlayCount += 1;
            updateHudPill();
            replayVideosFromStart();
          } else {
            startDataStage('bar');
          }
        });

        mainVid.addEventListener('error', function() {
          if (rotatorState.currentStage !== 'video') return;
          clearRotatorTimers();
          rotatorState.fallbackTimerId = setTimeout(function() {
            startDataStage('bar');
          }, 5000);
        });
      }

      document.addEventListener('fullscreenchange', function() {
        setTimeout(function() {
          if (barKelasChartInstance) barKelasChartInstance.resize();
          if (curveTrendChartInstance) curveTrendChartInstance.resize();
        }, 120);
      });

      startVideoStage();
    }

    // Inisialisasi saat halaman selesai dimuat
    window.addEventListener('DOMContentLoaded', function() {
      renderAnalyticsSummary(analyticsState.schoolTodaySummary, analyticsState.kehadiranTrend7Days);
      renderBarKelasChart(analyticsState.allKelasToday);
      renderCurveTrendChart(analyticsState.kehadiranTrend7Days);
      initWallboardRotator();
      initAutoScrollLateCards();
    });
  </script>
</body>
</html>

