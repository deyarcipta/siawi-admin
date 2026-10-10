@extends('errors.layout')

@section('title', '419 Sesi Kedaluwarsa')

@section('icon')
  <div class="error-icon-wrapper" style="background-color: #fffbeb; color: #d97706; border: 1px solid #fef3c7;">
    <i class="fas fa-hourglass-end"></i>
  </div>
@endsection

@section('badge')
  <div class="error-code-badge" style="background-color: #fef3c7; color: #92400e;">
    Status 419 &bull; Sesi Berakhir
  </div>
@endsection

@section('heading', 'Sesi Anda Telah Berakhir')

@section('message')
  <div style="font-weight: 600; color: #1e293b; margin-bottom: 0.35rem;">
    {{ $exception->getMessage() ?: 'Sesi login Anda telah kedaluwarsa karena tidak ada aktivitas dalam waktu lama.' }}
  </div>
  <div style="font-size: 0.82rem; color: #64748b; font-weight: 400;">
    Silakan muat ulang halaman atau login kembali untuk memperbarui token keamanan aplikasi.
  </div>
@endsection

@section('actions')
  <button type="button" onclick="window.location.reload();" class="error-btn error-btn-primary">
    <i class="fas fa-sync-alt"></i> Muat Ulang Halaman
  </button>
  <a href="{{ route('login') }}" class="error-btn error-btn-secondary">
    <i class="fas fa-sign-in-alt"></i> Login Kembali
  </a>
@endsection
