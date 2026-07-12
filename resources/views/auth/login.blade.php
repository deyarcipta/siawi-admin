<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" href="{{ asset("storage/gambar/$setting->logo") }}" type="image/x-icon">
  <title>{{ config('app.name', 'Login Sistem Informasi Akademik Wisata Indonesia') }}</title>
  
  <script>
    // Fungsi untuk membuat judul bergerak
    function animateTitle() {
        var title = "{{ config('app.name') }} | Sistem Informasi Akademik SMK Wisata Indonesia | ";
        var speed = 200; // kecepatan pergerakan (ms)
        var charIndex = 0;
        setInterval(function() {
            document.title = title.substring(charIndex) + title.substring(0, charIndex);
            charIndex = (charIndex + 1) % title.length;
        }, speed);
    }
    window.onload = animateTitle;
  </script>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('lte/plugins/fontawesome-free/css/all.min.css') }}">
  
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #1e3a8a 0%, #0f172a 100%);
      --accent-color: #3b82f6;
    }
    
    body, html {
      height: 100%;
      margin: 0;
      font-family: 'Source Sans Pro', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
      background-color: #0f172a;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .login-container {
      display: flex;
      width: 900px;
      height: 550px;
      background: #ffffff;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
      overflow: hidden;
      position: relative;
    }

    /* Left Panel (Branding) */
    .login-left {
      flex: 1;
      background: var(--primary-gradient);
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      color: #ffffff;
      position: relative;
      overflow: hidden;
    }

    /* Glowing Orbs */
    .login-left::before {
      content: '';
      position: absolute;
      top: -10%;
      left: -10%;
      width: 250px;
      height: 250px;
      background: rgba(59, 130, 246, 0.2);
      filter: blur(50px);
      border-radius: 50%;
    }

    .login-left::after {
      content: '';
      position: absolute;
      bottom: -10%;
      right: -10%;
      width: 200px;
      height: 200px;
      background: rgba(99, 102, 241, 0.25);
      filter: blur(55px);
      border-radius: 50%;
    }

    .brand-logo-container {
      display: flex;
      align-items: center;
      gap: 15px;
      z-index: 10;
    }

    .brand-logo {
      width: 50px;
      height: 50px;
      object-fit: contain;
      filter: drop-shadow(0 4px 8px rgba(0,0,0,0.15));
    }

    .brand-name {
      font-size: 1.5rem;
      font-weight: 800;
      letter-spacing: 1px;
    }

    .brand-text {
      z-index: 10;
      margin-top: auto;
      margin-bottom: auto;
    }

    .brand-title {
      font-size: 2rem;
      font-weight: 800;
      line-height: 1.2;
      margin-bottom: 15px;
    }

    .brand-description {
      font-size: 0.95rem;
      color: rgba(255,255,255,0.7);
      line-height: 1.5;
    }

    .brand-footer {
      font-size: 0.8rem;
      color: rgba(255,255,255,0.4);
      z-index: 10;
    }

    /* Right Panel (Form) */
    .login-right {
      flex: 1;
      padding: 50px;
      display: flex;
      flex-direction: column;
      justify-content: center;
      background: #ffffff;
    }

    .login-header {
      margin-bottom: 35px;
    }

    .login-header h2 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #1e293b;
      margin-bottom: 8px;
    }

    .login-header p {
      color: #64748b;
      font-size: 0.95rem;
    }

    /* Form controls styling */
    .form-group-custom {
      position: relative;
      margin-bottom: 24px;
    }

    .form-group-custom i.input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      transition: color 0.2s;
    }

    .form-group-custom .form-control-custom {
      width: 100%;
      padding: 14px 45px 14px 45px;
      border: 1.5px solid #e2e8f0;
      border-radius: 10px;
      outline: none;
      font-size: 0.95rem;
      color: #334155;
      transition: border-color 0.2s, box-shadow 0.2s;
      background-color: #f8fafc;
    }

    .form-group-custom .form-control-custom:focus {
      border-color: var(--accent-color);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
      background-color: #ffffff;
    }

    .form-group-custom .form-control-custom:focus + i.input-icon {
      color: var(--accent-color);
    }

    /* Toggle Password */
    .password-toggle {
      position: absolute;
      right: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      cursor: pointer;
      transition: color 0.2s;
      z-index: 10;
    }
    
    .password-toggle:hover {
      color: #64748b;
    }

    .forgot-link {
      display: inline-block;
      color: #3b82f6;
      font-size: 0.88rem;
      text-decoration: none;
      font-weight: 600;
      margin-top: 5px;
      transition: color 0.2s;
    }

    .forgot-link:hover {
      color: #2563eb;
      text-decoration: underline;
    }

    .btn-submit {
      width: 100%;
      padding: 14px;
      background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
      border: none;
      border-radius: 10px;
      color: #ffffff;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
      transition: transform 0.15s, box-shadow 0.15s, filter 0.2s;
    }

    .btn-submit:hover {
      filter: brightness(1.05);
      box-shadow: 0 6px 18px rgba(37, 99, 235, 0.3);
      transform: translateY(-1px);
    }

    .btn-submit:active {
      transform: translateY(1px);
    }

    .mobile-brand-header {
      display: none;
    }

    /* Responsive screen sizing */
    @media (max-width: 992px) {
      .login-container {
        width: 450px;
        height: 560px;
      }
      .login-left {
        display: none;
      }
      .mobile-brand-header {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 20px;
        gap: 8px;
      }
      .mobile-logo {
        width: 65px;
        height: 65px;
        object-fit: contain;
        filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
      }
      .mobile-app-name {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1e3a8a;
        letter-spacing: 0.5px;
        margin: 0;
      }
    }

    @media (max-width: 576px) {
      body, html {
        background-color: #ffffff;
      }
      .login-container {
        width: 100%;
        height: 100%;
        border-radius: 0;
        box-shadow: none;
      }
      .login-right {
        padding: 30px;
      }
    }
  </style>
</head>
<body>

<div class="login-container">
  <!-- Left Side: Branding -->
  <div class="login-left">
    <div class="brand-logo-container">
      <img src="{{ asset("storage/gambar/$setting->logo") }}" alt="Logo Sekolah" class="brand-logo">
      <span class="brand-name">SIAWI APP</span>
    </div>
    
    <div class="brand-text">
      <h1 class="brand-title">SMK Wisata Indonesia</h1>
      <p class="brand-description">Akses cepat, transparan, dan terintegrasi untuk seluruh layanan akademik sekolah. Silakan masuk untuk mengelola portal Anda.</p>
    </div>
    
    <div class="brand-footer">
      &copy; {{ date('Y') }} SMK Wisata Indonesia. All rights reserved.
    </div>
  </div>
  
  <!-- Right Side: Login Form -->
  <div class="login-right">
    <div class="mobile-brand-header">
      <img src="{{ asset("storage/gambar/$setting->logo") }}" alt="Logo Sekolah" class="mobile-logo">
      <h3 class="mobile-app-name">SIAWI APP</h3>
    </div>

    <div class="login-header">
      <h2>Selamat Datang</h2>
      <p class="mb-0">Masuk untuk mengakses sistem akademik</p>
    </div>
    
    <form action="/login-proses" method="post">
      @csrf
      
      <!-- Username Input -->
      <div class="form-group-custom">
        <input type="text" class="form-control-custom" name="username" placeholder="Masukkan Username" required autocomplete="username">
        <i class="fas fa-user input-icon"></i>
      </div>
      
      <!-- Password Input -->
      <div class="form-group-custom mb-2">
        <input type="password" class="form-control-custom" id="password-field" name="password" placeholder="Masukkan Password" required autocomplete="current-password">
        <i class="fas fa-lock input-icon"></i>
        <i class="fas fa-eye password-toggle" id="toggle-password"></i>
      </div>
      
      <div class="text-right mb-4">
        <a href="#" class="forgot-link">Lupa Password?</a>
      </div>
      
      <button type="submit" class="btn-submit">Sign In</button>
    </form>
  </div>
</div>

<!-- jQuery -->
<script src="{{ asset('lte/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- SweetAlert2 -->
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // Toggle Show/Hide Password
  const togglePassword = document.querySelector('#toggle-password');
  const passwordField = document.querySelector('#password-field');
  
  togglePassword.addEventListener('click', function (e) {
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    this.classList.toggle('fa-eye');
    this.classList.toggle('fa-eye-slash');
  });

  @if ($message = Session::get('success'))
      Swal.fire({
          icon: 'success',
          title: 'Berhasil!',
          text: '{{ $message }}',
          confirmButtonColor: '#3b82f6'
      });
  @endif

  @if ($message = Session::get('failed'))
      Swal.fire({
          icon: 'error',
          title: 'Gagal!',
          text: '{{ $message }}',
          confirmButtonColor: '#3b82f6'
      });
  @endif
</script>
</body>
</html>
