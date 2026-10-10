@extends('errors.layout')

@section('title', '503 Pemeliharaan Sistem')

@section('icon')
  <div class="error-icon-wrapper" style="background-color: #f0f9ff; color: #0284c7; border: 1px solid #e0f2fe;">
    <i class="fas fa-tools"></i>
  </div>
@endsection

@section('badge')
  <div class="error-code-badge" style="background-color: #e0f2fe; color: #075985;">
    Status 503 &bull; Pemeliharaan
  </div>
@endsection

@section('heading', 'Sistem Sedang Dalam Pemeliharaan')

@section('message')
  <div style="font-weight: 600; color: #1e293b; margin-bottom: 0.35rem;">
    {{ $exception->getMessage() ?: 'Aplikasi sedang menjalani pemeliharaan berkala atau peningkatan performa server.' }}
  </div>
  <div style="font-size: 0.82rem; color: #64748b; font-weight: 400;">
    Kami akan segera kembali dalam beberapa saat. Terima kasih atas kesabaran Anda.
  </div>
@endsection

@section('actions')
  <button type="button" onclick="window.location.reload();" class="error-btn error-btn-primary">
    <i class="fas fa-sync-alt"></i> Muat Ulang Halaman
  </button>
@endsection
