<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Terjadi Kesalahan') | {{ config('app.name', 'SIAWI | SMK Wisata Indonesia') }}</title>

  @php
    try {
        $appSetting = \App\Models\Setting::find('1');
    } catch (\Throwable $e) {
        $appSetting = null;
    }
  @endphp

  @if($appSetting && !empty($appSetting->logo))
    <link rel="icon" href="{{ asset('storage/gambar/' . $appSetting->logo) }}" type="image/x-icon">
  @endif

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('lte/dist/css/adminlte.min.css') }}">

  <style>
    :root {
      --font-base: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
      --font-display: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    body {
      font-family: var(--font-base);
      background-color: #f8fafc;
      color: #1e293b;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1.5rem;
      margin: 0;
    }

    .error-card {
      background: #ffffff;
      border: 1px solid #e2e8f0;
      border-radius: 16px;
      box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.06), 0 8px 10px -6px rgba(15, 23, 42, 0.04);
      max-width: 580px;
      width: 100%;
      overflow: hidden;
      text-align: center;
      padding: 2.5rem 2rem;
    }

    .error-brand {
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
      margin-bottom: 1.75rem;
      text-decoration: none;
    }

    .error-brand img {
      height: 40px;
      width: auto;
      object-fit: contain;
    }

    .error-brand-text {
      font-family: var(--font-display);
      font-weight: 700;
      font-size: 1.1rem;
      color: #0f172a;
      letter-spacing: -0.01em;
      line-height: 1.2;
      text-align: left;
    }

    .error-brand-sub {
      font-size: 0.75rem;
      color: #64748b;
      font-weight: 500;
    }

    .error-icon-wrapper {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 2.2rem;
      margin-bottom: 1.25rem;
    }

    .error-code-badge {
      font-family: var(--font-display);
      font-weight: 800;
      font-size: 0.85rem;
      letter-spacing: 0.08em;
      text-transform: uppercase;
      padding: 0.25rem 0.75rem;
      border-radius: 9999px;
      display: inline-block;
      margin-bottom: 0.75rem;
    }

    .error-title {
      font-family: var(--font-display);
      font-weight: 700;
      font-size: 1.55rem;
      color: #0f172a;
      margin-bottom: 0.75rem;
      line-height: 1.3;
    }

    .error-message-box {
      background-color: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 10px;
      padding: 0.85rem 1.15rem;
      margin: 1rem 0 1.5rem;
      color: #334155;
      font-size: 0.925rem;
      line-height: 1.5;
      font-weight: 500;
      text-align: center;
      word-break: break-word;
    }

    .error-actions {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      justify-content: center;
      align-items: center;
      margin-top: 1.25rem;
    }

    .error-btn {
      padding: 0.65rem 1.35rem;
      font-weight: 600;
      font-size: 0.875rem;
      border-radius: 8px;
      transition: all 0.15s ease-in-out;
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      text-decoration: none;
      cursor: pointer;
    }

    .error-btn-primary {
      background-color: #1a73e8;
      border: 1px solid #1a73e8;
      color: #ffffff;
    }

    .error-btn-primary:hover {
      background-color: #1557b0;
      border-color: #1557b0;
      color: #ffffff;
    }

    .error-btn-secondary {
      background-color: #ffffff;
      border: 1px solid #cbd5e1;
      color: #334155;
    }

    .error-btn-secondary:hover {
      background-color: #f1f5f9;
      color: #0f172a;
    }

    .error-footer {
      margin-top: 1.75rem;
      padding-top: 1.25rem;
      border-top: 1px solid #f1f5f9;
      font-size: 0.78rem;
      color: #94a3b8;
    }
  </style>
  @yield('styles')
</head>
<body>

  <div class="error-card">
    <a href="{{ url('/') }}" class="error-brand">
      @if($appSetting && !empty($appSetting->logo))
        <img src="{{ asset('storage/gambar/' . $appSetting->logo) }}" alt="Logo">
      @else
        <div style="width: 36px; height: 36px; border-radius: 8px; background: #1a73e8; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
          <i class="fas fa-graduation-cap"></i>
        </div>
      @endif
      <div>
        <div class="error-brand-text">{{ $appSetting->nama_sekolah ?? 'SIAWI Admin' }}</div>
        <div class="error-brand-sub">{{ $appSetting->nama_aplikasi ?? 'Sistem Informasi Akademik' }}</div>
      </div>
    </a>

    <div>
      @yield('icon')
      @yield('badge')
      <h1 class="error-title">@yield('heading')</h1>
      <div class="error-message-box">
        @yield('message')
      </div>
    </div>

    <div class="error-actions">
      @yield('actions')
    </div>

    <div class="error-footer">
      &copy; 2024 {{ $appSetting->nama_sekolah ?? 'SMK Wisata Indonesia' }} &bull; SIAWI Integrated System
    </div>
  </div>

</body>
</html>
