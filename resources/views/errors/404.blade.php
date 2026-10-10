@extends('errors.layout')

@section('title', '404 Halaman Tidak Ditemukan')

@section('icon')
  <div class="error-icon-wrapper" style="background-color: #eff6ff; color: #2563eb; border: 1px solid #dbeafe;">
    <i class="fas fa-search-location"></i>
  </div>
@endsection

@section('badge')
  <div class="error-code-badge" style="background-color: #dbeafe; color: #1e40af;">
    Status 404 &bull; Tidak Ditemukan
  </div>
@endsection

@section('heading', 'Halaman Tidak Ditemukan')

@section('message')
  <div style="font-weight: 600; color: #1e293b; margin-bottom: 0.35rem;">
    {{ $exception->getMessage() ?: 'Halaman atau data yang Anda cari tidak dapat ditemukan di server kami.' }}
  </div>
  <div style="font-size: 0.82rem; color: #64748b; font-weight: 400;">
    Tautan yang Anda tuju mungkin sudah kedaluwarsa, telah dihapus, atau alamat URL yang dimasukkan salah.
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
