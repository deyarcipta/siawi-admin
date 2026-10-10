@extends('errors.layout')

@section('title', '403 Akses Ditolak')

@section('icon')
  <div class="error-icon-wrapper" style="background-color: #fef2f2; color: #dc2626; border: 1px solid #fee2e2;">
    <i class="fas fa-user-shield"></i>
  </div>
@endsection

@section('badge')
  <div class="error-code-badge" style="background-color: #fee2e2; color: #991b1b;">
    Status 403 &bull; Akses Ditolak
  </div>
@endsection

@section('heading', 'Akses Tidak Diizinkan')

@section('message')
  <div style="font-weight: 600; color: #1e293b; margin-bottom: 0.35rem;">
    {{ $exception->getMessage() ?: 'Anda tidak memiliki hak akses untuk membuka atau memodifikasi data pada halaman ini.' }}
  </div>
  <div style="font-size: 0.82rem; color: #64748b; font-weight: 400;">
    Pastikan Anda telah masuk dengan akun yang memiliki hak akses yang sesuai, atau kembali ke halaman utama sistem.
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
