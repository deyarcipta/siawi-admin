@extends('errors.layout')

@section('title')
  @yield('code', 'Error') @yield('title', 'Terjadi Kesalahan')
@endsection

@section('icon')
  <div class="error-icon-wrapper" style="background-color: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">
    <i class="fas fa-exclamation-triangle"></i>
  </div>
@endsection

@section('badge')
  <div class="error-code-badge" style="background-color: #e2e8f0; color: #334155;">
    Status @yield('code', 'Error')
  </div>
@endsection

@section('heading')
  @yield('title', 'Pemberitahuan Sistem')
@endsection

@section('message')
  <div style="font-weight: 600; color: #1e293b; margin-bottom: 0.35rem;">
    @yield('message', !empty($exception->getMessage()) ? $exception->getMessage() : 'Permintaan Anda tidak dapat diproses saat ini.')
  </div>
  <div style="font-size: 0.82rem; color: #64748b; font-weight: 400;">
    Silakan kembali ke halaman sebelumnya atau ke menu dashboard aplikasi.
  </div>
@endsection

@section('actions')
  <button type="button" onclick="window.history.length > 1 ? window.history.back() : window.location.href='{{ url('/admin/dashboard') }}'" class="error-btn error-btn-primary">
    <i class="fas fa-arrow-left"></i> Kembali ke Halaman Sebelumnya
  </button>
  <a href="{{ url('/admin/dashboard') }}" class="error-btn error-btn-secondary">
    <i class="fas fa-th-large"></i> Ke Dashboard Utama
  </a>
@endsection
